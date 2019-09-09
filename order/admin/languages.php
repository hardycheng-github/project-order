<?php
/* --------------------------------------------------------------
   $Id: languages.php,v 1.5 2004/03/01 13:07:21 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.33 2003/05/07); www.oscommerce.com 
   (c) 2003	 nextcommerce (languages.php,v 1.10 2003/08/18); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');

  switch ($_GET['action']) {
    case 'insert':
      $name = twe_db_prepare_input($_POST['name']);
      $code = twe_db_prepare_input($_POST['code']);
      $image = twe_db_prepare_input($_POST['image']);
      $directory = twe_db_prepare_input($_POST['directory']);
      $sort_order = twe_db_prepare_input($_POST['sort_order']);
      $charset = twe_db_prepare_input($_POST['charset']);

      $db->Execute("insert into " . TABLE_LANGUAGES . " (name, code, image, directory, sort_order,language_charset) values ('" . twe_db_input($name) . "', '" . twe_db_input($code) . "', '" . twe_db_input($image) . "', '" . twe_db_input($directory) . "', '" . twe_db_input($sort_order) . "', '" . twe_db_input($charset) . "')");
      $insert_id = $db->Insert_ID();

      // create additional categories_description records
      $categories = $db->Execute("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c left join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.categories_id = cd.categories_id where cd.language_id = '" . $_SESSION['languages_id'] . "'");
      while (!$categories->EOF) {
        $db->Execute("insert into " . TABLE_CATEGORIES_DESCRIPTION . " (categories_id, language_id, categories_name) values ('" . $categories->fields['categories_id'] . "', '" . $insert_id . "', '" . twe_db_input($categories->fields['categories_name']) . "')");
      $categories->MoveNext();
	  }

      // create additional products_description records
      $products = $db->Execute("select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where pd.language_id = '" . $_SESSION['languages_id'] . "'");
      while (!$products->EOF) {
        $db->Execute("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url) values ('" . $products->fields['products_id'] . "', '" . $insert_id . "', '" . twe_db_input($products->fields['products_name']) . "', '" . twe_db_input($products->fields['products_description']) . "', '" . twe_db_input($products->fields['products_url']) . "')");
      $products->MoveNext();
	  }

      // create additional products_options records
      $products_options = $db->Execute("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $_SESSION['languages_id'] . "'");
      while (!$products_options->EOF) {
        $db->Execute("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . $products_options->fields['products_options_id'] . "', '" . $insert_id . "', '" . twe_db_input($products_options->fields['products_options_name']) . "')");
     $products_options->MoveNext();
	  }

      // create additional products_options_values records
      $products_options_values = $db->Execute("select products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . $_SESSION['languages_id'] . "'");
      while (!$products_options_values->EOF) {
        $db->Execute("insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . $products_options_values->fields['products_options_values_id'] . "', '" . $insert_id . "', '" . twe_db_input($products_options_values->fields['products_options_values_name']) . "')");
     $products_options_values->MoveNext(); 
	  }

      // create additional manufacturers_info records
      $manufacturers = $db->Execute("select m.manufacturers_id, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on m.manufacturers_id = mi.manufacturers_id where mi.languages_id = '" . $_SESSION['languages_id'] . "'");
      while (!$manufacturers->EOF) {
        $db->Execute("insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturers_id, languages_id, manufacturers_url) values ('" . $manufacturers->fields['manufacturers_id'] . "', '" . $insert_id . "', '" . twe_db_input($manufacturers->fields['manufacturers_url']) . "')");
      $manufacturers->MoveNext();
	  }

      // create additional orders_status records
      $orders_status = $db->Execute("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $_SESSION['languages_id'] . "'");
      while (!$orders_status->EOF) {
        $db->Execute("insert into " . TABLE_ORDERS_STATUS . " (orders_status_id, language_id, orders_status_name) values ('" . $orders_status->fields['orders_status_id'] . "', '" . $insert_id . "', '" . twe_db_input($orders_status->fields['orders_status_name']) . "')");
      $orders_status->MoveNext();
	  }
      
      // create additional customers status
            $data=$db->Execute("SELECT DISTINCT customers_status_id FROM ".TABLE_CUSTOMERS_STATUS);
      while (!$data->EOF) {
 
      $customers_status_data_query="SELECT * 
      						FROM ".TABLE_CUSTOMERS_STATUS."
      						WHERE customers_status_id='".$data->fields['customers_status_id']."'"; 
      
      $group_data=$db->Execute($customers_status_data_query);
 	$c_data=array(
 		'customers_status_id'=>$data->fields['customers_status_id'],
 		'language_id'=>$insert_id,
 		'customers_status_name'=>$group_data->fields['customers_status_name'],
 		'customers_status_public'=>$group_data->fields['customers_status_public'],
 		'customers_status_image'=>$group_data->fields['customers_status_image'],
 		'customers_status_discount'=>$group_data->fields['customers_status_discount'],
 		'customers_status_ot_discount_flag'=>$group_data->fields['customers_status_ot_discount_flag'],
 		'customers_status_ot_discount'=>$group_data->fields['customers_status_ot_discount'],
 		'customers_status_graduated_prices'=>$group_data->fields['customers_status_graduated_prices'],
 		'customers_status_show_price'=>$group_data->fields['customers_status_show_price'],
 		'customers_status_show_price_tax'=>$group_data->fields['customers_status_show_price_tax'],
 		'customers_status_add_tax_ot'=>$group_data->fields['customers_status_add_tax_ot'],
 		'customers_status_payment_unallowed'=>$group_data->fields['customers_status_payment_unallowed'],
 		'customers_status_shipping_unallowed'=>$group_data->fields['customers_status_shipping_unallowed'],
 		'customers_status_discount_attributes'=>$group_data->fields['customers_status_discount_attributes']);  
 		
 	twe_db_perform(TABLE_CUSTOMERS_STATUS, $c_data);	    	
    $data->MoveNext();  	
	}

      if ($_POST['default'] == 'on') {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '" . twe_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      }

      twe_redirect(twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $insert_id));
      break;

    case 'save':
      $lID = twe_db_prepare_input($_GET['lID']);
      $name = twe_db_prepare_input($_POST['name']);
      $code = twe_db_prepare_input($_POST['code']);
      $image = twe_db_prepare_input($_POST['image']);
      $directory = twe_db_prepare_input($_POST['directory']);
      $sort_order = twe_db_prepare_input($_POST['sort_order']);
     $charset = twe_db_prepare_input($_POST['charset']);
	  
      $db->Execute("update " . TABLE_LANGUAGES . " set name = '" . twe_db_input($name) . "', code = '" . twe_db_input($code) . "', image = '" . twe_db_input($image) . "', directory = '" . twe_db_input($directory) . "', sort_order = '" . twe_db_input($sort_order) . "', language_charset = '" . twe_db_input($charset) . "' where languages_id = '" . twe_db_input($lID) . "'");

      if ($_POST['default'] == 'on') {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '" . twe_db_input($code) . "' where configuration_key = 'DEFAULT_LANGUAGE'");
      }

      twe_redirect(twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']));
      break;

    case 'deleteconfirm':
      $lID = twe_db_prepare_input($_GET['lID']);

      $lng_query = "select languages_id from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_CURRENCY . "'";
      $lng = $db->Execute($lng_query);
      if ($lng->fields['languages_id'] == $lID) {
        $db->Execute("update " . TABLE_CONFIGURATION . " set configuration_value = '' where configuration_key = 'DEFAULT_CURRENCY'");
      }

      $db->Execute("delete from " . TABLE_CATEGORIES_DESCRIPTION . " where language_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_PRODUCTS_DESCRIPTION . " where language_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where language_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_MANUFACTURERS_INFO . " where languages_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_ORDERS_STATUS . " where language_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_LANGUAGES . " where languages_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_CONTENT_MANAGER . " where languages_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_PRODUCTS_CONTENT . " where languages_id = '" . twe_db_input($lID) . "'");
      $db->Execute("delete from " . TABLE_CUSTOMERS_STATUS . " where language_id = '" . twe_db_input($lID) . "'");

      twe_redirect(twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page']));
      break;

    case 'delete':
      $lID = twe_db_prepare_input($_GET['lID']);

      $lng_query = "select code from " . TABLE_LANGUAGES . " where languages_id = '" . twe_db_input($lID) . "'";
      $lng = $db->Execute($lng_query);

      $remove_language = true;
      if ($lng->fields['code'] == DEFAULT_LANGUAGE) {
        $remove_language = false;
        $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LANGUAGE_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $languages_query_raw = "select languages_id, name, code, image, directory, sort_order,language_charset from " . TABLE_LANGUAGES . " order by sort_order";
  $languages_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $languages_query_raw, $languages_query_numrows);
  $languages = $db->Execute($languages_query_raw);

  while (!$languages->EOF) {
    if (((!$_GET['lID']) || (@$_GET['lID'] == $languages->fields['languages_id'])) && (!$lInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $lInfo = new objectInfo($languages->fields);
    }

    if ( (is_object($lInfo)) && ($languages->fields['languages_id'] == $lInfo->languages_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages->fields['languages_id']) . '\'">' . "\n";
    }

    if (DEFAULT_LANGUAGE == $languages->fields['code']) {
      echo '                <td class="dataTableContent"><b>' . $languages->fields['name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $languages->fields['name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $languages->fields['code']; ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($lInfo)) && ($languages->fields['languages_id'] == $lInfo->languages_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $languages->fields['languages_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$languages->MoveNext();
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $languages_split->display_count($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $languages_split->display_links($languages_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (!$_GET['action']) {
?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=new') . '">' . twe_image_button('button_new_language.gif', IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $direction_options = array( array('id' => '', 'text' => TEXT_INFO_LANGUAGE_DIRECTION_DEFAULT),
                              array('id' => 'ltr', 'text' => TEXT_INFO_LANGUAGE_DIRECTION_LEFT_TO_RIGHT),
                              array('id' => 'rtl', 'text' => TEXT_INFO_LANGUAGE_DIRECTION_RIGHT_TO_LEFT));

  $heading = array();
  $contents = array();
  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>');

      $contents = array('form' => twe_draw_form('languages', FILENAME_LANGUAGES, 'action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . twe_draw_input_field('name'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . twe_draw_input_field('code'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CHARSET . '<br>' . twe_draw_input_field('charset'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . twe_draw_input_field('image', 'icon.gif'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . twe_draw_input_field('directory'));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . twe_draw_input_field('sort_order'));
      $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . ' <a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $_GET['lID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</b>');

      $contents = array('form' => twe_draw_form('languages', FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . twe_draw_input_field('name', $lInfo->name));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CODE . '<br>' . twe_draw_input_field('code', $lInfo->code));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_CHARSET . '<br>' . twe_draw_input_field('charset', $lInfo->language_charset));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_IMAGE . '<br>' . twe_draw_input_field('image', $lInfo->image));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . twe_draw_input_field('directory', $lInfo->directory));
      $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . twe_draw_input_field('sort_order', $lInfo->sort_order));
      if (DEFAULT_LANGUAGE != $lInfo->code) $contents[] = array('text' => '<br>' . twe_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</b>');

      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br><b>' . $lInfo->name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>' : '') . ' <a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_LANGUAGES, 'page=' . $_GET['page'] . '&lID=' . $lInfo->languages_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_CODE . ' ' . $lInfo->code);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_CHARSET_INFO . ' ' . $lInfo->language_charset);

        $contents[] = array('text' => '<br>' . twe_image(DIR_WS_LANGUAGES . $lInfo->directory . '/' . $lInfo->image, $lInfo->name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . DIR_WS_LANGUAGES . '<b>' . $lInfo->directory . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
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