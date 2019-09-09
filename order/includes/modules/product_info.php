  <?php
/* -----------------------------------------------------------------------------------------
   $Id: product_info.php,v 1.34 2004/04/26 10:31:17 oldpa   Exp $   

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

   //include needed functions

   require_once(DIR_FS_INC . 'twe_get_shipping_status_name.inc.php');
   require_once(DIR_FS_INC . 'twe_check_categories_status.inc.php');
   require_once(DIR_FS_INC . 'twe_date_long.inc.php');
global $group_check, $db;
 $info_smarty = new Smarty;
 $info_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
 $info_smarty->assign('product_height',$product_height);
 $info_smarty->assign('product_width',$product_width);

    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $product_info_query = "select
                                      p.products_fsk18,
                                      p.products_discount_allowed,
                                      p.products_id,
                                      pd.products_name,
                                      pd.products_description,
                                      p.products_model,
                                      p.products_shippingtime,
                                      p.products_quantity,
                                      p.products_weight,
                                      p.products_image,
									  p.products_status,
                                      p.products_ordered,
                                      pd.products_url,
                                      p.products_tax_class_id,
                                      p.products_date_added,
                                      p.products_date_available,
                                      p.manufacturers_id,
                                      p.product_template
                                      from " . TABLE_PRODUCTS . " p,
                                      " . TABLE_PRODUCTS_DESCRIPTION . " pd
                                      where p.products_status = '1'
                                      and p.products_id = '" . (int)$_GET['products_id'] . "'
                                      and pd.products_id = p.products_id
                                      ".$group_check."
                                      and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  	$product_info = $db->Execute($product_info_query);

   if (!$product_info->RecordCount()) {

  $error=TEXT_PRODUCT_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);


  } else {
    if (ACTIVATE_NAVIGATOR=='true') {
    include(DIR_WS_MODULES . 'product_navigator.php');
    }
   $db->Execute("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'");
	$product_info = $db->Execute($product_info_query);

    //fsk18 lock
    if ($_SESSION['customers_status']['customers_fsk18_display']=='0' && $product_info->fields['products_fsk18']=='1') {

  $error=TEXT_PRODUCT_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);


    } else {
		if (twe_get_products_price($product_info->fields['products_id'], $price_special=0, $quantity=1)!='0.00') {
		$products_price=twe_get_products_price($product_info->fields['products_id'], $price_special=1, $quantity=1);
    } else {
		$products_price='';
    }
    // check if customer is allowed to add to cart
    if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
        // fsk18
        if ($_SESSION['customers_status']['customers_fsk18']=='1') {
            if ($product_info->fields['products_fsk18']=='0') {
            $info_smarty->assign('ADD_QTY',twe_draw_input_field('products_qty', '1', 'class="qtyCtl_text"', 'text') . ' ' . twe_draw_hidden_field('products_id', $product_info->fields['products_id']));
            $info_smarty->assign('ADD_CART_BUTTON', twe_image_submit('button_add_cart.png', IMAGE_BUTTON_IN_CART));
			$info_smarty->assign('HOME_BUTTON', '<a href="'.$root_link.'">'.twe_image_button('button_home.png', IMAGE_BUTTON_HOME).'</a>');
		}
        } else {
        $info_smarty->assign('ADD_QTY',twe_draw_input_field('products_qty', '1', 'class="qtyCtl_text"', 'text') . ' ' . twe_draw_hidden_field('products_id', $product_info->fields['products_id']));
        $info_smarty->assign('ADD_CART_BUTTON', twe_image_submit('button_in_cart.gif', IMAGE_BUTTON_IN_CART));
        }
    }

    if ($product_info->fields['products_fsk18']=='1') {
    $info_smarty->assign('PRODUCTS_FSK18','true');
    }
   if (ACTIVATE_SHIPPING_STATUS=='true') {
    $shipping_status=twe_get_shipping_status_name($product_info->fields['products_shippingtime']);
    $info_smarty->assign('SHIPPING_NAME',$shipping_status['name']);
    if ($shipping_status['image']!='')  $info_smarty->assign('SHIPPING_IMAGE','admin/images/icons/'.$shipping_status['image']);
    }
    $info_smarty->assign('FORM_ACTION',twe_href_link(FILENAME_PRODUCT_INFO, twe_get_all_get_params(array('action')) . 'action=add_product'));
    $info_smarty->assign('PRODUCTS_PRICE',$products_price);
	$info_smarty->assign('PRODUCTS_SINGLE_PRICE',$product_info->fields['products_price']);
    $info_smarty->assign('PRODUCTS_ID',$product_info->fields['products_id']);
    $info_smarty->assign('PRODUCTS_NAME',$product_info->fields['products_name']);
    $info_smarty->assign('PRODUCTS_MODEL',$product_info->fields['products_model']);
    $info_smarty->assign('PRODUCTS_QUANTITY',$product_info->fields['products_quantity']);
    $info_smarty->assign('PRODUCTS_WEIGHT',$product_info->fields['products_weight']);
    $info_smarty->assign('PRODUCTS_STATUS',$product_info->fields['products_status']);
    $info_smarty->assign('PRODUCTS_ORDERED',$product_info->fields['products_ordered']);
    $info_smarty->assign('PRODUCTS_PRINT', '<img src="'.DIR_WS_ICONS.'print.gif"  style="cursor:hand" onClick="javascript:window.open(\''.twe_href_link(FILENAME_PRINT_PRODUCT_INFO,'products_id='.$_GET['products_id']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">');
    $info_smarty->assign('PRODUCTS_DESCRIPTION',stripslashes($product_info->fields['products_description']));
    $image='';
    if ($product_info->fields['products_image']!='') {
    $image=twe_href_link(DIR_WS_INFO_IMAGES . $product_info->fields['products_image']);
    }
    $info_smarty->assign('PRODUCTS_IMAGE',$image);
if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') $connector = '/'; else $connector = '&';
	$info_smarty->assign('PRODUCTS_POPUP_LINK','javascript:popupWindow(\'' . twe_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info->fields['products_id'] . $connector . 'imgID=' . '0'). '\')');
    $row = $db->Execute("select image_id, image_nr, image_name from " . TABLE_PRODUCTS_MORE_IMAGES . " where products_id = '" . $product_info->fields['products_id'] ."' ORDER BY image_nr");
	$module_content=array();
	$rows = 0;
    while (!$row->EOF){
	$rows++;
	$more_image='';
	if($row->fields['image_nr'] !=''){
	 $info_smarty->assign('MORE_IMG',true);
	 $more_image=twe_href_link(DIR_WS_INFO_IMAGES . $row->fields['image_name']);
    }
    $module_content[]=array(
                  'PRODUCTS_IMAGE_MORE' => $more_image,
                  'PRODUCTS_POPUP_LINK_MORE' => 'javascript:popupWindow(\'' . twe_href_link(FILENAME_POPUP_IMAGE, 'pID=' . $product_info->fields['products_id'] . $connector . 'imgID='.$row->fields['image_nr']).'\')');
 $row->MoveNext();   
}	
    $info_smarty->assign('module_content',$module_content);

        if ($_SESSION['customers_status']['customers_status_public'] == 1 && $_SESSION['customers_status']['customers_status_discount'] != '0.00') {
      $discount = $_SESSION['customers_status']['customers_status_discount'];
      if ($product_info->fields['products_discount_allowed'] < $_SESSION['customers_status']['customers_status_discount']) $discount = $product_info->fields['products_discount_allowed'];
      if ($discount != '0.00' ) {
        $info_smarty->assign('PRODUCTS_DISCOUNT',$discount . '%');
}
     
    }
if (twe_not_null($product_info->fields['products_url'])) {
    $info_smarty->assign('PRODUCTS_URL',sprintf(TEXT_MORE_INFORMATION, twe_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info->fields['products_url']), 'NONSSL', true, false)));

    }

    if ($product_info->fields['products_date_available'] > date('Y-m-d H:i:s')) {
        $info_smarty->assign('PRODUCTS_DATE_AVIABLE',sprintf(TEXT_DATE_AVAILABLE, twe_date_long($product_info->fields['products_date_available'])));


    } else {
        $info_smarty->assign('PRODUCTS_ADDED',sprintf(TEXT_DATE_ADDED, twe_date_long($product_info->fields['products_date_added'])));

    }

include(DIR_WS_MODULES . 'product_attributes.php');
include(DIR_WS_MODULES . 'product_reviews.php');
     
 if ($_SESSION['customers_status']['customers_status_graduated_prices'] == '1') {
 include(DIR_WS_MODULES.FILENAME_GRADUATED_PRICE);
 }
 include(DIR_WS_MODULES . FILENAME_PRODUCTS_MEDIA);
 include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);

  }
  if ($product_info->fields['product_template']=='' or $product_info->fields['product_template']=='default') {
          $files=array();
          if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_info/')){
          while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/product_info/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
        }
  $product_info->fields['product_template']=$files[0]['id'];
  }
  $info_smarty->assign('hint_content', twe_image_button('hint_product_info.png', HINT_PRODUCT_INFO));
  $info_smarty->assign('product_height', $product_height);
  $info_smarty->assign('product_width', $product_width);
  $info_smarty->assign('image_btn_increase', twe_image_button('button_increase.png', IMAGE_BUTTON_INCREASE));
  $info_smarty->assign('image_btn_decrease', twe_image_button('button_decrease.png', IMAGE_BUTTON_DECREASE));
  $info_smarty->assign('language', $_SESSION['language']);
  // set cache ID
  if (USE_CACHE=='false') {
  $info_smarty->caching = 0;
  $product_info= $info_smarty->fetch(CURRENT_TEMPLATE.'/module/product_info/'.$product_info->fields['product_template']);
  } else {
  $info_smarty->caching = 1;	
  $info_smarty->cache_lifetime=CACHE_LIFETIME;
  $info_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_GET['products_id'].$_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
  $product_info= $info_smarty->fetch(CURRENT_TEMPLATE.'/module/product_info/'.$product_info->fields['product_template'],$cache_id);
  }
  }
  $smarty->assign('main_content',$product_info);
  ?>