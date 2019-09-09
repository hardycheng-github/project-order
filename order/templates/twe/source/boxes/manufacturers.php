<?php
/* -----------------------------------------------------------------------------------------
   $Id: manufacturers.php,v 1.4 2004/04/13 20:22:44 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(manufacturers.php,v 1.18 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (manufacturers.php,v 1.9 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
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
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_manufacturers.html', $cache_id) || !$cache) {
	$rebuild = true;

  // include needed funtions
 require_once (DIR_FS_INC.'twe_hide_session_id.inc.php');
	require_once (DIR_FS_INC.'twe_draw_form.inc.php');
	require_once (DIR_FS_INC.'twe_draw_pull_down_menu.inc.php');

	$manufacturers_query = "select distinct m.manufacturers_id, m.manufacturers_name from ".TABLE_MANUFACTURERS." as m, ".TABLE_PRODUCTS." as p where m.manufacturers_id=p.manufacturers_id order by m.manufacturers_name";

	$manufacturers = $db->Execute($manufacturers_query);
	if ($manufacturers->RecordCount() <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
		// Display a list
		$manufacturers_list = '';
		while (!$manufacturers->EOF) {
			$manufacturers_name = ((strlen($manufacturers->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers->fields['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN).'..' : $manufacturers->fields['manufacturers_name']);
			if (isset ($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturers->fields['manufacturers_id']))
				$manufacturers_name = '<b>'.$manufacturers_name.'</b>';
			$manufacturers_list .= '<a href="'.twe_href_link(FILENAME_DEFAULT, 'manufacturers_id='.$manufacturers->fields['manufacturers_id']).'">'.$manufacturers_name.'</a><br>';
			$manufacturers->MoveNext();
		}
		$box_content = $manufacturers_list;
	} else {
		// Display a drop-down
		$manufacturers_array = array ();
		if (MAX_MANUFACTURERS_LIST < 2) {
			$manufacturers_array[] = array ('id' => '', 'text' => PULL_DOWN_DEFAULT);
		}

		while (!$manufacturers->EOF) {
			$manufacturers_name = ((strlen($manufacturers->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers->fields['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN).'..' : $manufacturers->fields['manufacturers_name']);
			$manufacturers_array[] = array ('id' => $manufacturers->fields['manufacturers_id'], 'text' => $manufacturers_name);
			$manufacturers->MoveNext();
		}

		$box_content = twe_draw_form('manufacturers', twe_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get').twe_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $_GET['manufacturers_id'], 'onchange="this.form.submit();" size="'.MAX_MANUFACTURERS_LIST.'" style="width: 100%"').twe_hide_session_id().'</form>';

	}

	$box_smarty->assign('BOX_CONTENT', $box_content);
}

if (!$cache || $rebuild) {
	if (twe_not_null($box_content)) {
			if ($rebuild)  $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_manufacturers.html', $cache_id);
		$box_manufacturers = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers.html', $cache_id);	
 $smarty->assign('manufacturers',$box_manufacturers);
	}
} else {
	$box_manufacturers = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_manufacturers.html', $cache_id);
 $smarty->assign('manufacturers',$box_manufacturers);
}
?>