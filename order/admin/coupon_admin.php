<?php
/*
  $Id: coupon_admin.php,v 1.2 2004/02/29 17:05:18 oldpa   Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
 (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');

  // initiate template engine for mail
  $smarty = new Smarty;

  if ($_GET['selected_box']) {
    $_GET['action']='';
    $_GET['old_action']='';
  }
  
  if (($_GET['action'] == 'send_email_to_user') && ($_POST['customers_email_address']) && (!$_POST['back_x'])) {
    switch ($_POST['customers_email_address']) {
    case '***':
      $mail = $db->Execute("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS);
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;
    case '**D':
      $mail = $db->Execute("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
    default:
      $customers_email_address = twe_db_prepare_input($_POST['customers_email_address']);
      $mail = $db->Execute("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($customers_email_address) . "'");
      $mail_sent_to = $_POST['customers_email_address'];
      break;
    }
    $coupon_query = "select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'";
    $coupon_result = $db->Execute($coupon_query);
    $coupon_name_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $coupon_name = $db->Execute($coupon_name_query);

    $from = twe_db_prepare_input($_POST['from']);
    $subject = twe_db_prepare_input($_POST['subject']);
    while (!$mail->EOF) {

      // assign language to template for caching
      $smarty->assign('language', $_SESSION['language']);
      $smarty->caching = false;

      // set dirs manual
      $smarty->template_dir=DIR_FS_CATALOG.'templates';
      $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
      $smarty->config_dir=DIR_FS_CATALOG.'lang';

      $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

      $smarty->assign('MESSAGE', $_POST['message']);
      $smarty->assign('COUPON_ID', $coupon_result->fields['coupon_code']);
      $smarty->assign('WEBSITE', HTTP_SERVER  . DIR_WS_CATALOG);


      $html_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_coupon.html');
      $txt_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_coupon.txt');


      twe_php_mail(EMAIL_BILLING_ADDRESS,EMAIL_BILLING_NAME, $mail->fields['customers_email_address'] , $mail->fields['customers_firstname'] . ' ' . $mail->fields['customers_lastname'] , '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', EMAIL_BILLING_SUBJECT, $html_mail , $txt_mail);
      $mail->MoveNext();
    }

    twe_redirect(twe_href_link(FILENAME_COUPON_ADMIN, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }
 
  if ( ($_GET['action'] == 'preview_email') && (!$_POST['customers_email_address']) ) {
    $_GET['action'] = 'email';
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if ($_GET['mail_sent_to']) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
  }

  switch ($_GET['action']) {
    case 'confirmdelete':
      $delete_query=$db->Execute("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id='".$_GET['cid']."'");
      break;
    case 'update':
      // get all _POST and validate
      $_POST['coupon_code'] = trim($_POST['coupon_code']);
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
          $_POST['coupon_name'][$language_id] = trim($_POST['coupon_name'][$language_id]);
          $_POST['coupon_desc'][$language_id] = trim($_POST['coupon_desc'][$language_id]);
        }
      $_POST['coupon_amount'] = trim($_POST['coupon_amount']);
      $update_errors = 0;
      if (!$_POST['coupon_name']) {
        $update_errors = 1;
        $messageStack->add(ERROR_NO_COUPON_NAME, 'error');
      }
      if ((!$_POST['coupon_amount']) && (!$_POST['coupon_free_ship'])) {
        $update_errors = 1;
        $messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
      }
      if (!$_POST['coupon_code']) {
        $coupon_code = create_coupon_code(); 
      }
      if ($_POST['coupon_code']) $coupon_code = $_POST['coupon_code'];
      $query1 = $db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . twe_db_prepare_input($coupon_code) . "'");
      if ($query1->RecordCount()>0 && $_POST['coupon_code'] && $_GET['oldaction'] != 'voucheredit')  {
        $update_errors = 1;
        $messageStack->add(ERROR_COUPON_EXISTS, 'error');
      }
      if ($update_errors != 0) {
        $_GET['action'] = 'new';
      } else {  
        $_GET['action'] = 'update_preview';
      }
      break;
    case 'update_confirm':
      if ( ($_POST['back_x']) || ($_POST['back_y']) ) {
        $_GET['action'] = 'new';
      } else {
        $coupon_type = "F";
        if (substr($_POST['coupon_amount'], -1) == '%') $coupon_type='P';
        if ($_POST['coupon_free_ship']) $coupon_type = 'S';
        $sql_data_array = array('coupon_code' => twe_db_prepare_input($_POST['coupon_code']),
                                'coupon_amount' => twe_db_prepare_input($_POST['coupon_amount']),
                                'coupon_type' => twe_db_prepare_input($coupon_type),
                                'uses_per_coupon' => twe_db_prepare_input($_POST['coupon_uses_coupon']),
                                'uses_per_user' => twe_db_prepare_input($_POST['coupon_uses_user']),
                                'coupon_minimum_order' => twe_db_prepare_input($_POST['coupon_min_order']),
                                'restrict_to_products' => twe_db_prepare_input($_POST['coupon_products']),
                                'restrict_to_categories' => twe_db_prepare_input($_POST['coupon_categories']),
                                'coupon_start_date' => $_POST['coupon_startdate'],
                                'coupon_expire_date' => $_POST['coupon_finishdate'],
                                'date_created' => 'now()',
                                'date_modified' => 'now()');
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
          $sql_data_marray[$i] = array('coupon_name' => twe_db_prepare_input($_POST['coupon_name'][$language_id]),
                                 'coupon_description' => twe_db_prepare_input($_POST['coupon_desc'][$language_id])
                                 );
        }
//        $query = $db->Execute("select coupon_code from " . TABLE_COUPONS . " where coupon_code = '" . twe_db_prepare_input($_POST['coupon_code']) . "'");
//        if (!twe_db_num_rows($query)) {
        if ($_GET['oldaction']=='voucheredit') {
          twe_db_perform(TABLE_COUPONS, $sql_data_array, 'update', "coupon_id='" . $_GET['cid']."'");
          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
            $update = $db->Execute("update " . TABLE_COUPONS_DESCRIPTION . " set coupon_name = '" . twe_db_prepare_input($_POST['coupon_name'][$language_id]) . "', coupon_description = '" . twe_db_prepare_input($_POST['coupon_desc'][$language_id]) . "' where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $language_id . "'");
//            tep_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i], 'update', "coupon_id='" . $_GET['cid']."'");
          }
        } else {
          twe_db_perform(TABLE_COUPONS, $sql_data_array);
          $insert_id = $db->Insert_ID();

          for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
            $sql_data_marray[$i]['coupon_id'] = $insert_id;
            $sql_data_marray[$i]['language_id'] = $language_id;
            twe_db_perform(TABLE_COUPONS_DESCRIPTION, $sql_data_marray[$i]);
          }
//        }
      }
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<?php 
  switch ($_GET['action']) {
  case 'voucherreport':
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo CUSTOMER_NAME; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo IP_ADDRESS; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo REDEEM_DATE; ?></td>	
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $cc_query_raw = "select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $_GET['cid'] . "'";
    $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_query_raw, $cc_query_numrows);
    $cc_list = $db->Execute($cc_query_raw);
    while (!$cc_list->EOF) {
      $rows++;
      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
      if (((!$_GET['uid']) || (@$_GET['uid'] == $cc_list->fields['unique_id'])) && (!$cInfo)) {
        $cInfo = new objectInfo($cc_list->fields);
      }
      if ( (is_object($cInfo)) && ($cc_list->fields['unique_id'] == $cInfo->unique_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link('coupon_admin.php', twe_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cInfo->coupon_id . '&action=voucherreport&uid=' . $cinfo->unique_id) . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link('coupon_admin.php', twe_get_all_get_params(array('cid', 'action', 'uid')) . 'cid=' . $cc_list->fields['coupon_id'] . '&action=voucherreport&uid=' . $cc_list->fields['unique_id']) . '\'">' . "\n";
      }
$customer_query = "select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . $cc_list->fields['customer_id'] . "'";
$customer = $db->Execute($customer_query);

?>
                <td class="dataTableContent"><?php echo $cc_list->fields['customer_id']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $customer->fields['customers_firstname'] . ' ' . $customer->fields['customers_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $cc_list->fields['redeem_ip']; ?></td>
                <td class="dataTableContent" align="center"><?php echo twe_date_short($cc_list->fields['redeem_date']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list->fields['unique_id'] == $cInfo->unique_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . twe_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list->fields['coupon_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$cc_list->MoveNext();
    }
?>

 

             </table></td>
<?php
    $heading = array();
    $contents = array();
      $coupon_description_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
      $coupon_desc = $db->Execute($coupon_description_query);
      $count_customers = $db->Execute("select * from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $_GET['cid'] . "' and customer_id = '" . $cInfo->customer_id . "'");
       
      $heading[] = array('text' => '<b>[' . $_GET['cid'] . ']' . COUPON_NAME . ' ' . $coupon_desc->fields['coupon_name'] . '</b>');
      $contents[] = array('text' => '<b>' . TEXT_REDEMPTIONS . '</b>');
      $contents[] = array('text' => TEXT_REDEMPTIONS_TOTAL . '=' . $cc_list->RecordCount());
      $contents[] = array('text' => TEXT_REDEMPTIONS_CUSTOMER . '=' . $count_customers->RecordCount());
      $contents[] = array('text' => '');
?>
    <td width="25%" valign="top">
<?php
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '            </td>' . "\n";
?>
<?php
    break;
  case 'preview_email': 
    $coupon_query = "select coupon_code from " .TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'";
    $coupon_result = $db->Execute($coupon_query);
    $coupon_name_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $coupon_name = $db->Execute($coupon_name_query);
    switch ($_POST['customers_email_address']) {
    case '***':
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;
    case '**D':
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
    default:
      $mail_sent_to = $_POST['customers_email_address'];
      break;
    }
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <tr><?php echo twe_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=send_email_to_user&cid=' . $_GET['cid']); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br><?php echo $coupon_name->fields['coupon_name']; ?></td>
              </tr>
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['from'])); ?></td>
              </tr>
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></td>
              </tr>
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></td>
              </tr>
              <tr>
                <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (!is_array($_POST[$key])) {
        echo twe_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php ?>&nbsp;</td>
                    <td align="right"><?php echo '<a href="' . twe_href_link(FILENAME_COUPON_ADMIN) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . twe_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
<?php 
    break;       
  case 'email':
    $coupon_query = "select coupon_code from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'";
    $coupon_result = $db->Execute($coupon_query);
    $coupon_name_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cid'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $coupon_name = $db->Execute($coupon_name_query);
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>

          <tr><?php echo twe_draw_form('mail', FILENAME_COUPON_ADMIN, 'action=preview_email&cid='. $_GET['cid']); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $customers_values  = $db->Execute("select customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " order by customers_lastname");
    while(!$customers_values->EOF) {
      $customers[] = array('id' => $customers_values->fields['customers_email_address'],
                           'text' => $customers_values->fields['customers_lastname'] . ', ' . $customers_values->fields['customers_firstname'] . ' (' . $customers_values->fields['customers_email_address'] . ')');
  $customers_values->MoveNext();  
	}
?>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_COUPON; ?>&nbsp;&nbsp;</td>
                <td><?php echo $coupon_name->fields['coupon_name']; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?>&nbsp;&nbsp;</td>
                <td><?php echo twe_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FROM; ?>&nbsp;&nbsp;</td>
                <td><?php echo twe_draw_input_field('from', EMAIL_FROM); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
/*
              <tr>
                <td class="main"><?php echo TEXT_RESTRICT; ?>&nbsp;&nbsp;</td>
                <td><?php echo twe_draw_checkbox_field('customers_restrict', $customers_restrict);?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
*/
?>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?>&nbsp;&nbsp;</td>
                <td><?php echo twe_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?>&nbsp;&nbsp;</td>
                <td><?php echo twe_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php echo twe_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table></td>
          </form></tr>

      </tr>
      </td>
<?php      
    break;
  case 'update_preview':
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php echo twe_draw_form('coupon', 'coupon_admin.php', 'action=update_confirm&oldaction=' . $_GET['oldaction'] . '&cid=' . $_GET['cid']); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_NAME; ?></td>
        <td align="left"><?php echo $_POST['coupon_name'][$language_id]; ?></td>
      </tr>
<?php
}
?>
<?php
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_DESC; ?></td>
        <td align="left"><?php echo $_POST['coupon_desc'][$language_id]; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo $_POST['coupon_amount']; ?></td>
      </tr>
 
      <tr>
        <td align="left"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo $_POST['coupon_min_order']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_FREE_SHIP; ?></td>
<?php
    if ($_POST['coupon_free_ship']) {
?>
        <td align="left"><?php echo TEXT_FREE_SHIPPING; ?></td>
<?php
    } else { 
?>
        <td align="left"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
<?php
    }
?>
      </tr>
      <tr>
        <td align="left"><?php echo COUPON_CODE; ?></td>
<?php
    if ($_POST['coupon_code']) {
      $c_code = $_POST['coupon_code'];
    } else {
      $c_code = $coupon_code;
    }
?>
        <td align="left"><?php echo $coupon_code; ?></td>
      </tr>
      
      <tr>
        <td align="left"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_coupon']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_user']; ?></td>
      </tr>
      
       <tr>
        <td align="left"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo $_POST['coupon_products']; ?></td>
      </tr>


      <tr>
        <td align="left"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo $_POST['coupon_categories']; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo COUPON_STARTDATE; ?></td>
<?php
    $start_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $start_date; ?></td>
      </tr>
      
      <tr>
        <td align="left"><?php echo COUPON_FINISHDATE; ?></td>
<?php
    $finish_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] ));
    echo date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $finish_date; ?></td>
      </tr>
<?php
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $language_id = $languages[$i]['id'];
          echo twe_draw_hidden_field('coupon_name[' . $languages[$i]['id'] . ']', $_POST['coupon_name'][$language_id]);
          echo twe_draw_hidden_field('coupon_desc[' . $languages[$i]['id'] . ']', $_POST['coupon_desc'][$language_id]);
       }
    echo twe_draw_hidden_field('coupon_amount', $_POST['coupon_amount']);
    echo twe_draw_hidden_field('coupon_min_order', $_POST['coupon_min_order']);
    echo twe_draw_hidden_field('coupon_free_ship', $_POST['coupon_free_ship']);
    echo twe_draw_hidden_field('coupon_code', $c_code);
    echo twe_draw_hidden_field('coupon_uses_coupon', $_POST['coupon_uses_coupon']);
    echo twe_draw_hidden_field('coupon_uses_user', $_POST['coupon_uses_user']);
    echo twe_draw_hidden_field('coupon_products', $_POST['coupon_products']);
    echo twe_draw_hidden_field('coupon_categories', $_POST['coupon_categories']);
    echo twe_draw_hidden_field('coupon_startdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] )));
    echo twe_draw_hidden_field('coupon_finishdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] )));
?>
     <tr>
        <td align="left"><?php echo twe_image_submit('button_confirm.gif',COUPON_BUTTON_CONFIRM); ?></td>
        <td align="left"><?php echo twe_image_submit('button_back.gif',COUPON_BUTTON_BACK, 'name=back'); ?></td>
      </td>
      </tr>
      
      </td></table></form>
      </tr>

      </table></td>
<?php      
   
    break;
  case 'voucheredit':
    $languages = twe_get_languages();
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
      $language_id = $languages[$i]['id'];
      $coupon_query = "select coupon_name,coupon_description from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" .  $_GET['cid'] . "' and language_id = '" . $language_id . "'";
      $coupon = $db->Execute($coupon_query);
      $coupon_name[$language_id] = $coupon->fields['coupon_name'];
      $coupon_desc[$language_id] = $coupon->fields['coupon_description'];
    }
    $coupon_query ="select coupon_code, coupon_amount, coupon_type, coupon_minimum_order, coupon_start_date, coupon_expire_date, uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cid'] . "'";
    $coupon = $db->Execute($coupon_query);
    $coupon_amount = $coupon->fields['coupon_amount'];
    if ($coupon->fields['coupon_type']=='P') {
      $coupon_amount .= '%';
    }
    if ($coupon->fields['coupon_type']=='S') {
      $coupon_free_ship .= true;
    }     
    $coupon_min_order = $coupon->fields['coupon_minimum_order'];
    $coupon_code = $coupon->fields['coupon_code'];
    $coupon_uses_coupon = $coupon->fields['uses_per_coupon'];
    $coupon_uses_user = $coupon->fields['uses_per_user'];
    $coupon_products = $coupon->fields['restrict_to_products'];
    $coupon_categories = $coupon->fields['restrict_to_categories'];     
  case 'new':
// set some defaults
    if (!$coupon_uses_user) $coupon_uses_user=1;
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php 
    echo twe_draw_form('coupon', 'coupon_admin.php', 'action=update&oldaction='.$_GET['action'] . '&cid=' . $_GET['cid']);
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left" class="main"><?php if ($i==0) echo COUPON_NAME; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', $coupon_name[$language_id]) . '&nbsp;' . twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?></td>
        <td align="left" class="main" width="40%"><?php if ($i==0) echo COUPON_NAME_HELP; ?></td>
      </tr>
<?php
}
?>
<?php
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
?>

      <tr>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC; ?></td>
        <td align="left" valign="top"><?php echo twe_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']','physical','24','3', $coupon_desc[$language_id]) . '&nbsp;' . twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']); ?></td>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC_HELP; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left" class="main"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_amount', $coupon_amount); ?></td>
        <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_min_order', $coupon_min_order); ?></td>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP; ?></td>
        <td align="left"><?php echo twe_draw_checkbox_field('coupon_free_ship', $coupon_free_ship); ?></td>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CODE; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_code', $coupon_code); ?></td>
        <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_uses_coupon', $coupon_uses_coupon); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_uses_user', $coupon_uses_user); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
      </tr>
       <tr>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_products', $coupon_products); ?> <A HREF="validproducts.php" TARGET="_blank" ONCLICK="window.open('validproducts.php', 'Valid_Products', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo twe_draw_input_field('coupon_categories', $coupon_categories); ?> <A HREF="validcategories.php" TARGET="_blank" ONCLICK="window.open('validcategories.php', 'Valid_Categories', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES_HELP; ?></td>
      </tr>
      <tr>
<?php
    if (!$_POST['coupon_startdate']) {
      $coupon_startdate = preg_split("[-]", date('Y-m-d'));
    } else {
      $coupon_startdate = preg_split("[-]", $_POST['coupon_startdate']);
    }
    if (!$_POST['coupon_finishdate']) {
      $coupon_finishdate = preg_split("[-]", date('Y-m-d'));
      $coupon_finishdate[0] = $coupon_finishdate[0] + 1;
    } else {
      $coupon_finishdate = preg_split("[-]", $_POST['coupon_finishdate']);
    }
?>
        <td align="left" class="main"><?php echo COUPON_STARTDATE; ?></td>
        <td align="left"><?php echo twe_draw_date_selector('coupon_startdate', mktime(0,0,0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0])); ?></td>
        <td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE; ?></td>
        <td align="left"><?php echo twe_draw_date_selector('coupon_finishdate', mktime(0,0,0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0])); ?></td>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo twe_image_submit('button_preview.gif',COUPON_BUTTON_PREVIEW); ?></td>
        <td align="left"><?php echo '&nbsp;&nbsp;<a href="' . twe_href_link('coupon_admin.php', ''); ?>"><?php echo twe_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>
      </td>
      </tr>
      </td></table></form>
      </tr>

      </table></td>
<?php
    break;
  default:
?>    
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="main"><?php echo twe_draw_form('status', FILENAME_COUPON_ADMIN, '', 'get'); ?>
<?php
    $status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
    $status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
    $status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);

    if ($_GET['status']) {
      $status = twe_db_prepare_input($_GET['status']);
    } else { 
      $status = 'Y';
    } 
    echo HEADING_TITLE_STATUS . ' ' . twe_draw_pull_down_menu('status', $status_array, $status, 'onChange="this.form.submit();"');
?>
              </form>
           </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo COUPON_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_AMOUNT; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_CODE; ?></td>	
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if ($_GET['page'] > 1) $rows = $_GET['page'] * 20 - 20;
    if ($status != '*') {
      $cc_query_raw = "select coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date,coupon_expire_date,uses_per_user,uses_per_coupon,restrict_to_products, restrict_to_categories, date_created,date_modified from " . TABLE_COUPONS ." where coupon_active='" . twe_db_input($status) . "' and coupon_type != 'G'";
    } else {
      $cc_query_raw = "select coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date,coupon_expire_date,uses_per_user,uses_per_coupon,restrict_to_products, restrict_to_categories, date_created,date_modified from " . TABLE_COUPONS . " where coupon_type != 'G'";
    }
    $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_query_raw, $cc_query_numrows);
    $cc_list = $db->Execute($cc_query_raw);
    while (!$cc_list->EOF) {
      $rows++;
      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
      if (((!$_GET['cid']) || (@$_GET['cid'] == $cc_list->fields['coupon_id'])) && (!$cInfo)) {
        $cInfo = new objectInfo($cc_list->fields);
      }
      if ( (is_object($cInfo)) && ($cc_list->fields['coupon_id'] == $cInfo->coupon_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link('coupon_admin.php', twe_get_all_get_params(array('cid', 'action')) . 'cid=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link('coupon_admin.php', twe_get_all_get_params(array('cid', 'action')) . 'cid=' . $cc_list->fields['coupon_id']) . '\'">' . "\n";
      }
      $coupon_description_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cc_list->fields['coupon_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
      $coupon_desc = $db->Execute($coupon_description_query);
?>
                <td class="dataTableContent"><?php echo $coupon_desc->fields['coupon_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php  
      if ($cc_list->fields['coupon_type'] == 'P') {
        echo $cc_list->fields['coupon_amount'] . '%';
      } elseif ($cc_list->fields['coupon_type'] == 'S') {
        echo TEXT_FREE_SHIPPING;
      } else {
        echo $currencies->format($cc_list->fields['coupon_amount']);
      }
?>
            &nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $cc_list->fields['coupon_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($cc_list->fields['coupon_id'] == $cInfo->coupon_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . twe_href_link(FILENAME_COUPON_ADMIN, 'page=' . $_GET['page'] . '&cid=' . $cc_list->fields['coupon_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$cc_list->MoveNext();
    }
?>
          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText">&nbsp;<?php echo $cc_split->display_count($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo $cc_split->display_links($cc_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>

              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . twe_href_link('coupon_admin.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=new') . '">' . twe_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>

<?php

    $heading = array();
    $contents = array();

    switch ($_GET['action']) {
    case 'release':
      break;
    case 'voucherreport':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_COUPON_REPORT . '</b>');
      $contents[] = array('text' => TEXT_NEW_INTRO);
      break;
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br>' . COUPON_NAME . '<br>' . twe_draw_input_field('name'));
      $contents[] = array('text' => '<br>' . COUPON_AMOUNT . '<br>' . twe_draw_input_field('voucher_amount'));
      $contents[] = array('text' => '<br>' . COUPON_CODE . '<br>' . twe_draw_input_field('voucher_code'));
      $contents[] = array('text' => '<br>' . COUPON_USES_COUPON . '<br>' . twe_draw_input_field('voucher_number_of'));
      break;
    default:
      $heading[] = array('text'=>'['.$cInfo->coupon_id.']  '.$cInfo->coupon_code);
      $amount = $cInfo->coupon_amount;
      if ($cInfo->coupon_type == 'P') {
        $amount .= '%';
      } else {
        $amount = $currencies->format($amount);
      }
      if ($_GET['action'] == 'voucherdelete') {
        $contents[] = array('text'=> TEXT_CONFIRM_DELETE . '</br></br>' . 
                '<a href="'.twe_href_link('coupon_admin.php','action=confirmdelete&cid='.$_GET['cid'],'NONSSL').'">'.twe_image_button('button_confirm.gif','Confirm Delete Voucher').'</a>' .
                '<a href="'.twe_href_link('coupon_admin.php','cid='.$cInfo->coupon_id,'NONSSL').'">'.twe_image_button('button_cancel.gif','Cancel').'</a>'
                );
      } else { 
        $prod_details = NONE;
        if ($cInfo->restrict_to_products) {
          $prod_details = '<A HREF="listproducts.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listproducts.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
        }     
        $cat_details = NONE;
        if ($cInfo->restrict_to_categories) {
          $cat_details = '<A HREF="listcategories.php?cid=' . $cInfo->coupon_id . '" TARGET="_blank" ONCLICK="window.open(\'listcategories.php?cid=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
        }
        $coupon_name_query = "select coupon_name from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $cInfo->coupon_id . "' and language_id = '" . $_SESSION['languages_id'] . "'";
        $coupon_name = $db->Execute($coupon_name_query);
        $contents[] = array('text'=>COUPON_NAME . '&nbsp;::&nbsp; ' . $coupon_name->fields['coupon_name'] . '<br>' .
                     COUPON_AMOUNT . '&nbsp;::&nbsp; ' . $amount . '<br>' .
                     COUPON_STARTDATE . '&nbsp;::&nbsp; ' . twe_date_short($cInfo->coupon_start_date) . '<br>' .
                     COUPON_FINISHDATE . '&nbsp;::&nbsp; ' . twe_date_short($cInfo->coupon_expire_date) . '<br>' .
                     COUPON_USES_COUPON . '&nbsp;::&nbsp; ' . $cInfo->uses_per_coupon . '<br>' .
                     COUPON_USES_USER . '&nbsp;::&nbsp; ' . $cInfo->uses_per_user . '<br>' .
                     COUPON_PRODUCTS . '&nbsp;::&nbsp; ' . $prod_details . '<br>' .
                     COUPON_CATEGORIES . '&nbsp;::&nbsp; ' . $cat_details . '<br>' .
                     DATE_CREATED . '&nbsp;::&nbsp; ' . twe_date_short($cInfo->date_created) . '<br>' .
                     DATE_MODIFIED . '&nbsp;::&nbsp; ' . twe_date_short($cInfo->date_modified) . '<br><br>' .
                     '<center><a href="'.twe_href_link('coupon_admin.php','action=email&cid='.$cInfo->coupon_id,'NONSSL').'">'.twe_image_button('button_email.gif','Email Voucher').'</a><br>' .
                     '<a href="'.twe_href_link('coupon_admin.php','action=voucheredit&cid='.$cInfo->coupon_id,'NONSSL').'">'.twe_image_button('button_edit.gif','Edit Voucher').'</a><br>' .
                     '<a href="'.twe_href_link('coupon_admin.php','action=voucherdelete&cid='.$cInfo->coupon_id,'NONSSL').'">'.twe_image_button('button_delete.gif','Delete Voucher').'</a><br>' .
                     '<a href="'.twe_href_link('coupon_admin.php','action=voucherreport&cid='.$cInfo->coupon_id,'NONSSL').'">'.twe_image_button('button_report.gif','Voucher Report').'</a></center>'
                     );
        }
        break;
      }
?>                       
    <td width="25%" valign="top">
<?php
      $box = new box;
      echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
    }
?>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
</td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>