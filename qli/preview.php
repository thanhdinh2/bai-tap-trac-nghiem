<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Xem trước bài kiểm tra: câu hỏi và phương án trả lời ngẫu nhiên (&da=có đáp án,&no=không có chữ cái A,B,C,D...)
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
include_once("bbcode.php");
//define("DEBUG",TRUE);
echo "<html><head><title>Xem thử bài trắc nghiệm</title>";
echo "<link rel='stylesheet' type='text/css' href='mystyles.css' media='screen' /><style></style>";
echo "</head><body>";
echo "XEM THỬ BÀI KIỂM TRA TRỰC TUYẾN<hr/>";
session_start();
$demuc=isset($_GET['no']);
$codapan=isset($_GET['da']);
$luotchon=isset($_GET['lc']);
$random =isset($_GET['rand']);
$sothutu = isset($_GET['stt']);
	if (isset($_GET['id'])) { //da chon ki thi
		$bai = intval($_GET['id']);
		$sql = "select * from tn_kiemtra where maso=$bai";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) { //co ki thi
			$data = mysql_fetch_array($result);
			//echo $data['tenbai'].layCaccauhoi($data['lop'],$data['chuong'],$data['bai'],$data['cau'])."<hr/>";
			$sql = "select * from tn_cauhoi where maso in (".layCaccauhoi($data['lop'],$data['chuong'],$data['bai'],$data['cau']).") ".($random?"order by rand();":"");
			$result = mysql_query($sql);
			$ch=1;
			echo "<div id='baikiemtra' >";
			while ($data = mysql_fetch_array($result)) {
				echo "<div class='cau' >";
				echo "<br/><div class='cauhoi'><a href='cauhoi.php?act=view&id=".$data['maso']."'>";
				echo ($ch++).".</a> ".showBBcodes($data['noidung'])."<br/>";
				echo "</div>";
				echo "<div class='traloi'>";
				$sql = "select * from tn_traloi where cauhoi=".$data['maso']." order by thutu+rand()";
				$result2 = mysql_query($sql) or die(mysql_error());
				$pa=1;
				while ($data2 = mysql_fetch_array($result2)) {
					echo "<div class='phuongan";
					if ($codapan) echo $data2['dung'];
					echo "'>";
					if (!$demuc) echo "<span>".chr(64+$pa++)."</span>. ";
					echo showBBcodes($data2['noidung']);
					if ($luotchon) echo " <span>(".$data2['luotchon'].")</span>";
					echo "</div>";
				}
				echo "</div>";
				echo "</div>";
			}
			echo "</div>";
		}
		else { //khong co ki thi
			echo "Không có bài kiểm tra";
		}
	}
	else if (isset($_GET['cau'])) {
		$phan = explode(",",$_GET['cau']);
		$caccau="";
		for ($i=0; $i<count($phan); $i++) {
			if ($phan[$i]){
				if (strpos($phan[$i],"-") === false) {
					$caccau.=$phan[$i].",";
				}
				else {
					$daucuoi = explode("-",$phan[$i]);
					$dau = intval($daucuoi[0]);
					$cuoi = intval($daucuoi[1]);
					for ($j=$dau; $j<=$cuoi; $j++) {
						$caccau.=$j.",";
					}
				}
			}
		}
		if ($caccau) $caccau=rtrim($caccau,",");
		$sql = "select * from tn_cauhoi where maso in (".$caccau.") ".($random?"order by rand();":"");
		//exit($sql);
		$result = mysql_query($sql);
		$ch=1;
		echo "<div id='baikiemtra' >";
		while ($data = mysql_fetch_array($result)) {
			echo "<div class='cau' >";
			echo "<br/><div class='cauhoi'>";
			echo "<a href='cauhoi.php?act=view&id=".$data['maso']."'>".($sothutu?$ch++:$data['maso'])."</a>. ".showBBcodes($data['noidung'])."<br/>";
			//echo "<a href='cauhoi.php?act=edit&id=".$data['maso']."'>".($ch++).".</a> (#".$data['maso'].") ".showBBcodes($data['noidung'])."<br/>";
			echo "</div>";
			echo "<div class='traloi'>";
			$sql = "select * from tn_traloi where cauhoi=".$data['maso']." order by thutu+rand()";
			$result2 = mysql_query($sql) or die(mysql_error());
			$pa=1;
			while ($data2 = mysql_fetch_array($result2)) {
				echo "<div class='phuongan";
				if ($codapan) echo $data2['dung'];
				echo "'>";
				if (!$demuc) echo "<span>".chr(64+$pa++)."</span>. ";
				echo showBBcodes($data2['noidung']);
				echo "</div>";
				//if ($data2['diem']>0) if (rand()>10000) $dapan=chr($ch+63).$pa.$dapan; else $dapan.=chr($ch+63).$pa;
			}
			echo "</div>";
			echo "</div>";
		}
	}
	else { //chua chon ki thi
		$sql = "select * from tn_kiemtra";
		$result = mysql_query($sql);
		echo "<table border='1'>";
		echo "<tr><td>STT</td><td>Tên bài kiểm tra</td><td>Số câu hỏi</td><td>Thời gian</td></tr>";
		$i=1;
		while ($data=mysql_fetch_array($result)) {
			//echo "<a href='?id=".$data['id']."'>".$data['tenbai']."</a><br/>";
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td><a href='?id=".$data['maso']."'>".$data['tenbai']."</a></td>\n";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".intval($data['sogiay']/60)." phút"."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	

echo "</body></html>";
function layCaccauhoi($lop,$chuong,$bai,$cau) {
	$sql="(lop=$lop)";
	if ($chuong) $sql.= " and (chuong in ($chuong))";
	if ($bai) $sql.= " and (bai in ($bai))";
	if ($cau) $sql.= " or (maso in ($cau))";
	$sql="select maso from tn_cauhoi where ".$sql;
	//echo $sql;
	$result = mysql_query($sql) or die (mysql_error());
	$cauhoi="";
	while ($data=mysql_fetch_array($result)) {
		$cauhoi .= $data['maso'].",";
	}
	return rtrim($cauhoi,",");
}
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}

?>
