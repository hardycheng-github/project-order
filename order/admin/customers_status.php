<?php
/* --------------------------------------------------------------
   $Id: customers_status.php,v 1.6 2004/02/29 17:05:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce( based on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35); www.oscommerce.com
   (c) 2003	 nextcommerce (customers_status.php,v 1.28 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   based on Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  switch ($_GET['action']) {
    case 'insert':
    case 'save':
      $customers_status_id = twe_db_prepare_input($_GET['cID']);

      $languages = twe_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $customers_status_name_array = $_POST['customers_status_name'];
        $customers_status_show_price = $_POST['customers_status_show_price'];
        $customers_status_show_price_tax = $_POST['customers_status_show_price_tax'];
        $customers_status_public = $_POST['customers_status_public'];
        $customers_status_discount = $_POST['customers_status_discount'];
        $customers_status_ot_discount_flag = $_POST['customers_status_ot_discount_flag'];
        $customers_status_ot_discount = $_POST['customers_status_ot_discount'];
        $customers_status_graduated_prices = $_POST['customers_status_graduated_prices'];
        $customers_status_discount_attributes = $_POST['customers_status_discount_attributes'];
        $customers_status_add_tax_ot = $_POST['customers_status_add_tax_ot'];
        $customers_status_payment_unallowed = $_POST['customers_status_payment_unallowed'];
        $customers_status_shipping_unallowed = $_POST['customers_status_shipping_unallowed'];
        $customers_fsk18 = $_POST['customers_fsk18'];
        $customers_fsk18_display = $_POST['customers_fsk18_display'];

        $language_id = $languages[$i]['id'];

        $sql_data_array = array(
          'customers_status_name' => twe_db_prepare_input($customers_status_name_array[$language_id]),
          'customers_status_public' => twe_db_prepare_input($customers_status_public),
          'customers_status_show_price' => twe_db_prepare_input($customers_status_show_price),
          'customers_status_show_price_tax' => twe_db_prepare_input($customers_status_show_price_tax),
          'customers_status_discount' => twe_db_prepare_input($customers_status_discount),
          'customers_status_ot_discount_flag' => twe_db_prepare_input($customers_status_ot_discount_flag),
          'customers_status_ot_discount' => twe_db_prepare_input($customers_status_ot_discount),
          'customers_status_graduated_prices' => twe_db_prepare_input($customers_status_graduated_prices),
          'customers_status_add_tax_ot' => twe_db_prepare_input($customers_status_add_tax_ot),
          'customers_status_payment_unallowed' => twe_db_prepare_input($customers_status_payment_unallowed),
          'customers_status_shipping_unallowed' => twe_db_prepare_input($customers_status_shipping_unallowed),
          'customers_fsk18' => twe_db_prepare_input($customers_fsk18),
          'customers_fsk18_display' => twe_db_prepare_input($customers_fsk18_display),
          'customers_status_discount_attributes' => twe_db_prepare_input($customers_status_discount_attributes)
        );
        if ($_GET['action'] == 'insert') {
          if (!twe_not_null($customers_status_id)) {
            $next_id_query = "select max(customers_status_id) as customers_status_id from " . TABLE_CUSTOMERS_STATUS . "";
            $next_id = $db->Execute($next_id_query);
            $customers_status_id = $next_id->fields['customers_status_id'] + 1;
            // We want to create a personal offer table corresponding to each customers_status
            $db->Execute("create table personal_offers_by_customers_status_" . $customers_status_id . " (price_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, products_id int NOT NULL, quantity int, personal_offer decimal(15,4))");
          }

          $insert_sql_data = array('customers_status_id' => twe_db_prepare_input($customers_status_id), 'language_id' => twe_db_prepare_input($language_id));
          $sql_data_array = twe_array_merge($sql_data_array, $insert_sql_data);
          twe_db_perform(TABLE_CUSTOMERS_STATUS, $sql_data_array);
                    
        } elseif ($_GET['action'] == 'save') {
          twe_db_perform(TABLE_CUSTOMERS_STATUS, $sql_data_array, 'update', "customers_status_id = '" . twe_db_input($customers_status_id) . "' and language_id = '" . $language_id . "'");
        }
      }
       
      function twe_try_upload($file = '', $destination = '', $permissions = '777', $extensions = ''){
  			$file_object = new upload($file, $destination, $permissions, $extensions);
		  	if ($file_object->filename != '') return $file_object; else return false;
			  }  
         if ($customers_status_image =twe_try_upload('customers_status_image', DIR_WS_ICONS ,'777','')) {
			$status_image_name = twe_db_input($customers_status_image->filename);
			}else{
			$status_image_name = twe_db_prepare_input($_POST['customers_status_previous_image']);
			}
            $db->Execute("update " . TABLE_CUSTOMERS_STATUS . " set customers_status_image = '" . $status_image_name . "' where customers_status_id = '" . twe_db_input($customers_status_id) . "'");

      if ($_POST['default'] == 'on') {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '" . twe_db_input($customers_status_id) . "' where configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'");
      }

      twe_redirect(twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $customers_status_id));
      break;

    case 'deleteconfirm':
      $cID = twe_db_prepare_input($_GET['cID']);

      $customers_status_query = "select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'";
      $customers_status = $db->Execute($customers_status_query);
      if ($customers_status->fields['configuration_value'] == $cID) {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'");
      }

      $db->Execute("delete from " . TABLE_CUSTOMERS_STATUS . " where customers_status_id = '" . twe_db_input($cID) . "'");

      // We want to drop the existing corresponding personal_offers table
      $db->Execute("drop table IF EXISTS personal_offers_by_customers_status_" . twe_db_input($cID) . "");
      twe_redirect(twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page']));
      break;

    case 'delete':
      $cID = twe_db_prepare_input($_GET['cID']);

      $status_query = "select count(*) as count from " . TABLE_CUSTOMERS . " where customers_status = '" . twe_db_input($cID) . "'";
      $status = $db->Execute($status_query);

      $remove_status = true;
      if (($cID == DEFAULT_CUSTOMERS_STATUS_ID) || ($cID == DEFAULT_CUSTOMERS_STATUS_ID_GUEST) || ($cID == DEFAULT_CUSTOMERS_STATUS_ID_NEWSLETTER)) {
        $remove_status = false;
        $messageStack->add(ERROR_REMOVE_DEFAULT_CUSTOMERS_STATUS, 'error');
      } elseif ($status->fields['count'] > 0) {
        $remove_status = false;
        $messageStack->add(ERROR_STATUS_USED_IN_CUSTOMERS, 'error');
      } else {
        $history_query = "select count(*) as count from " . TABLE_CUSTOMERS_STATUS_HISTORY . " where '" . twe_db_input($cID) . "' in (new_value, old_value)";
        $history = $db->Execute($history_query);
        if ($history->fields['count'] > 0) {
          // delete from history
          $db->Execute("DELETE FROM " . TABLE_CUSTOMERS_STATUS_HISTORY . "
                        where '" . twe_db_input($cID) . "' in (new_value, old_value)");
          $remove_status = true;
          // $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
        }
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
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_customers.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr> 
    <td class="main" valign="top">TWE Customers</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left" width=""><?php echo 'icon'; ?></td>
                <td class="dataTableHeadingContent" align="left" width=""><?php echo 'user'; ?></td>
                <td class="dataTableHeadingContent" align="left" width=""><?php echo TABLE_HEADING_CUSTOMERS_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center" width=""><?php echo TABLE_HEADING_TAX_PRICE; ?></td>
                <td class="dataTableHeadingContent" align="center" colspan="2"><?php echo TABLE_HEADING_DISCOUNT; ?></td>
                <td class="dataTableHeadingContent" width=""><?php echo TABLE_HEADING_CUSTOMERS_GRADUATED; ?></td>
                <td class="dataTableHeadingContent" width=""><?php echo TABLE_HEADING_CUSTOMERS_UNALLOW; ?></td>
                <td class="dataTableHeadingContent" width=""><?php echo TABLE_HEADING_CUSTOMERS_UNALLOW_SHIPPING; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $customers_status_ot_discount_flag_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_graduated_prices_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_public_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_show_price_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_show_price_tax_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_discount_attributes_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_status_add_tax_ot_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_fsk18_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));
  $customers_fsk18_display_array = array(array('id' => '0', 'text' => ENTRY_NO), array('id' => '1', 'text' => ENTRY_YES));

  $customers_status_query_raw = "select * from " . TABLE_CUSTOMERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "' order by customers_status_id";

  $customers_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_status_query_raw, $customers_status_query_numrows);
  $customers_status = $db->Execute($customers_status_query_raw);
  while (!$customers_status->EOF) {
    if (((!$_GET['cID']) || ($_GET['cID'] == $customers_status->fields['customers_status_id'])) && (!$cInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $cInfo = new objectInfo($customers_status->fields);
    }

    if ( (is_object($cInfo)) && ($customers_status->fields['customers_status_id'] == $cInfo->customers_status_id) ) {
      echo '<tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '<tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $customers_status->fields['customers_status_id']) . '\'">' . "\n";
    }

    echo '<td class="dataTableContent" align="left">';
     if ($customers_status->fields['customers_status_image'] != '') {
       echo twe_image(DIR_WS_ICONS . $customers_status->fields['customers_status_image'] , IMAGE_ICON_INFO);
     }
     echo '</td>';

     echo '<td class="dataTableContent" align="left">';
     echo twe_get_status_users($customers_status->fields['customers_status_id']);
     echo '</td>';

    if ($customers_status->fields['customers_status_id'] == DEFAULT_CUSTOMERS_STATUS_ID ) {
      echo '<td class="dataTableContent" align="left"><b>' . $customers_status->fields['customers_status_name'];
      echo ' (' . TEXT_DEFAULT . ')';
    } else {
      echo '<td class="dataTableContent" align="left">' . $customers_status->fields['customers_status_name'];
    }
    if ($customers_status->fields['customers_status_public'] == '1') {
      echo ' ,public ';
    }
    echo '</b></td>';

    if ($customers_status->fields['customers_status_show_price'] == '1') {
      echo '<td nowrap class="smallText" align="center"> ';
      if ($customers_status->fields['customers_status_show_price_tax'] == '1') {
        echo TAX_YES;
      } else {
        echo TAX_NO;
      }
    } else {
      echo '<td class="smallText" align="left"> ';
    }
    echo '</td>';

    echo '<td nowrap class="smallText" align="center">' . $customers_status->fields['customers_status_discount'] . ' %</td>';
      
    echo '<td nowrap class="dataTableContent" align="center">';
    if ($customers_status->fields['customers_status_ot_discount_flag'] == 0){
      echo '<font color="ff0000">'.$customers_status->fields['customers_status_ot_discount'].' %</font>';
    } else {
      echo $customers_status->fields['customers_status_ot_discount'].' %';
    }
    echo ' </td>';
  
    echo '<td class="dataTableContent" align="center">';
    if ($customers_status->fields['customers_status_graduated_prices'] == 0) {
      echo NO;
    } else {
      echo YES;
    }
    echo '</td>';
    echo '<td nowrap class="smallText" align="center">' . $customers_status->fields['customers_status_payment_unallowed'] . '</td>';
    echo '<td nowrap class="smallText" align="center">' . $customers_status->fields['customers_status_shipping_unallowed'] . '</td>';
    echo "\n";
?>
                <td class="dataTableContent" align="right"><?php if ( (is_object($cInfo)) && ($customers_status->fields['customers_status_id'] == $cInfo->customers_status_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $customers_status->fields['customers_status_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$customers_status->MoveNext();
  }
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_status_split->display_count($customers_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_status_split->display_links($customers_status_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (substr($_GET['action'], 0, 3) != 'new') {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&action=new') . '">' . twe_image_button('button_insert.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS . '</b>');
      $contents = array('form' => twe_draw_form('status', FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $customers_status_inputs_string = '';
      $languages = twe_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $customers_status_inputs_string .= '<br>' . twe_image(DIR_WS_CATALOG.'lang/'.$languages[$i]['directory'].'/admin/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . twe_draw_input_field('customers_status_name[' . $languages[$i]['id'] . ']');
      }
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_NAME . $customers_status_inputs_string);
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_IMAGE . '<br>' . twe_draw_file_field('customers_status_image'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_PUBLIC . ' ' . twe_draw_pull_down_menu('customers_status_public', $customers_status_public_array, $cInfo->customers_status_public ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO     . '<br>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE . ' ' . twe_draw_pull_down_menu('customers_status_show_price', $customers_status_show_price_array, $cInfo->customers_status_show_price ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX . ' ' . twe_draw_pull_down_menu('customers_status_show_price_tax', $customers_status_show_price_tax_array, $cInfo->customers_status_show_price_tax ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_ADD_TAX_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_ADD_TAX . ' ' . twe_draw_pull_down_menu('customers_status_add_tax_ot', $customers_status_add_tax_ot_array, $cInfo->customers_status_add_tax_ot));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . '<br>' . twe_draw_input_field('customers_status_discount', $cInfo->customers_status_discount));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES_INTRO     . '<br>' . ENTRY_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES . ' ' . twe_draw_pull_down_menu('customers_status_discount_attributes', $customers_status_discount_attributes_array, $cInfo->customers_status_discount_attributes ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br> ' . ENTRY_OT_XMEMBER . ' ' . twe_draw_pull_down_menu('customers_status_ot_discount_flag', $customers_status_ot_discount_flag_array, $cInfo->customers_status_ot_discount_flag ). '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . '<br>' . twe_draw_input_field('customers_status_ot_discount', $cInfo->customers_status_ot_discount));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_GRADUATED_PRICES_INTRO . '<br>' . ENTRY_GRADUATED_PRICES . ' ' . twe_draw_pull_down_menu('customers_status_graduated_prices', $customers_status_graduated_prices_array, $cInfo->customers_status_graduated_prices ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_PAYMENT_UNALLOWED . ' ' . twe_draw_input_field('customers_status_payment_unallowed', $cInfo->customers_status_payment_unallowed ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHIPPING_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_SHIPPING_UNALLOWED . ' ' . twe_draw_input_field('customers_status_shipping_unallowed', $cInfo->customers_status_shipping_unallowed ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_FSK18_INTRO . '<br>' . ENTRY_CUSTOMERS_FSK18 . ' ' . twe_draw_pull_down_menu('customers_fsk18', $customers_fsk18_array, $cInfo->customers_fsk18));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_FSK18_DISPLAY_INTRO . '<br>' . ENTRY_CUSTOMERS_FSK18_DISPLAY . ' ' . twe_draw_pull_down_menu('customers_fsk18_display', $customers_fsk18_display_array, $cInfo->customers_fsk18_display));
      $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS . '</b>');
      $contents = array('form' => twe_draw_form('status', FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id  .'&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $customers_status_inputs_string = '';
      $languages = twe_get_languages();
      for ($i=0; $i<sizeof($languages); $i++) {
        $customers_status_inputs_string .= '<br>' . twe_image(DIR_WS_CATALOG.'lang/'.$languages[$i]['directory'].'/admin/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . twe_draw_input_field('customers_status_name[' . $languages[$i]['id'] . ']', twe_get_customers_status_name($cInfo->customers_status_id, $languages[$i]['id']));
      }
	  
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_NAME . $customers_status_inputs_string);
      $contents[] = array('text' => '<br>' . twe_image(DIR_WS_ICONS . $cInfo->customers_status_image, $cInfo->customers_status_name) . '<br>' . DIR_WS_ICONS . '<br><b>' . $cInfo->customers_status_image . '</b>');
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_IMAGE . '<br>' . twe_draw_file_field('customers_status_image', $cInfo->customers_status_image).twe_draw_hidden_field('customers_status_previous_image', $cInfo->customers_status_image));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_PUBLIC . ' ' . twe_draw_pull_down_menu('customers_status_public', $customers_status_public_array, $cInfo->customers_status_public ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO     . '<br>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE . ' ' . twe_draw_pull_down_menu('customers_status_show_price', $customers_status_show_price_array, $cInfo->customers_status_show_price ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX . ' ' . twe_draw_pull_down_menu('customers_status_show_price_tax', $customers_status_show_price_tax_array, $cInfo->customers_status_show_price_tax ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_ADD_TAX_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_ADD_TAX . ' ' . twe_draw_pull_down_menu('customers_status_add_tax_ot', $customers_status_add_tax_ot_array, $cInfo->customers_status_add_tax_ot));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . ' ' . twe_draw_input_field('customers_status_discount', $cInfo->customers_status_discount));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES . ' ' . twe_draw_pull_down_menu('customers_status_discount_attributes', $customers_status_discount_attributes_array, $cInfo->customers_status_discount_attributes ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br> ' . ENTRY_OT_XMEMBER . ' ' . twe_draw_pull_down_menu('customers_status_ot_discount_flag', $customers_status_ot_discount_flag_array, $cInfo->customers_status_ot_discount_flag). '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . ' ' . twe_draw_input_field('customers_status_ot_discount', $cInfo->customers_status_ot_discount));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_GRADUATED_PRICES_INTRO . '<br>' . ENTRY_GRADUATED_PRICES . ' ' . twe_draw_pull_down_menu('customers_status_graduated_prices', $customers_status_graduated_prices_array, $cInfo->customers_status_graduated_prices));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_PAYMENT_UNALLOWED . ' ' . twe_draw_input_field('customers_status_payment_unallowed', $cInfo->customers_status_payment_unallowed ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHIPPING_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_SHIPPING_UNALLOWED . ' ' . twe_draw_input_field('customers_status_shipping_unallowed', $cInfo->customers_status_shipping_unallowed ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_FSK18_INTRO . '<br>' . ENTRY_CUSTOMERS_FSK18 . ' ' . twe_draw_pull_down_menu('customers_fsk18', $customers_fsk18_array, $cInfo->customers_fsk18 ));
      $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_FSK18_DISPLAY_INTRO . '<br>' . ENTRY_CUSTOMERS_FSK18_DISPLAY . ' ' . twe_draw_pull_down_menu('customers_fsk18_display', $customers_fsk18_display_array, $cInfo->customers_fsk18_display));
      if (DEFAULT_CUSTOMERS_STATUS_ID != $cInfo->customers_status_id) $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS . '</b>');

      $contents = array('form' => twe_draw_form('status', FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $cInfo->customers_status_name . '</b>');

      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->customers_status_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'page=' . $_GET['page'] . '&cID=' . $cInfo->customers_status_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $customers_status_inputs_string = '';
        $languages = twe_get_languages();
        for ($i=0; $i<sizeof($languages); $i++) {
          $customers_status_inputs_string .= '<br>' . twe_image(DIR_WS_CATALOG.'lang/'. $languages[$i]['directory'] . '/admin/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . twe_get_customers_status_name($cInfo->customers_status_id, $languages[$i]['id']);
        }
        $contents[] = array('text' => $customers_status_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . ' ' . $cInfo->customers_status_discount . '%');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br>' . ENTRY_OT_XMEMBER . ' ' . $customers_status_ot_discount_flag_array[$cInfo->customers_status_ot_discount_flag]['text'] . ' (' . $cInfo->customers_status_ot_discount_flag . ')' . ' - ' . $cInfo->customers_status_ot_discount . '%');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_GRADUATED_PRICES_INTRO . '<br>' . ENTRY_GRADUATED_PRICES . ' ' . $customers_status_graduated_prices_array[$cInfo->customers_status_graduated_prices]['text'] . ' (' . $cInfo->customers_status_graduated_prices . ')' );
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES . ' ' . $customers_status_discount_attributes_array[$cInfo->customers_status_discount_attributes]['text'] . ' (' . $cInfo->customers_status_discount_attributes . ')' );
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_PAYMENT_UNALLOWED . ':<b> ' . $cInfo->customers_status_payment_unallowed.'</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHIPPING_UNALLOWED_INTRO . '<br>' . ENTRY_CUSTOMERS_STATUS_SHIPPING_UNALLOWED . ':<b> ' . $cInfo->customers_status_shipping_unallowed.'</b>');
      }
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo '<td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '</td>' . "\n";
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