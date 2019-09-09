<?php
/*
  $Id: popup_coupon_help.php,v 1.1.2.5 2003/05/02 01:43:29 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  //$navigation->remove_current_page();
    require_once(DIR_FS_INC . 'twe_date_short.inc.php');

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_POPUP_COUPON_HELP);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  window.resizeTo(<? echo $size[0] ?> +305, <? echo $size[1] ?>+400-i);
   self.focus();
}
//--></script>
</head>
<style type="text/css"><!--
BODY { background: #FFFFFF;  color: #000000;  margin: 0px; }
//--></style>
<body onload="resize();" >

<?php
  $coupon_query = "select * from " . TABLE_COUPONS . " where coupon_id = '" . $_GET['cID'] . "'";
  $coupon = $db->Execute($coupon_query);
  $coupon_desc_query = "select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $_GET['cID'] . "' and language_id = '" . (int)$_SESSION['languages_id'] . "'";
  $coupon_desc = $db->Execute($coupon_desc_query);
  $text_coupon_help = TEXT_COUPON_HELP_HEADER;
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_NAME, $coupon_desc->fields['coupon_name']);
  if (twe_not_null($coupon_desc->fields['coupon_description'])) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DESC, $coupon_desc->fields['coupon_description']);
  $coupon_amount = $coupon->fields['coupon_amount'];
  switch ($coupon->fields['coupon_type']) {
    case 'F':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, $currencies->format($coupon->fields['coupon_amount']));
    break;
    case 'P':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, number_format($coupon->fields['coupon_amount'],2). '%');
    break;
    case 'S':
    $text_coupon_help .= TEXT_COUPON_HELP_FREESHIP;
    break;
    default:
  }
  if ($coupon->fields['coupon_minimum_order'] > 0 ) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_MINORDER, $currencies->format($coupon->fields['coupon_minimum_order']));
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DATE, twe_date_short($coupon->fields['coupon_start_date']),twe_date_short($coupon->fields['coupon_expire_date'])); 
  $text_coupon_help .= '<b>' . TEXT_COUPON_HELP_RESTRICT . '</b>';
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_CATEGORIES;
  $coupon_get="select restrict_to_categories from " . TABLE_COUPONS . " where coupon_id='".$_GET['cID']."'";
  $get_result=$db->Execute($coupon_get);

  $cat_ids = preg_split("[,]", $get_result->fields['restrict_to_categories']);
  for ($i = 0; $i < count($cat_ids); $i++) {
    $result = "SELECT * FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . (int)$_SESSION['languages_id'] . "' and categories.categories_id='" . $cat_ids[$i] . "'";
    $row=$db->Execute($result);
 
    if ($row->RecordCount()>0) {
    $cats .= '<br>' . $row->fields["categories_name"];
    } 
  }
  if ($cats=='') $cats = '<br>NONE';
  $text_coupon_help .= $cats;
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_PRODUCTS;
  $coupon_get="select restrict_to_products from " . TABLE_COUPONS . "  where coupon_id='".$_GET['cID']."'";
  $get_result=$db->Execute($coupon_get);

  $pr_ids = preg_split("[,]", $get_result->fields['restrict_to_products']);
  for ($i = 0; $i < count($pr_ids); $i++) {
    $result ="SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . (int)$_SESSION['languages_id'] . "'and products.products_id = '" . $pr_ids[$i] . "'";
       $row=$db->Execute($result);
 
	if ($row->RecordCount()>0) {
      $prods .= '<br>' . $row->fields["products_name"];
    }
  }
  if ($prods=='') $prods = '<br>NONE';
  $text_coupon_help .= $prods;


  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_COUPON_HELP);



  $info_box_contents = array();
  $info_box_contents[] = array('text' => $text_coupon_help);

  new infoBox($info_box_contents);
?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>

</body>
</html>
<?php require('includes/application_bottom.php'); ?>
