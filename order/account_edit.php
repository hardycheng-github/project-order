<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_edit.php,v 1.7 2005/04/16 oldpa Exp $   
   
   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_edit.php,v 1.63 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_edit.php,v 1.14 2003/08/17); www.nextcommerce.org
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
  require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');
  require_once(DIR_FS_INC . 'twe_date_short.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_validate_email.inc.php');
  require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');
  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }


  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (ACCOUNT_GENDER == 'true') $gender = twe_db_prepare_input($_POST['gender']);
    $firstname = twe_db_prepare_input($_POST['firstname']);
    $lastname = twe_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = twe_db_prepare_input($_POST['dob']);
    $email_address = twe_db_prepare_input($_POST['email_address']);
    $telephone = twe_db_prepare_input($_POST['telephone']);
    $fax = twe_db_prepare_input($_POST['fax']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }
	
   if(ACCOUNT_LASTNAME == 'true') {
	$check_name_query = "select count(*) as total from " . TABLE_CUSTOMERS . " where customers_lastname = '" . twe_db_input($lastname) . "'";
      $check_name = $db->Execute($check_name_query);
      if ($check_name->fields['total'] > 0) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR_EXISTS);
      }
     }
	 
    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(twe_date_raw($dob), 4, 2), substr(twe_date_raw($dob), 6, 2), substr(twe_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (twe_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = "select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($email_address) . "' and customers_id != '" . (int)$_SESSION['customer_id'] . "'";
    $check_email = $db->Execute($check_email_query);
    if ($check_email->fields['total'] > 0) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
							  'username' => $lastname);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = twe_date_raw($dob);

      twe_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      $db->Execute("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");
      
// reset the session variables
      $customer_first_name = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      twe_redirect(twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  } else {
    $account_query = "select customers_gender,customers_cid, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
    $account = $db->Execute($account_query);
  }

  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_EDIT, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_EDIT, twe_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php');
   $smarty->assign('FORM_ACTION',twe_draw_form('account_edit', twe_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . twe_draw_hidden_field('action', 'process'));
  if ($messageStack->size('account_edit') > 0) {
  $smarty->assign('error',$messageStack->output('account_edit'));

  }

  if (ACCOUNT_GENDER == 'true') {
  $smarty->assign('gender','1');
    $male = ($account->fields['customers_gender'] == 'm') ? true : false;
    $female = !$male;
  $smarty->assign('INPUT_MALE',twe_draw_radio_field('gender', 'm',$male));
  $smarty->assign('INPUT_FEMALE',twe_draw_radio_field('gender', 'f',$female).(twe_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''));


  }
  $smarty->assign('INPUT_FIRSTNAME',twe_draw_input_field('firstname',$account->fields['customers_firstname']) . '&nbsp;' . (twe_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''));
  $smarty->assign('INPUT_LASTNAME',twe_draw_input_field('lastname',$account->fields['customers_lastname']) . '&nbsp;' . (twe_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''));
  $smarty->assign('csID',$account->fields['customers_cid']);

  if (ACCOUNT_DOB == 'true') {
  $smarty->assign('birthdate','1');
  $smarty->assign('INPUT_DOB',twe_draw_input_field('dob',twe_date_short($account->fields['customers_dob'])) . '&nbsp;' . (twe_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''));

  }
  $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email_address',$account->fields['customers_email_address']) . '&nbsp;' . (twe_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''));
  $smarty->assign('INPUT_TEL',twe_draw_input_field('telephone',$account->fields['customers_telephone']) . '&nbsp;' . (twe_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('INPUT_FAX',twe_draw_input_field('fax',$account->fields['customers_fax']) . '&nbsp;' . (twe_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

  $smarty->assign('language', $_SESSION['language']);

  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/account_edit.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>