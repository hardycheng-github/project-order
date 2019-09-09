<?php
/* -----------------------------------------------------------------------------------------
   $Id: twe_expire_specials.inc.php,v 1.1 2003/09/06 21:47:50 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.5 2003/02/11); www.oscommerce.com 
   (c) 2003	 nextcommerce (twe_expire_specials.inc.php,v 1.5 2003/08/13); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  require_once(DIR_FS_INC . 'twe_set_specials_status.inc.php');
// Auto expire products on special
  function twe_expire_specials() {
    global $db;
    $specials_query = "select specials_id from " . TABLE_SPECIALS . " where status = '1' and now() >= expires_date and expires_date > 0";
    $specials = $db->Execute($specials_query);

    if ($specials->RecordCount() > 0) {
      while (!$specials->EOF) {
        twe_set_specials_status($specials->fields['specials_id'], '0');
		$specials->MoveNext();
      }
    }
  }
?>