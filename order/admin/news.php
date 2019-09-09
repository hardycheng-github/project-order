<?php
/* --------------------------------------------------------------
   $Id: index.php,v 1.2 2004/08/17 16:14:34 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(index.php,v 1.17 2003/02/14); www.oscommerce.com 
   (c) 2003	 nextcommerce (index.php,v 1.11 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contribution:
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/

  require('includes/application_top.php');
  require_once 'includes/classes/carp.php';
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style type="text/css">
.h2 {
  font-family: Trebuchet MS,Palatino,Times New Roman,serif;
  font-size: 13pt;
  font-weight: bold;
}

.h3 {
  font-family: Verdana,Arial,Helvetica,sans-serif;
  font-size: 9pt;
  font-weight: bold;
}
</style> 

<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_news.gif'); ?></td>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Central</td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td>
        <?php include(DIR_WS_MODULES.FILENAME_SECURITY_CHECK); ?>
        </td>
      <tr>
      <td style="border: 1px solid; border-color: #ffffff;">
	  <table valign="top" width="100%" cellpadding="5" cellspacing="5">
       
<?php
CarpConf('linkdiv', '<div class="main" style="background:#cccccc; padding:5px; font-weight: bold;">');
CarpConf('linkstyle', 'text-decoration:none');
CarpConf('linkclass', 'newslink');
CarpConf('showdesc|showcdesc', 1);
CarpConf('maxitems', 5);
CarpConf('cclass', 'h2');
CarpConf('postitem','');
CarpConf('poweredby','');
CarpShow('http://www.twecommerce.org/backend.php', 'twe-news.cache');
CarpConfReset();


CarpConf('linkdiv','<div style="background:#cccccc; width:185; padding:2px; border-width:1px; border-style:solid; border-color:#333333;">');
CarpConf('linkstyle','text-decoration:none');
CarpConf('linkclass','h3');
CarpConf('showdesc|showctitle|showcdesc',1);
CarpConf('maxitems',10);
CarpConf('cclass','h2');
CarpConf('postitems','');
CarpConf('poweredby','');
CarpConfReset();
?>
        </table></td>
      </tr>		
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?> 