<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Xem bài làm của thí sinh
 * Ngày 11/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
include_once("bbcode.php");
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Xem bài làm</title><link rel='stylesheet' type='text/css' href='mystyles.css' media='screen' />";
//echo "<style>.phuongan1{color:red;}</style>";
echo "</head><body>";
echo " BÀI LÀM KIỂM TRA TRỰC TUYẾN<hr/>";
session_start();
$demuc=isset($_GET['no']);
$codapan=isset($_GET['da']);

	if (isset($_GET['id'])) { //da chon bai thi
		$bai = intval($_GET['id']);
		$sql = "select *, lucnop-lucdau as tgian from tn_ketqua where id=$bai";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) { //co ki thi
			$data = mysql_fetch_array($result);
			echo "<p><a href='baitn.php?act=view&id=".$data['bai']."'>".$data['bai']."</a>. ";
			echo "".$data['hoten']." - ".$data['lop']." (#$bai)";
			echo ". Nộp bài vào lúc: ".$data['lucnop'].". Thời gian làm: ".$data['tgian']." giây";
			echo "</p><hr/>";
			$traloi="," . $data['traloi'] . ",";
			$cauhoikt = explode("|",$data['cauhoi']);
			//echo $data['cauhoi']."<br>";
			$ch=1;
			echo "<div id='baikiemtra'>";
			foreach ($cauhoikt as $cauhoirieng) { //cauhoirieng=1 cau hoi, có dạng mã câu hỏi: các câu trả lời
				if (strlen($cauhoirieng)>0) {
					$hoidap=explode(":",$cauhoirieng); //tach phan cau hoi va cac cau tra loi
					$cautraloi = explode(",",$hoidap[1]);
					echo "<div class='cau'>";
					echo "<div class='cauhoi'>";
					echo "<b><a href='cauhoi.php?id=".$hoidap[0]."&act=view'>Câu ".($ch++).".</a></b> ".showBBcodes(layCauhoi($hoidap[0]))."<br/>";
					echo "</div>";
					//$caccauhoi.="|".$data['id'].":";
					echo "<div class='traloi'>";
					$pa=1;
					foreach ($cautraloi as $tloi) {
						if (strlen($tloi)>0) {
							$pantl=showBBcodes(layTraloi($tloi));
							echo "<div class='traloi".(strstr($traloi,",$tloi,")?"1":"").$pantl[1]."' id='pa".$tloi."'><span>";
							echo chr(64+$pa++)."</span>. ";
							
							echo $pantl[0]; //phân trả lời
							if ($pantl[1]>0) echo " .";
							echo "</div>";
							 
						}
					}
					echo "</div>";
					echo "</div>";
				}
			}
			
		}
		else { //khong co bai thi
			echo "Không có bài làm kiểm tra";
		}
	}
	
	

echo "</body></html>";

function layCauhoi($macauhoi=0) {
	$retu = mysql_query("select noidung from tn_cauhoi where maso=$macauhoi");
	if ($retu) {
		if ($row=mysql_fetch_array($retu)) {
			return $row['noidung'];
		} else {
			return "";
		}
	} else {
		return "";
	}
}
function layTraloi($matraloi=0) {
	$retu = mysql_query("select noidung,dung from tn_traloi where maso=$matraloi");
	if ($retu) {
		if ($row=mysql_fetch_array($retu)) {
			return $row;
		} else {
			return ["",0];
		}
	} else {
		return ["",0];
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
