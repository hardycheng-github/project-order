<?php
/* -----------------------------------------------------------------------------------------
   $Id: orders_edit.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(database.php,v 1.19 2003/03/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_db_perform.inc.php,v 1.3 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('HEADING_TITLE', '編輯修改訂單');
define('HEADING_TITLE_SEARCH', '訂單編號:');
define('HEADING_TITLE_STATUS', '狀態:');
define('ADDING_TITLE', '新增商品至訂單');

define('ENTRY_UPDATE_TO_CC', '');
define('TABLE_HEADING_COMMENTS', '備註');
define('TABLE_HEADING_CUSTOMERS', '客戶名稱');
define('TABLE_HEADING_ORDER_TOTAL', '訂單總計');
define('TABLE_HEADING_DATE_PURCHASED', '訂購日期');
define('TABLE_HEADING_STATUS', '狀態');
define('TABLE_HEADING_ACTION', '動作');
define('TABLE_HEADING_QUANTITY', '數量');
define('TABLE_HEADING_PRODUCTS_MODEL', '貨號');
define('TABLE_HEADING_PRODUCTS', '商品');
define('TABLE_HEADING_TAX', '稅');
define('TABLE_HEADING_TOTAL', '總計');
define('TABLE_HEADING_UNIT_PRICE', '單價');
define('TABLE_HEADING_TOTAL_PRICE', '總價');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', '客戶通知');
define('TABLE_HEADING_DATE_ADDED', '新增日期');

define('ENTRY_CUSTOMER', '客戶:');
define('ENTRY_CUSTOMER_NAME', '客戶名稱');
define('ENTRY_CUSTOMER_COMPANY', '公司');
define('ENTRY_CUSTOMER_ADDRESS', '地址');
define('ENTRY_CUSTOMER_SUBURB', '鄰里');
define('ENTRY_CUSTOMER_CITY', '鄉鎮區');
define('ENTRY_CUSTOMER_STATE', '縣市');
define('ENTRY_CUSTOMER_POSTCODE', '郵遞區號');
define('ENTRY_CUSTOMER_COUNTRY', '國家');

define('ENTRY_SOLD_TO', 'SOLD TO:');
define('ENTRY_DELIVERY_TO', 'Delivery To:');
define('ENTRY_SHIP_TO', 'SHIP TO:');
define('ENTRY_SHIPPING_ADDRESS', '送貨地址:');
define('ENTRY_BILLING_ADDRESS', '帳單地址:');
define('ENTRY_PAYMENT_METHOD', '付款方式:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credit Card Type:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credit Card Owner:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credit Card Number:');
define('ENTRY_CREDIT_CARD_CVV', 'Credit Card CVV Number:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credit Card Expires:');
define('ENTRY_SUB_TOTAL', '小計:');
define('ENTRY_TAX', '稅:');
define('ENTRY_SHIPPING', 'Shipping:');
define('ENTRY_TOTAL', '總計:');
define('ENTRY_DATE_PURCHASED', '訂購日期:');
define('ENTRY_STATUS', '狀態:');
define('ENTRY_DATE_LAST_UPDATED', '最後更新日期:');
define('ENTRY_NOTIFY_CUSTOMER', '通知客戶:');
define('ENTRY_NOTIFY_COMMENTS', 'Append Comments:');
define('ENTRY_PRINTABLE', '列印帳單');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Delete Order');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Restock product quantity');
define('TEXT_DATE_ORDER_CREATED', '更改日期:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', '最後編輯:');
define('TEXT_DATE_ORDER_ADDNEW', '新增商品.');
define('TEXT_INFO_PAYMENT_METHOD', '付款方式:');

define('TEXT_ALL_ORDERS', 'All Orders');
define('TEXT_NO_ORDER_HISTORY', 'No Order History Available');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Order Update');
define('EMAIL_TEXT_ORDER_NUMBER', 'Order Number:');
define('EMAIL_TEXT_INVOICE_URL', 'Detailed Invoice:');
define('EMAIL_TEXT_DATE_ORDERED', 'Date Ordered:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Your order has been updated to the following status.' . "\n\n" . 'New status: %s' . "\n\n" . 'Please reply to this email if you have any questions.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'The comments for your order are' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', '錯誤: 訂單不存在.');
define('SUCCESS_ORDER_UPDATED', '完成: 訂單更新完成.');
define('WARNING_ORDER_NOT_UPDATED', '注意: 沒有更新. 這個訂單沒有更動.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', '選擇商品');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', '選擇項目');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', '沒有選項..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', '數量.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', '立即新增');


?>