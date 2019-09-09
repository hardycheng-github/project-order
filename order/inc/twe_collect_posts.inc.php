<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_collect_posts.inc.php,v 1.1 2005/04/17 21:13:27 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce coding standards; www.oscommerce.com
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


function twe_collect_posts() {
      global $currencies, $db,$coupon_no, $REMOTE_ADDR,$cc_id;
      if (!$REMOTE_ADDR) $REMOTE_ADDR=$_SERVER['REMOTE_ADDR'];
      if ($_POST['gv_redeem_code']) {
        $gv_result = $db->Execute("select coupon_id, coupon_amount, coupon_type, coupon_minimum_order,uses_per_coupon, uses_per_user, restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='".$_POST['gv_redeem_code']."' and coupon_active='Y'");

        if ($gv_result->RecordCount()!= 0) {
          $redeem_query = $db->Execute("select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $gv_result->fields['coupon_id'] . "'");
          if ( ($redeem_query->RecordCount() != 0) && ($gv_result->fields['coupon_type'] == 'G') ) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_NO_INVALID_REDEEM_GV), 'SSL'));
          }
        }  else {

        twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_NO_INVALID_REDEEM_GV), 'SSL'));
        }
    
        // GIFT CODE G START
        if ($gv_result->fields['coupon_type'] == 'G') {

          $gv_amount = $gv_result->fields['coupon_amount'];
          // Things to set
          // ip address of claimant
          // customer id of claimant
          // date
          // redemption flag
          // now update customer account with gv_amount
          $gv_amount_query=$db->Execute("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $_SESSION['customer_id'] . "'");
          $customer_gv = false;
          $total_gv_amount = $gv_amount;
          if ($gv_amount_query->RecordCount() > 0) {
            $total_gv_amount = $gv_amount_query->fields['amount'] + $gv_amount;
            $customer_gv = true;
          }
          $gv_update = $db->Execute("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_result->fields['coupon_id'] . "'");
          $gv_redeem = $db->Execute("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_result->fields['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
          if ($customer_gv) {
            // already has gv_amount so update
            $gv_update = $db->Execute("update " . TABLE_COUPON_GV_CUSTOMER . " set amount = '" . $total_gv_amount . "' where customer_id = '" . $_SESSION['customer_id'] . "'");
          } else {
            // no gv_amount so insert
            $gv_insert = $db->Execute("insert into " . TABLE_COUPON_GV_CUSTOMER . " (customer_id, amount) values ('" . $_SESSION['customer_id'] . "', '" . $total_gv_amount . "')");
          }
          twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_INVALID_REDEEM_COUPON), 'SSL'));
     
	  }else{
	  
        if ($gv_result->RecordCount()== 0) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_NO_INVALID_REDEEM_COUPON), 'SSL'));
        }

        $date_query=$db->Execute("select coupon_start_date from " . TABLE_COUPONS . " where coupon_start_date <= now() and coupon_code='".$_POST['gv_redeem_code']."'");

        if ($date_query->RecordCount()==0) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_INVALID_STARTDATE_COUPON), 'SSL'));
        }

        $date_query=$db->Execute("select coupon_expire_date from " . TABLE_COUPONS . " where coupon_expire_date >= now() and coupon_code='".$_POST['gv_redeem_code']."'");

       if ($date_query->RecordCount()==0) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_INVALID_FINISDATE_COUPON), 'SSL'));
        }

        $coupon_count = $db->Execute("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $gv_result->fields['coupon_id']."'");
        $coupon_count_customer = $db->Execute("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $gv_result->fields['coupon_id']."' and customer_id = '" . $_SESSION['customer_id'] . "'");

        if ($coupon_count->RecordCount()>=$gv_result->fields['uses_per_coupon'] && $gv_result->fields['uses_per_coupon'] > 0) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_INVALID_USES_COUPON . $gv_result->fields['uses_per_coupon'] . TIMES ), 'SSL'));
        }

        if ($coupon_count_customer->RecordCount()>=$gv_result->fields['uses_per_user'] && $gv_result->fields['uses_per_user'] > 0) {
            twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_INVALID_USES_USER_COUPON . $gv_result->fields['uses_per_user'] . TIMES ), 'SSL'));
        }
        if ($gv_result->fields['coupon_type']=='S') {
            $coupon_amount = $order->info['shipping_cost'];
        } else {
            $coupon_amount = $gv_result->fields['coupon_amount'] . ' ';
        }
        if ($gv_result->fields['coupon_type']=='P') $coupon_amount = $gv_result->fields['coupon_amount'] . '% ';
        if ($gv_result->fields['coupon_minimum_order']>0) $coupon_amount .= 'on orders greater than ' . $gv_result->fields['coupon_minimum_order'];
        if (!twe_session_is_registered('cc_id')) twe_session_register('cc_id'); //Fred - this was commented out before
        $_SESSION['cc_id'] = $gv_result->fields['coupon_id']; //Fred ADDED, set the global and session variable
        twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(REDEEMED_COUPON), 'SSL'));
    }

     }
     if ($_POST['submit_redeem_x'] && $gv_result->fields['coupon_type'] == 'G') twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, 'info_message=' . urlencode(ERROR_NO_REDEEM_CODE), 'SSL'));
   }
?>