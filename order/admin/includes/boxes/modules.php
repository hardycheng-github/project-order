<?php
/* --------------------------------------------------------------
   $Id: modules.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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


<div class="CollapsiblePanel" id="modules">
	<div class="CollapsiblePanelTab" tabindex="0"><?php echo BOX_HEADING_MODULES; ?></div>
	<div class="CollapsiblePanelContent">
		<?php
  if (($admin_access->fields['modules'] == '1') && (PAYMENT == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PAYMENT . '</a><br>';
  if (($admin_access->fields['modules'] == '1') && (SHIPPING == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=shipping&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SHIPPING . '</a><br>';
  if (($admin_access->fields['modules'] == '1') && (ORDER_TOTAL == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULES, 'set=ordertotal&selected_box=modules', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_ORDER_TOTAL . '</a><br>';
  if (($admin_access->fields['module_export'] == '1') && (MODULE_EXPORT == 'true')) echo  '<a href="' . twe_href_link(FILENAME_MODULE_EXPORT,'selected_box=modules') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_MODULE_EXPORT . '</a><br>';
?>
	</div>
</div>

<?php if($_SESSION['selected_box'] == 'modules'){ ?>
<script language="JavaScript" type="text/javascript">
var modules = new Spry.Widget.CollapsiblePanel("modules", { enableAnimation: false,contentIsOpen: true});
</script>
<?php }else{ ?>
<script language="JavaScript" type="text/javascript">
var modules = new Spry.Widget.CollapsiblePanel("modules", { enableAnimation: false,contentIsOpen: false});
</script>
<?php } ?>
