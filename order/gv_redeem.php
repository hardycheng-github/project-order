<?php
/* -----------------------------------------------------------------------------------------
   $Id: gv_redeem.php,v 1.1 2004/02/17 21:13:26 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project (earlier name of osCommerce)
   (c) 2002-2003 osCommerce (gv_redeem.php,v 1.3.2.1 2003/04/18); www.oscommerce.com
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

  require('includes/application_top.php');

  if (ACTIVATE_GIFT_SYSTEM!='true') twe_redirect(FILENAME_DEFAULT);
  
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  
  $smarty = new Smarty;
  
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 

  require(DIR_WS_INCLUDES . 'header.php');

// check for a voucher number in the url
  if (isset($_GET['gv_no'])) {
    $error = true;
    $gv_query = "select c.coupon_id, c.coupon_amount from " . TABLE_COUPONS . " c, " . TABLE_COUPON_EMAIL_TRACK . " et where coupon_code = '" . $_GET['gv_no'] . "' and c.coupon_id = et.coupon_id";
    $coupon = $db->Execute($gv_query);

    if ($coupon->RecordCount() >0) {
      $redeem_query = $db->Execute("select coupon_id from ". TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon->fields['coupon_id'] . "'");
      
	  if ($redeem_query->RecordCount() == 0 ) {
// check for required session variables
        $_SESSION['gv_id'] = $coupon->fields['coupon_id'];
        $error = false;
      } else {
        $error = true;
      }
    }
  } else {
    twe_redirect(FILENAME_DEFAULT);
  }
  if ((!$error) && (isset($_SESSION['customer_id']))) {
// Update redeem status
    $db->Execute("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $coupon->fields['coupon_id'] . "', '" . $_SESSION['customer_id'] . "', now(),'" . $REMOTE_ADDR . "')");
    $db->Execute("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $coupon->fields['coupon_id'] . "'");
    twe_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
    //unset($_SESSION['gv_id']);
  }
  
  $breadcrumb->add(NAVBAR_GV_REDEEM);

// if we get here then either the url gv_no was not set or it was invalid
// so output a message.
  $smarty->assign('coupon_amount', $currencies->format($coupon->fields['coupon_amount']));
  $smarty->assign('error', $error);
  $smarty->assign('LINK_DEFAULT', '<a href="' . twe_href_link(FILENAME_DEFAULT) . '">' . twe_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>');
  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/gv_redeem.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
?>