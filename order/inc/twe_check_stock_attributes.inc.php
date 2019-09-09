<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_check_stock_attributes.inc.php,v 1.2 2005/04/21 22:57:19 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_check_stock_attributes.inc.php); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_check_stock_attributes($attribute_id, $products_quantity) {
   global $db;

       $stock_query="SELECT
                                  attributes_stock
                                  FROM ".TABLE_PRODUCTS_ATTRIBUTES."
                                  WHERE products_attributes_id='".$attribute_id."'";
       $stock_data=$db->Execute($stock_query);
    $stock_left = $stock_data->fields['attributes_stock'] - $products_quantity;
    $out_of_stock = '';

    if ($stock_left < 0) {
      $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
  }
 ?>