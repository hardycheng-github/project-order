<?php
 // include( 'includes/application_top.php');
  
//require_once(DIR_WS_MODULES . 'twraec/get_customer_geninfo_value.php');
//  require_once(DIR_WS_MODULES . 'twraec/get_manufacturers_value.php');
  
//$xml = buildXml();
//echo "xml=".$xml;


/*
<order>
  <orderIcpNo>880001</orderIcpNo>
  <orderNo>100813001015-0M00008</orderNo>
  <orderAmt>222</orderAmt>
  <orderNotifyUrl>http://61.220.207.137:8080/twweb/payreceive.action</orderNotifyUrl>
  <orderDate>2010-08-13 00:10:15</orderDate>
  <orderDesc>牛肉乾</orderDesc>
  <orderIcpName>twra</orderIcpName>
  <orderReturnUrl>http://61.220.207.137:8080/twweb/product.action</orderReturnUrl>
  <orderTelNo>0920780842</orderTelNo>
  <orderPid>A123456789</orderPid>
  <orderBirthday>1960-12-12</orderBirthday>
  <orderUserEmail>test4@twra.com.tw</orderUserEmail>
  <orderSendedAddress>台北市中正區羅斯福路一段100號</orderSendedAddress>
  <orderIdentifyNo>8CD9943DB376FC0680A4AE8004D743164B486D91</orderIdentifyNo>
  <product>
    <storeId>22555003</storeId>
    <productName>綜合堅果仁</productName>
    <productPrice>111</productPrice>
    <productNumber>2</productNumber>
    <productDeliver>Y</productDeliver>
  </product>
</order>
 */
	function writeDiscountFile($id, $file_path){
		global $db;
		if(!isset($_SESSION['discount_content'])) return;
		//來店次數+1
		$count = $_SESSION['discount_content']['info']['count'];
		$custid = $_SESSION['discount_content']['info']['custid'];
		$t0200 = $_SESSION['discount_content']['info']['t0200'];
		$sqlcmd = "update mbrpromo_mf set count = '$count' where custid = '$custid' and t0200= '$t0200' " ;
		$db->Execute( $sqlcmd );
		$discount_content = $_SESSION['discount_content'];
		unset($_SESSION['discount_content']);
		$writer = new xmlWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->startDocument("1.0", "UTF-8");
		foreach($discount_content['discount_content'] as $discount_count){
			$writer->startElement('discountItem');
			$writer->writeElement('description', $discount_count['desrciption']);
			$writer->writeElement('disAmount', $discount_count['disAmount']);
			$writer->endElement();
		}
		$writer->endDocument();
		$content = $writer->outputMemory(true);
		return file_put_contents($file_path, $content, LOCK_EX);  
	}
	function getTaxFromTotal($total, $tax=0.05){
		return (int)floor($total / (1.00 + $tax) * $tax + 0.5);
	}
	function makeInvoice($order, $order_id, $inv_path){
		global $db, $seller;
		if(isset($_SESSION['invoice_no']) && strlen($_SESSION['invoice_no']) == 10){
			$invoice_no = $_SESSION['invoice_no'];
			$total_amount = $xml_value['order_amt'];
			$tax_amount = getTaxFromTotal($total_amount);
			$sales_amount = $total_amount - $tax_amount;
			$inv_content = array();
			$inv_content['invoice_no'] = $invoice_no;
			$inv_content['seller'] = $seller;
			$inv_content['random_num'] = genRandomNum();
			$inv_content['invoice_datetime'] = time();
			$inv_content['order_no'] = $order_id;
			$inv_content['sales_amount'] = (int)$sales_amount;
			$inv_content['tax_amount'] = (int)$tax_amount;
			$inv_content['total_amount'] = (int)$total_amount;
			$sql = "SELECT * FROM `invoice_mf` WHERE `invoice_no`='$invoice_no'";
			if($db->Execute($sql)->RecordCount() != 0){
				$sql = "UPDATE `invoice_mf` SET 
					`random_num`='".$inv_content['random_num']."',
					`invoice_datetime`='".$inv_content['invoice_datetime']."',
					`order_no`='".$inv_content['order_no']."',
					`sales_amount`='".$inv_content['sales_amount']."',
					`tax_amount`='".$inv_content['tax_amount']."',
					`total_amount`='".$inv_content['total_amount']."' WHERE `invoice_no`='$invoice_no'";
			
			} else{
				$sql = "INSERT INTO `invoice_mf`
				(`seller`,`invoice_no`,`random_num`, `invoice_datetime`, `order_no`
				,`sales_amount`, `tax_amount`, `total_amount`) VALUES
				('".$inv_content['seller']."','".$inv_content['invoice_no']."'
				,'".$inv_content['random_num']."','".$inv_content['invoice_datetime']."'
				,'".$inv_content['order_no']."','".$inv_content['sales_amount']."'
				,'".$inv_content['tax_amount']."','".$inv_content['total_amount']."')";
			}
			if(count($db->Execute($sql))!=0){
				//print_r($order->products[0]['attributes']);
				//exit(1);
				for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
					$inv_detail = array();
					$inv_detail['product_id']=$order->products[$i]['id'];
					$inv_detail['product_name']=$order->products[$i]['name'];
					$inv_detail['quantity']=$order->products[$i]['qty'];
					$inv_detail['unit']='';
					$inv_detail['unit_price']=(int)$order->products[$i]['price'];
					$inv_detail['amount']=(int)$order->products[$i]['final_price'];
					$sql = "INSERT INTO `invdetail_mf`
					(`invoice_no`, `product_id`, `product_name`, `quantity`, `unit`, `unit_price`, `amount`)
					VALUES (
					'".$invoice_no."',
					'".$inv_detail['product_id']."',
					'".$inv_detail['product_name']."',
					'".$inv_detail['quantity']."',
					'".$inv_detail['unit']."',
					'".$inv_detail['unit_price']."',
					'".$inv_detail['amount']."')";
					$db->Execute($sql);
				}
			}
			if(writeInvoiceFile($inv_content, $order_id, $inv_path) != false){
				unset($_SESSION['invoice_no']);
				return 'writeInvoiceFile success';
			} else{
				return 'writeInvoiceFile fail';
			}
		} else{
		  // no invoice can use
		  return 'no invoice can use';
		}
	}
	function genRandomNum(){
		return ''.rand(1,9).rand(1,9).rand(1,9).rand(1,9);
	}
	function writeInvoiceFile($inv_content,$order_id='',$inv_path=''){
		$writer = new xmlWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->startDocument("1.0", "UTF-8");
		$writer->writeElement ('invoiceNumber', $inv_content['invoice_no']);
		$writer->writeElement ('invoiceRandomNumer', $inv_content['random_num']);
		$writer->writeElement ('sellerUniformNumber', $inv_content['seller']);
		$writer->writeElement ('salesAmount', $inv_content['sales_amount']);
		$writer->writeElement ('taxAmount', $inv_content['tax_amount']);
		$writer->writeElement ('totalAmount', $inv_content['total_amount']);
		$writer->endDocument();
		$content = $writer->outputMemory(true);
		return file_put_contents($inv_path, $content, LOCK_EX);
	}
	function writeOrderFile($xml_content,$order_id='',$xml_path=''){
		// LOCK_EX flag to prevent anyone else writing to the file at the same time
		return file_put_contents($xml_path, $xml_content, LOCK_EX);
	}
	function twraec_pre_process($order='',$order_id='', $date_purchased='') {
			$customer_value = inquire_customer_info($order->customer['id']);
    		$xml_value = array(
				          'order_icp_no' => TWEC_ORDER_ICPNO,
                          'order_no' => $order_id,
                          'order_amt' => total_order_amount($order),
                          'order_notify_url' => TWEC_ORDER_NOTIFY_URL,
                          'order_date' => $date_purchased,
                          'order_desc' => TWEC_ORDER_ICP_NAME,
//                          'order_icp_name' => mb_convert_encoding(TWEC_ORDER_ICP_NAME, 'UTF-8', 'BIG-5' ),
                          'order_icp_name' => TWEC_ORDER_ICP_NAME,
						  'order_store_name' => STORE_NAME,
                          'order_return_url' => TWEC_ORDER_RETURN_URL,
                          'order_tel_no' => $order->customer['telephone'],
                          'order_pid' => $customer_value['customers_cid'],
                          'order_birthday' => $customer_value['customers_dob'],
                          'order_user_email' => $order->customer['email_address'],
                          'order_sended_name' => $order->delivery['name'],
    		              'order_sended_tel' => $order->delivery['telephone'],
                          'order_sended_address' => $order->delivery['postcode'].' '.$order->delivery['state']
    												.' '.$order->delivery['city'].' '.$order->delivery['street_address']
    												.' '.$order->delivery['company'],
                          );
             $sha_val = $xml_value['order_icp_no'].
             		$xml_value['order_tel_no'].
             		$xml_value['order_notify_url'].
             		$xml_value['order_no'].
             		$xml_value['order_amt'].TWEC_ORDER_IDENTIFY_KEY;
             $xml_value['order_identify_no'] = strtoupper(sha1($sha_val));
             return  $xml_value;       
	}
//		String enstr = Coder.encrypt(order.getOrderIcpNo()
//				+ order.getOrderTelNo() + order.getOrderNotifyUrl()
//				+ order.getOrderNo() + order.getOrderAmt() + key);
	function buildXml($order = '', $xml_value='') {
		$writer = new xmlWriter();
		$writer->openMemory();
		$writer->setIndent(true);
		$writer->startDocument("1.0", "UTF-8");

		$writer->startElement ('order'); // <order>

		//$writer->writeElement ('orderIcpNo', $xml_value['order_icp_no']);
		//$writer->writeElement ('orderNo', $xml_value['order_no']);
		$writer->writeElement ('orderAmt', $xml_value['order_amt']);
		//$writer->writeElement ('orderNotifyUrl', $xml_value['order_notify_url']);
		$writer->writeElement ('orderDate', $xml_value['order_date']);
		//$writer->writeElement ('orderDesc', $xml_value['order_desc']);
		$writer->writeElement ('orderStoreName', $xml_value['order_store_name']);
		//$writer->writeElement ('orderIcpName', $xml_value['order_icp_name']);
		//$writer->writeElement ('orderReturnUrl', $xml_value['order_return_url']);
		//$writer->writeElement ('orderTelNo', $xml_value['order_tel_no']);
		//$writer->writeElement ('orderPid', $xml_value['order_pid']);
		//$writer->writeElement ('orderBirthday', $xml_value['order_birthday']);
		//$writer->writeElement ('orderUserEmail', $xml_value['order_user_email']);
		//$writer->writeElement ('orderSendedName', $xml_value['order_sended_name']);
		//$writer->writeElement ('orderSendedTel', $xml_value['order_sended_tel']);
		//$writer->writeElement ('orderSendedAddress', $xml_value['order_sended_address']);
		//$writer->writeElement ('orderIdentifyNo', $xml_value['order_identify_no']);
		
		get_products_xml($order, $writer);
		
		$writer->endElement(); // </order>
		$writer->endDocument();
		$xml = $writer->outputMemory(true);
		return $xml;
	}
	
	function get_delivery_address($order) {
		$adr = $order->delivery['city'] . ' ' . $order->delivery['street_address'];
		return $adr;
	}
	function get_products_xml($order = '', $writer = '') {
    	for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
//    		$manufacturers_value = get_manufacturers_value($order->products[$i]['id']);
    		$writer->startElement ('product');
			
    		//$writer->writeElement ('uniformNo', TWEC_ICP_UNIFORM_NO);//give the product_id as uniformNo
//    		$writer->writeElement ('uniformNo', $manufacturers_value->fields[manufacturers_pid]);
    		$writer->writeElement ('productName', $order->products[$i]['name']);
    		$writer->writeElement ('productPrice', get_ride_dot($order->products[$i]['final_price']));
			//$writer->writeElement ('productSinglePrice', get_ride_dot($order->products[$i]['price']));
    		$writer->writeElement ('productNumber', $order->products[$i]['qty']);
    		//$writer->writeElement ('productDeliver', 'Y');
			for($j=0, $m=sizeof($order->products[$i]['attributes']);$j<$m; $j++){
				$attributes = $order->products[$i]['attributes'][$j];
				$writer->startElement ('productOption');
				$writer->writeElement ('optionName', $attributes['option']);
				$writer->writeElement ('optionValue', $attributes['value']);
				$optionPrice = (int)$attributes['price'];
				if($optionPrice != 0){
					$writer->writeElement ('optionPrice', $attributes['prefix'].$optionPrice);
				}
				$writer->endElement();
			}
    		
			$writer->endElement(); // </product>
    	}
	}
	function get_ride_dot($str) {
		return substr($str, 0, $fp = strpos($str, '.'));
	}
	function total_order_amount($order = '') {
		$total = 0;
		for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
			$total += $order->products[$i]['final_price'];
    	}
    	return $total;
	}
	function inquire_customer_info($c_id = '') {
  global $db;
		if (!$c_id) return '';

		$customer_info_query = "select customers_dob, customers_cid FROM " . "customers" . " where customers_id='" . $c_id . "'";
		$customer_info = $db->Execute($customer_info_query);
		$customer_value['customers_cid'] = $customer_info->fields['customers_cid'];
		if ($customer_info->fields['customers_dob']) {
			$customer_value['customers_dob'] =  date('Y-m-d', $customer_info->fields['customers_dob'] );
		}

		return $customer_value;
	}
	
	function get_manufacturers_value($product_id = '') {
  global $db;
		if ($product_id) {
			
		    $query_str = "select p.products_price as products_price, ";
		    $query_str .= "m.manufacturers_id as manufacturers_id, m.manufacturers_name  as manufacturers_name, ";
		    $query_str .= "m.manufacturers_pid as manufacturers_pid, m.store_id as store_id, m.shipment_fee as shipment_fee ";
		    $query_str .= "FROM products as p, manufacturers as m ";
		    $query_str .= " where p.products_id = ".$product_id." AND p.manufacturers_id = m.manufacturers_id";
  		} else {
  			die();
  		}

		$query_value = $db->Execute($query_str);

  		twe_session_register('customer_geninfo_value');
		return $query_value;
	}
?>
