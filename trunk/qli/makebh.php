<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Bai hoc</title></head><body>";
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM BÀI HỌC <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = ($_POST['muc']);
			$noidung = mysql_real_escape_string($_POST['noidung']);
			$baitap = ($_POST['baitap']);
			thembai($tieude,$muc,$noidung,$baitap);
			redirect("?",4);
		} else { //input
			echo "<form action='?act=add' method='post'>";
			echo "Tiêu đề <input name='tieude' type='text' size='100'><br/>";
			echo "Mã <input name='muc' type='text' value='".(isset($_GET['muc'])?$_GET['muc']:"")."' ><br/>";
			echo "Nội dung <textarea name='noidung' rows='9' cols='70'></textarea><br/>";
			echo "Mã bài tập <input name='baitap' type='text' value='' ><br/>";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$bai = intval($_GET['id']);
		echo "SỬA BÀI HỌC <hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = ($_POST['muc']);
			$noidung = mysql_real_escape_string($_POST['noidung']);
			$baitap = ($_POST['baitap']);
			capnhatdebai($bai,$tieude,$muc,$noidung,$baitap);
			redirect("?",4);
		} else { //prepare to edit
			suabai($bai);
		}
	} else if ($act=="delete") {
		if (isset($_GET['id'])) xoabai($_GET['id']);
		redirect("?",4);
	} else { //vieew
		if (isset($_GET['id'])) { //by id
			echo "BÀI HỌC<hr/>";
			xembai($_GET['id']);
		} else if (isset($_GET['muc'])) { //by category 
			lietketheomuc($_GET['muc']);
		} else { //by page
			if (isset($_GET['page'])) $page = intval($_GET['page']);
			else $page=1;
			if (isset($_GET['tren1page'])) $tren1page = intval($_GET['tren1page']);
			else $tren1page=0;
			if ($tren1page<20) $tren1page=20;
			lietkebai($page,$tren1page);
		}
	}

}
else {
	lietkedebai();
}
echo "</body></html>";

function thembai($tieude,$muc,$noidung,$codebaitap) {
	$sql= "insert into baihoc (title,code,content,codebt)value ('$tieude', '$muc','$noidung','$codebaitap')";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function capnhatbai($bai,$tieude,$muc,$noidung,$baitap) {
	$sql= "update baihoc set ten='$tieude', muc='$muc',noidung='$noidung',codebt='$baitap' where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function suabai($bai) { //xem de bai
	$sql= "select * from baihoc where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?act=edit&id=$bai' method='post'>";
		echo "Tiêu đề <input name='tieude' type='text' size='100' value='". $data['title']."'><br/>";
		echo "Mục <input name='muc' type='text'  value='". $data['code']."'><br/>";
		echo "Nội dung <textarea name='noidung' rows='9' cols='70'>".$data['content']."</textarea><br/>";
		echo "Mã bài tập <input name='baitap' type='text' value='". $data['codebt']."' ><br/>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có bài này";
	}
}

function xoabai($bai) {
	$sql= "delete from baihoc where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xembai($bai) { //xem de bai
	$sql= "select * from baihoc where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		//echo "<u>Mã số:</u> ". $data['id']."<br/>";
		echo "<u>". $data['title']."</u><br/>";
		echo $data['content']."<br/>";
		echo "<hr>";
		echo "<a href='?act=edit&id=$bai'>Sửa bài này</a><br/>";
		echo "<a href='?act=delete&id=$bai' onclick=\"return (confirm('Bạn muốn xóa bài học này'))\">Xóa bài này</a><br/>";
	} else {
		echo "Không có bài này";
	}
}

function lietkebai($trang=1,$sl1trang=20){ //liet ke de bai theo trang
	echo "DANH MỤC BÀI HỌC <hr/>";
	$sql="select * from baihoc limit ".($trang-1)*$sl1trang.",$sl1trang";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?act=view&id=".$data['id']."'>".$data['id'].". ".$data['title']."</a> //<a href='?act=view&muc=".$data['code']."'>".$data['code']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có bài học nào";
	}
	echo "<hr/>";
	echo "<a href='?act=add'>Thêm bài học</a>";
}

function lietketheomuc($muc){ //liet ke de bai theo muc
	echo "DANH MỤC BÀI HỌC <hr/>";
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
	echo "<hr/>";
	echo "<a href='?act=add&muc=$muc'>Thêm bài học</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
