<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí câu trả lời</title></head><body>";
if (!isset($_GET['cau'])) {
	echo "Chuyển sang phần quản lí bài trắc nghiệm";
	redirect("baitn.php",1);
	exit(0);
}
$cau = intval($_GET['cau']);
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM CÂU TRẢ LỜI <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['traloi']);
			$diem = intval($_POST['diem']);
			themcautraloi($cau,$tieude,$diem);
			redirect("?cau=$cau");
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<form action='?cau=$cau&act=add' method='post'>";
			echo "Câu trả lời <input type ='text' name='traloi' value='' size='60'><br/>";
			echo "Điểm <input type ='text' name='diem' value='0' size='9'>";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} 
	else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$tl = intval($_GET['id']);
		echo "SỬA CÂU HỎI<hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			//$muc = isset($_POST['muc'])?1:0;
			capnhatcautraloi($tl,$cau,$tieude);
			redirect("?cau=$cau");
		} else { //prepare to edit
			suacautraloi($cau,$tl);
		}
	} 
	else if ($act=="delete") {
		if (isset($_GET['id'])) xoacautraloi($_GET['id']);
		redirect("?cau=$cau",1);
	} 
	else { //vieew
		if (isset($_GET['id'])) { //by id
			echo "CÂU TRẢ LỜI<hr/>";
			xemcautraloi($cau,$_GET['id']);
		} 
		else { //all
			lietketraloi($cau);
		}
	}
}
else {
	lietketraloi($cau);
}
echo "</body></html>";

function themcautraloi($cau,$traloi,$diem) {
	$sql= "insert into traloi (cauhoi,phuongan,diem) value ($cau, '$traloi',$diem)";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function capnhatcautraloi($tl,$cau,$tieude) {
	$sql= "update traloi set cauhoi=$cau, phuongan='$tieude' where id=$tl";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function suacautraloi($cau,$tl) { //xem de bai
	$sql= "select * from traloi where id=$tl";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?cau=$cau&act=edit&id=$tl' method='post'>";
		echo "Tiêu đề <input name='tieude' type='text' size='100' value='". $data['phuongan']."'><br/>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có câu hỏi này";
	}
}

function xoacautraloi($cau) {
	$sql= "delete from traloi where id=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemcautraloi($cau,$tl) { //xem de bai
	$sql= "select * from traloi where id=$tl";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<u>". $data['phuongan']."</u><br/>";
		echo "<hr>";
		echo "<a href='?cau=$cau'>Quay lại danh mục câu trả lời</a><br/>";
		echo "<a href='?cau=$cau&act=edit&id=$tl'>Sửa câu trả lời này</a><br/>";
		echo "<a href='?cau=$cau&act=delete&id=$tl' onclick=\"return (confirm('Bạn muốn xóa câu trả lời này'))\">Xóa câu trả lời này</a><br/>";
	} else {
		echo "Không có câu trả lời";
	}
}

function lietketraloi($bai){ //liet ke de bai theo trang
	echo "DANH MỤC CÂU TRẢ LỜI <hr/>";
	$sql="select * from traloi where cauhoi=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?cau=$bai&act=view&id=".$data['id']."'>".$data['phuongan']." (".$data['diem'].")</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có câu trả lời nào";
	}
	echo "<hr/>";
	echo "<form action='?cau=$bai&act=add' method='post'>";
	echo "Thêm câu trả lời: <input type ='text' name='traloi' value='' size='40'><br/>";
	echo "Điểm: <input type ='text' name='diem' value='0' size='9'>";
	echo "<input type ='submit' name='addnew' value='Thêm vào'>";
	echo "</form>";
	echo "<br/><a href='?cau=$bai&act=add'>Thêm câu trả lời</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
