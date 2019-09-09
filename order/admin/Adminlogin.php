<?php
/* -----------------------------------------------------------------------------------------
   $Id: login.php,v 1.9 2005/04/17 21:13:26 oldpa Exp $   

   TWE-Commerce - community made shopping 
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(login.php,v 1.79 2003/05/19); www.oscommerce.com 
   (c) 2003      nextcommerce (login.php,v 1.13 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contribution:

   guest account idea by Ingo T. <xIngox@web.de>
   ---------------------------------------------------------------------------------------*/
  include( 'includes/application_top.php');
  require_once(DIR_FS_INC . 'twe_validate_password.inc.php');
  require_once(DIR_FS_INC . 'twe_array_to_string.inc.php');
  require_once(DIR_FS_INC . 'twe_write_user_info.inc.php');
  
    if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = twe_db_prepare_input($_POST['email_address']);
    $password = twe_db_prepare_input($_POST['password']);

    // Check if email exists
    $check_customer_query = "select customers_id, customers_firstname,customers_lastname, customers_gender, customers_password, customers_email_address, customers_default_address_id, username, user_active from " . TABLE_CUSTOMERS . " where customers_email_address = '" . twe_db_input($email_address) . "'";
    $check_customer = $db->Execute($check_customer_query);

    if (!$check_customer->RecordCount()) {
      $error = true;
	  $messageStack->add(TEXT_NO_EMAIL_ADDRESS_FOUND, 'error');
    } else {
      // Check that password is good
      if (!twe_validate_password($password, $check_customer->fields['customers_password'])) {
        $_GET['login'] = 'fail';
       $messageStack->add(TEXT_LOGIN_ERROR, 'error');
      } else {
        
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
        //$_SESSION['cart']->restore_contents();
        twe_redirect(twe_href_link(FILENAME_START));
       }
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<table border="0" align="center" width="50%" cellspacing="2" cellpadding="2">
  <tr>
    <td><fieldset><table border="0" align="center" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td align="center"><font size="+3"><?php echo PROJECT_VERSION.'<br>'.STORE_NAME; ?></font>
	</td>
    </tr>
  </table></fieldset><?php if ($messageStack->size > 0) {
    echo $messageStack->output();
  } ?></td>
    </tr>
  </table>
<form name="login" action="<?php echo twe_href_link('Adminlogin.php', 'action=process', 'SSL'); ?>" method = "POST">
<table border="0" align="center" width="50%" cellspacing="2" cellpadding="2">
  <tr>
    <td >
  <fieldset>
  <table align="center" border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#FFFFCC">
    <tr> 
      <td width="30%" height="51" valign="top" bgcolor="#66FFCC"><?php echo HEADER_TITLE_TOP; ?></td>
      <td valign="top">&nbsp;</td>
	  <td bgcolor="#CC9966">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#993333">&nbsp;</td>
      <td align="" bgcolor="#99CCFF"><?php echo ENTRY_EMAIL_ADDRESS; ?><br> 
        <input type="text" name="email_address" value="<?php echo $_POST['email_address']; ?>" size="30" /></td>
      <td align="">&nbsp;</td>
    </tr>
    <tr> 
      <td bgcolor="#000000">&nbsp;</td>
      <td bgcolor="#99CCFF"><?php echo ENTRY_PASSWORD; ?><br> 
        <input type="password"  name="password" value="<?php echo $_POST['password']; ?>" />
		<input type="submit" class="button" value="Login" /></td>
      <td bgcolor="#000000">&nbsp;</td>
    </tr>
  </table></fieldset>
  </td>
    </tr>
  </table>
</form>
<!-- body_eof //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>