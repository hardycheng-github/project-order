<?php
/*
  $Id: new_products.php,v 1.1.1.1 2004/08/14 08:01:09 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/

$module_smarty = new smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$module_content='';
$rebuild = false;
$module_smarty->assign('language', $_SESSION['language']);

     if (USE_CACHE=='false') {
	 	$cache=false;
		$module_smarty->caching = 0;
	} else {
		$cache=true;
		$module_smarty->caching = 1;
		$module_smarty->cache_lifetime = CACHE_LIFETIME;
		$module_smarty->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
	}
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html', $cache_id) || !$cache) {
	$rebuild = true;
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_short_description.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  global $db, $fsk_lock,$group_check,$new_products_category_id;
 $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock= ' and p.products_fsk18!=1';
  }
  $group_check= '';
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
   $new_products_query = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_date_added DESC limit " . MAX_DISPLAY_INDEX_NEW_PRODUCTS;
  $new_products = $db->Execute($new_products_query);
  $row = 0;
  $module_content = array();
  while (!$new_products->EOF) {
    $new_products->fields['products_name'] = twe_get_products_name($new_products->fields['products_id']);
	$new_products->fields['products_short_description'] = twe_get_short_description($new_products->fields['products_id']);
    if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
    $buy_now='';
    if ($_SESSION['customers_status']['customers_fsk18']=='1') {
        if ($new_products->fields['products_fsk18']=='0') $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $new_products->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $new_products->fields['products_name'] . TEXT_NOW);
    } else {
        $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $new_products->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $new_products->fields['products_name'] . TEXT_NOW);
    }
    $image='';
    if ($new_products->fields['products_image']!='') {
    $image=DIR_WS_THUMBNAIL_IMAGES . $new_products->fields['products_image'];
    }
    $module_content[]=array(
                            'PRODUCTS_NAME' => $new_products->fields['products_name'],
                            'PRODUCTS_DESCRIPTION' => $new_products->fields['products_short_description'],
                            'PRODUCTS_PRICE' => twe_get_products_price($new_products->fields['products_id'],$price_special=1,$quantity=1),
                            'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products->fields['products_id']),
                            'PRODUCTS_IMAGE' => $image,
                            'BUTTON_BUY_NOW'=>$buy_now);

    } else {

    $image='';
    if ($new_products->fields['products_image']!='') {
    $image=DIR_WS_THUMBNAIL_IMAGES . $new_products->fields['products_image'];
    }
	$module_content[]=array(
							'PRODUCTS_NAME' => $new_products->fields['products_name'],
							'PRODUCTS_DESCRIPTION' => $new_products->fields['products_short_description'],
							'PRODUCTS_PRICE' => twe_get_products_price($new_products->fields['products_id'],$price_special=1,$quantity=1),
							'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products->fields['products_id']),
							'PRODUCTS_IMAGE' => $image);
  }
    $row ++;
   $new_products->MoveNext();
  }
  $module_smarty->assign('module_content',$module_content);
}  
 
 if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/new_products_default.html',$cache_id);
  $default_smarty->assign('new_products',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/center_news.html',$cache_id);
  $default_smarty->assign('new_products',$module);
} 
?>