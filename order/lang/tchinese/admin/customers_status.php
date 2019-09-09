<?php
/* --------------------------------------------------------------
   $Id: customers_status.php,v 1.4 2004/04/01 14:19:25 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(customers.php,v 1.76 2003/05/04); www.oscommerce.com
   (c) 2003	 nextcommerce (customers_status.php,v 1.12 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License
   --------------------------------------------------------------*/

define('HEADING_TITLE', '會員群組');

define('ENTRY_CUSTOMERS_FSK18','啟用禁止未滿十八歲購買商品?');
define('ENTRY_CUSTOMERS_FSK18_DISPLAY','顯示未滿十八歲禁購的商品?');
define('ENTRY_CUSTOMERS_STATUS_ADD_TAX','訂單總金額列出稅額');
define('ENTRY_CUSTOMERS_STATUS_BT_PERMISSION','經由銀行轉帳Via Bank Collection');
define('ENTRY_CUSTOMERS_STATUS_CC_PERMISSION','經由信用卡支付Via Credit Card');
define('ENTRY_CUSTOMERS_STATUS_COD_PERMISSION','現金交易Via Cash on Delivery');
define('ENTRY_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES','折扣');
define('ENTRY_CUSTOMERS_STATUS_PAYMENT_UNALLOWED','輸入不能使用之付款方式');
define('ENTRY_CUSTOMERS_STATUS_PUBLIC','公開');
define('ENTRY_CUSTOMERS_STATUS_SHIPPING_UNALLOWED','輸入不能使用之出貨方式');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE','顯示價格');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX','價格含稅');
define('ENTRY_GRADUATED_PRICES','分級價格');
define('ENTRY_NO','否');
define('ENTRY_OT_XMEMBER', '購物總金額折扣 ? :');
define('ENTRY_YES','是');

define('ERROR_REMOVE_DEFAULT_CUSTOMER_STATUS', '注意: 無法刪除系統預設群組. 請先設定其他會員群組為預設後再嘗試一次.');
define('ERROR_REMOVE_DEFAULT_CUSTOMERS_STATUS','注意! 無法刪除系統預設群組');
define('ERROR_STATUS_USED_IN_CUSTOMERS', '注意: 這個會員群組下依舊有使用者運用.');
define('ERROR_STATUS_USED_IN_HISTORY', '注意: 這個會員群組下依舊有使用中的訂單紀錄.');

define('YES','是');
define('NO','否');

define('TABLE_HEADING_ACTION','動作');
define('TABLE_HEADING_CUSTOMERS_GRADUATED','分級價格');
define('TABLE_HEADING_CUSTOMERS_STATUS','會員群組');
define('TABLE_HEADING_CUSTOMERS_UNALLOW','禁止的付款方式');
define('TABLE_HEADING_CUSTOMERS_UNALLOW_SHIPPING','禁止的出貨方式');
define('TABLE_HEADING_DISCOUNT','折扣');
define('TABLE_HEADING_TAX_PRICE','價格 / 稅');

define('TAX_NO','未含稅');
define('TAX_YES','含稅');

define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', '目前會員群組:');

define('TEXT_INFO_CUSTOMERS_FSK18_DISPLAY_INTRO','<b>顯示未滿十八歲購買之商品</b>');
define('TEXT_INFO_CUSTOMERS_FSK18_INTRO','<b>禁止未滿十八歲購買商品</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_ADD_TAX_INTRO','<b>如果價格含稅設定為"否"</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_BT_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay via bank collection?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_CC_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay with credit cards?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_COD_PERMISSION_INTRO', '<b>Shall we allow customers of this group to pay COD?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_ATTRIBUTES_INTRO','<b>套用商品屬性折扣:</b><br>(Max. % 單一商品折扣)');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO','<b>購物總金額折扣:</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE', '折扣率 (0 至 100%):');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE_INTRO', '<b>定義一個折扣率:</b><br>在 0 與 100% 之間.這個折扣率將套用在商品屬性');
define('TEXT_INFO_CUSTOMERS_STATUS_GRADUATED_PRICES_INTRO','<b>分級價格</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE', '群組圖片:');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME','<b>群組名稱:</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_UNALLOWED_INTRO','<b>不允許的付款方式:</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO','<b>公開顯示折扣率?</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_SHIPPING_UNALLOWED_INTRO','<b>不允許的出貨方式:</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO','<b>商店顯示商品價格:</b>');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO', '<b>顯示價格含稅?</b>');

define('TEXT_INFO_DELETE_INTRO', '確定刪除此群組?');
define('TEXT_INFO_EDIT_INTRO', '請作必要之更改');
define('TEXT_INFO_INSERT_INTRO', '新增一個客戶群組並填入下列資訊.');

define('TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS', '刪除群組');
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS','編輯群組');
define('TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS', '新增群組');
?>