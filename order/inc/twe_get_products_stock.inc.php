<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_products_stock.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_products_stock.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_products_stock($products_id) {
   global $db;
    $products_id = twe_get_prid($products_id);
    $stock_query = "select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'";
    $stock_values = $db->Execute($stock_query);
    return $stock_values->fields['products_quantity'];
  }
 ?>