<?php
/* --------------------------------------------------------------
   $Id: stats_sales_report.php,v 1.2 2004/02/29 17:05:18 oldpa Exp $

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce coding standards; www.oscommerce.com   (c) 2003	 xt-commerce  www.xt-commerce.com
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------
   Third Party contribution:

   stats_sales_report (c) Charly Wilhelm  charly@yoshi.ch

   possible views (srView):
  1 yearly
  2 monthly
  3 weekly
  4 daily

  possible options (srDetail):
  0 no detail
  1 show details (products)
  2 show details only (products)

  export
  0 normal view
  1 html view without left and right
  2 csv

  sort
  0 no sorting
  1 product description asc
  2 product description desc
  3 #product asc, product descr asc
  4 #product desc, product descr desc
  5 revenue asc, product descr asc
  6 revenue desc, product descr des   
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  require('includes/application_top.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  // default detail
  $srDefaultDetail = 2;
  // default view
  $srDefaultView = 4;
  // default export
  $srDefaultExp = 0;
  // default sort
  $srDefaultSort = 4;
  // default date field
  $srDate_field_default = 1;
  // model # location
  $model_select = 0;

//                     //
// END DEFAULT SETTINGS//
//                     //


  // report views (1: yearly 2: monthly 3: weekly 4: daily)
  if ( ($_GET['report']) && (twe_not_null($_GET['report'])) ) {
    $srView = $_GET['report'];
  }
  if ($srView < 1 || $srView > 4) {
    $srView = $srDefaultView;
  }

  // detail
  if ( ($_GET['detail']) && (twe_not_null($_GET['detail'])) ) {
    $srDetail = $_GET['detail'];
  }
  if ($srDetail < 0 || $srDetail > 2) {
    $srDetail = $srDefaultDetail;
  }

  // report views (0: Normal 1: Print View 2: CSV)
  if ( ($_GET['export']) && (twe_not_null($_GET['export'])) ) {
    $srExp = $_GET['export'];
  }
  if ($srExp < 0 || $srExp > 2) {
    $srExp = $srDefaultExp;
  }

  // max returns
  if ( ($_GET['max']) && (twe_not_null($_GET['max'])) ) {
    $srMax = $_GET['max'];
  }
  if (!is_numeric($srMax)) {
    $srMax = 0;
  }

  // payment method
  if ( ($_GET['payment_method']) && (twe_not_null($_GET['payment_method'])) ) {
    $srPayment = $_GET['payment_method'];
  }
  else {
    $srPayment = 0;
  }

  // order status
  if ( ($_GET['status']) && (twe_not_null($_GET['status'])) ) {
    $srStatus = $_GET['status'];
  }
  else {
    $srStatus = 0;
  }

  // sort
  if ( ($_GET['sort']) && (twe_not_null($_GET['sort'])) ) {
    $srSort = $_GET['sort'];
  }
  if ($srSort < 0 || $srSort > 7) {
    $srSort = $srDefaultSort;
  }

  // target date field
  if ( ($_GET['date_field']) && (twe_not_null($_GET['date_field'])) ) {
    $srDate_field = $_GET['date_field'];
  }
  if ($srDate_field < 0 || $srDate_field > 1) {
    $srDate_field = $srDate_field_default;
  }

  $model_from_catalog = ($model_select == 1) ? true : false;


  // check start and end Date
  $startDate = "";
  $startDateG = 0;
  if ( ($_GET['startD']) && (twe_not_null($_GET['startD'])) ) {
    $sDay = $_GET['startD'];
    $startDateG = 1;
  } else {
    $sDay = 1;
  }
  if ( ($_GET['startM']) && (twe_not_null($_GET['startM'])) ) {
    $sMon = $_GET['startM'];
    $startDateG = 1;
  } else {
    $sMon = 1;
  }
  if ( ($_GET['startY']) && (twe_not_null($_GET['startY'])) ) {
    $sYear = $_GET['startY'];
    $startDateG = 1;
  } else {
    $sYear = date("Y");
  }
  if ($startDateG) {
    $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
  } else {
    $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }
    
  $endDate = "";
  $endDateG = 0;
  if ( ($_GET['endD']) && (twe_not_null($_GET['endD'])) ) {
    $eDay = $_GET['endD'];
    $endDateG = 1;
  } else {
    $eDay = 1;
  }
  if ( ($_GET['endM']) && (twe_not_null($_GET['endM'])) ) {
    $eMon = $_GET['endM'];
    $endDateG = 1;
  } else {
    $eMon = 1;
  }
  if ( ($_GET['endY']) && (twe_not_null($_GET['endY'])) ) {
    $eYear = $_GET['endY'];
    $endDateG = 1;
  } else {
    $eYear = date("Y");
  }
  if ($endDateG) {
    $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
    $text_ed = mktime(0, 0, 0, $eMon, $eDay, $eYear);
  } else {
    $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
    $text_ed = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
  }

  require(DIR_WS_CLASSES . 'sales_report.php');
  $sr = new sales_report($srView, $startDate, $endDate, $srSort, $srStatus, $srFilter, $srDate_field, $srPayment);
  $startDate = $sr->startDate;
  $endDate = $sr->endDate;

  if ($srExp == 2) {
    $filename = TITLE . CSV_FILENAME_PREFIX . date('Ymd', $startDate) . "-" . date('Ymd', $endDate);
    header("Pragma: cache");
    header("Content-Type: text/comma-separated-values");
    header("Content-Disposition: attachment; filename=".urlencode($filename).".csv");

    echo CSV_HEADING_START_DATE . SR_SEPARATOR1;
    echo CSV_HEADING_END_DATE . SR_SEPARATOR1;
    echo TABLE_HEADING_DESCRIPTION . SR_SEPARATOR1;
    echo TABLE_HEADING_ORDERS . SR_SEPARATOR1;
    echo TABLE_HEADING_MODEL . SR_SEPARATOR1;
    echo TABLE_HEADING_ITEMS . SR_SEPARATOR1;
    echo TABLE_HEADING_PRICE . SR_SEPARATOR1;
    echo TABLE_HEADING_SHIPPING . SR_SEPARATOR1;
    echo TABLE_HEADING_TAX . SR_SEPARATOR1;
    echo TABLE_HEADING_DISCOUNTS . SR_SEPARATOR1;
    echo TABLE_HEADING_VOUCHERS . SR_SEPARATOR1;
    echo TABLE_HEADING_TOTAL . SR_NEWLINE;
  }

  if ($srExp < 2) {
    // not for csv export
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

<?php
    if ($srExp < 1) {
      require(DIR_WS_INCLUDES . 'header.php');
    }
?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<?php
    if ($srExp < 1) {
?>
<td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
<?php
    } // end sr_exp
?>
    <td width="100%" valign="top">
   <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
     <td>
      
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
<?php
                if ($srExp < 1 ) {
?>
                  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                  <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td></tr>
<?php
                }
                // Header for the print format
                if ($srExp == 1) {
                // create arrays of defined titles for each variable
                  $report_array = array('', REPORT_TYPE_YEARLY, REPORT_TYPE_MONTHLY, REPORT_TYPE_WEEKLY, REPORT_TYPE_DAILY);
                  $detail_array = array(DET_HEAD_ONLY, DET_DETAIL, DET_DETAIL_ONLY);
                  $statusQuery = $db->Execute("SELECT orders_status_id, orders_status_name
                                               FROM " . TABLE_ORDERS_STATUS . "
                                               ORDER BY orders_status_id ASC");
                  $status_array = array('All');
                  $a = 1;
                  while (!$statusQuery->EOF) {
                    $status_array[$a] = $statusQuery->fields['orders_status_name'];
                    $a++;
                    $statusQuery->MoveNext();
                  }

                  $sort_array = array(SORT_VAL0, SORT_VAL1, SORT_VAL2, SORT_VAL3, SORT_VAL4, SORT_VAL5, SORT_VAL6, SORT_VAL7);
                  $date_field_array = array(REPORT_DATE_PURCHASED, REPORT_LAST_MOD);

                // select the proper title
                  if (sizeof($status_array) > 8) {
                    $limit = sizeof($status_array);
                  }
                  else {
                    $limit = 8;
                  }
                  for ($b = 0; $b < $limit; $b++) {
                    if ($_GET['report'] == $b) {
                      $report = $report_array[$b];
                    }
                    if ($_GET['detail'] == $b) {
                      $detail = $detail_array[$b];
                    }
                    if ($_GET['status'] == $b) {
                      $status = $status_array[$b];
                    }
                    if ($_GET['sort'] == $b) {
                      $sort = $sort_array[$b];
                    }
                    if ($_GET['date_field'] == $b) {
                      $date_field = $date_field_array[$b];
                    }
                  }
                  // format the pre-existing variables
                  if ($srMax == 0) {
                    $max = "All Orders";
                  }
                  else {
                    $max = $srMax;
                  }

                  // now display the title information
?>
                    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="pageHeading" align="left"><?php echo $report . " Sales Report"; ?></td>
                        <td class="pageHeading" colspan="2" align="right"><?php echo date(REPORT_DATE_FORMAT, $sr->startDate) . ' to ' . date(REPORT_DATE_FORMAT, $text_ed); ?></td>
                      </tr>
                      <tr>
                        <td><?php echo twe_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
                      </tr>
                      <tr>
                        <td align="left"><table border="0" width="100%" cellspacing="2" cellpadding="0">
                          <tr>
                            <td class="dataTableContent" align="left"><?php echo '<b>' . PRINT_SEARCH_HEADER . '</b>'; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="105" align="left"><?php echo REPORT_DATE_FIELD . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $date_field; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="105" align="left"><?php echo REPORT_STATUS_FILTER . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $status; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="105" align="left"><?php echo REPORT_PAYMENT_FILTER . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php
                              if ($sr->payment_method != "") {
                                echo $sr->payment_method;
                              }
                              else {
                                echo REPORT_ALL;
                              }
                            ?></td>
                          </tr>
                        </table></td>
                        <td><?php echo twe_draw_separator('pixel_trans.gif', '90', '1'); ?></td>
                        <td align="right"><table border="0" width="100%" cellspacing="2" cellpadding="0">
                          <tr>
                            <td class="dataTableContent" align="left"><?php echo '<b>' . PRINT_SORT_HEADER . '</b>'; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="90" align="left"><?php echo REPORT_DETAIL . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $detail; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="90" align="left"><?php echo REPORT_MAX . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $max; ?></td>
                          </tr>
                          <tr>
                            <td class="dataTableContent" width="90" align="left"><?php echo REPORT_SORT . ':'; ?></td>
                            <td class="dataTableContent" align="left"><?php echo $sort; ?></td>
                          </tr>
                        </table></td>
                      </tr>
                    </table></td>
<?php
                  } // END if ($srExp == 1)
?>
            </table>
          </td>
        </tr>
<?php
    if ($srExp < 1) {
?>
        <tr>
          <td colspan="2">
            <form action="" method="get">
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" rowspan="2" class="menuBoxHeading">
                    <input type="radio" name="report" value="1" <?php if ($srView == 1) echo "checked"; ?>><?php echo REPORT_TYPE_YEARLY; ?><br>
                    <input type="radio" name="report" value="2" <?php if ($srView == 2) echo "checked"; ?>><?php echo REPORT_TYPE_MONTHLY; ?><br>
                    <input type="radio" name="report" value="3" <?php if ($srView == 3) echo "checked"; ?>><?php echo REPORT_TYPE_WEEKLY; ?><br>
                    <input type="radio" name="report" value="4" <?php if ($srView == 4) echo "checked"; ?>><?php echo REPORT_TYPE_DAILY; ?><br>
                  </td>
                  <td class="menuBoxHeading"><?php echo REPORT_START_DATE; ?><br>
                    <select name="startD" size="1">
<?php
      if ($startDate) {
        $j = date("j", $startDate);
      } else {
        $j = 1;
      }
      for ($i = 1; $i < 32; $i++) {
?>
                        <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
      }
?>
                    </select>
                    <select name="startM" size="1">
<?php
      if ($startDate) {
        $m = date("n", $startDate);
      } else {
        $m = 1;
      }
      for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%m", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
      }
?>
                    </select>
                    <select name="startY" size="1">
<?php
      if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
      } else {
        $y = 0;
      }
      for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
<?php
    }
?>
                    </select>
                  </td>
                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_DETAIL; ?><br>
                    <select name="detail" size="1">
                      <option value="0"<?php if ($srDetail == 0) echo " selected"; ?>><?php echo DET_HEAD_ONLY; ?></option>
                      <option value="1"<?php if ($srDetail == 1) echo " selected"; ?>><?php echo DET_DETAIL; ?></option>
                      <option value="2"<?php if ($srDetail == 2) echo " selected"; ?>><?php echo DET_DETAIL_ONLY; ?></option>
                    </select><br><br>
                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <td rowspan="2" align="left" class="menuBoxHeading">
                        <?php echo REPORT_MAX; ?><br>
                        <select name="max" size="1">
                          <option value="0"><?php echo REPORT_ALL; ?></option>
                          <option<?php if ($srMax == 1) echo " selected"; ?>>1</option>
                          <option<?php if ($srMax == 3) echo " selected"; ?>>3</option>
                          <option<?php if ($srMax == 5) echo " selected"; ?>>5</option>
                          <option<?php if ($srMax == 10) echo " selected"; ?>>10</option>
                          <option<?php if ($srMax == 25) echo " selected"; ?>>25</option>
                          <option<?php if ($srMax == 50) echo " selected"; ?>>50</option>
                        </select>
                      </td>
                      <td rowspan="2" align="left" class="menuBoxHeading">
                        <?php echo REPORT_PAYMENT_FILTER; ?><br>
                        <select name="payment_method" size="1">
                          <option value="0" <?php if ($srPayment == 0) echo " selected"; echo '>' . REPORT_ALL; ?></option>
<?php
                          foreach ($sr->payment as $value) {
?>
                            <option value="<?php echo $value["id"]; ?>"<?php if ($srPayment == $value["id"]) echo " selected"; echo '>' . $value["payment_method"]; ?></option>
<?php
                          }
?>
                        </select>
                      </td>
                    </table>
                  </td>
                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_STATUS_FILTER; ?><br>
                    <select name="status" size="1">
                      <option value="0"><?php echo REPORT_ALL; ?></option>
<?php
                      foreach ($sr->status as $value) {
?>
                        <option value="<?php echo $value["orders_status_id"]?>"<?php if ($srStatus == $value["orders_status_id"]) echo " selected"; ?>><?php echo $value["orders_status_name"] ; ?></option>
<?php
                      }
?>
                    </select><br><br>

                    <?php echo REPORT_DATE_FIELD; ?><br>
                    <select name="date_field" size="1">
                      <option value="0"<?php if ($srDate_field == 0) echo " selected"; ?>><?php echo REPORT_DATE_PURCHASED; ?></option>
                      <option value="1"<?php if ($srDate_field == 1) echo " selected"; ?>><?php echo REPORT_LAST_MOD; ?></option>
                    </select><br>
                  </td>

                  <td rowspan="2" align="left" class="menuBoxHeading">
                    <?php echo REPORT_EXP; ?><br>
                    <select name="export" size="1">
                      <option value="0" selected><?php echo EXP_NORMAL; ?></option>
                      <option value="1"><?php echo EXP_HTML; ?></option>
                      <option value="2"><?php echo EXP_CSV; ?></option>
                    </select><br><br>
                    <?php echo REPORT_SORT; ?><br>
                    <select name="sort" size="1">
                      <option value="0"<?php if ($srSort == 0) echo " selected"; ?>><?php echo SORT_VAL0; ?></option>
                      <option value="1"<?php if ($srSort == 1) echo " selected"; ?>><?php echo SORT_VAL1; ?></option>
                      <option value="2"<?php if ($srSort == 2) echo " selected"; ?>><?php echo SORT_VAL2; ?></option>
                      <option value="3"<?php if ($srSort == 3) echo " selected"; ?>><?php echo SORT_VAL3; ?></option>
                      <option value="4"<?php if ($srSort == 4) echo " selected"; ?>><?php echo SORT_VAL4; ?></option>
                      <option value="5"<?php if ($srSort == 5) echo " selected"; ?>><?php echo SORT_VAL5; ?></option>
                      <option value="6"<?php if ($srSort == 6) echo " selected"; ?>><?php echo SORT_VAL6; ?></option>
                      <option value="7"<?php if ($srSort == 7) echo " selected"; ?>><?php echo SORT_VAL7; ?></option>
                    </select><br>
                  </td>
                </tr>
                <tr>
                  <td class="menuBoxHeading">
<?php echo REPORT_END_DATE; ?><br>
                    <select name="endD" size="1">
<?php
    if ($endDate) {
      $j = date("j", $endDate - 60* 60 * 24);
    } else {
      $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
    }
?>
                    </select>
                    <select name="endM" size="1">
<?php
    if ($endDate) {
      $m = date("n", $endDate - 60* 60 * 24);
    } else {
      $m = date("n");
    }
    for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%m", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
    }
?>
                    </select>
                    <select name="endY" size="1">
<?php
    if ($endDate) {
      $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
    } else {
      $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option><?php
    }
?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colspan="5" class="menuBoxHeading" align="right">
                    <input type="submit" value="<?php echo REPORT_SEND; ?>">
                  </td>
              </table>
<?php if (SID) echo twe_draw_hidden_field(twe_session_name(), twe_session_id());
?>

            </form>
          </td>
        </tr>
<?php
  } // end of ($srExp < 1)
?>
        <tr>
          <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td valign="top">
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php if ($srExp == 1) echo '<hr>'; ?>
                    <tr class="dataTableHeadingRow">
                      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE; ?></td>
                      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
                      <td class="dataTableHeadingContent" align="center"></td>
                      <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_MODEL; ?></td>
                      <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ITEMS; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE; ?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SHIPPING;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DISCOUNTS;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_VOUCHERS;?></td>
                      <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL;?></td>
                    </tr>
<?php
  } // end of if $srExp < 2 (csv export)
  $grandpricetotal = 0;
  $grandtotal = 0;
  $granditemcount = 0;
  $grandordercount = 0;
  $grandtaxtotal = 0;
  $grandshippingtotal = 0;
  $granddiscounttotal = 0;
  $grandvouchertotal = 0;

  $totaldays = floor(($sr->endDate - $sr->actDate) / 60 / 60 / 24);

  while ($sr->actDate < $sr->endDate) {
    $info = $sr->getNext();
    $last = sizeof($info) - 1;
    if ($srExp < 2) {
?>
                    <tr class="dataTableRowUnique" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRowUnique'">
<?php
      switch ($srView) {
        case '3':
?>
                      <td class="dataTableContent" align="left"><?php echo twe_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . twe_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
          break;
        case '4':
?>
                      <td class="dataTableContent" align="left"><?php echo twe_date_short(date("Y-m-d\ H:i:s", $sr->showDate)); ?></td>
<?php
          break;
        default;
?>
                      <td class="dataTableContent" align="left"><?php echo twe_date_short(date("Y-m-d\ H:i:s", $sr->showDate)) . " - " . twe_date_short(date("Y-m-d\ H:i:s", $sr->showDateEnd)); ?></td>
<?php
    }
?>

                      <td class="dataTableContent" align="right"><?php echo TABLE_VALUE_ORDER_TOTAL; ?></td>
                      <td class="dataTableContent" align="center"><?php if ($info[$last]['order'] == '') echo '0';
                                                                        else echo $info[$last]['order']; ?></td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="center"><?php if ($info[$last]['totitem'] == '') echo '0';
                                                                        else echo $info[$last]['totitem']; ?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['totsum']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['shipping']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['taxes']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['discounts']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['vouchers']);?></td>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$last]['total']);?></td>
                    </tr>
<?php
      $grandpricetotal += $info[$last]['totsum'];
      $grandtotal += $info[$last]['total'];
      $granditemcount += $info[$last]['totitem'];
      $grandordercount += $info[$last]['order'];
      $grandtaxtotal += $info[$last]['taxes'];
      $grandshippingtotal += $info[$last]['shipping'];
      $granddiscounttotal += $info[$last]['discounts'];
      $grandvouchertotal += $info[$last]['vouchers'];
    }
    else {
      // csv export (format: fromdate,todate,description,ordercount,itemcount,price,shipping,tax,discounts,total)
      echo date(REPORT_DATE_FORMAT, $sr->showDate) . SR_SEPARATOR1 . date(REPORT_DATE_FORMAT, $sr->showDateEnd) . SR_SEPARATOR1;
      echo TABLE_VALUE_ORDER_TOTAL . SR_SEPARATOR1;
      echo $info[$last]['order'] . SR_SEPARATOR1 . SR_SEPARATOR1;
      echo $info[$last]['totitem'] . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['totsum']) . '"' . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['shipping']) . '"' . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['taxes']) . '"' . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['discounts']) . '"' . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['vouchers']) . '"' . SR_SEPARATOR1;
      echo '"' . $currencies->format($info[$last]['total']) . '"' . SR_NEWLINE;
    }
    if ($srDetail) {
      for ($i = 0; $i <= $last; $i++) {
        if ($srMax == 0 or $i < $srMax) {
          if ($srExp < 2) {
?>
                    <tr class="dataTableRow" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRow'">
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent" align="left"><a href="<?php echo twe_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $info[$i]['pname']; ?></a>
<?php
  if (is_array($info[$i]['attr'])) {
    $attr_info = $info[$i]['attr'];
    foreach ($attr_info as $attr) {
      echo '<div style="font-style:italic;">&nbsp;' . $attr['quant'] . 'x ' ;
      //  $attr['options'] . ': '
      $flag = 0;
      foreach ($attr['options_values'] as $value) {
        if ($flag > 0) {
          echo "," . $value;
        } else {
          echo $value;
          $flag = 1;
        }
      }
      $price = 0;
      foreach ($attr['price'] as $value) {
        $price += $value;
      }
      if ($price != 0) {
        echo ' (';
        if ($price > 0) {
          echo "+";
        }
        echo $currencies->format($price). ')';
      }
      echo '</div>';
    }
  }

  if ($model_from_catalog) {
    $get_pmodel = $db->Execute("SELECT products_model FROM " . TABLE_PRODUCTS . "
                  WHERE products_id = '" . $info[$i]['pid'] . "'");
    $pmodel = $get_pmodel->fields['products_model'];
  } else {
    $pmodel = $info[$i]['pmodel'];
  }
?>                    </td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="left"><a href="<?php echo twe_catalog_href_link("product_info.php?products_id=" . $info[$i]['pid']) ?>" target="_blank"><?php echo $pmodel; ?></a></td>
                      <td class="dataTableContent" align="center"><?php echo (int)$info[$i]['pquant']; ?></td>
<?php
          if ($srDetail == 2) {?>
                      <td class="dataTableContent" align="right"><?php echo $currencies->format($info[$i]['psum']); ?></td>
<?php
          } else { ?>
                      <td class="dataTableContent">&nbsp;</td>
<?php
          }
?>
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent">&nbsp;</td>
                      <td class="dataTableContent">&nbsp;</td>
                    </tr>
<?php
        }
        else {
        // csv export (format: ,,description,,itemcount,price,,,,)
          echo SR_SEPARATOR2 . SR_SEPARATOR2;
          echo '"' . $info[$i]['pname'];

          if (is_array($info[$i]['attr'])) {
            $attr_info = $info[$i]['attr'];
            echo '(';
            foreach ($attr_info as $attr) {
              $flag = 0;
              foreach ($attr['options_values'] as $value) {
                if ($flag > 0) {
                  echo "; " . $value;
                } else {
                  echo $value;
                  $flag = 1;
                }
              }
              $price = 0;
              foreach ($attr['price'] as $value) {
                $price += $value;
              }
              if ($price != 0) {
                echo ' (';
                if ($price > 0) {
                  echo "+";
                } else {
                  echo " ";
                }
                echo $currencies->format($price). ')';
              }
              echo ")";
            }
          }
          echo '"' . SR_SEPARATOR2 . SR_SEPARATOR2;

          echo $info[$i]['pmodel'] . SR_SEPARATOR2;

          echo $info[$i]['pquant'] . SR_SEPARATOR2;

          if ($srDetail == 2) {
              echo '"' . $currencies->format($info[$i]['pquant'] * $info[$i]['price']) . '"';
          }

          echo SR_SEPARATOR2 . SR_SEPARATOR2 . SR_SEPARATOR2 . SR_SEPARATOR2 . SR_NEWLINE;
        }
      }
    }
  }
}
if ($srExp < 2) {
?>
                    <tr class="dataTableRowUnique" onMouseOver="this.className='dataTableRowOver';this.style.cursor='hand'" onMouseOut="this.className='dataTableRowUnique'">
                      <td class="dataTableContent" align="left"><strong>Total Days: <?php echo $totaldays ?></strong></td>
                      <td class="dataTableContent" align="right"><strong>Grand Totals:</strong></td>
                      <td class="dataTableContent" align="center"><strong><?php echo $grandordercount; ?></strong></td>
                      <td class="dataTableContent" align="right">&nbsp;</td>
                      <td class="dataTableContent" align="center"><strong><?php echo $granditemcount; ?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($grandpricetotal); ?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($grandshippingtotal);?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($grandtaxtotal);?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($granddiscounttotal);?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($grandvouchertotal);?></strong></td>
                      <td class="dataTableContent" align="right"><strong><?php echo $currencies->format($grandtotal);?></strong></td>
                    </tr>

                  </table>
               </td>
        </tr>
      </table>
          </td>
        </tr>
      </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php
  if ($srExp < 1) {
    require(DIR_WS_INCLUDES . 'footer.php');
  }
?>
<!-- footer_eof //-->
</body>
</html>
<?php
  require(DIR_WS_INCLUDES . 'application_bottom.php');
} // end if $srExp < 2

?>