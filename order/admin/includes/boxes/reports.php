<?php
/* --------------------------------------------------------------
   $Id: reports.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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

<div class="CollapsiblePanel" id="reports">
	<div class="CollapsiblePanelTab" tabindex="0"><?php echo BOX_HEADING_REPORTS; ?></div>
	<div class="CollapsiblePanelContent">
		<?php
  if (($admin_access->fields['stats_products_viewed'] == '1') && (STATS_PRODUCTS_VIEWED == 'true')) echo '<a href="' . twe_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PRODUCTS_VIEWED . '</a><br>';
  if (($admin_access->fields['stats_products_purchased'] == '1') && (STATS_PRODUCTS_PURCHASED == 'true')) echo '<a href="' . twe_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_PRODUCTS_PURCHASED . '</a><br>';
  if (($admin_access->fields['stats_customers'] == '1') && (STATS_CUSTOMERS == 'true')) echo '<a href="' . twe_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_STATS_CUSTOMERS . '</a><br>';
  if (($admin_access->fields['stats_sales_report'] == '1') && (SALES_REPORT == 'true')) echo '<a href="' . twe_href_link(FILENAME_SALES_REPORT, 'selected_box=reports', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_SALES_REPORT . '</a><br>';
  if (($admin_access->fields['stats_stock_warning'] == '1')&& (STATS_STOCK_WARNING == 'true')) echo '<a href="' . twe_href_link(FILENAME_STATS_STOCK_WARNING, 'selected_box=reports', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_STOCK_WARNING . '</a><br>';
?>
	</div>
</div>
<?php if($_SESSION['selected_box'] == 'reports'){ ?>
<script language="JavaScript" type="text/javascript">
var reports = new Spry.Widget.CollapsiblePanel("reports", { enableAnimation: false,contentIsOpen: true});
</script>
<?php }else{ ?>
<script language="JavaScript" type="text/javascript">
var reports = new Spry.Widget.CollapsiblePanel("reports", { enableAnimation: false,contentIsOpen: false});
</script>
<?php } ?>

