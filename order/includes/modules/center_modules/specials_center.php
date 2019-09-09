<?php
/* -----------------------------------------------------------------------------------------
   $Id: specials_center.php,v 1.3 2006/05/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping  
    http://www.oldpa.com.tw
    Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.30 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (specials.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
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
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html', $cache_id) || !$cache) {
	$rebuild = true;
  // include needed INCs
  require_once(DIR_FS_INC . 'twe_random_select.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_products_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_short_description.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');
      //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $specials_query_raw = "select p.products_id,
                                pd.products_name,
                                p.products_price,
                                p.products_tax_class_id,
                                p.products_image,
								p.products_fsk18,
								s.expires_date,
                                s.specials_new_products_price from " .
                                TABLE_PRODUCTS . " p, " .
                                TABLE_PRODUCTS_DESCRIPTION . " pd, " .
                                TABLE_SPECIALS . " s
                                where p.products_status = '1'
                                and s.products_id = p.products_id ".$fsk_lock."
                                and p.products_id = pd.products_id
                                ".$group_check."
                                and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                and s.status = '1' order by s.specials_date_added DESC limit " . MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS;
    $specials = $db->Execute($specials_query_raw);
	
$module_content='';
  if ($specials->RecordCount() > 0) {

    $row = 0;
    while (!$specials->EOF) {
      $row++;
      $products_price = twe_get_products_price($specials->fields['products_id'], $price_special=1, $quantity=1);

if ($_SESSION['customers_status']['customers_status_show_price']!='0') {
    if ($_SESSION['customers_status']['customers_fsk18']=='1') {
        if ($specials->fields['products_fsk18']=='0') 
		$buy_now_specials='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $specials->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $specials->fields['products_name'] . TEXT_NOW);
    } else {
        $buy_now_specials='<a href="' . twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=buy_now&BUYproducts_id=' . $specials->fields['products_id'], 'NONSSL') . '">' . twe_image_button('button_buy_now.gif', TEXT_BUY . $specials->fields['products_name'] . TEXT_NOW);
      }
	}else{
	$buy_now_specials='';
	}
	  $image='';
      if ($specials->fields['products_image']!='') {
      $image=DIR_WS_THUMBNAIL_IMAGES . $specials->fields['products_image'];
      }

if(!twe_date_short($specials->fields['expires_date'])) {
$specials_expires_date = '';
}else{
$specials_expires_date = $specials->fields['expires_date'];
}
      $module_content[]=array(
                            'PRODUCTS_ID' => $specials->fields['products_id'],
                            'PRODUCTS_NAME' => $specials->fields['products_name'],
                            'PRODUCTS_PRICE' => $products_price,
                            'PRODUCTS_LINK' => twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials->fields['products_id']),
                            'PRODUCTS_IMAGE'=> $image,
							'BUTTON_BUY_NOW'=>$buy_now_specials,
							'EXPIRES_DATE'=> $specials_expires_date,
                            'PRODUCTS_SHORT_DESCRIPTION' => twe_get_short_description($specials->fields['products_id']));
   
  
     $specials->MoveNext();
  }
  $module_smarty->assign('HEADER', HEADER_SPECIALS);
  $module_smarty->assign('module_content',$module_content);
 }
}
if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html',$cache_id);
  $default_smarty->assign('specials_center',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/specials_center.html',$cache_id);
  $default_smarty->assign('specials_center',$module);
}
?>