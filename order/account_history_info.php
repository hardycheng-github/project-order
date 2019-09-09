<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_history_info.php,v 1.9 2004/02/20 15:35:38 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_history_info.php,v 1.97 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_history_info.php,v 1.17 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
  // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_display_tax_value.inc.php');
  require_once(DIR_FS_INC . 'twe_format_price_order.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (!isset($_GET['order_id']) || (isset($_GET['order_id']) && !is_numeric($_GET['order_id']))) {
    twe_redirect(twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  
  $customer_info_query = "select customers_id from " . TABLE_ORDERS . " where orders_id = '". (int)$_GET['order_id'] . "'";
  $customer_info = $db->Execute($customer_info_query);
  if ($customer_info->fields['customers_id'] != $_SESSION['customer_id']) {
    twe_redirect(twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }


  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_HISTORY_INFO, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_HISTORY_INFO, twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3_ACCOUNT_HISTORY_INFO, $_GET['order_id']), twe_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'], 'SSL'));

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($_GET['order_id']);
 require(DIR_WS_INCLUDES . 'header.php');

 $smarty->assign('ORDER_NUMBER',$_GET['order_id']);
 $smarty->assign('ORDER_DATE',twe_date_long($order->info['date_purchased']));
 $smarty->assign('ORDER_STATUS',$order->info['orders_status']);
 $smarty->assign('BILLING_LABEL',twe_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br>'));
 $smarty->assign('PRODUCTS_EDIT',twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
 $smarty->assign('SHIPPING_ADDRESS_EDIT',twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));
 $smarty->assign('BILLING_ADDRESS_EDIT',twe_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'));
 $smarty->assign('BUTTON_PRINT','<img src="'.'templates/'.CURRENT_TEMPLATE.'/buttons/' . $_SESSION['language'].'/button_print.gif" style="cursor:hand" onClick="window.open(\''. twe_href_link(FILENAME_PRINT_ORDER,'oID='.$_GET['order_id']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">');


  if ($order->delivery != false) {

 $smarty->assign('DELIVERY_LABEL',twe_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'));

    if ($order->info['shipping_method']) {
    $smarty->assign('SHIPPING_METHOD',$order->info['shipping_method']);

    }

  }

  if (sizeof($order->info['tax_groups']) > 1) {

  } else {

  }

$data_products = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    $data_products .= '          <tr>' . "\n" .
         '            <td class="main" nowrap align="left" valign="top" width="">' . $order->products[$i]['qty'] .' x '.$order->products[$i]['name']. '</td>' . "\n" .
     '                <td class="main" align="right" valign="top">' .twe_format_price_order($order->products[$i]['price'],1,$order->info['currency']). '</td></tr>' . "\n" ;



    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        $data_products .= '<tr>
        <td class="main" align="left" valign="top">
        <nobr><small>&nbsp;<i> - '
        . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] .'
        </i></small></td>
        <td class="main" align="right" valign="top"><i><small>'
        .twe_get_products_attribute_price_checkout($order->products[$i]['attributes'][$j]['price'],$order->products[$i]['tax'],1,$order->products[$i]['qty'],$order->products[$i]['attributes'][$j]['prefix']).
        '</i></small></nobr></td></tr>';
      }
    }

    $data_products .= '' . "\n";

    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 0 && $_SESSION['customers_status']['customers_status_add_tax_ot'] == 1) {
      if (sizeof($order->info['tax_groups']) > 1) $data_products .= '            <td class="main" valign="top" align="right">' . twe_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
    }
     $data_products .=    '          </tr>' . "\n";
  }
  $data_products .= '</table>';
      $smarty->assign('PRODUCTS_BLOCK',$data_products);
       if ($order->info['payment_method']!='' && $order->info['payment_method']!='no_payment') {
       include(DIR_WS_LANGUAGES . '/' . $_SESSION['language'] . '/modules/payment/' . $order->info['payment_method'] . '.php');
       $smarty->assign('PAYMENT_METHOD',constant(MODULE_PAYMENT_ . strtoupper($order->info['payment_method']) . _TEXT_TITLE));
       }
$total_block='<table>';
  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
    $total_block.= '            <tr>' . "\n" .
         '                <td class="main"  align="right" width="100%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '                <td class="main" nowrap align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '              </tr>' . "\n";
  }
  $total_block.='</table>';
    $smarty->assign('TOTAL_BLOCK',$total_block);
$history_block='<table>';
  $statuses_query = "select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$_SESSION['languages_id'] . "' order by osh.date_added";
  $statuses = $db->Execute($statuses_query);
  while (!$statuses->EOF) {
    $history_block.= '              <tr>' . "\n" .
         '                <td class="main" valign="top" >' . twe_date_short($statuses->fields['date_added']) . '</td>' . "\n" .
         '                <td class="main" valign="top" >' . $statuses->fields['orders_status_name'] . '</td>' . "\n" .
         '                <td class="main" valign="top">' . (empty($statuses->fields['comments']) ? '&nbsp;' : nl2br(htmlspecialchars($statuses->fields['comments']))) . '</td>' . "\n" .
         '              </tr>' . "\n";
  $statuses->MoveNext();	 
  }
  $history_block.='</table>';
  $smarty->assign('HISTORY_BLOCK',$history_block);

  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php');
$smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT_HISTORY, twe_get_all_get_params(array('order_id')), 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('PAYMENT_BLOCK',$payment_block);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/account_history_info.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
?>