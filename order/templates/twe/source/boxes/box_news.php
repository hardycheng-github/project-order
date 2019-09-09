<?php
/*
  $Id: box_news.php,v 1.21 2006/03/26 22:07:52 oldpa Exp $

  TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(information.php,v 1.6 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (content.php,v 1.2 2003/08/21); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_content='';
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$rebuild = false;
$box_smarty->assign('language', $_SESSION['language']);

     if (USE_CACHE=='false') {
	 	$cache=false;
		$box_smarty->caching = 0;
	} else {
		$cache=true;
		$box_smarty->caching = 1;
		$box_smarty->cache_lifetime = CACHE_LIFETIME;
		$box_smarty->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'];
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_news.html', $cache_id) || !$cache) {
	$rebuild = true;

        $listing_sql = "select p.products_id, pd.products_name
                               from
							    " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd,
								" . TABLE_NEWS_PRODUCTS . " p, 
							    " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id
							   and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                               ORDER BY p.products_sort asc limit " . MAX_DISPLAY_INDEX_NEWS;

    $listing = $db->Execute($listing_sql);
	    $box_content=array();
    while (!$listing->EOF) {
	  	  $box_content[]=array('DESC' => $listing->fields['products_name'],
		  					   'LINK'=>twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'newsid=' . $listing->fields['products_id']));
     $listing->MoveNext();
    }
	print_r($box_content);
	$box_smarty->assign('heading_text',HEADER_NEWS);
    $box_smarty->assign('box_content', $box_content);
  }
 if (!$cache || $rebuild) {
	 //echo "<script>alert('".count($box_content)."');</script>";
	if (count($box_content)>0) {
		if ($rebuild)  $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_news.html', $cache_id);
		$box_center_news = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_news.html',$cache_id);
		$smarty->assign('box_news',$box_center_news);
	}
} else {
	$box_center_news = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_news.html', $cache_id);
	$smarty->assign('box_news',$box_center_news);
}
?>