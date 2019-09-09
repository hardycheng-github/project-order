<?php
/* -----------------------------------------------------------------------------------------
   $Id: checkout_shipping_address.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(checkout_shipping_address.php,v 1.14 2003/05/27); www.oscommerce.com
   (c) 2003	 nextcommerce (checkout_shipping_address.php,v 1.14 2003/08/17); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
     // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_count_customer_address_book_entries.inc.php');
  require_once(DIR_FS_INC . 'twe_address_label.inc.php');
  require_once(DIR_FS_INC . 'twe_get_address_format_id.inc.php');
  require_once(DIR_FS_INC . 'twe_address_format.inc.php');
  require_once(DIR_FS_INC . 'twe_get_country_name.inc.php');
  require_once(DIR_FS_INC . 'twe_get_countries.inc.php');
  require_once(DIR_FS_INC . 'twe_get_zone_code.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_radio_field.inc.php');
   require_once(DIR_FS_INC . 'twe_js_zone_list.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  // if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  // if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    twe_redirect(twe_href_link(FILENAME_SHOPPING_CART));
  }


  // if the order contains only virtual products, forward the customer to the billing page as
  // a shipping address is not needed
  if ($order->content_type == 'virtual') {
    $_SESSION['shipping'] = false;
    $_SESSION['sendto'] = false;
    twe_redirect(twe_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  $error = false;
  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'submit')) {
    // process a new shipping address
    if (twe_not_null($_POST['firstname']) && twe_not_null($_POST['lastname']) && twe_not_null($_POST['street_address'])) {
      $process = true;

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

          $messageStack->add('checkout_address', ENTRY_GENDER_ERROR);
        }
      }

      if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);
      }

      if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);
      }

      if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);
      }

      if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);
      }

      if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_CITY_ERROR);
      }

      if (ACCOUNT_STATE == 'true') {
        $zone_id = 0;
        $check_query = "select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'";
        $check = $db->Execute($check_query);
        $entry_state_has_zones = ($check->fields['total'] > 0);
        if ($entry_state_has_zones == true) {
          $zone = $db->Execute("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . twe_db_input($state) . "%' or zone_code like '%" . twe_db_input($state) . "%')");
          if ($zone->RecordCount() == 1) {
            $zone_id = $zone->fields['zone_id'];
          } else {
            $error = true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT);
          }
        } else {
          if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
            $error = true;

            $messageStack->add('checkout_address', ENTRY_STATE_ERROR);
          }
        }
      }

      if ( (is_numeric($country) == false) || ($country < 1) ) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
      }

      if ($error == false) {
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

        $_SESSION['sendto'] = $db->Insert_ID();

        twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      }
    // process the selected shipping destination
    } elseif (isset($_POST['address'])) {
      $reset_shipping = false;
      if (isset($_SESSION['sendto'])) {
        if ($_SESSION['sendto'] != $_POST['address']) {
          if (isset($_SESSION['shipping'])) {
            $reset_shipping = true;
          }
        }
      }

      $_SESSION['sendto'] = $_POST['address'];

      $check_address_query = "select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_SESSION['customer_id'] . "' and address_book_id = '" . $_SESSION['sendto'] . "'";
      $check_address = $db->Execute($check_address_query);

      if ($check_address->fields['total'] == '1') {
        if ($reset_shipping == true) unset($_SESSION['shipping']);
        twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
      } else {
        unset($_SESSION['sendto']);
      }
    } else {
      $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];

      twe_redirect(twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

  // if no shipping destination address was selected, use their own address as default
  if (!isset($_SESSION['sendto'])) {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
  }

  $breadcrumb->add(NAVBAR_TITLE_1_CHECKOUT_SHIPPING_ADDRESS, twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_CHECKOUT_SHIPPING_ADDRESS, twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'));

  $addresses_count = twe_count_customer_address_book_entries();

   require(DIR_WS_INCLUDES . 'header.php');
  $smarty->assign('FORM_ACTION',twe_draw_form('checkout_address', twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'post', 'onSubmit="return check_form_optional(checkout_address);"'));

  if ($messageStack->size('checkout_address') > 0) {
  $smarty->assign('error',$messageStack->output('checkout_address'));


  }

  if ($process == false) {
  $smarty->assign('ADDRESS_LABEL',twe_address_label($_SESSION['customer_id'], $_SESSION['sendto'], true, ' ', '<br>'));

    if ($addresses_count > 1) {

$address_content='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
      $radio_buttons = 0;

      $addresses = $db->Execute("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id, entry_telephone as telephone, entry_fax as fax from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . $_SESSION['customer_id'] . "'");
      while (!$addresses->EOF) {
        $format_id = twe_get_address_format_id($address->fields['country_id']);

 $address_content.=' <tr>
                <td>'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                ';

       if ($addresses->fields['address_book_id'] == $_SESSION['sendto']) {
          $address_content.= '                  <tr>' . "\n";
        } else {
          $address_content.= '                  <tr>' . "\n";
        }
$address_content.= '
                    <td width="10">'.twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td class="main" colspan="2"><b>'. $addresses->fields['firstname'] . ' ' . $addresses->fields['lastname'].'</b></td>
                    <td class="main" align="right">'. twe_draw_radio_field('address', $addresses->fields['address_book_id'], ($addresses->fields['address_book_id'] == $_SESSION['sendto'])).'</td>
                    <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>
                  <tr>
                    <td width="10">'.  twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                    <td colspan="3"><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10">'.  twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                        <td class="main">'.  twe_address_format($format_id, $addresses->fields, true, ' ', ', ').'</td>
                        <td width="10">'.  twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                      </tr>
                    </table></td>
                    <td width="10">'.  twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  </tr>
                </table></td>
                <td>'.  twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
              </tr>';

        $radio_buttons++;
	$addresses->MoveNext();	
      }
$address_content.='</table>';
$smarty->assign('BLOCK_ADDRESS',$address_content);
    }
  }

  if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {

 require(DIR_WS_MODULES . 'checkout_new_address.php');

  }
  $smarty->assign('BUTTON_CONTINUE',twe_draw_hidden_field('action', 'submit') . twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));

  if ($process == true) {
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');

  }

  $smarty->assign('language', $_SESSION['language']);

  $smarty->caching = 0;
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/checkout_shipping_address.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
?>