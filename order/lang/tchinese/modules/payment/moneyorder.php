<?php
/* -----------------------------------------------------------------------------------------
   $Id: moneyorder.php,v 1.1 2003/12/19 13:19:08 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(moneyorder.php,v 1.8 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (moneyorder.php,v 1.4 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', '到店付款');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', '非常歡迎您親臨' . MODULE_PAYMENT_MONEYORDER_PAYTO . '付款取貨<br>我們的服務據點:<br><br>' . nl2br(STORE_NAME_ADDRESS) . '<br><br>' . '在接到您的付款前,您所訂購商品將不會寄送出去!');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "非常歡迎您親臨 ". MODULE_PAYMENT_MONEYORDER_PAYTO . "付款取貨\n\n我們的服務據點:\n" . STORE_NAME_ADDRESS . "\n\n" . '在接到您的付款前,您所訂購商品將不會寄送出去');

  define('MODULE_PAYMENT_MONEYORDER_STATUS_TITLE' , '啟動自取付款模組');
define('MODULE_PAYMENT_MONEYORDER_STATUS_DESC' , '確定要啟動自取付款模組？');
define('MODULE_PAYMENT_MONEYORDER_ALLOWED_TITLE' , '自取付款國家');
define('MODULE_PAYMENT_MONEYORDER_ALLOWED_DESC' , '輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');
define('MODULE_PAYMENT_MONEYORDER_PAYTO_TITLE' , '付款取貨地點名稱:');
define('MODULE_PAYMENT_MONEYORDER_PAYTO_DESC' , '付款取貨地點名稱');
define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_TITLE' , '顯示順序');
define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_DESC' , '顯示順序，數字越小順序在前.');
define('MODULE_PAYMENT_MONEYORDER_ZONE_TITLE' , '自取付款地區');
define('MODULE_PAYMENT_MONEYORDER_ZONE_DESC' , '如果選擇地區，則只有該地區可以使用這個結帳模組.');
define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_TITLE' , '自取付款的訂單狀態');
define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_DESC' , '設定使用貨到付款結帳模組的預設訂單狀態，如處理中、已處理或已出貨');
?>
