<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí câu hỏi</title></head><body>";
if (!isset($_GET['bai'])) {
	echo "Chuyển sang phần quản lí bài trắc nghiệm";
	redirect("baitn.php",3);
	exit(0);
}
$bai = intval($_GET['bai']);
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM CÂU HỎI <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['cauhoi']);
			themcauhoi($bai,$tieude);
			redirect("?bai=$bai",3);
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<form action='?bai=$bai&act=add' method='post'>";
			echo "Câu hỏi <br/><textarea name='cauhoi' ></textarea><br/>";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} 
	else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$cau = intval($_GET['id']);
		echo "SỬA CÂU HỎI<hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = isset($_POST['muc'])?1:0;
			capnhatcauhoi($cau,$tieude);
			redirect("?bai=$bai",3);
		} else { //prepare to edit
			suacauhoi($bai,$cau);
		}
	} 
	else if ($act=="delete") {
		if (isset($_GET['id'])) xoacauhoi($_GET['id']);
		redirect("?bai=$bai",3);
	} 
	else { //vieew
		if (isset($_GET['id'])) { //by id
			echo "CÂU HỎI<hr/>";
			xemcauhoi($bai,$_GET['id']);
		} 
		else { //all
			lietkecauhoi($bai);
		}
	}
}
else {
	lietkecauhoi($bai);
}
echo "</body></html>";

function themcauhoi($bai,$hoi) {
	$sql= "insert into cauhoi (bai,hoi) value ($bai, '$hoi')";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function capnhatcauhoi($cau,$tieude) {
	$sql= "update cauhoi set hoi='$tieude' where id=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function suacauhoi($bai,$cau) { //xem de bai
	$sql= "select * from cauhoi where id=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?bai=$bai&act=edit&id=$cau' method='post'>";
		echo "Câu hỏi<br/> <textarea name='tieude' >". $data['hoi']."</textarea><br/>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có câu hỏi này";
	}
}

function xoacauhoi($cau) {
	$sql= "delete from cauhoi where id=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemcauhoi($bai,$cau) { //xem de bai
	$sql= "select * from cauhoi where id=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "". $data['hoi']."<br/>";
		echo "<iframe src='traloi.php?cau=$cau' height='300' width='800'></iframe><br/>";
		echo "<a href='?bai=$bai'>Quay lại danh mục câu hỏi</a><br/>";
		echo "<a href='?bai=$bai&act=edit&id=$cau'>Sửa câu hỏi này</a><br/>";
		echo "<a href='?bai=$bai&act=delete&id=$cau' onclick=\"return (confirm('Bạn muốn xóa câu hỏi này'))\">Xóa câu hỏi này</a><br/>";
	} else {
		echo "Không có câu hỏi";
	}
}

function lietkecauhoi($bai){ //liet ke de bai theo trang
	echo "DANH MỤC CÂU HỎI <hr/>";
	$sql="select * from cauhoi where bai=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		$i=1;
		while ($data = mysql_fetch_array($result)){
			echo $i++.". <a href='?bai=$bai&act=view&id=".$data['id']."'>".$data['hoi']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có câu hỏi nào";
	}
	echo "<hr/>";
	echo "<a href='?bai=$bai&act=add'>Thêm câu hỏi</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
