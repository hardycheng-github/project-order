<?php
/* -----------------------------------------------------------------------------------------
   $Id: products_media.php,v 1.8 2004/01/02 00:08:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (products_media.php,v 1.8 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/ 
$module_smarty= new Smarty;
$module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$module_content=array();
$filename='';

// check if allowed to see
require_once(DIR_FS_INC.'twe_in_array.inc.php');
$check_query="SELECT DISTINCT
				products_id
				FROM ".TABLE_PRODUCTS_CONTENT."
				WHERE languages_id='".(int)$_SESSION['languages_id']."'";
$check=$db->Execute($check_query);

$check_data=array();
$i='0';
while (!$check->EOF) {
 $check_data[$i]=$check->fields['products_id'];
 $i++;
 $check->MoveNext(); 
}
if (twe_in_array($_GET['products_id'],$check_data)) {
// get content data

require_once(DIR_FS_INC.'twe_filesize.inc.php');


//get download
$content_query="SELECT
				content_id,
				content_name,
				content_link,
				content_file,
				content_read,
				file_comment
				FROM ".TABLE_PRODUCTS_CONTENT."
				WHERE
				products_id='".(int)$_GET['products_id']."' AND
				languages_id='".(int)$_SESSION['languages_id']."'";

	$content_data=$db->Execute($content_query);	
	$button = '';		
	while (!$content_data->EOF) {
	$filename='';	
	if ($content_data->fields['content_link']!='')	{

	$icon= twe_image(DIR_WS_CATALOG.'admin/images/icons/icon_link.gif');
	} else {
	$icon= twe_image(DIR_WS_CATALOG.'admin/images/icons/icon_'.str_replace('.','',strstr($content_data->fields['content_file'],'.')).'.gif');
	}

	
	
	if ($content_data->fields['content_link']!='')	$filename= '<a href="'.$content_data->fields['content_link'].'" target="new">';
	$filename.=  $content_data->fields['content_name'];
	if ($content_data->fields['content_link']!='') $filename.= '</a>';
	
    if ($content_data->fields['content_link']=='') {
	if (preg_match('/.html/',$content_data->fields['content_file']) 
	or preg_match('/.htm/',$content_data->fields['content_file'])	
	or preg_match('/.txt/',$content_data->fields['content_file'])
	or preg_match('/.bmp/',$content_data->fields['content_file'])
	or preg_match('/.jpg/',$content_data->fields['content_file'])
	or preg_match('/.gif/',$content_data->fields['content_file'])
	or preg_match('/.png/',$content_data->fields['content_file'])
	or preg_match('/.tif/',$content_data->fields['content_file'])
	) 
	
	{
	

	 $button = '<a style="cursor:hand" onClick="javascript:window.open(\''.twe_href_link(FILENAME_MEDIA_CONTENT,'coID='.$content_data->fields['content_id']).'\', \'popup\', \'toolbar=0, width=640, height=600\')">'. twe_image_button('button_view.gif',TEXT_VIEW).'</a>';

	} else {

	$button= '<a href="'.twe_href_link('media/products/'.$content_data->fields['content_file']).'">'.twe_image_button('button_download.gif',TEXT_DOWNLOAD).'</a>';	
	
	}
	}	
$module_content[]=array(
			'ICON' => $icon,
			'FILENAME' => $filename,
			'DESCRIPTION' => $content_data->fields['file_comment'],
			'FILESIZE' => twe_filesize($content_data->fields['content_file']),
			'BUTTON' => $button,
			'HITS' => $content_data->fields['content_read']);
	 $content_data->MoveNext();		
	} 
 
  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->assign('module_content',$module_content);
  // set cache ID
  if (USE_CACHE=='false') {
  $module_smarty->caching = 0;
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/products_media.html');
  } else {
  $module_smarty->caching = 1;	
  $module_smarty->cache_lifetime=CACHE_LIFETIME;
  $module_smarty->cache_modified_check=CACHE_CHECK;
  $cache_id = $_SESSION['language'].$_GET['products_id'].$_SESSION['customers_status']['customers_status_name'];
  $module= $module_smarty->fetch(CURRENT_TEMPLATE.'/module/products_media.html',$cache_id);
  }
  $info_smarty->assign('MODULE_products_media',$module);
}
?>