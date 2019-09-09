<?php
/* --------------------------------------------------------------
   $Id: orders_status.php,v 1.1 2003/12/19 13:19:08 oldpa   Exp $   

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

define('HEADING_TITLE', '訂單狀態');

define('TABLE_HEADING_ORDERS_STATUS', '訂單狀態');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_INFO_EDIT_INTRO', '請做適當修改');
define('TEXT_INFO_ORDERS_STATUS_NAME', '訂單狀態：');
define('TEXT_INFO_INSERT_INTRO', '請填入新訂單狀態的相關資料');
define('TEXT_INFO_DELETE_INTRO', '確定要刪除這個訂單狀態？');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', '新增訂單狀態');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', '編輯訂單狀態');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', '刪除訂單狀態');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', '錯誤：預設的訂單狀態不可刪除，請先教將預設值設為其他訂單狀態後再試一次');
define('ERROR_STATUS_USED_IN_ORDERS', '錯誤：這個訂單目前正在使用');
define('ERROR_STATUS_USED_IN_HISTORY', '錯誤：這個訂單記錄目前正在使用');
?>
