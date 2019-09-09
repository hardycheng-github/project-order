<?php
/*
  $Id: gv_faq.php,v 1.1.1.1.2.2 2003/05/04 12:24:25 oldpa Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org
   (c) 2003	 xtcommerce  www.xt-commerce.com   

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', '禮券 FAQ');
define('HEADING_TITLE', '禮券 FAQ');

define('TEXT_INFORMATION', '<a name="Top"></a>
  <a href="'.twe_href_link(FILENAME_GV_FAQ,'faq_item=1','NONSSL').'">禮券購買方式</a><br>
  <a href="'.twe_href_link(FILENAME_GV_FAQ,'faq_item=2','NONSSL').'">怎樣郵寄禮券</a><br>
  <a href="'.twe_href_link(FILENAME_GV_FAQ,'faq_item=3','NONSSL').'">禮券購買之後</a><br>
  <a href="'.twe_href_link(FILENAME_GV_FAQ,'faq_item=4','NONSSL').'">怎樣兌換禮券</a><br>
  <a href="'.twe_href_link(FILENAME_GV_FAQ,'faq_item=5','NONSSL').'">禮券問題解決</a><br>
');
switch ($_GET['faq_item']) {
  case '1':
define('SUB_HEADING_TITLE','禮券購買方式.');
define('SUB_HEADING_TEXT','禮券的購買就像在我們的商店裡其他項目相同。您可使用標準付款方式購買。 
  一旦購買禮券我們會將額度添加到您自己的個人禮品券帳戶。
  如果您的禮品券帳戶裡有禮券額度，目前的額度將顯示在購物籃裡，並提供了一個鏈接到一個頁面，您可以通過電子郵件發送禮券給您的親朋好友。');
  break;
  case '2':
define('SUB_HEADING_TITLE','怎樣郵寄禮券.');
define('SUB_HEADING_TEXT','您需要到我們發送禮券頁來發送禮券給親朋好友。您可以在每個網頁上的購物籃裡找到鏈接。 
  當您發送禮品券，您需要注意如下。 
  >>親朋好友的姓名。 
  >>親朋好友的電子郵件地址。 
  >>發送的額度。（請注意您禮券帳戶的餘額） 
  >>商店會Email此消息給親朋好友。 
  請確保您已輸入的所有資料正確。');  
  break;
  case '3':
  define('SUB_HEADING_TITLE','禮券購買之後.');
  define('SUB_HEADING_TEXT','如果您的禮品券帳戶有額度，您可以使用這些額度購買商店其他物品。
  在結帳過程中，額外的選取方塊會出現。勾選此框將這些額度用於購買結算。 
  請注意，您仍然必須選擇其他付款方式，如果禮券額度不足，將以其他付款方式支付您的購買。 
  如果你有更多的額度在您的禮券帳戶裡並超過總費用，餘額將會留存在您的禮券帳戶裡。');
  break;
  case '4':
  define('SUB_HEADING_TITLE','怎樣兌換禮券.');
  define('SUB_HEADING_TEXT','如果您收到包含說明與是誰發出給您的的禮券通知，其中也將包含禮券號碼。
  您可以兌換禮券的方式。 
   透過點擊電子郵件中的鏈接。這將引導您到商店的兌換網頁。你會被要求創建一個帳戶，然後輸入禮券驗證碼來確認額度。 
   該代碼將被驗證和添加到您的禮券帳戶。然後，您可以使用額度來購買我們的商店的任何產品。');
  break;
  case '5':
  define('SUB_HEADING_TITLE','禮券問題解決.');
  define('SUB_HEADING_TEXT','如果對商店禮券系統有任何疑問, 
  請利用商店電子郵件 : '. STORE_OWNER_EMAIL_ADDRESS . '. 同時請確定信件中有足夠的相關資訊 
  . ');
  break;
  default:
  define('SUB_HEADING_TITLE','禮券 FAQ');
  define('SUB_HEADING_TEXT','請選擇上方任一項問題連結.');

  }
?>