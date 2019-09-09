<?php
/* -----------------------------------------------------------------------------------------
   $Id: popup_image.php,v 1.9 2005/03/13 14:01:00 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2004 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2003-2004 xt-commerce(popup_image.php); www.xt-commerce.com 
      ---------------------------------------------------------------------------------------*/

  require('includes/application_top.php');
  require_once(DIR_FS_INC . 'twe_get_more_images.inc.php');
  
  if ((int)$_GET['imgID'] == 0) {
  	$products_query = "select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  	$products_values = $db->Execute($products_query);
  } else {
  	$products_query = "select pd.products_name, p.products_image, pi.image_name from " . TABLE_PRODUCTS_MORE_IMAGES . " pi, " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pi.products_id = '" . (int)$_GET['pID'] . "' and pi.image_nr = '" . (int)$_GET['imgID'] . "' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
  	$products_values = $db->Execute($products_query);
  	$products_values->fields['products_image'] = $products_values->fields['image_name'];
  }

// get x and y of the image
$img = DIR_WS_POPUP_IMAGES.$products_values->fields['products_image'];
$size = GetImageSize("$img");

//get data for mo_images
$mo_images = twe_get_more_images((int)$_GET['pID']);
$img = DIR_WS_THUMBNAIL_IMAGES.$products_values->fields['products_image'];
$osize = GetImageSize("$img");
if (isset($mo_images)){	
	//$bwidth = $osize[0];
	$bheight = $osize[1];
	foreach ($mo_images as $mo_img){		  
		$img = DIR_WS_THUMBNAIL_IMAGES.$mo_img['image_name'];
		$mo_size = GetImageSize("$img");
		// if ($mo_size[0] > $bwidth)  $bwidth  = $mo_size[0];
		if ($mo_size[1] > $bheight) $bheight = $mo_size[1];		
	}
	$bheight += 50;
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $products_values->fields['products_name']; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  window.resizeTo(<?php echo $size[0] ?> +105, <?php echo $size[1] + $bheight ?>+125-i);
   self.focus();
}

//--></script>
</head>
<body onload="resize();" >


<!-- twe_image($src, $alt = '', $width = '', $height = '', $params = '') -->
    
<!-- big image -->
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="283758"><div align="center"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo $products_values->fields['products_name']; ?></strong></font></div></td>
  </tr>
  <tr>
    <td>
    <table border=0 align="center" cellpadding=5 cellspacing=0>
      <tr>
        <td align=center><?php echo twe_image(DIR_WS_POPUP_IMAGES . $products_values->fields['products_image'], $products_values->fields['products_name'], $size[0], $size[1]); ?></td>
      </tr>
    </table>
</table>

<!-- thumbs -->
<center>
<?php
if (isset($mo_images))
{		
?>
<iframe src="<?php echo 'show_product_more_pic.php?pID='.(int)$_GET['pID'].'&imgID='.(int)$_GET['imgID']; ?>" width="<?php echo $size[0] +40 ?>" height="<?php echo $bheight+5; ?>" border="0" frameborder="0">
<a href="<?php echo 'show_product_more_pic.php?pID='.(int)$_GET['pID'].'&imgID='.(int)$_GET['imgID']; ?>">More Images</a>
</iframe><br>
<?php
}
?>
<a href="#" onClick='window.close();'>window-close</a>
</body>
</html>