<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_gv_account_update.inc.php,v 1.1 2005/04/17 21:13:27 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(application_top.php,v 1.273 2003/05/19); www.oscommerce.com
   (c) 2003     nextcommerce (application_top.php,v 1.54 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contribution:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org


   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

   // Update the Customers GV account
  function twe_gv_account_update($customer_id, $gv_id) {
  global $db;
    $customer_gv_query = "select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'";
 	$customer_gv = $db->Execute($customer_gv_query);
   
	$coupon_gv_query = "select coupon_amount from " . TABLE_COUPONS . " where coupon_id = '" . $gv_id . "'";
    $coupon_gv = $db->Execute($coupon_gv_query);

	 if ($customer_gv->RecordCount() > 0) {
      $new_gv_amount = $customer_gv->fields['amount'] + $coupon_gv->fields['coupon_amount'];
   // new code bugfix
   $db->Execute("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "' where customer_id = '" . $customer_id . "'");
        // original code $gv_query = tep_db_query("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $new_gv_amount . "'");
    } else {
      $db->Execute("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . $customer_id . "', '" . $coupon_gv->fields['coupon_amount'] . "')");
    }
  }
?>