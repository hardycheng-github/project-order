<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_categories.inc.php,v 1.2 2005/04/14 16:28:39 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_get_categories.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
  global $db;
    $parent_id = twe_db_prepare_input($parent_id);
    if (!is_array($categories_array)) $categories_array = array();
    $categories_query = "select
                                      c.categories_id,
                                      cd.categories_name
                                      from " . TABLE_CATEGORIES . " c,
                                       " . TABLE_CATEGORIES_DESCRIPTION . " cd
                                       where parent_id = '" . twe_db_input($parent_id) . "'
                                       and c.categories_id = cd.categories_id
                                       and c.categories_status != 0
                                       and cd.language_id = '" . $_SESSION['languages_id'] . "'
                                       order by sort_order, cd.categories_name";
	$categories = $db->Execute(
    $categories_query);							   
    while (!$categories->EOF) {
      $categories_array[] = array('id' => $categories->fields['categories_id'],
                                  'text' => $indent . $categories->fields['categories_name']);

      if ($categories->fields['categories_id'] != $parent_id) {
        $categories_array = twe_get_categories($categories_array, $categories->fields['categories_id'], $indent . '&nbsp;&nbsp;');
      }
	 $categories->MoveNext(); 
    }
    return $categories_array;
  }
 ?>