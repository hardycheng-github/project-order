<?php
/* --------------------------------------------------------------
   $Id: object_info.php,v 1.1 2003/09/06 22:05:29 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(object_info.php,v 1.5 2002/01/30); www.oscommerce.com 
   (c) 2003	 nextcommerce (object_info.php,v 1.5 2003/08/18); www.nextcommerce.org   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
--------------------------------------------------------------*/

  class objectInfo {

    // class constructor
    function objectInfo($object_array) {
      reset($object_array);
      while (list($key, $value) = each($object_array)) {
        $this->$key = twe_db_prepare_input($value);
      }
    }
  }
 ?>