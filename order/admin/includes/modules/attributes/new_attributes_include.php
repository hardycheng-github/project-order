<?php
/* --------------------------------------------------------------
   $Id: new_attributes_include.php,v 1.4 2004/02/20 12:52:59 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes_functions); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_attributes_include.php,v 1.11 2003/08/21); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

   // include needed functions

   require_once(DIR_FS_INC .'twe_get_tax_rate.inc.php');
   require_once(DIR_FS_INC .'twe_get_tax_class_id.inc.php');
   require_once(DIR_FS_INC .'twe_format_price.inc.php');
?>
  <tr>
    <td class="pageHeading" colspan="3"><?php echo $pageTitle; ?></td>
  </tr>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="SUBMIT_ATTRIBUTES"><input type="hidden" name="current_product_id" value="<?php echo $_POST['current_product_id']; ?>"><input type="hidden" name="action" value="change">
<?php
echo twe_draw_hidden_field(twe_session_name(), twe_session_id());

  if ($cPath) echo '<input type="hidden" name="cPathID" value="' . $cPath . '">';

  require('new_attributes_functions.php');

  // Temp id for text input contribution.. I'll put them in a seperate array.
  $tempTextID = '1999043';

  // Lets get all of the possible options
  $query = "SELECT * FROM products_options where products_options_id LIKE '%' AND language_id = '" . $_SESSION['languages_id'] . "'";
  $line = $db->Execute($query) or die(mysql_error());
  $matches = $line->RecordCount() >0;

  if ($matches) {
    while (!$line->EOF) {
      $current_product_option_name = $line->fields['products_options_name'];
      $current_product_option_id = $line->fields['products_options_id'];
      // Print the Option Name
      echo "<TR class=\"dataTableHeadingRow\">";
      echo "<TD class=\"dataTableHeadingContent\"><B>" . $current_product_option_name . "</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_SORT_ORDER."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_ATTRIBUTE_MODEL."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_STOCK."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_VALUE_WEIGHT."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_WEIGHT_PREFIX."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_VALUE_PRICE."</B></TD>";
      echo "<TD class=\"dataTableHeadingContent\"><B>".TEXT_PRICE_PREFIX."</B></TD>";

      if ($optionTypeInstalled == '1') {
        echo "<TD class=\"dataTableHeadingContent\"><B>Option Type</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>Quantity</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>Order</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>Linked Attr.</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>ID</B></TD>";
      }

      if ($optionSortCopyInstalled == '1') {
        echo "<TD class=\"dataTableHeadingContent\"><B>Weight</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>Weight Prefix</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>Sort Order</B></TD>";
      }
      echo "</TR>";

      // Find all of the Current Option's Available Values
      $query2 = "SELECT * FROM products_options_values_to_products_options WHERE products_options_id = '" . $current_product_option_id . "' ORDER BY products_options_values_id DESC";
      $line2 = $db->Execute($query2) or die(mysql_error());
      $matches2 = $line2->RecordCount() >0;

      if ($matches2) {
        $i = '0';
        while (!$line2->EOF) {
          $i++;
          $rowClass = rowClass($i);
          $current_value_id = $line2->fields['products_options_values_id'];
          $isSelected = checkAttribute($current_value_id, $_POST['current_product_id'], $current_product_option_id);
          if ($isSelected) {
            $CHECKED = ' CHECKED';
          } else {
            $CHECKED = '';
          }

          $query3 = "SELECT * FROM products_options_values WHERE products_options_values_id = '" . $current_value_id . "' AND language_id = '" . $_SESSION['languages_id'] . "'";
          $line3 = $db->Execute($query3) or die(mysql_error());
          while(!$line3->EOF) {
            $current_value_name = $line3->fields['products_options_values_name'];
            // Print the Current Value Name
            echo "<TR class=\"" . $rowClass . "\">";
            echo "<TD class=\"main\">";
            // Add Support for multiple text input option types (for Chandra's contribution).. and using ' to begin/end strings.. less of a mess.
            if ($optionTypeTextInstalled == '1' && $current_value_id == $optionTypeTextInstalledID) {
              $current_value_id_old = $current_value_id;
              $current_value_id = $tempTextID;
              echo '<input type="checkbox" name="optionValuesText[]" value="' . $current_value_id . '"' . $CHECKED . '>&nbsp;&nbsp;' . $current_value_name . '&nbsp;&nbsp;';
              echo '<input type="hidden" name="' . $current_value_id . '_options_id" value="' . $current_product_option_id . '">';
            } else {
              echo "<input type=\"checkbox\" name=\"optionValues[]\" value=\"" . $current_value_id . "\"" . $CHECKED . ">&nbsp;&nbsp;" . $current_value_name . "&nbsp;&nbsp;";
            }
            echo "</TD>";
            echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_sortorder\" value=\"" . $sortorder . "\" size=\"4\"></TD>";
            echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_model\" value=\"" . $attribute_value_model . "\" size=\"15\"></TD>";
            echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_stock\" value=\"" . $attribute_value_stock . "\" size=\"4\"></TD>";
            echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_weight\" value=\"" . $attribute_value_weight . "\" size=\"10\"></TD>";
            echo "<TD class=\"main\" align=\"left\"><SELECT name=\"" . $current_value_id . "_weight_prefix\"><OPTION value=\"+\"" . $posCheck_weight . ">+<OPTION value=\"-\"" . $negCheck_weight . ">-</SELECT></TD>";

            // brutto Admin
            if (PRICE_IS_BRUTTO=='true'){
			$attribute_value_price_calculate = twe_format_price(twe_round($attribute_value_price*((100+(twe_get_tax_rate(twe_get_tax_class_id($_POST['current_product_id']))))/100),PRICE_PRECISION),false,false);
            } else {
            $attribute_value_price_calculate = twe_round($attribute_value_price,PRICE_PRECISION);
            }
            echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_price\" value=\"" . $attribute_value_price_calculate . "\" size=\"10\">";
            // brutto Admin
            if (PRICE_IS_BRUTTO=='true'){
             echo TEXT_NETTO .'<b>'.twe_format_price(twe_round($attribute_value_price,PRICE_PRECISION),true,false).'</b>  ';
            }
            echo "</TD>";
            echo "<TD class=\"main\" align=\"left\"><SELECT name=\"" . $current_value_id . "_prefix\"> <OPTION value=\"+\"" . $posCheck . ">+<OPTION value=\"-\"" . $negCheck . ">-</SELECT></TD>";
            echo "</TR>";
            // Download function start
            if(strtoupper($current_product_option_name) == 'DOWNLOADS' || strtoupper($current_product_option_name) == 'DOWNLOAD') {
                echo "<tr>";
               // echo "<td colspan=\"2\">File: <input type=\"file\" name=\"" . $current_value_id . "_download_file\"></td>";
                echo "<td colspan=\"2\">".twe_draw_pull_down_menu($current_value_id . '_download_file', twe_get_downloads() , $attribute_value_download_filename, '')."</td>";
                echo "<td class=\"main\">&nbsp;".DL_COUNT." <input type=\"text\" name=\"" . $current_value_id . "_download_count\" value=\"" . $attribute_value_download_count . "\"></td>";
                echo "<td class=\"main\">&nbsp;".DL_EXPIRE." <input type=\"text\" name=\"" . $current_value_id . "_download_expire\" value=\"" . $attribute_value_download_expire . "\"></td>";
                echo "</tr>";
            }
	      	$line3->MoveNext();
          }
	           $line2->MoveNext();	  
          if ($i == $matches2 ) $i = '0';
		}
      //	$line->MoveNext();		
      } else {
        echo "<TR>";
        echo "<TD class=\"main\"><SMALL>No values under this option.</SMALL></TD>";
        echo "</TR>";
      }
	        	$line->MoveNext();		

    }
  }
?>
  <tr>
    <td colspan="10" class="main"><br>
<?php
echo twe_image_submit('button_save.gif','');
echo $backLink.twe_image_button('button_cancel.gif','').'</a>';
?>
</td>
  </tr>
</form>