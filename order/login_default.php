<?php
if(!isset($_SESSION['customer_id']) || !$_SESSION['customer_id']){
	require_once(DIR_FS_INC . 'twe_draw_password_field.inc.php');
	require_once(DIR_FS_INC . 'twe_validate_password.inc.php');
	require_once(DIR_FS_INC . 'twe_array_to_string.inc.php');
	require_once(DIR_FS_INC . 'twe_image_button.inc.php');
	require_once(DIR_FS_INC . 'twe_write_user_info.inc.php');

	$email_address = twe_db_prepare_input($default_account);
	$password = twe_db_prepare_input($default_password);

	// Check if email exists
	$check_customer_query = "select customers_id, customers_firstname,customers_lastname, customers_gender, customers_password, customers_email_address, customers_default_address_id, username, user_active from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($email_address) . "'";
	$check_customer = $db->Execute($check_customer_query);

	if (!$check_customer->RecordCount()) {
	  $error = true;
	} else {
	  // Check that password is good
	  if (!twe_validate_password($password, $check_customer->fields['customers_password'])) {
		$_GET['login'] = 'fail';
		$info_message=TEXT_LOGIN_ERROR;
	  } else {
		if (SESSION_RECREATE == 'True') {
		  twe_session_recreate();
		}

		$check_country_query = "select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer->fields['customers_id'] . "' and address_book_id = '" . $check_customer->fields['customers_default_address_id'] . "'";
		$check_country = $db->Execute($check_country_query);

		$_SESSION['customer_gender'] = $check_customer->fields['customers_gender'];
		$_SESSION['customer_last_name'] = $check_customer->fields['customers_lastname'];
		$_SESSION['customer_id'] = $check_customer->fields['customers_id'];
		$_SESSION['customer_default_address_id'] = $check_customer->fields['customers_default_address_id'];
		$_SESSION['customer_first_name'] = $check_customer->fields['customers_firstname'];
		$_SESSION['customer_country_id'] = $check_country->fields['entry_country_id'];
		$_SESSION['customer_zone_id'] = $check_country->fields['entry_zone_id'];

		$date_now = date('Ymd');
		$db->Execute("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$_SESSION['customer_id'] . "'");

	//update bb user last_visit       
		$last_visit = 0;
		$current_time = time();
		$check_bbusers_query = "select *  from " . TABLE_CUSTOMERS . " where customers_id = '" . $check_customer->fields['customers_id'] . "'";
		$userdata = $db->Execute($check_bbusers_query);
		$last_visit = ( $userdata->fields['user_session_time'] > 0 ) ? $userdata->fields['user_session_time'] : $current_time; 
		$db->Execute("UPDATE " .  TABLE_CUSTOMERS . " SET user_session_time = '".$current_time."', user_session_page = '0', user_lastvisit = '".$last_visit."'
			WHERE customers_id ='" . $check_customer->fields['customers_id'] . "'");
		$userdata->fields['user_lastvisit'] = $last_visit;

		// restore cart contents
		$_SESSION['cart']->restore_contents();
		
		if(isset($_REQUEST['outerFrame'])){
			$url = twe_href_link(FILENAME_DEFAULT);
			$url = addGetVar($url, 'outerFrame', $_REQUEST['outerFrame']);
			twe_redirect($url);
		}
		else if (sizeof($_SESSION['navigation']->snapshot) > 0) {
		  $origin_href = twe_href_link($_SESSION['navigation']->snapshot['page'], twe_array_to_string($_SESSION['navigation']->snapshot['get'], array(twe_session_name())), $_SESSION['navigation']->snapshot['mode']);
		  $_SESSION['navigation']->clear_snapshot();
		  twe_redirect($origin_href);
		} else {
		  twe_redirect(twe_href_link(FILENAME_DEFAULT));
		}
	  }
	}
}
?>