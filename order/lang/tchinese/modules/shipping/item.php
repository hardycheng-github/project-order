<?php
/* -----------------------------------------------------------------------------------------
   $Id: item.php,v 1.1 2003/12/19 13:19:08 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(item.php,v 1.6 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (item.php,v 1.4 2003/08/13); www.nextcommerce.org   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/

define('MODULE_SHIPPING_ITEM_TEXT_TITLE', '單件運費');
define('MODULE_SHIPPING_ITEM_TEXT_DESCRIPTION', '若有一件以上貨品，總費用= 單件運費 x 貨品總件數');
define('MODULE_SHIPPING_ITEM_TEXT_WAY', '最佳');

define('MODULE_SHIPPING_ITEM_STATUS_TITLE' , '使用單件運費');
define('MODULE_SHIPPING_ITEM_STATUS_DESC' , '要提供單件運費模組?');
define('MODULE_SHIPPING_ITEM_ALLOWED_TITLE' , '單件運費國家');
define('MODULE_SHIPPING_ITEM_ALLOWED_DESC' , '輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');
define('MODULE_SHIPPING_ITEM_COST_TITLE' , '單件運費金額');
define('MODULE_SHIPPING_ITEM_COST_DESC' , '總運費為訂單內商品件數X單件運費金額');
define('MODULE_SHIPPING_ITEM_HANDLING_TITLE' , '處理費');
define('MODULE_SHIPPING_ITEM_HANDLING_DESC' , '單件運費的處理費用');
define('MODULE_SHIPPING_ITEM_TAX_CLASS_TITLE' , '稅別');
define('MODULE_SHIPPING_ITEM_TAX_CLASS_DESC' , '使用單一運費時使用的稅別');
define('MODULE_SHIPPING_ITEM_ZONE_TITLE' , '單件運費地區');
define('MODULE_SHIPPING_ITEM_ZONE_DESC' , '如果選擇地區，則只有該地區可以使用這個結帳模組');
define('MODULE_SHIPPING_ITEM_SORT_ORDER_TITLE' , '顯示順序');
define('MODULE_SHIPPING_ITEM_SORT_ORDER_DESC' , '顯示時的順序');
?>
