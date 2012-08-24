<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
$sql="";
if (isset($_POST['id']) && isset($_POST['tab'])) {
	if (strpos($_POST['tab'],"nopbai")!==false) {
		$sql = "delete  from ".$_POST['tab']." where id=".$_POST['id'];
		$result = mysql_query($sql) or die (mysql_error());
		if ($result) echo "OK"; else echo "FAIL";
	} else {
		echo "STOP ".$_POST['tab'];
	}
} else {
	echo "NOTHING";
}

?>
