<?php
/* -----------------------------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.8 2005/04/17 21:13:26 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(password_forgotten.php,v 1.49 2003/05/28); www.oscommerce.com 
   (c) 2003	 nextcommerce (password_forgotten.php,v 1.16 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

      $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 

  // include needed functions
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_encrypt_password.inc.php');
  require_once(DIR_WS_CLASSES.'class.phpmailer.php');
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');

  

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $check_customer_query = "select customers_firstname, customers_lastname, customers_password, customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $_POST['email_address'] . "' and account_type!=1";
    $check_customer = $db->Execute($check_customer_query);

    if ($check_customer->RecordCount()>0) {
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = twe_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crypted_password = twe_encrypt_password($newpass);
      
      $db->Execute("update " . TABLE_CUSTOMERS . " set customers_password = '" . $crypted_password . "' where customers_id = '" . $check_customer->fields['customers_id'] . "'");
      
      	// assign language to template for caching
  	$smarty->assign('language', $_SESSION['language']);	
  	$smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
    $smarty->assign('logo_path',HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
      
      	// assign vars
      	$smarty->assign('EMAIL',$_POST['email_address']);
      	$smarty->assign('PASSWORD',$newpass);
      	$smarty->assign('FIRSTNAME',$check_customer->fields['customers_firstname']);
      	$smarty->assign('LASTNAME',$check_customer->fields['customers_lastname']);
      	// dont allow cache
  	$smarty->caching = false;
  	
  	// create mails
 	$html_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/'.$_SESSION['language'].'/change_password_mail.html');
  	$txt_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/'.$_SESSION['language'].'/change_password_mail.txt');
      
      twe_php_mail(EMAIL_SUPPORT_ADDRESS,EMAIL_SUPPORT_NAME , $_POST['email_address'], $check_customer->fields['customers_firstname'] . " " . $check_customer->fields['customers_lastname'], EMAIL_SUPPORT_FORWARDING_STRING, EMAIL_SUPPORT_REPLY_ADDRESS, EMAIL_SUPPORT_REPLY_ADDRESS_NAME, '', '', EMAIL_SUPPORT_SUBJECT, $html_mail, $txt_mail);    
      
      if (!isset($mail_error)) {
          twe_redirect(twe_href_link(FILENAME_LOGIN, 'info_message=' . urlencode(TEXT_PASSWORD_SENT), 'SSL', true, false));
      }
      else {
          echo $mail_error;
      }
    } else {
      twe_redirect(twe_href_link(FILENAME_PASSWORD_FORGOTTEN, 'email=nonexistent', 'SSL'));
    }
  } else {
    $breadcrumb->add(NAVBAR_TITLE_1_PASSWORD_FORGOTTEN, twe_href_link(FILENAME_LOGIN, '', 'SSL'));
    $breadcrumb->add(NAVBAR_TITLE_2_PASSWORD_FORGOTTEN, twe_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'));

 include(DIR_WS_INCLUDES . 'header.php');


 $smarty->assign('FORM_ACTION',twe_draw_form('password_forgotten', twe_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')));
 $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email_address', '', 'maxlength="96"'));
 $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

    if (isset($_GET['email']) && ($_GET['email'] == 'nonexistent')) {
    $smarty->assign('error','1');
    }

  }


  $smarty->assign('language', $_SESSION['language']);


  // set cache ID
  if (USE_CACHE=='false') {
  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/password_forgotten.html');
  } else {
  $smarty->caching = 1;
  $smarty->cache_lifetime=CACHE_LIFETIME;
  $smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'];
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/password_forgotten.html',$cache_id);
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>