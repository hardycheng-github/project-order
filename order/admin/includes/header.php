<?php
/* --------------------------------------------------------------
   $Id: header.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(header.php,v 1.19 2002/04/13); www.oscommerce.com 
   (c) 2003	 nextcommerce (header.php,v 1.17 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

$languages = twe_get_languages();
  $languages_array = array();
  $languages_selected = $_GET['language'];
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan="2" width="1"><?php echo twe_image(DIR_WS_IMAGES . 'twe_logo.gif', 'twe-commerce', '303', '100'); ?></td>
    <?php echo twe_draw_form('languages', 'start.php', '', 'get'); ?>
    <td width="40%"><?php echo twe_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?></td>
                      </form>
    <td valign="bottom" align="center"><?php echo '<a href="start.php"  class="headerLink">'. twe_image(DIR_WS_IMAGES . 'top_index.gif', '', '', '').'</a>'; ?></td>
    <td valign="bottom" align="center"><?php echo '<a href="http://www.twecommerce.org" target="_new" class="headerLink">'. twe_image(DIR_WS_IMAGES . 'top_support.gif', '', '', '').'</a>'; ?></td>
    <td valign="bottom" align="center"><?php echo '<a href="../index.php" class="headerLink">'. twe_image(DIR_WS_IMAGES . 'top_shop.gif', '', '', '').'</a>'; ?></td>
    <td valign="bottom" align="center"><?php echo '<a href="' . twe_href_link(FILENAME_LOGOUT, '', 'NONSSL') . '" class="headerLink">'. twe_image(DIR_WS_IMAGES . 'top_logout.gif', '', '', '').'</a>'; ?></td>
  </tr>
  <tr>
    <td width="40%">&nbsp;</td>
    <td valign="top" align="center"><?php echo '<a href="start.php"  class="headerLink">'. HEADER_TITLE_ADMINISTRATION.'</a>'; ?></td>
    <td valign="top" align="center"><?php echo '<a href="http://www.twecommerce.org" target="_new" class="headerLink">'. HEADER_TITLE_SUPPORT_SITE.'</a>'; ?></td>
    <td valign="top" align="center"><?php echo '<a href="../index.php" class="headerLink">'. HEADER_TITLE_ONLINE_CATALOG.'</a>'; ?></td>
    <td valign="top" align="center"><?php echo '<a href="' . twe_href_link(FILENAME_LOGOUT, '', 'NONSSL') . '" class="headerLink">'. HEADER_TITLE_LOGOFF.'</a>'; ?></td>
  </tr>
</table>
<hr>
