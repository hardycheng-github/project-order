<?php
/* -----------------------------------------------------------------------------------------
   $Id: account.php,v 1.10 2004/07/17 oldpa Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project (earlier name of osCommerce)
   (c) 2002-2003 osCommerce (account.php,v 1.59 2003/05/19); www.oscommerce.com
   (c) 2003      nextcommerce (account.php,v 1.12 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

  // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_count_customer_orders.inc.php');
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  if (!$_SESSION['customer_id']) {

    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_ACCOUNT, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php'); 
 
 
  if ($messageStack->size('account') > 0) {
        
$smarty->assign('error_message',$messageStack->output('account'));

  }
$order_content='';
  if (twe_count_customer_orders() > 0) {

    $orders_query = "select
                                  o.orders_id,
                                  o.date_purchased,
                                  o.delivery_name,
                                  o.delivery_country,
                                  o.billing_name,
                                  o.billing_country,
                                  ot.text as order_total,
                                  s.orders_status_name
                                  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . "
                                  ot, " . TABLE_ORDERS_STATUS . " s
                                  where o.customers_id = '" . (int)$_SESSION['customer_id'] . "'
                                  and o.orders_id = ot.orders_id
                                  and ot.class = 'ot_total'
                                  and o.orders_status = s.orders_status_id
                                  and s.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                  order by orders_id desc limit 3";
  $orders = $db->Execute($orders_query);							  
                                  
    while (!$orders ->EOF) {
      if (twe_not_null($orders->fields['delivery_name'])) {
        $order_name = $orders->fields['delivery_name'];
        $order_country = $orders->fields['delivery_country'];
      } else {
        $order_name = $orders->fields['billing_name'];
        $order_country = $orders->fields['billing_country'];
      }
     $order_content[]=array(
                        'ORDER_ID' =>$orders->fields['orders_id'],
                        'ORDER_DATE' =>twe_date_short($orders->fields['date_purchased']),
                        'ORDER_STATUS' =>$orders->fields['orders_status_name'],
                        'ORDER_TOTAL' =>$orders->fields['order_total'],
                        'ORDER_LINK' => twe_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders->fields['orders_id'], 'SSL') ,
                        'ORDER_BUTTON' => '<a href="'.twe_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders->fields['orders_id'], 'SSL') . '">' . twe_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>');
   $orders ->MoveNext();
   }

  }
  $smarty->assign('LINK_EDIT',twe_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
  $smarty->assign('LINK_ADDRESS',twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  $smarty->assign('LINK_PASSWORD',twe_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
  $smarty->assign('LINK_ORDERS',twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $smarty->assign('LINK_NEWSLETTER',twe_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));
  $smarty->assign('LINK_NOTIFICATIONS',twe_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL'));
  $smarty->assign('LINK_ALL',twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $smarty->assign('order_content',$order_content);
  $smarty->assign('language', $_SESSION['language']);

  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/account.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
?>