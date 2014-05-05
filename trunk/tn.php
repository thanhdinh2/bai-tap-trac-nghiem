<?php

require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Trắc nghiệm</title>";
?>
<script src="jquery.min.js" type="text/javascript"></script>
<script language='javascript'>
	window.onbeforeunload = function() {
		//alert("good bye");
		if (typeof t == "number") {
			if (t>0) {
				$.post("caigio.php",{gio:t});
				alert("Bạn vẫn còn "+t+" giây");
			}
		}
	}
	var hoi = new Array();
	var cactraloi=new Array();
	var c = 0;
	var cau ;
	var pha= "";
	var myTimer = null;
	//var t = 300;//giay
	$(document).ready(function(){
		if (typeof sc !== 'undefined') {
			$(".cau:eq(0)").show();
			for (var i=1;i<=sc;i++) {
				$("#cauhoitraloi").append("<span class='cautraloi' id='cautraloi"+i+"'>"+i+":"+"*</span> ");
				hoi[i-1]=0;
			}
		}
		$(".phuongan").css("cursor","pointer");
		$(".phuongan").mouseenter(function(){
			$(this).css("background-color","#afff00");
		});
		$(".phuongan").mouseleave(function(){
			$(this).css("background-color","white");
		});
		$(".phuongan").click(function(){
			$(this).parent().find(".phuongan").css('color', 'black');
			$(this).css('color', 'red');
			var chon=$(this).find("span:eq(0)").text();
			$(this).parent().parent().find("div.chon:eq(0)").text("Bạn chọn phương án "+chon+".");
			$("#cautraloi"+(c+1)).text((c+1)+":"+chon);
			hoi[c]=1;
			cactraloi[c]=$(this).attr("id").substr(2);
			$("#cautraloi"+(c+1)).addClass("dachon");
			if ($(".dachon").length==sc) {
				$("#nopbai").show();
			}
			//alert(c+chon);
			cau = (c>0?cau.substr(0,c):"")+chon+cau.substr(c+1);
			pha+=$(this).attr("id");
			if ($("#tuchuyen").is(':checked'))
				$("#causau").click();
		});
		$(".cautraloi").css("cursor","pointer");
		$(".cautraloi").click(function(){
			//alert($(this).text());
			var cid = $(this).attr("id");
			cid=cid.replace("cautraloi","");
			//alert(cid);
			$(".cau:eq("+c+")").hide();
			c=parseInt(cid)-1;
			if ($(".cau:eq("+c+")").length<=0) c=0;
			$(".cau:eq("+c+")").show();
		});
		$("#causau").click(function(){
			$(".cau:eq("+c+")").hide();
			c++;
			if ($(".cau:eq("+c+")").length<=0) c=0;
			$(".cau:eq("+c+")").show();
		});
		$("#cautruoc").click(function(){
			$(".cau:eq("+c+")").hide();
			c--;
			if ($(".cau:eq("+c+")").length<=0) c=$(".cau").length-1;
			$(".cau:eq("+c+")").show();
		});
		$("#batdau").click(function(){
			//alert("OK");
			cau = Array(sc).join("-");
			$("#baikiemtra").show();
			$(this).parent().hide();
			$.post("batdau.php");
			tinhgio();
			//$.post('batdau.php',{});
		});
		$("#nopbai").hide();
		$("#nopbai").click(function(){
			//nopbai();
			if (confirm("Bạn chắc chắn muốn nộp bài?\nBài làm sẽ được chấm điểm ngay và không thể sửa trả lời được nữa.")) {
				t=0;
				nopbai();
			}
			//t=0;
		});
		if (typeof lamtudau != "undefined") {
			if (!lamtudau) tinhgio();
		}
	});
	function tinhgio() {
		t--;
		if (t>0) {
			$("#thoigian").text(~~(t/60) + ":" + (t%60<10?"0":"")+t%60);
			myTimer = window.setTimeout(tinhgio,1000);
			if (t==59) $("#thoigian").addClass("maudo");
		}
		else {
			$("#thoigian").text("00:00");
			nopbai();
		}
	}
	function nopbai() {
		//alert(cau);
		$("#baikiemtra").hide();
		$.post("caigio.php");
		//delete t;
		
		var ph="";
		var scd = 0;
		for (var i=0; i<sc; i++) {
			ph = String.fromCharCode(i+65)+""+(cau.charCodeAt(i)-63);
			if (dap.indexOf(ph)>=0) scd++;
		}
		//alert(scd);
		//alert(pha);
		pha=cactraloi.join(",");
		t=0;
		window.location.href+="&kq="+scd+"&fb="+pha;
	}
</script>
<link rel=stylesheet href='mystyle.css' type='text/css'>
<?php
echo "</head><body>";

echo "<div id='tieude'><span>KIỂM TRA TRỰC TUYẾN</span> <span>...</span></div>";
session_start();
if (isset($_SESSION['ten'])) { //da dang nhap
	//echo $_SESSION['ten']."<br/>";
	if (isset($_SESSION['ketqua'])) {
		echo "KẾT QUẢ LÀM BÀI <HR/>";
		echo "<center>";
		echo "<table border='0'>";
		echo "<tr class='chamcham'><td>Họ và tên:</td><td>".$_SESSION['ten']."</td></tr>";
		echo "<tr class='chamcham'><td>Lớp:</td><td>".$_SESSION['lop']."</td></tr>";
		echo "<tr class='chamcham'><td>Tổng số câu làm đúng:</td><td><b>".$_SESSION['ketqua']."</b> (".round(($_SESSION['ketqua']/$_SESSION['socau'])*10,1)." điểm)</td></tr>";
		echo "</table><br/></center>";
		//redirect("thoat.php",20);
	}
	else if (isset($_GET['kq'])) {
		$_SESSION['ketqua']=$_GET['kq'];
		unset($_SESSION['gio']);
		//$sql="insert into nopbaikiemtra (hoten,lop,ngaygio,ip,ketqua,baikt) value ('".$_SESSION['ten']."','".$_SESSION['lop']."',NOW(),'".$_SERVER['REMOTE_ADDR']."',".$_SESSION['ketqua'].",'".(isset($_GET['id'])?$_GET['id']:0)."')";
		$sql="update nopbaikiemtra set ngaygio=NOW(), ketqua=".$_SESSION['ketqua'].", baikt=".(isset($_GET['id'])?$_GET['id']:0)." where id=".$_SESSION['maso'];
		//echo $sql;
		$result = mysql_query($sql) or die (mysql_error());
		$fb = $_GET['fb']."";
		//$fb = str_replace("pa",",",$fb);
		$sql="update traloi set chon=chon+1 where id in (".$fb.")";
		echo $fb;
		$result = mysql_query($sql) or die (mysql_error());
		mysql_query("update nopbaikiemtra set cactraloi='$fb' where id=".$_SESSION['maso']);
		redirect("?",1);
	}
	else
	if (isset($_GET['id'])) { //da chon ki thi
		
		$bai = intval($_GET['id']);
		$sql = "select * from baikiemtra where kichhoat=1 and id=$bai";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)) { //co ki thi
			$dapan="";
			$data = mysql_fetch_array($result);
			echo "<table width=100%><tr><td width=50%>";
			echo "<div>".$data['tenbai']."</div>";
			echo "<div>Số câu: ".$data['socau']. "</div>";
			echo "<div>Thời gian: ".($data['thoigian']<600?"0":"").intval($data['thoigian']/60).":".(($data['thoigian'] % 60)<10?"0":"").($data['thoigian'] % 60)."</div>";
			echo "<div>Mã đề KT: <span class='maudo'>".$_SESSION['maso']."</span></div>";
			echo "</td><td width=50%>";
			echo "<div> Họ tên: ".$_SESSION['ten']."</div><div>Lớp: ".$_SESSION['lop']."</div>";
			echo "<div>Thời gian còn: <span id='thoigian'>--:--</span></div>";
			echo "</td></tr></table><hr/>";
			echo "<table id='khuvucthi' width=100%><tr><td><button id='nopbai'>Nộp bài</button></td>";
			echo "<td><div id='cauhoitraloi'>Câu trả lời: </div></td>";
			echo "<td><button id='cautruoc'>Câu trước</button> <button id='causau'>Câu sau</button>";
			echo "<input type='checkbox' id='tuchuyen'/>Tự chuyển câu hỏi</td></tr></table>";
			$tieude= $data['tieude']."<br/>";
			$socau = $data['socau'];
			$_SESSION['socau']=$socau;
			$_SESSION['baikt']=$tieude;
			$thoigian=$data['thoigian'];
			$lamtudau = true;
			if (isset($_SESSION['gio'])) {
				$thoigian = $_SESSION['gio'];
				$lamtudau = false;
			}
			echo "<script language='javascript'>var t = ".$thoigian."+1; var sc = ".$socau."; var lamtudau=".($lamtudau?1:0).";</script>";
			$caccauhoi="";
			$dsloai=",";
			$sql = "select * from cauhoi where dung=1 and bai=$bai order by rand()";// limit $socau
			$result = mysql_query($sql);
			$ch=1;
			echo "<hr/><div id='baikiemtra' style='display:".($lamtudau?"none":"block").";'>";
			while (($data = mysql_fetch_array($result)) && ($ch<=$socau)) {
				if (strpos($dsloai,",".$data['id'].",") === false) {
					echo "<div class='cau' style='display:none;'>";
					echo "<div class='cauhoi'>";
					echo ($ch++).". ".$data['hoi']."<br/>";
					echo "</div>";
					$caccauhoi.="|".$data['id'].":";
					echo "<div class='traloi'>";
					$sql = "select * from traloi where cauhoi=".$data['id']." order by rand()";
					$result2 = mysql_query($sql) or die(mysql_error());
					$pa=1;
					while ($data2 = mysql_fetch_array($result2)) {
						$caccauhoi.=$data2['id']." ";
						echo "<div class='phuongan' id='pa".$data2['id']."'><span>";
						echo chr(64+$pa++)."</span>. ";
						echo $data2['phuongan'];
						echo "</div>";
						if ($data2['diem']>0) if (rand()>10000) $dapan=chr($ch+63).$pa.$dapan; else $dapan.=chr($ch+63).$pa;
					}
					echo "</div>";
					$dsloai.=dsloaitru($data['id']);
					echo "<div class='chon'>Bạn chưa chọn câu trả lời</div>";
					//echo "<div><span class</div>";
					echo "</div>";
				}
			}
			//echo "<hr/>";
			echo "</div><div align='center' style='display:".($lamtudau?"block":"none").";'>".$tieude."<button id='batdau'>Bắt đầu làm bài</button></div>";
			echo "<script language='javascript'>var dap = '".$dapan."';</script>";
			mysql_query("update nopbaikiemtra set caccauhoi='$caccauhoi' where id=".$_SESSION['maso']) or die(mysql_error());
		}
		else { //khong co ki thi
			echo "Không có bài kiểm tra hoặc đã hết hạn";
		}
	}
	else { //chua chon ki thi
		$sql = "select * from baikiemtra where kichhoat=1";
		$result = mysql_query($sql);
		$fid=0;
		$cid=-1;
		echo "<h2>Chọn một bài kiểm tra sau đây để làm</h2>";
		echo "<table border='1'>";
		echo "<tr><td>STT</td><td>Tên bài kiểm tra</td><td>Số câu hỏi</td><td>Thời gian</td></tr>";
		$i=1;
		while ($data=mysql_fetch_array($result)) {
			//echo "<a href='?id=".$data['id']."'>".$data['tenbai']."</a><br/>";
			if ($fid==0) $fid = $data['id'];
			$cid = $data['id'];
			echo "<tr align='center'>";
			echo "<td>".$i++."</td>";
			echo "<td align='left'><a href='?id=".$data['id']."'>".$data['tenbai']."</a></td>\n";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".intval($data['thoigian']/60)." phút"."</td>";
			echo "</tr>";
		}
		echo "</table>";
		if ($fid==$cid) redirect("?id=".$fid);
	}
	echo "<hr/><a href='thoat.php'>Thoát</a><br/><br/>";
	echo $_SESSION['ten']." - ".$_SESSION['lop']."<br/>";
} 
else { //chua dang nhap
	if (isset($_POST['dangnhap'])) {
		if (!isset($_POST['ten'])) {
			echo "Vui lòng ghi họ tên.";
		}
		if (strlen($_POST['ten'])<5) {
			echo "Tên quá ngắn.";
		}
		elseif (strlen($_POST['lop'])<4) {
			echo "Chưa nhập lớp.";
		}
		else {
			$_SESSION['ten']=$_POST['ten'];
			$_SESSION['lop']=$_POST['lop'];
			$sql="insert into nopbaikiemtra (hoten,lop,dangnhap,ip) value ('".$_SESSION['ten']."','".$_SESSION['lop']."',NOW(),'".$_SERVER['REMOTE_ADDR']."')";
			$result = mysql_query($sql) or die (mysql_error());
			$_SESSION['maso']=mysql_insert_id();
			echo "Đăng nhập thành công";
		}
		redirect("?",1);
	}
	else {
		//echo "<br/>LÀM BÀI KIỂM TRA TRỰC TUYẾN<HR/>";
		echo "<center>";
		echo "<form action='?'".(isset($_GET['id'])?"id=".$_GET['id']:"")." method='post'>";
		echo "Họ và tên: <input type='text' name='ten' size='40'><br/><br/>";
		echo "Lớp: <input type='text' name='lop' size='30'><br/><br/>";
		echo "<input type='submit' name='dangnhap' value='Đăng nhập'><br/>";
		echo "</form>";
	}
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
		
		echo "File: <input name='nfile' type='file'  id='nfile'><br/>";
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
		echo "<table>";
		echo "<tr><td>STT</td><td>Tên bài kiểm tra</td><td>Số câu hỏi</td><td>Thời gian</td></tr>";
		$i=1;
		while ($data = mysql_fetch_array($result)){
			echo "<tr>";
			echo "<td>".$i++."</td>";
			echo "<td><a href='?act=view&id=".$data['id']."'>".$data['id'].". ".$data['ten']."</a></td>\n";
			echo "<td>".$data['socau']."</td>";
			echo "<td>".int($data['thoigian']/60).":".mod($data['thoigian'],60)."</td>";
			echo "</tr>";
			//echo "OK";
		}
		echo "</table>";
	} else {
		echo "Không có bài kiểm tra nào";
	}
}
function dsloaitru($chocau) {
	$sql="select * from cauhoiloaitru where cau1='".$chocau."' or cau2='".$chocau."'";
	$ret=mysql_query($sql) or die(mysql_error());
	$ds="";
	if (mysql_num_rows($ret)) { //co loai tru
		while ($cau=mysql_fetch_array($ret)) {
			if ($cau['cau1']==$chocau) {
				$ds.=$cau['cau2'].", ";
			} else {
				$ds.=$cau['cau1'].", ";
			}
		}
		echo $ds;
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
