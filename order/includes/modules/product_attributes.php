  <?php
/* -----------------------------------------------------------------------------------------
   $Id: product_attributes.php,v 1.16 2004/02/21 22:38:22 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(product_info.php,v 1.94 2003/05/04); www.oscommerce.com 
   (c) 2003      nextcommerce (product_info.php,v 1.46 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
   New Attribute Manager v4b                            Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   Cross-Sell (X-Sell) Admin 1                          Autor: Joshua Dechant (dreamscape)
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
$module_smarty=new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');

    $products_attributes_query = "select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$_SESSION['languages_id'] . "'";
    $products_attributes = $db->Execute($products_attributes_query);
    if ($products_attributes->fields['total'] > 0) {
      $products_options_name_query = "select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$_SESSION['languages_id'] . "' order by popt.products_options_name";
      $products_options_name = $db->Execute($products_options_name_query) ;  
        $row = 0;
  	$col = 0;
  	$products_options_data=array();
      while (!$products_options_name ->EOF) {
        $selected = 0;
        $products_options_array = array();

	$products_options_data[$row]=array(
	   				'NAME'=>$products_options_name->fields['products_options_name'],
	   				'ID' => $products_options_name->fields['products_options_id'],
	   				'DATA' =>'');
        $products_options_query = "select pov.products_options_values_id,
                                                 pov.products_options_values_name,
                                                 pa.attributes_model,
                                                 pa.options_values_price,
                                                 pa.price_prefix,
                                                 pa.attributes_stock,
                                                 pa.attributes_model
                                                 from " . TABLE_PRODUCTS_ATTRIBUTES . " pa,
                                                 " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
                                                 where pa.products_id = '" . (int)$_GET['products_id'] . "'
                                                 and pa.options_id = '" . $products_options_name->fields['products_options_id'] . "'
                                                 and pa.options_values_id = pov.products_options_values_id
                                                 and pov.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                                 order by pa.sortorder";
		$products_options = $db->Execute($products_options_query);										 
        $col = 0;
        while (!$products_options->EOF) {
          $products_options_array[] = array('id' => $products_options->fields['products_options_values_id'], 'text' => $products_options->fields['products_options_values_name']);
          if ($products_options->fields['options_values_price'] != '0') {
                 $products_options_array[sizeof($products_options_array)-1]['text'] .=  ' '.$products_options->fields['price_prefix'].' '.twe_get_products_attribute_price($products_options->fields['options_values_price'], $tax_class=$product_info->fields['products_tax_class_id'],$price_special=0,$quantity=1,$prefix=$products_options->fields['price_prefix']).' '.$_SESSION['currency'] ;
          }
          $price='';
          if ($products_options->fields['options_values_price']!='0.00') {
          $price = twe_format_price(twe_get_products_attribute_price($products_options->fields['options_values_price'], $tax_class=$product_info->fields['products_tax_class_id'],$price_special=0,$quantity=1,$prefix=$products_options->fields['price_prefix']),1,false,1);
          }
          $products_options_data[$row]['DATA'][$col]=array(
            						'ID' => $products_options->fields['products_options_values_id'],
            						'TEXT' =>$products_options->fields['products_options_values_name'],
                                    'MODEL' =>$products_options->fields['attributes_model'],
            						'PRICE' =>$price,
            						'PREFIX' =>$products_options->fields['price_prefix']);

        $col++;
       $products_options->MoveNext();  
        }
      $row++;
       $products_options_name->MoveNext();  
      }

    }
  // template query
  $template_query="SELECT
                                options_template
                                FROM ".TABLE_PRODUCTS."
                                WHERE products_id='".$_GET['products_id']."'";
  $template_data=$db->Execute($template_query);
  if ($template_data->fields['options_template']=='' or $template_data->fields['options_template']=='default') {
          $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_options/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_options/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
  $template_data->fields['options_template']=$files[0]['id'];
  }

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('options',$products_options_data);
  // set cache ID
  if (USE_CACHE=='false') {
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/product_options/'.$template_data->fields['options_template']);
  } else {
  $module_smarty->caching = 1;	
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_GET['products_id'].$_SESSION['language'].$_SESSION['customers_status']['customers_status_name'];
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/product_options/'.$template_data->fields['options_template'],$cache_id);
  }
  $info_smarty->assign('MODULE_product_options',$module);
 ?>