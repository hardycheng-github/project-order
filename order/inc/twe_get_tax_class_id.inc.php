<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_tax_class_id.inc.php,v 1.1 2005/04/31 14:53:53 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
      (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  function twe_get_tax_class_id($products_id) {
global $db;
    $tax_query ="SELECT
                               products_tax_class_id
                               FROM ".TABLE_PRODUCTS."
                               where products_id='".$products_id."'";
    $tax_query_data=$db->Execute(
    $tax_query);

    return $tax_query_data->fields['products_tax_class_id'];
  }
 ?>