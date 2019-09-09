<?php
/* --------------------------------------------------------------
   $Id: zones.php,v 1.3 2004/02/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(zones.php,v 1.21 2002/03/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (zones.php,v 1.8 2003/08/18); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'insert':
        $zone_country_id = twe_db_prepare_input($_POST['zone_country_id']);
        $zone_code = twe_db_prepare_input($_POST['zone_code']);
        $zone_name = twe_db_prepare_input($_POST['zone_name']);

        $db->Execute("insert into " . TABLE_ZONES . " (zone_country_id, zone_code, zone_name) values ('" . twe_db_input($zone_country_id) . "', '" . twe_db_input($zone_code) . "', '" . twe_db_input($zone_name) . "')");
        twe_redirect(twe_href_link(FILENAME_ZONES));
        break;
      case 'save':
        $zone_id = twe_db_prepare_input($_GET['cID']);
        $zone_country_id = twe_db_prepare_input($_POST['zone_country_id']);
        $zone_code = twe_db_prepare_input($_POST['zone_code']);
        $zone_name = twe_db_prepare_input($_POST['zone_name']);

        $db->Execute("update " . TABLE_ZONES . " set zone_country_id = '" . twe_db_input($zone_country_id) . "', zone_code = '" . twe_db_input($zone_code) . "', zone_name = '" . twe_db_input($zone_name) . "' where zone_id = '" . twe_db_input($zone_id) . "'");
        twe_redirect(twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $zone_id));
        break;
      case 'deleteconfirm':
        $zone_id = twe_db_prepare_input($_GET['cID']);

        $db->Execute("delete from " . TABLE_ZONES . " where zone_id = '" . twe_db_input($zone_id) . "'");
        twe_redirect(twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page']));
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ZONE_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $zones_query_raw = "select z.zone_id, c.countries_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id from " . TABLE_ZONES . " z, " . TABLE_COUNTRIES . " c where z.zone_country_id = c.countries_id order by c.countries_name, z.zone_name";
  $zones_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
  $zones = $db->Execute($zones_query_raw);
  while (!$zones->EOF) {
    if (((!$_GET['cID']) || (@$_GET['cID'] == $zones->fields['zone_id'])) && (!$cInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $cInfo = new objectInfo($zones->fields);
    }

    if ( (is_object($cInfo)) && ($zones->fields['zone_id'] == $cInfo->zone_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $zones->fields['zone_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $zones->fields['countries_name']; ?></td>
                <td class="dataTableContent"><?php echo $zones->fields['zone_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $zones->fields['zone_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($zones->fields['zone_id'] == $cInfo->zone_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $zones->fields['zone_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$zones->MoveNext();
  }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ZONES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (!$_GET['action']) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&action=new') . '">' . twe_image_button('button_new_zone.gif', IMAGE_NEW_ZONE) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>');

      $contents = array('form' => twe_draw_form('zones', FILENAME_ZONES, 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . twe_draw_input_field('zone_name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ZONES_CODE . '<br>' . twe_draw_input_field('zone_code'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . twe_draw_pull_down_menu('zone_country_id', twe_get_countries()));
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>');

      $contents = array('form' => twe_draw_form('zones', FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . twe_draw_input_field('zone_name', $cInfo->zone_name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_ZONES_CODE . '<br>' . twe_draw_input_field('zone_code', $cInfo->zone_code));
      $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . twe_draw_pull_down_menu('zone_country_id', twe_get_countries(), $cInfo->countries_id));
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>');

      $contents = array('form' => twe_draw_form('zones', FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->zone_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . '&nbsp;<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->zone_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_ZONES, 'page=' . $_GET['page'] . '&cID=' . $cInfo->zone_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . $cInfo->zone_name . ' (' . $cInfo->zone_code . ')');
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_NAME . ' ' . $cInfo->countries_name);
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