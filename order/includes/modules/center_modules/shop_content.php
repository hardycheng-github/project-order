<?php 
/* -----------------------------------------------------------------------------------------
   $Id: default.php,v 1.27 2004/04/26 10:31:17 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(default.php,v 1.84 2003/05/07); www.oscommerce.com
   (c) 2003	 nextcommerce (default.php,v 1.11 2003/08/22); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3        	Autor: Mikel Williams | mikel@ladykatcostumes.com
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/  

$shop_content_query="SELECT
                     content_title,
                     content_heading,
                     content_text,
                     content_file
                     FROM ".TABLE_CONTENT_MANAGER."
                     WHERE content_group='5'
                     AND languages_id='".$_SESSION['languages_id']."'";
     $shop_content_data=$db->Execute($shop_content_query);

    $default_smarty->assign('title',$shop_content_data->fields['content_heading']);

	if ($shop_content_data->fields['content_file']!=''){
       ob_start();
                  if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '<pre>';
                       include(DIR_FS_CATALOG.'media/content/'.$shop_content_data->fields['content_file']);
                  if (strpos($shop_content_data->fields['content_file'],'.txt')) echo '</pre>';
                  $shop_content_data->fields['content_text']=ob_get_contents();
       ob_end_clean();
        }


    $default_smarty->assign('shop_content',$shop_content_data->fields['content_text']);

?>
   