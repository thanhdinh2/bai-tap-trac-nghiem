<?php
//define("DEBUG",TRUE);
session_start();
if (isset($_POST['gio']))
	$_SESSION['gio']=$_POST['gio'];
else
	unset($_SESSION['gio']);
?>
