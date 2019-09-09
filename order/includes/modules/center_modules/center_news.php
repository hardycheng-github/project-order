<?php
/*
  $Id: center_news.php,v 1.21 2005/03/26 22:07:52 oldpa Exp $

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
if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/center_modules/center_news.html', $cache_id) || !$cache) {
	$rebuild = true;
  // include needed functions
  require_once(DIR_FS_INC . 'twe_row_number_format.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');

        $listing_sql = "select p.products_fsk18,
                               p.products_model,
                               pd.products_name,
							   pd.products_viewed, 
                               p.products_image,
							   p.products_last_modified,
							   p.products_date_added,  
                               p.products_id
                               from
							    " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd,
								" . TABLE_NEWS_PRODUCTS . " p, 
							    " . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c
                               where p.products_status = '1'
                               and p.products_id = p2c.products_id
                               and pd.products_id = p2c.products_id                               
                               and pd.language_id = '" . (int)$_SESSION['languages_id'] . "' ".$fsk_lock."
                               ORDER BY p.products_sort ASC limit " . MAX_DISPLAY_INDEX_NEWS;
     $listing = $db->Execute($listing_sql);

  $module_content=array();
  if ($listing->RecordCount() > 0) {
    $rows = 0;
    while (!$listing->EOF) {
      $rows++;   
	
	if (strlen($listing->fields['products_name']) > 20){ 
		$line_title = mb_substr($listing->fields['products_name'],'0','20','UTF-8')." ..."; 
      }else{ 
         $line_title = $listing->fields['products_name']; 
      } 
  $image='';
      if ($listing->fields['products_image']!='') {
      $image=DIR_WS_IMAGES .'news_product/'. $listing->fields['products_image'];
      }else{
      $image=DIR_WS_IMAGES .'pixel_trans.gif';
  	  }
$last_news = '';
if($listing->fields['products_last_modified'] ==''){
$last_news = sprintf(TEXT_NEWS_DATE_ADDED, twe_date_long($listing->fields['products_date_added']));
}else{
$last_news = sprintf(TEXT_NEWS_DATE_ADDED, twe_date_long($listing->fields['products_last_modified']));
}

	  $module_content[]=array(
	                       'VIEWED' => sprintf(TEXT_NEWS_VIEWED, $listing->fields['products_viewed']), 
	                       'LAST_TIME' => $last_news,
	                       'MORE' => READ_MORE_NEWS,
	                       'ID'=> twe_row_number_format($rows),
						   'NAME'=> $line_title,
						   'IMAGE'=> $image,
						   'LINK'=>twe_href_link(FILENAME_NEWS_PRODUCT_INFO, 'newsid=' . $listing->fields['products_id']));
$listing->MoveNext();
    }
	
    $module_smarty->assign('heading_text',HEADER_NEWS);
    $module_smarty->assign('module_content', $module_content);
 }
}

if (!$cache || $rebuild) {
	if (twe_not_null($module_content)) {
			if ($rebuild)  $module_smarty->clear_cache(CURRENT_TEMPLATE.'/module/center_modules/center_news.html', $cache_id);
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/center_news.html',$cache_id);
    $default_smarty->assign('center_news',$module);
	}
} else {
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/center_modules/center_news.html',$cache_id);
    $default_smarty->assign('center_news',$module);
}
?>