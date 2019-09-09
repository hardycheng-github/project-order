<?php
/* -----------------------------------------------------------------------------------------
   $Id: content_preview.php,v 1.2 2004/01/17 16:14:34 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (content_preview.php,v 1.2 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
   
require('includes/application_top.php');

if ($_GET['pID']=='media') {
	$content_query="SELECT
 					content_file,
 					content_name,
 					file_comment
 					FROM ".TABLE_PRODUCTS_CONTENT."
 					WHERE content_id='".(int)$_GET['coID']."'";
 	$content_data=$db->Execute($content_query);
	
} else {
	 $content_query="SELECT
 					content_title,
 					content_heading,
 					content_text,
 					content_file
 					FROM ".TABLE_CONTENT_MANAGER."
 					WHERE content_id='".(int)$_GET['coID']."'";
 	$content_data=$db->Execute($content_query);
 }
?>

<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $page_title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<div class="pageHeading"><?php echo $content_data->fields['content_heading']; ?></div><br>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">
 <?php
 if ($content_data->fields['content_file']!=''){
if (strpos($content_data->fields['content_file'],'.txt')) echo '<pre>';
if ($_GET['pID']=='media') {
	// display image
	if (preg_match('/.gif/',$content_data->fields['content_file']) or preg_match('/.jpg/',$content_data->fields['content_file']) or preg_match('/.png/',$content_data->fields['content_file']) or  preg_match('/.tif/',$content_data->fields['content_file']) or preg_match('/.bmp/',$content_data->fields['content_file'])) {	
	echo twe_image(DIR_WS_CATALOG.'media/products/'.$content_data->fields['content_file']);
	} else {
	include(DIR_FS_CATALOG.'media/products/'.$content_data->fields['content_file']);	
	}
} else {
include(DIR_FS_CATALOG.'media/content/'.$content_data->fields['content_file']);	
}
if (strpos($content_data->fields['content_file'],'.txt')) echo '</pre>';
 } else {	      
echo $content_data->fields['content_text'];
}
?>
</td>
</tr>
</table>
</body>
</html>