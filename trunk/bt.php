<?php

require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Bài tập</title></head><body>";

if (isset($_POST['nop'])) {
	$hoten = $_POST['hoten'];
	$lop = $_POST['lop'];
	$baitap = $_POST['baitap'];
	$ufile = uploadFile("nfile","upload",defined("DEBUG"));
	if ($ufile) {
		$sql="insert into nopbai (hoten,lop,ngaygio,ip,file,baitap) value ('$hoten','$lop',NOW(),'".$_SERVER['REMOTE_ADDR']."','$ufile',$baitap)";
		if (defined("DEBUG")) echo $sql."<br/>";
		$result = mysql_query($sql) or die (mysql_error());
		echo "Đã nộp xong.<br/>".$hoten." lớp ".$lop." Tập tin nộp: ".$ufile;
	}
	else echo "THẤT BẠI";
} else if (isset($_GET['muc'])) {
	lietketheomuc($_GET['muc']);
} else if (isset($_GET['id'])) {
	echo "BÀI TẬP<hr/>";
	xemdebai($_GET['id']);
} else {
	echo "WELCOME";
}
echo "</body></html>";

function uploadFile($uname,$folder,$debug=false) {
	if ($debug) print_r($_FILES);
	if (!empty($_FILES[$uname])) {
		if (is_uploaded_file($_FILES[$uname]["tmp_name"])){
			if ($_FILES[$uname]["error"] > 0)  {
				if ($debug) echo "Upload MP3 with error : " . $_FILES[$uname]["error"] . "<br />";
				return "";
			} else {
				if ($debug) echo "Upload: " . $_FILES[$uname]["name"] . "<br />";
				if ($debug) echo "Type: " . $_FILES[$uname]["type"] . "<br />";
				if ($debug) echo "Size: " . ($_FILES[$uname]["size"] / 1024) . " Kb<br />";
				if ($debug) echo "Temp file: " . $_FILES[$uname]["tmp_name"] . "<br />";
				$filename = $_FILES[$uname]["name"];
				$i=1;
				while (file_exists($folder. "/".$filename)) {
					$filename = $i++ ."_". $_FILES[$uname]["name"];    
				}
				move_uploaded_file($_FILES[$uname]["tmp_name"], $folder. "/" . $filename);
				return $filename;
			}
		} else {
			if ($debug) echo "Upload Fail";
			return "";
		}
	}
	if ($debug) echo "Not found";
	return "";
}

function xemdebai($bai) { //xem de bai
	$sql= "select * from baitap where id=$bai";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<u><b>". $data['ten']."</b></u><br/><br/>";
		echo $data['noidung']."<br/>";
		echo "<hr/>";
		echo "NỘP BÀI<br/>";
		echo "<form action='' method='post' enctype='multipart/form-data'>";
		echo "Họ và tên: <input name='hoten' type='text' size='40'><br/>";
		echo "Lớp: <input name='lop' type='text' size='9'><br/>";
		
		echo "Tập tin: <input name='nfile' type='file'  id='nfile'><br/>";
		echo "<input name='baitap' type='hidden' value='$bai'><br/>";
		echo "<input name='nop' type='submit' value='Nộp bài'><br/>";
		echo "</form>";
	} else {
		echo "Không có bài này";
	}
}


function lietketheomuc($muc){ //liet ke de bai theo muc
	echo "DANH MỤC BÀI TẬP<hr/>";
	$sql="select * from baitap where muc = '$muc' ";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die(mysql_error()); 
	if (mysql_num_rows($result)) {
		
		while ($data = mysql_fetch_array($result)){
			echo "<a href='?act=view&id=".$data['id']."'>".$data['id'].". ".$data['ten']."</a><br/>\n";
			//echo "OK";
		}
	} else {
		echo "Không có bài tập nào";
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
