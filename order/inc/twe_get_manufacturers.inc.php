<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_manufacturers.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_get_manufacturers.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  function twe_get_manufacturers($manufacturers_array = '') {
  global $db;  
    if (!is_array($manufacturers_array)) $manufacturers_array = array();

    $manufacturers_query = "select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name";
    $manufacturers = $db->Execute(
    $manufacturers_query);
    while (!$manufacturers->EOF) {
      $manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'], 'text' => $manufacturers->fields['manufacturers_name']);
    $manufacturers->MoveNext();
	}
    return $manufacturers_array;
  }
 ?>