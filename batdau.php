<?php
//define("DEBUG",TRUE);
require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8");
session_start();
$_SESSION['gio']=time()+0;
if (isset($_SESSION['maso']))
	mysql_query("update tn_ketqua set lucdau=NOW() where id=".$_SESSION['maso']);
?>
