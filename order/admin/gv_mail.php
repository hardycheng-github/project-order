<?php
/* -----------------------------------------------------------------------------------------
   $Id: gv_mail.php,v 1.4 2004/04/25 13:58:08 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project (earlier name of osCommerce)
   (c) 2002-2003 osCommerce (gv_mail.php,v 1.3.2.4 2003/05/12); www.oscommerce.com
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

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  require_once(DIR_FS_CATALOG.DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');

  // initiate template engine for mail
  $smarty = new Smarty;

  if ( ($_GET['action'] == 'send_email_to_user') && ($_POST['customers_email_address'] || $_POST['email_to']) && (!$_POST['back_x']) ) {
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
        if ($_POST['email_to']) {
          $mail_sent_to = $_POST['email_to'];
        }
        break;
    }

    $from = twe_db_prepare_input($_POST['from']);
    $subject = twe_db_prepare_input($_POST['subject']);
    while (!$mail->EOF) {
      $id1 = create_coupon_code($mail->fields['customers_email_address']);

      // assign language to template for caching
      $smarty->assign('language', $_SESSION['language']);
      $smarty->caching = false;

      // set dirs manual
      $smarty->template_dir=DIR_FS_CATALOG.'templates';
      $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
      $smarty->config_dir=DIR_FS_CATALOG.'lang';

      $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

      $smarty->assign('AMMOUNT', $currencies->format($_POST['amount']));
      $smarty->assign('MESSAGE', $_POST['message']);
      $smarty->assign('GIFT_ID', $id1);
      $smarty->assign('WEBSITE', HTTP_SERVER  . DIR_WS_CATALOG);


      $link = HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php';


      $smarty->assign('GIFT_LINK',$link);

      $html_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_gift.html');
      $txt_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_gift.txt');

      if ($subject=='') $subject=EMAIL_BILLING_SUBJECT;
      twe_php_mail(EMAIL_BILLING_ADDRESS,EMAIL_BILLING_NAME, $mail->fields['customers_email_address'] , $mail->fields['customers_firstname'] . ' ' . $mail->fields['customers_lastname'] , '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', $subject, $html_mail , $txt_mail);


	  // Now create the coupon main and email entry
      $db->Execute("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
      $insert_id = $db->Insert_ID();
      $insert_query = $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $mail->fields['customers_email_address'] . "', now() )");
   $mail->MoveNext();
    }
    if ($_POST['email_to']) {
      $id1 = create_coupon_code($_POST['email_to']);

      // assign language to template for caching
      $smarty->assign('language', $_SESSION['language']);
      $smarty->caching = false;

      // set dirs manual
      $smarty->template_dir=DIR_FS_CATALOG.'templates';
      $smarty->compile_dir=DIR_FS_CATALOG.'templates_c';
      $smarty->config_dir=DIR_FS_CATALOG.'lang';

      $smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
      $smarty->assign('logo_path',HTTP_SERVER  . DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');

      $smarty->assign('AMMOUNT', $currencies->format($_POST['amount']));
      $smarty->assign('MESSAGE', $_POST['message']);
      $smarty->assign('GIFT_ID', $id1);
      $smarty->assign('WEBSITE', HTTP_SERVER  . DIR_WS_CATALOG);

      if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
        $link = HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '/gv_no,'.$id1;
      } else {
        $link = HTTP_SERVER  . DIR_WS_CATALOG . 'gv_redeem.php' . '?gv_no='.$id1;
      }

      $smarty->assign('GIFT_LINK',$link);

      $html_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_gift.html');
      $txt_mail=$smarty->fetch(CURRENT_TEMPLATE . '/admin/mail/'.$_SESSION['language'].'/send_gift.txt');


      twe_php_mail(EMAIL_BILLING_ADDRESS,EMAIL_BILLING_NAME, $_POST['email_to'] , '' , '', EMAIL_BILLING_REPLY_ADDRESS, EMAIL_BILLING_REPLY_ADDRESS_NAME, '', '', EMAIL_BILLING_SUBJECT, $html_mail , $txt_mail);


      // Now create the coupon email entry
      $db->Execute("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
      $insert_id = $db->Insert_ID();
      $insert_query = $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $_POST['email_to'] . "', now() )");
	}
    twe_redirect(twe_href_link(FILENAME_GV_MAIL, 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($_GET['action'] == 'preview') && (!$_POST['customers_email_address']) && (!$_POST['email_to']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if ( ($_GET['action'] == 'preview') && (!$_POST['amount']) ) {
    $messageStack->add(ERROR_NO_AMOUNT_SELECTED, 'error');
  }

  if ($_GET['mail_sent_to']) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php if (USE_SPAW=='true') {
 $query="SELECT code FROM ". TABLE_LANGUAGES ." WHERE languages_id='".$_SESSION['languages_id']."'";
 $data=$db->Execute($query);
 ?>
<script type="text/javascript">
   _editor_url = "includes/htmlarea/";
   _editor_lang = "<?php echo $data->fields['code']; ?>";
</script>
    <!-- DWD Modify -> Add: HTMLArea v3.0 !-->
    <!-- Load HTMLArea Core Files. !-->
<script type="text/javascript" src="includes/htmlarea/htmlarea.js"></script>
<script type="text/javascript" src="includes/htmlarea/dialog.js"></script>
<script tyle="text/javascript" src="includes/htmlarea/lang/<?php echo $data->fields['code']; ?>.js"></script>
<?php } ?>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($_GET['action'] == 'preview') && ($_POST['customers_email_address'] || $_POST['email_to']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
      case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
      default:
        $mail_sent_to = $_POST['customers_email_address'];
        if ($_POST['email_to']) {
          $mail_sent_to = $_POST['email_to'];
        }
        break;
    }
?>
          <tr><?php echo twe_draw_form('mail', FILENAME_GV_MAIL, 'action=send_email_to_user'); ?>
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
                <td class="smallText"><b><?php echo TEXT_AMOUNT; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes($_POST['amount']))); ?></td>
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
                    <td><?php echo twe_image_submit('button_back.gif', IMAGE_BACK, 'name="back"'); ?></td>
                    <td align="right"><?php echo '<a href="' . twe_href_link(FILENAME_GV_MAIL) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a> ' . twe_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
<?php
  } else {
?>
          <tr><?php echo twe_draw_form('mail', FILENAME_GV_MAIL, 'action=preview'); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
<?php
    if ($_GET['cID']) {
    $select='where customers_id='.$_GET['cID'];
    } else {
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    }
    $customers_values = $db->Execute("select customers_id, customers_email_address, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ".$select." order by customers_lastname");
    while(!$customers_values->EOF) {
      $customers[] = array('id' => $customers_values->fields['customers_email_address'],
                           'text' => $customers_values->fields['customers_lastname'] . ', ' . $customers_values->fields['customers_firstname'] . ' (' . $customers_values->fields['customers_email_address'] . ')');
   $customers_values->MoveNext(); 
	}
?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo twe_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
               <tr>
                <td class="main"><?php echo TEXT_TO; ?></td>
                <td><?php echo twe_draw_input_field('email_to'); ?><?php echo '&nbsp;&nbsp;' . TEXT_SINGLE_EMAIL; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
             <tr>
                <td class="main"><?php echo TEXT_FROM; ?></td>
                <td><?php echo twe_draw_input_field('from', EMAIL_FROM); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo twe_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_AMOUNT; ?></td>
                <td><?php echo twe_draw_input_field('amount'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><?php echo twe_draw_textarea_field('message', 'soft', '100%', '55'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php echo twe_image_submit('button_send_mail.gif', IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table></td>
          </form></tr>
<?php
  }
?>
<!-- body_text_eof //-->
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
<?php if (USE_SPAW=='true') { ?>
<script type="text/javascript">
      HTMLArea.loadPlugin("SpellChecker");
      HTMLArea.loadPlugin("TableOperations");
      HTMLArea.loadPlugin("FullPage");
      HTMLArea.loadPlugin("CharacterMap");
      HTMLArea.loadPlugin("ContextMenu");
      HTMLArea.loadPlugin("ImageManager");
<?php if ( ($_GET['action'] != 'preview')){ ?>
HTMLArea.onload = function() {
var editor= new HTMLArea("message");
editor.registerPlugin(TableOperations);
editor.registerPlugin(FullPage);
editor.registerPlugin(ContextMenu);
editor.registerPlugin(CharacterMap);
editor.registerPlugin(ImageManager);
editor.generate();
};
HTMLArea.init();
<?php } ?>

</script>
<?php } ?>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>