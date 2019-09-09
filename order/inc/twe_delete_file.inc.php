<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_delete_file.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier <webmaster@zanier.at>
   based on: 
   (c) 2003	 nextcommerce (twe_delete_file.inc.php,v 1.1 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

function twe_delete_file($file){ 
	
	$delete= @unlink($file);
	clearstatcache();
	if (@file_exists($file)) {
		$filesys=eregi_replace("/","\\",$file);
		$delete = @system("del $filesys");
		clearstatcache();
		if (@file_exists($file)) {
			$delete = @chmod($file,0775);
			$delete = @unlink($file);
			$delete = @system("del $filesys");
		}
	}
	clearstatcache();
	if (@file_exists($file)) {
		return false;
	}
	else {
	return true;
} // end function
}
?>