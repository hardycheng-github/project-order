<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<?php
/*
  The following copyright announcement is in compliance
  to section 2c of the GNU General Public License, and
  thus can not be removed, or can only be modified
  appropriately.

  Please leave this comment intact together with the
  following copyright announcement.
  
*/
?>
<!--
	This OnlineStore is brought to you by TWE-Commerce, Community made shopping
	TWE is a free open source e-Commerce System
	modified by oldpa and licensed under GNU/GPL.
	Information and contribution at http://www.oldpa.com.tw & http://www.twecommerce.org
-->
<meta name="generator" content="(c) by <?php echo PROJECT_VERSION; ?>,http://www.oldpa.com.tw">
<?php if(defined('NEWS')){
 include(DIR_WS_MODULES.FILENAME_NEWS_METATAGS);
}else{
include(DIR_WS_MODULES.FILENAME_METATAGS);
} ?>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
<script language="javascript"><!--
var selected;

var submitter = null;
function clearText(thefield){
  if (thefield.defaultValue==thefield.value)
    thefield.value = ""
}
function submitFunction() {
    submitter = 1;
}

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}  

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
function checkBox(object) {
  document.account_newsletter.elements[object].checked = !document.account_newsletter.elements[object].checked;
}
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}

//--></script>
<?php
// require theme based javascript
require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE.'/javascript/general.js.php');

if (strstr($PHP_SELF, FILENAME_CHECKOUT_PAYMENT)) {
 echo $payment_modules->javascript_validation();
}

if (strstr($PHP_SELF, FILENAME_CREATE_ACCOUNT)) {
require('includes/form_check.js.php');
}

if (strstr($PHP_SELF, FILENAME_CREATE_GUEST_ACCOUNT )) {
require('includes/form_check.js.php');
}
if (strstr($PHP_SELF, FILENAME_ACCOUNT_PASSWORD )) {
require('includes/form_check.js.php');
}
if (strstr($PHP_SELF, FILENAME_ACCOUNT_EDIT )) {
require('includes/form_check.js.php');
}
if (strstr($PHP_SELF, FILENAME_ADDRESS_BOOK_PROCESS )) {
  if (isset($_GET['delete']) == false) {
    include('includes/form_check.js.php');
  }
  }
if (strstr($PHP_SELF, FILENAME_CHECKOUT_SHIPPING_ADDRESS )or strstr($PHP_SELF,FILENAME_CHECKOUT_PAYMENT_ADDRESS)) {
require('includes/form_check.js.php');
?>
<script language="javascript"><!--
function check_form_optional(form_name) {
  var form = form_name;

  var firstname = form.elements['firstname'].value;
  var lastname = form.elements['lastname'].value;
  var street_address = form.elements['street_address'].value;

  if (firstname == '' && lastname == '' && street_address == '') {
    return true;
  } else {
    return check_form(form_name);
  }
}
//--></script>
<?php
}

?>
<?php
if (strstr($PHP_SELF, FILENAME_ADVANCED_SEARCH )) {
?>
<script language="javascript" src="includes/general.js"></script>
<script language="javascript"><!--
function check_form() {
  var error_message = "<?php echo JS_ERROR; ?>";
  var error_found = false;
  var error_field;
  var keywords = document.advanced_search.keywords.value;
  var dfrom = document.advanced_search.dfrom.value;
  var dto = document.advanced_search.dto.value;
  var pfrom = document.advanced_search.pfrom.value;
  var pto = document.advanced_search.pto.value;
  var pfrom_float;
  var pto_float;

  if ( ((keywords == '') || (keywords.length < 1)) && ((dfrom == '') || (dfrom == '<?php echo DOB_FORMAT_STRING; ?>') || (dfrom.length < 1)) && ((dto == '') || (dto == '<?php echo DOB_FORMAT_STRING; ?>') || (dto.length < 1)) && ((pfrom == '') || (pfrom.length < 1)) && ((pto == '') || (pto.length < 1)) ) {
    error_message = error_message + "<?php echo JS_AT_LEAST_ONE_INPUT; ?>";
    error_field = document.advanced_search.keywords;
    error_found = true;
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo JS_INVALID_FROM_DATE; ?>";
      error_field = document.advanced_search.dfrom;
      error_found = true;
    }
  }

  if ((dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>')) {
    if (!IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>')) {
      error_message = error_message + "<?php echo JS_INVALID_TO_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if ((dfrom.length > 0) && (dfrom != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dfrom, '<?php echo DOB_FORMAT_STRING; ?>')) && (dto.length > 0) && (dto != '<?php echo DOB_FORMAT_STRING; ?>') && (IsValidDate(dto, '<?php echo DOB_FORMAT_STRING; ?>'))) {
    if (!CheckDateRange(document.advanced_search.dfrom, document.advanced_search.dto)) {
      error_message = error_message + "<?php echo JS_TO_DATE_LESS_THAN_FROM_DATE; ?>";
      error_field = document.advanced_search.dto;
      error_found = true;
    }
  }

  if (pfrom.length > 0) {
    pfrom_float = parseFloat(pfrom);
    if (isNaN(pfrom_float)) {
      error_message = error_message + "<?php echo JS_PRICE_FROM_MUST_BE_NUM; ?>";
      error_field = document.advanced_search.pfrom;
      error_found = true;
    }
  } else {
    pfrom_float = 0;
  }

  if (pto.length > 0) {
    pto_float = parseFloat(pto);
    if (isNaN(pto_float)) {
      error_message = error_message + "<?php echo JS_PRICE_TO_MUST_BE_NUM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  } else {
    pto_float = 0;
  }

  if ( (pfrom.length > 0) && (pto.length > 0) ) {
    if ( (!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float) ) {
      error_message = error_message + "<?php echo JS_PRICE_TO_LESS_THAN_PRICE_FROM; ?>";
      error_field = document.advanced_search.pto;
      error_found = true;
    }
  }

  if (error_found == true) {
    alert(error_message);
    error_field.focus();
    return false;
  } else {
    RemoveFormatString(document.advanced_search.dfrom, "<?php echo DOB_FORMAT_STRING; ?>");
    RemoveFormatString(document.advanced_search.dto, "<?php echo DOB_FORMAT_STRING; ?>");
    return true;
  }
}

function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<?php
}

if (strstr($PHP_SELF, FILENAME_PRODUCT_REVIEWS_WRITE )) {
?>


<script language="javascript"><!--
function checkForm() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";

  var review = document.product_reviews_write.review.value;

  if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
    error = 1;
  }

  if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
  } else {
    error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
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
if (strstr($PHP_SELF, FILENAME_POPUP_IMAGE )) {
?>

<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);
  self.focus();
}
//--></script>
<?php
}
?>

</head>
<?php
if (strstr($PHP_SELF, FILENAME_POPUP_IMAGE )) {
echo '<body onload="resize();"> ';
} else {
echo '<body>';
}

  // include needed functions
  //require_once(DIR_FS_CATALOG .'inc/twe_output_warning.inc.php');
  require_once(DIR_FS_CATALOG .'inc/twe_image.inc.php');
  require_once(DIR_FS_CATALOG .'inc/twe_image_button.inc.php');
  require_once(DIR_FS_CATALOG .'inc/twe_parse_input_field_data.inc.php');
  require_once(DIR_FS_CATALOG .'inc/twe_draw_separator.inc.php');

//  require_once('inc/twe_draw_form.inc.php');
//  require_once('inc/twe_draw_pull_down_menu.inc.php');
/*
  // check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/twe_installer')) {
      twe_output_warning(WARNING_INSTALL_DIRECTORY_EXISTS);
    }
  }

  // check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      twe_output_warning(WARNING_CONFIG_FILE_WRITEABLE);
    }
  }

  // check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(twe_session_save_path())) {
        twe_output_warning(WARNING_SESSION_DIRECTORY_NON_EXISTENT);
      } elseif (!is_writeable(twe_session_save_path())) {
        twe_output_warning(WARNING_SESSION_DIRECTORY_NOT_WRITEABLE);
      }
    }
  }

  // check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      twe_output_warning(WARNING_SESSION_AUTO_START);
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      twe_output_warning(WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT);
    }
  }
*/

if (strstr($PHP_SELF, FILENAME_DEFAULT) && !isset($_GET['manufacturers_id']) && !isset($_GET['cPath'])) {
  require_once(DIR_FS_INC . 'twe_customer_greeting.inc.php');
$smarty->assign('greeting',twe_customer_greeting());
}
$smarty->assign('navtrail',$breadcrumb->trail(' &raquo; '));
if (isset($_SESSION['customer_id'])) {

$smarty->assign('logoff',twe_href_link(FILENAME_LOGOFF, '', 'SSL'));
}
if ( $_SESSION['account_type']=='0') {
$smarty->assign('account',twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
}
$smarty->assign('cart',twe_href_link(FILENAME_SHOPPING_CART, '', 'SSL'));
$smarty->assign('checkout',twe_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
$smarty->assign('login',twe_href_link(FILENAME_LOGIN, '', 'SSL'));
$smarty->assign('forum',twe_href_link(FILENAME_BBFORUM, '', 'SSL'));
$smarty->assign('default',twe_href_link(FILENAME_DEFAULT, '', 'SSL'));

$layout_logo = $db->Execute("select * from layout_template where layout_template_name = '" . CURRENT_TEMPLATE . "'");
$logo = '';
 if(!$layout_logo->RecordCount()){
$logo = 'logo.jpg';
$smarty->assign('logo_width', '883');
$smarty->assign('logo_height', '242');
}else{
$logo = $layout_logo->fields['layout_template_logo'];
$img = DIR_FS_CATALOG.DIR_WS_IMAGES.$layout_logo->fields['layout_template_logo'];
if(file_exists($img)) $size = GetImageSize("$img");
$smarty->assign('bottom_height', $bottom_height);
$smarty->assign('bottom_btn_height', $bottom_btn_height);
$smarty->assign('bottom_list_height', $bottom_list_height);
$smarty->assign('logo', $logo);
$smarty->assign('logo_width', $size[0]);
$smarty->assign('logo_height', $size[1]);
$smarty->assign('width', $layout_logo->fields['layout_template_width']);
$smarty->assign('left_width', $layout_logo->fields['layout_template_left_width']);
$smarty->assign('right_width', $layout_logo->fields['layout_template_right_width']);
$smarty->assign('center_width', $layout_logo->fields['layout_template_center_width']);    
}
  if (isset($_GET['error_message']) && twe_not_null($_GET['error_message'])) {

$smarty->assign('error','
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerError">
        <td class="headerError">'. htmlspecialchars(urldecode($_GET['error_message'])).'</td>
      </tr>
    </table>');

  }

  if (isset($_GET['info_message']) && twe_not_null($_GET['info_message'])) {

$smarty->assign('error','
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="headerInfo">
        <td class="headerInfo">'.htmlspecialchars($_GET['info_message']).'</td>
      </tr>
    </table>');

  }
  include(DIR_WS_INCLUDES.FILENAME_BANNER);
  
  // myEdit marquee 跑馬燈加入 start
  if(show_top_marquee == true){
	require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/marquee.php');
  }
  // myEdit marquee 跑馬燈加入 end
  if(show_top_marquee == true){
	  $block_height = block_height;
	  $marquee_height = marquee_height;
  } else{
	  $block_height = block_height + marquee_height;
	  $marquee_height = 0;
  }
  $smarty->assign('show_breadcrumb', show_breadcrumb);
  $smarty->assign('show_top_marquee', show_top_marquee);
  $smarty->assign('block_height', $block_height);
  $smarty->assign('cartbar_height', cartbar_height);
  $smarty->assign('frameBlock_height', $block_height + cartbar_height);
  $smarty->assign('marquee_height', $marquee_height);
  $smarty->assign('frameUrl', twe_href_link(FILENAME_DEFAULT));
  $smarty->assign('outerFrame', $outerFrame);
  // myEdit 加入底部購物欄位
  if ($_SESSION['cart']->count_contents() > 0) {
	$smarty->assign('BUTTON_NEXT','<a href='.twe_href_link(FILENAME_SHOPPING_CART).'>'.twe_image_button('button_cart.png', IMAGE_BUTTON_CHECKOUT).'</a>');
  } else{
	$smarty->assign('BUTTON_NEXT',twe_image_button('button_cart.png', IMAGE_BUTTON_CHECKOUT, $button_next_disable_style));
  }
  $smarty->assign('TOTAL_CONTENT',$total_content);
  $url = twe_href_link(basename($PHP_SELF), twe_get_all_get_params(array('action')) . 'action=clear_cart');
  $smarty->assign('BUTTON_CLEAR', "<form action='$url' method='post'>".twe_image_submit('button_clear.png'))."</form>";
  // 購物欄位 end
?>