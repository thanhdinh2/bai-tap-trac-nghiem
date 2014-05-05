<?php
//define("DEBUG",TRUE);
require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8");
session_start();
if (isset($_SESSION['maso']))
	mysql_query("update nopbaikiemtra set batdau=NOW() where id=".$_SESSION['maso']);
	//echo "update nopbaikiemtra set batdau=NOW() where id=".$_SESSION['maso'];
?>
