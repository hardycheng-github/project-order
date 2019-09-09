<?php
/*
  $Id: stats_sales_report.php,v 1.2 2004/04/01 14:19:25 oldpa   Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('REPORT_DATE_FORMAT', 'm/d/Y');
define('CSV_FILENAME_PREFIX', '_SalesReport_');
define('CSV_HEADING_START_DATE', 'Start Date');
define('CSV_HEADING_END_DATE', 'End Date');

define('PRINT_SEARCH_HEADER', 'Search Criteria');
define('PRINT_SORT_HEADER', 'Sort Criteria');

define('HEADING_TITLE', 'Sales Report');

define('REPORT_TYPE_YEARLY', 'Yearly');
define('REPORT_TYPE_MONTHLY', 'Monthly');
define('REPORT_TYPE_WEEKLY', 'Weekly');
define('REPORT_TYPE_DAILY', 'Daily');
define('REPORT_START_DATE', 'Starting date');
define('REPORT_END_DATE', 'Ending date (inclusive)');
define('REPORT_DETAIL', 'Detail level');
define('REPORT_MAX', 'Max results');
define('REPORT_ALL', 'All');
define('REPORT_SORT', 'Sort results by');
define('REPORT_EXP', 'Display');
define('REPORT_SEND', 'Run');
define('REPORT_PAYMENT_FILTER', 'Payment method');
define('REPORT_STATUS_FILTER', 'Order status');

define('EXP_NORMAL', 'Normal');
define('EXP_HTML', 'Print Format');
define('EXP_CSV', 'CSV Export');

define('REPORT_DATE_FIELD', 'Search orders by');
define('REPORT_DATE_PURCHASED', 'Date of purchase');
define('REPORT_LAST_MOD', 'Date of last modification');

define('TABLE_HEADING_DATE', 'Date');
define('TABLE_HEADING_DESCRIPTION', 'Description');
define('TABLE_HEADING_MODEL', 'Model No.');
define('TABLE_HEADING_ORDERS', '# Orders');
define('TABLE_HEADING_ITEMS', '# Items');
define('TABLE_HEADING_PRICE', 'Price');
define('TABLE_HEADING_SHIPPING', 'Shipping');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_DISCOUNTS', 'Discounts');
define('TABLE_HEADING_VOUCHERS', 'Gift Certificates');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_VALUE_ORDER_TOTAL', 'Order Totals:');

define('DET_HEAD_ONLY', 'No details');
define('DET_DETAIL', 'Show products');
define('DET_DETAIL_ONLY', 'Show products & price');

define('SORT_VAL0', 'Default');
define('SORT_VAL1', 'Description Z-A');
define('SORT_VAL2', 'Description A-Z');
define('SORT_VAL3', '# Items 1-9');
define('SORT_VAL4', '# Items 9-1');
define('SORT_VAL5', 'Price Lo-Hi');
define('SORT_VAL6', 'Price Hi-Lo');
define('SORT_VAL7', 'Model No.');

define('SR_SEPARATOR1', ',');
define('SR_SEPARATOR2', ',');
define('SR_NEWLINE', "\n");

?>
