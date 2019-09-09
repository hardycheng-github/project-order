<?php
   
// Return all status info values for a customer_id in catalog, need to check session registered customer or will return dafault guest customer status value !
function get_manufacturers_value($product_id = '') {
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
  if ($product_id) {
    $query_str = "select p.products_price as products_price, ";
    $query_str .= "m.manufacturers_id as manufacturers_id, m.manufacturers_name  as manufacturers_name ";
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