<?php
/* -----------------------------------------------------------------------------------------
   $Id: create_guest_account.php,v 1.7 2005/04/14 19:13:03 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(create_account.php,v 1.63 2003/05/28); www.oscommerce.com
   (c) 2003	 nextcommerce (create_account.php,v 1.27 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   
   Guest account idea by Ingo T. <xIngox@web.de>
   ---------------------------------------------------------------------------------------*/

  require('includes/application_top.php');

       // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php');
// include needed functions
require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');
require_once(DIR_FS_INC . 'twe_get_country_list.inc.php');
require_once(DIR_FS_INC . 'twe_get_countries.inc.php');
require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
require_once(DIR_FS_INC . 'twe_draw_password_field.inc.php');
require_once(DIR_FS_INC . 'twe_validate_email.inc.php');
require_once(DIR_FS_INC . 'twe_encrypt_password.inc.php');
require_once(DIR_WS_CLASSES.'class.phpmailer.php');
require_once(DIR_FS_INC . 'twe_php_mail.inc.php');
require_once(DIR_FS_INC . 'twe_create_password.inc.php');
require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
require_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');




// needs to be included earlier to set the success message in the messageStack
//  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') $gender = twe_db_prepare_input($_POST['gender']);
    $firstname = twe_db_prepare_input($_POST['firstname']);
    $lastname = twe_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = twe_db_prepare_input($_POST['dob']);
    $email_address = twe_db_prepare_input($_POST['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = twe_db_prepare_input($_POST['company']);
    $street_address = twe_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = twe_db_prepare_input($_POST['suburb']);
    $postcode = twe_db_prepare_input($_POST['postcode']);
    $city = twe_db_prepare_input($_POST['city']);
    $zone_id = twe_db_prepare_input($_POST['zone_id']);
    if (ACCOUNT_STATE == 'true') $state = twe_db_prepare_input($_POST['state']);
    $country = twe_db_prepare_input($_POST['country']);
    $telephone = twe_db_prepare_input($_POST['telephone']);
    $fax = twe_db_prepare_input($_POST['fax']);
    $newsletter = twe_db_prepare_input($_POST['newsletter']);
    $password = twe_db_prepare_input($_POST['password']);
    $confirmation = twe_db_prepare_input($_POST['confirmation']);

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(twe_date_raw($dob), 4, 2), substr(twe_date_raw($dob), 6, 2), substr(twe_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = "select count(*) as total
                      from " . TABLE_ZONES . "
                      where zone_country_id = '" . (int)$country . "'";

      $check = $db->Execute($check_query);

      $entry_state_has_zones = ($check->fields['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = "select distinct zone_id, zone_name
                       from " . TABLE_ZONES . "
                       where zone_country_id = '" . (int)$country . "'
                       and zone_code =  '" . strtoupper(twe_db_input($state)) . "'";

        $zone = $db->Execute($zone_query);
        if ($zone->RecordCount() > 0) {
          $zone_id = $zone->fields['zone_id'];
          $zone_name = $zone->fields['zone_name'];

        } else {

          $zone_query = "select distinct zone_id, zone_name
                         from " . TABLE_ZONES . "
                         where zone_country_id = '" . (int)$country . "'
                         and (zone_name like '" . twe_db_input($state) . "%'
                         or zone_code like '%" . twe_db_input($state) . "%')";

          $zone = $db->Execute($zone_query);

          if ($zone->RecordCount() > 0) {
            $zone_id = $zone->fields['zone_id'];
            $zone_name = $zone->fields['zone_name'];
          }
        }
        if (!$zone_name) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

    //create password	
    $password= twe_create_password(8);


    if ($error == false) {
      $sql_data_array = array('customers_status' => DEFAULT_CUSTOMERS_STATUS_ID,
	  		      'customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'account_type' => '1',
                              'customers_password' => twe_encrypt_password($password));

      $_SESSION['account_type']='1';

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = twe_date_raw($dob);

      twe_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $_SESSION['customer_id'] = $db->Insert_ID();

      $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
							  'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      twe_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = $db->Insert_ID();

      $db->Execute("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . $address_id . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      $db->Execute("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$_SESSION['customer_id'] . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        twe_session_recreate();
      }

      $_SESSION['customer_first_name'] = $firstname;
      $_SESSION['customer_default_address_id'] = $address_id;
      $_SESSION['customer_country_id'] = $country;
      $_SESSION['customer_zone_id'] = $zone_id;


// restore cart contents
      $_SESSION['cart']->restore_contents();

     
      twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }



  $breadcrumb->add(NAVBAR_TITLE_CREATE_GUEST_ACCOUNT, twe_href_link(FILENAME_CREATE_GUEST_ACCOUNT, '', 'SSL'));

require(DIR_WS_INCLUDES . 'header.php');

  if ($messageStack->size('create_account') > 0) {
  $smarty->assign('error',$messageStack->output('create_account'));

  }
  $smarty->assign('FORM_ACTION',twe_draw_form('create_account', twe_href_link(FILENAME_CREATE_GUEST_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . twe_draw_hidden_field('action', 'process'));

  if (ACCOUNT_GENDER == 'true') {
  $smarty->assign('gender','1');

  $smarty->assign('INPUT_MALE',twe_draw_radio_field('gender', 'm'));
  $smarty->assign('INPUT_FEMALE',twe_draw_radio_field('gender', 'f').(twe_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''));

  } else {
    $smarty->assign('gender','0');
    }

  $smarty->assign('INPUT_FIRSTNAME',twe_draw_input_field('firstname') . '&nbsp;' . (twe_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''));
  $smarty->assign('INPUT_LASTNAME',twe_draw_input_field('lastname') . '&nbsp;' . (twe_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''));

  if (ACCOUNT_DOB == 'true') {
  $smarty->assign('birthdate','1');

  $smarty->assign('INPUT_DOB',twe_draw_input_field('dob') . '&nbsp;' . (twe_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''));

  }  else {
  $smarty->assign('birthdate','0');
  }

  $smarty->assign('INPUT_EMAIL',twe_draw_input_field('email_address') . '&nbsp;' . (twe_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''));

  if (ACCOUNT_COMPANY == 'true') {
  $smarty->assign('company','1');
  $smarty->assign('INPUT_COMPANY',twe_draw_input_field('company') . '&nbsp;' . (twe_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''));

  }  else {
  $smarty->assign('company','0');
  }

  $smarty->assign('INPUT_STREET',twe_draw_input_field('street_address') . '&nbsp;' . (twe_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''));

  if (ACCOUNT_SUBURB == 'true') {
  $smarty->assign('suburb','1');
 $smarty->assign('INPUT_SUBURB',twe_draw_input_field('suburb') . '&nbsp;' . (twe_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''));

  } else {
  $smarty->assign('suburb','0');
  }

  $smarty->assign('INPUT_CODE',twe_draw_input_field('postcode') . '&nbsp;' . (twe_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''));
  $smarty->assign('INPUT_CITY',twe_draw_input_field('city') . '&nbsp;' . (twe_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''));

  if (ACCOUNT_STATE == 'true') {
  $smarty->assign('state','1');

    
   if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_values = $db->Execute("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
        while (!$zones_values->EOF) {
          $zones_array[] = array('id' => $zones_values->fields['zone_name'], 'text' => $zones_values->fields['zone_name']);
       $zones_values->MoveNext();
	    }
        $state_input= twe_draw_pull_down_menu('state', $zones_array);
      } else {
        $state_input= twe_draw_input_field('state');
      }
    } else {
      $state_input= twe_draw_input_field('state');
    }

    if (twe_not_null(ENTRY_STATE_TEXT)) $state_input.= '&nbsp;<span class="inputRequirement">' . ENTRY_STATE_TEXT;

   $smarty->assign('INPUT_STATE',$state_input);
  } else {
  $smarty->assign('state','0');
  }

  $smarty->assign('SELECT_COUNTRY',twe_get_country_list('country', STORE_COUNTRY) . '&nbsp;' . (twe_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''));
  $smarty->assign('INPUT_TEL',twe_draw_input_field('telephone') . '&nbsp;' . (twe_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('INPUT_FAX',twe_draw_input_field('fax') . '&nbsp;' . (twe_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('CHECKBOX_NEWSLETTER',twe_draw_checkbox_field('newsletter', '1') . '&nbsp;' . (twe_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''));

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/create_account_guest.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>