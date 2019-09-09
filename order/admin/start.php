<?php
/* --------------------------------------------------------------
   $Id: start.php,v 1.5 2004/03/17 16:31:26 oldpa Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project 
   (c) 2002-2003 osCommerce coding standards (a typical file) www.oscommerce.com
   (c) 2003     nextcommerce (start.php,1.5 2004/03/17); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/
  require('includes/application_top.php');
  require_once 'includes/classes/carp.php';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
.h2 {
  font-family: Trebuchet MS,Palatino,Times New Roman,serif;
  font-size: 13pt;
  font-weight: bold;
}

.h3 {
  font-family: Verdana,Arial,Helvetica,sans-serif;
  font-size: 9pt;
  font-weight: bold;
}
</style> 

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
    <td class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_news.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Central</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td>
        <?php include(DIR_WS_MODULES.FILENAME_SECURITY_CHECK); ?>
        </td>
      <tr>
      <td style="border: 1px solid; border-color: #ffffff;">
	  <table valign="top" width="100%" cellpadding="5" cellspacing="5">
 <?php

  $customers = $db->Execute("select count(*) as count from " . TABLE_CUSTOMERS);

  $products = $db->Execute("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");

  $products_off = $db->Execute("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '0'");

  $reviews = $db->Execute("select count(*) as count from " . TABLE_REVIEWS);

  $newsletters = $db->Execute("select count(*) as count from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'");

  
?>
<div id="colone">
<div class="reportBox">
<div class="header"><?php echo TXT_STATISTICS; ?> </div>
<?php
	echo '<div class="row"><span class="left">' . BOX_CUSTOMERS . '</span><span class="rigth"> ' . $customers->fields['count'] . '</span></div>';
	echo '<div class="row"><span class="left">' . TXT_PRODUCTS . ' </span><span class="rigth">' . $products->fields['count'] . '</span></div>';
	echo '<div class="row"><span class="left">' . BOX_REVIEWS . '</span><span class="rigth">' . $reviews->fields['count']. '</span></div>';
	echo '<div class="row"><span class="left">' . ENTRY_NEWSLETTER . '</span><span class="rigth"> ' . $newsletters->fields['count']. '</span></div>';
?>
 </div>
 <div class="reportBox">
   <div class="header"><?php echo BOX_ORDERS_STATUS; ?> </div>
  <?php   $orders_contents = '';
  $orders_status = $db->Execute("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "'");

  while (!$orders_status->EOF) {
    $orders_pending = $db->Execute("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status->fields['orders_status_id'] . "'");

    $orders_contents .= '<div class="row"><span class="left"><a href="' . twe_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status->fields['orders_status_id']) . '">' . $orders_status->fields['orders_status_name'] . '</a>:</span><span class="rigth"> ' . $orders_pending->fields['count'] . '</span>   </div>';
    $orders_status->MoveNext();
  }

  echo $orders_contents;
  ?>
  </div>
</div>
<div id="coltwo">
<div class="reportBox">
<div class="header"><?php echo BOX_CUSTOMERS; ?> </div>
  <?php  $customers = $db->Execute("select c.customers_id as customers_id, c.customers_firstname as customers_firstname, c.customers_email_address as customers_email_address, c.customers_lastname as customers_lastname, a.customers_info_date_account_created as customers_info_date_account_created, a.customers_info_id from " . TABLE_CUSTOMERS . " c left join " . TABLE_CUSTOMERS_INFO . " a on c.customers_id = a.customers_info_id order by a.customers_info_date_account_created DESC limit 5");

  while (!$customers->EOF) {
    echo '              <div class="row"><span class="left"><a href="' . twe_href_link(FILENAME_CUSTOMERS, 'search=' . $customers->fields['customers_email_address'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '" class="contentlink">'. $customers->fields['customers_firstname'] . ' ' . $customers->fields['customers_lastname'] . '</a></span><span class="rigth">' . "\n";
    echo twe_date_short($customers->fields['customers_info_date_account_created']);
    echo '              </span></div>' . "\n";
    $customers->MoveNext();
  }
?>
</div>
</div>
<div id="colthree">
<div class="reportBox">
<div class="header"><?php echo BOX_ORDERS; ?> </div>
  <?php  $orders = $db->Execute("select o.orders_id as orders_id, o.customers_name as customers_name, o.customers_id, o.date_purchased as date_purchased, o.currency, o.currency_value, ot.class, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id) where class = 'ot_total' order by orders_id DESC limit 5");

  while (!$orders->EOF) {
    echo '              <div class="row"><span class="left"><a href="' . twe_href_link(FILENAME_ORDERS, 'oID=' . $orders->fields['orders_id'] . '&origin=' . FILENAME_DEFAULT, 'NONSSL') . '" class="contentlink"> ' . $orders->fields['customers_name'] . '</a></span>' . $orders->fields['order_total'] . '<span class="rigth">' . "\n";
    echo twe_date_short($orders->fields['date_purchased']);
    echo '              </span></div>' . "\n";
    $orders->MoveNext();
  }
?>
</div>
</div>
</div>
</table>
     </td>
      </tr>		
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>