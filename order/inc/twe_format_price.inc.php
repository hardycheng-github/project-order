<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_format_price.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_format_price.inc.php,v 1.7 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/
// include needed functions
require_once(DIR_FS_INC . 'twe_precision.inc.php');
function twe_format_price ($price_string,$price_special,$calculate_currencies,$show_currencies=1)
{
 global $db;

// calculate currencies

$currencies_query = "SELECT symbol_left,
          symbol_right,
          decimal_places,
          value
          FROM ". TABLE_CURRENCIES ." WHERE
          code = '".$_SESSION['currency'] ."'";
$currencies_value = $db->Execute($currencies_query);		  
$currencies_data=array();
$currencies_data=array(
      'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'] ,
      'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'] ,
      'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'] ,
      'VALUE'=> $currencies_value->fields['value']);
if ($calculate_currencies=='true') {
$price_string=$price_string * $currencies_data['VALUE'];
}
// round price
$price_string=twe_precision($price_string,$currencies_data['DECIMAL_PLACES']);


if ($price_special=='1') {
$currencies_query = "SELECT symbol_left,
          decimal_point,
          thousands_point,
          value
          FROM ". TABLE_CURRENCIES ." WHERE
          code = '".$_SESSION['currency'] ."'";
$currencies_value = $db->Execute($currencies_query);		  

$price_string=number_format($price_string,$currencies_data['DECIMAL_PLACES'], $currencies_value->fields['decimal_point'], $currencies_value->fields['thousands_point']);
  if ($show_currencies == 1) {
    $price_string = $currencies_data['SYMBOL_LEFT']. $price_string . $currencies_data['SYMBOL_RIGHT'];
  }
}
return $price_string;
}
?>