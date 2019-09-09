<?php
/* --------------------------------------------------------------
   $Id: create_account.php,v 1.2 2004/04/01 14:19:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(create_account.php,v 1.13 2003/05/19); www.oscommerce.com 
   (c) 2003	 nextcommerce (create_account.php,v 1.4 2003/08/14); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
   --------------------------------------------------------------*/

define('NAVBAR_TITLE', '新增帳號');

define('HEADING_TITLE', '新增帳號管理');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>提示:</b></font></small> 如果你已經註冊為會員請按 <a href="%s"><u>這裡</u></a>.');

define('EMAIL_SUBJECT', '歡迎光臨 ' . STORE_NAME);
define('EMAIL_GREET_MR', '親愛的 %s 先生,' . "\n\n");
define('EMAIL_GREET_MS', '親愛的 %s 小姐,' . "\n\n");
define('EMAIL_GREET_NONE', '親愛的 %s' . "\n\n");
define('EMAIL_WELCOME', '我們非常誠摯的歡迎您光臨 <b>' . STORE_NAME . '</b>.' . "\n\n" .'您的帳號尚未啟動,請按下列連結啟動您的帳號'. "\n\n");
define('EMAIL_TEXT', '下列為我們提供<b>線上服務</b>：' . "\n\n" . '<li><b>1.智慧型購物車</b>：'."\n".'          放到購物車的商品，除非您將它們移除或結帳，否則商品將會一直留在購物車內' . "\n\n" . '<li><b>2.個人通訊錄</b>：'."\n".'          我們提供您將購買的商品，直接寄送給通訊錄裡的親友! 例如：當您有親友生日時，我們可以替您將購買的生日禮物直接送到壽星手上' . "\n\n" . '<li><b>3.訂單購物記錄</b>：'."\n".'          您可以隨時登入，查詢已購買商品的最新狀態及紀錄' . "\n\n" . '<li><b>4.商品評論</b>：'."\n".'          分享您的購物經驗或評論您有興趣的商品' . "\n\n".'<li><b>5.商品通知</b>：'."\n".'          商品通知可以讓您隨時掌握我們的商品動態,讓您不錯失特價商品及其他優惠的良機' . "\n\n");
define('EMAIL_CONTACT', '任何線上服務問題，請直接 email 給店長: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>附註:</b>這個電子郵件地址(E-mail address)是由我們的客戶提供，如果您並不願意加入會員，請來信： mailto:' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

?>
