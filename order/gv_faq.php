<?php
/*
  $Id: gv_faq.php,v 1.2 2005/04/17 23:53:04 oldpa Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  based on:
  (c) 2003	 xt-commerce  www.xt-commerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Gift Voucher System v1.0
  Copyright (c) 2001, 2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $smarty = new Smarty;
  require(DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes.php'); 
  require_once(DIR_FS_INC . 'twe_image_button.inc.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_GV_FAQ);

  $breadcrumb->add(NAVBAR_TITLE, twe_href_link(FILENAME_GV_FAQ, '', 'SSL')); 
  require(DIR_WS_INCLUDES . 'header.php');
  $text_infomation = TEXT_INFORMATION;
  $sub_heading_title = SUB_HEADING_TITLE;
  $sub_heading_text = SUB_HEADING_TEXT;
  
  $smarty->assign('language', $_SESSION['language']);
  
  $smarty->assign('text_infomation',$text_infomation);
  $smarty->assign('sub_heading_title',$sub_heading_title);
  $smarty->assign('sub_heading_text',$sub_heading_text);
  $smarty->assign('LINK_DEFAULT','<a href="' . twe_href_link(FILENAME_DEFAULT, '', 'SSL') . '">' . twe_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>');
  
  $main_content= $smarty->fetch(CURRENT_TEMPLATE.'/module/gv_faq.html');

  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('main_content',$main_content);
  $smarty->caching = 0;
  $smarty->display(CURRENT_TEMPLATE . '/index.html');
  ?>