<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_new.php,v 1.13 2005/04/23 20:39:46 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(products_new.php,v 1.25 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (products_new.php,v 1.16 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

               // create smarty elements
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
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html', $cache_id) || !$cache) {
	$rebuild = true;  // include needed function
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_get_short_description.inc.php');
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  $group_check='';
     if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  } 
$products_new_query_raw = "SELECT * FROM
			                                         " . TABLE_PRODUCTS . " p,
			                                         " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE
			                                         p.products_id=pd.products_id 
			                                         " . $group_check . "
			                                         " . $fsk_lock . "
													 and p.products_featured = '1'
			                                         and p.products_status = '1' and pd.language_id = '" . (int) $_SESSION['languages_id'] . "'
			                                         order by p.products_ordered DESC limit " . MAX_DISPLAY_INDEX_FEATURED;
  $products_new = $db->Execute($products_new_query_raw);

$module_content='';
$module_content = array();
  if ($products_new->RecordCount() > 0) {
    while (!$products_new->EOF) {
     $buy_now='';
if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
    if ($_SESSION['customers_status']['customers_fsk18']=='1') {
    if ($products_new->fields['products_fsk18']=='0') $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $products_new->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $products_new->fields['products_name'] . TEXT_NOW);
	    } else {
    $buy_now='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $products_new->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $products_new->fields['products_name'] . TEXT_NOW);
	 }
	 }else{
	$buy_now=''; 
}
    if ($products_new->fields['products_image']!='') {
    $products_image=DIR_WS_THUMBNAIL_IMAGES . $products_new->fields['products_image'];
    }
    $module_content[]=array(
                            'PRODUCTS_NAME' => $products_new->fields['products_name'],
                            'PRODUCTS_DESCRIPTION' => $products_new->fields['products_short_description'],
                            'PRODUCTS_PRICE' => twe_get_products_price($products_new->fields['products_id'],$price_special=1,$quantity=1),
                            'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_new->fields['products_id']),
                            'PRODUCTS_IMAGE' => $products_image,
                            'BUTTON_BUY_NOW'=>$buy_now);
$products_new->MoveNext();
    }
   $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  }
  }
  // set cache ID
  if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html',$cache_id);
  $default_smarty->assign('products_featured',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/products_featured.html',$cache_id);
  $default_smarty->assign('products_featured',$module);
}
?>