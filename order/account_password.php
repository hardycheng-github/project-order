<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_password.php,v 1.5 2004/02/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_password.php,v 1.1 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_password.php,v 1.14 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
         // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_password_field.inc.php');
  require_once(DIR_FS_INC . 'twe_validate_password.inc.php');
  require_once(DIR_FS_INC . 'twe_encrypt_password.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $password_current = twe_db_prepare_input($_POST['password_current']);
    $password_new = twe_db_prepare_input($_POST['password_new']);
    $password_confirmation = twe_db_prepare_input($_POST['password_confirmation']);

    $error = false;

    if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_CURRENT_ERROR);
    } elseif (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $check_customer_query = "select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
      $check_customer = $db->Execute($check_customer_query);

      if (twe_validate_password($password_current, $check_customer->fields['customers_password'])) {
        $db->Execute("update " . TABLE_CUSTOMERS . " set customers_password = '" . twe_encrypt_password($password_new) . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        $db->Execute("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
      
        $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

        twe_redirect(twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      } else {
        $error = true;

        $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_PASSWORD, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_PASSWORD, twe_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');

  if ($messageStack->size('account_password') > 0) {
  $smarty->assign('error',$messageStack->output('account_password'));

  }
  $smarty->assign('FORM_ACTION',twe_draw_form('account_password', twe_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onSubmit="return check_form(account_password);"') . twe_draw_hidden_field('action', 'process'));
  $smarty->assign('INPUT_ACTUAL',twe_draw_password_field('password_current') . '&nbsp;' . (twe_not_null(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>': ''));
  $smarty->assign('INPUT_NEW',twe_draw_password_field('password_new') . '&nbsp;' . (twe_not_null(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_NEW_TEXT . '</span>': ''));
  $smarty->assign('INPUT_CONFIRM',twe_draw_password_field('password_confirmation') . '&nbsp;' . (twe_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''));

 $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
 $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

  $smarty->assign('language', $_SESSION['language']);

  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/account_password.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>