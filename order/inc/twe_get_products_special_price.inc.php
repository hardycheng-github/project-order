<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_products_special_price.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_products_special_price.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_products_special_price($product_id) {
     global $db,$fields;
    $product_query = "select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status";
    $product = $db->Execute($product_query);
    return $product->fields['specials_new_products_price'];
  }
 ?>