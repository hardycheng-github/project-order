<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_currency_exists.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_currency_exists.inc.php); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


function twe_currency_exists($code) {
global $db;
    $code = twe_db_prepare_input($code);

    $currency_code = "select currencies_id from " . TABLE_CURRENCIES . " where code = '" . $code . "'";
    $currency = $db->Execute($currency_code);
  if ($currency->RecordCount()>0) {
      return $code;
    } else {
      return false;
    }
  }
 ?>