<?php
/* -----------------------------------------------------------------------------------------
   $Id: send_order.php,v 1.11 2004/04/25 13:58:08 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2003	 nextcommerce (send_order.php,v 1.1 2003/08/24); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  $module_smarty=new Smarty;
  $module_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
  include_once('inc/twe_draw_selection_field.inc.php');
  $kyear=date("Y")-1911 . '�~' . date("n") . '��' . (date("j")+7) . '��';
function xselect() {
  for($mk=9;$mk<22;$mk++) { //9�N���W9�I,22�N��ߤW10�I
  if($mk<12) {$mc = $mk;echo '<option value="���W' . $mc . '�I">���W' . $mc . '�I</option>';}
  else if($mk>12 and $mk<18) {$mc = $mk-12;echo '<option value="�U��' . $mc . '�I">�U��' . $mc . '�I</option>';}
  else if($mk>17) {$mc = $mk-12;echo '<option value="�ߤW' . $mc . '�I">�ߤW' . $mc . '�I</option>';}
  else {echo '<option value="����12�I">����12�I</option>';}
  }
 } 

  $module_smarty->assign('OUTSTIME',twe_draw_selection_field('outstime', '�����w�ɶ�',xselect()));
  $module_smarty->assign('OUTSTIME',twe_draw_selection_field('outstime', '�����w�ɶ�',xselect()));

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->caching = 0;
  $main_content=$module_smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_payment.html');
  $smarty->assign('MODULE_select_time',$module);
?>  