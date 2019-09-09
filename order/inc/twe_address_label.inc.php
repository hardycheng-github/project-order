<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_address_label.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_address_label.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   // include needed functions
   require_once(DIR_FS_INC . 'twe_get_address_format_id.inc.php');
   require_once(DIR_FS_INC . 'twe_address_format.inc.php');
  function twe_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n") {
    global $db;
    $address_query = "select b.entry_firstname as firstname, b.entry_lastname as lastname, b.entry_company as company, b.entry_street_address as street_address, b.entry_suburb as suburb, b.entry_city as city, b.entry_postcode as postcode, b.entry_state as state, b.entry_zone_id as zone_id, b.entry_country_id as country_id, b.entry_telephone as telephone, b.entry_fax as fax from " . TABLE_ADDRESS_BOOK . " b, " . TABLE_CUSTOMERS . " c where b.customers_id = '" . $customers_id . "' and b.address_book_id = '" . $address_id . "' and b.customers_id = c.customers_id";
    $address = $db->Execute($address_query);

    $format_id = twe_get_address_format_id($address->fields['country_id']);

    return twe_address_format($format_id, $address->fields, $html, $boln, $eoln);
  }
 ?>