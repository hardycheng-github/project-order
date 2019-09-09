<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_format_special_price.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for XTcommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_format_special_price.inc.php,v 1.6 2003/08/20); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

function twe_format_special_price ($special_price,$price,$price_special,$calculate_currencies,$quantity,$products_tax)
	{
		  global $db;

	// calculate currencies
	
	$currencies_query = "SELECT symbol_left,
											symbol_right,
											decimal_places,
											decimal_point,
          										thousands_point,
											value
											FROM ". TABLE_CURRENCIES ." WHERE
											code = '".$_SESSION['currency'] ."'";
	$currencies_value=$db->Execute(
    $currencies_query);
	$currencies_data=array();
	$currencies_data=array(
							'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'] ,
							'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'] ,
							'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'],
							'DEC_POINT'=>$currencies_value->fields['decimal_point'],
							'THD_POINT'=>$currencies_value->fields['thousands_point'],
							'VALUE'=> $currencies_value->fields['value'])							;
	if ($_SESSION['customers_status']['customers_status_show_price_tax'] =='0') {
		$products_tax='';
	}						
	//$special_price= (twe_add_tax($special_price,$products_tax))*$quantity;
	//$price= (twe_add_tax($price,$products_tax))*$quantity;
	$price=$price*$quantity;
	$special_price=$special_price*$quantity;
	
	if ($calculate_currencies=='true') {
	$special_price=$special_price * $currencies_data['VALUE'];
	$price=$price * $currencies_data['VALUE'];
	
	}
	// round price
	$special_price=twe_precision($special_price,$currencies_data['DECIMAL_PLACES'] );
	$price=twe_precision($price,$currencies_data['DECIMAL_PLACES'] );
	
	if ($price_special=='1') {
	$price=number_format($price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);
	$special_price=number_format($special_price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);

	$special_price ='<span class="productNoSpecialPrice"><s>'. $currencies_data['SYMBOL_LEFT'] . $price . $currencies_data['SYMBOL_RIGHT'].'</s>&nbsp</span>'
	.'<span class="productSpecialPrice">'. $currencies_data['SYMBOL_LEFT'] . $special_price . $currencies_data['SYMBOL_RIGHT'].'</span>';
	} 
	return $special_price;
	}
 ?>