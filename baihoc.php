<?php

require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Bài học</title></head><body>";

if (isset($_GET['muc'])) {
	lietketheomuc($_GET['muc']);
} else if (isset($_GET['id'])) {
	echo "BÀI HỌC<hr/>";
	xemdebai($_GET['id']);
} else {
	echo "WELCOME";
}
echo "</body></html>";


function xemdebai($bai) { //xem de bai
	$sql= "select * from baihoc where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<u><b>". $data['title']."</b></u><br/><br/>";
		echo $data['content']."<br/>";
	} else {
		echo "Không có bài này";
	}
}


function lietketheomuc($muc){ //liet ke de bai theo muc
	echo "DANH MỤC BÀI HỌC<hr/>";
	$sql="select * from baihoc where muc = '$muc' ";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?act=view&id=".$data['id']."'>".$data['id'].". ".$data['title']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có bài học nào";
	}
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
