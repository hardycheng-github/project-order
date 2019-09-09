<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_history.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.twCopyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_history.php,v 1.60 2003/05/27); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_history.php,v 1.13 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
   // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_count_customer_orders.inc.php');
  require_once(DIR_FS_INC . 'twe_date_long.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_get_all_get_params.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }


  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_HISTORY, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_HISTORY, twe_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');

 $module_content=array();
  if (($orders_total = twe_count_customer_orders()) > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$_SESSION['customer_id'] . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_id DESC";
    $history_split = new splitPageResults($history_query_raw, $_GET['page'], MAX_DISPLAY_ORDER_HISTORY);
    $history =  $db->Execute($history_split->sql_query);

    while (!$history->EOF) {
      $products_query = "select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . $history->fields['orders_id'] . "'";
      $products = $db->Execute($products_query);

      if (twe_not_null($history->fields['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history->fields['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history->fields['billing_name'];
      }
      $module_content[]=array(
                            'ORDER_ID'=>$history->fields['orders_id'],
                            'ORDER_STATUS'=>$history->fields['orders_status_name'],
                            'ORDER_DATE'=>twe_date_long($history->fields['date_purchased']),
                            'ORDER_PRODUCTS'=>$products->fields['count'],
                            'ORDER_TOTAL'=>strip_tags($history->fields['order_total']),
                            'ORDER_BUTTON'=>'<a href="' . twe_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . '&order_id=' . $history->fields['orders_id'], 'SSL') . '">' . twe_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>');
     $history->MoveNext();
    }
  }

  if ($orders_total > 0) {
  $smarty->assign('SPLIT_BAR','
          <tr>
            <td class="smallText" valign="top">'. $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS).'</td>
            <td class="smallText" align="right">'. TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, twe_get_all_get_params(array('page', 'info', 'x', 'y'))).'</td>
          </tr>');

  }
  $smarty->assign('order_content',$module_content);
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/account_history.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>