<?php
/* -----------------------------------------------------------------------------------------
   $Id: banners.php,v 1.1 2004/02/28 19:16:49 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  require_once(DIR_FS_INC . 'twe_banner_exists.inc.php');
  require_once(DIR_FS_INC . 'twe_display_banner.inc.php');
  require_once(DIR_FS_INC . 'twe_update_banner_display_count.inc.php');

  if ($banner = twe_banner_exists('dynamic', 'banner')) {
	$smarty->assign('BANNER',twe_display_banner('static', $banner));
  }
?>