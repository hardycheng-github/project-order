<?php
/* -----------------------------------------------------------------------------------------
   $Id: content.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

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
		$cache_id = $_SESSION['language'];
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_content.html', $cache_id) || !$cache) {
	$rebuild = true;

$content_query="SELECT
 					content_id,
 					categories_id,
 					parent_id,
 					content_title,
 					content_group
 					FROM ".TABLE_CONTENT_MANAGER."
 					WHERE languages_id='".(int)$_SESSION['languages_id']."'
 					and file_flag=1 and content_status=1";
  $content_data = $db->Execute($content_query);
					
					
 while (!$content_data->EOF) {
 	
 $content_string .= '<img src="templates/'.CURRENT_TEMPLATE.'/img/icon_arrow3.jpg"> <a href="' . twe_href_link(FILENAME_CONTENT,'coID='.$content_data->fields['content_group']) . '">' . $content_data->fields['content_title'] . '</a><br>';
 $content_data->MoveNext();
 }
  $box_smarty->assign('BOX_CONTENT', $content_string);
  
}
	 if (!$cache || $rebuild) {
	 	if (twe_not_null($content_string)) {
			if ($rebuild)  $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_content.html', $cache_id);
			$box_content = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_content.html',$cache_id);
  $smarty->assign('content',$box_content);
	 	}
	} else {
		$box_content = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_content.html', $cache_id);
  $smarty->assign('content',$box_content);
	}
?>