<?php
/* --------------------------------------------------------------
   $Id: shipping_status.php,v 1.1 2004/02/20 20:54:02 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(orders_status.php,v 1.7 2002/01/30); www.oscommerce.com 
   (c) 2003	 nextcommerce (orders_status.php,v 1.4 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('HEADING_TITLE', '出貨狀況');
define('TABLE_HEADING_SHIPPING_STATUS_IMAGE', '圖示');
define('TABLE_HEADING_SHIPPING_STATUS', '出貨所需時程');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_INFO_EDIT_INTRO', '請做必要修改');
define('TEXT_INFO_SHIPPING_STATUS_NAME', '出貨時程:');
define('TEXT_INFO_INSERT_INTRO', '請輸入新的出貨時程');
define('TEXT_INFO_DELETE_INTRO', '確定刪除此出貨時程?');
define('TEXT_INFO_HEADING_NEW_SHIPPING_STATUS', '新的出貨時程');
define('TEXT_INFO_HEADING_EDIT_SHIPPING_STATUS', '編輯出貨時程');
define('TEXT_INFO_SHIPPING_STATUS_IMAGE', '圖片:');
define('TEXT_INFO_HEADING_DELETE_SHIPPING_STATUS', '刪除出貨時程');
define('TEXT_DISPLAY_NUMBER_OF_SHIPPING_STATUS', '出貨時程');
define('ERROR_REMOVE_DEFAULT_SHIPPING_STATUS', '錯誤: 預設的出貨時程無法刪除. 請設定另一出貨時程為預設後再試一次.');
define('ERROR_STATUS_USED_IN_ORDERS', '錯誤: 這個出貨時程仍有商品使用中.');
define('ERROR_STATUS_USED_IN_HISTORY', '錯誤: 這個出貨時程仍有商品使用中.');
?>