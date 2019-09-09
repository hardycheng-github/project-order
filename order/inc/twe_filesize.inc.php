<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_filesize.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (twe_filesize.inc.php,v 1.1 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

// returns human readeable filesize :)

function twe_filesize($file) {
	$a = array("B","KB","MB","GB","TB","PB");
	
	$pos = 0;
	$size = filesize(DIR_FS_CATALOG.'media/products/'.$file);
	while ($size >= 1024) {
		$size /= 1024;
		$pos++;
	}
	return round($size,2)." ".$a[$pos];
}

?>