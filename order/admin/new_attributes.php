<?php
/* --------------------------------------------------------------
   $Id: new_attributes.php,v 1.6 2004/02/29 17:05:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes); www.oscommerce.com
   (c) 2003	 nextcommerce (new_attributes.php,v 1.13 2003/08/21); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   copy attributes                          Autor: Hubi | http://www.netz-designer.de

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 

  require('includes/application_top.php');
  require(DIR_WS_MODULES.'attributes/new_attributes_config.php');
    
  $adminImages = DIR_WS_CATALOG . "lang/". $_SESSION['language'] ."/admin/images/buttons/";
  $backLink = "<a href=\"".twe_href_link(FILENAME_NEW_ATTRIBUTES)."\">";

  if ( isset($cPathID) && $_POST['action'] == 'change') {
    include(DIR_WS_MODULES.'attributes/new_attributes_change.php');

    twe_redirect( './' . FILENAME_CATEGORIES . '?cPath=' . $cPathID . '&pID=' . $_POST['current_product_id'] );
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">

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
  switch($_POST['action']) {
    case 'edit':
      if ($_POST['copy_product_id'] != 0) {
          $attrib_res = $db->Execute("SELECT products_id, options_id, options_values_id, options_values_price, price_prefix, attributes_model, attributes_stock, options_values_weight, weight_prefix FROM products_attributes WHERE products_id = " . $_POST['copy_product_id']);
          while (!$attrib_res->EOF) {
              $db->Execute("INSERT into products_attributes (products_id, options_id, options_values_id, options_values_price, price_prefix, attributes_model, attributes_stock, options_values_weight, weight_prefix) VALUES ('" . $_POST['current_product_id'] . "', '" . $attrib_res->fields['options_id'] . "', '" . $attrib_res->fields['options_values_id'] . "', '" . $attrib_res->fields['options_values_price'] . "', '" . $attrib_res->fields['price_prefix'] . "', '" . $attrib_res->fields['attributes_model'] . "', '" . $attrib_res->fields['attributes_stock'] . "', '" . $attrib_res->fields['options_values_weight'] . "', '" . $attrib_res->fields['weight_prefix'] . "')");
         $attrib_res->MoveNext(); 
		  }
      }
      $pageTitle = HEADER_TITLE_EDIT_ATTRIBUTES  . twe_findTitle($_POST['current_product_id'], $languageFilter);
      include(DIR_WS_MODULES.'attributes/new_attributes_include.php');
      break;

    case 'change':
      $pageTitle = HEADER_TITLE_PRODUCT_ATTRIBUTES_UPDATED;
      include(DIR_WS_MODULES.'attributes/new_attributes_change.php');
      include(DIR_WS_MODULES.'attributes/new_attributes_select.php');
      break;

    default:
      $pageTitle = HEADER_TITLE_EDIT_ATTRIBUTES;
      include(DIR_WS_MODULES.'attributes/new_attributes_select.php');
      break;
  }
?>
    </table></td>
  </tr>
  </table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>