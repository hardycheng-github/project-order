<?php
/* --------------------------------------------------------------
   $Id: export_functions.php,v 1.5 2004/04/14 19:20:25 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce coding standards; www.oscommerce.com
   (c) 2003     nextcommerce (twe_format_price.inc.php,v 1.7 2003/08/19); www.nextcommerce.org
   (c) 2003     nextcommerce (twe_get_products_price.inc.php,v 1.13 2003/08/20); www.nextcommerce.org
   (c) 2003     nextcommerce (twe_format_special_price.inc.php,v 1.6 2003/08/20); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com


   Released under the GNU General Public License
   --------------------------------------------------------------*/

   require_once(DIR_FS_INC . 'twe_precision.inc.php');
   require_once(DIR_FS_INC . 'twe_get_products_special_price.inc.php');
   require_once(DIR_FS_INC . 'twe_get_qty.inc.php');


   function twe_get_tax_rate_export($class_id, $country_id = -1, $zone_id = -1) {
   global $currency,$db; // calculate currencies

    if ( ($country_id == -1) && ($zone_id == -1) ) {

        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;

    }

    $tax = $db->Execute("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . $country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . $zone_id . "') and tr.tax_class_id = '" . $class_id . "' group by tr.tax_priority");
    if ($tax->RecordCount()>0) {
      $tax_multiplier = 1.0;
      while (!$tax->EOF) {
        $tax_multiplier *= 1.0 + ($tax->fields['tax_rate'] / 100);
      $tax->MoveNext();
	  }
      return ($tax_multiplier - 1.0) * 100;
    } else {
      return 0;
    }
  }

function twe_get_products_price_export($products_id,$price_special,$quantity,$group_id=0,$add_tax=1,$currency,$calculate_currencies=true)
    {
global $db;

        // load price data into array for further use!
        $product_price_query = "SELECT   products_price,
                                            products_discount_allowed,
                                            products_tax_class_id
                                            FROM ". TABLE_PRODUCTS ."
                                            WHERE
                                            products_id = '".$products_id."'";
        $product_price = $db->Execute($product_price_query);
        $price_data=array();
        $price_data=array(
                    'PRODUCTS_PRICE'=>$product_price->fields['products_price'],
                    'PRODUCTS_DISCOUNT_ALLOWED'=>$product_price->fields['products_discount_allowed'],
                    'PRODUCT_TAX_CLASS_ID'=>$product_price->fields['products_tax_class_id']
                    );
        // get tax rate for tax class
        $products_tax=twe_get_tax_rate_export($price_data->fields['PRODUCT_TAX_CLASS_ID']);

        // check if user is allowed to see tax rates
        if ($add_tax =='0') {
            $products_tax='';
        } // end $_SESSION['customers_status']['customers_status_show_price_tax'] =='0'
        // check if special price is aviable for product (no product discount on special prices!)
        if ($special_price=twe_get_products_special_price($products_id)) {
            $special_price= (twe_add_tax($special_price,$products_tax));
             $price_data['PRODUCTS_PRICE']= (twe_add_tax($price_data['PRODUCTS_PRICE'],$products_tax));

            $price_string=twe_format_special_price_export($special_price,$price_data['PRODUCTS_PRICE'],$price_special,$calculate_currencies=true,$quantity,$products_tax,$add_tax,$currency);
        }
        else {
            // if ($special_price=twe_get_products_special_price($products_id))
            // Check if there is another price for customers_group (if not, take norm price and calculte discounts (NOTE: no discount on group PRICES(only OT DISCOUNT!)!
            $group_price_query="SELECT personal_offer
                                             FROM personal_offers_by_customers_status_".$group_id."
                                             WHERE products_id='".$products_id."'";
            $group_price_data=$db->Execute($group_price_query);
            // if we found a price, everything is ok if not, we will use normal price
            if     ($group_price_data->fields['personal_offer']!='' and $group_price_data->firlds['personal_offer']!='0.0000') {
                 $price_string=$group_price_data->fields['personal_offer'];
                 // check if customer is allowed to get graduated prices
        //         if ($_SESSION['customers_status']['customers_status_graduated_prices']=='1'){
                     // check if there are graduated prices in db
                     // get quantity for products

                     // modifikations for new graduated prices



                     $qty=twe_get_qty($products_id);
                     if (!twe_get_qty($products_id)) $qty=$quantity;



                     $graduated_price_query="SELECT max(quantity)
                                                          FROM personal_offers_by_customers_status_".$group_id."
                                                          WHERE products_id='".$products_id."'
                                                          AND quantity<='".$qty."'";
                     $graduated_price_data=$db->Execute($graduated_price_query);
                     // get singleprice
                     $graduated_price_query="SELECT personal_offer
                                                          FROM personal_offers_by_customers_status_".$group_id."
                                                          WHERE products_id='".$products_id."'
                                                            AND quantity='".$graduated_price_data['max(quantity)']."'";
                     $graduated_price_data=$db->Execute($graduated_price_query);
                     $price_string=$graduated_price_data->fields['personal_offer'];
            //     } // end $_SESSION['customers_status']['customers_status_graduated_prices']=='1'
                 $price_string= (twe_add_tax($price_string,$products_tax));//*$quantity;

            }
            else {
                // if     ($group_price_data['personal_offer']!='' and $group_price_data['personal_offer']!='0.0000')
               $price_string= (twe_add_tax($price_data['PRODUCTS_PRICE'],$products_tax)); //*$quantity;

                // check if product allows discount

            }
            // format price & calculate currency

            $price_string=$price_string*$quantity;

             // currency calculations
          $currencies_query = "SELECT *
          FROM ". TABLE_CURRENCIES ." WHERE
          code = '".$currency."'";
          $currencies_value=$db->Execute($currencies_query);
          $currencies_data=array();
          $currencies_data=array(
                           'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'] ,
                           'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'] ,
                           'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'] ,
                           'VALUE'=> $currencies_value->fields['value']);

          if ($calculate_currencies=='true') {
             $price_string=$price_string * $currencies_data['VALUE'];
          }
          $price_string=twe_precision($price_string,$currencies_data['DECIMAL_PLACES']);

          if ($price_special=='1') {
              $currencies_query = "SELECT *
                                            FROM ". TABLE_CURRENCIES ." WHERE
                                            code = '".$currency ."'";

              $currencies_value=$db->Execute($currencies_query);
              $price_string=number_format($price_string,$currencies_data['DECIMAL_PLACES'], $currencies_value->fields['decimal_point'], $currencies_value->fields['thousands_point']);

          if ($show_currencies == 1) {
          $price_string = $currencies_data['SYMBOL_LEFT']. ' '.$price_string.' '.$currencies_data['SYMBOL_RIGHT'];
            }
}

        }
    //}
    return $price_string;
//    return $price_data['PRODUCTS_PRICE'];
}



function twe_format_special_price_export ($special_price,$price,$price_special,$calculate_currencies,$quantity,$products_tax,$add_tax,$currency)
    {
	global $db;
    // calculate currencies
    global $currency; // calculate currencies
    $currencies_query = "SELECT symbol_left,
                                            symbol_right,
                                            decimal_places,
                                            decimal_point,
                                                  thousands_point,
                                            value
                                            FROM ". TABLE_CURRENCIES ." WHERE
                                            code = '".$currency ."'";
    $currencies_value=$db->Execute($currencies_query);
    $currencies_data=array();
    $currencies_data=array(
                            'SYMBOL_LEFT'=>$currencies_value->fields['symbol_left'] ,
                            'SYMBOL_RIGHT'=>$currencies_value->fields['symbol_right'] ,
                            'DECIMAL_PLACES'=>$currencies_value->fields['decimal_places'],
                            'DEC_POINT'=>$currencies_value->fields['decimal_point'],
                            'THD_POINT'=>$currencies_value->fields['thousands_point'],
                            'VALUE'=> $currencies_value->fields['value'])                            ;
    if ($add_tax =='0') {
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
    /*
    if ($price_special=='1') {
    $price=number_format($price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);
    $special_price=number_format($special_price,$currencies_data['DECIMAL_PLACES'], $currencies_data['DEC_POINT'], $currencies_data['THD_POINT']);

    $special_price ='<font color="ff0000"><s>'. $currencies_data['SYMBOL_LEFT'].$price.$currencies_data['SYMBOL_RIGHT'].' </s></font>'. $currencies_data['SYMBOL_LEFT'].$special_price.$currencies_data['SYMBOL_RIGHT'];
    }
    */
    return $special_price;
    }

?>