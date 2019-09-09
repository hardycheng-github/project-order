<?php
/* -----------------------------------------------------------------------------------------
   $Id: account_newsletters.php,v 1.5 2005/04/07 23:05:09 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(account_newsletters.php,v 1.2 2003/05/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (account_newsletters.php,v 1.13 2003/08/17); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  include( 'includes/application_top.php');
     // create smarty elements
  $smarty = new Smarty;
  // include boxes
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  // include needed functions
  require_once(DIR_FS_INC . 'twe_draw_hidden_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_checkbox_field.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_selection_field.inc.php');
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  if (!isset($_SESSION['customer_id'])) {
    
    twe_redirect(twe_href_link(FILENAME_LOGIN, '', 'SSL'));
  }



  $newsletter_query = "select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$_SESSION['customer_id'] . "'";
  $newsletter = $db->Execute($newsletter_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
      $newsletter_general = twe_db_prepare_input($_POST['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }

    if ($newsletter_general != $newsletter->fields['customers_newsletter']) {
      $newsletter_general = (($newsletter->fields['customers_newsletter'] == '1') ? '0' : '1');

     $db->Execute("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$_SESSION['customer_id'] . "'");
     
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    twe_redirect(twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1_ACCOUNT_NEWSLETTERS, twe_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2_ACCOUNT_NEWSLETTERS, twe_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

 require(DIR_WS_INCLUDES . 'header.php');

  $smarty->assign('FORM_ACTION',twe_draw_form('account_newsletter', twe_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL')) . twe_draw_hidden_field('action', 'process'));
  $smarty->assign('CHECKBOX',twe_draw_checkbox_field('newsletter_general', '1', (($newsletter->fields['customers_newsletter'] == '1') ? true : false), 'onclick="checkBox(\'newsletter_general\')"'));
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('BUTTON_BACK','<a href="' . twe_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  $smarty->assign('BUTTON_CONTINUE',twe_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE));
  $smarty->caching = 0;
  $main_content=$smarty->fetch(CURRENT_TEMPLATE . '/module/account_newsletter.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>