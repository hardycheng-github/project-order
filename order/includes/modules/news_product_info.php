  <?php
/* -----------------------------------------------------------------------------------------
   $Id: news_product_info.php,v 1.34 2004/04/26 10:31:17 oldpa   Exp $   

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
global $group_check;
 $info_smarty = new Smarty;
 $info_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 

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
                                      p.products_image,
									  p.products_status,
                                      p.products_ordered,
                                      pd.products_url,
                                      p.products_date_added,
                                      p.products_date_available,
                                      p.manufacturers_id,
                                      p.product_template
                                      from " . TABLE_NEWS_PRODUCTS . " p,
                                      " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd
                                      where p.products_status = '1'
                                      and p.products_id = '" . (int)$_GET['newsid'] . "'
                                      and pd.products_id = p.products_id
                                      ".$group_check."
                                      and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
    $product_info = $db->Execute($product_info_query);

  if (!$product_info->RecordCount()) { // product not found in database

  $error=TEXT_PRODUCT_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);


  } else {
    if (ACTIVATE_NAVIGATOR=='true') {
    include(DIR_WS_MODULES . 'product_navigator.php');
    }
    $db->Execute("update " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['newsid'] . "' and language_id = '" . $_SESSION['languages_id'] . "'");

    //fsk18 lock
    if ($_SESSION['customers_status']['customers_fsk18_display']=='0' && $product_info->fields['products_fsk18']=='1') {

  $error=TEXT_PRODUCT_NOT_FOUND;
  include(DIR_WS_MODULES . FILENAME_ERROR_HANDLER);


    } else {
        // check if customer is allowed to add to cart
   
    if ($product_info->fields['products_fsk18']=='1') {
    $info_smarty->assign('PRODUCTS_FSK18','true');
    }
    $info_smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
    $info_smarty->assign('PRODUCTS_ID',$product_info->fields['products_id']);
    $info_smarty->assign('PRODUCTS_NAME',$product_info->fields['products_name']);
    $info_smarty->assign('PRODUCTS_MODEL',$product_info->fields['products_model']);
    $info_smarty->assign('PRODUCTS_PRINT', '<img src="'.DIR_WS_ICONS.'print.gif"  style="cursor:hand" onClick="javascript:window.open(\''.twe_href_link(FILENAME_PRINT_NEWS_PRODUCT_INFO,'newsid='.$_GET['newsid']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">');
    $info_smarty->assign('PRODUCTS_DESCRIPTION',stripslashes($product_info->fields['products_description']));
    $image='';
    if ($product_info->fields['products_image']!='') {
    $image=twe_href_link(DIR_WS_IMAGES.'news_product/' . $product_info->fields['products_image']);
    }
    $info_smarty->assign('PRODUCTS_IMAGE',$image);

       
if (twe_not_null($product_info->fields['products_url'])) {
    $info_smarty->assign('PRODUCTS_URL',sprintf(TEXT_MORE_INFORMATION, twe_href_link(FILENAME_REDIRECT, 'action=url&goto=' . urlencode($product_info->fields['products_url']), 'NONSSL', true, false)));

    }

    if ($product_info->fields['products_date_available'] > date('Y-m-d H:i:s')) {
        $info_smarty->assign('PRODUCTS_DATE_AVIABLE',sprintf(TEXT_DATE_AVAILABLE, twe_date_long($product_info->fields['products_date_available'])));


    } else {
        $info_smarty->assign('PRODUCTS_ADDED',sprintf(TEXT_NEWS_DATE_ADDED, twe_date_long($product_info->fields['products_date_added'])));

    } 
  }
  if ($product_info->fields['product_template']=='' or $product_info->fields['product_template']=='default') {
          $files=array();
          if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_info/')){
          while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_info/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
        }
  $product_info->fields['product_template']=$files[0]['id'];
  }


  $info_smarty->assign('language', $_SESSION['language']);
  // set cache ID
  if (USE_CACHE=='false') {
  $info_smarty->caching = 0;
  $product_info= $info_smarty->fetch(CURRENT_TEMPLATE.'/module/news_product_info/'.$product_info->fields['product_template']);
  } else {
  $info_smarty->caching = 1;	
  $info_smarty->cache_lifetime=CACHE_LIFETIME;
  $info_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_GET['newsid'].$_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
  $product_info= $info_smarty->fetch(CURRENT_TEMPLATE.'/module/news_product_info/'.$product_info->fields['product_template'],$cache_id);
  }
  }
  $smarty->assign('main_content',$product_info);
  ?>