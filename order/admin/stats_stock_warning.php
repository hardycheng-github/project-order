<?php
/* --------------------------------------------------------------
   $Id: stats_stock_warning.php,v 1.3 2004/02/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(stats_products_viewed.php,v 1.27 2003/01/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (stats_stock_warning.php,v 1.9 2003/08/18); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_statistic.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Statistics</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
             <td class="dataTableHeadingRow" width="50%"><?php echo HEADING_PRODUCT; ?></td>
             <td class="dataTableHeadingRow" width="50%"><?php echo HEADING_QTY;  ?></td>
			  </tr>
			</table>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
             <td><table width="100%">
<?php
  $products_values = $db->Execute("SELECT p.products_id, p.products_quantity, pd.products_name FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE pd.language_id = '" . $_SESSION['languages_id'] . "' AND pd.products_id = p.products_id ORDER BY products_quantity");
  while (!$products_values->EOF) {
    echo '<tr><td width="50%" class="dataTableContent"><a href="' . twe_href_link(FILENAME_CATEGORIES, 'pID=' . $products_values->fields['products_id'] . '&action=new_product') . '"><b>' . $products_values->fields['products_name'] . '</b></a></td><td width="50%" class="dataTableContent">';
    if ($products_values->fields['products_quantity'] <='0') {
      echo '<font color="ff0000"><b>'.$products_values->fields['products_quantity'].'</b></font>';
    } else {
      echo $products_values->fields['products_quantity'];
    }
    echo '</td></tr>';

    $products_attributes_values = $db->Execute("SELECT
                                                   pov.products_options_values_name,
                                                   pa.attributes_stock
                                               FROM
                                                   " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
                                               WHERE
                                                   pa.products_id = '".$products_values->fields['products_id'] . "' AND pov.products_options_values_id = pa.options_values_id AND pov.language_id = '" . $_SESSION['languages_id'] . "' ORDER BY pa.attributes_stock");
								
    while (!$products_attributes_values->EOF) {
      echo '<tr><td width="50%" class="dataTableContent">&nbsp;&nbsp;&nbsp;&nbsp;-' . $products_attributes_values->fields['products_options_values_name'] . '</td><td width="50%" class="dataTableContent">';
      if ($products_attributes_values->fields['attributes_stock'] <= '0') {
        echo '<font color="ff0000"><b>' . $products_attributes_values->fields['attributes_stock'] . '</b></font>';
      } else {
        echo $products_attributes_values->fields['attributes_stock'];
      }
      echo '</td></tr>';
   $products_attributes_values->MoveNext();	  
    }
  $products_values->MoveNext();	  
  }
?>  
	        </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
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