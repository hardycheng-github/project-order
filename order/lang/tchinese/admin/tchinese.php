<?php
/* --------------------------------------------------------------
   $Id: tchinese.php, 2004/08/01  oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(german.php,v 1.99 2003/05/28); www.oscommerce.com 
   (c) 2003	 nextcommerce (german.php,v 1.24 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contributions:
   Customers Status v3.x (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'zh_TW.utf-8');
define('DATE_FORMAT_SHORT', '%Y/%d/%m');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y');  // this is used for strftime()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('CHARSET', 'utf-8');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function twe_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="utf-8"');


// page title
define('TITLE',  STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_TOP', '系統管理');
define('HEADER_TITLE_SUPPORT_SITE', '支援網站');
define('HEADER_TITLE_ONLINE_CATALOG', '線上目錄');
define('HEADER_TITLE_ADMINISTRATION', '系統管理');
define('HEADER_TITLE_LOGOFF', '登出');

// text for gender
define('MALE', '男');
define('FEMALE', '女');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php

define('BOX_HEADING_CONFIGURATION', '系統設定');
define('BOX_HEADING_MODULES', '外掛模組');
define('BOX_HEADING_ZONE', '地區 / 稅別');
define('BOX_TAXES_ZONES', '地區');
define('BOX_HEADING_CUSTOMERS', '客戶/訂單');
define('BOX_HEADING_PRODUCTS','商品目錄');
define('BOX_HEADING_STATISTICS','各類報表');
define('BOX_HEADING_TOOLS','系統工具');
define('BOX_QUOTES_ORDERS','詢價管理');

define('BOX_CONTENT','內容管理員');
define('TEXT_ALLOWED', '權限');
define('TEXT_ACCESS', '使用範圍');
define('BOX_CONFIGURATION', '一般設定');
define('BOX_CONFIGURATION_1', '我的商店');
define('BOX_CONFIGURATION_2', '最小值設定');
define('BOX_CONFIGURATION_3', '最大值設定');
define('BOX_CONFIGURATION_4', '圖片設定');
define('BOX_CONFIGURATION_5', '會員註冊表單設定');
define('BOX_CONFIGURATION_6', '模組設定');
define('BOX_CONFIGURATION_7', '運送設定');
define('BOX_CONFIGURATION_8', '首頁商品列表設定');
define('BOX_CONFIGURATION_9', '庫存設定');
define('BOX_CONFIGURATION_10', '網頁載入設定');
define('BOX_CONFIGURATION_11', '快取設定');
define('BOX_CONFIGURATION_12', '郵件設定');
define('BOX_CONFIGURATION_13', '下載設定');
define('BOX_CONFIGURATION_14', 'Gzip壓縮設定');
define('BOX_CONFIGURATION_15', 'Sessions');
define('BOX_CONFIGURATION_16', '關鍵字/搜索引擎');
define('BOX_CONFIGURATION_17', '額外模組設定'); 
define('BOX_MODULES', '付款/運送/帳單-模組');
define('BOX_PAYMENT', '付款機制');
define('BOX_SHIPPING', '運送機制');
define('BOX_ORDER_TOTAL', '購物總額');
define('BOX_CATEGORIES', '目錄 / 商品');
define('BOX_PRODUCTS_ATTRIBUTES', '商品類別設定');
define('BOX_MANUFACTURERS', '廠商');
define('BOX_REVIEWS', '商品回應評註');
define('BOX_SPECIALS', '特價商品');
define('BOX_PRODUCTS_EXPECTED', '即將上市商品');
define('BOX_CUSTOMERS', '會員 / 客戶');
define('BOX_ACCOUNTING', '管理員權限');
define('BOX_CUSTOMERS_STATUS','會員群組');
define('BOX_ORDERS', '訂單');
define('BOX_COUNTRIES', '國別');
define('BOX_ZONES', '地區');
define('BOX_GEO_ZONES', '稅區');
define('BOX_TAX_CLASSES', '稅別');
define('BOX_TAX_RATES', '稅率');
define('BOX_HEADING_REPORTS', '各類報表');
define('BOX_PRODUCTS_VIEWED', '商品瀏覽排行');
define('BOX_STOCK_WARNING','庫存資訊');
define('BOX_PRODUCTS_PURCHASED', '商品銷售排行');
define('BOX_STATS_CUSTOMERS', '客戶購物排行榜');
define('BOX_BACKUP', '資料備份管理');
define('BOX_BANNER_MANAGER', '廣告管理');
define('BOX_CACHE', '快取控制');
define('BOX_DEFINE_LANGUAGE', '語言定義');
define('BOX_FILE_MANAGER', '檔案總管');
define('BOX_MAIL', '電子郵件');
define('BOX_NEWSLETTERS', '商品通知管理');
define('BOX_SERVER_INFO', '伺服器資訊');
define('BOX_WHOS_ONLINE', '線上客戶');
define('BOX_FILE_MANAGER', '檔案總管');
define('BOX_TPL_BOXES','區塊訂單排序');
define('BOX_CURRENCIES', '貨幣');
define('BOX_LANGUAGES', '語言');
define('BOX_ORDERS_STATUS', '訂單狀態');
define('BOX_ATTRIBUTES_MANAGER','商品屬性管理');
define('BOX_PRODUCTS_ATTRIBUTES','商品屬性群組設定');
define('BOX_MODULE_NEWSLETTER','電子報管理');
define('BOX_SHIPPING_STATUS','運送時程');
define('BOX_SALES_REPORT','銷售報表');
define('BOX_MODULE_EXPORT','輸出模組');
define('BOX_HEADING_GV_ADMIN', '禮券/折價券管理');
define('BOX_GV_ADMIN_QUEUE', '禮券銷售管理');
define('BOX_GV_ADMIN_MAIL', '郵寄禮券');
define('BOX_GV_ADMIN_SENT', '禮券郵寄管理');
define('BOX_COUPON_ADMIN','折價券管理');
define('BOX_TOOLS_BLACKLIST','-信用卡黑名單');
define('BOX_FILE_MANAGER','檔案總管');
define('BOX_XSELL_PRODUCTS','額外推薦商品');

define('TXT_GROUPS','<b>群組</b>:');
define('TXT_SYSTEM','系統');
define('TXT_CUSTOMERS','客戶/訂單');
define('TXT_PRODUCTS','商品/目錄');
define('TXT_STATISTICS','狀態');
define('TXT_TOOLS','工具');
define('TEXT_ACCOUNTING','管理:');

//Dividers text for menu

define('BOX_HEADING_MODULES', '模組');
define('BOX_HEADING_LOCALIZATION', '語言/貨幣');
define('BOX_HEADING_TEMPLATES','Templates');
define('BOX_HEADING_TOOLS', '工具');
define('BOX_HEADING_LOCATION_AND_TAXES', '地區 / 稅率');
define('BOX_CUSTOMERS', '客戶');
define('BOX_HEADING_CATALOG', '商品目錄');
define('BOX_MODULE_NEWSLETTER','電子報');

// javascript messages
define('JS_ERROR', 'Error have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product attribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product attribute needs a price prefix (+/-)\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'eMail-Adress\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Adress\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry must be selected.\n');
define('JS_STATE_SELECT', '-- Select above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', '訂單編號 %s 不存在!');

define('CATEGORY_PERSONAL', '個人資料');
define('CATEGORY_ADDRESS', '通訊地址');
define('CATEGORY_CONTACT', '電話/傳真');
define('CATEGORY_COMPANY', '公司資料');
define('CATEGORY_PASSWORD', '密碼');
define('CATEGORY_OPTIONS', '選項');

define('ENTRY_GENDER', '性別:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">必填</span>');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 個字</span>');
define('ENTRY_LAST_NAME', '暱稱:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 幾個字</span>');
define('ENTRY_LAST_NAME_ERROR_EXISTS', '&nbsp;<span class="errorText">這個暱稱已經存在！</span>');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(例如 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', '電子郵件:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 個字</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">這個電子郵件無效！</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">這個電子郵件已經存在！</span>');
define('ENTRY_COMPANY', '公司名稱:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', '街道門牌號碼:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 個字</span>');
define('ENTRY_SUBURB', '村里鄰:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', '郵遞區號:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 個字</span>');
define('ENTRY_CITY', '鄉鎮區:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_CITY_MIN_LENGTH . ' 個字</span>');
define('ENTRY_STATE', '縣市:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">必填</span>');
define('ENTRY_COUNTRY', '國家:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', '電話號碼:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">少於 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 個字</span>');
define('ENTRY_FAX_NUMBER', '傳真:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', '電子報:');
define('ENTRY_NEWSLETTER_YES', '訂電子報');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密碼:');
define('ENTRY_PASSWORD_CONFIRMATION', '再輸入一次密碼:');
define('PASSWORD_HIDDEN', '--隱藏--');

define('ENTRY_PASSWORD_ERROR','&nbsp;<span class="errorText">min. ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars</span>');
define('ENTRY_MAIL_COMMENTS','郵件內容:');
define('ENTRY_MAIL','電子郵件傳送密碼?');
define('ENTRY_CUSTOMERS_STATUS','客戶層級');
define('YES','是');
define('NO','否');
define('SAVE_ENTRY','確定儲存?');
define('TEXT_CHOOSE_INFO_TEMPLATE','商品明細');
define('TEXT_CHOOSE_OPTIONS_TEMPLATE','商品屬性列表');
define('TEXT_SELECT','-- 請選擇 --');

// images
define('IMAGE_ANI_SEND_EMAIL', '寄電子郵件中...');
define('IMAGE_BACK', '回上頁');
define('IMAGE_BACKUP', '備份');
define('IMAGE_CANCEL', '取消');
define('IMAGE_CONFIRM', '確認');
define('IMAGE_COPY', '複製');
define('IMAGE_COPY_TO', '複製到');
define('IMAGE_DEFINE', '定義');
define('IMAGE_DELETE', '刪除');
define('IMAGE_EDIT', '編輯');
define('IMAGE_EMAIL', 'Email 郵寄');
define('IMAGE_FILE_MANAGER', '檔案總管');
define('IMAGE_ICON_STATUS_GREEN', '已啟動');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', '按我啟動');
define('IMAGE_ICON_STATUS_RED', '未啟動');
define('IMAGE_ICON_STATUS_RED_LIGHT', '按我停止');
define('IMAGE_ICON_INFO', '資訊');
define('IMAGE_INSERT', '插入');
define('IMAGE_LOCK', '鎖定');
define('IMAGE_MODULE_INSTALL', '安裝模組');
define('IMAGE_MODULE_REMOVE', '移除模組');
define('IMAGE_MOVE', '移動');
define('IMAGE_NEW_BANNER', '新增廣告');
define('IMAGE_NEW_CATEGORY', '新增商品分類');
define('IMAGE_NEW_COUNTRY', '新增國別');
define('IMAGE_NEW_CURRENCY', '新增貨幣');
define('IMAGE_NEW_FILE', '新增檔案');
define('IMAGE_NEW_FOLDER', '新增資料夾');
define('IMAGE_NEW_LANGUAGE', '新增語系');
define('IMAGE_NEW_NEWSLETTER', '新增電子報');
define('IMAGE_NEW_PRODUCT', '新增商品');
define('IMAGE_NEW_TAX_CLASS', '新增稅別');
define('IMAGE_NEW_TAX_RATE', '新增稅率');
define('IMAGE_NEW_TAX_ZONE', '新增稅區');
define('IMAGE_NEW_ZONE', '新增地區');
define('IMAGE_ORDERS', '訂單');
define('IMAGE_ORDERS_INVOICE', '收據列印');
define('IMAGE_ORDERS_PACKINGSLIP', '分裝列印');
define('IMAGE_PREVIEW', '預覽');
define('IMAGE_RESTORE', '回復資料庫');
define('IMAGE_RESET', '重設');
define('IMAGE_SAVE', '儲存');
define('IMAGE_SEARCH', '搜尋');
define('IMAGE_SELECT', '選擇');
define('IMAGE_SEND', '寄出');
define('IMAGE_SEND_EMAIL', '寄 Email');
define('IMAGE_UNLOCK', '開鎖');
define('IMAGE_UPDATE', '更新');
define('IMAGE_UPDATE_CURRENCIES', '更新匯率');
define('IMAGE_UPLOAD', '上傳');
define('IMAGE_ACCOUNTING','管理權限');
define('IMAGE_STATUS','客戶狀態');
define('IMAGE_IPLOG','IP-Log');
define('CREATE_ACCOUNT','新增帳號');
define('IMAGE_ICON_LAYOUT_RED', '按我移動至此區塊');

define('ICON_CROSS', '否');
define('ICON_CURRENT_FOLDER', '目前資料夾');
define('ICON_DELETE', '刪除');
define('ICON_ERROR', '錯誤');
define('ICON_FILE', '檔案');
define('ICON_FILE_DOWNLOAD', '下載');
define('ICON_FOLDER', '資料夾');
define('ICON_LOCKED', '鎖住');
define('ICON_PREVIOUS_LEVEL', '預設值');
define('ICON_PREVIEW', '預覽');
define('ICON_STATISTICS', '統計資料');
define('ICON_SUCCESS', '完成');
define('ICON_TICK', '是');
define('ICON_UNLOCKED', '解開');
define('ICON_WARNING', '警告');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', ' %s / %d 頁');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個廣告)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個國別)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個客戶)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個貨幣)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個語系)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個製造廠商)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 份電子報)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 筆訂單)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個訂單狀態)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個商品)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個商品上市預告)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個商品評論)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個特價商品)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個稅別)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個稅區)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個稅率)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', '顯示 <b>%d</b> 到 <b>%d</b> (共 <b>%d</b> 個地區)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', '預設');
define('TEXT_SET_DEFAULT', '設定為預設');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* 必填</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', '錯誤：沒有預設的貨幣，請到\'系統工具/本地化設定/貨幣\' 設定');

define('TEXT_CACHE_CATEGORIES', '類別區');
define('TEXT_CACHE_MANUFACTURERS', '廠商區');
define('TEXT_CACHE_ALSO_PURCHASED', '推薦商品模組');

define('TEXT_NONE', '--無--');
define('TEXT_TOP', '頂端');

define('ERROR_DESTINATION_DOES_NOT_EXIST', '錯誤: 目的地不存在');
define('ERROR_DESTINATION_NOT_WRITEABLE', '錯誤: 目的地無法寫入');
define('ERROR_FILE_NOT_SAVED', '錯誤: 上傳檔案無法儲存');
define('ERROR_FILETYPE_NOT_ALLOWED', '錯誤r: 不允許檔案上傳');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', '完成: 檔案上傳完成');
define('WARNING_NO_FILE_UPLOADED', '注意: 沒有檔案上傳');
define('WARNING_FILE_UPLOADS_DISABLED', '警告:  php.ini 設定檔無開啟檔案上傳');

define('DELETE_ENTRY','刪除輸入?');
define('TEXT_PAYMENT_ERROR','<b>注意:</b><br>請選擇至少一種付款方式!');
define('TEXT_SHIPPING_ERROR','<b>注意:</b><br>請選擇至少一種運送方式!');

define('TEXT_NETTO','不含稅: ');

define('ENTRY_CID','客戶ID:');
define('IP','訂單 IP:');
define('CUSTOMERS_MEMO','備註:');
define('DISPLAY_MEMOS','顯示/內容');
define('TITLE_MEMO','客戶備註');
define('ENTRY_LANGUAGE','語言:');
define('CATEGORIE_NOT_FOUND','搜尋商品目錄不存在!');

define('IMAGE_RELEASE', '兌換禮券');

define('_JANUARY', '一月');
define('_FEBRUARY', '二月');
define('_MARCH', '三月');
define('_APRIL', '四月');
define('_MAY', '五月');
define('_JUNE', '六月');
define('_JULY', '七月');
define('_AUGUST', '八月');
define('_SEPTEMBER', '九月');
define('_OCTOBER', '十月');
define('_NOVEMBER', '十一月');
define('_DECEMBER', '十二月');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', '顯示 <b>%d</b> 到 <b>%d</b> (of <b>%d</b> 禮券)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', '顯示 <b>%d</b> 到 <b>%d</b> (of <b>%d</b> 優待券)');

define('TEXT_VALID_PRODUCTS_LIST', '商品列表');
define('TEXT_VALID_PRODUCTS_ID', '商品 ID');
define('TEXT_VALID_PRODUCTS_NAME', '商品名稱');
define('TEXT_VALID_PRODUCTS_MODEL', '商品型號');

define('TEXT_VALID_CATEGORIES_LIST', '商品目錄列表');
define('TEXT_VALID_CATEGORIES_ID', '商品目錄 ID');
define('TEXT_VALID_CATEGORIES_NAME', '商品目錄名稱');

define('SECURITY_CODE_LENGTH_TITLE', '禮券兌換密碼長度');
define('SECURITY_CODE_LENGTH_DESC', '輸入禮券兌換密碼字數 (最多. 16 字元)');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', '新加入會員贈送禮券額度');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', '新加入會員贈送禮券額度:如果您不希望在新增會員時郵件贈送禮券, 輸入 0 為沒有額度, 否則請輸入, 例 10.00 或 50.00, 不需貨幣標示');
define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', '新加入會員贈送折扣禮券碼');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', '新加入會員贈送折扣禮券碼: 如果您不希望在新增會員時郵件贈送折扣額度 ,
請留空白, 否則請填入您希望使用的禮券兌換碼');

define('TXT_ALL','All');
define('HEADER_TITLE_EDIT_ATTRIBUTES', '商品屬性管理');
define('HEADER_TITLE_PRODUCT_ATTRIBUTES_UPDATED', '商品屬性更新');
define('TEXT_PRODUCT_ATTRIBUTES_SELECT', '請選擇商品編輯:');
define('TEXT_PRODUCT_ATTRIBUTES_SELECT_COPY', '請選擇欲複製屬性商品:');
define('TEXT_NO_PRODUCT_ATTRIBUTES_COPY', '沒有商品屬性複製:');
define('TEXT_NO_PRODUCT_ATTRIBUTES', '目前沒有商品');
define('TEXT_DELETE', '刪除圖片');
define('TEXT_SORT_ORDER','排序');
define('TEXT_ATTRIBUTE_MODEL','選項說明');
define('TEXT_STOCK','存量');
define('TEXT_VALUE_WEIGHT','重量');
define('TEXT_WEIGHT_PREFIX','重量前置');
define('TEXT_VALUE_PRICE','價格');
define('MODULE_PAYMENT_MONEYORDER_PAYTO' , '付款取貨地點');

define('TEXT_PRICE_PREFIX','價格前置');
define('TEXT_NO_COPY','不複製');
define('BOX_NEWS_CATEGORIES', '最新消息/新聞');
define('SUCCESS_LAST_MOD', '更新完成');
define('BOX_QUOTES_ORDERS', '報價單');
define('TABLE_HEADING_PRODUCTS_IMAGE', '圖片');
define('TEXT_LOGIN_ERROR', '密碼不符!');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '電子郵件不符!');
define('BOX_LAYOUT_CONTROLLER','區塊配置'); 
?>