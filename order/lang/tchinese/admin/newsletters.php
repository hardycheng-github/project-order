<?php
/* --------------------------------------------------------------
   $Id: newsletters.php,v 1.1 2003/12/19 13:19:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(newsletters.php,v 1.7 2002/03/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (newsletters.php,v 1.5 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('HEADING_TITLE', '電子報總管');

define('TABLE_HEADING_NEWSLETTERS', '電子報');
define('TABLE_HEADING_SIZE', '大小');
define('TABLE_HEADING_MODULE', '模組');
define('TABLE_HEADING_SENT', '寄送');
define('TABLE_HEADING_STATUS', '狀態');
define('TABLE_HEADING_ACTION', '動作');

define('TEXT_NEWSLETTER_MODULE', '模組：');
define('TEXT_NEWSLETTER_TITLE', '電子報名稱：');
define('TEXT_NEWSLETTER_CONTENT', '內容：');

define('TEXT_NEWSLETTER_DATE_ADDED', '新增日期：');
define('TEXT_NEWSLETTER_DATE_SENT', '寄出日期：');

define('TEXT_INFO_DELETE_INTRO', '確定要刪除這份電子報？');

define('TEXT_PLEASE_WAIT', '請稍後 .. 郵件傳送中 ..<br><br>在完成前請勿中斷操作!');
define('TEXT_FINISHED_SENDING_EMAILS', '傳送郵件完成!');

define('ERROR_NEWSLETTER_TITLE', '錯誤: 電子報標題必須填寫');
define('ERROR_NEWSLETTER_MODULE', '錯誤: 電子報模組必須填寫');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', '錯誤: 請鎖定此電子報後再刪除.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', '錯誤: 請鎖定此電子報後再編輯.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', '錯誤: 請鎖定此電子報後再傳送.');
define('TABLE_HEADING_NEWS_HIST_CS_VALUE','變更');
define('TABLE_HEADING_NEWS_HIST_DATE_ADDED','日期');
define('TEXT_NO_NEWSLETTERS_CS_HISTORY','-不更動-');
?>
