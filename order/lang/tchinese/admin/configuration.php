<?php
/* -----------------------------------------------------------------------------------------
   $Id: configuration.php,v 1.7 2004/04/14 19:17:21 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(configuration.php,v 1.8 2002/01/04); www.oscommerce.com
   (c) 2003	 nextcommerce (configuration.php,v 1.16 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

define('TABLE_HEADING_CONFIGURATION_TITLE', '名稱');
define('TABLE_HEADING_CONFIGURATION_VALUE', '值');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_INFO_EDIT_INTRO', '請做必要修改');
define('TEXT_INFO_DATE_ADDED', '建檔日期:');
define('TEXT_INFO_LAST_MODIFIED', '最後修改日期:');

// language definitions for config
define('STORE_NAME_TITLE' , '商店名稱');
define('STORE_NAME_DESC' , '商店名稱');
define('STORE_OWNER_TITLE' , '商店擁有人');
define('STORE_OWNER_DESC' , '商店擁有人');
define('STORE_OWNER_EMAIL_ADDRESS_TITLE' , '電子郵件');
define('STORE_OWNER_EMAIL_ADDRESS_DESC' , '商店擁有人的電子郵件');

define('EMAIL_FROM_TITLE' , '寄件人');
define('EMAIL_FROM_DESC' , '郵件寄件人.');

define('STORE_COUNTRY_TITLE' , '國別');
define('STORE_COUNTRY_DESC' , '商店所在國 <br><br><b>注意： 請記得更新商店地區.</b>');
define('STORE_ZONE_TITLE' , '地區');
define('STORE_ZONE_DESC' , '商店所在地區.');

define('EXPECTED_PRODUCTS_SORT_TITLE' , '預計上市商品順序排序');
define('EXPECTED_PRODUCTS_SORT_DESC' , '預計上市商品排序方式遞增或遞減.');
define('EXPECTED_PRODUCTS_FIELD_TITLE' , '預計上市商品欄位排序');
define('EXPECTED_PRODUCTS_FIELD_DESC' , '預計上市商品排序依欄位排序.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE' , '語系與貨幣同步');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC' , '變更語系時，自動切換至該語系之貨幣.');

define('SEND_EXTRA_ORDER_EMAILS_TO_TITLE' , '新訂單郵件額外通知:');
define('SEND_EXTRA_ORDER_EMAILS_TO_DESC' , '有新訂單時額外通知的電子郵件地址，兩個郵件地址間以逗號隔開，格式如下: Name1<email@address1>,Name2<email@address2>');

define('SEARCH_ENGINE_FRIENDLY_URLS_TITLE' , '使用搜尋引擎儲存連結?');
define('SEARCH_ENGINE_FRIENDLY_URLS_DESC' , '使用搜尋引擎儲存所有網站的連結.');

define('DISPLAY_CART_TITLE' , '購買商品後顯示購物車?');
define('DISPLAY_CART_DESC' , '購買商品後立刻顯示購物車(或留在原來的位置)?');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE' , '非會員可否推薦商品?');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC' , '非會員可否推薦商品給親友?');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE' , '預設的搜尋運算元');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC' , '預設的搜尋運算元.');

define('STORE_NAME_ADDRESS_TITLE' , '商店地址與電話');
define('STORE_NAME_ADDRESS_DESC' , '商店名稱、地址與電話，用於文件列印及線上顯示.');

define('SHOW_COUNTS_TITLE' , '顯示商品分類商品數');
define('SHOW_COUNTS_DESC' , '可以計算並顯示每一個商品目錄內的商品數量');

define('DISPLAY_PRICE_WITH_TAX_TITLE' , '顯示含稅金額');
define('DISPLAY_PRICE_WITH_TAX_DESC' , '顯示含稅金額(true) 或將稅金加於尾端(false)');

define('DEFAULT_CUSTOMERS_STATUS_ID_ADMIN_TITLE' , '管理員層級');
define('DEFAULT_CUSTOMERS_STATUS_ID_ADMIN_DESC' , '選擇一個客戶層級歸屬於管理團隊!');
define('DEFAULT_CUSTOMERS_STATUS_ID_GUEST_TITLE' , '訪客層級');
define('DEFAULT_CUSTOMERS_STATUS_ID_GUEST_DESC' , '當客戶尚未登入前的客戶層級?');
define('DEFAULT_CUSTOMERS_STATUS_ID_TITLE' , '新註冊客戶層級');
define('DEFAULT_CUSTOMERS_STATUS_ID_DESC' , '新註冊客戶套用客戶層級?');


define('ALLOW_ADD_TO_CART_TITLE' , '群組放到購物車選項');
define('ALLOW_ADD_TO_CART_DESC' , '如果群組設定顯示價格為"否"時開放客戶群組使用放到購物車');
define('ALLOW_DISCOUNT_ON_PRODUCTS_ATTRIBUTES_TITLE' , '商品屬性折扣');
define('ALLOW_DISCOUNT_ON_PRODUCTS_ATTRIBUTES_DESC' , '讓客戶在商品屬性價格取得折扣 (假如商品不是一件特價商品)');
define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE' , '目錄種類使用簡短說明');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC' , '目錄種類使用簡短說明');
define('CURRENT_TEMPLATE_TITLE' , '商店使用佈景 (Theme)');
define('CURRENT_TEMPLATE_DESC' , '選擇一個佈景 (Theme).');
define('OPEN_FORUM_TITLE' , '商店使用討論區');
define('OPEN_FORUM_DESC' , '開啟討論區?');
define('OPEN_TWE_GROUP_TITLE' , '商店開啟群組廣告');
define('OPEN_TWE_GROUP_DESC' , '開啟群組廣告?');
define('PRICE_IS_BRUTTO_TITLE','毛利管理');
define('PRICE_IS_BRUTTO_DESC','測試毛利標準');

define('PRICE_PRECISION_TITLE','毛利/稅金比率');
define('PRICE_PRECISION_DESC','毛利/稅金比率');


define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE' , '中文姓名');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC' , '中文姓名最少字數');
define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE' , '暱稱');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC' , '暱稱最少字數');
define('ENTRY_DOB_MIN_LENGTH_TITLE' , '生日');
define('ENTRY_DOB_MIN_LENGTH_DESC' , '生日最少字數');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE' , '電子郵件地址');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC' , '電子郵件地址最少字數');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE' , '街道門牌號碼');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC' , '街道門牌號碼最少字數');
define('ENTRY_COMPANY_MIN_LENGTH_TITLE' , '公司');
define('ENTRY_COMPANY_MIN_LENGTH_DESC' , '公司名稱最少字數');
define('ENTRY_POSTCODE_MIN_LENGTH_TITLE' , '郵遞區號');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC' , '郵遞區號最少字數');
define('ENTRY_CITY_MIN_LENGTH_TITLE' , '鎮鄉村');
define('ENTRY_CITY_MIN_LENGTH_DESC' , '鄉鎮最少字數');
define('ENTRY_STATE_MIN_LENGTH_TITLE' , '縣市');
define('ENTRY_STATE_MIN_LENGTH_DESC' , '縣市最少字數');
define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE' , '電話號碼');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC' , '電話號碼最少字數');
define('ENTRY_PASSWORD_MIN_LENGTH_TITLE' , '密碼');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC' , '密碼最少字數');

define('CC_OWNER_MIN_LENGTH_TITLE' , '持卡人姓名');
define('CC_OWNER_MIN_LENGTH_DESC' , '持卡人姓名最少字數');
define('CC_NUMBER_MIN_LENGTH_TITLE' , '信用卡卡號');
define('CC_NUMBER_MIN_LENGTH_DESC' , '信用卡卡號最少字數');

define('REVIEW_TEXT_MIN_LENGTH_TITLE' , '評論文字');
define('REVIEW_TEXT_MIN_LENGTH_DESC' , '商品評論最少字數');

define('MIN_DISPLAY_BESTSELLERS_TITLE' , '暢銷商品');
define('MIN_DISPLAY_BESTSELLERS_DESC' , '暢銷商品最少顯示數量');
define('MIN_DISPLAY_ALSO_PURCHASED_TITLE' , '建議購買商品');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC' , '建議客戶購買商品區塊所能顯示的最少數目');

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE' , '通訊錄筆數');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC' , '每位顧客擁有通訊錄的最大筆數');
define('MAX_DISPLAY_SEARCH_RESULTS_TITLE' , '搜尋結果');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC' , '搜尋產品列表最大數量');
define('MAX_DISPLAY_PAGE_LINKS_TITLE' , '網頁最大連結數');
define('MAX_DISPLAY_PAGE_LINKS_DESC' , '每一網頁可連結最大數目');
define('MAX_DISPLAY_SPECIAL_PRODUCTS_TITLE' , '特價商品');
define('MAX_DISPLAY_SPECIAL_PRODUCTS_DESC' , '特價商品最大顯示數量');
define('MAX_DISPLAY_NEW_PRODUCTS_TITLE' , '新上市商品模組');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC' , '商品目錄中新上市商品最大顯示數量');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE' , '商品上市預告');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC' , '商品上市預告最大顯示數量');
define('MAX_DISPLAY_MANUFACTURERS_IN_A_LIST_TITLE' , '製造廠商列表');
define('MAX_DISPLAY_MANUFACTURERS_IN_A_LIST_DESC' , '用於製造廠商區塊; 當製造廠商數超過這個數量，預設的清單會取代下拉式清單');
define('MAX_MANUFACTURERS_LIST_TITLE' , '製造廠商選取格式');
define('MAX_MANUFACTURERS_LIST_DESC' , '用於製造廠商區塊; 如果這個值是\'1\' 則顯示下拉式選單，否則會依這個數值作為製造廠商列表的列數，例如，數值 3 則會出現 3 列的製造廠商列表，而不會出現下拉式選單');
define('MAX_DISPLAY_MANUFACTURER_NAME_LEN_TITLE' , '製造廠商名稱字數');
define('MAX_DISPLAY_MANUFACTURER_NAME_LEN_DESC' , '用於製造廠商區塊; 名稱顯示的最多字數');
define('MAX_DISPLAY_NEW_REVIEWS_TITLE' , '新的評論');
define('MAX_DISPLAY_NEW_REVIEWS_DESC' , '商品新增評論顯示的最大數量');
define('MAX_RANDOM_SELECT_REVIEWS_TITLE' , '商品評論隨機選取');
define('MAX_RANDOM_SELECT_REVIEWS_DESC' , '從多少筆資料中隨機選取一筆商品評論');
define('MAX_RANDOM_SELECT_NEW_TITLE' , '新上市商品隨機選取');
define('MAX_RANDOM_SELECT_NEW_DESC' , '從多少筆記錄中隨機選取一筆新上市資料');
define('MAX_RANDOM_SELECT_SPECIALS_TITLE' , '特價商品隨機選取');
define('MAX_RANDOM_SELECT_SPECIALS_DESC' , '從多少筆資料中選取一筆特價商品資料');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE' , '每列可列出的商品目錄');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC' , '每一列可列多少個商品目錄');
define('MAX_DISPLAY_PRODUCTS_PER_ROW_TITLE' , '每列可列出的商品數量');
define('MAX_DISPLAY_PRODUCTS_PER_ROW_DESC' , '每一列可列多少個商品');
define('MAX_DISPLAY_SPECIAL_PER_ROW_TITLE' , '特價商品每列可列出的商品數量');
define('MAX_DISPLAY_SPECIAL_PER_ROW_DESC' , '特價商品每一列可列多少個商品');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE' , '新上市商品列表');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC' , '新上市商品每頁可顯示最大數量');
define('MAX_DISPLAY_BESTSELLERS_TITLE' , '暢銷商品');
define('MAX_DISPLAY_BESTSELLERS_DESC' , '暢銷商品最大顯示數量');
define('MAX_DISPLAY_ALSO_PURCHASED_TITLE' , '建議購買商品');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC' , '建議客戶購買商品區塊所能顯示的最大數目');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE' , '顧客訂單記錄區塊');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC' , '顧客訂單記錄區塊中，顯示已購買商品的最大數量');
define('MAX_DISPLAY_ORDER_HISTORY_TITLE' , '訂單記錄');
define('MAX_DISPLAY_ORDER_HISTORY_DESC' , '訂單記錄每頁可顯示的最大訂單記錄數量');

define('PRODUCT_IMAGE_THUMBNAIL_WIDTH_TITLE' , '重製商品小圖寬度');
define('PRODUCT_IMAGE_THUMBNAIL_WIDTH_DESC' , '重製商品小圖寬度');
define('PRODUCT_IMAGE_THUMBNAIL_HEIGHT_TITLE' , '重製商品小圖高度');
define('PRODUCT_IMAGE_THUMBNAIL_HEIGHT_DESC' , '重製商品小圖高度');

define('PRODUCT_IMAGE_INFO_WIDTH_TITLE' , '重製商品明細圖片寬度');
define('PRODUCT_IMAGE_INFO_WIDTH_DESC' , '重製商品明細圖片寬度');
define('PRODUCT_IMAGE_INFO_HEIGHT_TITLE' , '重製商品明細圖片高度');
define('PRODUCT_IMAGE_INFO_HEIGHT_DESC' , '重製商品明細圖片高度');

define('PRODUCT_IMAGE_POPUP_WIDTH_TITLE' , '重製彈出視窗圖片寬度');
define('PRODUCT_IMAGE_POPUP_WIDTH_DESC' , '重製彈出視窗圖片寬度');
define('PRODUCT_IMAGE_POPUP_HEIGHT_TITLE' , '重製彈出視窗圖片高度');
define('PRODUCT_IMAGE_POPUP_HEIGHT_DESC' , '重製彈出視窗圖片高度');

define('SMALL_IMAGE_WIDTH_TITLE' , '商品小圖寬度');
define('SMALL_IMAGE_WIDTH_DESC' , '商品小圖寬度');
define('SMALL_IMAGE_HEIGHT_TITLE' , '商品小圖高度');
define('SMALL_IMAGE_HEIGHT_DESC' , '商品小圖高度');

define('HEADING_IMAGE_WIDTH_TITLE' , '標題列圖片寬度設定');
define('HEADING_IMAGE_WIDTH_DESC' , '標題列圖片寬度設定');
define('HEADING_IMAGE_HEIGHT_TITLE' , '標題列圖片高度設定');
define('HEADING_IMAGE_HEIGHT_DESC' , '標題列圖片高度設定');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE' , '子目錄圖片寬度設定');
define('SUBCATEGORY_IMAGE_WIDTH_DESC' , '子目錄圖片寬度設定');
define('SUBCATEGORY_IMAGE_HEIGHT_TITLE' , '子目錄圖片高度設定');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC' , '子目錄圖片高度設定');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE' , '計算圖檔大小');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC' , '計算圖檔大小?');

define('IMAGE_REQUIRED_TITLE' , '顯示圖片');
define('IMAGE_REQUIRED_DESC' , '可以顯示失去連結的圖片，以利除錯.');

//This is for the Images showing your products for preview. All the small stuff.

define('PRODUCT_IMAGE_THUMBNAIL_BEVEL_TITLE' , '商品縮小圖片 :斜角寬度');
define('PRODUCT_IMAGE_THUMBNAIL_BEVEL_DESC' , '商品縮小圖片:斜角寬度<br><br>一般-值: (8,FFCCCC,330000)<br><br>斜角切邊<br>用法:<br>(邊寬度,亮系色彩,暗系色彩)');
define('PRODUCT_IMAGE_THUMBNAIL_GREYSCALE_TITLE' , '商品縮小圖片:色相/飽和度');
define('PRODUCT_IMAGE_THUMBNAIL_GREYSCALE_DESC' , '商品縮小圖片:色相/飽和度<br><br>一般-值: (32,22,22)<br><br>色相/飽和度<br>用法:<br>(紅,綠,藍)');
define('PRODUCT_IMAGE_THUMBNAIL_ELLIPSE_TITLE' , '商品縮小圖片:橢圓背景');
define('PRODUCT_IMAGE_THUMBNAIL_ELLIPSE_DESC' , '商品縮小圖片:橢圓背景<br><br>一般-值: (FFFFFF)<br><br>橢圓背景的顏色<br>用法:<br>(十六進位背景顏色)');
define('PRODUCT_IMAGE_THUMBNAIL_ROUND_EDGES_TITLE' , '商品縮小圖片:圓角');
define('PRODUCT_IMAGE_THUMBNAIL_ROUND_EDGES_DESC' , '商品縮小圖片:圓角<br><br>一般-值: (5,FFFFFF,3)<br><br>圓角<br>用法:<br>(圓角半徑,背景色,寬度)');
define('PRODUCT_IMAGE_THUMBNAIL_MERGE_TITLE' , '商品縮小圖片:合併圖片');
define('PRODUCT_IMAGE_THUMBNAIL_MERGE_DESC' , '商品縮小圖片:合併圖片<br><br>一般-值: (overlay.gif,10,-50,60,FF0000)<br><br>預覆蓋上的圖片<br>用法:<br>(圖片檔案名稱,x 軸點,y 軸點,透明度,圖片通透顏色)');
define('PRODUCT_IMAGE_THUMBNAIL_FRAME_TITLE' , '商品縮小圖片:外框架<br><br>一般-值: (FFFFFF,000000,3,EEEEEE)');
define('PRODUCT_IMAGE_THUMBNAIL_FRAME_DESC' , '商品縮小圖片:外框架<br><br>一般-值: <br><br>圖片邊框<br>用法:<br>(淡色彩,暗色彩,邊框寬度,框架顏色 [選擇性 - 預設值在淡色彩與暗色彩之間])');
define('PRODUCT_IMAGE_THUMBNAIL_DROP_SHADDOW_TITLE' , '商品縮小圖片:陰影[功能開發中]');
define('PRODUCT_IMAGE_THUMBNAIL_DROP_SHADDOW_DESC' , '商品縮小圖片:陰影[功能開發中]<br><br>一般-值: (3,333333,FFFFFF)<br><br>柔化[功能開發中]<br>用法:<br>(陰影寬度,陰影顏色,背景顏色)');
define('PRODUCT_IMAGE_THUMBNAIL_MOTION_BLUR_TITLE' , '商品縮小圖片:層次模式');
define('PRODUCT_IMAGE_THUMBNAIL_MOTION_BLUR_DESC' , '商品縮小圖片:層次模式<br><br>一般-值: (4,FFFFFF)<br><br>向下延伸的平行線<br>用法:<br>(線條數量,背景色)');

//And this is for the Images showing your products in single-view

define('PRODUCT_IMAGE_INFO_BEVEL_TITLE' , '商品明細圖片:斜角寬度');
define('PRODUCT_IMAGE_INFO_BEVEL_DESC' , '商品明細圖片:斜角寬度<br><br>一般-值: (8,FFCCCC,330000)<br><br>斜角切邊<br>用法:<br>(邊寬度,亮系色彩,暗系色彩)');
define('PRODUCT_IMAGE_INFO_GREYSCALE_TITLE' , '商品明細圖片:色相/飽和度');
define('PRODUCT_IMAGE_INFO_GREYSCALE_DESC' , '商品明細圖片:色相/飽和度<br><br>一般-值: (32,22,22)<br><br>色相/飽和度<br>用法:<br>(紅,綠,藍)');
define('PRODUCT_IMAGE_INFO_ELLIPSE_TITLE' , '商品明細圖片:橢圓背景');
define('PRODUCT_IMAGE_INFO_ELLIPSE_DESC' , '商品明細圖片:橢圓背景<br><br>一般-值: (FFFFFF)<br><br>橢圓背景的顏色<br>用法:<br>(十六進位背景顏色)');
define('PRODUCT_IMAGE_INFO_ROUND_EDGES_TITLE' , '商品明細圖片:圓角');
define('PRODUCT_IMAGE_INFO_ROUND_EDGES_DESC' , '商品明細圖片:圓角<br><br>一般-值: (5,FFFFFF,3)<br><br>圓角<br>用法:<br>(圓角半徑,背景色,寬度)');
define('PRODUCT_IMAGE_INFO_MERGE_TITLE' , '商品明細圖片:合併圖片');
define('PRODUCT_IMAGE_INFO_MERGE_DESC' , '商品明細圖片:合併圖片<br><br>一般-值: (overlay.gif,10,-50,60,FF0000)<br><br>預覆蓋上的圖片<br>用法:<br>(圖片檔案名稱,x 軸點,y 軸點,透明度,圖片通透顏色)');
define('PRODUCT_IMAGE_INFO_FRAME_TITLE' , '商品明細圖片:外框架');
define('PRODUCT_IMAGE_INFO_FRAME_DESC' , '商品明細圖片:外框架<br><br>一般-值: (FFFFFF,000000,3,EEEEEE)<br><br>圖片邊框<br>用法:<br>(淡色彩,暗色彩,邊框寬度,框架顏色 [選擇性 - 預設值在淡色彩與暗色彩之間])');
define('PRODUCT_IMAGE_INFO_DROP_SHADDOW_TITLE' , '商品明細圖片:陰影[功能開發中]');
define('PRODUCT_IMAGE_INFO_DROP_SHADDOW_DESC' , '商品明細圖片:陰影[功能開發中]<br><br>一般-值:(3,333333,FFFFFF)<br><br>柔化[功能開發中]<br>用法:<br>(陰影寬度,陰影顏色,背景顏色)');
define('PRODUCT_IMAGE_INFO_MOTION_BLUR_TITLE' , '商品明細圖片:層次模式');
define('PRODUCT_IMAGE_INFO_MOTION_BLUR_DESC' , '商品明細圖片:層次模式<br><br>一般-值: (4,FFFFFF)<br><br>向下延伸的平行線<br>用法:<br>(行數,背景顏色)');

//so this image is the biggest in the shop this

define('PRODUCT_IMAGE_POPUP_BEVEL_TITLE' , '商品彈出視窗圖片:斜角寬度');
define('PRODUCT_IMAGE_POPUP_BEVEL_DESC' , '商品彈出視窗圖片:斜角寬度<br><br>一般-值: (8,FFCCCC,330000)<br><br>斜角切邊<br>用法:<br>(邊寬度,亮系色彩,暗系色彩)');
define('PRODUCT_IMAGE_POPUP_GREYSCALE_TITLE' , '商品彈出視窗圖片:色相/飽和度');
define('PRODUCT_IMAGE_POPUP_GREYSCALE_DESC' , '商品彈出視窗圖片:色相/飽和度<br><br>一般-值: (32,22,22)<br><br>色相/飽和度<br>用法:<br>(紅,綠,藍)');
define('PRODUCT_IMAGE_POPUP_ELLIPSE_TITLE' , '商品彈出視窗圖片:橢圓背景');
define('PRODUCT_IMAGE_POPUP_ELLIPSE_DESC' , '商品彈出視窗圖片:橢圓背景<br><br>一般-值: (FFFFFF)<br><br>橢圓背景的顏色<br>用法:<br>(十六進位背景顏色)');
define('PRODUCT_IMAGE_POPUP_ROUND_EDGES_TITLE' , '商品彈出視窗圖片:圓角');
define('PRODUCT_IMAGE_POPUP_ROUND_EDGES_DESC' , '商品彈出視窗圖片:圓角<br><br>一般-值: (5,FFFFFF,3)<br><br>圓角<br>用法:<br>(圓角半徑,背景色,寬度)');
define('PRODUCT_IMAGE_POPUP_MERGE_TITLE' , '商品彈出視窗圖片:合併圖片');
define('PRODUCT_IMAGE_POPUP_MERGE_DESC' , '商品彈出視窗圖片:合併圖片<br><br>一般-值: (overlay.gif,10,-50,60,FF0000)<br><br>預覆蓋上的圖片<br>用法:<br>(圖片檔案名稱,x 軸點,y 軸點,透明度,圖片通透顏色)');
define('PRODUCT_IMAGE_POPUP_FRAME_TITLE' , '商品彈出視窗圖片:外框架');
define('PRODUCT_IMAGE_POPUP_FRAME_DESC' , '商品彈出視窗圖片:外框架<br><br>一般-值:(FFFFFF,000000,3,EEEEEE)<br><br>圖片邊框<br>用法:<br>(淡色彩,暗色彩,邊框寬度,框架顏色 [選擇性 - 預設值在淡色彩與暗色彩之間])');
define('PRODUCT_IMAGE_POPUP_DROP_SHADDOW_TITLE' , '商品彈出視窗圖片:陰影[功能開發中]');
define('PRODUCT_IMAGE_POPUP_DROP_SHADDOW_DESC' , '商品彈出視窗圖片:陰影[功能開發中]<br><br>一般-值:(3,333333,FFFFFF)<br><br>柔化[功能開發中]<br>用法:<br>(陰影寬度,陰影顏色,背景顏色)');
define('PRODUCT_IMAGE_POPUP_MOTION_BLUR_TITLE' , '商品彈出視窗圖片:層次模式');
define('PRODUCT_IMAGE_POPUP_MOTION_BLUR_DESC' , '商品彈出視窗圖片:層次模式<br><br>一般-值: (4,FFFFFF)<br><br>向下延伸的平行線<br>用法:<br>(行數,背景顏色)');


define('ACCOUNT_GENDER_TITLE' , '性別');
define('ACCOUNT_GENDER_DESC' , '顯示性別');
define('ACCOUNT_DOB_TITLE' , '生日');
define('ACCOUNT_DOB_DESC' , '顯示生日');
define('ACCOUNT_COMPANY_TITLE' , '公司');
define('ACCOUNT_COMPANY_DESC' , '顯示公司');
define('ACCOUNT_SUBURB_TITLE' , '里鄰');
define('ACCOUNT_SUBURB_DESC' , '顯示里鄰');
define('ACCOUNT_STATE_TITLE' , '縣市');
define('ACCOUNT_STATE_DESC' , '顯示縣市');

define('DEFAULT_CURRENCY_TITLE' , '預設貨幣');
define('DEFAULT_CURRENCY_DESC' , '預設貨幣');
define('DEFAULT_LANGUAGE_TITLE' , '預設語系');
define('DEFAULT_LANGUAGE_DESC' , '預設語系');
define('DEFAULT_ORDERS_STATUS_ID_TITLE' , '新訂單的預設狀態');
define('DEFAULT_ORDERS_STATUS_ID_DESC' , '新訂單的預設狀態.');

define('DEFAULT_SHIPPING_STATUS_ID_TITLE' , '預設出貨時程');
define('DEFAULT_SHIPPING_STATUS_ID_DESC' , '預設出貨時程.');

define('SHIPPING_ORIGIN_COUNTRY_TITLE' , '出貨國');
define('SHIPPING_ORIGIN_COUNTRY_DESC' , '出貨單上商店國別.');
define('SHIPPING_ORIGIN_ZIP_TITLE' , '郵遞區號');
define('SHIPPING_ORIGIN_ZIP_DESC' , '出貨單上商店所在郵遞區號.');
define('SHIPPING_MAX_WEIGHT_TITLE' , '輸入運送包裝的最大重量');
define('SHIPPING_MAX_WEIGHT_DESC' , '運送時，通常會有單一包裝的重量限制.');
define('SHIPPING_BOX_WEIGHT_TITLE' , '包裝重量.');
define('SHIPPING_BOX_WEIGHT_DESC' , '中小型包裝的代表性重量');
define('SHIPPING_BOX_PADDING_TITLE' , '大包裝-百分比增加.');
define('SHIPPING_BOX_PADDING_DESC' , '輸入 10 代表 10%');

define('PRODUCT_LIST_FILTER_TITLE' , '顯示商品目錄/廠商資訊 (0=關閉; 1=開啟)');
define('PRODUCT_LIST_FILTER_DESC' , '顯示商品目錄/廠商資訊?');

define('STOCK_CHECK_TITLE' , '檢查庫存量水準');
define('STOCK_CHECK_DESC' , '檢查庫存量水準');

define('ATTRIBUTE_STOCK_CHECK_TITLE' , '檢查商品屬性的庫存量');
define('ATTRIBUTE_STOCK_CHECK_DESC' , '如果商品屬性設定時有設定商品數量,是否為主要依據');

define('STOCK_LIMITED_TITLE' , '庫存自動扣除');
define('STOCK_LIMITED_DESC' , '依顧客訂單數量自動扣除庫存量');
define('STOCK_ALLOW_CHECKOUT_TITLE' , '可以結帳');
define('STOCK_ALLOW_CHECKOUT_DESC' , '當庫存量不足時，仍然可以讓顧客結帳');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE' , '缺貨標示');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC' , '缺貨時會顯示這個標示，讓顧客知道哪一項商品已缺貨');
define('STOCK_REORDER_LEVEL_TITLE' , '安全庫存量');
define('STOCK_REORDER_LEVEL_DESC' , '低於安全庫存量時進貨');

define('STORE_PAGE_PARSE_TIME_TITLE' , '儲存頁面解析時間');
define('STORE_PAGE_PARSE_TIME_DESC' , '儲存頁面解析時間');
define('STORE_PAGE_PARSE_TIME_LOG_TITLE' , '紀錄檔位置');
define('STORE_PAGE_PARSE_TIME_LOG_DESC' , '頁解析時間紀錄檔的資料夾與檔名');
define('STORE_PARSE_DATE_TIME_FORMAT_TITLE' , '紀錄檔日期格式');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC' , '日期格式');

define('DISPLAY_PAGE_PARSE_TIME_TITLE' , '顯示頁解析時間');
define('DISPLAY_PAGE_PARSE_TIME_DESC' , '顯示頁解析時間(儲存頁解析時間必須啟動)');

define('STORE_DB_TRANSACTIONS_TITLE' , '儲存資料庫查詢');
define('STORE_DB_TRANSACTIONS_DESC' , '儲存每頁查詢資料庫的解析時間紀錄(僅適用PHP4)');

define('USE_CACHE_TITLE' , '使用快取');
define('USE_CACHE_DESC' , '使用快取');

define('DIR_FS_CACHE_TITLE' , '快取目錄');
define('DIR_FS_CACHE_DESC' , '快取檔案儲存的目錄');

define('ACCOUNT_OPTIONS_TITLE','會員購物帳號選項');
define('ACCOUNT_OPTIONS_DESC','是否開啟會員購物登入選項 ?<br>可以選擇需加入會員(customer)與非會員購物(guest)或是全部開啟(all)');

define('EMAIL_TRANSPORT_TITLE' , '電子郵件寄信方式');
define('EMAIL_TRANSPORT_DESC' , '定義使用本地主機(Unix-like) sendmail 或經由 TCP/IP 連接的網路 SMTP 主機送信，如果您的主機是運作在 Windows 或是 MacOS 底下，請設定為 SMTP.');

define('EMAIL_LINEFEED_TITLE' , '電子郵件換行');
define('EMAIL_LINEFEED_DESC' , '定義電子郵件表頭所使用的換行字元.');
define('EMAIL_USE_HTML_TITLE' , '使用 MIME HTML 格式寄電子郵件');
define('EMAIL_USE_HTML_DESC' , '傳送 HTML 格式的電子郵件');
define('ENTRY_EMAIL_ADDRESS_CHECK_TITLE' , '利用 DNS 檢查電子郵件地址');
define('ENTRY_EMAIL_ADDRESS_CHECK_DESC' , '利用 DNS 主機檢查電子郵件地址');
define('SEND_EMAILS_TITLE' , '傳送電子郵件');
define('SEND_EMAILS_DESC' , '寄出電子郵件');
define('SENDMAIL_PATH_TITLE' , 'sendmail 的路徑');
define('SENDMAIL_PATH_DESC' , '如果您使用 sendmail, 你必須指定正確路徑 (一般為: /usr/bin/sendmail):');
define('SMTP_MAIN_SERVER_TITLE' , 'SMTP 伺服器位址');
define('SMTP_MAIN_SERVER_DESC' , '輸入SMTP 位址.');
define('SMTP_BACKUP_SERVER_TITLE' , '備用smtp伺服器');
define('SMTP_BACKUP_SERVER_DESC' , '輸入備用SMTP 位址.');
define('SMTP_USERNAME_TITLE' , 'SMTP 帳號');
define('SMTP_USERNAME_DESC' , '輸入 SMTP 帳號.');
define('SMTP_PASSWORD_TITLE' , 'SMTP 密碼');
define('SMTP_PASSWORD_DESC' , '輸入  SMTP 密碼.');
define('SMTP_AUTH_TITLE' , 'SMTP 安全確認');
define('SMTP_AUTH_DESC' , '你的 SMTP 伺服器需要安全確認?');
define('SMTP_PORT_TITLE' , 'SMTP 連接阜');
define('SMTP_PORT_DESC' , '輸入 SMTP 連接阜 (一般: 25)');

//Constants for contact_us
define('CONTACT_US_EMAIL_ADDRESS_TITLE' , '聯絡'.STORE_NAME.'- 郵件地址');
define('CONTACT_US_EMAIL_ADDRESS_DESC' , '輸入郵件地址 當客戶使用"聯絡我們"時的郵件地址');
define('CONTACT_US_NAME_TITLE' , '聯絡'.STORE_NAME.' - 郵件寄件人名稱');
define('CONTACT_US_NAME_DESC' , '輸入郵件寄件人名稱,當客戶使用"聯絡我們"時的郵件寄件人名稱');
define('CONTACT_US_FORWARDING_STRING_TITLE' , '聯絡'.STORE_NAME.' - 轉遞郵件地址');
define('CONTACT_US_FORWARDING_STRING_DESC' , '輸入轉遞郵件地址 (以 " , "區隔) 當客戶使用"聯絡我們"時的轉遞郵件地址');
define('CONTACT_US_REPLY_ADDRESS_TITLE' , '聯絡'.STORE_NAME.' - 接收回信的郵件地址');
define('CONTACT_US_REPLY_ADDRESS_DESC' , '輸入接收回信的郵件地址.');
define('CONTACT_US_REPLY_ADDRESS_NAME_TITLE' , '聯絡'.STORE_NAME.' - 接收回信的郵件地址 , 名稱');
define('CONTACT_US_REPLY_ADDRESS_NAME_DESC' , '接收回信的郵件地址的名稱.');
define('CONTACT_US_EMAIL_SUBJECT_TITLE' , '聯絡'.STORE_NAME.' - 郵件摘要');
define('CONTACT_US_EMAIL_SUBJECT_DESC' , '輸入郵件摘要.');

//Constants for support system
define('EMAIL_SUPPORT_ADDRESS_TITLE' , '歡迎光臨'.STORE_NAME.' - 郵件地址');
define('EMAIL_SUPPORT_ADDRESS_DESC' , '輸入郵件地址 (使用時機-新增帳號, 變更密碼).');
define('EMAIL_SUPPORT_NAME_TITLE' , '歡迎光臨'.STORE_NAME.' - 郵件寄件人名稱');
define('EMAIL_SUPPORT_NAME_DESC' , '輸入郵件寄件人名稱 (使用時機-新增帳號, 變更密碼).');
define('EMAIL_SUPPORT_FORWARDING_STRING_TITLE' , '歡迎光臨'.STORE_NAME.' - 轉遞郵件地址');
define('EMAIL_SUPPORT_FORWARDING_STRING_DESC' , '輸入轉遞郵件地址 (以 " , "區隔) (使用時機-新增帳號, 變更密碼)');
define('EMAIL_SUPPORT_REPLY_ADDRESS_TITLE' , '歡迎光臨'.STORE_NAME.' - 接收回信的郵件地址');
define('EMAIL_SUPPORT_REPLY_ADDRESS_DESC' , '輸入接收回信的郵件地址.');
define('EMAIL_SUPPORT_REPLY_ADDRESS_NAME_TITLE' , '歡迎光臨'.STORE_NAME.' - 接收回信的郵件寄件人名稱');
define('EMAIL_SUPPORT_REPLY_ADDRESS_NAME_DESC' , '輸入接收回信的郵件寄件人名稱.');
define('EMAIL_SUPPORT_SUBJECT_TITLE' , '歡迎光臨'.STORE_NAME.' - 郵件主旨摘要');
define('EMAIL_SUPPORT_SUBJECT_DESC' , '輸入郵件主旨摘要.');

//Constants for Billing system
define('EMAIL_BILLING_ADDRESS_TITLE' , ''.STORE_NAME.' - 郵件地址');
define('EMAIL_BILLING_ADDRESS_DESC' , '輸入郵件地址 (使用時機-訂單確認, 出貨狀態變更,..).');
define('EMAIL_BILLING_NAME_TITLE' , ''.STORE_NAME.' - 郵件寄件人名稱');
define('EMAIL_BILLING_NAME_DESC' , '輸入郵件寄件人名稱 (使用時機-訂單確認, 出貨狀態變更,..).');
define('EMAIL_BILLING_FORWARDING_STRING_TITLE' , ''.STORE_NAME.' - 轉遞郵件地址');
define('EMAIL_BILLING_FORWARDING_STRING_DESC' , '輸入轉遞郵件地址 (以 " , "區隔)(使用時機-訂單確認, 出貨狀態變更,..)');
define('EMAIL_BILLING_REPLY_ADDRESS_TITLE' , ''.STORE_NAME.' - 接收回信的郵件地址');
define('EMAIL_BILLING_REPLY_ADDRESS_DESC' , '輸入接收回信的郵件地址.');
define('EMAIL_BILLING_REPLY_ADDRESS_NAME_TITLE' , ''.STORE_NAME.' - 接收回信的郵件寄件人名稱');
define('EMAIL_BILLING_REPLY_ADDRESS_NAME_DESC' , '輸入接收回信的郵件寄件人名稱.');
define('EMAIL_BILLING_SUBJECT_TITLE' , ''.STORE_NAME.' - 郵件主旨摘要');
define('EMAIL_BILLING_SUBJECT_DESC' , '輸入郵件主旨摘要');
define('EMAIL_BILLING_SUBJECT_ORDER_TITLE',''.STORE_NAME.' - 訂單郵件主旨摘要');
define('EMAIL_BILLING_SUBJECT_ORDER_DESC','輸入訂單郵件主旨摘要(像是 <b>訂單資訊 {$nr},{$date}</b>) 註: 可以使用, {$nr},{$date},{$firstname},{$lastname}');


define('DOWNLOAD_ENABLED_TITLE' , '啟動下載');
define('DOWNLOAD_ENABLED_DESC' , '啟動產品下載.');
define('DOWNLOAD_BY_REDIRECT_TITLE' , '轉址下載');
define('DOWNLOAD_BY_REDIRECT_DESC' , '使用瀏覽器轉向下載，非 Unix 系統無法使用.');
define('DOWNLOAD_MAX_DAYS_TITLE' , '到期日(天數)');
define('DOWNLOAD_MAX_DAYS_DESC' , '設定下載過期天數，輸入 0 代表沒有期限.');
define('DOWNLOAD_MAX_COUNT_TITLE' , '最大下載數');
define('DOWNLOAD_MAX_COUNT_DESC' , '設定最大下載數，輸入 0 代表不可下載.');

define('GZIP_COMPRESSION_TITLE' , '使用 GZip 壓縮');
define('GZIP_COMPRESSION_DESC' , '使用 HTTP GZip 壓縮.');
define('GZIP_LEVEL_TITLE' , '壓縮等級');
define('GZIP_LEVEL_DESC' , '使用的壓縮等級 0-9 (0 = 最小, 9 = 最大).');

define('SESSION_WRITE_DIRECTORY_TITLE' , 'Session 儲存目錄');
define('SESSION_WRITE_DIRECTORY_DESC' , '如果你的 session 是儲存於檔案，則會放到此目錄.');
define('SESSION_FORCE_COOKIE_USE_TITLE' , '強迫 Cookie 使用');
define('SESSION_FORCE_COOKIE_USE_DESC' , '當只有 cookie 啟動時，強迫使用 session.');
define('SESSION_CHECK_SSL_SESSION_ID_TITLE' , '檢查 SSL Session ID');
define('SESSION_CHECK_SSL_SESSION_ID_DESC' , '在每一個加密網頁(https)確認 SSL_SESSION_ID.');
define('SESSION_CHECK_USER_AGENT_TITLE' , '檢查使用者瀏覽器種類');
define('SESSION_CHECK_USER_AGENT_DESC' , '每一網頁確認瀏覽器種類.');
define('SESSION_CHECK_IP_ADDRESS_TITLE' , '檢查 IP 位址');
define('SESSION_CHECK_IP_ADDRESS_DESC' , '每一網頁檢查使用者 IP 位址.');
define('SESSION_BLOCK_SPIDERS_TITLE' , '拒絕搜尋引擎機器人建立 Sessions');
define('SESSION_BLOCK_SPIDERS_DESC' , '拒絕已知搜尋引擎之機器人(spiders or robots)建立 session，如 google).');
define('SESSION_RECREATE_TITLE' , '重新建立 Session');
define('SESSION_RECREATE_DESC' , '當使用者登入或新增帳號時，重建 session 以新增 session ID (PHP 版本必須為 4.1 以上).');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE' , '顯示購物合約');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC' , '結帳時顯示購物合約');

define('META_MIN_KEYWORD_LENGTH_TITLE' , '最少 meta-關鍵字數');
define('META_MIN_KEYWORD_LENGTH_DESC' , '最少 meta-關鍵字數 (商品說明)');
define('META_KEYWORDS_NUMBER_TITLE' , 'meta-關鍵字數量');
define('META_KEYWORDS_NUMBER_DESC' , '關鍵字數量');
define('META_AUTHOR_TITLE' , 'author');
define('META_AUTHOR_DESC' , '<meta name="author">');
define('META_PUBLISHER_TITLE' , 'publisher');
define('META_PUBLISHER_DESC' , '<meta name="publisher">');
define('META_COMPANY_TITLE' , 'company');
define('META_COMPANY_DESC' , '<meta name="conpany">');
define('META_TOPIC_TITLE' , 'page-topic');
define('META_TOPIC_DESC' , '<meta name="page-topic">');
define('META_REPLY_TO_TITLE' , 'reply-to');
define('META_REPLY_TO_DESC' , '<meta name="reply-to">');
define('META_REVISIT_AFTER_TITLE' , 'revisit-after');
define('META_REVISIT_AFTER_DESC' , '<meta name="revisit-after">');
define('META_ROBOTS_TITLE' , 'robots');
define('META_ROBOTS_DESC' , '<meta name="robots">');
define('META_DESCRIPTION_TITLE' , 'Description');
define('META_DESCRIPTION_DESC' , '<meta name="description">');
define('META_KEYWORDS_TITLE' , 'Keywords');
define('META_KEYWORDS_DESC' , '<meta name="keywords">');

define('MODULE_PAYMENT_INSTALLED_TITLE' , '已啟用的付款模組');
define('MODULE_PAYMENT_INSTALLED_DESC' , '付款模組列表，每一個模組以分號隔開，系統會自動更新，不需手動修改 (例如: cc.php;cod.php;paypal.php)');
define('MODULE_ORDER_TOTAL_INSTALLED_TITLE' , '已啟用的訂單總計模組');
define('MODULE_ORDER_TOTAL_INSTALLED_DESC' , '訂單總計模組列表，每一個模組以分號隔開，系統會自動更新，不需手動修改 (例如: ot_subtotal.php;ot_tax.php;ot_shipping.php;ot_total.php)');
define('MODULE_SHIPPING_INSTALLED_TITLE' , '已啟用的出貨模組');
define('MODULE_SHIPPING_INSTALLED_DESC' , '運送模組列表，每一個模組以分號隔開，系統會自動更新，不需手動修改 (例如: ups.php;flat.php;item.php)');

define('CACHE_LIFETIME_TITLE','快取時間');
define('CACHE_LIFETIME_DESC','快取時間,已秒為單位');
define('CACHE_CHECK_TITLE','快取變動檢查');
define('CACHE_CHECK_DESC','快取變動檢查');

define('PRODUCT_REVIEWS_VIEW_TITLE','商品評論');
define('PRODUCT_REVIEWS_VIEW_DESC','每頁顯示幾篇商品評論');

define('DELETE_GUEST_ACCOUNT_TITLE','刪除非會員購物帳號');
define('DELETE_GUEST_ACCOUNT_DESC','非會員購物帳號於購物完成後刪除 ? (訂單資訊仍會保留)');

define('USE_SPAW_TITLE','使用所見即所得模組');
define('USE_SPAW_DESC','使用所見即所得模組');

define('PRICE_IS_BRUTTO_TITLE','Gross Admin');
define('PRICE_IS_BRUTTO_DESC','慣用的價格與稅');

define('PRICE_PRECISION_TITLE','Gross/Net precision');
define('PRICE_PRECISION_DESC','Gross/Net precision');

define('CHECK_CLIENT_AGENT_TITLE','檢查瀏覽器?');
define('CHECK_CLIENT_AGENT_DESC','移除搜索蜘蛛所建立sessions');
define('SHOW_IP_LOG_TITLE','顯示紀錄結帳時客戶IP');
define('SHOW_IP_LOG_DESC','結帳時頁面顯示 "您目前的IP位置已經紀錄 "?');

define('ACTIVATE_GIFT_SYSTEM_TITLE','啟動禮券系統');
define('ACTIVATE_GIFT_SYSTEM_DESC','啟動禮券系統');

define('ACTIVATE_SHIPPING_STATUS_TITLE','顯示出貨狀態');
define('ACTIVATE_SHIPPING_STATUS_DESC','顯示出貨狀態? (可以針對不同商品設定不同的出貨時程. 開啟後新增商品時會顯示一個 
<b>出貨時程</b> 讓你輸入)');

define('SECURITY_CODE_LENGTH_TITLE','安全密碼長度(禮券)');
define('SECURITY_CODE_LENGTH_DESC','安全密碼長度 (禮券)');

define('IMAGE_QUALITY_TITLE','圖片壓縮品質');
define('IMAGE_QUALITY_DESC','圖片壓縮品質 (0= 高壓縮比, 100=最高品質)');

define('GROUP_CHECK_TITLE','顯示商品種類時檢查客戶位階');
define('GROUP_CHECK_DESC','只有特定的客戶群有權檢視此商品類 (開啟後, 輸入欄位會在建立商品種類與新增商品時顯示');

define('ACTIVATE_NAVIGATOR_TITLE','使用商品導行列?');
define('ACTIVATE_NAVIGATOR_DESC','開啟/關閉 商品導行列在商品列表product_info顯示, (在一些租用的空間為了改善系統速度可以設定成關閉)');

define('QUICKLINK_ACTIVATED_TITLE','開啟多連結/複製商品');
define('QUICKLINK_ACTIVATED_DESC','開啟多連結/複製商品, 在商品上架時可以使用複製商品的動作,可以一次選擇多項種類來複製商品');
define('CURRENT_MODULES_TITLE','商店首頁'); 
define('CURRENT_MODULES_DESC','設定商店首頁'); 
define('PHP_DEBUG_TITLE','PHP除錯');  
define('PHP_DEBUG_DESC','開啟php除錯?'); 
define('SQL_DEBUG_TITLE','SQL除錯'); 
define('SQL_DEBUG_DESC','開啟SQL除錯?'); 
define('MORE_PICS_TITLE','多圖模組');  
define('MORE_PICS_DESC','如果數量大於零，每個商品可以上傳與顯示多個圖片');  
define('DOWNLOAD_UNALLOWED_PAYMENT_TITLE', '不允許使用的下載商品付款方式');
define('DOWNLOAD_UNALLOWED_PAYMENT_DESC', '可下載商品不使用的付款方式，以逗點分隔，例如 banktransfer,cod,invoice,moneyorder');
define('DOWNLOAD_MIN_ORDERS_STATUS_TITLE', '訂單狀態下限');
define('DOWNLOAD_MIN_ORDERS_STATUS_DESC', '要下載檔案，訂單狀態至少為何？');
define('SUCCESS_LAST_MOD', '更新完成');
define('SERVER_INFO_TITLE', '伺服器資訊'); 
define('SERVER_INFO_DESC', '伺服器資訊');
define('WHOS_ONLINE_TITLE', '誰在線上'); 
define('WHOS_ONLINE_DESC', '誰在線上');
define('FILE_MANAGER_TITLE', '檔案總管'); 
define('FILE_MANAGER_DESC', '檔案總管');
   
define('CATEGORIES_TITLE','目錄 / 商品'); 
define('CATEGORIES_DESC', '目錄 / 商品');
define('NEW_ATTRIBUTES_TITLE','商品屬性管理'); 
define('NEW_ATTRIBUTES_DESC','商品屬性管理'); 
define('PRODUCTS_ATTRIBUTES_TITLE','商品類別設定');
define('PRODUCTS_ATTRIBUTES_DESC','商品類別設定');
define('MANUFACTURERS_TITLE', '廠商');
define('MANUFACTURERS_DESC','廠商');
define('REVIEWS_TITLE','商品回應評註'); 
define('REVIEWS_DESC', '商品回應評註');
define('SPECIALS_TITLE', '特價商品');
define('SPECIALS_DESC','特價商品');
define('PRODUCTS_EXPECTED_TITLE', '即將上市商品');
define('PRODUCTS_EXPECTED_DESC','即將上市商品'); 
define('NEWS_CATEGORIES_TITLE','新聞管理'); 
define('NEWS_CATEGORIES_DESC', '新聞管理');
define('CONFIGURATIONGID1_TITLE','我的商店'); 
define('CONFIGURATIONGID1_DESC', '我的商店');
define('CONFIGURATIONGID2_TITLE','最小值設定');
define('CONFIGURATIONGID2_DESC', '最小值設定'); 
define('CONFIGURATIONGID3_TITLE','最大值設定'); 
define('CONFIGURATIONGID3_DESC', '最大值設定');
define('CONFIGURATIONGID4_TITLE','圖片設定');
define('CONFIGURATIONGID4_DESC', '圖片設定'); 
define('CONFIGURATIONGID5_TITLE','會員註冊表單設定'); 
define('CONFIGURATIONGID5_DESC', '會員註冊表單設定');
define('CONFIGURATIONGID6_TITLE','模組選項'); 
define('CONFIGURATIONGID6_DESC','模組選項'); 
define('CONFIGURATIONGID7_TITLE','運送設定');
define('CONFIGURATIONGID7_DESC','運送設定');  
define('CONFIGURATIONGID8_TITLE','首頁商品列表設定'); 
define('CONFIGURATIONGID8_DESC','首頁商品列表設定'); 
define('CONFIGURATIONGID9_TITLE','庫存設定'); 
define('CONFIGURATIONGID9_DESC','庫存設定'); 
define('CONFIGURATIONGID10_TITLE','Logging');
define('CONFIGURATIONGID10_DESC','Logging');
define('CONFIGURATIONGID11_TITLE','快取設定');
define('CONFIGURATIONGID11_DESC','快取設定');
define('CONFIGURATIONGID12_TITLE','E-Mail 選項');
define('CONFIGURATIONGID12_DESC','E-Mail 選項');
define('CONFIGURATIONGID13_TITLE','下載設定');
define('CONFIGURATIONGID13_DESC','下載設定');
define('CONFIGURATIONGID14_TITLE','GZip Compression');
define('CONFIGURATIONGID14_DESC','GZip Compression');
define('CONFIGURATIONGID15_TITLE','Sessions');
define('CONFIGURATIONGID15_DESC','Sessions');
define('CONFIGURATIONGID16_TITLE','Meta-Tags');
define('CONFIGURATIONGID16_DESC','Meta-Tags');
define('CONFIGURATIONGID17_TITLE','額外模組設定');
define('CONFIGURATIONGID17_DESC','額外模組設定');
define('ORDERS_STATUS_TITLE', '訂單狀態設定');
define('ORDERS_STATUS_DESC', '訂單狀態設定');
define('SHIPPING_STATUS_TITLE', '出貨時程設定');
define('SHIPPING_STATUS_DESC', '出貨時程設定');
define('CUSTOMERS_TITLE', '會員客戶設定');
define('CUSTOMERS_DESC','會員客戶設定'); 
define('CUSTOMERS_STATUS_TITLE', '會員層級設定');
define('CUSTOMERS_STATUS_DESC','會員層級設定'); 
define('ORDERS_TITLE','訂單管理');
define('ORDERS_DESC','訂單管理');
define('COUPON_ADMIN_TITLE', '折價券管理');
define('COUPON_ADMIN_DESC','折價券管理'); 
define('GV_QUEUE_TITLE', 'GV_QUEUE');
define('GV_QUEUE_DESC','GV_QUEUE');
define('GV_MAIL_TITLE', 'GV_MAIL');
define('GV_MAIL_DESC', 'GV_MAIL');
define('GV_SENT_TITLE','GV_SENT'); 
define('GV_SENT_DESC', 'GV_SENT');
define('LANGUAGES_TITLE','語系'); 
define('LANGUAGES_DESC','語系');
define('COUNTRIES_TITLE','國家'); 
define('COUNTRIES_DESC','國家'); 
define('CURRENCIES_TITLE','貨幣'); 
define('CURRENCIES_DESC','貨幣'); 
define('ZONES_TITLE','地區'); 
define('ZONES_DESC','地區'); 
define('GEO_ZONES_TITLE', '稅區');
define('GEO_ZONES_DESC','稅區'); 
define('TAX_CLASSES_TITLE','稅別');
define('TAX_CLASSES_DESC','稅別');
define('TAX_RATES_TITLE','稅率');
define('TAX_RATES_DESC','稅率');  
define('PAYMENT_TITLE','付款模組');
define('PAYMENT_DESC','付款模組');
define('SHIPPING_TITLE', '運送模組');
define('SHIPPING_DESC', '運送模組');
define('ORDER_TOTAL_TITLE', '總計模組');
define('ORDER_TOTAL_DESC', '總計模組');
define('MODULE_EXPORT_TITLE','圖片重製模組'); 
define('MODULE_EXPORT_DESC', '圖片重製模組');
define('STATS_PRODUCTS_VIEWED_TITLE', '商品瀏覽排行');
define('STATS_PRODUCTS_VIEWED_DESC', '商品瀏覽排行');
define('STATS_PRODUCTS_PURCHASED_TITLE','商品銷售排行');
define('STATS_PRODUCTS_PURCHASED_DESC','商品銷售排行');
define('STATS_CUSTOMERS_TITLE', '客戶購物排行榜');
define('STATS_CUSTOMERS_DESC','客戶購物排行榜'); 
define('SALES_REPORT_TITLE', '銷售報表');
define('SALES_REPORT_DESC', '銷售報表');
define('MODULE_NEWSLETTER_TITLE', '電子報管理');
define('MODULE_NEWSLETTER_DESC','電子報管理');
define('CONTENT_MANAGER_TITLE','內容管理');
define('CONTENT_MANAGER_DESC', '內容管理');
define('BACKUP_TITLE','資料庫備份');
define('BACKUP_DESC','資料庫備份'); 
define('BANNER_MANAGER_TITLE','廣告管理'); 
define('BANNER_MANAGER_DESC', '廣告管理');
define('CATEGORIES_NOTE_TITLE','目錄商品管理大項'); 
define('CATEGORIES_NOTE_DESC', '目錄商品管理大項');
define('CONFIGURATION_NOTE_TITLE','系統設定大項'); 
define('CONFIGURATION_NOTE_DESC', '系統設定大項');
define('CUSTOMERS_NOTE_TITLE','會員客戶大項'); 
define('CUSTOMERS_NOTE_DESC', '會員客戶大項');
define('GV_ADMIN_NOTE_TITLE','禮巻大項'); 
define('GV_ADMIN_NOTE_DESC', '禮巻大項');
define('LOCALIZATION_NOTE_TITLE','語言貨幣大項'); 
define('LOCALIZATION_NOTE_DESC', '語言貨幣大項');
define('MODULES_NOTE_TITLE','外掛模組大項'); 
define('MODULES_NOTE_DESC', '外掛模組大項');
define('REPORTS_NOTE_TITLE','各類報表大項'); 
define('REPORTS_NOTE_DESC', '各類報表大項');
define('TOOLS_NOTE_TITLE','系統工具大項'); 
define('TOOLS_NOTE_DESC', '系統工具大項');
define('DB_LAST_RESTORE_TITLE', '資料庫備份'); 
define('DB_LAST_RESTORE_DESC', '資料庫備份');
define('MODULE_EXPORT_INSTALLED_TITLE', '輸出模組'); 
define('MODULE_EXPORT_INSTALLED_DESC', '輸出模組'); 
define('SHOPPING_TO_SHOW_TITLE','轉為報價平台');  
define('SHOPPING_TO_SHOW_DESC','轉為報價平台'); 
define('BUY_MORE_TITLE','一次購足');
define('BUY_MORE_DESC','一次購足');
define('QUOTES_ORDERS_TITLE','報價管理'); 
define('QUOTES_ORDERS_DESC','報價管理'); 
define('PRODUCT_IMAGE_FIXED_SIZE_TITLE' , '變更圖片尺寸');
define('PRODUCT_IMAGE_FIXED_SIZE_DESC' , '變更圖片尺寸');
define('PRODUCT_IMAGE_FIXED_SIZE_BACKGROUND_TITLE' , '變更圖片尺寸背景顏色');
define('PRODUCT_IMAGE_FIXED_SIZE_BACKGROUND_DESC' , '變更圖片尺寸背景顏色');
define('USE_INVOICE_TITLE','使用統一發票');  
define('USE_INVOICE_DESC','使用統一發票'); 
define('XSELL_PRODUCTS_TITLE','額外推薦商品');  
define('XSELL_PRODUCTS_DESC','額外推薦商品'); 
define('MODULE_IMAGE_PROCESS_STATUS_TITLE','圖片重製模組'); 
define('MODULE_IMAGE_PROCESS_STATUS_DESC','圖片重製模組');  
define('STATS_STOCK_WARNING_TITLE','庫存報告'); 
define('STATS_STOCK_WARNING_DESC','庫存報告'); 
define('LAYOUT_CONTROLLER_TITLE','區塊配置'); 
define('LAYOUT_CONTROLLER_DESC','區塊配置'); 
define('LOGO_TITLE','Logo');  
define('LOGO_DESC','Logo');  
define('ACCOUNT_LASTNAME_TITLE','檢查暱稱是否重複');  
define('ACCOUNT_LASTNAME_DESC','檢查暱稱是否重複(使用於討論區)');  



//for twe30
define('MAX_DISPLAY_INDEX_NEWS_TITLE','顯示幾則新聞'); 
define('MAX_DISPLAY_INDEX_NEWS_DESC','首頁新聞模組最多顯示幾則新聞?');  
define('MAX_DISPLAY_INDEX_NEW_PRODUCTS_PER_ROW_TITLE','每一列可列多少個最新商品'); 
define('MAX_DISPLAY_INDEX_NEW_PRODUCTS_PER_ROW_DESC','首頁每一列可列多少個最新商品?');  
define('MAX_DISPLAY_INDEX_NEW_PRODUCTS_TITLE','顯示幾個最新商品'); 
define('MAX_DISPLAY_INDEX_NEW_PRODUCTS_DESC','首頁顯示多少個最新商品?');  
define('MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS_PER_ROW_TITLE','每一列可列多少個特價商品'); 
define('MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS_PER_ROW_DESC','首頁每一列可列多少個特價商品?');  
define('MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS_TITLE','顯示幾個特價商品'); 
define('MAX_DISPLAY_INDEX_SPECIAL_PRODUCTS_DESC','首頁顯示多少個特價商品?');  
define('MAX_DISPLAY_INDEX_BESTSELLERS_PER_ROW_TITLE','每一列可列多少個暢銷商品'); 
define('MAX_DISPLAY_INDEX_BESTSELLERS_PER_ROW_DESC','首頁每一列可列多少個暢銷商品?');  
define('MAX_DISPLAY_INDEX_BESTSELLERS_TITLE','顯示幾個暢銷商品'); 
define('MAX_DISPLAY_INDEX_BESTSELLERS_DESC','首頁可列多少個暢銷商品?');  
define('MAX_DISPLAY_INDEX_FEATURED_PER_ROW_TITLE','每一列可列多少個推薦商品'); 
define('MAX_DISPLAY_INDEX_FEATURED_PER_ROW_DESC','首頁推薦商品模組每一列可列多少個暢銷商品?');  
define('MAX_DISPLAY_INDEX_FEATURED_TITLE','顯示幾個推薦商品'); 
define('MAX_DISPLAY_INDEX_FEATURED_DESC','首頁可列多少個推薦商品?');  
define('MYSQL_TITLE','phpmyadmin'); 
define('MYSQL_DESC','phpmyadmin'); 

?>