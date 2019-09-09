<?php
/* -----------------------------------------------------------------------------------------
   $Id: address_book_process.php,v 1.5 2004/02/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.twCopyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(address_book_process.php,v 1.77 2003/05/27); www.oscommerce.com
   (c) 2003	 nextcommerce (address_book_process.php,v 1.13 2003/08/17); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
          // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');
  require_once(DIR_FS_INC . 'twe_count_customer_address_book_entries.inc.php');
  require_once(DIR_FS_INC . 'twe_address_label.inc.php');
  require_once(DIR_FS_INC . 'twe_get_country_name.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');
 
  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }


  if (isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
   $db->Execute("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'");
  
    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  }

  // error checking when updating or adding an entry
  $process = false;
  if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update'))) {
    $process = true;
    $error = false;

    if (ACCOUNT_GENDER == 'true') $gender = twe_db_prepare_input($_POST['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = twe_db_prepare_input($_POST['company']);
    $firstname = twe_db_prepare_input($_POST['firstname']);
    $lastname = twe_db_prepare_input($_POST['lastname']);
    $street_address = twe_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = twe_db_prepare_input($_POST['suburb']);
    $postcode = twe_db_prepare_input($_POST['postcode']);
    $city = twe_db_prepare_input($_POST['city']);
	$telephone = twe_db_prepare_input($_POST['telephone']);
    $fax = twe_db_prepare_input($_POST['fax']);
    $country = twe_db_prepare_input($_POST['country']);
    if (ACCOUNT_STATE == 'true') {
      $zone_id = twe_db_prepare_input($_POST['zone_id']);
      $state = twe_db_prepare_input($_POST['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      $zone_id = 0;
      $check_query = "select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'";
      $check = $db->Execute($check_query);
      $entry_state_has_zones = ($check->fields['total'] > 0);
      if ($entry_state_has_zones == true) {
        $zone_query = "select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . twe_db_input($state) . "%' or zone_code like '%" . twe_db_input($state) . "%')";
        $zone = $db->Execute($zone_query);
      
	    if ($zone->RecordCount() == 1) {
          $zone_id = $zone->fields['zone_id'];
        } else {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR);
        }
      }
    }

    if ($error == false) {
      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
							  'entry_telephone' => $telephone,
							  'entry_fax' => $fax,
                              'entry_country_id' => (int)$country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = (int)$zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($_POST['action'] == 'update') {
        twe_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_GET['edit'] . "' and customers_id ='" . (int)$_SESSION['customer_id'] . "'");

        // reregister session variables
        if ( (isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $_SESSION['customer_default_address_id']) ) {
          $_SESSION['customer_first_name'] = $firstname;
          $_SESSION['customer_country_id'] = $country_id;
          $_SESSION['customer_zone_id'] = (($zone_id > 0) ? (int)$zone_id : '0');
          $_SESSION['customer_default_address_id'] = (int)$_GET['edit'];

          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_default_address_id' => (int)$_GET['edit']);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

          twe_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        }
      } else {
        $sql_data_array['customers_id'] = (int)$_SESSION['customer_id'];
        twe_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        $new_address_book_id = $db->Insert_ID();

        // reregister session variables
        if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
          $_SESSION['customer_first_name'] = $firstname;
          $_SESSION['customer_country_id'] = $country_id;
          $_SESSION['customer_zone_id'] = (($zone_id > 0) ? (int)$zone_id : '0');
          if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) $_SESSION['customer_default_address_id'] = $new_address_book_id;

          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
          if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) $sql_data_array['customers_default_address_id'] = $new_address_book_id;

          twe_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$_SESSION['customer_id'] . "'");
        }
      }

      $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');

      twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $entry_query = "select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id, entry_telephone, entry_fax from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$_SESSION['customer_id'] . "' and address_book_id = '" . (int)$_GET['edit'] . "'";
    $entry = $db->Execute($entry_query);
    if ($entry->RecordCount()<=0) {
      $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);
      twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $_SESSION['customer_default_address_id']) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

      twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
      $check_query = "select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$_SESSION['customer_id'] . "'";
      $check = $db->Execute($check_query);

      if ($check->fields['total'] < 1) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
      }
    }
  } else {
    $entry = array();
  }

  if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (twe_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

      twe_redirect(twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1_ADDRESS_BOOK_PROCESS, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ADDRESS_BOOK_PROCESS, twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY_ADDRESS_BOOK_PROCESS, twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY_ADDRESS_BOOK_PROCESS, twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY_ADDRESS_BOOK_PROCESS, twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
  }

 require(DIR_WS_INCLUDES . 'header.php');
 if (isset($_GET['delete']) == false) $action= twe_draw_form('addressbook', twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"');

  $smarty->assign('FORM_ACTION',$action);
  if ($messageStack->size('addressbook') > 0) {
  $smarty->assign('error',$messageStack->output('addressbook'));

  }

  if (isset($_GET['delete'])) {
  $smarty->assign('delete','1');
  $smarty->assign('ADDRESS',twe_address_label($_SESSION['customer_id'], $_GET['delete'], true, ' ', '<br>'));

$smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
$smarty->assign('BUTTON_DELETE','<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL') . '">' . twe_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>');
  } else {

 include(DIR_WS_MODULES . 'address_book_details.php');

    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
    $smarty->assign('BUTTON_UPDATE',twe_draw_hidden_field('action', 'update') . twe_draw_hidden_field('edit', $_GET['edit']) . twe_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE));

    } else {
      if (sizeof($_SESSION['navigation']->snapshot) > 0) {
        $back_link = twe_href_link($_SESSION['navigation']->snapshot['page'], twe_array_to_string($_SESSION['navigation']->snapshot['get'], array(twe_session_name())), $_SESSION['navigation']->snapshot['mode']);
      } else {
        $back_link = twe_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
      $smarty->assign('BUTTON_BACK','<a href="' . $back_link . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
      $smarty->assign('BUTTON_UPDATE',twe_draw_hidden_field('action', 'process') . twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

    }
  }

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/address_book_process.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>