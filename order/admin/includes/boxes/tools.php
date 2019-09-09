<?php
/* --------------------------------------------------------------
   $Id: tools.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

    TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project 
   (c) 2002-2003 osCommerce coding standards (a typical file) www.oscommerce.com
   (c) 2003     nextcommerce (start.php,1.5 2004/03/17); www.nextcommerce.org
   (c) 2003-2004 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

?>

<div class="CollapsiblePanel" id="tools">
	<div class="CollapsiblePanelTab" tabindex="0"><?php echo BOX_HEADING_TOOLS; ?></div>
	<div class="CollapsiblePanelContent">
		<?php
 if (($admin_access->fields['module_newsletter'] == '1') && (MODULE_NEWSLETTER == 'true')) echo '<a href="' . twe_href_link(FILENAME_MODULE_NEWSLETTER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_MODULE_NEWSLETTER . '</a><br>';
  if (($admin_access->fields['content_manager'] == '1') && (CONTENT_MANAGER == 'true')) echo'<a href="' . twe_href_link(FILENAME_CONTENT_MANAGER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CONTENT . '</a><br>';
  if (($admin_access->fields['backup'] == '1') && (BACKUP == 'true')) echo '<a href="' . twe_href_link(FILENAME_BACKUP,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_BACKUP . '</a><br>';
  if (($admin_access->fields['banner_manager'] == '1') && (BANNER_MANAGER == 'true')) echo '<a href="' . twe_href_link(FILENAME_BANNER_MANAGER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_BANNER_MANAGER . '</a><br>';
  if (($admin_access->fields['server_info'] == '1') && (SERVER_INFO == 'true')) echo '<a href="' . twe_href_link(FILENAME_SERVER_INFO,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SERVER_INFO . '</a><br>';
  if (($admin_access->fields['whos_online'] == '1') && (WHOS_ONLINE == 'true')) echo '<a href="' . twe_href_link(FILENAME_WHOS_ONLINE,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_WHOS_ONLINE . '</a><br>';
  if (($admin_access->fields['file_manager'] == '1') && (FILE_MANAGER == 'true')) echo '<a href="' . twe_href_link(FILENAME_FILE_MANAGER,'selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_FILE_MANAGER . '</a><br>';
  if (($admin_access->fields['mysql'] == '1') && (MYSQL == 'true')) echo '<a href="' . twe_href_link('mysql.php','selected_box=tool') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . phpMyAdmin . '</a><br>';

?>
	</div>
</div>
<?php if($_SESSION['selected_box'] == 'tool'){ ?>
<script language="JavaScript" type="text/javascript">
var tools = new Spry.Widget.CollapsiblePanel("tools", { enableAnimation: false,contentIsOpen: true});
</script>
<?php }else{ ?>
<script language="JavaScript" type="text/javascript">
var tools = new Spry.Widget.CollapsiblePanel("tools", { enableAnimation: false,contentIsOpen: false});
</script>
<?php } ?>
