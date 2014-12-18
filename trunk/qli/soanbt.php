<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí bài tập</title><script src='ckeditor/ckeditor.js'></script></head><body>";
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM BÀI TẬP <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = ($_POST['muc']);
			$noidung = mysql_real_escape_string($_POST['noidung']);
			themdebai($tieude,$muc,$noidung);
			redirect("?",4);
		} else { //input
			echo "<form action='?act=add' method='post'>";
			echo "Tiêu đề <input name='tieude' type='text' size='100'><br/>";
			echo "Mục <input name='muc' type='text' value='".(isset($_GET['muc'])?$_GET['muc']:"")."' ><br/>";
			echo "Nội dung <textarea name='noidung' class='ckeditor' rows='9' cols='70'></textarea><br/>";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$bai = intval($_GET['id']);
		echo "SỬA BÀI TẬP <hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = ($_POST['muc']);
			$noidung = mysql_real_escape_string($_POST['noidung']);
			capnhatdebai($bai,$tieude,$muc,$noidung);
			redirect("?",4);
		} else { //prepare to edit
			suadebai($bai);
		}
	} else if ($act=="delete") {
		if (isset($_GET['id'])) xoadebai($_GET['id']);
		redirect("?",4);
	} else { //vieew
		if (isset($_GET['id'])) { //by id
			echo "BÀI TẬP<hr/>";
			xemdebai($_GET['id']);
		} else if (isset($_GET['muc'])) { //by category 
			lietketheomuc($_GET['muc']);
		} else { //by page
			if (isset($_GET['page'])) $page = intval($_GET['page']);
			else $page=1;
			if (isset($_GET['tren1page'])) $tren1page = intval($_GET['tren1page']);
			else $tren1page=0;
			if ($tren1page<20) $tren1page=20;
			lietkedebai($page,$tren1page);
		}
	}

}
else {
	lietkedebai();
}
echo "</body></html>";

function themdebai($tieude,$muc,$noidung) {
	$sql= "insert into th_baitap (tieude,mamuc,noidung)value ('$tieude', '$muc','$noidung')";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function capnhatdebai($bai,$tieude,$muc,$noidung) {
	$sql= "update th_baitap set tieude='$tieude', mamuc='$muc',noidung='$noidung' where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function suadebai($bai) { //xem de bai
	$sql= "select * from th_baitap where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?act=edit&id=$bai' method='post'>";
		echo "<p>Tiêu đề <input name='tieude' type='text' size='100' value='". $data['tieude']."'></p>";
		echo "<p>Mục <input name='muc' type='text'  value='". $data['mamuc']."'></p>";
		echo "Nội dung <br/><textarea name='noidung' class='ckeditor' rows='9' cols='70'>".$data['noidung']."</textarea><br/>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có bài này";
	}
}

function xoadebai($bai) {
	$sql= "delete from th_baitap where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemdebai($bai) { //xem de bai
	$sql= "select * from th_baitap where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		//echo "<u>Mã số:</u> ". $data['id']."<br/>";
		echo "<u>". $data['tieude']."</u><br/>";
		echo $data['noidung']."<br/>";
		echo "<hr>";
		echo "<a href='?act=edit&id=$bai'>Sửa bài này</a><br/>";
		echo "<a href='?act=delete&id=$bai' onclick=\"return (confirm('Bạn muốn xóa bài tập này'))\">Xóa bài này</a><br/>";
	} else {
		echo "Không có bài này";
	}
}

function lietkedebai($trang=1,$sl1trang=30){ //liet ke de bai theo trang
	echo "DANH MỤC BÀI TẬP <hr/>";
	$sql="select * from th_baitap limit ".($trang-1)*$sl1trang.",$sl1trang";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?act=view&id=".$data['maso']."'>".$data['maso'].". ".$data['tieude']."</a> #<a href='?act=view&muc=".$data['mamuc']."'>".$data['mamuc']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có bài tập nào";
	}
	echo "<hr/>";
	echo "<a href='?act=add'>Thêm bài tập</a>";
}

function lietketheomuc($muc){ //liet ke de bai theo muc
	echo "DANH MỤC BÀI TẬP <hr/>";
	$sql="select * from th_baitap where mamuc = '$muc' ";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?act=view&id=".$data['maso']."'>".$data['maso'].". ".$data['tieude']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có bài tập nào";
	}
	echo "<hr/>";
	echo "<a href='?act=add&muc=$muc'>Thêm bài tập</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
