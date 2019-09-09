<?php
/* -----------------------------------------------------------------------------------------
   $Id: center_modules.php,v 1.3 2004/08/12 09:19:32 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (center_modules.php,v 1.5 2003/08/13); www.nextcommerce.org 
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
 
define('DIR_WS_CENTER_MODULES', DIR_WS_MODULES .'center_modules/');
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$center_center='';
$center_right='';
$center_left='';

$center_left_box = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
                                        and layout_box_location = '2'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single !='1'
                                        order by layout_box_sort_order ASC");

if($center_left_box->RecordCount()>0){	
$center_left=array();
$rows = 0;    

    while (!$center_left_box->EOF){
$rows++;
   $width = (int)(100 / $rows) . '%';	
   include(DIR_WS_CENTER_MODULES . $center_left_box->fields['layout_box_name']);    
   $center_left[]=array('WIDTH' =>$width, 'BOXES' => '{$'.str_replace('.php', '', $center_left_box->fields['layout_box_name']).'}');   
   $center_left_box->MoveNext(); 
  }
}


$center_right_box = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
                                        and layout_box_location = '4'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single !='1'
                                        order by layout_box_sort_order ASC");

if($center_right_box->RecordCount()>0){	
$rows = 0;    
$lbox_content=array();
    while (!$center_right_box->EOF){
	      $rows++;
   $width = (int)(100 / $rows) . '%';
   include(DIR_WS_CENTER_MODULES . $center_right_box->fields['layout_box_name']);    
   $center_right[]=array('WIDTH' =>$width,
   						'BOXES' => '{$'.str_replace('.php', '', $center_right_box->fields['layout_box_name']).'}');   
   $center_right_box->MoveNext(); 
  }
}

$center_box = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
                                        and layout_box_location = '3'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single !='1'
                                        order by layout_box_sort_order ASC");

if($center_box->RecordCount()>0){	
$rbox_content=array();
    while (!$center_box->EOF){
   include(DIR_WS_CENTER_MODULES . $center_box->fields['layout_box_name']);    
   $center_center[]=array('BOXES' => '{$'.str_replace('.php', '', $center_box->fields['layout_box_name']).'}');   
   $center_box->MoveNext(); 
  }
}
$default_smarty->assign('center_left',$center_left);  
$default_smarty->assign('center_center',$center_center);  
$default_smarty->assign('center_right',$center_right);  
?>