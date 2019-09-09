<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_address_format.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_address_format.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
   require_once(DIR_FS_INC . 'twe_get_zone_code.inc.php');
   require_once(DIR_FS_INC . 'twe_get_country_name.inc.php');
   require_once(DIR_FS_INC . 'twe_output_string.inc.php');
function twe_address_format($address_format_id, $address, $html, $boln, $eoln) {
    global $db;
    $address_format = $db->Execute("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int)$address_format_id . "'");

    $company = twe_output_string_protected($address['company']);
    if (isset($address['firstname']) && twe_not_null($address['firstname'])) {
      $firstname = twe_output_string_protected($address['firstname']);
      $lastname = twe_output_string_protected($address['lastname']);
    } elseif (isset($address['name']) && twe_not_null($address['name'])) {
      $firstname = twe_output_string_protected($address['name']);
      $lastname = '';
    } else {
      $firstname = '';
      $lastname = '';
    }
    $street = twe_output_string_protected($address['street_address']);
    $suburb = twe_output_string_protected($address['suburb']);
    $city = twe_output_string_protected($address['city']);
    $state = twe_output_string_protected($address['state']); 
	$telephone = twe_output_string_protected($address['telephone']);
	$fax = twe_output_string_protected($address['fax']);
	
    if (isset($address['country_id']) && twe_not_null($address['country_id'])) {
      $country = twe_get_country_name($address['country_id']);

      if (isset($address['zone_id']) && twe_not_null($address['zone_id'])) {
        $state = twe_get_zone_code($address['country_id'], $address['zone_id'], $state);
      }
    } elseif (isset($address['country']) && twe_not_null($address['country'])) {
      $country = twe_output_string_protected($address['country']);
    } else {
      $country = '';
    }
    $postcode = twe_output_string_protected($address['postcode']);
    $zip = $postcode;

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br>';
        $cr = '<br>';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($country == '') $country = twe_output_string_protected($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format->fields['format'];
    eval("\$address = \"$fmt\";");

    if ( (ACCOUNT_COMPANY == 'true') && (twe_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $address;
  }
 ?>