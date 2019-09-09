<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_currencies_values.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_get_currencies_values.inc.php,v 1.1 2003/08/213); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


function twe_get_currencies_values($code) {
  global $db;
    $currency_values = "select * from " . TABLE_CURRENCIES . " where code = '" . $code . "'";
    $currencie_data=$db->Execute(
    $currency_values);
    return $currencie_data;
  }
 ?>