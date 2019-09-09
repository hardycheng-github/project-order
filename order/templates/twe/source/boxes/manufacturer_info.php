<?php
/* -----------------------------------------------------------------------------------------
   $Id: manufacturer_info.php,v 1.2 2005/04/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(manufacturer_info.php,v 1.10 2003/02/12); www.oscommerce.com
   (c) 2003	 nextcommerce (manufacturer_info.php,v 1.6 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
  if (isset($_GET['products_id'])) {

$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$content_string='';
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
		$cache_id = $_SESSION['language'].$_GET['products_id'];
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_manufacturers_info.html', $cache_id) || !$cache) {
	$rebuild = true;
    $manufacturer_query = "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$_SESSION['languages_id'] . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$_GET['products_id'] . "' and p.manufacturers_id = m.manufacturers_id";
      $manufacturer = $db->Execute($manufacturer_query);
   if ($manufacturer->RecordCount() > 0 ) {

      $image='';
      if (twe_not_null($manufacturer->fields['manufacturers_image'])) $image=DIR_WS_IMAGES . $manufacturer->fields['manufacturers_image'];
      $box_smarty->assign('IMAGE',$image);
      $box_smarty->assign('NAME',$manufacturer->fields['manufacturers_name']);
      $box_smarty->assign('URL','<a href="' . twe_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer->fields['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer->fields['manufacturers_name']) . '</a>');
      $box_smarty->assign('LINK_MORE','<a href="' . twe_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer->fields['manufacturers_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</a>');
	  }
	}
if (!$cache || $rebuild) {
	if (twe_not_null($manufacturer->fields['manufacturers_name'])) {
			if ($rebuild)  $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_manufacturers_info.html', $cache_id);
		$box_manufacturers_info = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers_info.html', $cache_id);	
 $smarty->assign('manufacturer_info',$box_manufacturers_info);
	}
} else {
	$box_manufacturers_info = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers_info.html', $cache_id);
 $smarty->assign('manufacturer_info',$box_manufacturers_info);
 }
}
?>