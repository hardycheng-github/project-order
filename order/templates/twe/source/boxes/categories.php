<?php
/* -----------------------------------------------------------------------------------------
   $Id: categories.php,v 1.3 2004/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
// reset var
$box_smarty = new smarty;
$rebuild = false;
$categories_string = '';
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
		$cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_id'].$_GET['cPath'];
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_categories.html', $cache_id) || !$cache) {
	$rebuild = true;
  // include needed functions
  require_once(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/inc/twe_show_category.inc.php');
  require_once(DIR_FS_INC . 'twe_has_category_subcategories.inc.php');
  require_once(DIR_FS_INC . 'twe_count_products_in_category.inc.php');
  $group_check = '';
    if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $categories_query = "select c.categories_id,
                                           cd.categories_name,
										   cd.categories_name_size,
                                           c.parent_id from " .
                                           TABLE_CATEGORIES . " c, " .
                                           TABLE_CATEGORIES_DESCRIPTION . " cd
                                           where c.categories_status = '1'
                                           and c.parent_id = '0'
                                           ".$group_check."
                                           and c.categories_id = cd.categories_id
                                           and cd.language_id='" . (int)$_SESSION['languages_id'] ."'
                                           order by sort_order, cd.categories_name";
    $categories = $db->Execute($categories_query);
    while (!$categories->EOF)  {
    $foo[$categories->fields['categories_id']] = array(
                                        'name' => $categories->fields['categories_name'],
										'size' => $categories->fields['categories_name_size'],
                                        'parent' => $categories->fields['parent_id'],
                                        'level' => 0,
                                        'path' => $categories->fields['categories_id'],
                                        'next_id' => false);

    if (isset($prev_id)) {
      $foo[$prev_id]['next_id'] = $categories->fields['categories_id'];
    }

    $prev_id = $categories->fields['categories_id'];

    if (!isset($first_element)) {
      $first_element = $categories->fields['categories_id'];
    }
     $categories->MoveNext();
  }

  //------------------------
  if ($cPath) {
    $new_path = '';
//    $id = preg_split('_', $cPath);
 $id = preg_split('/_/i', $cPath);
    reset($id);
    while (list($key, $value) = each($id)) {
      unset($prev_id);
      unset($first_id);
      $categories_query = "select c.categories_id, cd.categories_name, cd.categories_name_size, c.parent_id from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $_SESSION['languages_id'] ."' order by sort_order, cd.categories_name";
      $row = $db->Execute($categories_query);
      if ($row->RecordCount()>0) {
       $new_path .= $value;
      while (!$row->EOF) {	  
	            $foo[$row->fields['categories_id']] = array(
                                              'name' => $row->fields['categories_name'],
											  'size' => $row->fields['categories_name_size'],
                                              'parent' => $row->fields['parent_id'],
                                              'level' => $key+1,
                                              'path' => $new_path . '_' . $row->fields['categories_id'],
                                              'next_id' => false);
		 if (isset($prev_id)) {
            $foo[$prev_id]['next_id'] = $row->fields['categories_id'];
          }

          $prev_id = $row->fields['categories_id'];

          if (!isset($first_id)) {
            $first_id = $row->fields['categories_id'];
          }

          $last_id = $row->fields['categories_id'];
		 $row->MoveNext();
        }
        $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
        $foo[$value]['next_id'] = $first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
  twe_show_category($first_element); 
    $box_smarty->assign('BOX_CONTENT', $categories_string.'</td></tr></table>');
 }  
 if (!$cache || $rebuild) {
  if(twe_not_null($categories_string)){
	if ($rebuild) $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_categories.html', $cache_id);
	$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories.html',$cache_id);
	}
} else {
	$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_categories.html', $cache_id);
}

    $smarty->assign('categories',$box_categories);
  ?>
