<?php
/*
  $Id: stats_sales_report.php,v 1.2 2004/04/01 14:19:25 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('CSV_FILENAME_PREFIX', '_SalesReport_');
define('CSV_HEADING_START_DATE', '開始日期');
define('CSV_HEADING_END_DATE', '結束日期');

define('PRINT_SEARCH_HEADER', 'Search Criteria');
define('PRINT_SORT_HEADER', 'Sort Criteria');

define('REPORT_DATE_FORMAT', 'm/d/Y');

define('HEADING_TITLE', '銷售報表');

define('REPORT_TYPE_YEARLY', '年');
define('REPORT_TYPE_MONTHLY', '月');
define('REPORT_TYPE_WEEKLY', '星期');
define('REPORT_TYPE_DAILY', '日');
define('REPORT_START_DATE', '開始日期');
define('REPORT_END_DATE', '截止日期');
define('REPORT_DETAIL', '細節');
define('REPORT_MAX', '頂端');
define('REPORT_ALL', '全部');
define('REPORT_SORT', '排序');
define('REPORT_EXP', '輸出');
define('REPORT_SEND', '傳送');
define('EXP_NORMAL', '一般格式');
define('EXP_HTML', '使用HTML格式');
define('EXP_CSV', 'CSV格式');
define('REPORT_PAYMENT_FILTER', '付款方式');
define('REPORT_STATUS_FILTER', '訂單狀態');

define('TABLE_HEADING_DATE', '日期');
define('TABLE_HEADING_ORDERS', '訂單張數');
define('TABLE_HEADING_ITEMS', '商品件數');
define('TABLE_HEADING_REVENUE', '收入');
define('TABLE_HEADING_SHIPPING', '運費');
define('TABLE_HEADING_TAX', '稅');
define('TABLE_HEADING_DISCOUNTS', '折扣');
define('TABLE_HEADING_VOUCHERS', '禮券折抵');
define('TABLE_HEADING_TOTAL', '總計');
define('TABLE_VALUE_ORDER_TOTAL', '訂單張數:');
define('TABLE_HEADING_DESCRIPTION', '說明');
define('TABLE_HEADING_MODEL', '商品型號.');
define('TABLE_HEADING_PRICE', '貨款金額');

define('DET_HEAD_ONLY', '不顯示細節');
define('DET_DETAIL', '顯示細節');
define('DET_DETAIL_ONLY', '合計細節');
define('REPORT_DATE_FIELD', '搜尋排序由');
define('REPORT_DATE_PURCHASED', '購買日期');
define('REPORT_LAST_MOD', '最後修改');

define('SORT_VAL0', '預設');
define('SORT_VAL1', '說明 Z-A');
define('SORT_VAL2', '說明 A-Z');
define('SORT_VAL3', '# Items 1-9');
define('SORT_VAL4', '# Items 9-1');
define('SORT_VAL5', '價格 低-高');
define('SORT_VAL6', '價格 高-低');
define('SORT_VAL7', '商品型號.');

define('REPORT_STATUS_FILTER', '狀態');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '<br>');
?>
