<?php
/* -----------------------------------------------------------------------------------------
   $Id: layout_controller.php,v 1.3 2008/03/16 14:59:01 oldpa Exp $   

   TWE-Commerce - community made shopping   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org   
   (c) 2003	 xt-commerce  www.xt-commerce.com
   Released under the GNU General Public License 
-----------------------------------------------------------------------------------------*/

  require('includes/application_top.php');

if ($dir= opendir(DIR_FS_CATALOG.'templates/')){
  $templates_array = array();
 while  (($templates = readdir($dir)) !==false) {
        if (is_dir( DIR_FS_CATALOG.'templates/'."//".$templates) and ($templates !="CVS") and ($templates!=".") and ($templates !="..")) {
        $templates_array[]=$templates; 
        }
        }
        closedir($dir);
        sort($templates_array);
} 
//print_r($templates_array);
$check_data=array();
$layout1 = $db->Execute("select layout_template_name from layout_template");
while (!$layout1->EOF) {
$check_data[] = $layout1->fields['layout_template_name'];
$result = array_diff_assoc($check_data,$templates_array);
$layout_template_name = pos($result);
//print_r($layout_template_name);
if($layout_template_name) $db->Execute("delete from layout_template where layout_template_name= '" .$layout_template_name. "'");
if($layout_template_name) $db->Execute("delete from " . TABLE_LAYOUT_BOXES . " where layout_template = '" .$layout_template_name. "'");
$layout1->MoveNext();
}

$warning_new_template='';
    //  $templates_array = array();
  for ($i = 0, $n = sizeof($templates_array); $i < $n; $i++) {
    $file = $templates_array[$i];
    
    $layout = $db->Execute("select layout_template_name from layout_template where layout_template_name = '" . $templates_array[$i] . "'");
	    if (!$layout->fields['layout_template_name']) {
      $warning_new_template .= $file . ' ';
      $db->Execute("insert into layout_template
                  (layout_template_id, layout_template_name, layout_template_logo, layout_template_left_width, layout_template_right_width, layout_template_center_width, layout_template_added)
                  values ('',
                  '" . $templates_array[$i]  . "', 'logo.jpg', 0, 0, 0, now())");
    }
  }
// Check all exisiting boxes are in the main /sideboxes
  $boxes_directory = DIR_FS_CATALOG .'templates/';

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($boxes_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($boxes_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          if ($file != 'empty.txt') {
            $directory_array[] = $file;
          }
        }
      }
    }
    if (sizeof($directory_array)) {
      sort($directory_array);
    }
    $dir->close();
  }
  

// Check all exisiting boxes are in the current template /sideboxes/template_dir
  $dir_check= $directory_array;
  $boxes_directory = DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/';

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  if ($dir = @dir($boxes_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($boxes_directory . $file)) {
          if (in_array($file, $dir_check, TRUE)) {
            // skip name exists
          } else {
            if ($file != 'empty.txt') {
              $directory_array[] = $file;
            }
          }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  $warning_new_box='';
  $installed_boxes = array();
  for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];

// Verify Definitions
    $definitions = $db->Execute("select layout_box_name from " . TABLE_LAYOUT_BOXES . " where layout_box_name='" . $file . "' and layout_template='" . CURRENT_TEMPLATE . "'");
    if ($definitions->EOF) {
      $warning_new_box .= $file . ' ';
      $db->Execute("insert into " . TABLE_LAYOUT_BOXES . "
                  (layout_id, layout_template, layout_box_name, layout_box_status, layout_box_location, layout_box_sort_order, layout_box_sort_order_single, layout_box_status_single)
                  values ('',
                  '" . CURRENT_TEMPLATE  . "', '" . $file . "', 1, 0, 0, 0, 0)");
    }
  }


// Check all center modules
  $boxes_directory = DIR_FS_CATALOG . DIR_WS_MODULES. 'center_modules/';

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
  $directory_array = array();
  if ($dir = @dir($boxes_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($boxes_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == $file_extension) {
          if ($file != 'empty.txt') {
            $directory_array[] = $file;
          }
        }
      }
    }
    if (sizeof($directory_array)) {
      sort($directory_array);
    }
    $dir->close();
  }
  

  $dir_check= $directory_array;
  $boxes_directory = DIR_FS_CATALOG . DIR_WS_MODULES. 'center_modules/';

  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));

  if ($dir = @dir($boxes_directory)) {
    while ($file = $dir->read()) {
      if (!is_dir($boxes_directory . $file)) {
          if (in_array($file, $dir_check, TRUE)) {
            // skip name exists
          } else {
            if ($file != 'empty.txt') {
              $directory_array[] = $file;
            }
          }
      }
    }
    sort($directory_array);
    $dir->close();
  }

  $warning_new_modules='';
  $installed_boxes = array();
  for ($i = 0, $n = sizeof($directory_array); $i < $n; $i++) {
    $file = $directory_array[$i];

// Verify Definitions
    $definitions = $db->Execute("select layout_box_name from " . TABLE_LAYOUT_BOXES . " where layout_box_name='" . $file . "' and layout_template='" . CURRENT_TEMPLATE . "'");
    if ($definitions->EOF) {
      $warning_new_modules .= $file . ' ';
      $db->Execute("insert into " . TABLE_LAYOUT_BOXES . "
                  (layout_id, layout_template, layout_box_name, layout_box_status, layout_box_location, layout_box_sort_order, layout_box_sort_order_single, layout_box_status_single)
                  values ('',
                  '" . CURRENT_TEMPLATE  . "', '" . $file . "', 1, 3, 0, 0, 0)");
    }
  }

////////////////////////////////////
  if ($_GET['action']) {
    switch ($_GET['action']) {
	case 'setflagtype':
	if ($_GET['flagtype'] == '1') {
	  $db->Execute("update " . TABLE_LAYOUT_BOXES . " set layout_box_status = '1' where layout_id = '" . $_GET['id'] . "'");
	  }else if ($_GET['flagtype'] == '0'){
	  $db->Execute("update " . TABLE_LAYOUT_BOXES . " set layout_box_status = '0' where layout_id = '" . $_GET['id'] . "'");
	  }else{
	  return;
	  }
      $messageStack->add_session(SUCCESS_BOX_UPDATED, 'success');
      twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID='.$_GET['id'], 'NONSSL'));
      break;
	case 'setflag':
	  $db->Execute("update " . TABLE_LAYOUT_BOXES . " set layout_box_location = '".$_GET['flag']."' where layout_id = '" . $_GET['id'] . "'");
        $messageStack->add_session(SUCCESS_BOX_UPDATED, 'success');
      twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID='.$_GET['id'], 'NONSSL'));
      break;
      case 'insert':
        $layout_box_name = twe_db_prepare_input($_POST['layout_box_name']);
        $layout_box_status = twe_db_prepare_input($_POST['layout_box_status']);
        $layout_box_location = twe_db_prepare_input($_POST['layout_box_location']);
        $layout_box_sort_order = twe_db_prepare_input($_POST['layout_box_sort_order']);
		$layout_box_sort_order_single = twe_db_prepare_input($_POST['layout_box_sort_order_single']);
        $layout_box_status_single = twe_db_prepare_input($_POST['layout_box_status_single']);
		
        $db->Execute("insert into " . TABLE_LAYOUT_BOXES . "
                    (layout_box_name, layout_box_status, layout_box_location, layout_box_sort_order, layout_box_sort_order_single, layout_box_status_single)
values ('" . twe_db_input($layout_box_name) . "',
                            '" . twe_db_input($layout_box_status) . "',
                            '" . twe_db_input($layout_box_location) . "',
                            '" . twe_db_input($layout_box_sort_order) . "',
                            '" . twe_db_input($layout_box_sort_order_single) . "',
                            '" . twe_db_input($layout_box_status_single) . "')");
							
        $messageStack->add_session(SUCCESS_BOX_ADDED . $_GET['layout_box_name'], 'success');
        twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER));
        break;
      case 'save':
        $box_id = twe_db_prepare_input($_GET['cID']);
        // $layout_box_name = twe_db_prepare_input($_POST['layout_box_name']);
        $layout_box_status = twe_db_prepare_input($_POST['layout_box_status']);
        $layout_box_location = twe_db_prepare_input($_POST['layout_box_location']);
        $layout_box_sort_order = twe_db_prepare_input($_POST['layout_box_sort_order']);
		$layout_box_sort_order_single = twe_db_prepare_input($_POST['layout_box_sort_order_single']);
        $layout_box_status_single = twe_db_prepare_input($_POST['layout_box_status_single']);


        $db->Execute("update " . TABLE_LAYOUT_BOXES . " set layout_box_status = '" . twe_db_input($layout_box_status) . "', layout_box_location = '" . twe_db_input($layout_box_location) . "', layout_box_sort_order = '" . twe_db_input($layout_box_sort_order) . "', layout_box_sort_order_single = '" . twe_db_input($layout_box_sort_order_single) . "', layout_box_status_single = '" . twe_db_input($layout_box_status_single) . "' where layout_id = '" . twe_db_input($box_id) . "'");

        $messageStack->add_session(SUCCESS_BOX_UPDATED . $_GET['layout_box_name'], 'success');
        twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $box_id));
        break;
	case 'change':
	    $layout_template_left_width = twe_db_prepare_input($_POST['left_width']);
        $layout_template_right_width = twe_db_prepare_input($_POST['right_width']);
        $layout_template_center_width = twe_db_prepare_input($_POST['center_width']);
        $layout_template_width = twe_db_prepare_input($_POST['width']);
		
$db->Execute("update layout_template set layout_template_left_width ='".$layout_template_left_width."', layout_template_width ='".$layout_template_width."',  layout_template_right_width = '".$layout_template_right_width."', layout_template_center_width = '".$layout_template_center_width."'
                                 where layout_template_name = '" . CURRENT_TEMPLATE . "'");
  				 
	function twe_try_upload($file = '', $destination = '', $permissions = '777', $extensions = ''){
  			$file_object = new upload($file, $destination, $permissions, $extensions);
		  	if ($file_object->filename != '') return $file_object; else return false;
			  } 
	$logo= DIR_FS_CATALOG_IMAGES."/";
    if ($logo_name = &twe_try_upload('logo_name', $logo)) {
        $db->Execute("update layout_template set
                                 layout_template_logo ='".$logo_name->filename . "'
                                 where layout_template_name = '" . CURRENT_TEMPLATE . "'");
    }
       
        $messageStack->add_session(SUCCESS_LOGO_CHANGE, 'success');
        twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER));
        break;
      case 'deleteconfirm':
        $box_id = twe_db_prepare_input($_GET['cID']);

        $db->Execute("delete from " . TABLE_LAYOUT_BOXES . " where layout_id = '" . twe_db_input($box_id) . "'");

        $messageStack->add_session(SUCCESS_BOX_DELETED . $_GET['layout_box_name'], 'success');
        twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER));
        break;
      case 'reset_defaults':
	  $template_id = twe_db_prepare_input($_GET['tID']);
        $reset_boxes = $db->Execute("select * from " . TABLE_LAYOUT_BOXES . " where layout_template= '".$template_id."'");
        while (!$reset_boxes->EOF) {
          $db->Execute("update " . TABLE_LAYOUT_BOXES . " set layout_box_status= '" . $reset_boxes->fields['layout_box_status'] . "', layout_box_location= '" . $reset_boxes->fields['layout_box_location'] . "', layout_box_sort_order='" . $reset_boxes->fields['layout_box_sort_order'] . "', layout_box_sort_order_single='" . $reset_boxes->fields['layout_box_sort_order_single'] . "', layout_box_status_single='" . $reset_boxes->fields['layout_box_status_single'] . "' where layout_box_name='" . $reset_boxes->fields['layout_box_name'] . "' and layout_template='" . CURRENT_TEMPLATE . "'");
          $reset_boxes->MoveNext();
        }

        $messageStack->add_session(SUCCESS_BOX_RESET . CURRENT_TEMPLATE, 'success');
        twe_redirect(twe_href_link(FILENAME_LAYOUT_CONTROLLER));
        break;
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script src="includes/SpryCollapsiblePanel.js" type="text/javascript"></script>
</head>
<body>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . ' ' . CURRENT_TEMPLATE; ?></td>
            <td class="pageHeading" align="right"><?php echo twe_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
		  <tr><td><iframe src="<?php echo 'index.php'; ?>" width="100%" height="200" border="0" frameborder="1"></iframe></td></tr>
        </table></td>
      </tr>
      <tr>
        <td>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<?php
if(!$_GET['action']){
	?>
		<tr>
		<td>
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
		  <tr><?php echo  twe_draw_form('logo', FILENAME_LAYOUT_CONTROLLER, 'action=change', 'post','enctype="multipart/form-data"'); ?>
			<td class="main"><?php echo TEXT_CHANGE_LOGO. twe_draw_file_field('logo_name'); ?><?php 
$layout_logo = $db->Execute("select * from layout_template where layout_template_name = '" . CURRENT_TEMPLATE . "'");
if(file_exists(DIR_FS_CATALOG_IMAGES.$layout_logo->fields['layout_template_logo'])){		  
$img = DIR_FS_CATALOG_IMAGES.$layout_logo->fields['layout_template_logo'];
$size = GetImageSize("$img");
echo $layout_logo->fields['layout_template_logo'].'-->'.$size[0].'X'.$size[1];
}; ?></td>
            <td class="main"><?php echo WIDTH.twe_draw_input_field('width',$layout_logo->fields['layout_template_width'],'size=5'); ?></td>
            <td class="main"><?php echo LEFT_WIDTH.twe_draw_input_field('left_width',$layout_logo->fields['layout_template_left_width'],'size=5'); ?></td>
            <td class="main"><?php echo RIGHT_WIDTH.twe_draw_input_field('right_width',$layout_logo->fields['layout_template_right_width'],'size=5'); ?></td>
            <td class="main"><?php echo CENTER_WIDTH.twe_draw_input_field('center_width',$layout_logo->fields['layout_template_center_width'],'size=5'); ?></td>
		<td><?php echo twe_image_submit('button_save.gif', IMAGE_SAVE); ?></td>
          </tr>
		  </table>
		  </td></tr>
		  <?php
}

		  ?>
              <tr>
                <td class="main" align="left"><strong><?php echo BOXES_PATH; ?></strong><?php echo DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/' . ' ... ' . '<br />&nbsp;'; ?></td>
              </tr>
			  <tr>
                <td class="main" align="left"><strong><?php echo MODULES_PATH; ?></strong><?php echo DIR_FS_CATALOG .'includes/modules/center_modules/' . ' ... ' . '<br />&nbsp;'; ?></td>
              </tr>
<?php
if ($warning_new_template) {
?>
        <tr class="messageStackError">
          <td colspan="2" class="messageStackError">
<?php echo 'WARNING:'. TEMPLATE_FOUND . $warning_new_template; ?>
          </td>
        </tr>
<?php
}
?>
<?php
if ($warning_new_box) {
?>
        <tr class="messageStackError">
          <td colspan="2" class="messageStackError">
<?php echo 'WARNING:'. BOXES_FOUND . $warning_new_box; ?>
          </td>
        </tr>
<?php
}
?>
<?php
if ($warning_new_modules) {
?>
        <tr class="messageStackError">
          <td colspan="2" class="messageStackError">
<?php echo 'WARNING:'. BOXES_FOUND . $warning_new_modules; ?>
          </td>
        </tr>
<?php
}
?>
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="left" width="200"><?php echo TABLE_HEADING_LAYOUT_BOX_NAME_LEFT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAYOUT_BOX_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAYOUT_BOX_LOCATION; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAYOUT_BOX_SORT_ORDER; ?></td>
				<td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAYOUT_BOX_SORT_ORDER_SINGLE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>

<?php
  $boxes_directory = DIR_FS_CATALOG .'templates/'.CURRENT_TEMPLATE. '/source/boxes/';
  $boxes_directory_template = DIR_FS_CATALOG .'templates/';
  $modules_directory = DIR_FS_CATALOG . DIR_WS_MODULES. 'center_modules/';

  $column_controller = $db->Execute("select layout_id, layout_box_name, layout_box_status, layout_box_location, layout_box_sort_order, layout_box_sort_order_single, layout_box_status_single from " . TABLE_LAYOUT_BOXES . " where layout_template='" . CURRENT_TEMPLATE . "' order by  layout_box_location, layout_box_sort_order");
  while (!$column_controller->EOF) {
  $tick = '<table width="80"  border="1" cellspacing="1" cellpadding="1">
     <tr>';
	if($column_controller->fields['layout_box_location'] == 0){
    $tick .=  '<td width="16">'.twe_image(DIR_WS_ICONS.'tick.gif').'</td>
	           <td width="16">&nbsp;</td>
	           <td width="16">&nbsp;</td>
			   <td width="16">&nbsp;</td>
	           <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=1&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>';
    }elseif($column_controller->fields['layout_box_location'] == 1){
	 $tick .=  '<td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=0&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
	           <td width="16">&nbsp;</td>
	           <td width="16">&nbsp;</td>
			   <td width="16">&nbsp;</td>
	           <td width="16">'.twe_image(DIR_WS_ICONS.'tick.gif').'</td>';
	}elseif($column_controller->fields['layout_box_location'] == 2){
	 $tick .=  '<td width="16">&nbsp;</td>
	           <td width="16">'.twe_image(DIR_WS_ICONS.'tick.gif').'</td>
	           <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=3&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
			   <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=4&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
	           <td width="16">&nbsp;</td>';
	}elseif($column_controller->fields['layout_box_location'] == 3){
	$tick .=  '<td width="16">&nbsp;</td>
	           <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=2&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
	           <td width="16">'.twe_image(DIR_WS_ICONS.'tick.gif').'</td>
			   <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=4&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
	           <td width="16">&nbsp;</td>';
	}elseif($column_controller->fields['layout_box_location'] == 4){
	$tick .=  '<td width="16">&nbsp;</td>
	           <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=2&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
	           <td width="16"><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflag&flag=3&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_LAYOUT_RED, 10, 10) . '</a></td>
			   <td width="16">'.twe_image(DIR_WS_ICONS.'tick.gif').'</td>
	           <td width="16">&nbsp;</td>';
	}	
	$tick .= '</tr></table>';
//    if (((!$_GET['cID']) || (@$_GET['cID'] == $column_controller->fields['layout_id'])) && (!$bInfo) && (substr($_GET['action'], 0, 3) != 'new')) {
  if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $column_controller->fields['layout_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
      $bInfo = new objectInfo($column_controller->fields);
    }

//  if ( (is_object($bInfo)) && ($column_controller->fields['layout_id'] == $bInfo->layout_id) ) {
    if (isset($bInfo) && is_object($bInfo) && ($column_controller->fields['layout_id'] == $bInfo->layout_id)) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $column_controller->fields['layout_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent" width="100"><?php echo (file_exists($boxes_directory_template . $column_controller->fields['layout_box_name']) ? '<span class="alert">' . preg_replace('^'.DIR_FS_CATALOG.'^', '', $column_controller->fields['layout_box_name']) . '</span>' . $column_controller->fields['layout_box_name'] : preg_replace('^'.DIR_FS_CATALOG.'^' , '', $column_controller->fields['layout_box_name'])); ?></td>
				
                <td class="<?php echo ( (file_exists($boxes_directory . $column_controller->fields['layout_box_name']) or file_exists($boxes_directory_template . $column_controller->fields['layout_box_name'])or file_exists($modules_directory . $column_controller->fields['layout_box_name'])) ? dataTableContent : messageStackError ); ?>" align="center"><?php  echo ($column_controller->fields['layout_box_status']=='1' ? twe_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflagtype&flagtype=0&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>' : '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'action=setflagtype&flagtype=1&id=' . $column_controller->fields['layout_id'], 'NONSSL') . '">' . twe_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . twe_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10)); ?></td>
				
                <td class="<?php echo ( (file_exists($boxes_directory . $column_controller->fields['layout_box_name']) or file_exists($boxes_directory_template . $column_controller->fields['layout_box_name'])or file_exists($modules_directory . $column_controller->fields['layout_box_name'])) ? dataTableContent : messageStackError ); ?>" align="center"><?php if($column_controller->fields['layout_box_status_single']=='0') echo $tick; ?></td>
				
                <td class="<?php echo ( (file_exists($boxes_directory . $column_controller->fields['layout_box_name']) or file_exists($boxes_directory_template . $column_controller->fields['layout_box_name'])or file_exists($modules_directory . $column_controller->fields['layout_box_name'])) ? dataTableContent : messageStackError ); ?>" align="center"><?php  echo $column_controller->fields['layout_box_sort_order']; ?></td>
				
				<td class="<?php echo ( (file_exists($boxes_directory . $column_controller->fields['layout_box_name']) or file_exists($boxes_directory_template . $column_controller->fields['layout_box_name'])or file_exists($modules_directory . $column_controller->fields['layout_box_name'])) ? dataTableContent : messageStackError ); ?>" align="center"><?php echo ($column_controller->fields['layout_box_status_single']=='1' ? TEXT_ON : '<span class="alert">' . TEXT_OFF . '</span>'); ?></td>

                <td class="dataTableContent" align="right"><?php echo ( (file_exists($boxes_directory . $column_controller->fields['layout_box_name']) or file_exists($boxes_directory_template . $column_controller->fields['layout_box_name'])or file_exists($modules_directory . $column_controller->fields['layout_box_name'])) ? TEXT_GOOD_BOX : TEXT_BAD_BOX) ; ?><?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $column_controller->fields['layout_id'] . '&action=edit') . '">' . twe_image(DIR_WS_IMAGES . 'icon_edit.gif', IMAGE_EDIT) . '</a>'; ?></td>
				
                
              </tr>

<?php
    $last_box_column = $column_controller->fields['layout_box_location'];
    $column_controller->MoveNext();
    if (($column_controller->fields['layout_box_location'] !=$last_box_column) and !$column_controller->EOF) {
?>
   <tr valign="top">
                <td colspan="7" height="20" align="center" valign="middle"><?php echo twe_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
              </tr>
			  <?php
    }
	
  }
?>

              <tr valign="top">
                <td valign="top"><?php echo twe_draw_separator('pixel_trans.gif', '75%', '10'); ?></td>
              </tr>

            </table></td>
<?php
  $heading = array();
  $contents = array();

    switch ($bInfo->layout_box_status) {
      case '0': $layout_box_status_status_on = false; $layout_box_status_status_off = true; break;
      case '1':
      default: $layout_box_status_status_on = true; $layout_box_status_status_off = false;
    }
    switch ($bInfo->layout_box_status_single) {
      case '0': $layout_box_status_single_on = false; $layout_box_status_single_off = true; break;
      case '1':
      default: $layout_box_status_single_on = true; $layout_box_status_single_off = false;
    }

  switch ($_GET['action']) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_BOX . '</b>');

      $contents = array('form' => twe_draw_form('column_controller', FILENAME_LAYOUT_CONTROLLER, 'action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_NAME . '<br />' . twe_draw_input_field('layout_box_name'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_STATUS . '<br />' . twe_draw_input_field('layout_box_status'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_LOCATION . '<br />' . twe_draw_input_field('layout_box_location'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_SORT_ORDER . '<br />' . twe_draw_input_field('layout_box_sort_order'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_SORT_ORDER_SINGLE . '<br />' . twe_draw_input_field('layout_box_sort_order_single'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_STATUS_SINGLE . '<br />' . twe_draw_input_field('layout_box_status_single'));

      $contents[] = array('align' => 'center', 'text' => '<br />' . twe_image_submit('button_insert.gif', IMAGE_INSERT) . '&nbsp;<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'edit':
      switch ($bInfo->layout_box_status) {
        case '0': $in_status = false; $out_status = true; break;
        case '1': $in_status = true; $out_status = false; break;
        default: $in_status = true; $out_status = false;
      }
      switch ($bInfo->layout_box_location) {
        case '0': $left_status = true; $right_status = false; break;
        case '1': $left_status = false; $right_status = true; break;
		case '2': $center_left_status = true; $center_right_status = false; $center_status= false; break;
        case '3': $center_left_status = false; $center_right_status = false; $center_status= true; break;
        case '4': $center_left_status = false; $center_right_status = true; $center_status= false; break;
        default: $center_left_status = false; $center_right_status = false; $center_status= true;
      }
      switch ($bInfo->layout_box_status_single) {
        case '0': $in_status_single = false; $out_status_single = true; break;
        case '1': $in_status_single = true; $out_status_single = false; break;
        default: $in_status_single = true; $out_status_single = false;
      }

      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_BOX . '</b>');

      $contents = array('form' => twe_draw_form('column_controller', FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&action=save' . '&layout_box_name=' . $bInfo->layout_box_name));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_NAME . ' ' . $bInfo->layout_box_name);
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_STATUS . '<br />' . twe_draw_radio_field('layout_box_status', '1', $in_status) . TEXT_ON . twe_draw_radio_field('layout_box_status', '0', $out_status) . TEXT_OFF);
	 	  if($bInfo->layout_box_location == '0' || $bInfo->layout_box_location == '1'){ 
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_LOCATION . '<br />' . twe_draw_radio_field('layout_box_location', '0', $left_status) . TEXT_LEFT . twe_draw_radio_field('layout_box_location', '1', $right_status) . TEXT_RIGHT);
	  }else{
	 $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_LOCATION . '<br />' . twe_draw_radio_field('layout_box_location', '2', $center_left_status) . TEXT_CENTER_LEFT . twe_draw_radio_field('layout_box_location', '3', $center_status) . TEXT_CENTER . twe_draw_radio_field('layout_box_location', '4', $center_right_status) . TEXT_CENTER_RIGHT); 
	  }
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_SORT_ORDER . '<br />' . twe_draw_input_field('layout_box_sort_order', $bInfo->layout_box_sort_order,'size="4"'));
	  if($bInfo->layout_box_location == '0' || $bInfo->layout_box_location == '1'){
	  $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_SORT_ORDER_SINGLE . '<br />' . twe_draw_input_field('layout_box_sort_order_single', $bInfo->layout_box_sort_order_single,'size="4"'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_LAYOUT_BOX_STATUS_SINGLE . '<br />' . twe_draw_radio_field('layout_box_status_single', '1', $in_status_single) . TEXT_ON . twe_draw_radio_field('layout_box_status_single', '0', $out_status_single) . TEXT_OFF);
		}
      $contents[] = array('align' => 'center', 'text' => '<br />' . twe_image_submit('button_update.gif', IMAGE_UPDATE) . '<br><a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&layout_box_name=' . $bInfo->layout_box_name) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_BOX . '</b>');

      $contents = array('form' => twe_draw_form('column_controller', FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&action=deleteconfirm' . '&layout_box_name=' . $bInfo->layout_box_name));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $bInfo->layout_box_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . twe_image_submit('button_delete.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (is_object($bInfo)) {
        $heading[] = array('text' => '<strong>' . TEXT_INFO_LAYOUT_BOX . $bInfo->layout_box_name . '</strong>');
        $contents[] = array('align' => 'left', 'text' => '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&action=edit') . '">' . twe_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
        $contents[] = array('text' => '<strong>' . TEXT_INFO_BOX_DETAILS . '<strong>');
        $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_NAME . ' ' . $bInfo->layout_box_name);
        $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_STATUS . ' ' .  ($bInfo->layout_box_status=='1' ? TEXT_ON : TEXT_OFF) );
			  if($bInfo->layout_box_location == '0' || $bInfo->layout_box_location == '1'){
        $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_LOCATION . ' ' . ($bInfo->layout_box_location=='0' ? TEXT_LEFT : TEXT_RIGHT) );
		}
        $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_SORT_ORDER . ' ' . $bInfo->layout_box_sort_order);
$contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_SORT_ORDER_SINGLE . ' ' . $bInfo->layout_box_sort_order_single);
        $contents[] = array('text' => TEXT_INFO_LAYOUT_BOX_STATUS_SINGLE . ' ' .  ($bInfo->layout_box_status_single=='1' ? TEXT_ON : TEXT_OFF) );

        if (!(file_exists($boxes_directory . $bInfo->layout_box_name) or file_exists($boxes_directory_template . $bInfo->layout_box_name)or file_exists($modules_directory . $bInfo->layout_box_name))) {
          $contents[] = array('align' => 'left', 'text' => '<br /><strong>' . TEXT_INFO_DELETE_MISSING_LAYOUT_BOX . '<br />' . CURRENT_TEMPLATE . '</strong>');
          $contents[] = array('align' => 'left', 'text' => TEXT_INFO_DELETE_MISSING_LAYOUT_BOX_NOTE . '<strong>' . $bInfo->layout_box_name . '</strong>');
          $contents[] = array('align' => 'left', 'text' => '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'cID=' . $bInfo->layout_id . '&action=delete' . '&layout_box_name=' . $bInfo->layout_box_name) . '">' . twe_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        }
      }
      break;
  }

  if ( (twe_not_null($heading)) && (twe_not_null($contents)) ) {
    echo "\n" . '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
  </tr>
  <tr>
    <td><table align="center">
      
      <tr>
        <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_1&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_1.jpg') . '</a><br>templates_1'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_2&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_2.jpg') . '</a><br>templates_2'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_3&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_3.jpg') . '</a><br>templates_3'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_4&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_4.jpg') . '</a><br>templates_4'; ?>
        </td>
      </tr>
	  <tr>
        <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_5&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_5.jpg') . '</a><br>templates_5'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_6&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_6.jpg') . '</a><br>templates_6'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_7&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_7.jpg') . '</a><br>templates_7'; ?>
        </td>
		 <td class="main" align="center">
          <?php echo '<a href="' . twe_href_link(FILENAME_LAYOUT_CONTROLLER, 'tID=templates_8&action=reset_defaults') . '">' . twe_image(DIR_WS_IMAGES.'templates_8.jpg') . '</a><br>templates_8'; ?>
        </td>
      </tr>
    </table></td>
  </tr>  
  <tr valign="top">
    <td valign="top"><?php echo twe_draw_separator('pixel_trans.gif', '1', '100'); ?></td>
  </tr>

<!-- end of display -->

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
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
