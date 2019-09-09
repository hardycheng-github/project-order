<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_set_customer_status_upgrade.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(general.php,v 1.225 2003/05/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_set_customer_status_upgrade.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
//set customer satus to new customer for upgrade account
function twe_set_customer_status_upgrade($customer_id){
global $db;
if ( ($_SESSION['customer_status_value']['customers_status_id'] == "' . DEFAULT_CUSTOMERS_STATUS_ID_NEWSLETTER .'" ) AND ($_SESSION['customer_status_value']['customers_is_newsletter'] == 0 ) ) {
 $sql = "update " . TABLE_CUSTOMERS . " set customers_status = '" . DEFAULT_CUSTOMERS_STATUS_ID . "' where customers_id = '" . $_SESSION['customer_id'] . "'";
  $db->Execute(
    $sql);
 $sql = "insert into " . TABLE_CUSTOMERS_STATUS_HISTORY . " (customers_id, new_value, old_value, date_added, customer_notified) values ('" . $_SESSION['customer_id'] . "', '" . DEFAULT_CUSTOMERS_STATUS_ID . "', '" . DEFAULT_CUSTOMERS_STATUS_ID_NEWSLETTER . "', now(), '" . $customer_notified . "')";
 $db->Execute(
    $sql);
}
return 1;
}
?>
