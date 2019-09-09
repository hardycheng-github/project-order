<?php
/* -----------------------------------------------------------------------------------------
   $Id: news_categories.php,v 1.3 2005/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
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
		$cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_id'].$_GET['news_cPath'];
	}
if (!$box_smarty->is_cached(CURRENT_TEMPLATE.'/boxes/box_news_categories.html', $cache_id) || !$cache) {
	$rebuild = true;
  // include needed functions
  require_once(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/inc/twe_show_news.inc.php');
  require_once(DIR_FS_INC . 'twe_news_has_category_subcategories.inc.php');
  require_once(DIR_FS_INC . 'twe_news_count_products_in_category.inc.php');


  $news_categories_string = '';
    if (GROUP_CHECK=='true') {
   $group_check="and c.group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
  }
  $categories = $db->Execute("select c.categories_id,
                                           cd.categories_name,
                                           c.parent_id from " .
                                           TABLE_NEWS_CATEGORIES . " c, " .
                                           TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd
                                           where c.categories_status = '1'
                                           and c.parent_id = '0'
                                           ".$group_check."
                                           and c.categories_id = cd.categories_id
                                           and cd.language_id='" . (int)$_SESSION['languages_id'] ."'
                                           order by sort_order, cd.categories_name");

  while (!$categories->EOF)  {
    $news_foo[$categories->fields['categories_id']] = array(
                                        'name' => $categories->fields['categories_name'],
                                        'parent' => $categories->fields['parent_id'],
                                        'level' => 0,
                                        'path' => $categories->fields['categories_id'],
                                        'next_id' => false);

    if (isset($news_prev_id)) {
      $news_foo[$news_prev_id]['next_id'] = $categories->fields['categories_id'];
    }

    $news_prev_id = $categories->fields['categories_id'];

    if (!isset($first_news_element)) {
      $first_news_element = $categories->fields['categories_id'];
    }
$categories->MoveNext();	
  }

  //------------------------
  if ($news_cPath) {
    $new_path = '';
//    $news_id = preg_split('_', $news_cPath);
     $news_id = preg_split('/_/i', $news_cPath);
    reset($news_id);
    while (list($key, $value) = each($news_id)) {
      unset($news_prev_id);
      unset($news_first_id);
      $categories_query = "select c.categories_id, cd.categories_name, c.parent_id from " . TABLE_NEWS_CATEGORIES . " c, " . TABLE_NEWS_CATEGORIES_DESCRIPTION . " cd where c.categories_status = '1' and c.parent_id = '" . $value . "' and c.categories_id = cd.categories_id and cd.language_id='" . $_SESSION['languages_id'] ."' order by sort_order, cd.categories_name";
      $row = $db->Execute($categories_query);
      if ($row->RecordCount() > 0) {
        $new_path .= $value;
        while (!$row->EOF) {
          $news_foo[$row->fields['categories_id']] = array(
                                              'name' => $row->fields['categories_name'],
                                              'parent' => $row->fields['parent_id'],
                                              'level' => $key+1,
                                              'path' => $new_path . '_' . $row->fields['categories_id'],
                                              'next_id' => false);

          if (isset($news_prev_id)) {
            $news_foo[$news_prev_id]['next_id'] = $row->fields['categories_id'];
          }

          $news_prev_id = $row->fields['categories_id'];

          if (!isset($news_first_id)) {
            $news_first_id = $row->fields['categories_id'];
          }

          $news_last_id = $row->fields['categories_id'];
		$row->MoveNext();  
        }
        $news_foo[$news_last_id]['next_id'] = $news_foo[$value]['next_id'];
        $news_foo[$value]['next_id'] = $news_first_id;
        $new_path .= '_';
      } else {
        break;
      }
    }
  }
    twe_show_news_category($first_news_element);
    $box_smarty->assign('BOX_CONTENT', $news_categories_string.'</td></tr></table>');
}

 if (!$cache || $rebuild) {
 if(twe_not_null($news_categories_string)){
	if ($rebuild) $box_smarty->clear_cache(CURRENT_TEMPLATE.'/boxes/box_news_categories.html', $cache_id);
	$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_news_categories.html',$cache_id);
	}
} else {
	$box_categories = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_news_categories.html', $cache_id);
}
$smarty->assign('news_categories',$box_categories);
?>
