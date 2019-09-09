<?php
/* --------------------------------------------------------------
   $Id: customers.php,v 1.11 2004/04/14 19:14:06 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(customers.php,v 1.76 2003/05/04); www.oscommerce.com 
   (c) 2003	 nextcommerce (customers.php,v 1.22 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');


  $customers_statuses_array = twe_get_customers_statuses();
  
  if ($_GET['special'] == 'remove_memo') {
    $mID = twe_db_prepare_input($_GET['mID']);
    $db->Execute("DELETE FROM " . TABLE_CUSTOMERS_MEMO . " WHERE memo_id = '". $mID . "'");
    twe_redirect(twe_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$_GET['cID'] . '&action=edit'));
  }

  if ($_GET['action'] == 'edit' || $_GET['action'] == 'update') {
    if ($_GET['cID'] == 2 && $_SESSION['customer_id'] == 2)  {
    } else {
      if ($_GET['cID'] != 2)  {
      } else {
        twe_redirect(twe_href_link(FILENAME_CUSTOMERS, ''));
      }
    }
  }

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'statusconfirm':
        $customers_id = twe_db_prepare_input($_GET['cID']);
        $customer_updated = false;
        $check_status_query = "select customers_firstname, customers_lastname, customers_email_address , customers_status, member_flag from " . TABLE_CUSTOMERS . " where customers_id = '" . twe_db_input($_GET['cID']) . "'";
        $check_status =  $db->Execute($check_status_query);
        if ($check_status->fields['customers_status'] != $status) {
          $db->Execute("update " . TABLE_CUSTOMERS . " set customers_status = '" . twe_db_input($_POST['status']) . "' where customers_id = '" . twe_db_input($_GET['cID']) . "'");

    // create insert for admin access table if customers status is set to 0
    if ($_POST['status']==0) {
    $db->Execute("INSERT into ".TABLE_ADMIN_ACCESS." (customers_id,start) VALUES ('".twe_db_input($_GET['cID'])."','1')");    	
	} else {	
	$db->Execute("DELETE FROM ".TABLE_ADMIN_ACCESS." WHERE customers_id = '".twe_db_input($_GET['cID'])."'");	

	}
    //Temporarily set due to above commented lines
          $customer_notified = '0';
          $db->Execute("insert into " . TABLE_CUSTOMERS_STATUS_HISTORY . " (customers_id, new_value, old_value, date_added, customer_notified) values ('" . twe_db_input($_GET['cID']) . "', '" . twe_db_input($_POST['status']) . "', '" . $check_status->fields['customers_status'] . "', now(), '" . $customer_notified . "')");
          $customer_updated = true;
		twe_redirect(twe_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$_GET['cID'], 'NONSSL'));
        }
        break;

      case 'update':
        $customers_id = twe_db_prepare_input($_GET['cID']);
        $customers_cid = twe_db_prepare_input($_POST['csID']);
        $customers_firstname = twe_db_prepare_input($_POST['customers_firstname']);
        $customers_lastname = twe_db_prepare_input($_POST['customers_lastname']);
        $customers_email_address = twe_db_prepare_input($_POST['customers_email_address']);
        $customers_telephone = twe_db_prepare_input($_POST['customers_telephone']);
        $customers_fax = twe_db_prepare_input($_POST['customers_fax']);
        $customers_newsletter = twe_db_prepare_input($_POST['customers_newsletter']);

        $customers_gender = twe_db_prepare_input($_POST['customers_gender']);
        $customers_dob = twe_db_prepare_input($_POST['customers_dob']);

        $default_address_id = twe_db_prepare_input($_POST['default_address_id']);
        $entry_street_address = twe_db_prepare_input($_POST['entry_street_address']);
        $entry_suburb = twe_db_prepare_input($_POST['entry_suburb']);
        $entry_postcode = twe_db_prepare_input($_POST['entry_postcode']);
        $entry_city = twe_db_prepare_input($_POST['entry_city']);
        $entry_country_id = twe_db_prepare_input($_POST['entry_country_id']);

        $entry_company = twe_db_prepare_input($_POST['entry_company']);
        $entry_state = twe_db_prepare_input($_POST['entry_state']);
        $entry_zone_id = twe_db_prepare_input($_POST['entry_zone_id']);

        $memo_title = twe_db_prepare_input($_POST['memo_title']);
        $memo_text = twe_db_prepare_input($_POST['memo_text']);

        if ($memo_text != '' && $memo_title != '' ) {
          $sql_data_array = array(
            'customers_id' => $_GET['cID'],
            'memo_date' => date("Y-m-d"),
            'memo_title' =>$memo_title,
            'memo_text' =>$memo_text,
            'poster_id' => $_SESSION['customer_id']
          );
          twe_db_perform(TABLE_CUSTOMERS_MEMO, $sql_data_array);
        }
        $error = false; // reset error flag

        if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_firstname_error = true;
        } else {
          $entry_firstname_error = false;
        }

        if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_lastname_error = true;
        } else {
          $entry_lastname_error = false;
        }

        if (ACCOUNT_DOB == 'true') {
          if (checkdate(substr(twe_date_raw($customers_dob), 4, 2), substr(twe_date_raw($customers_dob), 6, 2), substr(twe_date_raw($customers_dob), 0, 4))) {
            $entry_date_of_birth_error = false;
          } else {
            $error = true;
            $entry_date_of_birth_error = true;
          }
        }

        if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_email_address_error = true;
        } else {
          $entry_email_address_error = false;
        }

        if (!twe_validate_email($customers_email_address)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }

        if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_street_address_error = true;
        } else {
          $entry_street_address_error = false;
        }

        if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $entry_post_code_error = true;
        } else {
          $entry_post_code_error = false;
        }

        if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
          $error = true;
          $entry_city_error = true;
        } else {
          $entry_city_error = false;
        }

        if ($entry_country_id == false) {
          $error = true;
          $entry_country_error = true;
        } else {
          $entry_country_error = false;
        }

        if (ACCOUNT_STATE == 'true') {
          if ($entry_country_error == true) {
            $entry_state_error = true;
          } else {
            $zone_id = 0;
            $entry_state_error = false;
            $check_query = "select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . twe_db_input($entry_country_id) . "'";
            $check_value = $db->Execute($check_query);
            $entry_state_has_zones = ($check_value->fields['total'] > 0);
            if ($entry_state_has_zones == true) {
              $zone_query = "select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . twe_db_input($entry_country_id) . "' and zone_name = '" . twe_db_input($entry_state) . "'";
              $zone_values = $db->Execute($zone_query);
              if ($zone_values->RecordCount() == 1) {
                $entry_zone_id = $zone_values->fields['zone_id'];
              } else {
                $zone_query = "select zone_id from " . TABLE_ZONES . " where zone_country_id = '" . twe_db_input($entry_country) . "' and zone_code = '" . twe_db_input($entry_state) . "'";
                 $zone_values = $db->Execute($zone_query);
			    if ($zone_values->RecordCount() >= 1) {
                  $zone_id = $zone_values->fields['zone_id'];
                } else {
                  $error = true;
                  $entry_state_error = true;
                }
              }
            } else {
              if ($entry_state == false) {
                $error = true;
                $entry_state_error = true;
              }
            }
          }
        }

        if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
          $error = true;
          $entry_telephone_error = true;
        } else {
          $entry_telephone_error = false;
        }

        $check_email = $db->Execute("select customers_email_address from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($customers_email_address) . "' and customers_id <> '" . twe_db_input($customers_id) . "'");
        if ($check_email->RecordCount()>0) {
          $error = true;
          $entry_email_address_exists = true;
        } else {
          $entry_email_address_exists = false;
        }

        if ($error == false) {
          $sql_data_array = array(
            'customers_firstname' => $customers_firstname,
            'customers_cid' => $customers_cid,
            'customers_lastname' => $customers_lastname,
            'customers_email_address' => $customers_email_address,
            'customers_telephone' => $customers_telephone,
            'customers_fax' => $customers_fax,
            'customers_newsletter' => $customers_newsletter,
			'username' => $customers_lastname);

          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
          if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = twe_date_raw($customers_dob);

          twe_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . twe_db_input($customers_id) . "'");

          $db->Execute("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . twe_db_input($customers_id) . "'");

          if ($entry_zone_id > 0) $entry_state = '';

          $sql_data_array = array(
            'entry_firstname' => $customers_firstname,
            'entry_lastname' => $customers_lastname,
            'entry_street_address' => $entry_street_address,
            'entry_postcode' => $entry_postcode,
            'entry_city' => $entry_city,
            'entry_country_id' => $entry_country_id);

          if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $entry_company;
          if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;

          if (ACCOUNT_STATE == 'true') {
            if ($entry_zone_id > 0) {
              $sql_data_array['entry_zone_id'] = $entry_zone_id;
              $sql_data_array['entry_state'] = '';
            } else {
              $sql_data_array['entry_zone_id'] = '0';
              $sql_data_array['entry_state'] = $entry_state;
            }
          }

          twe_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . twe_db_input($customers_id) . "' and address_book_id = '" . twe_db_input($default_address_id) . "'");
          twe_redirect(twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
        } elseif ($error == true) {
          $cInfo = new objectInfo($_POST);
          $processed = true;
        }

        break;
      case 'deleteconfirm':
        $customers_id = twe_db_prepare_input($_GET['cID']);

        if ($_POST['delete_reviews'] == 'on') {
          $reviews = $db->Execute("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . twe_db_input($customers_id) . "'");
          while (!$reviews->EOF) {
            $db->Execute("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . $reviews->fields['reviews_id'] . "'");
           $reviews->MoveNext();
		  }
          $db->Execute("delete from " . TABLE_REVIEWS . " where customers_id = '" . twe_db_input($customers_id) . "'");
        } else {
          $db->Execute("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . twe_db_input($customers_id) . "'");
        }

        $db->Execute("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS_STATUS_HISTORY . " where customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("delete from " . TABLE_CUSTOMERS_IP . " where customers_id = '" . twe_db_input($customers_id) . "'");
        //--------del phpbb user
		$usergroup_query = "SELECT g.group_id FROM " . TABLE_PHPBB_USER_GROUP . " ug, " . TABLE_PHPBB_GROUPS . " g WHERE ug.customers_id = '" . twe_db_input($customers_id) . "' 
		AND g.group_id = ug.group_id 
		AND g.group_single_user = 1";
		$usergroup = $db->Execute($usergroup_query);
      
	    $db->Execute("update " . TABLE_PHPBB_POSTS . " SET poster_id = '-1', post_username = '1' WHERE poster_id = '" . twe_db_input($customers_id) . "'");
	    $db->Execute("update " . TABLE_PHPBB_TOPICS ." SET topic_poster = '1' WHERE topic_poster = '" . twe_db_input($customers_id) . "'");
	    $db->Execute("update " . TABLE_PHPBB_VOTE_USERS ." SET vote_user_id = '1' WHERE vote_user_id = '" . twe_db_input($customers_id) . "'");

        $group = $db->Execute("SELECT group_id FROM " . TABLE_PHPBB_GROUPS . " WHERE group_moderator = '" . twe_db_input($customers_id) . "'");
		while(!$group->EOF) {
		 $group_moderator[] = $group->fields['group_id'];
		$group->MoveNext(); 
         }
		if ( count($group_moderator) )
        {
		$update_moderator_id = implode(', ', $group_moderator);
	    $db->Execute("UPDATE " . TABLE_PHPBB_GROUPS . "	SET group_moderator = " . (int)$customers_id . " WHERE group_moderator IN ($update_moderator_id)");
		}
        $db->Execute("DELETE FROM " . TABLE_PHPBB_USER_GROUP . "	WHERE customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("DELETE FROM " . TABLE_PHPBB_GROUPS . "	WHERE group_id = '" . $usergroup->fields['group_id']."'");
        $db->Execute("DELETE FROM " . TABLE_PHPBB_AUTH_ACCESS . "	WHERE group_id = '" . $usergroup->fields['group_id']."'");
        $db->Execute("DELETE FROM " . TABLE_PHPBB_TOPICS_WATCH . "	WHERE customers_id = '" . twe_db_input($customers_id) . "'");
        $db->Execute("DELETE FROM " . TABLE_PHPBB_BANLIST . "	WHERE ban_userid = '" . twe_db_input($customers_id) . "'");
        $row_privmsgs = $db->Execute("SELECT privmsgs_id FROM " . TABLE_PHPBB_PRIVMSGS . " WHERE privmsgs_from_userid = '" . twe_db_input($customers_id) . "' OR privmsgs_to_userid = '" . twe_db_input($customers_id) . "'");
        while (!$row_privmsgs->EOF){
		$mark_list[] = $row_privmsgs->fields['privmsgs_id'];
		$row_privmsgs->MoveNext();
		}			
		if ( count($mark_list) ){
		$delete_sql_id = implode(', ', $mark_list);
		$db->Execute("DELETE FROM " . TABLE_PHPBB_PRIVMSGS_TEXT . "	WHERE privmsgs_text_id IN ($delete_sql_id)");
		$db->Execute("DELETE FROM " . TABLE_PHPBB_PRIVMSGS . " WHERE privmsgs_id IN ($delete_sql_id)");
        }		

		twe_redirect(twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')))); 
        break;

      default:
        $customers_query = "select c.customers_id,c.customers_cid, c.customers_status, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . $_GET['cID'] . "'";
        $customers = $db->Execute($customers_query);
        $cInfo = new objectInfo($customers->fields);
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
<?php
  if ($_GET['action'] == 'edit' || $_GET['action'] == 'update') {
?>
<script language="javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var customers_firstname = document.customers.customers_firstname.value;
  var customers_lastname = document.customers.customers_lastname.value;
<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
  var customers_email_address = document.customers.customers_email_address.value;  
  var entry_street_address = document.customers.entry_street_address.value;
  var entry_postcode = document.customers.entry_postcode.value;
  var entry_city = document.customers.entry_city.value;
  var customers_telephone = document.customers.customers_telephone.value;

<?php if (ACCOUNT_GENDER == 'true') { ?>
  if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
  } else {
    error_message = error_message + "<?php echo JS_GENDER; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
    error = 1;
  }

  if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
    error = 1;
  }

<?php if (ACCOUNT_DOB == 'true') { ?>
  if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_DOB; ?>";
    error = 1;
  }
<?php } ?>

  if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_ADDRESS; ?>";
    error = 1;
  }

  if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_POST_CODE; ?>";
    error = 1;
  }

  if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_CITY; ?>";
    error = 1;
  }

<?php
  if (ACCOUNT_STATE == 'true') {
?>
  if (document.customers.elements['entry_state'].type != "hidden") {
    if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
       error_message = error_message + "<?php echo JS_STATE; ?>";
       error = 1;
    }
  }
<?php
  }
?>

  if (document.customers.elements['entry_country_id'].type != "hidden") {
    if (document.customers.entry_country_id.value == 0) {
      error_message = error_message + "<?php echo JS_COUNTRY; ?>";
      error = 1;
    }
  }

  if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
    error = 1;
  }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($_GET['action'] == 'edit' || $_GET['action'] == 'update') {
    $customers_query = "select c.customers_gender,c.customers_status, c.member_flag, c.customers_firstname,c.customers_cid, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, c.customers_telephone, c.customers_fax, c.customers_newsletter, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . $_GET['cID'] . "'";

    $customers = $db->Execute($customers_query);
    $cInfo = new objectInfo($customers->fields);
    $newsletter_array = array(array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES), array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO));
?>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_customers.gif'); ?></td>
    <td class="pageHeading"><?php echo $cInfo->customers_lastname.' '.$cInfo->customers_firstname; ?></td>
  </tr>
  <tr> 
    <td class="main" valign="top">TWE Customers</td>
  </tr>
</table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="middle" class="pageHeading"><?php if ($customers_statuses_array[$customers->fields['customers_status']]['csa_image'] != '') { echo twe_image(DIR_WS_ICONS . $customers_statuses_array[$customers->fields['customers_status']]['csa_image'], ''); } ?></td>
            <td class="main"></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
          <tr>
            <td colspan="3" class="main"><?php echo HEADING_TITLE_STATUS  .': ' . $customers_statuses_array[$customers->fields['customers_status']]['text'] ; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo twe_draw_form('customers', FILENAME_CUSTOMERS, twe_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . twe_draw_hidden_field('default_address_id', $cInfo->customers_default_address_id); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
<?php
    if (ACCOUNT_GENDER == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_GENDER; ?></td>
            <td class="main"><?php
      if ($error == true) {
        if ($entry_gender_error == true) {
          echo twe_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . twe_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
        } else {
          echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
          echo twe_draw_hidden_field('customers_gender');
        }
      } else {
        echo twe_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . twe_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
      }
?></td>
          </tr>
<?php
  
    }
?>
          <tr>
            <td class="main" bgcolor="#FFCC33"><?php echo ENTRY_CID; ?></td>
            <td class="main" width="100%" bgcolor="#FFCC33"><?php
       echo twe_draw_input_field('csID', $cInfo->customers_cid, 'maxlength="32"', false);

?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
            <td class="main"><?php
    if ($entry_firstname_error == true) {
      echo twe_draw_input_field('customers_firstname',$cInfo->customers_firstname, 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
    } else {
       echo twe_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true);
    }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_lastname_error == true) {
        echo twe_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
      } else {
        echo $cInfo->customers_lastname . twe_draw_hidden_field('customers_lastname');
      }
    } else {
      echo twe_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true);
    }
?></td>
          </tr>
<?php
    if (ACCOUNT_DOB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
            <td class="main"><?php
	if ($error == true) {
        if ($entry_date_of_birth_error == true) {
          echo twe_draw_input_field('customers_dob', twe_date_short($cInfo->customers_dob), 'maxlength="10"') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
        } else {
          echo $cInfo->customers_dob . twe_draw_hidden_field('customers_dob');
        }
      } else {
        echo twe_draw_input_field('customers_dob', twe_date_short($cInfo->customers_dob), 'maxlength="10"', true);
      }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_email_address_error == true) {
        echo twe_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
      } elseif ($entry_email_address_check_error == true) {
        echo twe_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
      } elseif ($entry_email_address_exists == true) {
        echo twe_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
      } else {
        echo $customers_email_address . twe_draw_hidden_field('customers_email_address');
      }
    } else {
      echo twe_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true);
    }
?></td>
          </tr>
        </table></td>
      </tr>
<?php
    if (ACCOUNT_COMPANY == 'true') {
?>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_COMPANY; ?></td>
            <td class="main"><?php
      if ($error == true) {
        if ($entry_company_error == true) {
          echo twe_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
        } else {
          echo $cInfo->entry_company . twe_draw_hidden_field('entry_company');
        }
      } else {
        echo twe_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
      }
?></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
?>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_street_address_error == true) {
        echo twe_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
      } else {
        echo $cInfo->entry_street_address . twe_draw_hidden_field('entry_street_address');
      }
    } else {
      echo twe_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
    }
?></td>
          </tr>
<?php
    if (ACCOUNT_SUBURB == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_SUBURB; ?></td>
            <td class="main"><?php
      if ($error == true) {
        if ($entry_suburb_error == true) {
          echo twe_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
        } else {
          echo $cInfo->entry_suburb . twe_draw_hidden_field('entry_suburb');
        }
      } else {
        echo twe_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
      }
?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_post_code_error == true) {
        echo twe_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
      } else {
        echo $cInfo->entry_postcode . twe_draw_hidden_field('entry_postcode');
      }
    } else {
      echo twe_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="8"', true);
    }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CITY; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_city_error == true) {
        echo twe_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
      } else {
        echo $cInfo->entry_city . twe_draw_hidden_field('entry_city');
      }
    } else {
      echo twe_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
    }
?></td>
          </tr>
<?php
    if (ACCOUNT_STATE == 'true') {
?>
          <tr>
            <td class="main"><?php echo ENTRY_STATE; ?></td>
            <td class="main"><?php
      $entry_state = twe_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state);
      if ($error == true) {
        if ($entry_state_error == true) {
          if ($entry_state_has_zones == true) {
            $zones_array = array();
            $zones_values = $db->Execute("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . twe_db_input($cInfo->entry_country_id) . "' order by zone_name");
            while (!$zones_values->EOF) {
              $zones_array[] = array('id' => $zones_values->fields['zone_name'], 'text' => $zones_values->fields['zone_name']);
            $zones_values->MoveNext();
			}
            echo twe_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
          } else {
            echo twe_draw_input_field('entry_state', twe_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
          }
        } else {
          echo $entry_state . twe_draw_hidden_field('entry_zone_id') . twe_draw_hidden_field('entry_state');
        }
      } else {
        echo twe_draw_input_field('entry_state', twe_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
      }
?></td>
         </tr>
<?php
    }
?>
          <tr>
            <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_country_error == true) {
        echo twe_draw_pull_down_menu('entry_country_id', twe_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
      } else {
        echo twe_get_country_name($cInfo->entry_country_id) . twe_draw_hidden_field('entry_country_id');
      }
    } else {
      echo twe_draw_pull_down_menu('entry_country_id', twe_get_countries(), $cInfo->entry_country_id);
    }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
            <td class="main"><?php
    if ($error == true) {
      if ($entry_telephone_error == true) {
        echo twe_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
      } else {
        echo $cInfo->customers_telephone . twe_draw_hidden_field('customers_telephone');
      }
    } else {
      echo twe_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true);
    }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
            <td class="main"><?php
    if ($processed == true) {
      echo $cInfo->customers_fax . twe_draw_hidden_field('customers_fax');
    } else {
      echo twe_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"');
    }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
            <td class="main"><?php
    if ($processed == true) {
      if ($cInfo->customers_newsletter == '1') {
        echo ENTRY_NEWSLETTER_YES;
      } else {
        echo ENTRY_NEWSLETTER_NO;
      }
      echo twe_draw_hidden_field('customers_newsletter');
    } else {
      echo twe_draw_pull_down_menu('customers_newsletter', $newsletter_array, $cInfo->customers_newsletter);
    }
?></td>
          </tr>
          <tr>
<?php include(DIR_WS_MODULES . FILENAME_CUSTOMER_MEMO); ?>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('action'))) .'">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_customers.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr> 
    <td class="main" valign="top">TWE Customers</td>
  </tr>
</table>
        
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr><?php echo twe_draw_form('search', FILENAME_CUSTOMERS, '', 'get'); ?>
            <td class="pageHeading"><?php echo '<a href="' . twe_href_link(FILENAME_CREATE_ACCOUNT) . '">' . twe_image_button('create_account.gif', CREATE_ACCOUNT) . '</a>'; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . twe_draw_input_field('search').twe_draw_hidden_field(twe_session_name(), twe_session_id()); ?></td>
          </form></tr>
          <tr><?php echo twe_draw_form('status', FILENAME_CUSTOMERS, '', 'get'); ?>
<?php
$select_data=array();
$select_data=array(array('id' => '99', 'text' => TEXT_SELECT),array('id' => '100', 'text' => TEXT_ALL_CUSTOMERS));
?>          
            <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . twe_draw_pull_down_menu('status',twe_array_merge($select_data, $customers_statuses_array), '99', 'onChange="this.form.submit();"').twe_draw_hidden_field(twe_session_name(), twe_session_id()); ?></td>




          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="1"><?php echo TABLE_HEADING_ACCOUNT_TYPE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LASTNAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FIRSTNAME; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo HEADING_TITLE_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACCOUNT_CREATED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if ( ($_GET['search']) && (twe_not_null($_GET['search'])) ) {
      $keywords = twe_db_input(twe_db_prepare_input($_GET['search']));
      $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%'";
    }

    if ($_GET['status'] && $_GET['status']!='100' or $_GET['status']=='0') {
      $status = twe_db_prepare_input($_GET['status']);
    //  echo $status;
      $search ="where c.customers_status = '". $status . "'";
    }
    $customers_query_raw = "select c.account_type,c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id, c.customers_status, c.member_flag, c.delete_user from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by c.customers_lastname, c.customers_firstname";

    $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers = $db->Execute($customers_query_raw);
    while (!$customers->EOF) {
      $info_query = "select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers->fields['customers_id'] . "'";
      $info = $db->Execute($info_query);

      if (((!$_GET['cID']) || (@$_GET['cID'] == $customers->fields['customers_id'])) && (!$cInfo)) {
        $country_query ="select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . $customers->fields['entry_country_id'] . "'";
        $country = $db->Execute($country_query);

        $reviews_query = "select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . $customers->fields['customers_id'] . "'";
        $reviews = $db->Execute($reviews_query);

        $customer_info = twe_array_merge($country->fields, $info->fields, $reviews->fields);

        $cInfo_array = twe_array_merge($customers->fields, $customer_info);
        $cInfo = new objectInfo($cInfo_array);
      }

      if ( (is_object($cInfo)) && ($customers->fields['customers_id'] == $cInfo->customers_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID')) . 'cID=' . $customers->fields['customers_id']) . '\'">' . "\n";
      }


                 if  ($customers->fields['account_type']==1) {


                echo '<td class="dataTableContent">';
                 echo TEXT_GUEST;



                 } else {
                 echo '<td class="dataTableContent">';
                 echo TEXT_ACCOUNT;
                 }
                 ?></td>
                <td class="dataTableContent"><b><?php echo $customers->fields['customers_lastname']; ?></b></td>
                <td class="dataTableContent"><?php echo $customers->fields['customers_firstname']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $customers_statuses_array[$customers->fields['customers_status']]['text'] . ' (' . $customers->fields['customers_status'] . ')' ; ?></td>
                <td class="dataTableContent" align="right"><?php echo twe_date_short($info->fields['date_account_created']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($customers->fields['customers_id'] == $cInfo->customers_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID')) . 'cID=' . $customers->fields['customers_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$customers->MoveNext();
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], twe_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
<?php
    if (twe_not_null($_GET['search'])) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS) . '">' . twe_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');

      $contents = array('form' => twe_draw_form('customers', FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
      if ($cInfo->number_of_reviews > 0) $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'editstatus':
      if ($_GET['cID'] != 2) {
        $customers_history = $db->Execute("select new_value, old_value, date_added, customer_notified from " . TABLE_CUSTOMERS_STATUS_HISTORY . " where customers_id = '" . twe_db_input($_GET['cID']) . "' order by customers_status_history_id desc");
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_STATUS_CUSTOMER . '</b>');
        $contents = array('form' => twe_draw_form('customers', FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=statusconfirm'));
        $contents[] = array('text' => '<br>' . twe_draw_pull_down_menu('status', $customers_statuses_array, $cInfo->customers_status) );
        $contents[] = array('text' => '<table nowrap border="0" cellspacing="0" cellpadding="0"><tr><td style="border-bottom: 1px solid; border-color: #000000;" nowrap class="smallText" align="center"><b>' . TABLE_HEADING_NEW_VALUE .' </b></td><td style="border-bottom: 1px solid; border-color: #000000;" nowrap class="smallText" align="center"><b>' . TABLE_HEADING_DATE_ADDED . '</b></td></tr>');

        if ($customers_history->RecordCount()>0) {
          while (!$customers_history->EOF) {

            $contents[] = array('text' => '<tr>' . "\n" . '<td class="smallText">' . $customers_statuses_array[$customers_history->fields['new_value']]['text'] . '</td>' . "\n" .'<td class="smallText" align="center">' . twe_datetime_short($customers_history->fields['date_added']) . '</td>' . "\n" .'<td class="smallText" align="center">');

            $contents[] = array('text' => '</tr>' . "\n");
         $customers_history->MoveNext();
		  }
        } else {
          $contents[] = array('text' => '<tr>' . "\n" . ' <td class="smallText" colspan="2">' . TEXT_NO_CUSTOMER_HISTORY . '</td>' . "\n" . ' </tr>' . "\n");
        }
        $contents[] = array('text' => '</table>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        $status = twe_db_prepare_input($_POST['status']);    // maybe this line not needed to recheck...
      }
      break;

    default:
      $customer_status = twe_get_customer_status($_GET['cID']);
      $cs_id = $customer_status->fields['customers_status'];
      $cs_member_flag  = $customer_status->fields['member_flag'];
      $cs_name = $customer_status->fields['customers_status_name'];
      $cs_image = $customer_status->fields['customers_status_image'];
      $cs_discount = $customer_status->fields['customers_status_discount'];
      $cs_ot_discount_flag  = $customer_status->fields['customers_status_ot_discount_flag'];
      $cs_ot_discount = $customer_status->fields['customers_status_ot_discount'];
      $cs_staffelpreise = $customer_status->fields['customers_status_staffelpreise'];
      $cs_payment_unallowed = $customer_status->fields['customers_status_payment_unallowed'];

//      echo 'customer_status ' . $cID . 'variables = ' . $cs_id . $cs_member_flag . $cs_name .  $cs_discount .  $cs_image . $cs_ot_discount;

      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
        if ($cInfo->customers_id != 2) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        }
        if ($cInfo->customers_id == 2 && $_SESSION['customer_id'] == 2) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        }
        if ($cInfo->delete_user != 0) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        }
        if ($cInfo->customers_id != 2 /*&& $_SESSION['customer_id'] == 1*/) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_CUSTOMERS, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=editstatus') . '">' . twe_image_button('button_status.gif', IMAGE_STATUS) . '</a>');
        }
        // elari cs v3.x changed for added accounting module
        if ($cInfo->customers_id != 2) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_ACCOUNTING, twe_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . twe_image_button('button_accounting.gif', IMAGE_ACCOUNTING) . '</a>');
        }
        // elari cs v3.x changed for added iplog module
        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . twe_image_button('button_orders.gif', IMAGE_ORDERS) . '</a><br><a href="' . twe_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . twe_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');

        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' ' . twe_date_short($cInfo->date_account_created));
        $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' ' . twe_date_short($cInfo->date_account_last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' '  . twe_date_short($cInfo->date_last_logon));
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' ' . $cInfo->number_of_logons);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' ' . $cInfo->countries_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_REVIEWS . ' ' . $cInfo->number_of_reviews);
      }

      if ($_GET['action']=='iplog') {
        $contents[] = array('text' => '<br><b>IPLOG :' );
        $customers_id = twe_db_prepare_input($_GET['cID']);
        $customers_log_info = twe_get_user_info($customers_id);
        if ($customers_log_info->RecordCount()>0) {
          while (!$customers_log_info->EOF) {
            $contents[] = array('text' => '<tr>' . "\n" . '<td class="smallText">' . $customers_log_info['customers_ip_date'] . ' ' . $customers_log_info['customers_ip']);
         $customers_log_info->MoveNext(); 
		  }
        }
      }
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  } 
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>