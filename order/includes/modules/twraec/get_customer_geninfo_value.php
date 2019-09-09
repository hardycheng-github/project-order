<?php
   
// Return all status info values for a customer_id in catalog, need to check session registered customer or will return dafault guest customer status value !
function get_customer_geninfo_value($customer_id) {
  global $db;
  
/*
  <orderIcpNo>880001</orderIcpNo>
  <orderNo>100812145518-0M00007</orderNo>
  <orderAmt>222</orderAmt>
  <orderNotifyUrl>http://61.220.207.137:8080/twweb/payreceive.action</orderNotifyUrl>
  <orderDate>2010-08-12 14:55:18</orderDate>
  <orderDesc>牛肉乾</orderDesc>
  <orderIcpName>twra</orderIcpName>
  <orderReturnUrl>http://61.220.207.137:8080/twweb/product.action</orderReturnUrl>
  <orderTelNo>0920780842</orderTelNo>
  <orderPid>A123456789</orderPid>
  <orderBirthday>1960-12-12</orderBirthday>
  <orderUserEmail>test4@twra.com.tw</orderUserEmail>
  <orderSendedAddress>台北市中正區羅斯福路一段100號</orderSendedAddress>

 */
  if (isset($_SESSION['customer_id'])) {
    $customer_info_query = "select c.general_member_id, c.email, c.true_name, c.mobile_phone, c.identifier, c.birthday, c.address, FROM " . "general_member" . " as c where c.customers_id='" . $_SESSION['customer_id'] . "'";
  } else {
  	die();
  }

  $customer_geninfo_value = $db->Execute($customer_info_query);

  twe_session_register('customer_geninfo_value');
return $customer_geninfo_value;
}
 ?>