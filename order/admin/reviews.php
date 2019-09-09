<?php
/* --------------------------------------------------------------
   $Id: reviews.php,v 1.2 2004/02/29 17:05:18 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(reviews.php,v 1.40 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (reviews.php,v 1.9 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'update':
        $reviews_id = twe_db_prepare_input($_GET['rID']);
        $reviews_rating = twe_db_prepare_input($_POST['reviews_rating']);
        $last_modified = twe_db_prepare_input($_POST['last_modified']);
        $reviews_text = twe_db_prepare_input($_POST['reviews_text']);

        $db->Execute("update " . TABLE_REVIEWS . " set reviews_rating = '" . twe_db_input($reviews_rating) . "', last_modified = now() where reviews_id = '" . twe_db_input($reviews_id) . "'");
        $db->Execute("update " . TABLE_REVIEWS_DESCRIPTION . " set reviews_text = '" . twe_db_input($reviews_text) . "' where reviews_id = '" . twe_db_input($reviews_id) . "'");

        twe_redirect(twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));
        break;

      case 'deleteconfirm':
        $reviews_id = twe_db_prepare_input($_GET['rID']);

        $db->Execute("delete from " . TABLE_REVIEWS . " where reviews_id = '" . twe_db_input($reviews_id) . "'");
        $db->Execute("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . twe_db_input($reviews_id) . "'");

        twe_redirect(twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page']));
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($_GET['action'] == 'edit') {
    $rID = twe_db_prepare_input($_GET['rID']);

    $reviews_query = "select r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . twe_db_input($rID) . "' and r.reviews_id = rd.reviews_id";
    $reviews = $db->Execute($reviews_query);
    $products_query = "select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews->fields['products_id'] . "'";
    $products = $db->Execute($products_query);

    $products_name_query = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $reviews->fields['products_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
    $products_name = $db->Execute($products_name_query);

    $rInfo_array = twe_array_merge($reviews->fields, $products->fields, $products_name->fields);
    $rInfo = new objectInfo($rInfo_array);
?>
      <tr><?php echo twe_draw_form('review', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=preview'); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo twe_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo twe_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo twe_draw_textarea_field('reviews_text', 'soft', '60', '15', $rInfo->reviews_text); ?></td>
          </tr>
          <tr>
            <td class="smallText" align="right"><?php echo ENTRY_REVIEW_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<?php for ($i=1; $i<=5; $i++) echo twe_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating) . '&nbsp;'; echo TEXT_GOOD; ?></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo twe_draw_hidden_field('reviews_id', $rInfo->reviews_id) . twe_draw_hidden_field('products_id', $rInfo->products_id) . twe_draw_hidden_field('customers_name', $rInfo->customers_name) . twe_draw_hidden_field('products_name', $rInfo->products_name) . twe_draw_hidden_field('products_image', $rInfo->products_image) . twe_draw_hidden_field('date_added', $rInfo->date_added) . twe_image_submit('button_preview.gif', IMAGE_PREVIEW) . ' <a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
  } elseif ($_GET['action'] == 'preview') {
    if ($_POST) {
      $rInfo = new objectInfo($_POST);
    } else {
      $reviews_query = "select r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $_GET['rID'] . "' and r.reviews_id = rd.reviews_id";
      $reviews = $db->Execute($reviews_query);
      $products_query = "select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews->fields['products_id'] . "'";
      $products = $db->Execute($products_query);

      $products_name_query = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $reviews->fields['products_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
      $products_name = $db->Execute($products_name_query);

      $rInfo_array = twe_array_merge($reviews->fields, $products->fields, $products_name->fields);
      $rInfo = new objectInfo($rInfo_array);
    }
?>
      <tr><?php echo twe_draw_form('update', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo twe_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo twe_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo nl2br(twe_db_output(twe_break_string($rInfo->reviews_text, 15))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo twe_image(DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)); ?>&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating); ?>]</small></td>
      </tr>
      <tr>
        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    if ($_POST) {
      // Re-Post all POST'ed variables
      reset($_POST);
      while(list($key, $value) = each($_POST)) echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes($value)) . '">';
?>
      <tr>
        <td align="right" class="smallText"><?php echo '<a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
    } else {
      if ($_GET['origin']) {
        $back_url = $_GET['origin'];
        $back_url_params = '';
      } else {
        $back_url = FILENAME_REVIEWS;
        $back_url_params = 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . twe_href_link($back_url, $back_url_params, 'NONSSL') . '">' . twe_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    }
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_RATING; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $reviews_query_raw = "select reviews_id, products_id, date_added, last_modified, reviews_rating from " . TABLE_REVIEWS . " order by date_added DESC";
    $reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $reviews_query_raw, $reviews_query_numrows);
    $reviews = $db->Execute($reviews_query_raw);
    while (!$reviews->EOF) {
      if ( ((!$_GET['rID']) || ($_GET['rID'] == $reviews->fields['reviews_id'])) && (!$rInfo) ) {
        $reviews_text_query = "select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . $reviews->fields['reviews_id'] . "' and r.reviews_id = rd.reviews_id";
        $reviews_text = $db->Execute($reviews_text_query);

        $products_image_query = "select products_image from " . TABLE_PRODUCTS . " where products_id = '" . $reviews->fields['products_id'] . "'";
        $products_image = $db->Execute($products_image_query);

        $products_name_query = "select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $reviews->fields['products_id'] . "' and language_id = '" . $_SESSION['languages_id'] . "'";
        $products_name = $db->Execute($products_name_query);

        $reviews_average_query = "select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . $reviews->fields['products_id'] . "'";
        $reviews_average = $db->Execute($reviews_average_query);

        $review_info = twe_array_merge($reviews_text->fields, $reviews_average->fields, $products_name->fields);
        $rInfo_array = twe_array_merge($reviews->fields, $review_info, $products_image->fields);
        $rInfo = new objectInfo($rInfo_array);
      }

      if ( (is_object($rInfo)) && ($reviews->fields['reviews_id'] == $rInfo->reviews_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews->fields['reviews_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews->fields['reviews_id'] . '&action=preview') . '">' . twe_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . twe_get_products_name($reviews->fields['products_id']); ?></td>
                <td class="dataTableContent" align="right"><?php echo twe_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $reviews->fields['reviews_rating'] . '.gif'); ?></td>
                <td class="dataTableContent" align="right"><?php echo twe_date_short($reviews->fields['date_added']); ?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($rInfo)) && ($reviews->fields['reviews_id'] == $rInfo->reviews_id) ) { echo twe_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews->fields['reviews_id']) . '">' . twe_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
$reviews->MoveNext();
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
                    <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($_GET['action']) {
      case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');

        $contents = array('form' => twe_draw_form('reviews', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
        $contents[] = array('text' => '<br><b>' . $rInfo->products_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . twe_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
      if (is_object($rInfo)) {
        $heading[] = array('text' => '<b>' . $rInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . twe_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . twe_date_short($rInfo->date_added));
        if (twe_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . twe_date_short($rInfo->last_modified));
        $contents[] = array('text' => '<br>' . twe_info_image($rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);
        $contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . twe_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif'));
        $contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);
        $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');
        $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
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
<?php
  }
?>
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