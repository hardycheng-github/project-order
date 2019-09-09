<?php
	include( 'includes/application_top.php');
	/*if(isset($_GET['order'])){
		$_GET['order'] = urldecode($_GET['order']);
		$xml_path = DIR_FS_ORDER_XML . "order-" . $_GET['order'] . ".xml";
		$res_path = DIR_FS_ORDER_XML . "order-" . $_GET['order'] . ".res";
		if(file_exists($xml_path)) unlink($xml_path);
		if(file_exists($res_path)) unlink($res_path);
	}*/
	//刪除order底下所有檔案
	if(isset($order_clear) && $order_clear == true){
		array_map('unlink', glob(DIR_FS_ORDER_XML."*"));
	}
	twe_redirect(twe_href_link(FILENAME_DEFAULT));
?>