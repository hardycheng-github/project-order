<?php
require("includes/application_top.php");
require_once(DIR_FS_INC . 'icer_function.php');
$mode = null;
if(isset($_GET['mode'])){
	$mode = $_GET['mode'];
}
if($mode == "signOn"){
	execInBackground($icer_api_signOn);
}
else if($mode == "settle"){
	execInBackground($icer_api_settle);
}
?>