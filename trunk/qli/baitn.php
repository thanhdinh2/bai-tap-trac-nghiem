<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí bài kiểm tra</title></head><body>";
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM BÀI KIỂM TRA <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = isset($_POST['kichhoat'])?1:0;
            $socau = $_POST['socau'];
            $thoigian = $_POST['thoigian'];
            $ghichu=mysql_real_escape_string($_POST['ghichu']);
			themdebai($tieude,$muc,$socau,$thoigian,$ghichu);
			redirect("?",4);
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<form action='?act=add' method='post'>";
			echo "Tiêu đề <input name='tieude' type='text' size='100'><br/>";
			echo "Kích hoạt sử dụng <input name='kichhoat' type='checkbox'><br/>";
			echo "Số câu hỏi: <input name='socau' type='text'><br/>";
			echo "Thời gian làm bài: <input name='thoigian' type='text'><br/>";
			echo "Tiêu đề (ghi chú): <br/><textarea name='ghichu'></textarea><br/>";
			echo "<input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	} 
	else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$bai = intval($_GET['id']);
		echo "SỬA BÀI KIỂM TRA <hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$muc = isset($_POST['kichhoat'])?1:0;
            $socau = $_POST['socau'];
            $thoigian = $_POST['thoigian'];
            $ghichu=mysql_real_escape_string($_POST['ghichu']);
			capnhatdebai($bai,$tieude,$muc,$socau,$thoigian,$ghichu);
			redirect("?",4);
		} else { //prepare to edit
			suadebai($bai);
		}
	} 
	else if ($act=="delete") {
		if (isset($_GET['id'])) xoadebai($_GET['id']);
		redirect("?",4);
	} 
	else { //vieew
		if (isset($_GET['id'])) { //by id
			echo "BÀI KIỂM TRA<hr/>";
			xemdebai($_GET['id']);
		} 
		else { //by page
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

function themdebai($tieude,$muc,$socau,$thoigian,$ghichu) {
	$sql= "insert into baikiemtra (tenbai,kichhoat,socau,thoigian,tieude)value ('$tieude', $muc,$socau,$thoigian,$ghichu)";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function capnhatdebai($bai,$tieude,$muc,$socau,$thoigian,$ghichu) {
	$sql= "update baikiemtra set tenbai='$tieude', kichhoat=$muc,socau=$socau,thoigian=$thoigian,tieude='$ghichu' where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function suadebai($bai) { //xem de bai
	$sql= "select * from baikiemtra where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?act=edit&id=$bai' method='post'>";
		echo "Tiêu đề <input name='tieude' type='text' size='100' value='". $data['tenbai']."'><br/>";
		echo "Kích hoạt <input name='kichhoat' type='checkbox'  ".($data['kichhoat']>0?"checked":"")."><br/>";
        echo "Số câu hỏi: <input name='socau' type='text' value='".$data['socau']."'><br/>";
        echo "Thời gian làm bài: <input name='thoigian' type='text' value='".$data['thoigian']."'><br/>";
        echo "Tiêu đề (ghi chú): <br/><textarea name='ghichu'>".$data['tieude']."</textarea><br/>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
	} else {
		echo "Không có bài này";
	}
}

function xoadebai($bai) {
	$sql= "delete from baikiemtra where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemdebai($bai) { //xem de bai
	$sql= "select * from baikiemtra where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<u>". $data['tenbai']."</u><br/>";
		echo "<iframe src='cauhoi.php?bai=$bai' height='500' width='1000'></iframe><br/>";
		echo "<a href='preview.php?id=$bai'>Xem thử bài kiểm tra</a><br/>";
		echo "<a href='?'>Quay lại danh mục bài kiểm tra</a><br/>";
		echo "<a href='?act=edit&id=$bai'>Sửa bài này</a><br/>";
		echo "<a href='?act=delete&id=$bai' onclick=\"return (confirm('Bạn muốn xóa bài kiểm tra này'))\">Xóa bài này</a><br/>";
	} else {
		echo "Không có bài này";
	}
}

function lietkedebai($trang=1,$sl1trang=20){ //liet ke de bai theo trang
	echo "DANH MỤC BÀI KIỂM TRA <hr/>";
	$sql="select * from baikiemtra limit ".($trang-1)*$sl1trang.",$sl1trang";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	
	if (mysql_num_rows($result)) {
		echo "<table border='1'>";
		echo "<tr align='center'><td>STT</td><td>Tên bài kiểm tra</td><td>Số câu</td><td>Thời gian</td><td>Kích hoạt</td></tr>";
		$i=1;
		while ($data = mysql_fetch_array($result)){
			echo "<tr align='center'>";
			echo "<td>".$i++."</td>";
			echo "<td align='left'><a href='?act=view&id=".$data['id']."'>".$data['tenbai']."</a></td>";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".$data['thoigian']."</td>";
			echo "<td>".$data['kichhoat']."</td>";
			//echo "OK";
		}
		echo "</table>";
	} else {
		echo "Không có bài kiểm tra nào";
	}
	echo "<hr/>";
	echo "<a href='?act=add'>Thêm bài kiểm tra</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
