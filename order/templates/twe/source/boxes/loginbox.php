<?php
/* -----------------------------------------------------------------------------------------
   $Id: loginbox.php,v 1.2 2004/02/17 16:20:07 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (loginbox.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------
   Third Party contributions:
   Loginbox V1.0        	Aubrey Kilian <aubrey@mycon.co.za>   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
$box_smarty = new smarty;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/'); 
$box_content='';
  require_once(DIR_FS_INC . 'twe_image_submit.inc.php');
  require_once(DIR_FS_INC . 'twe_draw_password_field.inc.php');

  if (!twe_session_is_registered('customer_id')) {

    $box_smarty->assign('FORM_ACTION','<form name="login" method="post" action="' . twe_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . '">');
    $box_smarty->assign('FIELD_EMAIL',twe_draw_input_field('email_address', '', 'size="22" maxlength="50"'));
    $box_smarty->assign('TEXT_EMAIL',BOX_LOGINBOX_EMAIL);
    $box_smarty->assign('FIELD_PWD',twe_draw_password_field('password', '', 'size="10" maxlength="35"'));
    $box_smarty->assign('TEXT_PWD',BOX_LOGINBOX_PASSWORD);
    $box_smarty->assign('BUTTON',twe_image_submit('button_login_small.gif', IMAGE_BUTTON_LOGIN).'</form>');
    //$box_smarty->assign('TEXT_FORGOT',);
    //$box_smarty->assign('LINK_FORGOT',);





    $box_smarty->assign('BOX_CONTENT', $loginboxcontent);

    $box_smarty->caching = 0;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_loginbox= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_login.html');
    $smarty->assign('loginbox',$box_loginbox);
 }
 ?>