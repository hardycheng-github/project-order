<?php
/* --------------------------------------------------------------
   $Id: geo_zones.php,v 1.3 2004/02/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(geo_zones.php,v 1.27 2003/05/07); www.oscommerce.com 
   (c) 2003	 nextcommerce (geo_zones.php,v 1.9 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  switch ($_GET['saction']) {
    case 'insert_sub':
      $zID = twe_db_prepare_input($_GET['zID']);
      $zone_country_id = twe_db_prepare_input($_POST['zone_country_id']);
      $zone_id = twe_db_prepare_input($_POST['zone_id']);

      $db->Execute("insert into " . TABLE_ZONES_TO_GEO_ZONES . " (zone_country_id, zone_id, geo_zone_id, date_added) values ('" . twe_db_input($zone_country_id) . "', '" . twe_db_input($zone_id) . "', '" . twe_db_input($zID) . "', now())");
      $new_subzone_id = $db->Insert_ID();

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $new_subzone_id));
      break;

    case 'save_sub':
      $sID = twe_db_prepare_input($_GET['sID']);
      $zID = twe_db_prepare_input($_GET['zID']);
      $zone_country_id = twe_db_prepare_input($_POST['zone_country_id']);
      $zone_id = twe_db_prepare_input($_POST['zone_id']);

      $db->Execute("update " . TABLE_ZONES_TO_GEO_ZONES . " set geo_zone_id = '" . twe_db_input($zID) . "', zone_country_id = '" . twe_db_input($zone_country_id) . "', zone_id = " . ((twe_db_input($zone_id)) ? "'" . twe_db_input($zone_id) . "'" : 'null') . ", last_modified = now() where association_id = '" . twe_db_input($sID) . "'");

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $_GET['sID']));
      break;

    case 'deleteconfirm_sub':
      $sID = twe_db_prepare_input($_GET['sID']);

      $db->Execute("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where association_id = '" . twe_db_input($sID) . "'");

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage']));
      break;
  }

  switch ($_GET['action']) {
    case 'insert_zone':
      $geo_zone_name = twe_db_prepare_input($_POST['geo_zone_name']);
      $geo_zone_description = twe_db_prepare_input($_POST['geo_zone_description']);

      $db->Execute("insert into " . TABLE_GEO_ZONES . " (geo_zone_name, geo_zone_description, date_added) values ('" . twe_db_input($geo_zone_name) . "', '" . twe_db_input($geo_zone_description) . "', now())");
      $new_zone_id = $db->Insert_ID();

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $new_zone_id));
      break;

    case 'save_zone':
      $zID = twe_db_prepare_input($_GET['zID']);
      $geo_zone_name = twe_db_prepare_input($_POST['geo_zone_name']);
      $geo_zone_description = twe_db_prepare_input($_POST['geo_zone_description']);

      $db->Execute("update " . TABLE_GEO_ZONES . " set geo_zone_name = '" . twe_db_input($geo_zone_name) . "', geo_zone_description = '" . twe_db_input($geo_zone_description) . "', last_modified = now() where geo_zone_id = '" . twe_db_input($zID) . "'");

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']));
      break;

    case 'deleteconfirm_zone':
      $zID = twe_db_prepare_input($_GET['zID']);

      $db->Execute("delete from " . TABLE_GEO_ZONES . " where geo_zone_id = '" . twe_db_input($zID) . "'");
      $db->Execute("delete from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . twe_db_input($zID) . "'");

      twe_redirect(twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage']));
      break;
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
  if ($_GET['zID']  && (($_GET['saction'] == 'edit') || ($_GET['saction'] == 'new'))) {
?>
<script language="javascript"><!--
function resetZoneSelected(theForm) {
  if (theForm.state.value != '') {
    theForm.zone_id.selectedIndex = '0';
    if (theForm.zone_id.options.length > 0) {
      theForm.state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  var NumState = theForm.zone_id.options.length;
  var SelectedCountry = "";

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }         

  SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;

<?php echo twe_js_zone_list('SelectedCountry', 'theForm', 'zone_id'); ?>

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
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
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
            <td valign="top">
<?php
  if ($_GET['action'] == 'list') {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_ZONE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $rows = 0;
    $zones_query_raw = "select a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.geo_zone_id, a.last_modified, a.date_added, z.zone_name from " . TABLE_ZONES_TO_GEO_ZONES . " a left join " . TABLE_COUNTRIES . " c on a.zone_country_id = c.countries_id left join " . TABLE_ZONES . " z on a.zone_id = z.zone_id where a.geo_zone_id = " . $_GET['zID'] . " order by association_id";
    $zones_split = new splitPageResults($_GET['spage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
    $zones = $db->Execute($zones_query_raw);
    while (!$zones->EOF) {
      $rows++;
      if (((!$_GET['sID']) || (@$_GET['sID'] == $zones->fields['association_id'])) && (!$sInfo) && (substr($_GET['saction'], 0, 3) != 'new')) {
        $sInfo = new objectInfo($zones->fields);
      }
      if ( (is_object($sInfo)) && ($zones->fields['association_id'] == $sInfo->association_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $zones->fields['association_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo (($zones->fields['countries_name']) ? $zones->fields['countries_name'] : TEXT_ALL_COUNTRIES); ?></td>
                <td class="dataTableContent"><?php echo (($zones->fields['zone_id']) ? $zones->fields['zone_name'] : PLEASE_SELECT); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($sInfo)) && ($zones->fields['association_id'] == $sInfo->association_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $zones->fields['association_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$zones->MoveNext();
    }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['spage'], TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['spage'], 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list', 'spage'); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="3"><?php if (!$_GET['saction']) echo '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']) . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=new') . '">' . twe_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
            </table>
<?php
  } else {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_ZONES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $zones_query_raw = "select geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added from " . TABLE_GEO_ZONES . " order by geo_zone_name";
    $zones_split = new splitPageResults($_GET['zpage'], MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_query_numrows);
    $zones = $db->Execute($zones_query_raw);
    while (!$zones->EOF) {
      if (((!$_GET['zID']) || (@$_GET['zID'] == $zones->fields['geo_zone_id'])) && (!$zInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
        $num_zones_query = "select count(*) as num_zones from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . $zones->fields['geo_zone_id'] . "' group by geo_zone_id";
        $num_zones = $db->Execute($num_zones_query);

	    if ($num_zones->RecordCount() > 0) {
          $zones->fields['num_zones'] = $num_zones->fields['num_zones'];
        } else {
          $zones->fields['num_zones'] = 0;
        }
        $zInfo = new objectInfo($zones->fields);
      }
      if ( (is_object($zInfo)) && ($zones->fields['geo_zone_id'] == $zInfo->geo_zone_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=list') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones->fields['geo_zone_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones->fields['geo_zone_id'] . '&action=list') . '">' . twe_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;' . $zones->fields['geo_zone_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($zInfo)) && ($zones->fields['geo_zone_id'] == $zInfo->geo_zone_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zones->fields['geo_zone_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$zones->MoveNext();
    }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo $zones_split->display_count($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['zpage'], TEXT_DISPLAY_NUMBER_OF_TAX_ZONES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['zpage'], '', 'zpage'); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="2"><?php if (!$_GET['action']) echo '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=new_zone') . '">' . twe_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
            </table>
<?php
  }
?>
            </td>
<?php
  $heading = array();
  $contents = array();

  if ($_GET['action'] == 'list') {
    switch ($_GET['saction']) {
      case 'new':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SUB_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $_GET['sID'] . '&saction=insert_sub'));
        $contents[] = array('text' => TEXT_INFO_NEW_SUB_ZONE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . twe_draw_pull_down_menu('zone_country_id', twe_get_countries(TEXT_ALL_COUNTRIES), '', 'onChange="update_zone(this.form);"'));
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . twe_draw_pull_down_menu('zone_id', twe_prepare_country_zones_pull_down()));
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $_GET['sID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'edit':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SUB_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=save_sub'));
        $contents[] = array('text' => TEXT_INFO_EDIT_SUB_ZONE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . twe_draw_pull_down_menu('zone_country_id', twe_get_countries(TEXT_ALL_COUNTRIES), $sInfo->zone_country_id, 'onChange="update_zone(this.form);"'));
        $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . twe_draw_pull_down_menu('zone_id', twe_prepare_country_zones_pull_down($sInfo->zone_country_id), $sInfo->zone_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SUB_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=deleteconfirm_sub'));
        $contents[] = array('text' => TEXT_INFO_DELETE_SUB_ZONE_INTRO);
        $contents[] = array('text' => '<br><b>' . $sInfo->countries_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if (is_object($sInfo)) {
          $heading[] = array('text' => '<b>' . $sInfo->countries_name . '</b>');

          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $_GET['spage'] . '&sID=' . $sInfo->association_id . '&saction=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . twe_date_short($sInfo->date_added));
          if (twe_not_null($sInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . twe_date_short($sInfo->last_modified));
        }
        break;
    }
  } else {
    switch ($_GET['action']) {
      case 'new_zone':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID'] . '&action=insert_zone'));
        $contents[] = array('text' => TEXT_INFO_NEW_ZONE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . twe_draw_input_field('geo_zone_name'));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . twe_draw_input_field('geo_zone_description'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $_GET['zID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'edit_zone':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=save_zone'));
        $contents[] = array('text' => TEXT_INFO_EDIT_ZONE_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . twe_draw_input_field('geo_zone_name', $zInfo->geo_zone_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . twe_draw_input_field('geo_zone_description', $zInfo->geo_zone_description));
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete_zone':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>');

        $contents = array('form' => twe_draw_form('zones', FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=deleteconfirm_zone'));
        $contents[] = array('text' => TEXT_INFO_DELETE_ZONE_INTRO);
        $contents[] = array('text' => '<br><b>' . $zInfo->geo_zone_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if (is_object($zInfo)) {
          $heading[] = array('text' => '<b>' . $zInfo->geo_zone_name . '</b>');

          $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=edit_zone') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=delete_zone') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' . ' <a href="' . twe_href_link(FILENAME_GEO_ZONES, 'zpage=' . $_GET['zpage'] . '&zID=' . $zInfo->geo_zone_id . '&action=list') . '">' . twe_image_button('button_details.gif', IMAGE_DETAILS) . '</a>');
          $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_ZONES . ' ' . $zInfo->num_zones);
          $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . twe_date_short($zInfo->date_added));
          if (twe_not_null($zInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . twe_date_short($zInfo->last_modified));
          $contents[] = array('text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . $zInfo->geo_zone_description);
        }
        break;
    }
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