<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_countries_list.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

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
   function twe_get_countries_list($default = '') {
   global $db;
    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => STORE_COUNTRY,
                                 'text' => $default);
    }
    $countries = $db->Execute("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
    while (!$countries->EOF) {
      $countries_array[] = array('id' => $countries->fields['countries_id'],
                                 'text' => $countries->fields['countries_name']);
   $countries->MoveNext();
    }

    return $countries_array;
  }
 
   ?>