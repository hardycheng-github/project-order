<?php
/* -----------------------------------------------------------------------------------------
   $Id: table.php,v 1.2 2004/04/01 14:19:25 oldpa Exp $   

   TWE-Commerce - community made shopping   
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(table.php,v 1.6 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (table.php,v 1.4 2003/08/13); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

define('MODULE_SHIPPING_TABLE_TEXT_TITLE', '收費表');
define('MODULE_SHIPPING_TABLE_TEXT_DESCRIPTION', '收費表運費模組，依照商品重量或金額多寡按表收費');
define('MODULE_SHIPPING_TABLE_TEXT_WAY', '最佳');
define('MODULE_SHIPPING_TABLE_TEXT_WEIGHT', '重量');
define('MODULE_SHIPPING_TABLE_TEXT_AMOUNT', '金額');

define('MODULE_SHIPPING_TABLE_STATUS_TITLE' , '啟動運費表');
define('MODULE_SHIPPING_TABLE_STATUS_DESC' , '確定要啟動運費表?');
define('MODULE_SHIPPING_TABLE_ALLOWED_TITLE' , '運送國家');
define('MODULE_SHIPPING_TABLE_ALLOWED_DESC' , '輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');
define('MODULE_SHIPPING_TABLE_COST_TITLE' , '運費表');
define('MODULE_SHIPPING_TABLE_COST_DESC' , '運費表係依照銷售物品的金額或重量按表計算運費，例如：25:8.50,50:5.50，表示達 25 時收費 8.50，26 到 50 者收費 5.50');
define('MODULE_SHIPPING_TABLE_MODE_TITLE' , '計算方式');
define('MODULE_SHIPPING_TABLE_MODE_DESC' , '運費係依照銷售物品的總計金額或總重量按表計算運費');
define('MODULE_SHIPPING_TABLE_HANDLING_TITLE' , '處理費');
define('MODULE_SHIPPING_TABLE_HANDLING_DESC' , '運送計算方式處理費');
define('MODULE_SHIPPING_TABLE_TAX_CLASS_TITLE' , '稅別');
define('MODULE_SHIPPING_TABLE_TAX_CLASS_DESC' , '選擇運送稅別');
define('MODULE_SHIPPING_TABLE_ZONE_TITLE' , '運送地區');
define('MODULE_SHIPPING_TABLE_ZONE_DESC' , '如果選擇地區，則只有該地區可以使用這個運送模組');
define('MODULE_SHIPPING_TABLE_SORT_ORDER_TITLE' , '顯示順序');
define('MODULE_SHIPPING_TABLE_SORT_ORDER_DESC' , '顯示順序，數字越小順序在前');
?>
