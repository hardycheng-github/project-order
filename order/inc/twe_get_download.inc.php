<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_get_download.inc.php,v 1.1 2005/04/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier <webmaster@zanier.at>
   based on:
   (c) 2003	 nextcommerce (twe_get_download.inc.php,v 1.4 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
// safe download function, file get renamed bevor sending to browser!!
function twe_get_download($content_id) {
global $db;	
	$content_query="SELECT
					content_file,
					content_read
					FROM ".TABLE_PRODUCTS_CONTENT."
					WHERE content_id='".$content_id."'";
					
	$content_data=$db->Execute($content_query);
	// update file counter
	
	$sql = "UPDATE 
			".TABLE_PRODUCTS_CONTENT." 
			SET content_read='".($content_data->fields['content_read']+1)."'
			WHERE content_id='".$content_id."'";
	$db->Execute($sql);
	// original filename
	$filename = DIR_FS_CATALOG.'media/products/'.$content_data->fields['content_file'];
	$backup_filename = DIR_FS_CATALOG.'media/products/backup/'.$content_data->fields['content_file'];
	// create md5 hash id from original file
	$orign_hash_id=md5_file($filename);
	
	clearstatcache();
	
	// create new filename with timestamp
	$timestamp=str_replace('.','',microtime());
        $timestamp=str_replace(' ','',$timestamp);
        $new_filename=DIR_FS_CATALOG.'media/products/'.$timestamp.strstr($content_data->fields['content_file'],'.');
        
        // rename file
        rename($filename,$new_filename);	
	if (file_exists($new_filename)) {	
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=".$new_filename);
	@readfile($new_filename);	
	// rename file to original name
	rename($new_filename,$filename);
	$new_hash_id=md5_file($filename);
	clearstatcache();	
	// check hash id of file again, if not same, get backup!
	if ($new_hash_id!=$orign_hash_id) copy($backup_filename,$filename);
	}	
}
?>