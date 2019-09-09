<?php
/* --------------------------------------------------------------
   $Id: orders.php,v 1.15 2004/04/14 19:14:06 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders.php,v 1.109 2003/05/28); www.oscommerce.com 
   (c) 2003	 nextcommerce (orders.php,v 1.19 2003/08/24); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------
   Third Party contribution:
   OSC German Banktransfer v0.85a       	Autor:	Dominik Guder <osc@guder.org>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   credit card encryption functions for the catalog module
   BMC 2003 for the CC CVV Module   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');
  require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');
  require_once(DIR_FS_INC . 'twe_add_tax.inc.php');
  require_once(DIR_FS_INC . 'changedataout.inc.php');

  // initiate template engine for mail
  $smarty = new Smarty;
require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status = $db->Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "'");
  while (!$orders_status->EOF) {
    $orders_statuses[] = array('id' => $orders_status->fields['orders_status_id'],
                               'text' => $orders_status->fields['orders_status_name']);
    $orders_status_array[$orders_status->fields['orders_status_id']] = $orders_status->fields['orders_status_name'];
 $orders_status->MoveNext(); 
  }

    if ( ($_GET['action'] == 'edit') && ($_GET['oID']) ) {
    $oID = twe_db_prepare_input($_GET['oID']);

    $orders_query = $db->Execute("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . twe_db_input($oID) . "'");
    $order_exists = true;
    if ($orders_query->RecordCount() < 1) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

 require(DIR_WS_CLASSES . 'order.php');
   if ( ($_GET['action'] == 'edit') && ($order_exists) ) {
    $order = new order($oID);
    }

  switch ($_GET['action']) {
    case 'update_order':
      $oID = twe_db_prepare_input($_GET['oID']);
      $status = twe_db_prepare_input($_POST['status']);
      $comments = twe_db_prepare_input($_POST['comments']);
      $order = new order($oID);
      $order_updated = false;
      $check_status_query ="select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . twe_db_input($oID) . "'";
      $check_status = $db->Execute($check_status_query);
      if ($check_status->fields['orders_status'] != $status || $comments != '') {
        $db->Execute("update " . TABLE_ORDERS . " set orders_status = '" . twe_db_input($status) . "', last_modified = now() where orders_id = '" . twe_db_input($oID) . "'");

        $customer_notified = '0';
        if ($_POST['notify'] == 'on') {
          $notify_comments = '';
          if ($_POST['notify_comments'] == 'on') {
            $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";

          } else {
          $comments='';
          }


      // assign language to template for caching
      $smarty->assign('language', $_SESSION['language']);
      $smarty->caching = false;

      // set dirs manual
	  $smarty->template_dir=DIR_FS_CATALOG.'templates';
	  $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
	  $smarty->config_dir=DIR_FS_CATALOG.'lang';
	  
	  $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

	  $smarty->assign('NAME',$check_status->fields['customers_name']);
	  $smarty->assign('ORDER_NR',$oID);
	  $smarty->assign('ORDER_LINK',twe_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL'));
	  $smarty->assign('ORDER_DATE',twe_date_long($check_status->fields['date_purchased']));
	  $smarty->assign('NOTIFY_COMMENTS',$comments);
	  $smarty->assign('ORDER_STATUS',$orders_status_array[$status]);

          $html_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$order->info['language'].'/change_order_mail.html');
          $txt_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$order->info['language'].'/change_order_mail.txt');

          twe_php_mail(EMAIL_BILLING_ADDRESS,EMAIL_BILLING_NAME , $check_status->fields['customers_email_address'], $check_status->fields['customers_name'], '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', EMAIL_BILLING_SUBJECT, $html_mail , $txt_mail);
          $customer_notified = '1';
        }

        $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . twe_db_input($oID) . "', '" . twe_db_input($status) . "', now(), '" . $customer_notified . "', '" . twe_db_input($comments)  . "')");

        $order_updated = true;
      }


      if ($order_updated) {
       $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
      } else {
        $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
      }

      twe_redirect(twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('action')) . 'action=edit'));
      break;
    case 'deleteconfirm':
      $oID = twe_db_prepare_input($_GET['oID']);

      twe_remove_order($oID, $_POST['restock']);

      twe_redirect(twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action'))));
      break;
// BMC Delete CC info Start
// Remove CVV Number
    case 'deleteccinfo':
      $oID = twe_db_prepare_input($_GET['oID']);


      $db->Execute("update " . TABLE_ORDERS . " set cc_cvv = null where orders_id = '" . twe_db_input($oID) . "'");
      $db->Execute("update " . TABLE_ORDERS . " set cc_number = '0000000000000000' where orders_id = '" . twe_db_input($oID) . "'");
      $db->Execute("update " . TABLE_ORDERS . " set cc_expires = null where orders_id = '" . twe_db_input($oID) . "'");
      $db->Execute("update " . TABLE_ORDERS . " set cc_start = null where orders_id = '" . twe_db_input($oID) . "'");
      $db->Execute("update " . TABLE_ORDERS . " set cc_issue = null where orders_id = '" . twe_db_input($oID) . "'");

      twe_redirect(twe_href_link(FILENAME_ORDERS, 'oID=' . $_GET['oID'] . '&action=edit'));
      break;
// BMC Delete CC Info End
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($_GET['action'] == 'edit') && ($order_exists) ) {
//    $order = new order($oID);
?>
      <tr>
      <td width="100%">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_customers.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE_SEARCH . TABLE_HEADING_DATE_PURCHASED . $oID . ' - ' . $order->info['date_purchased'] ; ?></td>
  </tr>
  <tr> 
    <td class="main" valign="top">TWE Customers</td>
  </tr>
</table>
 <?php echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('action'))) . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
</td>

      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><?php echo twe_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
            <?php if ($order->customer['csID']!='') { ?>
                <tr>
                <td class="main" valign="top" bgcolor="#FFCC33"><b><?php echo ENTRY_CID; ?></b></td>
                <td class="main" bgcolor="#FFCC33"><?php echo $order->customer['csID']; ?></td>
              </tr>
            <?php } ?>
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo twe_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>

              <tr>
                <td class="main" valign="top"><b><?php echo CUSTOMERS_MEMO; ?></b></td>
<?php
// memoquery
$memo_query="SELECT count(*) as count FROM ".TABLE_CUSTOMERS_MEMO." where customers_id='".$order->customer['ID']."'";
$memo_count=$db->Execute($memo_query);
?>
                <td class="main"><b><?php echo $memo_count->fields['count'].'</b>'; ?>  <a style="cursor:hand" onClick="javascript:window.open('<?php echo twe_href_link(FILENAME_POPUP_MEMO,'ID='.$order->customer['ID']); ?>', 'popup', 'scrollbars=yes, width=500, height=500')">(<?php echo DISPLAY_MEMOS; ?>)</a></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
			  <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_FAX; ?></b></td>
                <td class="main"><?php echo $order->customer['fax']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
              <tr>
                <td class="main" valign="top" bgcolor="#FFCC33"><b><?php echo IP; ?></b></td>
                <td class="main" bgcolor="#FFCC33"><b><?php echo $order->customer['cIP']; ?></b></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php echo twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo twe_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
        <tr>
            <td class="main"><b><?php echo ENTRY_LANGUAGE; ?></b></td>
            <td class="main"><?php echo $order->info['language']; ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
<?php
    if ( (($order->info['cc_type']) || ($order->info['cc_owner']) || ($order->info['cc_number'])) ) {
?>
          <tr>
            <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
<?php
// BMC CC Mod Start
if ($order->info['cc_number'] != '0000000000000000') {
  if ( strtolower(CC_ENC) == 'true' ) {
    $key = changeme;
    $cipher_data = $order->info['cc_number'];
    $order->info['cc_number'] = changedataout($cipher_data,$key);
  }
}
// BMC CC Mod End
?>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
<?php
    if ($order->products[0]['allow_tax'] == 1) {
?>  
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>           
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
<?php
  }
?>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX;
            if ($order->products[$i]['allow_tax'] == 1){ echo ' (excl.)'; } ?></td>
          </tr>
<?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (sizeof($order->products[$i]['attributes']) > 0) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') 
          echo ' (' . $order->products[$i]['attributes'][$j]['prefix'];
          if ($order->products[$i]['allow_tax'] == 1) {
         echo $currencies->format(twe_add_tax($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'],$order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']);
        } else {
         echo  $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']);
        }
          echo ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' .
           format_price($order->products[$i]['final_price']/$order->products[$i]['qty'], 1, $order->info['currency'], $order->products[$i]['allow_tax'], $order->products[$i]['tax']) .
           '</td>' . "\n";

      if ($order->products[$i]['allow_tax'] == 1) {
    	echo '<td class="dataTableContent" align="right" valign="top">';
    	echo twe_display_tax_value($order->products[$i]['tax']).'%';
    	echo '</td>' . "\n";
	echo '<td class="dataTableContent" align="right" valign="top"><b>';

	echo format_price($order->products[$i]['final_price']/$order->products[$i]['qty'], 1, $order->info['currency'], 0, 0);

        echo '</b></td>' . "\n";
      }
      echo '            <td class="dataTableContent" align="right" valign="top"><b>' . format_price(($order->products[$i]['final_price']),1,$order->info['currency'],0,0). '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="10"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
    $orders_history = $db->Execute("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . twe_db_input($oID) . "' order by date_added");
    if ($orders_history->RecordCount()>0) {
      while (!$orders_history->EOF) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . twe_datetime_short($orders_history->fields['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history->fields['customer_notified'] == '1') {
          echo twe_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo twe_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history->fields['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . nl2br(twe_db_output($orders_history->fields['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
     $orders_history->MoveNext(); 
	  }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr><?php echo twe_draw_form('status', FILENAME_ORDERS, twe_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main"><?php echo twe_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']); ?></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo twe_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo twe_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo twe_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo twe_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
        <td colspan="2" align="right">
<?php
if (ACTIVATE_GIFT_SYSTEM=='true') {

echo '<a href="' . twe_href_link(FILENAME_GV_MAIL, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $order->customer['ID']) . '">' . twe_image_button('button_gift.gif', IMAGE_ACCOUNTING) . '</a>';
}
?>

   <img src="<?php echo DIR_WS_CATALOG.'lang/'.$_SESSION['language'].'/admin/images/buttons/'; ?>button_invoice.gif" style="cursor:hand" onClick="window.open('<?php echo twe_href_link(FILENAME_PRINT_ORDER,'oID='.$_GET['oID']); ?>', 'popup', 'toolbar=0, width=640, height=600')">
   <img src="<?php echo DIR_WS_CATALOG.'lang/'.$_SESSION['language'].'/admin/images/buttons/'; ?>button_packingslip.gif" style="cursor:hand" onClick="window.open('<?php echo twe_href_link(FILENAME_PRINT_PACKINGSLIP,'oID='.$_GET['oID']); ?>', 'popup', 'toolbar=0, width=640, height=600')">
 <?php   
 
// BMC Delete CC Info Start
 echo '<a href="' . twe_href_link(FILENAME_ORDERS, 'oID=' . $_GET['oID'] . '&action=deleteccinfo') . '">' . twe_image_button('button_removeccinfo.gif', RemoveCVV) . '&nbsp;</a>';
// BMC Delete CC Info END
echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('action'))) . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%">
        

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_customers.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
    <td class="pageHeading" align="right">
              <?php echo twe_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <?php echo HEADING_TITLE_SEARCH . ' ' . twe_draw_input_field('oID', '', 'size="12"') . twe_draw_hidden_field('action', 'edit').twe_draw_hidden_field(twe_session_name(), twe_session_id()); ?>
              </form>
</td>
  </tr>
  <tr> 
    <td class="main" valign="top">TWE Customers</td>
    <td class="main" valign="top" align="right"><?php echo twe_draw_form('status', FILENAME_ORDERS, '', 'get'); ?>
                <?php echo HEADING_TITLE_STATUS . ' ' . twe_draw_pull_down_menu('status', twe_array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();"').twe_draw_hidden_field(twe_session_name(), twe_session_id()); ?>
              </form></td>
  </tr>
</table> 
        

        
        
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo HEADING_TITLE_SEARCH; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($_GET['cID']) {
      $cID = twe_db_prepare_input($_GET['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . twe_db_input($cID) . "' and o.orders_status = s.orders_status_id and s.language_id = '" . $_SESSION['languages_id'] . "' and ot.class = 'ot_total' order by orders_id DESC";
    } elseif ($_GET['status']) {
      $status = twe_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $_SESSION['languages_id'] . "' and s.orders_status_id = '" . twe_db_input($status) . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . $_SESSION['languages_id'] . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    }
    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_query_raw, $orders_query_numrows);
    $orders = $db->Execute($orders_query_raw);
    while (!$orders->EOF) {
      if (((!$_GET['oID']) || ($_GET['oID'] == $orders->fields['orders_id'])) && (!$oInfo)) {
        $oInfo = new objectInfo($orders->fields);
      }

      if ( (is_object($oInfo)) && ($orders->fields['orders_id'] == $oInfo->orders_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID')) . 'oID=' . $orders->fields['orders_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders->fields['orders_id'] . '&action=edit') . '">' . twe_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders->fields['customers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders->fields['orders_id']; ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders->fields['order_total']); ?></td>
                <td class="dataTableContent" align="center"><?php echo twe_datetime_short($orders->fields['date_purchased']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $orders->fields['orders_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($oInfo)) && ($orders->fields['orders_id'] == $oInfo->orders_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID')) . 'oID=' . $orders->fields['orders_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$orders->MoveNext();
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], twe_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>');

      $contents = array('form' => twe_draw_form('orders', FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . twe_datetime_short($oInfo->date_purchased) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_ORDERS, twe_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
       
        //$contents[] = array('align' => 'center', 'text' => '');

        $contents[] = array('text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . twe_date_short($oInfo->date_purchased));
        if (twe_not_null($oInfo->last_modified)) $contents[] = array('text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . twe_date_short($oInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method);
        // elari added to display product list for selected order
        $order = new order($oInfo->orders_id);
        $contents[] = array('text' => '<br><br>' . sizeof($order->products) . ' Products ' );
        for ($i=0; $i<sizeof($order->products); $i++) {
          $contents[] = array('text' => $order->products[$i]['qty'] . '&nbsp;x' . $order->products[$i]['name']);

          if (sizeof($order->products[$i]['attributes']) > 0) {
            for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
              $contents[] = array('text' => '<small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>' );
            }
          }
        }
        // elari End add display products
      }
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>