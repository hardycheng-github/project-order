<?php
/* --------------------------------------------------------------
   $Id: news_new_product.php,v 1.12 2005/03/16 15:01:16 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.140 2003/03/24); www.oscommerce.com
   (c) 2003  nextcommerce (categories.php,v 1.37 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License
   --------------------------------------------------------------
   Third Party contribution:
   Enable_Disable_Categories 1.3               Autor: Mikel Williams | mikel@ladykatcostumes.com
   New Attribute Manager v4b                   Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   Category Descriptions (Version: 1.5 MS2)    Original Author:   Brian Lowe <blowe@wpcusrgrp.org> | Editor: Lord Illicious <shaolin-venoms@illicious.net>
   Customers Status v3.x  (c) 2002-2003 Copyright Elari elari@free.fr | www.unlockgsm.com/dload-osc/ | CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist

   Released under the GNU General Public License
   --------------------------------------------------------------*/


   if ( ($_GET['pID']) && (!$_POST) ) {
      $product_query = "select p.products_fsk18,
	                                        p.product_template,
                                            pd.products_name,
                                            pd.products_description,
                                            pd.products_short_description,
                                            pd.products_meta_title,
                                            pd.products_meta_description,
                                            pd.products_meta_keywords,
                                            pd.products_url,
                                            p.products_id,
                                            p.group_ids,
                                            p.products_sort,
                                            p.products_model,
                                            p.products_image,
                                            p.products_discount_allowed,
                                            p.products_date_added,
											p.products_status,
                                            p.products_last_modified,
                                            date_format(p.products_date_available, '%Y-%m-%d') as products_date_available,
                                            p.manufacturers_id from " . TABLE_NEWS_PRODUCTS . " p,
                                            " . TABLE_NEWS_PRODUCTS_DESCRIPTION . " pd
                                            where p.products_id = '" . (int)$_GET['pID'] . "'
                                            and p.products_id = pd.products_id
                                            and pd.language_id = '" . $_SESSION['languages_id'] . "'";

      $product = $db->Execute($product_query);
      $pInfo = new objectInfo($product->fields);

    } elseif ($_POST) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
      $products_short_description = $_POST['products_short_description'];
      $products_meta_title = $_POST['products_meta_title'];
      $products_meta_description = $_POST['products_meta_description'];
      $products_meta_keywords = $_POST['products_meta_keywords'];
      $products_url = $_POST['products_url'];
    } else {
      $pInfo = new objectInfo(array());
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers = $db->Execute("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while (!$manufacturers->EOF) {
      $manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'],
                                     'text' => $manufacturers->fields['manufacturers_name']);
   $manufacturers->MoveNext();
    }

    $languages = twe_get_languages();

?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<tr><td>
 <?php $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product'; ?>
<?php $fsk18_array=array(array('id'=>0,'text'=>NO),array('id'=>1,'text'=>YES)); ?>
<?php echo twe_draw_form('new_product', FILENAME_NEWS_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID'] . '&action='.$form_action, 'post', 'enctype="multipart/form-data"'); ?>
<span class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, twe_output_generated_news_category_path($current_category_id)); ?></span><br>
<table width="100%"  border="0">
  <tr>
    <td>       
      <table width="100%" border="0">
        <tr>
          <td class="main" width="40%"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?>
              <small>(YYYY-MM-DD)</small></td>
          <td class="main"><?php echo twe_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
              <script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
        </tr>
		      <tr>
	  <td colspan="4"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
	 </tr>		  
      </table>
      <span class="main">      <br>
      </span></td>
    <td><table bgcolor="f3f3f3" style="border: 1px solid; border-color: #cccccc;" "width="60%"  border="0">
    <tr>
        <td><span class="main"><?php echo TEXT_PRODUCTS_SORT; ?></span></td>
        <td><span class="main"><?php echo  twe_draw_input_field('products_sort', $pInfo->products_sort); ?></span></td>
      </tr>
              <tr>
        <td><span class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></span></td>
        <td><span class="main"><?php echo  twe_draw_input_field('products_model', $pInfo->products_model); ?></span></td>
      </tr>
      <tr>
        <td><span class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></span></td>
        <td><span class="main"><?php echo twe_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></span></td>
      </tr>
	  <tr>
        <td><span class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></span></td>
        <td><span class="main"><?php echo twe_draw_file_field('products_image') . '<br>' . twe_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_image . twe_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></span></td>
      </tr>
      <tr>
        <td><span class="main"><?php echo TEXT_FSK18; ?></span></td>
        <td><span class="main"><?php echo twe_draw_pull_down_menu('fsk18', $fsk18_array, $pInfo->products_fsk18); ?></span></td>
      </tr>
       <tr>
          <?php
        $files=array();
 if ($dir= opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_info/')){
 while  (($file = readdir($dir)) !==false) {
        if (is_file( DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/news_product_info/'.$file) and ($file !="index.html")){
        $files[]=array(
                        'id' => $file,
                        'text' => $file);
        }//if
        } // while
        closedir($dir);
 }
 $default_array=array();
 // set default value in dropdown!
if ($content['content_file']=='') {
$default_array[]=array('id' => 'default','text' => TEXT_SELECT);
$default_value=$pInfo->product_template;
$files=array_merge($default_array,$files);
} else {
$default_array[]=array('id' => 'default','text' => TEXT_NO_FILE);
$default_value=$pInfo->product_template;
$files=array_merge($default_array,$files);
}
echo '<td class="main">'.CHOOSE_INFO_TEMPLATE.':</td>';
echo '<td><span class="main">'.twe_draw_pull_down_menu('info_template',$files,$default_value);
?>
        </span></td>
      </tr>
    </table></td>
  </tr>
</table>
  <br><br>
  <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i++) { ?>
  <table width="100%" border="0">
  <tr>
  <td bgcolor="#000000" height="10"></td>
  </tr>
  <tr>
    <td bgcolor="#FFCC33" valign="top" class="main"><?php echo twe_image(DIR_WS_LANGUAGES . $languages[$i]['directory'] .'/'. $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;<?php echo TEXT_PRODUCTS_NAME; ?><?php echo twe_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : twe_get_news_products_name($pInfo->products_id, $languages[$i]['id'])),'size=60'); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo TEXT_PRODUCTS_URL . '&nbsp;<small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?><?php echo twe_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : twe_get_news_products_url($pInfo->products_id, $languages[$i]['id'])),'size=60'); ?></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
    <td class="main" colspan="2"><b><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></b><br>
      <?php echo twe_draw_textarea_field('products_description_' . $languages[$i]['id'], 'soft', '150', '50', (($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : twe_get_news_products_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
  </tr>
  <tr>
    <td class="main" width="60%" rowspan="2" valign="top"><b><?php echo TEXT_PRODUCTS_SHORT_DESCRIPTION; ?></b><br>
      <?php echo twe_draw_textarea_field('products_short_description_' . $languages[$i]['id'], 'soft', '60', '25', (($products_short_description[$languages[$i]['id']]) ? stripslashes($products_short_description[$languages[$i]['id']]) : twe_get_news_products_short_description($pInfo->products_id, $languages[$i]['id']))); ?></td>
    <td class="main"><?php echo TEXT_META_TITLE; ?><br>
      <?php echo twe_draw_textarea_field('products_meta_title[' . $languages[$i]['id'] . ']', 'soft', '40', '1', (($products_meta_title[$languages[$i]['id']]) ? stripslashes($products_meta_title[$languages[$i]['id']]) : twe_get_news_products_meta_title($pInfo->products_id, $languages[$i]['id']))); ?></td>
  </tr>
  <tr>
    <td class="main"><?php echo TEXT_META_DESCRIPTION; ?><br>
      <?php echo twe_draw_textarea_field('products_meta_description[' . $languages[$i]['id'] . ']', 'soft', '40', '3', (($products_meta_description[$languages[$i]['id']]) ? stripslashes($products_meta_description[$languages[$i]['id']]) : twe_get_news_products_meta_description($pInfo->products_id, $languages[$i]['id']))); ?><br>
      <?php echo TEXT_META_KEYWORDS; ?><br>
      <?php echo twe_draw_textarea_field('products_meta_keywords[' . $languages[$i]['id'] . ']', 'soft', '40', '3', (($products_meta_keywords[$languages[$i]['id']]) ? stripslashes($products_meta_keywords[$languages[$i]['id']]) : twe_get_news_products_meta_keywords($pInfo->products_id, $languages[$i]['id']))); ?>
    </td>
  </tr>
</table>

<?php } ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td>
<table width="100%" border="0" bgcolor="f3f3f3" style="border: 1px solid; border-color: #cccccc;">
          <tr><td colspan="4"><?php echo twe_draw_separator('pixel_trans.gif', '1', '10'); ?></td></tr>
          <?php
if (GROUP_CHECK=='true') {
$customers_statuses_array = twe_get_customers_statuses();
$customers_statuses_array=array_merge(array(array('id'=>'all','text'=>TXT_ALL)),$customers_statuses_array);
?>
<tr>
<td style="border-top: 1px solid; border-color: #ff0000;" valign="top" class="main" ><?php echo ENTRY_CUSTOMERS_STATUS; ?></td>
<td style="border-top: 1px solid; border-left: 1px solid; border-color: #ff0000;"  bgcolor="#FFCC33" class="main">
<?php

for ($i=0;$n=sizeof($customers_statuses_array),$i<$n;$i++) {
if (strstr($pInfo->group_ids,'c_'.$customers_statuses_array[$i]['id'].'_group')) {

$checked='checked ';
} else {
$checked='';
}
echo '<input type="checkbox" name="groups[]" value="'.$customers_statuses_array[$i]['id'].'"'.$checked.'> '.$customers_statuses_array[$i]['text'].'<br>';
}
?>
</td>
</tr>
<?php
}
?>
</table>
</td></tr>

<tr><td>
</td></tr>

    <tr>
      <td class="main" align="right"><?php echo twe_draw_hidden_field('products_date_added', (($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . twe_image_submit('button_save.gif', IMAGE_SAVE,'style="cursor:hand" onClick="return confirm(\''.SAVE_ENTRY.'\')"') . '&nbsp;&nbsp;<a href="' . twe_href_link(FILENAME_NEWS_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . twe_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
    </tr></form>