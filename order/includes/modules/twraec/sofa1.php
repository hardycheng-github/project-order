<?php
	$IcpNo="80146891";
    $VaccNo="95543920784063";
    $IcpConfirmTransUrl="http://61.220.207.137/twec/transresp.action";
    $TransNo="AA0001TX1012";
    $TransAmt="8";
    $key="86A2C451B375D51080146891F8A6E5B1";
//    echo sha1($IcpNo.$VaccNo.$IcpConfirmTransUrl.$TransNo.$TransAmt.$key);
    	for($aa = 0; $aa<999; $aa++) {
        	$ecstr = strtoupper(sha1($IcpNo.$VaccNo.$IcpConfirmTransUrl.$TransNo.$aa.$key));
        	if (strlen($ecstr) != 40) {
				echo("ERR====>".$ecstr);
				echo "\r\n";
			} else {
				echo '|'.$aa.'|'.'<br/>';
//				echo($ecstr);			echo '\r\n';
			}
    	}
    	flush();
    exit();
	
	$vv = "    xxx  yyyy  zzzz    ";
	$vv = trim($vv, ' ');
	echo $vv;
	flush();
	echo "\n";
	echo date("Y-m-d H:i:s");
	exit();
$batchSize = 25;
$xmlWriter = new XMLWriter();
$xmlWriter->openUri('php://output');
$xmlWriter->setIndent(true);
if($xmlWriter)
{
  $xmlWriter->startDocument('1.0','UTF-8');
  $xmlWriter->startElement('Books');
 
  $memXmlWriter = new XMLWriter();
 
  $memXmlWriter->openMemory();
  $memXmlWriter->setIndent(true);
 
  for($i=1;$i<=1000;$i++)
  {
    $memXmlWriter->startElement('book');
      $memXmlWriter->text('book_'.$i);
    $memXmlWriter->endElement();
   
    if($i%5 == 0)
    {
      $batchXmlString = $memXmlWriter->outputMemory(true);
      $xmlWriter->writeRaw($batchXmlString);
    }
  }
  $memXmlWriter->flush();
  unset($memXmlWriter);
  $xmlWriter->endElement();
  $xmlWriter->endDocument(); 
}
?>