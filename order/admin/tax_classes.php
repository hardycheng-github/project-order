<?php
/* --------------------------------------------------------------
   $Id: tax_classes.php,v 1.3 2004/02/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(tax_classes.php,v 1.19 2002/03/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (tax_classes.php,v 1.9 2003/08/18); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'insert':
        $tax_class_title = twe_db_prepare_input($_POST['tax_class_title']);
        $tax_class_description = twe_db_prepare_input($_POST['tax_class_description']);
        $date_added = twe_db_prepare_input($_POST['date_added']);

        $db->Execute("insert into " . TABLE_TAX_CLASS . " (tax_class_title, tax_class_description, date_added) values ('" . twe_db_input($tax_class_title) . "', '" . twe_db_input($tax_class_description) . "', now())");
        twe_redirect(twe_href_link(FILENAME_TAX_CLASSES));
        break;

      case 'save':
        $tax_class_id = twe_db_prepare_input($_GET['tID']);
        $tax_class_title = twe_db_prepare_input($_POST['tax_class_title']);
        $tax_class_description = twe_db_prepare_input($_POST['tax_class_description']);
        $last_modified = twe_db_prepare_input($_POST['last_modified']);

        $db->Execute("update " . TABLE_TAX_CLASS . " set tax_class_id = '" . twe_db_input($tax_class_id) . "', tax_class_title = '" . twe_db_input($tax_class_title) . "', tax_class_description = '" . twe_db_input($tax_class_description) . "', last_modified = now() where tax_class_id = '" . twe_db_input($tax_class_id) . "'");
        twe_redirect(twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tax_class_id));
        break;

      case 'deleteconfirm':
        $tax_class_id = twe_db_prepare_input($_GET['tID']);

        $db->Execute("delete from " . TABLE_TAX_CLASS . " where tax_class_id = '" . twe_db_input($tax_class_id) . "'");
        twe_redirect(twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page']));
        break;
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
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_configuration.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Configuration</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_CLASSES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $classes_query_raw = "select tax_class_id, tax_class_title, tax_class_description, last_modified, date_added from " . TABLE_TAX_CLASS . " order by tax_class_title";
  $classes_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $classes_query_raw, $classes_query_numrows);
  $classes = $db->Execute($classes_query_raw);
  while (!$classes->EOF) {
    if (((!$_GET['tID']) || (@$_GET['tID'] == $classes->fields['tax_class_id'])) && (!$tcInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $tcInfo = new objectInfo($classes->fields);
    }

    if ( (is_object($tcInfo)) && ($classes->fields['tax_class_id'] == $tcInfo->tax_class_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo'              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $classes['tax_class_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $classes->fields['tax_class_title']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($tcInfo)) && ($classes->fields['tax_class_id'] == $tcInfo->tax_class_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $classes->fields['tax_class_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$classes->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $classes_split->display_count($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES); ?></td>
                    <td class="smallText" align="right"><?php echo $classes_split->display_links($classes_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (!$_GET['action']) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&action=new') . '">' . twe_image_button('button_new_tax_class.gif', IMAGE_NEW_TAX_CLASS) . '</a>'; ?></td>
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
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TAX_CLASS . '</b>');

      $contents = array('form' => twe_draw_form('classes', FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_TITLE . '<br>' . twe_draw_input_field('tax_class_title'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . twe_draw_input_field('tax_class_description'));
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TAX_CLASS . '</b>');

      $contents = array('form' => twe_draw_form('classes', FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_TITLE . '<br>' . twe_draw_input_field('tax_class_title', $tcInfo->tax_class_title));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . twe_draw_input_field('tax_class_description', $tcInfo->tax_class_description));
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TAX_CLASS . '</b>');

      $contents = array('form' => twe_draw_form('classes', FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $tcInfo->tax_class_title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (is_object($tcInfo)) {
        $heading[] = array('text' => '<b>' . $tcInfo->tax_class_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_TAX_CLASSES, 'page=' . $_GET['page'] . '&tID=' . $tcInfo->tax_class_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . twe_date_short($tcInfo->date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . twe_date_short($tcInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . $tcInfo->tax_class_description);
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