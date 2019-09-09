<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_address_format_id.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com
   (c) 2003	 nextcommerce (twe_get_address_format_id.inc.php,v 1.3 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com
  Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_address_format_id($country_id) {
  global $db; 
    $address_format_query = "select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . $country_id . "'";
    $address_format = $db->Execute($address_format_query);
   if ($address_format->RecordCount() > 0) {
      return $address_format->fields['format_id'];
    } else {
      return '1';
    }
  }
 ?>