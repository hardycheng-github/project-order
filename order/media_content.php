<?php
/* -----------------------------------------------------------------------------------------
   $Id: media_content.php,v 1.3 2004/01/02 00:08:25 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce 
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (media_content.php,v 1.2 2003/08/25); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require('includes/application_top.php');


	 $content_query="SELECT
	 				content_name,
	 				content_read,
 					content_file
 					FROM ".TABLE_PRODUCTS_CONTENT."
 					WHERE content_id='".(int)$_GET['coID']."'";
 	$content_data=$db->Execute($content_query);
 	
 		// update file counter
	
	$db->Execute("UPDATE 
			".TABLE_PRODUCTS_CONTENT." 
			SET content_read='".($content_data->fields['content_read']+1)."'
			WHERE content_id='".(int)$_GET['coID']."'");
	
?>

<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $content_data->fields['content_name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo 'templates/'.CURRENT_TEMPLATE.'/stylesheet.css'; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);
  self.focus();
}
//--></script>

</head>
<body onLoad="resize();">
 <?php
 if ($content_data->fields['content_file']!=''){
if (strpos($content_data->fields['content_file'],'.txt')) echo '<pre>';

	if (preg_match('/.gif/',$content_data->fields['content_file']) or preg_match('/.jpg/',$content_data->fields['content_file']) or  preg_match('/.png/',$content_data->fields['content_file']) or  preg_match('/.tif/',$content_data->fields['content_file']) or  preg_match('/.bmp/',$content_data->fields['content_file'])) {	
	echo '<table align="center" valign="middle" width="100%" height="100%" border=0><tr><td class="main" align="middle" valign="middle">';	
	
	echo twe_image(DIR_WS_CATALOG.'media/products/'.$content_data->fields['content_file']);
	echo '</td></tr></table>';
	} else {
	echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">';
	include(DIR_FS_CATALOG.'media/products/'.$content_data->fields['content_file']);
	echo '</td>
          </tr>
        </table>';	
	}


	
if (strpos($content_data->fields['content_file'],'.txt')) echo '</pre>';
 } 
?>
</body>
</html>