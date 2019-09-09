<?php
/* -----------------------------------------------------------------------------------------
   $Id: show_produt_thumbs.php,v 0.4 2005/03/13 13:35:23 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2004 xt-Commerce; www.xt-commerce.com 
   ---------------------------------------------------------------------------------------*/
   
  require('includes/application_top.php');
  require_once(DIR_FS_INC . 'twe_get_more_images.inc.php'); 
   
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body bgcolor="#FFFFFF">
<table align="center">
<tr>
<?php
$mo_images = twe_get_more_images((int)$_GET['pID']);
if ((int)$_GET['imgID'] == 0) $actual = ' bgcolor="#FF0000"'; else unset($actual);
echo '<td align="left"'.$actual.'>';
$products_query = "select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
$products_values = $db->Execute($products_query);
echo '<a href="popup_image.php?pID='.(int)$_GET['pID'].'&imgID=0" target="_parent">' . twe_image(DIR_WS_THUMBNAIL_IMAGES . $products_values->fields['products_image'], $products_values->fields['products_name']) . '</a>';
echo '</td>';
foreach ($mo_images as $mo_img){
	if ($mo_img['image_nr'] == (int)$_GET['imgID']) $actual = ' bgcolor="#FF0000"'; else unset($actual);	
	echo '<td align=left'.$actual.'><a href="popup_image.php?pID='.(int)$_GET['pID'].'&imgID='.$mo_img['image_nr'].'" target="_parent">' . twe_image(DIR_WS_THUMBNAIL_IMAGES . $mo_img['image_name'], $products_values->fields['products_name']) . '</a></td>';	
} 
?>
</tr>
</table>
</body>
