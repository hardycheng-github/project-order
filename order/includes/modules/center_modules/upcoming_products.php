<?php
/* -----------------------------------------------------------------------------------------
   $Id: upcoming_products.php,v 1.6 2005/04/16 15:01:16 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(upcoming_products.php,v 1.23 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (upcoming_products.php,v 1.7 2003/08/22); www.nextcommerce.org
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
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html', $cache_id) || !$cache) {
	$rebuild = true;
  // include needed functions
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');
 global $db;
  
   $module_content=array();
     if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $expected_query = "select p.products_id,
                                  pd.products_name,
                                  products_date_available as date_expected from " .
                                  TABLE_PRODUCTS . " p, " .
                                  TABLE_PRODUCTS_DESCRIPTION . " pd
                                  where to_days(products_date_available) >= to_days(now())
                                  and p.products_id = pd.products_id
                                  ".$group_check."
                                  and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                  order by " . EXPECTED_PRODUCTS_FIELD . " " . EXPECTED_PRODUCTS_SORT . "
                                  limit " . MAX_DISPLAY_UPCOMING_PRODUCTS;
  $expected = $db->Execute($expected_query);
 if ($expected->RecordCount() >0) {
     $row = 0;
    while (!$expected ->EOF) {
      $row++;
      $module_content[]=array('PRODUCTS_LINK'=>twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $expected->fields['products_id']),
                               'PRODUCTS_NAME'=>$expected->fields['products_name'],
                               'PRODUCTS_DATE'=>twe_date_short($expected->fields['date_expected'])
                               );
   $expected->MoveNext();
    }
  $module_smarty->assign('module_content',$module_content);
  }
  }
if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html',$cache_id);
  $default_smarty->assign('upcoming_products',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/upcoming_products.html',$cache_id);
  $default_smarty->assign('upcoming_products',$module);
}
?>