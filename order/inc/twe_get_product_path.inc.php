<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_product_path.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_product_path.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// Construct a category path to the product
// TABLES: products_to_categories
  function twe_get_product_path($products_id) {
    global $db;
 
    $cPath = '';

    $category_query = "select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1";
    $category = $db->Execute($category_query);
   if ($category->RecordCount() > 0 ) {
      $categories = array();
      twe_get_parent_categories($categories, $category->fields['categories_id']);

      $categories = array_reverse($categories);

      $cPath = implode('_', $categories);

      if (twe_not_null($cPath)) $cPath .= '_';
      $cPath .= $category->fields['categories_id'];
    }

    return $cPath;
  }
 ?>