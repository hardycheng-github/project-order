<?php
/* -----------------------------------------------------------------------------------------
   $Id: tchinese.php,v 1.6 2004/04/01 14:19:25 oldpa Exp $   

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
---------------------------------------------------------------------------------------*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat try 'de_DE'
// on FreeBSD try 'de_DE.ISO_8859-15'
// on Windows try 'de' or 'German'
global $navigation;
@setlocale(LC_TIME, 'zh_TW.utf-8');
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%Y %B %d %A'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y');  // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

// page title
define('TITLE', STORE_NAME);
// charset for web pages and emails
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

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'TWD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="utf-8"');

define('HEADER_TITLE_TOP', '您現在的位置');
define('HEADER_TITLE_CATALOG', '首頁');

 // text for gender
define('MALE', '先生.');
define('FEMALE', '小姐.');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Mrs.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// text for quick purchase
define('IMAGE_BUTTON_ADD_QUICK', '快速購買!');
define('BOX_ADD_PRODUCT_ID_TEXT', '快速購買請輸入商品 ID.');

// text for gift voucher redeeming
define('IMAGE_REDEEM_GIFT','兌換禮券!');

define('BOX_TITLE_STATISTICS','商店狀態統計');
define('BOX_ENTRY_CUSTOMERS','客戶');
define('BOX_ENTRY_PRODUCTS','商品');
define('BOX_ENTRY_REVIEWS','商品評論');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_SEARCH_TEXT', '用關鍵字搜尋商品.');
define('BOX_SEARCH_ADVANCED_SEARCH', '進階搜尋');

// reviews box text in includes/boxes/reviews.php
define('BOX_REVIEWS_WRITE_REVIEW', '寫一寫評論!');
define('BOX_REVIEWS_NO_REVIEWS', '目前尚無評論');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s 最多 5 顆星!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_SHOPPING_CART_EMPTY', '0 商品');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_NOTIFICATIONS_NOTIFY', '當此商品更新時通知我 <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', '當此商品更新時不要通知我 <b>%s</b>');

// manufacturer box text
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s 首頁');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', '更多商品');

define('BOX_HEADING_ADD_PRODUCT_ID','放進購物籃!');
define('BOX_HEADING_SEARCH','搜尋!');

define('BOX_INFORMATION_CONTACT', '服務內容');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', '推薦給親友');
define('BOX_TELL_A_FRIEND_TEXT', '用電子郵件推薦此商品給親友.');

// pull down default text
define('PULL_DOWN_DEFAULT', '請選擇');
define('TYPE_BELOW', '於下列輸入');

// javascript messages
define('JS_ERROR', '別急！別急！\n您的資料還沒填完喔！\n看看您漏掉哪些\n\n');

define('JS_REVIEW_TEXT', '* 您的評論最少要' . (REVIEW_TEXT_MIN_LENGTH/2) . '個字\n\n');
define('JS_REVIEW_RATING', '* 請給這個商品一個評等\n\n');
define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* 請選一個付款方式\n');
define('JS_ERROR_SUBMITTED', '這個表單已經送出，請按 Ok 後等待處理');
define('ERROR_NO_PAYMENT_MODULE_SELECTED', '您必須選一個付款方式.');
define('CATEGORY_COMPANY', '公司資訊');
define('CATEGORY_PERSONAL', '您的個人資訊');
define('CATEGORY_ADDRESS', '您的地址');
define('CATEGORY_CONTACT', '意見欄');
define('CATEGORY_OPTIONS', '選項');
define('CATEGORY_PASSWORD', '您的密碼');

define('ENTRY_COMPANY', '公司名稱:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', '性別:');
define('ENTRY_GENDER_ERROR', '請選擇性別.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', '中文姓名:');
define('ENTRY_FIRST_NAME_ERROR', '中文姓名不得少於 ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' 個字');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', '暱稱:');
define('ENTRY_LAST_NAME_ERROR', '暱稱不得少於 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 個字');
define('ENTRY_LAST_NAME_ERROR_EXISTS', '&nbsp;<span class="errorText">這個暱稱已經存在！</span>');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', '生日:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '(例：05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '*');
define('ENTRY_EMAIL_ADDRESS', '電子郵件:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '電子郵件不得少於 ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' 個字');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '電子郵件地址格式錯誤!');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '這個電子郵件已經註冊過!請確認或換一個電子郵件.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', '街道門牌號碼:');
define('ENTRY_STREET_ADDRESS_ERROR', '街道門牌號碼不得少於 ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' 個字');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', '村里鄰:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', '郵遞區號:');
define('ENTRY_POST_CODE_ERROR', '郵遞區號不得少於 ' . ENTRY_POSTCODE_MIN_LENGTH . ' 個字');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', '鄉/村鎮區:');
define('ENTRY_CITY_ERROR', '鄉/村鎮區不得少於 ' . ENTRY_CITY_MIN_LENGTH . ' 個字');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', '縣市:');
define('ENTRY_STATE_ERROR', '縣市最少必須 ' . ENTRY_STATE_MIN_LENGTH . '個字');
define('ENTRY_STATE_ERROR_SELECT', '請從下拉式選單中選取縣市');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', '國家:');
define('ENTRY_COUNTRY_ERROR', '請從下拉式選單中選取國別');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', '電話號碼:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '電話號碼不得少於 ' . ENTRY_TELEPHONE_MIN_LENGTH . ' 個字');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', '行動電話:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', '電子報:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', '-訂閱-');
define('ENTRY_NEWSLETTER_NO', '取消');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', '密碼:');
define('ENTRY_PASSWORD_ERROR', '密碼不得少於' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', '密碼不符');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', '再輸入一次密碼:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', '目前密碼:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', '密碼不得少於 ' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
define('ENTRY_PASSWORD_NEW', '新密碼:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', '新密碼不得少於' . ENTRY_PASSWORD_MIN_LENGTH . ' 個字');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', '密碼不符');
define('PASSWORD_HIDDEN', '--隱藏--');


// constants for use in twe_prev_next_display function
define('TEXT_RESULT_PAGE', '總頁數:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', '顯示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>個商品)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>筆訂單)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>個評論)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', '顯示 <b>%d</b> 到 第<b>%d</b> (共<b>%d</b>個新商品)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', '顯示 <b>%d</b> 到 第 <b>%d</b> (共 <b>%d</b> 項特價)');
define('TEXT_OF_5_STARS', '%s 顆星!');

define('PREVNEXT_TITLE_FIRST_PAGE', '第一頁');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', '前一頁');
define('PREVNEXT_TITLE_NEXT_PAGE', '下一頁');
define('PREVNEXT_TITLE_LAST_PAGE', '最後一頁');
define('PREVNEXT_TITLE_PAGE_NO', '第%d頁');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', '前 %d 頁');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', '下 %d 頁');
define('PREVNEXT_BUTTON_FIRST', '<<最前面');
define('PREVNEXT_BUTTON_PREV', '[<<&nbsp;往前]');
define('PREVNEXT_BUTTON_NEXT', '[往後&nbsp;>>]');
define('PREVNEXT_BUTTON_LAST', '最後面>>');

define('IMAGE_BUTTON_ADD_ADDRESS', '新地址');
define('IMAGE_BUTTON_ADDRESS_BOOK', '地址簿');
define('IMAGE_BUTTON_BACK', '上一項');
define('IMAGE_BUTTON_CHANGE_ADDRESS', '變更地址');
define('IMAGE_BUTTON_CHECKOUT', '結帳');
define('IMAGE_BUTTON_CLEAR', '清空購物車');
define('IMAGE_BUTTON_DELETE', '移除');
define('IMAGE_BUTTON_CONFIRM_ORDER', '確認訂單');
define('IMAGE_BUTTON_CONTINUE', '下一項');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', '繼續購物');
define('IMAGE_BUTTON_DELETE', '刪除');
define('IMAGE_BUTTON_EDIT_ACCOUNT', '更改帳號');
define('IMAGE_BUTTON_HISTORY', '訂單紀錄');
define('IMAGE_BUTTON_LOGIN', '登入');
define('IMAGE_BUTTON_IN_CART', '放到購物籃');
define('IMAGE_BUTTON_NOTIFICATIONS', '商品通知');
define('IMAGE_BUTTON_QUICK_FIND', '快速搜尋');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', '刪除商品通知');
define('IMAGE_BUTTON_REVIEWS', '商品評論');
define('IMAGE_BUTTON_SEARCH', '搜尋');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', '出貨選項');
define('IMAGE_BUTTON_TELL_A_FRIEND', '推薦');
define('IMAGE_BUTTON_UPDATE', '更新');
define('IMAGE_BUTTON_UPDATE_CART', '更新購物籃');
define('IMAGE_BUTTON_WRITE_REVIEW', '輯寫商品評論');

define('SMALL_IMAGE_BUTTON_DELETE', '刪除');
define('SMALL_IMAGE_BUTTON_EDIT', '編輯');
define('SMALL_IMAGE_BUTTON_VIEW', '檢視');

define('ICON_ARROW_RIGHT', '看更多');
define('ICON_CART', '放到購物籃');
define('ICON_SUCCESS', '完成');
define('ICON_WARNING', '注意');

define('TEXT_GREETING_PERSONAL', '<span class="greetUser">%s 您好，歡迎光臨！ 想看看有什麼<a href="%s"><u>新進商品</u></a>？</span>');
define('TEXT_GREETING_PERSONAL_RELOGON', '如果您不是 %s, 請用自己的帳號<a href="%s"><u>登入</u></a>');
define('TEXT_GREETING_GUEST', '<span class="greetUser">訪客，歡迎光臨，想看看有什麼<a href="' . DIR_WS_CATALOG . 'products_new.php"><u>新進商品</u></a>？ 如果您已經是會員請直接<a href="%s"><u>登入</u></a>？ 或是<a href="%s"><u>註冊為會員</u></a>？</span>');

define('TEXT_SORT_PRODUCTS', '商品排序方式');
define('TEXT_DESCENDINGLY', '遞減，');
define('TEXT_ASCENDINGLY', '遞增，');
define('TEXT_BY', '排序鍵');

define('TEXT_REVIEW_BY', '來自 %s 所評論寫道');
define('TEXT_REVIEW_WORD_COUNT', '%s 字');
define('TEXT_REVIEW_RATING', '評等: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', '評論日期: %s');
define('TEXT_NO_REVIEWS', '目前沒有任何商品評論.');
define('TEXT_NO_NEW_PRODUCTS', '目前沒有新進商品.');

define('TEXT_UNKNOWN_TAX_RATE', '不明的稅率');

define('TEXT_REQUIRED', '<span class="errorText">必填</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> 無法由指定的 SMTP 主機傳送郵件，請檢查 php.ini 設定</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', '警告： 安裝目錄仍然存在： ' . DIR_FS_DOCUMENT_ROOT . 'twe_installer. 基於安全的理由，請將這個目錄刪除');
define('WARNING_CONFIG_FILE_WRITEABLE', '警告： 設定檔允許被寫入： ' . DIR_FS_DOCUMENT_ROOT . 'includes/configure.php. 這將具有潛在的系統安全風險 - 請將檔案設定為正確的使用權限');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', '警告： sessions 資料夾不存在： ' . twe_session_save_path() . '. 在這個目錄未建立之前 Sessions 無法正常動作');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', '警告： 無法寫入sessions 資料夾： ' . twe_session_save_path() . '. 在使用者權限未正確設定之前 Sessions 將無法正常動作');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', '警告： 下載的商品目錄不存在： ' . DIR_FS_DOWNLOAD . '. 在這個目錄未建立之前，無法下載商品');
define('WARNING_SESSION_AUTO_START', '警告： session.auto_start 已啟動 - 請到 php.ini 內關閉這個功能，並重新啟動網頁主機');
define('TEXT_CCVAL_ERROR_INVALID_DATE', '輸入的信用卡到期日無效<br>請檢查日期後再試');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', '信用卡卡號無效<br>請檢查後再試');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', '您輸入的前四碼是: %s<br>如果正確，我們目前尚無法接受此類信用卡<br>如果錯誤請重試');


define('FOOTER_TEXT_BODY', '<img   src="../images/banner/twecommerce.gif" >');

//  conditions check

define('ERROR_CONDITIONS_NOT_ACCEPTED', '如果您不同意我們的購物條約, 將無法接受您的訂單!');

define('TEXT_CART_OT_DISCOUNT', '客戶層級 ');
define('SUB_TITLE_OT_DISCOUNT','折扣:');
define('SUB_TITLE_SUB_NEW','總計:');

define('NOT_ALLOWED_TO_SEE_PRICES','您目前沒有權限檢示價格');
define('NOT_ALLOWED_TO_ADD_TO_CART','您目前沒有權限將商品放進購物籃');

define('BOX_LOGINBOX_HEADING', '歡迎光臨!');
define('BOX_LOGINBOX_EMAIL', '電子郵件:');
define('BOX_LOGINBOX_PASSWORD', '密碼:');
define('BOX_ACCOUNTINFORMATION_HEADING','服務台');

define('BOX_LOGINBOX_STATUS','客戶群組 : ');
define('BOX_LOGINBOX_INCL','含稅價格 : 所有價格含稅');
define('BOX_LOGINBOX_EXCL','不含稅價格 : 所有價格不含稅');
define('TAX_ADD_TAX','含稅. ');
define('TAX_NO_TAX','未稅 ');
define('BOX_LOGINBOX_DISCOUNT','商品折扣 :');
define('BOX_LOGINBOX_DISCOUNT_TEXT','層級折扣 :');
define('BOX_LOGINBOX_DISCOUNT_OT','');
define('TAX_ALL_TAX','稅率 :');

define('NOT_ALLOWED_TO_SEE_PRICES_TEXT','無法檢視商品價格.');

define('TEXT_DOWNLOAD','下載');
define('TEXT_VIEW','檢視');

define('TEXT_BUY', '1 x \'');
define('TEXT_NOW', '\' ');
define('TEXT_GUEST','訪客');
define('TEXT_NO_PURCHASES', '您尚未完成任何訂單程序.');


// Warnings
define('SUCCESS_ACCOUNT_UPDATED', '您的帳號資訊已經更新.');
define('SUCCESS_NEWSLETTER_UPDATED', '您的電子報選項已經更新!');
define('SUCCESS_NOTIFICATIONS_UPDATED', '您的商品通知選項已經更新!');
define('SUCCESS_PASSWORD_UPDATED', '您的密碼已經更新!');
define('ERROR_CURRENT_PASSWORD_NOT_MATCHING', '輸入的密碼不符. 請再試一次.');
define('TEXT_MAXIMUM_ENTRIES', '<font color="#ff0000"><b>註:</b></font> 通訊錄上限為 %s 筆!');
define('SUCCESS_ADDRESS_BOOK_ENTRY_DELETED', '已從通訊錄移除選取的地址.');
define('SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED', '通訊錄更新完成!');
define('WARNING_PRIMARY_ADDRESS_DELETION', '這預設的通訊地址不能刪除. 請先選擇其他通訊地址將其設定成預設地址後再行刪除.');
define('ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY', '通訊錄資料不存在.');
define('ERROR_ADDRESS_BOOK_FULL', '通訊錄已滿，.請刪除不需要的地址已儲存新的地址.');

//Advanced Search
define('ENTRY_CATEGORIES', '商品目錄:');
define('ENTRY_INCLUDE_SUBCATEGORIES', '包括子目錄');
define('ENTRY_MANUFACTURERS', '廠商:');
define('ENTRY_PRICE_FROM', '價格由:');
define('ENTRY_PRICE_TO', '到:');
define('TEXT_ALL_CATEGORIES', '所有商品目錄');
define('TEXT_ALL_MANUFACTURERS', '所有廠商');
define('JS_AT_LEAST_ONE_INPUT', '* 搜尋表單內至少必須填一個欄位\n');
define('JS_INVALID_FROM_DATE', '* 無效的開始日期\n');
define('JS_INVALID_TO_DATE', '* 無效的結束日期\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* 結束日期必須大於或等於開始日期\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* 價格必須為數字\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* 價格必須為數字\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* 價格上限必須大於或等於價格下限.\n');
define('JS_INVALID_KEYWORDS', '* 無效的關鍵字\n');
define('TEXT_NO_PRODUCTS', '無符合搜尋條件的商品.');
define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>注意:</b></font></small> 如果您已經擁有帳號, 請按 <a href="%s"><u><b>這裡</b>登入</u></a>.');
define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>錯誤:</b></font>\'電子郵件地址\' 或 \'密碼\'不符，請重新輸入');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>提示:</b></font> 若您要結帳請先以會員帳號登入，您於&quot;訪客購物車&quot;裡的商品，會在登入後自動併入&quot;會員購物車&quot;內 <a href="javascript:session_win();">[更多說明]</a>');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>注意:</b></font> 輸入的電子郵件尚未註冊. 請再試一次.');
define('TEXT_PASSWORD_SENT', '新的密碼已經寄出至這郵件地址.');
define('TEXT_PRODUCT_NOT_FOUND', '商品不存在!');
define('TEXT_MORE_INFORMATION', '更多訊息, 請按 <a href="%s" target="_blank"><u>這裡</u></a>');
define('TEXT_DATE_ADDED', '產品上架時間 %s.');
define('TEXT_DATE_AVAILABLE', '<font color="#ff0000">本商品預計上架日期：%s.</font>');
define('TEXT_CART_EMPTY', '您的購物籃沒有任何商品');
define('SUB_TITLE_SUB_TOTAL', '總計:');
define('SUB_TITLE_TOTAL', '總計:');

define('OUT_OF_STOCK_CANT_CHECKOUT', '標示' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' 的商品目前庫存量已經不足，請減少標示(' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ')的商品數量, 謝謝');
define('OUT_OF_STOCK_CAN_CHECKOUT', '標示' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '的商品目前庫存量已經不足，如果您願意仍然可以繼續完成結帳程序，我們會在補足庫存後立即將您所訂購的商品寄出！謝謝');

define('HEADING_TITLE_TELL_A_FRIEND', '推薦給 \'%s\'');
define('HEADING_TITLE_ERROR_TELL_A_FRIEND', '推薦商品給親友');
define('ERROR_INVALID_PRODUCT', '無符合搜尋條件的商品!');


define('NAVBAR_TITLE_ACCOUNT', '您的帳號');
define('NAVBAR_TITLE_1_ACCOUNT_EDIT', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_EDIT', '更改帳號');
define('NAVBAR_TITLE_1_ACCOUNT_HISTORY', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_HISTORY', '您的訂單紀錄');
define('NAVBAR_TITLE_1_ACCOUNT_HISTORY_INFO', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_HISTORY_INFO', '確認訂單');
define('NAVBAR_TITLE_3_ACCOUNT_HISTORY_INFO', '訂單序號 %s');
define('NAVBAR_TITLE_1_ACCOUNT_NEWSLETTERS', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_NEWSLETTERS', '訂閱電子報');
define('NAVBAR_TITLE_1_ACCOUNT_NOTIFICATIONS', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_NOTIFICATIONS', '商品資訊');
define('NAVBAR_TITLE_1_ACCOUNT_PASSWORD', '您的帳號');
define('NAVBAR_TITLE_2_ACCOUNT_PASSWORD', '更改密碼');
define('NAVBAR_TITLE_1_ADDRESS_BOOK', '您的帳號');
define('NAVBAR_TITLE_2_ADDRESS_BOOK', '地址簿');
define('NAVBAR_TITLE_1_ADDRESS_BOOK_PROCESS', '您的帳號');
define('NAVBAR_TITLE_2_ADDRESS_BOOK_PROCESS', '地址簿');
define('NAVBAR_TITLE_ADD_ENTRY_ADDRESS_BOOK_PROCESS', '新增地址');
define('NAVBAR_TITLE_MODIFY_ENTRY_ADDRESS_BOOK_PROCESS', '更改');
define('NAVBAR_TITLE_DELETE_ENTRY_ADDRESS_BOOK_PROCESS', '刪除');
define('NAVBAR_TITLE_ADVANCED_SEARCH', '進階搜尋');
define('NAVBAR_TITLE1_ADVANCED_SEARCH', '進階搜尋');
define('NAVBAR_TITLE2_ADVANCED_SEARCH', '搜尋結果');
define('NAVBAR_TITLE_1_CHECKOUT_CONFIRMATION', '結帳');
define('NAVBAR_TITLE_2_CHECKOUT_CONFIRMATION', '確認');
define('NAVBAR_TITLE_1_CHECKOUT_PAYMENT', '結帳');
define('NAVBAR_TITLE_2_CHECKOUT_PAYMENT', '付款方式');
define('NAVBAR_TITLE_1_PAYMENT_ADDRESS', '結帳');
define('NAVBAR_TITLE_2_PAYMENT_ADDRESS', '更改帳單地址');
define('NAVBAR_TITLE_1_CHECKOUT_SHIPPING', '結帳');
define('NAVBAR_TITLE_2_CHECKOUT_SHIPPING', '出貨資訊');
define('NAVBAR_TITLE_1_CHECKOUT_SHIPPING_ADDRESS', '結帳');
define('NAVBAR_TITLE_2_CHECKOUT_SHIPPING_ADDRESS', '更改收貨地址');
define('NAVBAR_TITLE_1_CHECKOUT_SUCCESS', '結帳');
define('NAVBAR_TITLE_2_CHECKOUT_SUCCESS', '結帳完成');
define('NAVBAR_TITLE_CONTACT_US', '聯繫我們');
define('NAVBAR_TITLE_CREATE_ACCOUNT', '新增帳號');
define('NAVBAR_TITLE_QUOTES_PROCESS', '報價');
if ($navigation->snapshot['page'] == FILENAME_CHECKOUT_SHIPPING) {
  define('NAVBAR_TITLE_LOGIN', '訂購');
} else {
  define('NAVBAR_TITLE_LOGIN', '登入');
}
define('NAVBAR_TITLE_LOGOFF','再見');
define('NAVBAR_TITLE_1_PASSWORD_FORGOTTEN', '登入');
define('NAVBAR_TITLE_2_PASSWORD_FORGOTTEN', '忘記密碼');
define('NAVBAR_TITLE_PRODUCTS_NEW', '新上架商品');
define('NAVBAR_TITLE_SHOPPING_CART', '購物籃');
define('NAVBAR_TITLE_SPECIALS', '推薦商品');
define('NAVBAR_TITLE_COOKIE_USAGE', 'Cookie用法');
define('NAVBAR_TITLE_PRODUCT_REVIEWS', '商品評論');
define('NAVBAR_TITLE_TELL_A_FRIEND', '推薦商品');
define('NAVBAR_TITLE_REVIEWS_WRITE', '意見');
define('NAVBAR_TITLE_REVIEWS','商品評論');
define('NAVBAR_TITLE_SSL_CHECK', 'SSL安全環境');
define('NAVBAR_TITLE_CREATE_GUEST_ACCOUNT','新增帳號');

define('TEXT_EMAIL_SUCCESSFUL_SENT','您的電子郵件傳送完成!');
define('ERROR_MAIL','請檢查輸入的郵件資料是否正確');
define('CATEGORIE_NOT_FOUND','沒有符合的商品目錄');
define('PULL_DOWN_TAIWAN','台灣');
define('ARRIVE_DAY','預約出貨日期 :');
define('ARRIVE_TIME','預約出貨時間 :');
define('ARRIVE_TIME_TO','至');
define('ENTRY_COMMENT','客戶意見:');
define('NAVBAR_TITLE_BBFORUM','討論區');
define('HEADER_TITLE_MY_BBFORUM', 'Phpbb 討論區');
define('ENTRY_USER_NAME', '帳號:');
define('ENTRY_USER_NAME_ERROR', '帳號不得少於 ' . ENTRY_LAST_NAME_MIN_LENGTH . ' 個字');
define('ENTRY_USER_NAME_TEXT', '*');
define('ENTRY_USER_NAME_ERROR_EXISTS', '這個帳號已經註冊過!請確認或換一個帳號');
define('ERROR_INVALID_USES_COUPON', '這個折價券兌換次數超過,只能兌換 ');
define('ERROR_INVALID_USES_USER_COUPON', '這個折價券兌換次數超過,每一位只能兌換 ');
define('TIMES', '次的時間');
define('ERROR_INVALID_FINISDATE_COUPON', '這個折價券已經超過兌換時限!');
define('ERROR_INVALID_STARTDATE_COUPON', '這個折價券尚未到達啟用時間!');
define('ERROR_NO_INVALID_REDEEM_COUPON', '這個折價券兌換碼是無效的!');
define('ERROR_INVALID_REDEEM_COUPON', '兌換成功!');
define('BOX_INFORMATION_GV', '禮券 FAQ');
define('BOX_HEADING_GIFT_VOUCHER', '禮券帳號'); 
define('GV_FAQ', '禮券 FAQ');
define('ERROR_REDEEMED_AMOUNT', '兌換完成 ');
define('ERROR_NO_REDEEM_CODE', '您尚未輸入兌換密碼.');  
define('ERROR_NO_INVALID_REDEEM_GV', '兌換密碼是無效的'); 
define('TABLE_HEADING_CREDIT', '禮券可用存餘');
define('ENTRY_AMOUNT_CHECK_ERROR', '您的禮券存餘不足,無法寄出您欲寄出之總額.'); 

define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'當您加入本站為會員時, 我們有附贈給您一份價值 %s 禮券面額');
define('EMAIL_GV_REDEEM', '禮券兌換碼是 %s, 當您在我們的線上商店購物結帳時, 可以輸入此兌換碼進行兌換抵扣購物金額');
define('EMAIL_COUPON_INCENTIVE_HEADER', '同時為了感謝您第一次光臨我們的線上商店, 並希望能給您一個愉快的購物經驗 ,我們也贈送給您一份折價購物禮券.' . "\n" . 
                                        ' 以下是給您個人的禮券資訊' . "\n");
define('EMAIL_COUPON_REDEEM', '禮券的兌換密碼是 %s 可以在結帳時使用');
define('EMAIL_SUBJECT', '訊息從 ' . STORE_NAME);
define('EMAIL_SEPARATOR', '----------------------------------------------------------------------------------------');

define('EMAIL_GV_TEXT_HEADER', '恭喜您, 您擁有一份有效的禮券面額為 %s');
define('EMAIL_GV_TEXT_SUBJECT', '一份禮物從 %s');
define('EMAIL_GV_FROM', '寄給您這份禮物是經由 %s');
define('EMAIL_GV_MESSAGE', '其中訊息中提到 ');
define('EMAIL_GV_SEND_TO', 'Hi, %s');
define('EMAIL_GV_REDEEMED', '兌換禮券, 請按下連結至禮券兌換區於兌換碼欄位中填入正確的兌換碼, 您的兌換碼是 %s. 有任何疑問.');
define('EMAIL_GV_LINK', '兌換禮券請按 ');
define('EMAIL_GV_VISIT', ' 或蒞臨 ');
define('EMAIL_GV_ENTER', ' 並輸入密碼 ');
define('EMAIL_GV_FIXED_FOOTER', '如果您在兌換禮券時有任何問題,請按上方的連結, ' . "\n" .
                                '您也可以在我們的商店結帳時,填入禮券兌換碼,來兌換您的禮券.' . "\n\n");
define('EMAIL_GV_SHOP_FOOTER', '');
define('MAIN_MESSAGE', '您寄送了一份禮券價值為 %s 給 %s 電子郵件地址是 %s<br><br>這附在郵件中的說明是<br><br>您好 %s<br><br>
                        您寄送了一份禮券價值為 %s 從 %s');
define('PERSONAL_MESSAGE', '%s 提到:');
define('NAVBAR_GV_FAQ', '兌換禮券 FAQ');
define('NAVBAR_GV_REDEEM', '兌換禮券');
define('NAVBAR_GV_SEND', '郵寄禮券給親友');
define('HEADER_NEWS','<font size="2">最新消息</font>');
define('READ_MORE_NEWS','詳全文');
define('TEXT_NEWS_DATE_ADDED', '<font size="1">新聞發佈時間 %s.</font>');
define('TEXT_NEWS_VIEWED', '<font size="1">瀏覽次數&nbsp;<strong>(<strong> %s <strong>)<strong></font>');
define('TEXT_DISPLAY_NUMBER_OF_NEWS', '顯示 <b>%d</b> 到 <b>%d</b> (共<b>%d</b>個新聞)');
define('TEXT_QUOTES_TITLE','您的報價內容');
define('TEXT_SHOPPING_CART_TITLE','您的購物車內容');
define('CONDITIONS_ACCEPTED','您要求的報價項目已經紀錄了,我們會盡快回覆!');
define('JS_STATE_SELECT', '-- 使用上方選擇 --');
define('PLEASE_SELECT', '請選擇縣市');
define('HEADER_SPECIALS','<font size="2">特價商品</font>');
define('ENTRY_PLEASE','請輸入關鍵字');
define('REDEEMED_COUPON','兌換完成!');

//myEdit
define('HINT_PRODUCT_INFO','不同商品需求請逐筆選購!');
define('IMAGE_BUTTON_ADD_CART', '加入購物車');
define('IMAGE_BUTTON_HOME', '返回首頁');

?>