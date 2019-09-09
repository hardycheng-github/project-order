<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_unlink_temp_dir.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   by Mario Zanier for neXTCommerce
   
   based on:
   (c) 2003	 nextcommerce (twe_unlink_temp_dir.inc.php,v 1.1 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
  // Unlinks all subdirectories and files in $dir
  // Works only on one subdir level, will not recurse
  function twe_unlink_temp_dir($dir) {
    $h1 = opendir($dir);
    while ($subdir = readdir($h1)) {
      // Ignore non directories
      if (!is_dir($dir . $subdir)) continue;
      // Ignore . and .. and CVS
      if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') continue;
      // Loop and unlink files in subdirectory
      $h2 = opendir($dir . $subdir);
      while ($file = readdir($h2)) {
        if ($file == '.' || $file == '..') continue;
        @unlink($dir . $subdir . '/' . $file);
      }
      closedir($h2); 
      @rmdir($dir . $subdir);
    }
    closedir($h1);
  }
 ?>