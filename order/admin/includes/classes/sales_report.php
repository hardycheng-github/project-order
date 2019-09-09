<?php
/* --------------------------------------------------------------
   $Id: sales_report.php,v 1.2 2004/03/11 23:29:53 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.twCopyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce coding standards; www.oscommerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contribution:

   stats_sales_report (c) Charly Wilhelm  charly@yoshi.ch

  
   Released under the GNU General Public License
   --------------------------------------------------------------*/

  class sales_report {
    var $mode, $globalStartDate, $startDate, $endDate, $actDate, $showDate, $showDateEnd, $sortString, $status, $outlet, $srDate_field, $srPayment;

    function sales_report($mode, $startDate = 0, $endDate = 0, $sort = 0, $statusFilter = 0, $filter = 0, $srDate_field, $srPayment = 0) {
      global $db;

      // Use $srDate_type to determine which date field we will be looking at
      // date_purchased (value 0) or last_modified (value 1)
      // If invalid default to date_purchased
      $this->srDate_field = $srDate_field;
      if ($srDate_field == 0) {
        define('DATE_TARGET', 'date_purchased');
      }

      else if ($srDate_field == 1) {
        define('DATE_TARGET', 'last_modified');
      }

      else {
        define('DATE_TARGET', 'date_purchased');
      }

      // startDate and endDate have to be a unix timestamp. Use mktime !
      // if set then both have to be valid startDate and endDate
      $this->mode = $mode;
      $this->tax_include = DISPLAY_PRICE_WITH_TAX;

      $this->statusFilter = $statusFilter;
      $this->srPayment = $srPayment;

      // get date of first sale
      $first = $db->Execute("select UNIX_TIMESTAMP(min(" . DATE_TARGET . ")) as first FROM " . TABLE_ORDERS);

      $this->globalStartDate = mktime(0, 0, 0, date("m", $first->fields['first']), date("d", $first->fields['first']), date("Y", $first->fields['first']));
            
      $statusQuery = $db->Execute("SELECT orders_status_id, orders_status_name
                                   FROM " . TABLE_ORDERS_STATUS . "
                                   WHERE language_id = '" . (int)$_SESSION['languages_id'] . "'");
      $i = 0;
      while (!$statusQuery->EOF) {
        $this->status[$i] = $statusQuery->fields;
        $i++;
        $statusQuery->MoveNext();
      }
      //$this->status = $status;

      $paymentQuery = $db->Execute("SELECT DISTINCT payment_method FROM " . TABLE_ORDERS);
      $a = 1;
      while (!$paymentQuery->EOF) {
        $this->payment[] = array('id' => $a,
                                 'payment_method' => $paymentQuery->fields['payment_method']);
        $a++;
        $paymentQuery->MoveNext();
      }
      $this->payment_method = $this->payment[$this->srPayment - 1]['payment_method'];

      if ($startDate == 0  or $startDate < $this->globalStartDate) {
        // set startDate to globalStartDate
        $this->startDate = $this->globalStartDate;
      } else {
        $this->startDate = $startDate;
      }
      if ($this->startDate > mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
        $this->startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
      }

      if ($endDate > mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))) {
        // set endDate to tomorrow
        $this->endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
      } else {
        $this->endDate = $endDate;
      }
      if ($this->endDate < $this->startDate + 24 * 60 * 60) {
        $this->endDate = $this->startDate + 24 * 60 * 60;
      }
      
      $this->actDate = $this->startDate;

      // query for order count
      $this->queryOrderCnt = "SELECT count(o.orders_id) as order_cnt FROM " . TABLE_ORDERS . " o";

      // queries for item details count
      $this->queryItemCnt = "SELECT o.orders_id, op.products_id as pid, op.orders_products_id, op.products_name as pname, op.products_model as pmodel, sum(op.products_quantity) as pquant, sum(op.products_price * op.products_quantity) as psum, op.products_tax as ptax FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = op.orders_id";

      // query for attributes
      $this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " opa, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op WHERE o.orders_id = opa.orders_id AND op.orders_products_id = opa.orders_products_id";

      // query for shipping
      $this->queryShipping = "SELECT sum(ot.value) as shipping FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND ot.class = 'ot_shipping'";

      // query for totals
      $this->queryTotal = "SELECT sum(ot.value) as total FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND ot.class = 'ot_total'";

      // query for taxes
      $this->queryTaxes = "SELECT sum(ot.value) as taxes FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND ot.class = 'ot_tax'";

      // query for discounts
      $this->queryDiscounts = "SELECT sum(ot.value) as discounts FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND (ot.class = 'ot_group_pricing' OR ot.class = 'ot_coupon')";

      // query for gift certificates
      $this->queryVouchers = "SELECT sum(ot.value) as vouchers FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot WHERE ot.orders_id = o.orders_id AND ot.class = 'ot_gv'";


      switch ($sort) {
        case '0':
          $this->sortString = "";
          break;
        case '1':
          $this->sortString = " ORDER BY pname ASC";
          break;
        case '2':
          $this->sortString = " ORDER BY pname DESC";
          break;
        case '3':
          $this->sortString = " ORDER BY pquant ASC, pname ASC";
          break;
        case '4':
          $this->sortString = " ORDER BY pquant DESC, pname ASC";
          break;
        case '5':
          $this->sortString = " ORDER BY psum ASC, pname ASC";
          break;
        case '6':
          $this->sortString = " ORDER BY psum DESC, pname ASC";
          break;
        case '7':
          $this->sortString = " ORDER BY pmodel DESC";
          break;
      }

    }

    function getNext() {
      global $db;

      switch ($this->mode) {
        // yearly
        case '1':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd), date("Y", $sd) + 1);
          break;
        // monthly
        case '2':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd) + 1, 1, date("Y", $sd));
          break;
        // weekly
        case '3':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 7, date("Y", $sd));
          break;
        // daily
        case '4':
          $sd = $this->actDate;
          $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 1, date("Y", $sd));
          break;
      }
      if ($ed > $this->endDate) {
        $ed = $this->endDate;
      }

      $filterString = "";
      if ($this->statusFilter > 0) {
        $filterString .= " AND o.orders_status = " . $this->statusFilter . " ";
      }

      // determine if queries should be limited to a certain payment method
      if ($this->srPayment > 0) {
        $filterString .= " AND o.payment_method = '" . $this->payment_method . "' ";
      }

      $order = $db->Execute($this->queryOrderCnt . " WHERE o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $shipping = $db->Execute($this->queryShipping . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $ototal = $db->Execute($this->queryTotal . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $taxes = $db->Execute($this->queryTaxes . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $discounts = $db->Execute($this->queryDiscounts . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $vouchers = $db->Execute($this->queryVouchers . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);

      $rqItems = $db->Execute($this->queryItemCnt . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString . " GROUP BY pid " . $this->sortString);

      // set the return values
      $this->actDate = $ed;
      $this->showDate = $sd;
      $this->showDateEnd = $ed - 60 * 60 * 24;

      // execute the query
      $cnt = 0;
      $itemTot = 0;
      $sumTot = 0;
      while (!$rqItems->EOF) {
      	$resp[$cnt] = $rqItems->fields;
        // to avoid rounding differences round for every quantum
        // multiply with the number of items afterwords.
        $price = $resp[$cnt]['psum'] / $resp[$cnt]['pquant'];

        // products_attributes
        // are there any attributes for this order_id ?
        $rqAttr = $db->Execute($this->queryAttr . " AND o." . DATE_TARGET . " >= '" . twe_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o." . DATE_TARGET . " < '" . twe_db_input(date("Y-m-d\TH:i:s", $ed)) . "' AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " GROUP BY products_options_values ORDER BY orders_products_id");
        $i = 0;
        while (!$rqAttr->EOF) {
          $attr[$i] = $rqAttr->fields;
          $i++;
          $rqAttr->MoveNext();
        }

        // values per date
        if ($i > 0) {
          $price2 = 0;
          $price3 = 0;
          $option = array();
          $k = -1;
          $ord_pro_id_old = 0;
          for ($j = 0; $j < $i; $j++) {
            if ($attr[$j]['price_prefix'] == "-") {
              $price2 += (-1) *  $attr[$j]['options_values_price'];
              $price3 = (-1) * $attr[$j]['options_values_price'];
              $prefix = "-";
            } else {
              $price2 += $attr[$j]['options_values_price'];
              $price3 = $attr[$j]['options_values_price'];
              $prefix = "+";
            }
            $ord_pro_id = $attr[$j]['orders_products_id'];
            if ( $ord_pro_id != $ord_pro_id_old) {
              $k++;
              $l = 0;
              // set values
              $option[$k]['quant'] = $attr[$j]['attr_cnt'];
              $option[$k]['options'][0] = $attr[$j]['products_options'];
              $option[$k]['options_values'][0] = $attr[$j]['products_options_values'];
              if ($price3 != 0) {
                $option[$k]['price'][0] = $price3;
              } else {
                $option[$k]['price'][0] = 0;
              }
            } else {
              $l++;
              // update values
              $option[$k]['options'][$l] = $attr[$j]['products_options'];
              $option[$k]['options_values'][$l] = $attr[$j]['products_options_values'];
              if ($price3 != 0) {
                $option[$k]['price'][$l] = twe_calculate_tax($price3, $resp[$cnt]['ptax'] + 100);
              } else {
                $option[$k]['price'][$l] = 0;
              }
            }
            $ord_pro_id_old = $ord_pro_id;
          }
          // set attr value
          $resp[$cnt]['attr'] = $option;
        } else {
          $resp[$cnt]['attr'] = "";
        }


        // item detail (per product)
        $resp[$cnt]['price'] = $price;

        $resp[$cnt]['psum'] =  $resp[$cnt]['pquant'] * $price;

        $resp[$cnt]['order'] = $order->fields['order_cnt'];
        $resp[$cnt]['shipping'] = $shipping->fields['shipping'];
        $resp[$cnt]['total'] = $ototal->fields['total'];
        $resp[$cnt]['taxes'] = $taxes->fields['taxes'];
        $resp[$cnt]['discounts'] = $discounts->fields['discounts'];
        $resp[$cnt]['vouchers'] = $vouchers->fields['vouchers'];

        // values per date and item
        $sumTot += $resp[$cnt]['psum'];
        $itemTot += $resp[$cnt]['pquant'];

        // add totsum and totitem until current row
        $resp[$cnt]['totsum'] = $sumTot;
        $resp[$cnt]['totitem'] = $itemTot;
        $cnt++;

        $rqItems->MoveNext();
      }

      return $resp;
    }
}
?>