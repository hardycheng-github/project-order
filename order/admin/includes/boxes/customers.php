<?php
/* --------------------------------------------------------------
   $Id: customers.php,v 1.5 2005/04/17 16:31:26 oldpa Exp $

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
<div class="CollapsiblePanel" id="customers">
<div class="CollapsiblePanelTab" tabindex="0"><?php echo BOX_HEADING_CUSTOMERS; ?></div>
<div class="CollapsiblePanelContent">
<?php
if (($admin_access->fields['customers'] == '1') && (CUSTOMERS == 'true')) echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS, 'selected_box=customers', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CUSTOMERS . '</a><br>';
if (($admin_access->fields['customers_status'] == '1') && (CUSTOMERS_STATUS == 'true')) echo '<a href="' . twe_href_link(FILENAME_CUSTOMERS_STATUS, 'selected_box=customers', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_CUSTOMERS_STATUS . '</a><br>';
if (($admin_access->fields['orders'] == '1') && (ORDERS == 'true')) echo '<a href="' . twe_href_link(FILENAME_ORDERS, 'selected_box=customers', 'NONSSL') . '" class="menuBoxContentLink">'.twe_image(DIR_WS_IMAGES . 'nav.gif', '', '', '').'' . BOX_ORDERS . '</a><br>';
?>
</div>
</div>
<?php if($_SESSION['selected_box'] == 'customers'){ ?>
<script language="JavaScript" type="text/javascript">
var customers = new Spry.Widget.CollapsiblePanel("customers", { enableAnimation: false,contentIsOpen: true});
</script>
<?php }else{ ?>
<script language="JavaScript" type="text/javascript">
var customers = new Spry.Widget.CollapsiblePanel("customers", { enableAnimation: false,contentIsOpen: false});
</script>
<?php } ?>

           