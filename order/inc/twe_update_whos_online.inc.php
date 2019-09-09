<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_update_whos_online.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(whos_online.php,v 1.8 2003/02/21); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_update_whos_online.inc.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  function twe_update_whos_online() {
  global $db;
    if (isset($_SESSION['customer_id'])) {
      $wo_customer_id = $_SESSION['customer_id'];

      $customer_query = "select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'";
      $customer = $db->Execute($customer_query);

      $wo_full_name = addslashes($customer->fields['customers_firstname'] . ' ' . $customer->fields['customers_lastname']);
    } else {
      $wo_customer_id = '1';
      $wo_full_name = 'Guest';
    }

    $wo_session_id = twe_session_id();
    $wo_ip_address = getenv('REMOTE_ADDR');
    $wo_last_page_url = addslashes(getenv('REQUEST_URI'));

    $current_time = time();
    $xx_mins_ago = ($current_time - 900);

    // remove entries that have expired
    $db->Execute("delete from " . TABLE_WHOS_ONLINE . " where time_last_click < '" . $xx_mins_ago . "'");
    

    $stored_customer_query = "select count(*) as count from " . TABLE_WHOS_ONLINE . " where session_id = '" . $wo_session_id . "'";
    $stored_customer = $db->Execute($stored_customer_query);

    if ($stored_customer->fields['count'] > 0) {
     $db->Execute("update " . TABLE_WHOS_ONLINE . " set customer_id = '" . $wo_customer_id . "', full_name = '" . $wo_full_name . "', ip_address = '" . $wo_ip_address . "', time_last_click = '" . $current_time . "', last_page_url = '" . $wo_last_page_url . "' where session_id = '" . $wo_session_id . "'");
  	} else {
    $db->Execute("insert into " . TABLE_WHOS_ONLINE . " (customer_id, full_name, session_id, ip_address, time_entry, time_last_click, last_page_url) values ('" . $wo_customer_id . "', '" . $wo_full_name . "', '" . $wo_session_id . "', '" . $wo_ip_address . "', '" . $current_time . "', '" . $current_time . "', '" . $wo_last_page_url . "')");
    	}
  }
?>