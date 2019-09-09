<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_parent_categories.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_parent_categories.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
  function twe_get_parent_categories(&$categories, $categories_id) {
    global $db;
 
    $parent_categories_query = "select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . $categories_id . "'";
   $parent_categories = $db->Execute($parent_categories_query) ;
	while (!$parent_categories->EOF) {
      if ($parent_categories->fields['parent_id'] == 0) return true;
      $categories[sizeof($categories)] = $parent_categories->fields['parent_id'];
      if ($parent_categories->fields['parent_id'] != $categories_id) {
        twe_get_parent_categories($categories, $parent_categories->fields['parent_id']);
      }
	   $parent_categories->MoveNext();  
    }
  }
 ?>