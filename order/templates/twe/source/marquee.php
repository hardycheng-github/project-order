<?php
if(!$smarty) $smarty = new smarty;
$listing_sql = "select p.products_id, pd.products_name from
				" . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd,
				" . TABLE_NEWS_PRODUCTS . " p, 
				" . TABLE_NEWS_PRODUCTS_TO_CATEGORIES . " p2c
			   where p.products_status = '1'
			   and p.products_id = p2c.products_id
			   and pd.products_id = p2c.products_id
			   and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
			   ORDER BY p.products_sort asc limit " . MAX_DISPLAY_INDEX_NEWS;
$listing = $db->Execute($listing_sql);
$marquee_content=array();
while (!$listing->EOF) {
	$marquee_content[]=array(
		'DESC' => $listing->fields['products_name'],
		'LINK'=>twe_href_link(FILENAME_NEWS_PRODUCT_INFO,
		'newsid=' . $listing->fields['products_id'])
	);
	$listing->MoveNext();
}
$smarty->assign('heading_text',HEADER_NEWS);
$smarty->assign('marquee_content', $marquee_content);
$smarty->assign('split_symbol_left', "【");
$smarty->assign('split_symbol_right', "】");
?>