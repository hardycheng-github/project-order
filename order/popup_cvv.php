<?php
/*------------------------------------------------------------------------------
  $Id: popup_cvv.php,v 1.1 2004/03/16 20:12:29 oldpa   Exp $

  XTC-CC - Contribution for TWE-Commerce 
  http://www.oldpa.com.tw
  modified by http://www.netz-designer.de

  Copyright (c) 2003 netz-designer
  -----------------------------------------------------------------------------
  based on:
  $Id: popup_cvv.php,v 1.1 2004/03/16 20:12:29 oldpa   Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  (c) 2003	 xt-commerce  www.xt-commerce.com

  Released under the GNU General Public License
------------------------------------------------------------------------------*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . modules . '/' . payment . '/' . 'cc.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" >
<!-- 
// prevent click click
document.oncontextmenu = function(){return false}
if(document.layers) {
window.captureEvents(Event.MOUSEDOWN);
window.onmousedown = function(e){
if(e.target==document)return false;
}
}
else {
document.onmousedown = function(){return false}
}
//-->
</SCRIPT>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<style type="text/css"><!--
BODY { margin-bottom: 10px; margin-left: 10px; margin-right: 10px; margin-top: 10px; }
//--></style>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left', 'text' => HEADING_CVV);

  new infoBoxHeading($info_box_contents);

  $info_box_contents = array();
  $info_box_contents[] = array( 'align' => 'left', 'text' => TEXT_CVV);

  new infoBox($info_box_contents);
?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>

</body>
</html>
<?php require('includes/application_bottom.php'); ?>
