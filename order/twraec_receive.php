<?php
  include( 'includes/application_top.php');

	function get_receive($in_val) {
		$receive_value = array(
			'order_icp_no' => $in_val['orderIcpNo'],
			'order_no' => $in_val['orderNo'],
			'pay_amt' => $in_val['payAmt'],
			'pay_date' => $in_val['payDate'],
			'pay_result' => $in_val['payResult'],
			'pay_err_no' => $in_val['payErrNo'],
			'pay_err_desc' => $in_val['payErrDesc'],
			'pay_identify_no' => $in_val['payIdentityNo'],
			'recv_date' => 'now()'
		);
		return $receive_value;
	}
	if (isset($_GET['orderIcpNo']))
		$receive = get_receive($_GET);
	if (isset($_POST['orderIcpNo']))
		$receive = get_receive($_POST);
	if (!$receive) {
		echo "no 'orderIcpNo'";
		die();
	}
	
    twe_db_perform('twraec_order', $receive, 'update', "order_no = " . $receive['order_no']);
	
	
?>