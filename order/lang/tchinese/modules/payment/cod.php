<?php
/* -----------------------------------------------------------------------------------------
   $Id: cod.php,v 1.2 2004/04/01 14:19:25 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(cod.php,v 1.7 2002/04/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (cod.php,v 1.5 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
  define('MODULE_PAYMENT_TYPE_PERMISSION', '貨到付款');
  define('MODULE_PAYMENT_COD_TEXT_TITLE', '貨到付款');
  define('MODULE_PAYMENT_COD_TEXT_DESCRIPTION', '貨到付款');

  define('MODULE_PAYMENT_COD_ZONE_TITLE' , '貨到收款地區');
define('MODULE_PAYMENT_COD_ZONE_DESC' , '如果選擇地區，則只有該地區可以使用這個結帳模組');
define('MODULE_PAYMENT_COD_ALLOWED_TITLE' , '開放使用國家');
define('MODULE_PAYMENT_COD_ALLOWED_DESC' , '輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');
define('MODULE_PAYMENT_COD_STATUS_TITLE' , '啟動貨到收款模組');
define('MODULE_PAYMENT_COD_STATUS_DESC' , '確定要啟動貨到收款模組？');
define('MODULE_PAYMENT_COD_SORT_ORDER_TITLE' , '顯示順序');
define('MODULE_PAYMENT_COD_SORT_ORDER_DESC' , '顯示順序，數字越小順序在前.');
define('MODULE_PAYMENT_COD_ORDER_STATUS_ID_TITLE' , '貨到收款的訂單狀態');
define('MODULE_PAYMENT_COD_ORDER_STATUS_ID_DESC' , '設定使用貨到付款結帳模組的預設訂單狀態，如處理中、已處理或已出貨');
?>
