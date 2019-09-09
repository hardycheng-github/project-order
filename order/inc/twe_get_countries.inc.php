<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_countries.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_countries.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_countries($countries_id = '', $with_iso_codes = false) {
    global $db;
    $countries_array = array();
    if (twe_not_null($countries_id)) {
      if ($with_iso_codes == true) {
        $countries = "select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "' order by countries_name";
        $countries_values = $db->Execute(
    $countries);
        $countries_array = array('countries_name' => $countries_values->fields['countries_name'],
                                 'countries_iso_code_2' => $countries_values->fields['countries_iso_code_2'],
                                 'countries_iso_code_3' => $countries_values->fields['countries_iso_code_3']);
      } else {
        $countries = "select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $countries_id . "'";
        $countries_values = $db->Execute(
    $countries);
        $countries_array = array('countries_name' => $countries_values->fields['countries_name']);
      }
    } else {
      $countries = "select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name";
     $countries_values = $db->Execute(
    $countries);
	  while (!$countries_values->EOF) {
        $countries_array[] = array('countries_id' => $countries_values->fields['countries_id'],
                                   'countries_name' => $countries_values->fields['countries_name']);
      	 $countries_values->MoveNext(); 
	  }
    }
    return $countries_array;
  }
 ?>