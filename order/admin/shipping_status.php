<?php
/* --------------------------------------------------------------
   $Id: shipping_status.php,v 1.2 2004/02/29 17:05:18 oldpa Exp $

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders_status.php,v 1.19 2003/02/06); www.oscommerce.com
   (c) 2003	 nextcommerce (orders_status.php,v 1.9 2003/08/18); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  switch ($_GET['action']) {
    case 'insert':
    case 'save':
      $shipping_status_id = twe_db_prepare_input($_GET['oID']);

      $languages = twe_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $shipping_status_name_array = $_POST['shipping_status_name'];
        $language_id = $languages[$i]['id'];

        $sql_data_array = array('shipping_status_name' => twe_db_prepare_input($shipping_status_name_array[$language_id]));

        if ($_GET['action'] == 'insert') {
          if (!twe_not_null($shipping_status_id)) {
            $next_id_query = "select max(shipping_status_id) as shipping_status_id from " . TABLE_SHIPPING_STATUS;
            $next_id = $db->Execute($next_id_query);
            $shipping_status_id = $next_id->fields['shipping_status_id'] + 1;
          }

          $insert_sql_data = array('shipping_status_id' => $shipping_status_id,
                                   'language_id' => $language_id);
          $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
          twe_db_perform(TABLE_SHIPPING_STATUS, $sql_data_array);
        } elseif ($_GET['action'] == 'save') {
          twe_db_perform(TABLE_SHIPPING_STATUS, $sql_data_array, 'update', "shipping_status_id = '" . twe_db_input($shipping_status_id) . "' and language_id = '" . $language_id . "'");
        }
      }

      if ($shipping_status_image = new upload('shipping_status_image',DIR_WS_ICONS)) {
        $db->Execute("update " . TABLE_SHIPPING_STATUS . " set shipping_status_image = '" . $shipping_status_image->filename . "' where shipping_status_id = '" . twe_db_input($shipping_status_id) . "'");
      }

      if ($_POST['default'] == 'on') {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '" . twe_db_input($shipping_status_id) . "' where configuration_key = 'DEFAULT_SHIPPING_STATUS_ID'");
      }

      twe_redirect(twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $shipping_status_id));
      break;

    case 'deleteconfirm':
      $oID = twe_db_prepare_input($_GET['oID']);

      $shipping_status_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_SHIPPING_STATUS_ID'";
      $shipping_status = $db->Execute($shipping_status_query);
      if ($shipping_status->fields['configuration_value'] == $oID) {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_SHIPPING_STATUS_ID'");
      }

      $db->Execute("delete from " . TABLE_SHIPPING_STATUS . " where shipping_status_id = '" . twe_db_input($oID) . "'");

      twe_redirect(twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page']));
      break;

    case 'delete':
      $oID = twe_db_prepare_input($_GET['oID']);


      $remove_status = true;
      if ($oID == DEFAULT_SHIPPING_STATUS_ID) {
        $remove_status = false;
        $messageStack->add(ERROR_REMOVE_DEFAULT_SHIPPING_STATUS, 'error');
      } else {

      }
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
    <td class="pageHeading"><?php echo BOX_SHIPPING_STATUS; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Configuration</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SHIPPING_STATUS_IMAGE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SHIPPING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
<?php
  $shipping_status_query_raw = "select shipping_status_id, shipping_status_name,shipping_status_image from " . TABLE_SHIPPING_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by shipping_status_id";
  $shipping_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $shipping_status_query_raw, $shipping_status_query_numrows);
  $shipping_status = $db->Execute($shipping_status_query_raw);
  while (!$shipping_status->EOF) {
    if (((!$_GET['oID']) || ($_GET['oID'] == $shipping_status->fields['shipping_status_id'])) && (!$oInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $oInfo = new objectInfo($shipping_status->fields);
    }

    if ( (is_object($oInfo)) && ($shipping_status->fields['shipping_status_id'] == $oInfo->shipping_status_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $shipping_status->fields['shipping_status_id']) . '\'">' . "\n";
    }

    if (DEFAULT_SHIPPING_STATUS_ID == $shipping_status->fields['shipping_status_id']) {
        echo '<td class="dataTableContent" align="left">';
     if ($shipping_status->fields['shipping_status_image'] != '') {
       echo twe_image(DIR_WS_ICONS . $shipping_status->fields['shipping_status_image'] , IMAGE_ICON_INFO);
     }
     echo '</td>';
      echo '                <td class="dataTableContent"><b>' . $shipping_status->fields['shipping_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {

      			echo '<td class="dataTableContent" align="left">';
                       if ($shipping_status->fields['shipping_status_image'] != '') {
                           echo twe_image(DIR_WS_ICONS . $shipping_status->fields['shipping_status_image'] , IMAGE_ICON_INFO);
                           }
                           echo '</td>';
      echo '                <td class="dataTableContent">' . $shipping_status->fields['shipping_status_name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if ( (is_object($oInfo)) && ($shipping_status->fields['shipping_status_id'] == $oInfo->shipping_status_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $shipping_status->fields['shipping_status_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$shipping_status->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $shipping_status_split->display_count($shipping_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_SHIPPING_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $shipping_status_split->display_links($shipping_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (substr($_GET['action'], 0, 3) != 'new') {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&action=new') . '">' . twe_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_SHIPPING_STATUS . '</b>');

      $contents = array('form' => twe_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $shipping_status_inputs_string = '';
      $languages = twe_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $shipping_status_inputs_string .= '<br>' . twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . twe_draw_input_field('shipping_status_name[' . $languages[$i]['id'] . ']');
      }
      $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_STATUS_IMAGE . '<br>' . twe_draw_file_field('shipping_status_image'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_STATUS_NAME . $shipping_status_inputs_string);
      $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_SHIPPING_STATUS . '</b>');

      $contents = array('form' => twe_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id  . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $shipping_status_inputs_string = '';
      $languages = twe_get_languages();
      for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
        $shipping_status_inputs_string .= '<br>' . twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . twe_draw_input_field('shipping_status_name[' . $languages[$i]['id'] . ']', twe_get_shipping_status_name($oInfo->shipping_status_id, $languages[$i]['id']));
      }
      $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_STATUS_IMAGE . '<br>' . twe_draw_file_field('shipping_status_image',$oInfo->shipping_status_image));
      $contents[] = array('text' => '<br>' . TEXT_INFO_SHIPPING_STATUS_NAME . $shipping_status_inputs_string);
      if (DEFAULT_SHIPPING_STATUS_ID != $oInfo->shipping_status_id) $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SHIPPING_STATUS . '</b>');

      $contents = array('form' => twe_draw_form('status', FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $oInfo->shipping_status_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->shipping_status_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_SHIPPING_STATUS, 'page=' . $_GET['page'] . '&oID=' . $oInfo->shipping_status_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');

        $shipping_status_inputs_string = '';
        $languages = twe_get_languages();
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $shipping_status_inputs_string .= '<br>' . twe_image(DIR_WS_LANGUAGES.$languages[$i]['directory'].'/admin/images/'.$languages[$i]['image']) . '&nbsp;' . twe_get_shipping_status_name($oInfo->shipping_status_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $shipping_status_inputs_string);
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