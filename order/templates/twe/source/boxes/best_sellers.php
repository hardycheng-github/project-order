<?php
/* -----------------------------------------------------------------------------------------
   $Id: best_sellers.php,v 1.4 2004/03/25 08:31:41 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(best_sellers.php,v 1.20 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (best_sellers.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
// reset var
$box_smarty = new smarty;
$box_content='';
$rebuild = false;

$box_smarty->assign('language', $_SESSION['language']);
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 

     if (USE_CACHE=='false') {
	 	$cache=false;
		$box_smarty->caching = 0;
	} else {
		$cache=true;
		$box_smarty->caching = 1;
		$box_smarty->cache_lifetime = CACHE_LIFETIME;
		$box_smarty->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'].$current_category_id;
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_best_sellers.html', $cache_id) || !$cache) {
	$rebuild = true;

  // include needed functions
  require_once(DIR_FS_INC . 'twe_row_number_format.inc.php');

      //fsk18 lock
  $fsk_lock='';
  if ($_SESSION['customers_status']['customers_fsk18_display']=='0') {
  $fsk_lock=' and p.products_fsk18!=1';
  }
  $group_check='';
    if (GROUP_CHECK=='true') {
   $group_check="and p.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  if (isset($current_category_id) && ($current_category_id > 0)) {
    $best_sellers_query = "select distinct
                                        p.products_id,
                                        p.products_image,
                                        pd.products_name from " .
                                        TABLE_PRODUCTS . " p, " .
                                        TABLE_PRODUCTS_DESCRIPTION . " pd, " .
                                        TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " .
                                        TABLE_CATEGORIES . " c
                                        where p.products_status = '1'
                                        and c.categories_status = '1'
                                        and p.products_ordered > 0
                                        and p.products_id = pd.products_id
                                        and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                        and p.products_id = p2c.products_id ".$fsk_lock."
                                        ".$group_check."
                                        and p2c.categories_id = c.categories_id and '" . $current_category_id . "'
                                        in (c.categories_id, c.parent_id)
                                        order by p.products_ordered desc, pd.products_name limit " . MAX_DISPLAY_BESTSELLERS;
    $best_sellers = $db->Execute($best_sellers_query);
  } else {
    $best_sellers_query = "select distinct
                                        p.products_id,
                                        p.products_image,
                                        pd.products_name from " .
                                        TABLE_PRODUCTS . " p, " .
                                        TABLE_PRODUCTS_DESCRIPTION . " pd, " .
                                        TABLE_CATEGORIES . " c
                                        where p.products_status = '1'
                                        and c.categories_status = '1'
                                        ".$group_check."
                                        and p.products_ordered > 0
                                        and p.products_id = pd.products_id ".$fsk_lock."
                                        and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                        order by p.products_ordered desc,
                                        pd.products_name limit " . MAX_DISPLAY_BESTSELLERS;
	      $best_sellers = $db->Execute($best_sellers_query);			
  }
    if ($best_sellers->RecordCount() >= MIN_DISPLAY_BESTSELLERS) {
    $rows = 0;
    $box_content=array();
    while (!$best_sellers->EOF) {
      $rows++;
      $image='';
      if ($best_sellers->fields['products_image']) $image=DIR_WS_INFO_IMAGES . $best_sellers->fields['products_image'];
      $box_content[]=array(
                           'ID'=> twe_row_number_format($rows),
                           'NAME'=> $best_sellers->fields['products_name'],
                           'IMAGE' => $image,
                           'PRICE'=>twe_get_products_price($best_sellers->fields['products_id'],$price_special=1,$quantity=1),
                           'LINK'=> twe_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers->fields['products_id']));
	   $best_sellers->MoveNext();
    }
	
    $box_smarty->assign('box_content', $box_content);
 }   
} 
 // set cache ID
	 if (!$cache || $rebuild) {
	 	if (count($box_content)>0) {
			if ($rebuild)  $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_best_sellers.html', $cache_id);
			$box_best_sellers = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_best_sellers.html',$cache_id);
    $smarty->assign('best_sellers',$box_best_sellers);
	 	}
	} else {
		$box_best_sellers = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_best_sellers.html', $cache_id);
    $smarty->assign('best_sellers',$box_best_sellers);
	}
 
?>