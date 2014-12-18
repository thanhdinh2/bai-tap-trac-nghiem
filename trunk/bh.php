<?php

require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Bài học</title></head><body>";
$id="0";
$b="";
if (isset($_GET['id'])) {
	//xemdebai($_GET['id']);
	$id=$_GET['id'];
} else if (isset($_GET['b'])) {
	$b=$_GET['b'];
} else {
	exit( "BÀI HỌC");
}

$sql= "select * from bh_kienthuc where (maso=$id) or (slug='$b') limit 1";
if (defined("DEBUG")) echo $sql."<br/>";
$result = mysql_query($sql) or die (mysql_error());
if (mysql_num_rows($result)) {
	$data  = mysql_fetch_array($result);
	echo "<u><b>". $data['tieude']."</b></u><br/><br/>";
	$sql="select tukhoa,lienket from bh_tukhoa order by length(tukhoa) desc";
	$result = mysql_query($sql);
	$tukhoa=array();
	$lienket=array();
	while ($khoa = mysql_fetch_array($result)) {
		$tukhoa[]=" ".$khoa['tukhoa'];
		$lienket[] = " <a href='".$khoa['lienket']."' class='mucdonvi'>".$khoa['tukhoa']."</a>";
	}
	if (count($tukhoa)) {
		echo str_ireplace($tukhoa,$lienket,$data['noidung']);
		
	} else {
		echo $data['noidung'];
	}
	echo "<hr/>";
	if ($data['lienquan']) {
	echo "<p>Các bài liên quan:</p>";
		$sql = "select slug,tieude,ngaylam from bh_kienthuc where maso in (".$data['lienquan'].")";
		$result = mysql_query($sql) or die (mysql_error());
		if (mysql_num_rows($result)) {
			echo "<ul>";
			while ($data=mysql_fetch_array($result)) {
				echo "<li>";
				echo "<span><a href='".$data['slug']."'>".$data['tieude']."</a></span> ";
				echo "<span>".$data['ngaylam']."</span> ";
				echo "</li>\n";
			}
			echo "</ul>";
		}
	} else {
		echo "<p>Không có bài liên quan</p>";
	}
} else {
	echo "Không có bài này";
}

echo "</body></html>";


?>
