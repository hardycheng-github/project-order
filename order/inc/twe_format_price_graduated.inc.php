<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_format_price_graduated.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_format_price_graduated.inc.php,v 1.4 2003/08/19); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function twe_format_price_graduated($price_string,$price_special,$calculate_currencies,$products_tax_class)
	{
	  global $db;

	$currencies_query = "SELECT symbol_left,
											symbol_right,
											decimal_places,
											decimal_point,
          										thousands_point,
											value
											FROM ". TABLE_CURRENCIES ." WHERE
											code = '".$_SESSION['currency'] ."'";
	$currencies_value=$db->Execute($currencies_query);
	$currencies_data=array();
	$currencies_data=array(
							'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'] ,
							'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'] ,
							'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'] ,
							'VALUE'=> $currencies_value->fields['value'],
							'THD_POINT'=> $currencies_value->fields['thousands_point'],
							'DEC_POINT'=> $currencies_value->fields['decimal_point']);
	if ($calculate_currencies=='true') {
	$price_string=$price_string * $currencies_data['VALUE'];
	}
	// add tax
	$products_tax=twe_get_tax_rate($products_tax_class);
	if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
		$products_tax='';
	}						
	$price_string= (twe_add_tax($price_string,$products_tax));
	// round price
	$price_string=twe_precision($price_string,$currencies_data['DECIMAL_PLACES']);
	
	if ($price_special=='1') {
	$price_string=number_format($price_string,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);

	$price_string = $currencies_data['SYMBOL_LEFT'] . $price_string . $currencies_data['SYMBOL_RIGHT'];
	}
	return $price_string;
	}
 ?>