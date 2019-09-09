<?php
/* --------------------------------------------------------------
   $Id: accounting.php,v 1.8 2004/03/11 23:29:53 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercecoding standards www.oscommerce.com 
   (c) 2003	 nextcommerce (accounting.php,v 1.27 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'save':
      // reset values before writing
       $admin_access = $db->Execute("select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . (int)$_GET['cID'] . "'");
       $fields = mysql_list_fields(DB_DATABASE, TABLE_ADMIN_ACCESS);
       $columns = mysql_num_fields($fields);
		for ($i = 0; $i < $columns; $i++) {
             $field=mysql_field_name($fields, $i);
                    if ($field!='customers_id') {

                    $db->Execute("UPDATE ".TABLE_ADMIN_ACCESS." SET
                                  ".$field."=0 where customers_id='".(int)$_GET['cID']."'");
    		}
        }
      $access_ids='';
        if(isset($_POST['access'])) foreach($_POST['access'] as $key){
        $db->Execute("UPDATE ".TABLE_ADMIN_ACCESS." SET ".$key."=1 where customers_id='".(int)$_GET['cID']."'");
        }
        twe_redirect(twe_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$_GET['cID'], 'NONSSL'));
        break;
      }
    }
	
    if ($_GET['cID'] != '') {
      if ($_GET['cID'] == 2) {
        twe_redirect(twe_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$_GET['cID'], 'NONSSL'));
      } else {
        $allow_edit_query ="select customers_status, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_GET['cID'] . "'";
        $allow_edit = $db->Execute($allow_edit_query);
        if ($allow_edit->fields['customers_status'] != 0 || $allow_edit == '') {
          twe_redirect(twe_href_link(FILENAME_CUSTOMERS, 'cID=' . (int)$_GET['cID'], 'NONSSL'));
        }
      }
    }
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo TEXT_ACCOUNTING.' '.$allow_edit->fields['customers_lastname'].' '.$allow_edit->fields['customers_firstname']; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?><br><br></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td colspan="2" class="main"> <br><?php echo TXT_GROUPS; ?><br>

      <table width="100%" cellpadding="0" cellspacing="2">
      <tr>
       <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="FF6969" ><?php echo twe_draw_separator('pixel_trans.gif',15, 15); ?></td>
       <td width="100%" class="main"><?php echo TXT_SYSTEM; ?></td>
      </tr>
      <tr>
       <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="69CDFF" ><?php echo twe_draw_separator('pixel_trans.gif',10, 15); ?></td>
       <td width="100%" class="main"><?php echo TXT_CUSTOMERS; ?></td>
      </tr>
      <tr>
       <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="6BFF7F" ><?php echo twe_draw_separator('pixel_trans.gif',15, 15); ?></td>
       <td width="100%" class="main"><?php echo TXT_PRODUCTS; ?></td>
      </tr>
      <tr>
       <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="BFA8FF" ><?php echo twe_draw_separator('pixel_trans.gif',15, 15); ?></td>
       <td width="100%" class="main"><?php echo TXT_STATISTICS; ?></td>
      </tr>
      <tr>
       <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="FFE6A8" ><?php echo twe_draw_separator('pixel_trans.gif',15, 15); ?></td>
       <td width="100%" class="main"><?php echo TXT_TOOLS; ?></td>
      </tr>
      </table>
      <br>
      </td>
      </tr>
      <tr>
        <td><table valign="top" width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TEXT_ACCESS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TEXT_ALLOWED; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr><table border="0" cellpadding="0" cellspacing="2">
<?php
 echo twe_draw_form('accounting', FILENAME_ACCOUNTING, 'cID=' . $_GET['cID']  . '&action=save', 'post', 'enctype="multipart/form-data"');

   $admin_access='';
    $customers_id = twe_db_prepare_input($_GET['cID']);
    $admin_access_query = "select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . (int)$_GET['cID'] . "'";
    $admin_access = $db->Execute($admin_access_query);

    $group_query="select * from " . TABLE_ADMIN_ACCESS . " where customers_id = 'groups'";
    $group_access = $db->Execute($group_query);
   if (!$admin_access->RecordCount()) {
      $db->Execute("INSERT INTO " . TABLE_ADMIN_ACCESS . " (customers_id) VALUES ('" . (int)$_GET['cID'] . "')");
      $admin_access_query = "select * from " . TABLE_ADMIN_ACCESS . " where customers_id = '" . (int)$_GET['cID'] . "'";
      $admin_access = $db->Execute($admin_access_query);
	  $group_query="select * from " . TABLE_ADMIN_ACCESS . " where customers_id = 'groups'";
      $group_access = $db->Execute($group_query);
    }

$fields = mysql_list_fields(DB_DATABASE, TABLE_ADMIN_ACCESS);
$columns = mysql_num_fields($fields);

for ($i = 0; $i < $columns; $i++) {
    $field=mysql_field_name($fields, $i);
    if ($field!='customers_id') {
    $checked='';
    if ($admin_access->fields[$field] == '1') $checked='checked';

    // colors
    switch ($group_access->fields[$field]) {
            case '1':
            $color='#FF6969';
            break;
            case '2':
            $color='#69CDFF';
            break;
            case '3':
            $color='#6BFF7F';
            break;
            case '4':
            $color='#BFA8FF';
            break;
            case '5':
            $color='#FFE6A8';

    }
    echo '<tr class="dataTable">
    <td style="border: 1px solid; border-color: #000000;" width="10" bgcolor="'.$color.'" >'.twe_draw_separator('pixel_trans.gif',15, 15).'</td>
        <td width="100%" class="dataTableContentRow">
        <input type="checkbox" name="access[]" value="'.$field.'"'.$checked.'>
        '.$field.'</td>
        <td></td></tr>';
    }
}
?>
    </table>
<?php echo twe_image_submit('button_save.gif', IMAGE_SAVE,'style="cursor:hand" onClick="return confirm(\''.SAVE_ENTRY.'\')"'); ?>
</td>

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