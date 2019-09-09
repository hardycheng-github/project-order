<?php
/* --------------------------------------------------------------
   $Id: configuration.php,v 1.4 2005/03/29 17:05:18 oldpa Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(configuration.php,v 1.40 2002/12/29); www.oscommerce.com 
   (c) 2003	 nextcommerce (configuration.php,v 1.16 2003/08/19); www.nextcommerce.org
   (c) 2003-2004 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require('includes/application_top.php');

  if ($_GET['action']) {
    switch ($_GET['action']) {
      case 'save':
          $configuration = $db->Execute("select configuration_key,configuration_id, configuration_value, use_function,set_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$_GET['gID'] . "' order by sort_order");

          while (!$configuration->EOF){
              $db->Execute("UPDATE ".TABLE_CONFIGURATION." SET configuration_value='".$_POST[$configuration->fields['configuration_key']]."' where configuration_key='".$configuration->fields['configuration_key']."'");
            $configuration->MoveNext();
			 }
			  $messageStack->add_session(SUCCESS_LAST_MOD, 'success');
			  twe_redirect(FILENAME_CONFIGURATION. '?gID=' . (int)$_GET['gID']);
        break;

    }
  }

  $cfg_group_query = "select configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$_GET['gID'] . "'";
  $cfg_group = $db->Execute($cfg_group_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
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
    <td class="boxCenter" width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo twe_image(DIR_WS_ICONS.'heading_configuration.gif'); ?></td>
    <td class="pageHeading"><?php echo $cfg_group->fields['configuration_group_title']; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">TWE Configuration</td>
  </tr>
</table> </td>
      </tr>
      <tr>
        <td style="border-top: 3px solid; border-color: #cccccc;"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" align="right">
<?php echo twe_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . (int)$_GET['gID'] . '&action=save'); ?>
            <table width="100%"  border="0" cellspacing="0" cellpadding="4">
<?php
  $configuration = $db->Execute("select configuration_key,configuration_id, configuration_value, use_function,set_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$_GET['gID'] . "' order by sort_order");

  while (!$configuration->EOF) {
    if ($_GET['gID'] == 6) {
      switch ($configuration->fields['configuration_key']) {
        case 'MODULE_PAYMENT_INSTALLED':
          if ($configuration->fields['configuration_value'] != '') {
            $payment_installed = explode(';', $configuration->fields['configuration_value']);
            for ($i = 0, $n = sizeof($payment_installed); $i < $n; $i++) {
              include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/modules/payment/' . $payment_installed[$i]);
            }
          }
          break;

        case 'MODULE_SHIPPING_INSTALLED':
          if ($configuration->fields['configuration_value'] != '') {
            $shipping_installed = explode(';', $configuration->fields['configuration_value']);
            for ($i = 0, $n = sizeof($shipping_installed); $i < $n; $i++) {
              include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/modules/shipping/' . $shipping_installed[$i]);			
            }
          }
          break;

        case 'MODULE_ORDER_TOTAL_INSTALLED':
          if ($configuration->fields['configuration_value'] != '') {
            $ot_installed = explode(';', $configuration->fields['configuration_value']);
            for ($i = 0, $n = sizeof($ot_installed); $i < $n; $i++) {
              include(DIR_FS_LANGUAGES . $_SESSION['language'] . '/modules/order_total/' . $ot_installed[$i]);			
            }
          }
          break;
      }
    }
    if (twe_not_null($configuration->fields['use_function'])) {
      $use_function = $configuration->fields['use_function'];
      if (preg_match('/->/', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include(DIR_WS_CLASSES . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = twe_call_function($class_method[1], $configuration->fields['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = twe_call_function($use_function, $configuration->fields['configuration_value']);
      }
    } else {
      $cfgValue = $configuration->fields['configuration_value'];
    }

    if (((!$_GET['cID']) || (@$_GET['cID'] == $configuration->fields['configuration_id'])) && (!$cInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
      $cfg_extra_query = "select configuration_key,configuration_value, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . $configuration->fields['configuration_id'] . "'";
      $cfg_extra = $db->Execute($cfg_extra_query);

      $cInfo_array = twe_array_merge($configuration->fields, $cfg_extra->fields);
      $cInfo = new objectInfo($cInfo_array);
    }
    if ($configuration->fields['set_function']) {
        eval('$value_field = ' . $configuration->fields['set_function'] . '"' . htmlspecialchars($configuration->fields['configuration_value']) . '");');
      } else {
        $value_field = twe_draw_input_field($configuration->fields['configuration_key'], $configuration->fields['configuration_value'],'size=40');
      }
   // add

   if (strstr($value_field,'configuration_value')) $value_field=str_replace('configuration_value',$configuration->fields['configuration_key'],$value_field);

   echo '
  <tr>
    <td width="200" valign="top" class="dataTableContent"><b>'.constant(strtoupper($configuration->fields['configuration_key'].'_TITLE')).'</b></td>
    <td valign="top" class="dataTableContent">
    <table width="100%"  border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="45%" style="background-color:#DFEDFF ; border: 1px solid; border-color: #CCCCCC;" class="dataTableContent">'.$value_field.'</td><td style="background-color:#CCCCCC ; border: 1px solid; border-color: #CCCCCC;" class="dataTableContent">
        '.constant(strtoupper( $configuration->fields['configuration_key'].'_DESC')).'</td>
      </tr>
    </table>
    </td>
  </tr>
  ';
$configuration->MoveNext();
  }
?>
            </table>
<?php echo twe_image_submit('button_save.gif', IMAGE_SAVE); ?></form>
            </td>

          </tr>
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
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>