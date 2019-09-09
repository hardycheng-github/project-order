<?php
/* -----------------------------------------------------------------------------------------
   $Id: create_account.php,v 1.12 2005/04/22 13:18:46 oldpa Exp $   

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
-----------------------------------------------------------------------------------------
   Third Party contribution:

   Credit Class/Gift Vouchers/Discount Coupons (Version 5.10)
   http://www.oscommerce.com/community/contributions,282
   Copyright (c) Strider | Strider@oscworks.com
   Copyright (c  Nick Stanko of UkiDev.com, nick@ukidev.com
   Copyright (c) Andre ambidex@gmx.net
   Copyright (c) 2001,2002 Ian C Wilson http://www.phesis.org   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');

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
  require_once(DIR_FS_INC . 'twe_php_mail.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_pull_down_menu.inc.php');
  require_once(DIR_FS_INC . 'twe_prepare_country_zones_pull_down.inc.php');
  require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');
  require_once(DIR_FS_INC . 'twe_get_countries_list.inc.php');
  require_once(DIR_WS_CLASSES . 'class.phpmailer.php');



  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') $gender = twe_db_prepare_input($_POST['gender']);
    $firstname = twe_db_prepare_input($_POST['firstname']);
    $lastname = twe_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true'){
	$dob_year = twe_db_prepare_input($_POST['dob_year']);
	$dob_month= twe_db_prepare_input($_POST['dob_month']);
	$dob_day = twe_db_prepare_input($_POST['dob_day']);
	$dob = $dob_month . "/" . $dob_day . "/" . $dob_year;
	}
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
	if(ACCOUNT_LASTNAME == 'true') {
	$check_name_query = "select count(*) as total from " . TABLE_CUSTOMERS . " where customers_lastname = '" . twe_db_input($lastname) . "'";
      $check_name = $db->Execute($check_name_query);
      if ($check_name->fields['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR_EXISTS);
      }
     }
    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(twe_date_raw($dob), 4, 2), substr(twe_date_raw($dob), 6, 2), substr(twe_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (twe_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = "select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($email_address) . "'";
      $check_email = $db->Execute($check_email_query);
      if ($check_email->fields['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
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
if (is_numeric($zone_id) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
    }

   /* if (ACCOUNT_STATE == 'true') {
    if($state ==''|| $state == JS_STATE_SELECT)  
      if (!$zone_id) {
	  $error = true;
          $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
	  if (!$zone_id) {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }*/

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_status' => DEFAULT_CUSTOMERS_STATUS_ID,
	  		                  'customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => $newsletter,
                              'customers_password' => twe_encrypt_password($password),
							  'username' => $lastname,
							  'user_regdate' => time(),
							  'user_active' => '1');
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
							  'entry_zone_id' => $zone_id,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      
	 /* if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }*/

      twe_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = $db->Insert_ID();

      $db->Execute("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . $address_id . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");

      $db->Execute("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$_SESSION['customer_id'] . "', '0', now())");
      $db->Execute("INSERT INTO " . TABLE_PHPBB_GROUPS . " (group_name, group_description, group_single_user, group_moderator) VALUES ('', 'Personal User', 1, 0)");
	
	  $group_id =  $db->Insert_ID();

	  $db->Execute("INSERT INTO " . TABLE_PHPBB_USER_GROUP . " (customers_id, group_id, user_pending) VALUES ('" . (int)$_SESSION['customer_id'] . "', '". $group_id ."', 0)");

	  
      if (SESSION_RECREATE == 'True') {
        twe_session_recreate();
      }

      $_SESSION['customer_first_name'] = $firstname;
      $_SESSION['customer_default_address_id'] = $address_id;
      $_SESSION['customer_country_id'] = $country;
      $_SESSION['customer_zone_id'] = $zone_id;

      // restore cart contents
      $_SESSION['cart']->restore_contents();
   if (USE_POINTS_SYSTEM=='true'){
    require_once(DIR_FS_INC . 'twe_redemptions.inc.php');
      if (NEW_SIGNUP_POINT_AMOUNT > 0) {
        twe_add_welcome_points($_SESSION['customer_id']);
      }
     } 
      // build the message content
      $name = $firstname . ' ' . $lastname;
     // GV Code Start
            // ICW - CREDIT CLASS CODE BLOCK ADDED  ******************************************************* BEGIN
        if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
		
		       $smarty->assign('NEW_SIGNUP_GIFT_VOUCHER','1');
	             $coupon_code = create_coupon_code();

	            $link = HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_SHOPPING_CART;
                $db->Execute("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
                $insert_id = $db->Insert_ID();
                $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $email_address . "', now() )");
                $smarty->assign('EMAIL_GV_INCENTIVE_HEADER',$currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT));
	            $smarty->assign('EMAIL_GV_REDEEM', $coupon_code);
	            $smarty->assign('EMAIL_GV_LINK', $link);
		}
              if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
			    $smarty->assign('NEW_SIGNUP_DISCOUNT','1');
                $coupon_code = NEW_SIGNUP_DISCOUNT_COUPON;
                $coupon_query = "select * from " . TABLE_COUPONS . " where coupon_code = '" . $coupon_code . "'";
                $coupon = $db->Execute($coupon_query);
                $coupon_id = $coupon->fields['coupon_id'];
                $coupon_desc_query = "select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . (int)$_SESSION['languages_id'] . "'";
                $coupon_desc = $db->Execute($coupon_desc_query);
                $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $email_address . "', now() )");
                $smarty->assign('EMAIL_COUPON_DESC', $coupon_desc->fields['coupon_description']);
	            $smarty->assign('EMAIL_COUPON_REDEEM', $coupon->fields['coupon_code']);
			   }
            // ICW - CREDIT CLASS CODE BLOCK ADDED  ******************************************************* END
            // GV Code End


      // load data into array
      $module_content = array();
      $module_content = array(
        'MAIL_NAME' => $name,
        'MAIL_REPLY_ADDRESS' => EMAIL_SUPPORT_REPLY_ADDRESS,
        'MAIL_GENDER'=>$gender);
		
	       // assign data to smarty
      $smarty->assign('language', $_SESSION['language']);
      $smarty->assign('logo_path',HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
      $smarty->assign('NAME',$name);
	  $smarty->assign('content', $module_content);
      $smarty->caching = false;

      // create templates
      $smarty->caching = 0;
      $html_mail = $smarty->fetch(CURRENT_TEMPLATE . '/mail/'.$_SESSION['language'].'/create_account_mail.html');
      $smarty->caching = 0;
      $txt_mail = $smarty->fetch(CURRENT_TEMPLATE . '/mail/'.$_SESSION['language'].'/create_account_mail.txt');

      twe_php_mail(EMAIL_SUPPORT_ADDRESS,EMAIL_SUPPORT_NAME,$email_address , $name , EMAIL_SUPPORT_FORWARDING_STRING, EMAIL_SUPPORT_REPLY_ADDRESS, EMAIL_SUPPORT_REPLY_ADDRESS_NAME, '', '', EMAIL_SUPPORT_SUBJECT, $html_mail, $txt_mail);

      if (!isset($mail_error)) {
          twe_redirect(twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
      }
      else {
          echo $mail_error;
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_CREATE_ACCOUNT, twe_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');


  if ($messageStack->size('create_account') > 0) {
  $smarty->assign('error',$messageStack->output('create_account'));

  }
  $smarty->assign('FORM_ACTION',twe_draw_form('create_account', twe_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . twe_draw_hidden_field('action', 'process'));

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
  $y =array();
 for($i = 99; $i >= 0; $i--){
                    if($i < 10){
                      $y[] = array('id' =>  "190" . $i,'text' => "190" . $i);
                    }
                    else{
                      $y[] = array('id' =>  "19" . $i,'text' => "19" . $i);
                    } 
				}
  $smarty->assign('INPUT_YEAR',twe_draw_pull_down_menu('dob_year', $y, $y));
 $m =array();
 for($i = 1; $i <= 12; $i++){
                    if($i < 10){
                      $m[]= array('id' => "0" . $i,'text' => "0" . $i);
                    }else{
			          $m[]= array('id' => $i,'text' => $i);
		}
}
  $smarty->assign('INPUT_MONTH',twe_draw_pull_down_menu('dob_month', $m, $m));
$d = array();
for($i = 1; $i <= 31; $i++){
                    if($i < 10){
                      $d[]= array('id' => "0" . $i,'text' => "0" . $i);
                     }else{
			          $d[]= array('id' => $i,'text' => $i);
				}    
		      }					
  $smarty->assign('INPUT_DAY',twe_draw_pull_down_menu('dob_day', $d, $d). '&nbsp;' . (twe_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''));
   //$smarty->assign('INPUT_DOB',twe_draw_input_field('dob') . '&nbsp;' . (twe_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''));
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
  $smarty->assign('state','1');    
  $smarty->assign('INPUT_STATE',twe_draw_pull_down_menu('zone_id', twe_prepare_country_zones_pull_down(STORE_COUNTRY), $zone_id));
 
 /* if (ACCOUNT_STATE == 'true') {
  $smarty->assign('state','1');    
  $smarty->assign('INPUT_STATE',twe_draw_pull_down_menu('zone_id', twe_prepare_country_zones_pull_down(STORE_COUNTRY), $zone_id, 'onChange="resetStateText(this.form);"').'<br>'.twe_draw_input_field('state', $state, 'maxlength="32" onChange="resetZoneSelected(this.form);"'));
  } else {
  $smarty->assign('state','0');
  } */

  $smarty->assign('SELECT_COUNTRY',twe_draw_pull_down_menu('country', twe_get_countries_list(), STORE_COUNTRY, 'onChange="update_zone(this.form);"') . '&nbsp;' . (twe_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''));
  $smarty->assign('INPUT_TEL',twe_draw_input_field('telephone') . '&nbsp;' . (twe_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('INPUT_FAX',twe_draw_input_field('fax') . '&nbsp;' . (twe_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''));
  $smarty->assign('CHECKBOX_NEWSLETTER',twe_draw_checkbox_field('newsletter', '1') . '&nbsp;' . (twe_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="inputRequirement">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''));
  $smarty->assign('INPUT_PASSWORD',twe_draw_password_field('password') . '&nbsp;' . (twe_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''));
  $smarty->assign('INPUT_CONFIRMATION',twe_draw_password_field('confirmation') . '&nbsp;' . (twe_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''));

  $smarty->assign('language', $_SESSION['language']);
  $smarty->caching = 0;
  $smarty->assign('BUTTON_SUBMIT',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
  if($_SESSION['language'] == 'tchinese'){
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/create_account.html');
   }else{
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/create_account_en.html');
   }
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>