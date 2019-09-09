<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_success.php,v 1.8 2004/03/01 19:16:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_success.php,v 1.48 2003/02/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (checkout_success.php,v 1.14 2003/08/17); www.nextcommerce.org
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

  include( 'includes/application_top.php');
   // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  // if the customer is not logged on, redirect them to the shopping cart page
  if (!isset($_SESSION['customer_id'])) {
    twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $notify_string = 'action=notify&';
    $notify = $_POST['notify'];
    if (!is_array($notify)) $notify = array($notify);
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);
	if ((DELETE_GUEST_ACCOUNT == true) && ($_SESSION['account_type']==1)){
	$db->Execute("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
    $db->Execute("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
    twe_session_destroy();
    twe_redirect(twe_href_link(FILENAME_DEFAULT));
   }else{
    if ($_SESSION['account_type']!=1) {
	twe_redirect(twe_href_link(FILENAME_DEFAULT, $notify_string));
    } else {
    twe_redirect(twe_href_link(FILENAME_LOGOFF, $notify_string));
    }
   }
  }

 $breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_SUCCESS);
  $breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_SUCCESS);

  $global_query = "select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'";
  $global = $db->Execute($global_query);

    $orders_query = "select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' order by date_purchased desc limit 1";
    $orders = $db->Execute($orders_query);

  if ($global->fields['global_product_notifications'] != '1') {

    $products_array = array();
    $products = $db->Execute("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders->fields['orders_id'] . "' order by products_name");
    while (!$products->EOF) {
      $products_array[] = array('id' => $products->fields['products_id'],
                                'text' => $products->fields['products_name']);
	$products->MoveNext();							
    }
  }

 require(DIR_WS_INCLUDES . 'header.php');
 

 /* if ($global->fields['global_product_notifications'] != '1') {
    $notifications= '<p class="productsNotifications">';

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
        $notifications.=  twe_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br>';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }

    $notifications.=  '</p>';
  } else {
    $notifications.=  TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
  }
 $smarty->assign('NOTIFICATION_BLOCK',$notifications);*/
 $smarty->assign('FORM_ACTION',twe_draw_form('order', twe_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')));
 $smarty->assign('BUTTON_CONTINUE',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
 $smarty->assign('BUTTON_PRINT','<img src="'.'templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'].'/button_print.gif" style="cursor:hand" onClick="window.open(\''. twe_href_link(FILENAME_PRINT_ORDER,'oID='.$orders->fields['orders_id']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">');

 // GV Code Start
 $gv_query="select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id='".$_SESSION['customer_id']."'";
   $gv_result=$db->Execute($gv_query);
    if ($gv_result->RecordCount()>0) {
       if ($gv_result->fields['amount'] > 0) {
            $smarty->assign('GV_SEND_LINK', twe_href_link(FILENAME_GV_SEND));
            }
       }
 // GV Code End
 if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); 
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('PAYMENT_BLOCK',$payment_block);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_success.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
?>