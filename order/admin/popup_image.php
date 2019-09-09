<?php
/* --------------------------------------------------------------
   $Id: popup_image.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(popup_image.php,v 1.6 2002/05/20); www.oscommerce.com 
   (c) 2003	 nextcommerce (popup_image.php,v 1.7 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  reset($_GET);
  while (list($key, ) = each($_GET)) {
    switch ($key) {
      case 'banner':
        $banners_id = twe_db_prepare_input($_GET['banner']);

        $banner_query = "select banners_title, banners_image, banners_html_text from " . TABLE_BANNERS . " where banners_id = '" . twe_db_input($banners_id) . "'";
        $banner = $db->Execute($banner_query);

        $page_title = $banner->fields['banners_title'];

        if ($banner->fields['banners_html_text']) {
          $image_source = $banner->fields['banners_html_text'];
        } elseif ($banner->fields['banners_image']) {
          $image_source = twe_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES  . 'banner/'. $banner->fields['banners_image'], $page_title);
        }
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $page_title; ?></title>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i = 40;
  window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
}
//--></script>
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body onload="resize();">
<?php echo $image_source; ?>
</body>
</html>