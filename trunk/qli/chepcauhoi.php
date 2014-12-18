<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Quản lí các phương án trả lời cho mỗi câu hỏi: thêm/xoá/sửa
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí câu trả lời</title></head><body>";
if (!isset($_GET['cau'])) {
	echo "Chuyển sang phần quản lí câu hỏi";
	redirect("cauhoi.php",1);
	exit(0);
}
$vitri = array("Vị trí đầu tiên","Ngẫu nhiên","Vị trí cuối cùng");
$cau = intval($_GET['cau']);
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM CÂU TRẢ LỜI <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['traloi']);
			$diem = isset($_POST['diem']) ? 1 : 0;
			$thutu = $_POST['vitri'];
			themcautraloi($cau,$tieude,$diem,$thutu);
			redirect("?cau=$cau");
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<form action='?cau=$cau&act=add' method='post'>";
			echo "Thêm câu trả lời: <input type ='text' name='traloi' value='' size='100'> <input type ='checkbox' name='diem'>Đúng. ";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} 
	else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$tl = intval($_GET['id']);
		echo "SỬA CÂU TRẢ LỜI<hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$dung = isset($_POST['diem'])?1:0;
			$thutu = $_POST['vitri'];
			$sql= "update tn_traloi set thutu=$thutu, dung=$dung, noidung='$tieude',ngaylam=NOW() where maso=$tl";
			if (defined("DEBUG")) echo $sql."<br/>";
			$result = mysql_query($sql) or die (mysql_error());
			if ($result) echo "THÀNH CÔNG";
			else echo "THẤT BẠI";
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

function themcautraloi($cau,$traloi,$diem,$thutu) {
	$sql= "insert into tn_traloi (cauhoi,thutu,noidung,dung,ngaylam) value ($cau,$thutu, '$traloi',$diem,NOW())";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}


function suacautraloi($cau,$tl) { //xem de bai
	global $vitri;
	$sql= "select * from tn_traloi where maso=$tl";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?cau=$cau&act=edit&id=$tl' method='post'>";
		echo "Trả lời: <input name='tieude' type='text' size='100' value='". $data['noidung']."'><input type ='checkbox' name='diem' ".($data['dung']?"checked":"").">Đúng. Vị trí:";
		echo "<select name='vitri'>";
		for ($i=0; $i<3; $i++) {
			echo "<option value=$i ".($data['thutu']==$i?"selected":"").">".$vitri[$i]."</option>";
		}
		echo "</select>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có câu hỏi này";
	}
}

function xoacautraloi($cau) {
	$sql= "delete from tn_traloi where maso=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemcautraloi($cau,$tl) { //xem de bai
	$sql= "select * from tn_traloi where maso=$tl";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<p>". $data['noidung']."</p>";
		echo "<p>Thứ tư: ".$data['thutu'].". <i> Phương án ". ($data['dung']?"đúng":"sai")."</i></p>";
		echo "<hr>";
		echo "<p><a href='?cau=$cau'>Quay lại danh mục câu trả lời</a></p>";
		echo "<p><a href='?cau=$cau&act=edit&id=$tl'>Sửa câu trả lời này</a></p>";
		echo "<p><a href='?cau=$cau&act=delete&id=$tl' onclick=\"return (confirm('Bạn muốn xóa câu trả lời này'))\">Xóa câu trả lời này</a></p>";
	} else {
		echo "<p>Không có câu trả lời</p>";
	}
}

function lietketraloi($bai){ //liet ke de bai theo trang
	echo "DANH MỤC CÂU TRẢ LỜI <hr/>";
	$sql= "select * from tn_cauhoi where maso=$bai";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<p>Lớp: ". $data['lop']."</p>";
		echo "<p>Chương: ". $data['chuong']."</p>";
		echo "<p>Bài: ". $data['bai']."</p>";
		echo "<p>Kiểu: ". $data['kieu']."</p>";
		echo "<p>Độ khó: ". $data['dokho']."</p>";
		echo "<p>Nội dung: ". $data['noidung']."</p>";
		echo "<p>Kích hoạt: ". $data['kichhoat']."</p>";
		echo "<p>Ngày tạo: ". $data['ngaylam']."</p>";
	} else {
		exit("Không có câu hỏi");
	}
	echo "<hr/>";
	$sql="select * from tn_traloi where cauhoi=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		echo "<ul>";
		while ($data = mysql_fetch_array($result)){
			echo "<li><a href='?cau=$bai&act=view&id=".$data['maso']."'>".$data['noidung']." [".$data['thutu']."] ".($data['dung']?"*":"")."</a><br/>\n";
			//echo "OK";
		}
		echo "</ul>";
	} else {
		echo "Không có câu trả lời nào";
	}
	echo "<hr/>";
	echo "<form action='?cau=$bai&act=add' method='post'>";
	echo "Thêm câu trả lời: <input type ='text' name='traloi' value='' size='80' id='nhaptraloi'><br/>";
	echo "Kiểu xuất hiện: <select name='vitri'><option value=0>Vị trí đầu tiên</option><option value=1 selected>Ngẫu nhiên</option><option value=2>Vị trí cuối cùng</option></select>. <input type ='checkbox' name='diem'>Đúng. \n";
	echo "<input type ='submit' name='addnew' value='Thêm vào'>";
	echo "</form>";
	echo "<script>document.getElementById('nhaptraloi').focus();</script>";
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
