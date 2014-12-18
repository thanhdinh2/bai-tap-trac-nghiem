<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Quản lí các bài trắc nghiệm: thêm/xoá/sửa
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Quản lí bài kiểm tra</title>";
?>
<link rel="stylesheet" href='mystyles.css' type='text/css'>
<?php
echo "</head><body>";
if (isset($_GET['act'])) {
	$act = $_GET['act'];
	if ($act=="add") {
		echo "THÊM BÀI KIỂM TRA <hr/>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$kichhoat = isset($_POST['kichhoat'])?1:0;
            $socau = $_POST['socau'];
            $thoigian = $_POST['thoigian'];
            $khoi=$_POST['khoi'];
			$chuong=$_POST['chuong'];
			$bai=$_POST['bai'];
			$cau=$_POST['cau'];
			$sql= "INSERT INTO `tn_kiemtra`( `kichhoat`, `cauhoi`, `socau`, `sogiay`, `lop`,`chuong`,`bai`,`cau`, `tenbai`, `ngaylam`) VALUES ('$kichhoat', '','$socau', '$thoigian','$khoi','$chuong','$bai','$cau','$tieude',NOW())";
			if (defined("DEBUG")) echo $sql."<br/>";
			$result = mysql_query($sql) or die (mysql_error());
			if ($result) echo "THÀNH CÔNG";
			else echo "THẤT BẠI";
			redirect("?",1);
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<form action='?act=add' method='post'>";
			echo "<p>Tên bài kiểm tra <input name='tieude' type='text' size='100'></p>";
			echo "<p>Kích hoạt sử dụng <input name='kichhoat' type='checkbox'></p>";
			echo "<p>Số câu hỏi: <input name='socau' type='text'></p>";
			echo "<p>Thời gian làm bài (giây): <input name='thoigian' type='text'></p>";
			echo "<p>Khối: <input name='khoi' type='text' size='2'></p>";
			echo "<p>Lựa chọn các câu hỏi từ đâu: </p>";
			echo "<p>-Chọn từ các chương: <input name='chuong' type='text' size='20'></p>";
			echo "<p>-Chọn từ các bài: <input name='bai' type='text' size='40'></p>";
			echo "<p>-Chọn từ các câu: <input name='cau' type='text' size='100'></p>";
			echo "<p><input type ='submit' name='addnew' value='Thêm vào'>";
			echo "</form>";
		}
	}
	else if ($act=="edit") {
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$mabai = intval($_GET['id']);
		echo "SỬA BÀI KIỂM TRA <hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['tieude']);
			$kichhoat = isset($_POST['kichhoat'])?1:0;
            $socau = $_POST['socau'];
            $thoigian = $_POST['thoigian'];
            $khoi=$_POST['khoi'];
			$chuong=$_POST['chuong'];
			$bai=$_POST['bai'];
			$cau=$_POST['cau'];

			$sql= "UPDATE `tn_kiemtra` SET `kichhoat`=$kichhoat,`socau`=$socau,`sogiay`=$thoigian,`lop`=$khoi,`chuong`='$chuong',`bai`='$bai',`cau`='$cau',`tenbai`='$tieude',`ngaylam`=NOW() WHERE `maso`=$mabai";
			//echo $sql."<br/>";
			$result = mysql_query($sql) or die (mysql_error());
			if ($result) echo "THÀNH CÔNG";
			else echo "THẤT BẠI";
			redirect("?",1);
		} else { //prepare to edit
			suadebai($mabai);
		}
	} 
	else if ($act=="toggle") {
		if (isset($_GET['id'])) battat($_GET['id']);
		redirect("?",1);
	} 
	else if ($act=="delete") {
		if (isset($_GET['id'])) xoadebai($_GET['id']);
		redirect("?",2);
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
	
}

function suadebai($bai) { //xem de bai
	$sql= "select * from tn_kiemtra where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<form action='?act=edit&id=$bai' method='post'>";
		echo "<p>Tên bài kiểm tra <input name='tieude' type='text' size='100' value='".$data['tenbai']."'></p>";
		echo "<p>Kích hoạt sử dụng <input name='kichhoat' type='checkbox' ".($data['kichhoat']?"checked":"")."></p>";
		echo "<p>Số câu hỏi: <input name='socau' type='text' value='".$data['socau']."'></p>";
		echo "<p>Thời gian làm bài (giây): <input name='thoigian' type='text' value='".$data['sogiay']."'></p>";
		echo "<p>Khối: <input name='khoi' type='text' size='2' value='".$data['lop']."'></p>";
		//echo "<p>Các câu hỏi:<br/><textarea name='cau' cols='60'>".$data['cauhoi']."</textarea></p>";
			echo "<p>Lựa chọn các câu hỏi từ: </p>";
			echo "<p>-Chọn từ các chương: <input name='chuong' type='text' size='20' value='".$data['chuong']."'></p>";
			echo "<p>-Chọn từ các bài: <input name='bai' type='text' size='40' value='".$data['bai']."'></p>";
			echo "<p>-Chọn từ các câu: <input name='cau' type='text' size='100' value='".$data['cau']."'></p>";
		echo "<input type ='submit' name='save' value='Lưu lại'>";
		echo "</form>";
		echo "<br/><a href='?'>Quay lại danh mục bài kiểm tra</a><br/>";
	} else {
		echo "Không có bài này";
	}
}
function battat($bai) {
	$sql= "update tn_kiemtra set  kichhoat=1-kichhoat where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}
function xoadebai($bai) {
	$sql= "delete from tn_kiemtra where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if ($result) echo "THÀNH CÔNG";
	else echo "THẤT BẠI";
}

function xemdebai($bai) { //xem de bai
	$sql= "select * from tn_kiemtra where maso=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<p>Mã số: ".$data['maso']."</p>";
		echo "<p>Khối lớp: ".$data['lop']."</p>";
		echo "<p>Chương: ".$data['chuong']."</p>";
		echo "<p>Bài: ".$data['bai']."</p>";
		echo "<p>Câu: ".$data['cau']."</p>";
		echo "<p>Tên bài: ".$data['tenbai']."</p>";
		echo "<p>Số câu: ".$data['socau']."</p>";
		echo "<p>Thời gian: ".$data['sogiay']." (giây)</p>";
		echo "<p>Ngày tạo: ".$data['ngaylam']."</p>";
		echo "<hr/>";
		echo "<a href='preview.php?id=$bai'>Xem thử bài kiểm tra</a><br/>";
		echo "<a href='?'>Quay lại danh mục bài kiểm tra</a><br/>";
		echo "<a href='?act=edit&id=$bai'>Sửa bài này</a><br/>";
		echo "<a href='?act=delete&id=$bai' onclick=\"return (confirm('Bạn muốn xóa bài kiểm tra này'))\">Xóa bài này</a><br/>";
	} else {
		echo "Không có bài này";
	}
}

function lietkedebai($trang=1,$sl1trang=30){ //liet ke de bai theo trang
	echo "DANH MỤC BÀI KIỂM TRA <hr/>";
	$sql="select * from tn_kiemtra limit ".($trang-1)*$sl1trang.",$sl1trang";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	
	if (mysql_num_rows($result)) {
		echo "<table border='1'>";
		echo "<tr align='center'><td>Mã</td><td>Tên bài kiểm tra</td><td>Lớp</td><td>Chương</td><td>Bài</td><td>Câu</td><td>SC</td><td>TG</td><td>Ngày tạo</td></tr>";
		while ($data = mysql_fetch_array($result)){
			echo "<tr>";
			echo "<td width='2%'>".$data['maso']."</td>";
			
			echo "<td width='15%' align='left' class='kichhoat".$data['kichhoat']."'><a href='?act=view&id=".$data['maso']."'>".$data['tenbai']."</a><a class='chucnang' href='baitn.php?act=toggle&id=".$data['maso']."'>[".($data['kichhoat']?"Tắt":"Bật")."]</a></td>";
			echo "<td width='2%'>".$data['lop']."</td>";
			echo "<td width='2%'>".$data['chuong']."</td>";
			echo "<td width='2%'>".$data['bai']."</td>";
			echo "<td width='2%'>".$data['cau']."</td>";
			echo "<td width='2%'>".$data['socau']."</td>";
			echo "<td width='3%'>".$data['sogiay']."</td>";
			//echo "<td width='50%' class='ngatdong'>".$data['cauhoi']."</td>";
			echo "<td width='10%'>".$data['ngaylam']."</td>";
			//echo "OK";
		}
		echo "</table>";
	} else {
		echo "Không có bài kiểm tra nào";
	}
	//echo "<hr/>";
	echo "<br/><a href='?act=add'>Thêm bài kiểm tra</a>";
}

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
