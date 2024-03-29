<?php
/* -----------------------------------------------------------------------------------------
   $Id: infobox.php,v 1.1 2004/02/07 23:02:54 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (infobox.php,v 1.7 2003/08/13); www.nextcommerce.org   
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


  if ($_SESSION['customers_status']['customers_status_image']!='') {
    $loginboxcontent = '<center>' . twe_image('admin/images/icons/' . $_SESSION['customers_status']['customers_status_image']) . '</center>';
  }
  $loginboxcontent .= BOX_LOGINBOX_STATUS . '<b>' . $_SESSION['customers_status']['customers_status_name'] . '</b><br>';
  if ($_SESSION['customers_status']['customers_status_show_price'] == 0) {
    $loginboxcontent .= NOT_ALLOWED_TO_SEE_PRICES_TEXT;
  } else  {
    if ($_SESSION['customers_status']['customers_status_show_price_tax'] == 1) {
      $loginboxcontent .= BOX_LOGINBOX_INCL . '<br>';
    } else {
      $loginboxcontent .= BOX_LOGINBOX_EXCL . '<br>';
    }
    if ($_SESSION['customers_status']['customers_status_discount'] != '0.00') {
      $loginboxcontent.=BOX_LOGINBOX_DISCOUNT . ' ' . $_SESSION['customers_status']['customers_status_discount'] . '%<br>';
    }
    if ($_SESSION['customers_status']['customers_status_ot_discount_flag'] == 1  && $_SESSION['customers_status']['customers_status_ot_discount'] != '0.00') {
      $loginboxcontent .= BOX_LOGINBOX_DISCOUNT_TEXT . ' '  . $_SESSION['customers_status']['customers_status_ot_discount'] . ' % ' . BOX_LOGINBOX_DISCOUNT_OT . '<br>';
    }
  }



    $box_smarty->assign('BOX_CONTENT', $loginboxcontent);
	$box_smarty->assign('language', $_SESSION['language']);
       	  // set cache ID
  if (USE_CACHE=='false') {
  $box_smarty->caching = 0;
  $box_infobox= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_infobox.html');
  } else {
  $box_smarty->caching = 1;
  $box_smarty->cache_lifetime=CACHE_LIFETIME;
  $box_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_id'];
  $box_infobox= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_infobox.html',$cache_id);
  }
    
    $smarty->assign('infobox',$box_infobox);
    
    ?>