<?php
/* --------------------------------------------------------------
   $Id: content_manager.php,v 1.2 2004/04/01 14:19:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (content_manager.php,v 1.8 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   --------------------------------------------------------------*/
   
 define('HEADING_TITLE','內容管理');
 define('HEADING_CONTENT','網站訊息內容管理');
 define('HEADING_PRODUCTS_CONTENT','商品內容');
 define('TABLE_HEADING_CONTENT_ID','內容 ID');
 define('TABLE_HEADING_CONTENT_TITLE','標題');
 define('TABLE_HEADING_CONTENT_FILE','檔案');
 define('TABLE_HEADING_CONTENT_STATUS','區塊狀態');
 define('TABLE_HEADING_CONTENT_BOX','區塊');
 define('TABLE_HEADING_PRODUCTS_ID','ID');
 define('TABLE_HEADING_PRODUCTS','商品介紹');
 define('TABLE_HEADING_PRODUCTS_CONTENT_ID','ID');
 define('TABLE_HEADING_LANGUAGE','語言');
 define('TABLE_HEADING_CONTENT_NAME','名稱/檔案名稱');
 define('TABLE_HEADING_CONTENT_LINK','連結');
 define('TABLE_HEADING_CONTENT_HITS','檢視');
 define('TABLE_HEADING_CONTENT_GROUP','連結ID');
 define('TABLE_HEADING_CONTENT_SORT','排序');
 define('TEXT_YES','是');
 define('TEXT_NO','否');
 define('TABLE_HEADING_CONTENT_ACTION','動作');
 define('TEXT_DELETE_IMG','刪除');
 define('TEXT_EDIT','編輯');
 define('TEXT_PREVIEW','預覽');
 define('CONFIRM_DELETE','刪除內容 ?');
 define('CONTENT_NOTE','<font color="ff0000">*</font> 表示為系統必須檔案,無法刪除!');


 // edit
 define('TEXT_LANGUAGE','語言:');
 define('TEXT_STATUS','顯示:');
 define('TEXT_STATUS_DESCRIPTION','如果勾選, 在區塊中將會出現連結');
 define('TEXT_TITLE','連結標題:');
 define('TEXT_TITLE_FILE','標題/檔名:');
 define('TEXT_SELECT','-請選擇-');
 define('TEXT_HEADING','內容標題:');
 define('TEXT_CONTENT','內容:');
 define('TEXT_UPLOAD_FILE','上傳檔案:');
 define('TEXT_UPLOAD_FILE_LOCAL','(由本機電腦)');
 define('TEXT_CHOOSE_FILE','選擇檔案:');
 define('TEXT_CHOOSE_FILE_DESC','你可以選擇一個已經存在的檔案.');
 define('TEXT_NO_FILE','刪除選擇');
 define('TEXT_CHOOSE_FILE_SERVER','(如果你已經由FTP上傳檔案至<i>(media/content)</i>, 你可以用下拉選單選擇檔案.');
 define('TEXT_CURRENT_FILE','Current File:');
 define('TEXT_FILE_DESCRIPTION','<b>提示:</b><br>你也可以上傳 <b>.htlm</b> 或 <b>.htm</b> 取代內容.<br>
  如果你選擇使用上傳之檔案, 則下方內容將會被忽略.<br><br>');
 define('ERROR_FILE','錯誤的檔案格式 (只能是 .html 或 .htm)');
 define('ERROR_TITLE','請輸入標題');
 define('ERROR_COMMENT','請輸入檔案說明!');
 define('TEXT_FILE_FLAG','區塊:');
 define('TEXT_PARENT','主要說明:');
 define('TEXT_PARENT_DESCRIPTION','指向至這篇文件');
 define('TEXT_PRODUCT','商品:');
 define('TEXT_LINK','連結:');
 define('TEXT_GROUP','連結ID:');
 define('TEXT_GROUP_DESC','連結到此內容之ID');

 define('TEXT_CONTENT_DESCRIPTION','用這個內容管理可以位商品增加商品說明 , 例如教學, 商品細節說明, 這內容會顯示在商品簡介的頁面.<br><br>');
 define('TEXT_FILENAME','使用中的檔案:');
 define('TEXT_FILE_DESC','說明內容:');
 define('USED_SPACE','檔案使用空間:');
 define('TABLE_HEADING_CONTENT_FILESIZE','檔案大小');


 ?>
