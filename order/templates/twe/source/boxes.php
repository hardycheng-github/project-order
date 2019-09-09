<?php
/* -----------------------------------------------------------------------------------------
   $Id: boxes.php,v 1.2 2008/04/07 23:02:54 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   ----------------------------------------------------------------------------------------- 
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
---------------------------------------------------------------------------------------*/
  define('DIR_WS_BOXES',DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/');
    
$smarty = new smarty;
$lbox_content='';
$rbox_content='';
$sbox_content='';

$sbox = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single ='1'
                                        order by layout_box_sort_order_single ASC");

if($sbox->RecordCount()>0){	
$sbox_content=array();
$rows = 0;    

    while (!$sbox->EOF){
	 $rows++;
   $width = (int)(100 / $rows) . '%';
   include(DIR_WS_BOXES . $sbox->fields['layout_box_name']);    
   $sbox_content[]=array('WIDTH' =>$width,
   						 'BOXES' => '{$'.str_replace('.php', '', $sbox->fields['layout_box_name']).'}');   
   $sbox->MoveNext(); 
  }
}


$lbox = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
                                        and layout_box_location = '0'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single !='1'
                                        order by layout_box_sort_order ASC");

if($lbox->RecordCount()>0){	
$lbox_content=array();
    while (!$lbox->EOF){
   include(DIR_WS_BOXES . $lbox->fields['layout_box_name']);    
   $lbox_content[]=array('BOXES' => '{$'.str_replace('.php', '', $lbox->fields['layout_box_name']).'}');   
   $lbox->MoveNext(); 
  }
}
$rbox = $db->Execute("select layout_box_name from layout_boxes 
                                        where layout_box_status  = '1'
                                        and layout_box_location = '1'
										and layout_template ='".CURRENT_TEMPLATE."'
										and layout_box_status_single !='1'
                                        order by layout_box_sort_order ASC");

if($rbox->RecordCount()>0){	
$rbox_content=array();
    while (!$rbox->EOF){
   include(DIR_WS_BOXES . $rbox->fields['layout_box_name']);    
   $rbox_content[]=array('BOXES' => '{$'.str_replace('.php', '', $rbox->fields['layout_box_name']).'}');   
   $rbox->MoveNext(); 
  }
}
$smarty->assign('rbox_box',$rbox_content);  
$smarty->assign('1box_box',$lbox_content);  
$smarty->assign('sbox_box',$sbox_content);  
$smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
?>