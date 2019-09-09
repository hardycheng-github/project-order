<?php
/* --------------------------------------------------------------
   $Id: file_manager.php,v 1.1 2003/12/19 13:19:08 fanta2k Exp $   

   TWE-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(file_manager.php,v 1.17 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (file_manager.php,v 1.4 2003/08/14); www.nextcommerce.org

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('HEADING_TITLE', '檔案總管');

define('TABLE_HEADING_FILENAME', '檔名');
define('TABLE_HEADING_SIZE', '大小');
define('TABLE_HEADING_PERMISSIONS', '權限');
define('TABLE_HEADING_USER', '使用者');
define('TABLE_HEADING_GROUP', '群組');
define('TABLE_HEADING_LAST_MODIFIED', '最後修改日期');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_INFO_HEADING_UPLOAD', '上傳');
define('TEXT_FILE_NAME', '檔名：');
define('TEXT_FILE_SIZE', '大小：');
define('TEXT_FILE_CONTENTS', '內容：');
define('TEXT_LAST_MODIFIED', '最後修改日期：');
define('TEXT_NEW_FOLDER', '新增資料夾');
define('TEXT_NEW_FOLDER_INTRO', '輸入新資料夾名稱：');
define('TEXT_DELETE_INTRO', '確定要刪除這個資料夾？');
define('TEXT_UPLOAD_INTRO', '請選擇上傳的檔案');

define('ERROR_DIRECTORY_NOT_WRITEABLE', '錯誤：資料夾無法寫入，請設定正確的使用者權限給： %s');
define('ERROR_FILE_NOT_WRITEABLE', '錯誤： 檔案無法寫入，請設定正確的使用者權限給： %s');
define('ERROR_DIRECTORY_NOT_REMOVEABLE', '錯誤： 資料夾無法移動，請設定正確的使用者權限給： %s');
define('ERROR_FILE_NOT_REMOVEABLE', '錯誤： 檔案無法移動，請設定正確的使用者權限給： %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', '錯誤： 資料夾不存在: %s');
?>
