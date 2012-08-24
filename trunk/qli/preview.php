<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Trắc nghiệm</title>";

echo "</head><body>";
echo "LÀM BÀI KIỂM TRA TRỰC TUYẾN<hr/>";
session_start();

	if (isset($_GET['id'])) { //da chon ki thi
		$bai = intval($_GET['id']);
		$sql = "select * from baikiemtra where id=$bai";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) { //co ki thi
			$dapan="";
			$data = mysql_fetch_array($result);
			echo $data['tenbai']."<hr/>";
			$socau = $data['socau'];
			$sql = "select * from cauhoi where bai=$bai order by rand() limit $socau";
			$result = mysql_query($sql);
			$ch=1;
			echo "<div id='baikiemtra' >";
			while ($data = mysql_fetch_array($result)) {
				echo "<div class='cau' >";
				echo "<br/><div class='cauhoi'>";
				echo ($ch++).". ".$data['hoi']."<br/>";
				echo "</div>";
				echo "<div class='traloi'>";
				$sql = "select * from traloi where cauhoi=".$data['id']." order by rand()";
				$result2 = mysql_query($sql) or die(mysql_error());
				$pa=1;
				while ($data2 = mysql_fetch_array($result2)) {
					echo "<div class='phuongan'><span>";
					echo chr(64+$pa++)."</span>. ";
					echo $data2['phuongan'];
					echo "</div>";
					if ($data2['diem']>0) if (rand()>10000) $dapan=chr($ch+63).$pa.$dapan; else $dapan.=chr($ch+63).$pa;
				}
				echo "</div>";
				echo "</div>";
			}
			
		}
		else { //khong co ki thi
			echo "Không có bài kiểm tra";
		}
	}
	else { //chua chon ki thi
		$sql = "select * from baikiemtra where kichhoat=1";
		$result = mysql_query($sql);
		echo "<table border='1'>";
		echo "<tr><td>STT</td><td>Tên bài kiểm tra</td><td>Số câu hỏi</td><td>Thời gian</td></tr>";
		$i=1;
		while ($data=mysql_fetch_array($result)) {
			//echo "<a href='?id=".$data['id']."'>".$data['tenbai']."</a><br/>";
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td><a href='?id=".$data['id']."'>".$data['tenbai']."</a></td>\n";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".intval($data['thoigian']/60)." phút"."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	

echo "</body></html>";

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
