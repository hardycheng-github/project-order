<?php
	include( 'includes/application_top.php');
	function retInv($inv_data){
		echo $inv_data;
		exit(1);
	}
	if ($db == null || !isset($_REQUEST['invoice_no'])) retInv("");
	$invoice_no = $_REQUEST['invoice_no'];
	$query="SELECT * FROM `invoice_mf` WHERE `invoice_no`='$invoice_no'";
    $result = $db->Execute($query);
	$data = array();
	foreach ($result as $row) {
		if($row['random_num']=="0000"){
			retInv("");
		}
		$count = count($row)/2;
		for($i = 0; $i < $count; $i++){
			unset($row[$i]);
		}
		$query="SELECT `product_id`,`product_name`,`quantity`,`unit`,`unit_price`,`amount`
				FROM `invdetail_mf` WHERE `invoice_no`='$invoice_no'";
		$details = $db->Execute($query);
		foreach ($details as $details_row) {
			$count = count($details_row)/2;
			for($i = 0; $i < $count; $i++){
				unset($details_row[$i]);
			}
			$row['details'][] = $details_row;
		}
		$data = $row;
		unset($data['status']);
		unset($data['msg']);
	}
	retInv(json_encode($data));
?>