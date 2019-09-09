<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_address_summary.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_address_summary.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
function twe_address_summary($customers_id, $address_id) {
global $db;
    $customers_id = twe_db_prepare_input($customers_id);
    $address_id = twe_db_prepare_input($address_id);

    $address_query = "select ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_state, ab.entry_country_id, ab.entry_zone_id, c.countries_name, c.address_format_id from " . TABLE_ADDRESS_BOOK . " ab, " . TABLE_COUNTRIES . " c where ab.address_book_id = '" . twe_db_input($address_id) . "' and ab.customers_id = '" . twe_db_input($customers_id) . "' and ab.entry_country_id = c.countries_id";
    $address = $db->Execute(
    $address_query);

    $street_address = $address->fields['entry_street_address'];
    $suburb = $address->fields['entry_suburb'];
    $postcode = $address->fields['entry_postcode'];
    $city = $address->fields['entry_city'];
    $state = twe_get_zone_code($address->fields['entry_country_id'], $address->fields['entry_zone_id'], $address->fields['entry_state']);
    $country = $address->fields['countries_name'];

    $address_format_query = "select address_summary from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . $address->fields['address_format_id'] . "'";
    $address_format = $db->Execute(
    $address_format_query);

//    eval("\$address = \"{$address_format['address_summary']}\";");
    $address_summary = $address_format->fields['address_summary'];
    eval("\$address = \"$address_summary\";");

    return $address;
  }
 ?>