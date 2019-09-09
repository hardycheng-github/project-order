<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_country_zones.inc.php, 2005/04/21 17:55:00 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(html_output.php,v 1.52 2003/03/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_href_link.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   function twe_get_country_zones($country_id) {
            global $db;
    $zones_array = array();
    $zones = $db->Execute("select zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' order by zone_name");
    while (!$zones->EOF) {
      $zones_array[] = array('id' => $zones->fields['zone_id'],
                             'text' => $zones->fields['zone_name']);
	$zones->MoveNext();						 
    }

    return $zones_array;
  }
?>