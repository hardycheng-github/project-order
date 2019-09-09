<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/08/14 08:01:09 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/
?>
<?php 
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  require_once(DIR_FS_INC . 'twe_get_news_products_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_news_short_description.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  global $fsk_lock,$group_check,$new_products_category_id;
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock= ' and p.products_fsk18!=1';
  }
if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $new_products = $db->Execute("select p.products_fsk18, p.products_id, p.products_image  from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_NEWS_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id  ".$fsk_lock." ".$group_check." and p.products_status = '1' order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);

  } else {
   if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
    $new_products = $db->Execute("select p.products_fsk18, p.products_id, p.products_image from " . TABLE_NEWS_PRODUCTS . " p, " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_NEWS_CATEGORIES . " c where c.categories_status='1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ".$group_check." and c.parent_id = '" . $new_products_category_id . "' and p.products_status = '1' ".$fsk_lock." order by p.products_date_added DESC limit " . MAX_DISPLAY_NEW_PRODUCTS);
 
  }
  $row = 0;
  $module_content = array();
  while (!$new_products->EOF) {
    $new_products->fields['products_name'] = twe_get_news_products_name($new_products->fields['products_id']);
	$new_products->fields['products_short_description'] = twe_get_news_short_description($new_products->fields['products_id']);
    if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
    $image='';
    if ($new_products->fields['products_image']!='') {
    $image=DIR_WS_IMAGES.'news_product/' . $new_products->fields['products_image'];
    }
    $module_content[]=array('PRODUCTS_NAME' => $new_products->fields['products_name'],
                            'PRODUCTS_DESCRIPTION' => $new_products->fields['products_short_description'],
                            'PRODUCTS_LINK' => twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'newsid=' . $new_products->fields['products_id']),
                            'PRODUCTS_IMAGE' => $image);

    } else {

    $image='';
    if ($new_products->fields['products_image']!='') {
    $image=DIR_WS_IMAGES.'news_product/' . $new_products->fields['products_image'];
    }
	$module_content[]=array('PRODUCTS_NAME' => $new_products->fields['products_name'],
							'PRODUCTS_DESCRIPTION' => $new_products->fields['products_short_description'],
							'PRODUCTS_LINK' => twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'newsid=' . $new_products->fields['products_id']),
							'PRODUCTS_IMAGE' => $image);
  }
    $row ++;
  $new_products->MoveNext();
  }
   if (sizeof($module_content)>=1)
   {
  $module_smarty->assign('PRODUCTS_HEADER', HEADER_NEWS);
  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  // set cache ID
  if (USE_CACHE=='false') {
  $module_smarty->caching = 0;
  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/news_new_products_default.html');
  } else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/news_new_products.html');
  }
  } else {
  $module_smarty->caching = 1;
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $new_products_category_id.$_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/news_new_products_default.html',$cache_id);
  } else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/news_new_products.html',$cache_id);
  }
  }
  $default_smarty->assign('MODULE_new_products',$module);
  }  
?>