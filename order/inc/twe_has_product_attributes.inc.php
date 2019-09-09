<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_has_product_attributes.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_has_product_attributes.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// Check if product has attributes
  function twe_has_product_attributes($products_id) {
 global $db; 
    $attributes_query = "select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . $products_id . "'";
    $attributes = $db->Execute($attributes_query);

    if ($attributes->fields['count'] > 0) {
      return true;
    } else {
      return false;
    }
  }
 ?>