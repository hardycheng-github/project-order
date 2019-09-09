<?php
/* -----------------------------------------------------------------------------------------
   $Id: layout_controller.php,v 1.3 2008/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------*/

define('HEADING_TITLE', '佈景檔名稱 :');

define('TABLE_HEADING_LAYOUT_BOX_NAME_LEFT', '檔名');
define('TABLE_HEADING_LAYOUT_BOX_NAME_RIGHT', '檔名');

define('TABLE_HEADING_LAYOUT_BOX_STATUS', '主要狀態');
define('TABLE_HEADING_LAYOUT_BOX_STATUS_SINGLE', '獨立區塊<br />編輯');
define('TABLE_HEADING_LAYOUT_BOX_LOCATION', '縱列');
define('TABLE_HEADING_LAYOUT_BOX_SORT_ORDER', '排序');
define('TABLE_HEADING_LAYOUT_BOX_SORT_ORDER_SINGLE', '獨立區塊<br />狀態');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_INFO_EDIT_INTRO', '請進行更改');
define('TEXT_INFO_LAYOUT_BOX','選擇區塊: ');
define('TEXT_INFO_LAYOUT_BOX_NAME', '區塊名稱:');
define('TEXT_INFO_LAYOUT_BOX_LOCATION','位址:');
define('TEXT_INFO_LAYOUT_BOX_STATUS', '主要狀態: ');
define('TEXT_INFO_LAYOUT_BOX_STATUS_SINGLE', '獨立區塊狀態: ');
define('TEXT_INFO_LAYOUT_BOX_STATUS_INFO','開啟= 1 關閉=0');
define('TEXT_INFO_LAYOUT_BOX_SORT_ORDER', '排序:');
define('TEXT_INFO_LAYOUT_BOX_SORT_ORDER_SINGLE', '獨立區塊排序:');
define('TEXT_INFO_INSERT_INTRO', '請輸入新區塊的資料');
define('TEXT_INFO_DELETE_INTRO', '確定要刪除這個區塊嗎?');
define('TEXT_INFO_HEADING_NEW_BOX', '新區塊');
define('TEXT_INFO_HEADING_EDIT_BOX', '編輯區塊');
define('TEXT_INFO_HEADING_DELETE_BOX', '刪除區塊');
define('TEXT_INFO_DELETE_MISSING_LAYOUT_BOX','刪除在佈景清單裡遺失的區塊: ');
define('TEXT_INFO_DELETE_MISSING_LAYOUT_BOX_NOTE','注意: 這個不會移除檔案所以您可以重新加入區塊到檔案夾裡.<br /><br /><strong>刪除區塊名稱: </strong>');
define('TEXT_INFO_RESET_TEMPLATE_SORT_ORDER','以預設的佈景:twe重設全部的區塊來符合佈景: ');
define('TEXT_INFO_RESET_TEMPLATE_SORT_ORDER_NOTE','這個動作不會移除任何區塊. 這只會複製twe佈景的編排來重設');
define('TEXT_INFO_BOX_DETAILS','區塊詳細資料: ');

////////////////

define('HEADING_TITLE_LAYOUT_TEMPLATE', '網站佈景配置');

define('TABLE_HEADING_LAYOUT_TITLE', '標題');
define('TABLE_HEADING_LAYOUT_VALUE', '數值');
define('TABLE_HEADING_ACTION', '動作');


define('TEXT_MODULE_DIRECTORY', '網站配置檔案夾:');
define('TEXT_INFO_DATE_ADDED', '增入日期:');
define('TEXT_INFO_LAST_MODIFIED', '最後修改日期:');

// layout box text in includes/boxes/layout.php
define('BOX_HEADING_LAYOUT', '配置');
define('BOX_LAYOUT_COLUMNS', '縱列控制');

// file exists
define('TEXT_GOOD_BOX',' ');
define('TEXT_BAD_BOX','<font color="ff0000"><b>不存在</b></font><br />');


// Success message
define('SUCCESS_BOX_DELETED','成功移除佈景的區塊: ');
define('SUCCESS_BOX_RESET','成功重設佈景的區塊設定: ');
define('SUCCESS_BOX_UPDATED','成功更新區塊設定: ');
define('SUCCESS_LOGO_CHANGE','成功更新');

define('TEXT_ON',' 開啟 ');
define('TEXT_OFF',' 關閉 ');
define('TEXT_LEFT',' 左 ');
define('TEXT_RIGHT',' 右 ');
define('TEXT_CHANGE_LOGO', '更換Logo:');
define('BOXES_PATH', '區塊路徑:');
define('BOXES_FOUND', '發現新區塊(更新資料庫完成):');
define('TEMPLATE_FOUND', '發現新佈景(更新資料庫完成):');
define('LEFT_WIDTH', '左欄寬:');
define('WIDTH','總寬:');
define('RIGHT_WIDTH', '右欄寬:');
define('CENTER_WIDTH', '中央寬:');
define('TEXT_CENTER_LEFT', '中左');
define('TEXT_CENTER_RIGHT', '中右');
define('TEXT_CENTER', '中央');
define('MODULES_PATH', '中央模組路徑');

?>