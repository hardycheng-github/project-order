<?php
/* -----------------------------------------------------------------------------------------
   $Id: freeamount.php,v 1.2 2004/04/01 14:19:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce( freeamount.php,v 1.01 2002/01/24 03:25:00); www.oscommerce.com 
   (c) 2003	 nextcommerce (freeamount.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   freeamountv2-p1         	Autor:	dwk

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('MODULE_SHIPPING_FREECOUNT_TEXT_TITLE', '購物滿額免運費');
define('MODULE_SHIPPING_FREECOUNT_TEXT_DESCRIPTION', '購物滿額免運費');
define('MODULE_SHIPPING_FREECOUNT_TEXT_WAY', '您的訂單金額必須超過' . MODULE_SHIPPING_FREECOUNT_AMOUNT . ' 才能免運費');
define('MODULE_SHIPPING_FREECOUNT_SORT_ORDER', '排序');

define('MODULE_SHIPPING_FREEAMOUNT_ALLOWED_TITLE' , '購物滿額免運費國家');
define('MODULE_SHIPPING_FREEAMOUNT_ALLOWED_DESC' , '輸入國家代碼，則只有列出國家可以使用這個運送方式 (例如 AT,DE (留白表示不設限))');
define('MODULE_SHIPPING_FREECOUNT_STATUS_TITLE' , '開啟購物滿額免運費');
define('MODULE_SHIPPING_FREECOUNT_STATUS_DESC' , '提供購物滿額免運費?');
define('MODULE_SHIPPING_FREECOUNT_DISPLAY_TITLE' , '開啟訊息顯示');
define('MODULE_SHIPPING_FREECOUNT_DISPLAY_DESC' , '顯示當購物金額未到達指定免運費的最低訂單金額訊息?');
define('MODULE_SHIPPING_FREECOUNT_AMOUNT_TITLE' , '最低訂單金額');
define('MODULE_SHIPPING_FREECOUNT_AMOUNT_DESC' , '超過最低訂單金額免運費?');
define('MODULE_SHIPPING_FREECOUNT_SORT_ORDER_TITLE' , '顯示順序');
define('MODULE_SHIPPING_FREECOUNT_SORT_ORDER_DESC' , '顯示順序，數字越小順序在前.');
?>
