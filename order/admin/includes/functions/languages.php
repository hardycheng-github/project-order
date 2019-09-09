<?php
/* --------------------------------------------------------------
   $Id: languages.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.5 2002/11/22); www.oscommerce.com 
   (c) 2003	 nextcommerce (languages.php,v 1.6 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  function twe_get_languages_directory($code) {
 global $db; 
    $language_query = "select languages_id, directory from " . TABLE_LANGUAGES . " where code = '" . $code . "'";
    $lang = $db->Execute($language_query);
	if ($lang->RecordCount()>0) {
      $_SESSION['languages_id'] = $lang->fields['languages_id'];
      return $lang->fields['directory'];
    } else {
      return false;
    }
  }
?>