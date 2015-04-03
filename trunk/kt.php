<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Đăng nhập đơn giản và làm bài trắc nghiệm cho thí sinh
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
include_once("thamso.php");
include_once("bbcode.php");
//define("DEBUG",TRUE);
echo "<html><head><title>Trắc nghiệm</title>";

echo '<script src="jquery.min.js" type="text/javascript"></script>';
echo '<script language="javascript" src="kt.js"></script>';
echo "<link rel=stylesheet href='mystyle.css' type='text/css'>";
echo "</head><body>";

echo "<div id='tieude'><span>KIỂM TRA TRẮC NGHIỆM</span> <span>. $thithu</span></div>";
session_start();
if ($thithu>1) {
	$_SESSION['ten']='thi thử $thithu';
	$_SESSION['lop']='-----';
}
if (isset($_SESSION['ten'])||($thithu>1)) { //da dang nhap
	//echo $_SESSION['ten']."<br/>";
	if (isset($_SESSION['ketqua'])) {
		echo "KẾT QUẢ LÀM BÀI <br/>";
		echo $_SESSION['tenbai']."<hr/>";
		echo "<div id='bangketqua'><center>";
		echo "<table border='0'>";
		echo "<tr class='chamcham'><td>Họ và tên:</td><td>".$_SESSION['ten']."</td></tr>";
		echo "<tr class='chamcham'><td>Lớp:</td><td>".$_SESSION['lop']."</td></tr>";
		echo "<tr class='chamcham'><td>Mã đề:</td><td class='mauxanh'>".(isset($_SESSION['maso'])?$_SESSION['maso']:"0")."</td></tr>";
		echo "<tr class='chamcham'><td>Số câu đúng:</td><td><span class='maudo'><b>".$_SESSION['ketqua']."</b></span> <i>(".round(($_SESSION['ketqua']/$_SESSION['socau'])*10,1)." điểm)</i></td></tr>";
		echo "</table><br/></center></div>";
		if (!$thithu) redirect("thoat.php",20);
		echo "<hr/><a href='thoat.php'>Thoát</a><br/>";
		echo "<hr/><font size='1'>Written by Tran Huu Nam - thnam@thptnguyendu.edu.vn - 2014</font><br/>";
	}
	else if (isset($_GET['kq'])) {
		$_SESSION['ketqua']=$_GET['kq'];
		$fb = $_GET['fb']."";
		unset($_SESSION['gio']);
		//$sql="insert into nopbaikiemtra (hoten,lop,ngaygio,ip,ketqua,baikt) value ('".$_SESSION['ten']."','".$_SESSION['lop']."',NOW(),'".$_SERVER['REMOTE_ADDR']."',".$_SESSION['ketqua'].",'".(isset($_GET['id'])?$_GET['id']:0)."')";
		$sql="update tn_ketqua set lucnop=NOW(), caudung=".$_SESSION['ketqua'].", traloi='$fb', bai=".(isset($_GET['id'])?$_GET['id']:0)." where id=".$_SESSION['maso'];
		//echo $sql;
		if ($thithu==0) $result = mysql_query($sql);
		$sql="update tn_traloi set luotchon=luotchon+1 where maso in (".$fb.")";
		if ($thithu==0) $result = mysql_query($sql);
		redirect("kt.php",1);
	}
	else
	if (isset($_GET['id'])) { //da chon ki thi
		if (isset($_SESSION['mabai'])) {  //dang lam do dang
			if (!isset($_SESSION['maso'])) redirect("thoat.php");
			echo "<div class='maudo' id='canhbao'>Mỗi lần tải lại (refresh) trang web, bạn bị mất hết các phương án đã chọn và bị trừ thêm 5 giây. Lặp lại lần nữa, bạn sẽ nhận điểm 0.</div>";
			$bai = intval($_SESSION['mabai']);
			$sql = "select * from tn_kiemtra where kichhoat=1 and maso=$bai";
			$result=mysql_query($sql);
			if (mysql_num_rows($result)) { //co ki thi
				$dapan="";
				$data = mysql_fetch_array($result);
				echo "<div id='thongtin'><table width=100% id='bangthongtin'><tr><td width=50%>";
				echo "<div>".$data['tenbai']."</div>";
				echo "<div>Số câu: ".$data['socau']. "</div>";
				echo "<div>Thời gian: ".($data['sogiay']<600?"0":"").intval($data['sogiay']/60).":".(($data['sogiay'] % 60)<10?"0":"").($data['sogiay'] % 60)."</div>";
				echo "<div>Mã đề KT: <span class='maudo'>".$_SESSION['maso']."</span></div>";
				echo "</td><td width=50%>";
				echo "<div> Họ tên: ".$_SESSION['ten']."</div><div>Lớp: ".$_SESSION['lop']."</div>";
				echo "<div>Thời gian còn: <span id='thoigian'>--:--</span></div>";
				echo "</td></tr></table></div>";
				echo "<table id='khuvucthi' width=100%><tr><td><button id='nopbai'>Nộp bài</button></td>";
				echo "<td><div id='cauhoitraloi'>Câu trả lời: </div></td>";
				echo "<td><button id='cautruoc'>Câu trước</button> <button id='causau'>Câu sau</button>";
				echo "<input type='checkbox' checked id='tuchuyen'/>Tự chuyển câu hỏi</td></tr></table>";
				$tieude= $data['tenbai']."<br/>";
				$socau = $data['socau'];
				$_SESSION['mabai']=$bai;
				//$_SESSION['socau']=$socau;
				$_SESSION['baikt']=$tieude;
				$_SESSION['tenbai']=$data['tenbai'];
				$thoigian=$data['sogiay'];
				$lamtudau = false;
				if (isset($_SESSION['gio'])) {
					$_SESSION['gio']-=5;
					$thoigian = $thoigian + $_SESSION['gio'] - time(); //trừ 5 giây phạt
					//$lamtudau = false;
				}
				$dekiemtra = layCaccauhoi($_SESSION['maso']);
				//echo $_SESSION['maso'].$dekiemtra;
				$cauhoikt = explode("|",$dekiemtra);
				$ch=1;
				echo "<hr/><div id='baikiemtra' style='display:none;'>";
				foreach ($cauhoikt as $cauhoirieng) { //cauhoirieng = 1 cau hoi có dạng mã câu hỏi: các câu trả lời
					if (strlen($cauhoirieng)>0) {
						$hoidap=explode(":",$cauhoirieng); //tach phan cau hoi va cac cau tra loi
						$cautraloi = explode(",",$hoidap[1]);
						echo "<div class='cau' style='display:none;'>";
						echo "<div class='cauhoi'>";
						echo "<div class='bu'><b><u>Câu ".($ch++).".</u></b></div><div> ".showBBcodes(layCauhoi($hoidap[0]))."</div>";
						echo "</div>";
						//$caccauhoi.="|".$data['id'].":";
						echo "<div class='traloi'>";
						$pa=1;
						foreach ($cautraloi as $tloi) {
							if (strlen($tloi)>0) {
								echo "<div class='phuongan ".((($thithu>=3)&&($pantl[1]>0))?"padung":"")."' id='pa".$tloi."'><span>";
								echo chr(64+$pa++)."</span>. ";
								$pantl=layTraloi($tloi);
								echo showBBcodes($pantl[0]); //phân trả lời
								echo "</div>";
								if ($pantl[1]>0) if (rand()>10000) $dapan=chr($ch+63).$pa.$dapan; else $dapan.=chr($ch+63).$pa; //phan dap an
							}
						}
						echo "</div>";
						echo "<div class='chon'>Bạn chưa chọn câu trả lời</div>";
						echo "</div>";
					}
				}
				if ($socau>=$ch) $socau=$ch-1; //truong hop khong du so cau hoi
				echo "</div><div align='center' style='margin:20px;'>".$tieude."<button id='batdau'>Tiếp tục làm bài</button></div>";
				echo "<script language='javascript'>var dap = '".$dapan."';var t = ".$thoigian."+1; var sc = ".$socau."; var lamtudau=".($lamtudau?1:0).";</script>";
			}
			else { //khong co ki thi
				echo "Không có bài kiểm tra hoặc đã hết hạn";
			}
		} else { // moi vao thi
			$bai = intval($_GET['id']);
			$sql = "select * from tn_kiemtra where kichhoat=1 and maso=$bai";
			$result=mysql_query($sql) or die(mysql_error().$sql);
			if (mysql_num_rows($result)) { //co ki thi
				$dapan="";
				$data = mysql_fetch_array($result);
				echo "<div id='thongtin'><table width=100%><tr><td width=50%>";
				echo "<div>".$data['tenbai']."</div>";
				echo "<div>Số câu: ".$data['socau']. "</div>";
				echo "<div>Thời gian: ".($data['sogiay']<600?"0":"").intval($data['sogiay']/60).":".(($data['sogiay'] % 60)<10?"0":"").($data['sogiay'] % 60)."</div>";
				echo "<div>Mã đề KT: <span class='maudo' id='madekiemtra'></span></div>";
				echo "</td><td width=50%>";
				echo "<div> Họ tên: ".$_SESSION['ten']."</div><div>Lớp: ".$_SESSION['lop']."</div>";
				echo "<div>Thời gian còn: <span id='thoigian'>--:--</span></div>";
				echo "</td></tr></table></div>";
				echo "<table id='khuvucthi' width=100% style='display:none;'><tr><td><button id='nopbai'>Nộp bài</button></td>";
				echo "<td><div id='cauhoitraloi'>Câu trả lời: </div></td>";
				echo "<td><button id='cautruoc'>Câu trước</button> <button id='causau'>Câu sau</button>";
				echo "<input type='checkbox'  id='tuchuyen' checked/>Tự chuyển câu hỏi</td></tr></table>";
				$tieude= $data['tenbai']."<br/>";
				$socau = $data['socau'];
				$_SESSION['mabai']=$bai;
				
				$_SESSION['baikt']=$tieude;
				$_SESSION['tenbai']=$data['tenbai'];
				$thoigian=$data['sogiay'];
				$lamtudau = true;
				
				$caccauhoi="";
				$dsloai=",";
				$danhsachcauhoi= laydscauhoi($data['lop'],$data['chuong'],$data['bai'],$data['cau']);
				$sql = "select * from tn_cauhoi where kichhoat=1 and maso in (".$danhsachcauhoi.") order by rand()";
				$result = mysql_query($sql);
				$ch=1;
				echo "<hr/><div id='baikiemtra' style='display:".($lamtudau?"none":"block").";'>";
				while (($data = mysql_fetch_array($result)) && ($ch<=$socau)) { //duyet qua cac cau hoi
					if (strpos($dsloai,",".$data['maso'].",") === false) {
						echo "<div class='cau' style='display:none;'>";
						echo "<div class='cauhoi'>";
						echo "<div class='bu'><b><u>Câu ".($ch++).".</u></b></div><div> ".showBBcodes($data['noidung'])."</div>";
						echo "</div>";
						$caccauhoi.="|".$data['maso'].":";
						echo "<div class='traloi'>";
						$sql = "select * from tn_traloi where cauhoi=".$data['maso']." order by thutu+rand()";
						$result2 = mysql_query($sql) or die(mysql_error());
						$pa=1;
						while ($data2 = mysql_fetch_array($result2)) { //duyet cac cau tra loi
							$caccauhoi.=$data2['maso'].",";
							echo "<div class='phuongan ".((($thithu>=3)&&($data2['dung']>0))?"padung":"")."' id='pa".$data2['maso']."'><span>";
							echo chr(64+$pa++)."</span>. ";
							echo showBBcodes($data2['noidung']);
							echo "</div>";
							if ($data2['dung']>0) if (rand()>10000) $dapan=chr($ch+63).$pa.$dapan; else $dapan.=chr($ch+63).$pa;
						}
						echo "</div>";
						$dsloai.=dsloaitru($data['maso']);
						echo "<div class='chon'>Bạn chưa chọn câu trả lời</div>";
						//echo "<div><span class</div>";
						echo "</div>";
					}
				}
				if ($socau>=$ch) $socau=$ch-1; //truong hop khong du so cau hoi
				//echo "<hr/>";
				echo "</div><div align='center' style='display:block;margin: 20px;".($lamtudau?"block":"none").";'>".$tieude."<button id='batdau'>Bắt đầu làm bài</button></div>";
				echo "<script language='javascript'>var dap = '".$dapan."';var thithu=".$thithu."; var t = ".$thoigian."+1; var sc = ".$socau."; var lamtudau=".($lamtudau?1:0).";</script>";
				//echo "<p>$sql</p>";
				if ($thithu==0) {
					$sql = "INSERT INTO `tn_ketqua`(`bai`, `hoten`, `lop`, `cauhoi`,  `lucvao`, `ip`) VALUES ('$bai','".$_SESSION['ten']."','".$_SESSION['lop']."','$caccauhoi','".$_SESSION['dangnhap']."','".$_SERVER['REMOTE_ADDR']."')";
					mysql_query($sql) or die(mysql_error());
					$_SESSION['maso']=mysql_insert_id();
					echo "<script>document.getElementById('madekiemtra').innerHTML='".$_SESSION['maso']."';</script>";
				}
				$_SESSION['socau']=$socau;
			}
			else { //khong co ki thi
				echo "<p>Không có bài kiểm tra hoặc đã hết hạn</p>";
			}
		}
	}
	else { //chua chon ki thi
		$sql = "select * from tn_kiemtra where kichhoat=1 ".($thithu?"":"and lop = ".intval($_SESSION['lop']));
		$result = mysql_query($sql);
		$fid=0;
		$cid=-1;
		echo "<h2>Chọn một bài kiểm tra sau đây để làm</h2>";
		echo "<table class='don'>";
		echo "<tr><td>Mã số</td><td>Tên bài kiểm tra</td><td>Lớp</td><td>Số câu hỏi</td><td>Thời gian</td></tr>";
		
		while ($data=mysql_fetch_array($result)) {
			if ($fid==0) $fid = $data['maso'];
			$cid = $data['maso'];
			echo "<tr align='center'>";
			echo "<td>".$cid."</td>";
			echo "<td align='left'><a href='?id=".$data['maso']."'>".$data['tenbai']."</a></td>\n";
			echo "<td>".$data['lop']."</td>";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".intval($data['sogiay']/60)." phút"."</td>";
			echo "</tr>";
		}
		echo "</table>";
		if ($fid==$cid) redirect("?id=".$fid);
	}
	echo "<hr/>";
	if ($thithu==0) echo $_SESSION['ten']." - ".$_SESSION['lop']."<br/>";
	//echo $_SESSION['dangnhap'];
} 
else { //chua dang nhap
	if (isset($_POST['dangnhap'])) {
		if (!isset($_POST['ten'])) {
			echo "Vui lòng ghi họ tên.";
		}
		if (strlen($_POST['ten'])<5) {
			echo "Tên quá ngắn.";
		}
		elseif (strlen($_POST['lop'])<3) {
			echo "Chưa nhập lớp.";
		}
		else {
			$_SESSION['ten']=$_POST['ten'];
			$_SESSION['lop']=$_POST['lop'];
			$_SESSION['dangnhap']=date("Y-m-d H:i:s");
			echo "Đăng nhập thành công";
		}
		redirect("?".(isset($_GET['id'])?"id=".$_GET['id']:""),1);
	}
	else {
		//echo "<br/>LÀM BÀI KIỂM TRA TRỰC TUYẾN<HR/>";
		echo "<div id='bieumaudangnhap'><center><br><br>";
		echo "<form action='?'".(isset($_GET['id'])?"id=".$_GET['id']:"")." method='post'>";
		echo "Họ và tên: <input type='text' name='ten' size='40' id='hovaten'><br/><br/>";
		echo "<script>document.getElementById('hovaten').focus();</script>";
		echo "Lớp: <input type='text' name='lop' size='10' value='".(isset($_SESSION['lop'])?$_SESSION['lop']:"")."'><br/><br/>";
		echo "<input type='submit' name='dangnhap' value='Đăng nhập'><br/>";
		echo "</form></center></div>";
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
function layCaccauhoi($madekt=0) {
	$retu = mysql_query("select * from tn_ketqua where id=$madekt");
	if ($retu) {
		if ($row=mysql_fetch_array($retu)) {
			return $row['cauhoi'];
		} else {
			return "";
		}
	} else {
		return "";
	}
}
function laydscauhoi($lop,$chuong,$bai,$cau) {
	$sql="(lop=$lop)";
	if ($chuong) $sql.= " and (chuong in ($chuong))";
	if ($bai) $sql.= " and (bai in ($bai))";
	if ($cau) $sql.= " or (maso in ($cau))";
	$sql="select maso from tn_cauhoi where ".$sql;
	$result = mysql_query($sql) or die (mysql_error());
	$cauhoi="";
	while ($data=mysql_fetch_array($result)) {
		$cauhoi .= $data['maso'].",";
	}
	return rtrim($cauhoi,",");
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
		
		echo "File: <input name='nfile' type='file'  id='nfile'><br/>";
		echo "<input name='baitap' type='hidden' value='$bai'><br/>";
		echo "<input name='nop' type='submit' value='Nộp bài'><br/>";
		echo "</form>";
	} else {
		echo "Không có bài này";
	}
}



function dsloaitru($chocau) {
	$sql="select * from tn_loaitru where cau1='".$chocau."' or cau2='".$chocau."'";
	$ret=mysql_query($sql) or die(mysql_error());
	$ds="";
	if (mysql_num_rows($ret)) { //co loai tru
		while ($cau=mysql_fetch_array($ret)) {
			if ($cau['cau1']==$chocau) {
				$ds.=$cau['cau2'].",";
			} else {
				$ds.=$cau['cau1'].",";
			}
		}
		//echo $ds;
	} 
	return $ds;
}
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}

?>
