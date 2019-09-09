<?php
/*
  $Id: bank.php,v 1.1 2004/07/19 08:27:22 oldpa Exp $

  TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(german.php,v 1.119 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (german.php,v 1.25 2003/08/25); www.nextcommerce.org
   (c) 2003	 xtcommerce  www.xt-commerce.com   
   Released under the GNU General Public License 

*/
  define('MODULE_PAYMENT_BANK_TEXT_TITLE', '銀行匯款');
  define('MODULE_PAYMENT_BANK_TEXT_DESCRIPTION', '銀行匯款轉帳');
  define('MODULE_PAYMENT_BANK_TEXT_DESCRIPTION1', '請就近到銀行將所購買貨品款項匯入<br><br>銀行名稱：' . MODULE_PAYMENT_BANK_NAME . '<br>帳戶名稱：' . MODULE_PAYMENT_BANK_ACCNAME . '<br>帳戶號碼：' . MODULE_PAYMENT_BANK_ACCNUM . '<br><br>我們會於收到貨款時立刻與您聯絡並出貨。<br>注意：請在匯款單上註明您的訂單編號，以便核對，謝謝您的惠顧。');
  define('MODULE_PAYMENT_BANK_TEXT_EMAIL_FOOTER', "請就近到銀行將所購買貨品款項匯入\n\n銀行名稱：" . MODULE_PAYMENT_BANK_NAME . "\n帳戶名稱：" . MODULE_PAYMENT_BANK_ACCNAME . "\n帳戶號碼：" . MODULE_PAYMENT_BANK_ACCNUM . "\n\n我們會於收到貨款時立刻與您聯絡並出貨。\n注意：請在匯款單上註明您的訂單編號，以便核對，謝謝您的惠顧。");
  
  define('MODULE_PAYMENT_BANK_STATUS_TITLE', '啟動銀行轉帳模組');  
  define('MODULE_PAYMENT_BANK_STATUS_DESC', '確定要啟動銀行轉帳？');  
  define('MODULE_PAYMENT_BANK_ALLOWED_TITLE','銀行轉帳國家'); 
  define('MODULE_PAYMENT_BANK_ALLOWED_DESC','輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');  
  define('MODULE_PAYMENT_BANK_NAME_TITLE', '銀行名稱');
  define('MODULE_PAYMENT_BANK_NAME_DESC', '請輸入你的銀行名稱');
  define('MODULE_PAYMENT_BANK_ACCNAME_TITLE', '帳戶名稱');
  define('MODULE_PAYMENT_BANK_ACCNAME_DESC', '請輸入你的銀行帳戶名稱');
  define('MODULE_PAYMENT_BANK_ACCNUM_TITLE', '帳戶號碼'); 
  define('MODULE_PAYMENT_BANK_ACCNUM_DESC', '請輸入你的銀行帳戶號碼');
  define('MODULE_PAYMENT_BANK_SORT_ORDER_TITLE', '顯示順序');  
  define('MODULE_PAYMENT_BANK_SORT_ORDER_DESC', '顯示順序，數字越小順序在前');
  define('MODULE_PAYMENT_BANK_ZONE_TITLE', '銀行轉帳地區');  
  define('MODULE_PAYMENT_BANK_ZONE_DESC', '如果選擇地區，則只有該地區可以使用這個結帳模組');
  define('MODULE_PAYMENT_BANK_ORDER_STATUS_ID_TITLE', '銀行轉帳的訂單狀態');  
  define('MODULE_PAYMENT_BANK_ORDER_STATUS_ID_DESC', '設定使用貨到付款結帳模組的預設訂單狀態，如處理中、已處理或已出貨');
?>
