<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
$sql="";
if (isset($_GET['id'])) {
	$sql = "select * from nopbai where id>".$_GET['id']." order by id desc";
}
else {
	$sql = "select * from nopbai order by ngaygio desc limit 10";
}
$result = mysql_query($sql) or die (mysql_error());
while ($data = mysql_fetch_array($result)) {
	echo "<tr align='center'>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['ngaygio']."</td>";
	echo "<td><a href='loadfile.php?f=".$data['file']."'>".$data['file']."</a></td>";
	echo "<td>".$data['baitap']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
?>
