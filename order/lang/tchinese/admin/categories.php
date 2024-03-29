<?php
/* --------------------------------------------------------------
   $Id: categories.php,v 1.7 2004/04/15 18:22:16 oldpa Exp $

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.22 2002/08/17); www.oscommerce.com
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/14); www.nextcommerce.org   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/
define('TEXT_EDIT_STATUS', '狀態');
define('HEADING_TITLE', '目錄/商品');
define('HEADING_TITLE_SEARCH', '搜尋：');
define('HEADING_TITLE_GOTO', '前往：');

define('TABLE_HEADING_ID', '編號');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', '目錄 / 商品');
define('TABLE_HEADING_ACTION', '動作');
define('TABLE_HEADING_STATUS', '狀態');
define('TABLE_HEADING_STOCK','庫存狀態');

define('TEXT_WARN_MAIN','Main');
define('TEXT_WARN_ATTRIBUTE','商品屬性');
define('TEXT_NEW_PRODUCT', '新商品在 &quot;%s&quot;');
define('TEXT_CATEGORIES', '目錄：');
define('TEXT_SUBCATEGORIES', '子目錄：');
define('TEXT_PRODUCTS', '商品：');
define('TEXT_PRODUCTS_PRICE_INFO', '價格：');
define('TEXT_PRODUCTS_TAX_CLASS', '稅別：');
define('TEXT_PRODUCTS_AVERAGE_RATING', '平均評等：');
define('TEXT_PRODUCTS_QUANTITY_INFO', '數量：');
define('TEXT_DATE_ADDED', '新增日期：');
define('TEXT_DATE_AVAILABLE', '上架日期：');
define('TEXT_LAST_MODIFIED', '最後修改日期：');
define('TEXT_IMAGE_NONEXISTENT', '沒有圖片');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', '請新增一個目錄或商品<br>&nbsp;<br><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', '更多資訊息,請到商品 <a href="http://%s" target="blank"><u>網頁</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', '本商品加入目錄日期： %s');
define('TEXT_PRODUCT_DATE_AVAILABLE', '本商品預定入庫日期： %s');
define('TEXT_CHOOSE_INFO_TEMPLATE', '商品資訊樣式Product-Info Template:');
define('TEXT_CHOOSE_OPTIONS_TEMPLATE', '商品選項樣式Product-Optionen Template:');
define('TEXT_SELECT', '請選擇:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED_INFO', '折扣設定:');
define('TEXT_EDIT_INTRO', '請做適當修改');
define('TEXT_EDIT_CATEGORIES_ID', '目錄編號：');
define('TEXT_EDIT_CATEGORIES_NAME', '目錄名稱：');
define('TEXT_EDIT_CATEGORIES_NAME_SIZE', '目錄字體大小：');
define('TEXT_EDIT_CATEGORIES_IMAGE', '目錄圖片：');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', '目錄說明:');
define('TEXT_EDIT_SORT_ORDER', '排序');

define('TEXT_INFO_COPY_TO_INTRO', '請選擇一個您想將本商品複製到那裡的目錄');
define('TEXT_INFO_CURRENT_CATEGORIES', '現在目錄：');

define('TEXT_INFO_HEADING_NEW_CATEGORY', '新增目錄');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', '編輯目錄');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', '刪除目錄');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', '搬移目錄');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', '刪除商品');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', '新增商品');
define('TEXT_INFO_HEADING_COPY_TO', '複製到');

define('TEXT_DELETE_CATEGORY_INTRO', '確定要刪除這個目錄？');
define('TEXT_DELETE_PRODUCT_INTRO', '確定要永久刪除這個商品？');

define('TEXT_DELETE_WARNING_CHILDS', '<b>注意：</b> 這個目錄內尚有 %s 個子目錄！');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>注意：</b> 這個目錄內尚有 %s 個商品！');

define('TEXT_MOVE_PRODUCTS_INTRO', '請選擇一個要放 <b>%s</b> 的商品分類');
define('TEXT_MOVE_CATEGORIES_INTRO', '請選擇一個要放 <b>%s</b> 的商品分類');
define('TEXT_MOVE', '搬移<b>%s</b>到：');

define('TEXT_NEW_CATEGORY_INTRO', '<br>請填寫下列新目錄資料');
define('TEXT_CATEGORIES_NAME', '目錄名稱：');
define('TEXT_CATEGORIES_IMAGE', '目錄圖片：');

define('TEXT_META_TITLE', 'Meta 標頭:');
define('TEXT_META_DESCRIPTION', 'Meta 說明:');
define('TEXT_META_KEYWORDS', 'Meta 關鍵字:');

define('TEXT_SORT_ORDER', '排序:');

define('TEXT_PRODUCTS_STATUS', '產品狀態：');
define('TEXT_PRODUCTS_DATE_AVAILABLE', '上架日期：');
define('TEXT_PRODUCT_AVAILABLE', '現貨');
define('TEXT_PRODUCT_NOT_AVAILABLE', '缺貨');
define('TEXT_PRODUCTS_MANUFACTURER', '商品廠商：');
define('TEXT_PRODUCTS_NAME', '品名：');
define('TEXT_PRODUCTS_DESCRIPTION', '商品說明：');
define('TEXT_PRODUCTS_QUANTITY', '數量：');
define('TEXT_PRODUCTS_MODEL', '型號：');
define('TEXT_PRODUCTS_IMAGE', '商品圖片：');
define('TEXT_PRODUCTS_MEDIA_IMAGE', '商品影片：');
define('TEXT_PRODUCTS_URL', '商品網址：');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(不需打 http://)</small>');
define('TEXT_PRODUCTS_PRICE', '商品價格:');
define('TEXT_PRODUCTS_WEIGHT', '重量：');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', '摘要：');
define('EMPTY_CATEGORY', '空目錄');

define('TEXT_HOW_TO_COPY', '複製方式：');
define('TEXT_COPY_AS_LINK', '商品連結');
define('TEXT_COPY_AS_DUPLICATE', '商品複製');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', '錯誤：您無法在同一個目錄內作商品連結 ！');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', '錯誤：目錄圖片資料夾無法寫入(權限)：' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', '錯誤：目錄圖片的資料夾不存在： ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', '錯誤: 資料夾無法移動到子資料夾內');
define('ENTRY_CUSTOMERS_STATUS', '選擇可以檢視之客戶群組');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED','折扣:');
define('HEADING_PRICES_OPTIONS','<b>價格設定</b>');
define('HEADING_PRODUCT_OPTIONS','<b>商品設定</b>');
define('TEXT_PRODUCTS_WEIGHT_INFO','<small>(kg)</small>');
define('TEXT_PRODUCTS_SHORT_DESCRIPTION','簡短說明:');
define('TXT_STK','購買: ');
define('TXT_PRICE','每一個 :');
define('TXT_NETTO','最終價格: ');
define('TEXT_NETTO','淨價: ');
define('TXT_STAFFELPREIS','分級價格');

define('HEADING_PRODUCTS_MEDIA','<b>商品媒體</b>');
define('TABLE_HEADING_PRICE','價格');

define('TEXT_CHOOSE_INFO_TEMPLATE','商品說明樣式');
define('TEXT_SELECT','--請選擇--');
define('TEXT_CHOOSE_OPTIONS_TEMPLATE','商品屬性樣式');
define('SAVE_ENTRY','儲存 ?');

define('TEXT_FSK18','禁止18歲以下購買:');
define('TEXT_CHOOSE_INFO_TEMPLATE_CATEGORIE','目錄列表樣式');
define('TEXT_CHOOSE_INFO_TEMPLATE_LISTING','商品列表樣式');
define('TEXT_PRODUCTS_SORT','排序:');
define('TEXT_EDIT_PRODUCT_SORT_ORDER','商品排序');
define('TXT_PRICES','價格');
define('TXT_NAME','品名');
define('TXT_ORDERED','銷售量');
define('TXT_SORT','排序');
define('TXT_WEIGHT','重量');
define('TXT_QTY','庫存量');

define('TEXT_MULTICOPY','多層複製');
define('TEXT_MULTICOPY_DESC','複製/多層商品至商品目錄下 (如果有選擇, 單一複製設定將不於理會)');
define('TEXT_SINGLECOPY','單一複製');
define('TEXT_SINGLECOPY_DESC','複製/單一商品至商品目錄下');
define('TEXT_FEATURED','設為推薦商品');
define('TABLE_HEADING_FEATURED','推薦');
?>