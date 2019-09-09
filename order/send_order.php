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

  require_once(DIR_FS_INC . 'twe_get_products_price.inc.php');
  require_once(DIR_FS_INC . 'twe_get_order_data.inc.php');
  require_once(DIR_FS_INC . 'twe_get_attributes_model.inc.php');
  // check if customer is allowed to send this order!
  $order_query_check = "SELECT
  					customers_id
  					FROM ".TABLE_ORDERS."
  					WHERE orders_id='".$insert_id."'";
  					
  $order_check = $db->Execute($order_query_check);
  if ($_SESSION['customer_id'] == $order_check->fields['customers_id'])
  	{

	$order = new order($insert_id);

	
  	$smarty->assign('address_label_customer',twe_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'));
  	$smarty->assign('address_label_shipping',twe_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'));
    if ($_SESSION['credit_covers']!='1') {
  	$smarty->assign('address_label_payment',twe_address_format($order->billing['format_id'], $order->billing, 1, '', '<br>'));
    }
  	$smarty->assign('csID',$order->customer['csID']);
  	// get products data
        $order_data_values=$db->Execute("SELECT
        				products_id,
        				orders_products_id,
        				products_model,
        				products_name,
        				final_price,
        				products_quantity
        				FROM ".TABLE_ORDERS_PRODUCTS."
        				WHERE orders_id='".$insert_id."'");
        $order_data=array();
        while (!$order_data_values->EOF) {
        $attributes_data_values=$db->Execute("SELECT
        				products_options,
        				products_options_values,
        				price_prefix,
        				options_values_price
        				FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES."
        				WHERE orders_products_id='".$order_data_values->fields['orders_products_id']."'");
        	$attributes_data='';
        	$attributes_model='';
        	while (!$attributes_data_values->EOF) {
        	$attributes_data .=$attributes_data_values->fields['products_options'].':'.$attributes_data_values->fields['products_options_values'].'<br>';
        	$attributes_model .=twe_get_attributes_model($order_data_values->fields['products_id'],$attributes_data_values->fields['products_options_values']).'<br>';
            $attributes_data_values->MoveNext();
			}
        $order_data[]=array(
        		'PRODUCTS_MODEL' => $order_data_values->fields['products_model'],
        		'PRODUCTS_NAME' => $order_data_values->fields['products_name'],
        		'PRODUCTS_ATTRIBUTES' => $attributes_data,
        		'PRODUCTS_ATTRIBUTES_MODEL' => $attributes_model,
        		'PRODUCTS_PRICE' => twe_format_price($order_data_values->fields['final_price'],$price_special=1,$calculate_currencies=1,$show_currencies=1),
        		'PRODUCTS_QTY' => $order_data_values->fields['products_quantity']);
       $order_data_values->MoveNext();
	    }
  	// get order_total data
  $oder_total_values=$db->Execute("SELECT
  					title,
  					text,
  					sort_order
  					FROM ".TABLE_ORDERS_TOTAL."
  					WHERE orders_id='".$insert_id."'
  					ORDER BY sort_order ASC");

  	$order_total=array();
  	while (!$oder_total_values->EOF) {
  	
  	$order_total[]=array(
  			'TITLE' => $oder_total_values->fields['title'],
  			'TEXT' => $oder_total_values->fields['text']);
	$oder_total_values->MoveNext();			
  	}

  	// assign language to template for caching
  	$smarty->assign('language', $_SESSION['language']);
  	$smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
    $smarty->assign('logo_path',HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/img/');
	$smarty->assign('oID',$insert_id);
    if ($order->info['payment_method']!='' && $order->info['payment_method']!='no_payment') {
	include(DIR_WS_LANGUAGES.$_SESSION['language'].'/modules/payment/'.$order->info['payment_method'].'.php');
 	$payment_method=constant(strtoupper('MODULE_PAYMENT_'.$order->info['payment_method'].'_TEXT_TITLE'));
    //$payment_info oldpa start
	if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
       $payment_info=$confirmation['title'];
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
$payment_info .= '<table><tr>
                  <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  <td class="main">'. $confirmation['fields'][$i]['title'].'</td>
                  <td width="10">'. twe_draw_separator('pixel_trans.gif', '10', '1').'</td>
                  <td class="main">'. stripslashes($confirmation['fields'][$i]['field']).'</td>
                  </tr></table>';
      }
      $smarty->assign('PAYMENT_INFORMATION',$payment_info);
      }
     }
 //$payment_info end
	}
  	$smarty->assign('PAYMENT_METHOD',$payment_method);
  	$smarty->assign('DATE',twe_date_long($order->info['date_purchased']));
  	$smarty->assign('order_data', $order_data);
  	$smarty->assign('order_total', $order_total);
  	$smarty->assign('NAME',$order->customer['name']);
    $smarty->assign('COMMENTS',$order->info['comments']);

  	// dont allow cache
  	$smarty->caching = false;
// myEdit mail寄送功能取消
/*  	
  $html_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/'.$_SESSION['language'].'/order_mail.html');
  $txt_mail=$smarty->fetch(CURRENT_TEMPLATE.'/mail/'.$_SESSION['language'].'/order_mail.txt');

  // create subject
  $order_subject=str_replace('{$nr}',$insert_id,EMAIL_BILLING_SUBJECT_ORDER);
  $order_subject=str_replace('{$date}',strftime(DATE_FORMAT_LONG),$order_subject);
  $order_subject=str_replace('{$lastname}',$order->customer['firstname'],$order_subject);
  $order_subject=str_replace('{$firstname}',$order->customer['firstname'],$order_subject);
  
  // send mail to admin
   twe_php_mail($order->customer['email_address'],
               $order->customer['firstname'],
               EMAIL_BILLING_FORWARDING_STRING ,
               STORE_NAME,
               '',
               $order->customer['email_address'],
               $order->customer['firstname'],
               '',
               '',
               $order_subject,
               $html_mail ,
               $txt_mail );


  // send mail to customer
  twe_php_mail(EMAIL_BILLING_ADDRESS,
               EMAIL_BILLING_NAME,
               $order->customer['email_address'] ,
               $order->customer['firstname'] . ' ' . $order->customer['lastname'] ,
               '',
               EMAIL_BILLING_REPLY_ADDRESS,
               EMAIL_BILLING_REPLY_ADDRESS_NAME,
               '',
               '',
               $order_subject,
               $html_mail ,
               $txt_mail );
*/
} else {
$smarty->assign('ERROR','You are not allowed to view this order!');
$smarty->display(CURRENT_TEMPLATE . '/module/error_message.html');	
}
?>