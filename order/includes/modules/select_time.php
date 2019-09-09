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
  $kyear=date("Y")-1911 . '年' . date("n") . '月' . (date("j")+7) . '日';
function xselect() {
  for($mk=9;$mk<22;$mk++) { //9代表早上9點,22代表晚上10點
  if($mk<12) {$mc = $mk;echo '<option value="早上' . $mc . '點">早上' . $mc . '點</option>';}
  else if($mk>12 and $mk<18) {$mc = $mk-12;echo '<option value="下午' . $mc . '點">下午' . $mc . '點</option>';}
  else if($mk>17) {$mc = $mk-12;echo '<option value="晚上' . $mc . '點">晚上' . $mc . '點</option>';}
  else {echo '<option value="中午12點">中午12點</option>';}
  }
 } 

  $module_smarty->assign('OUTSTIME',twe_draw_selection_field('outstime', '未指定時間',xselect()));
  $module_smarty->assign('OUTSTIME',twe_draw_selection_field('outstime', '未指定時間',xselect()));

  $module_smarty->assign('language', $_SESSION['language']);
  $module_smarty->caching = 0;
  $main_content=$module_smarty->fetch(CURRENT_TEMPLATE . '/module/checkout_payment.html');
  $smarty->assign('MODULE_select_time',$module);
?>  