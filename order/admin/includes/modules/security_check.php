<?php
/* --------------------------------------------------------------
   $Id: security_check.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (security_check.php,v 1.2 2003/08/23); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License
   --------------------------------------------------------------*/
$file_warning='';
 
if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'includes/configure.php')),'444')) {
$file_warning .= '<br>'.DIR_FS_CATALOG.'includes/configure.php';
}

/*
if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'includes/configure.org.php')),'444')) {
$file_warning .= '<br>'.DIR_FS_CATALOG.'includes/configure.org.php';
}
*/

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'admin/includes/configure.php')),'444')) {
$file_warning .= '<br>'.DIR_FS_CATALOG.'admin/includes/configure.php';
}

/*
if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'admin/includes/configure.org.php')),'444')) {
$file_warning .= '<br>'.DIR_FS_CATALOG.'admin/includes/configure.org.php';
}
*/

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'admin/rss/')),'777') and !strpos(decoct(fileperms(DIR_FS_CATALOG.'admin/rss/')),'755')) {
$folder_warning .= '<br>'.DIR_FS_CATALOG.'admin/rss/';
}

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'templates_c/')),'777') and !strpos(decoct(fileperms(DIR_FS_CATALOG.'templates_c/')),'755')) {
$folder_warning .=  '<br>'.DIR_FS_CATALOG.'templates_c/';
}

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'cache/')),'777') and !strpos(decoct(fileperms(DIR_FS_CATALOG.'cache/')),'755')) {
$folder_warning .=  '<br>'.DIR_FS_CATALOG.'cache/';
}

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'media/')),'777') and !strpos(decoct(fileperms(DIR_FS_CATALOG.'media/')),'755')) {
$folder_warning .=  '<br>'.DIR_FS_CATALOG.'media/';
}

if (!strpos(decoct(fileperms(DIR_FS_CATALOG.'media/content/')),'777') and !strpos(decoct(fileperms(DIR_FS_CATALOG.'media/content/')),'755')) {
$folder_warning .=  '<br>'.DIR_FS_CATALOG.'media/content/';
}

if ($file_warning!='' or $folder_warning != '') {
 ?>


<table style="border: 1px solid; border-color: #ff0000;" bgcolor="FDAC00" border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td>
<div class"main"> 
        <table width="100%" border="0">
          <tr>
            <td width="1"><?php echo twe_image(DIR_WS_ICONS.'big_warning.gif'); ?></td>
            <td class="main">
              <?php 
 if ($file_warning!='') {
 
 echo TEXT_FILE_WARNING;
 
echo '<b>'.$file_warning.'</b><br>';
}

 if ($folder_warning!='') {
 
 echo TEXT_FOLDER_WARNING;
 
echo '<b>'.$folder_warning.'</b>';
}

$payment_data=$db->Execute("SELECT *
			FROM ".TABLE_CONFIGURATION."
			WHERE configuration_key = 'MODULE_PAYMENT_INSTALLED'");
while (!$payment_data->EOF) {
 $installed_payment=$payment_data->fields['configuration_value'];
 	
$payment_data->MoveNext();	
}
if ($installed_payment=='') {
echo '<br>'.TEXT_PAYMENT_ERROR;
}
$shipping_data=$db->Execute("SELECT *
			FROM ".TABLE_CONFIGURATION."
			WHERE configuration_key = 'MODULE_SHIPPING_INSTALLED'");
while (!$shipping_data->EOF) {
 $installed_shipping=$shipping_data->fields['configuration_value'];
 	
$shipping_data->MoveNext();	
}
if ($installed_shipping=='') {
echo '<br>'.TEXT_SHIPPING_ERROR;
}
 ?>
            </td>
          </tr>
        </table>
      </div>
</td>
</tr>
</table>
<?php 
}
?>