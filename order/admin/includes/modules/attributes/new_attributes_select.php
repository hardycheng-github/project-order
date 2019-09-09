<?php
/* --------------------------------------------------------------
   $Id: new_attributes_select.php,v 1.5 2003/12/14 15:59:43 oldpa   Exp $

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw

   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(new_attributes_select.php); www.oscommerce.com 
   (c) 2003	 nextcommerce (new_attributes_select.php,v 1.9 2003/08/21); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------
   Third Party contributions:
   New Attribute Manager v4b				Autor: Mike G | mp3man@internetwork.net | http://downloads.ephing.com
   copy attributes                          Autor: Hubi | http://www.netz-designer.de

   Released under the GNU General Public License 
   --------------------------------------------------------------*/ 

$adminImages = DIR_WS_CATALOG . "lang/". $_SESSION['language'] ."/admin/images/buttons/";
?>
  <tr>
    <td class="pageHeading" colspan="3"><?php echo $pageTitle; ?></td>
  </tr>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="SELECT_PRODUCT" method="post"><input type="hidden" name="action" value="edit">
<?php
  echo "<TR>";
  echo "<TD class=\"main\"><BR><B>".TEXT_PRODUCT_ATTRIBUTES_SELECT."<BR></TD>";
  echo "</TR>";
  echo "<TR>";
  echo "<TD class=\"main\"><SELECT NAME=\"current_product_id\">";

  $query = "SELECT * FROM products_description where products_id LIKE '%' AND language_id = '" . $_SESSION['languages_id'] . "' ORDER BY products_name ASC";

  $line = $db->Execute($query);

  if ($line->RecordCount()>0) {
    while (!$line->EOF) {
      $title = $line->fields['products_name'];
      $current_product_id = $line->fields['products_id'];

      echo "<OPTION VALUE=\"" . $current_product_id . "\">" . $title;
    $line->MoveNext();
	}
  } else {
    echo TEXT_NO_PRODUCT_ATTRIBUTES;
  }

  echo "</SELECT>";
  echo "</TD></TR>";

  echo "<TR>";
  echo "<TD class=\"main\">";
  echo twe_image_submit('button_edit.gif','');

  echo "</TD>";
  echo "</TR>";
  // start change for Attribute Copy
?>
<br><br>
<?php
  echo "<TR>";
  echo "<TD class=\"main\"><BR><B>".TEXT_PRODUCT_ATTRIBUTES_SELECT_COPY."<BR></TD>";
  echo "</TR>";
  echo "<TR>";
  echo "<TD class=\"main\"><SELECT NAME=\"copy_product_id\">";

  $copy_res = $db->Execute("SELECT pd.products_name, pd.products_id FROM products_description pd, products_attributes pa where pa.products_id = pd.products_id AND pd.products_id LIKE '%' AND pd.language_id = '" . $_SESSION['languages_id'] . "' GROUP BY pd.products_id ORDER BY pd.products_name ASC");

  if ($copy_res->RecordCount()) {
      echo '<option value="0">No Copy</option>';
      while (!$copy_res->EOF) {
          echo '<option value="' . $copy_res->fields['products_id'] . '">' . $copy_res->fields['products_name'] . '</option>';
     $copy_res->MoveNext();
	  }
  }
  else {
      echo TEXT_NO_PRODUCT_ATTRIBUTES_COPY;
  }
  echo '</select></td></tr>';
  echo "<TR>";
  echo "<TD class=\"main\"><input type=\"image\" src=\"" . $adminImages . "button_edit.gif\"></TD>";
  echo "</TR>";
// end change for Attribute Copy
?>
</form>