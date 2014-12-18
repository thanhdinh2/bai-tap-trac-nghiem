<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Quản lí các câu hỏi: thêm/xoá/sửa
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
?>
<html>
<head><title>Quản lí câu hỏi</title>
<!--<script src='ckeditor/ckeditor.js'></script>--><link rel='stylesheet' type='text/css' href='mystyles.css' media='screen' />
<script src='jquery.min.js'></script>
<script>
jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      var sel = document.selection.createRange();
      sel.text = myValue + sel.text + (myValue!='[br]'?myValue.replace("[","[/"):"");
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+ this.value.substring(startPos,endPos) + (myValue!='[br]'?myValue.replace("[","[/"):"")+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = endPos + 2*myValue.length;
      this.selectionEnd = endPos + 2*myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  });
}
});
$(document).ready(function(){
	$(".bbcode").click(function(){
		//$("#cauhoichinh").html($(this).text());
		//$("#cauhoichinh").val($("#cauhoichinh").val()+$(this).text()+$(this).text().replace("[","[/"));
		$("#cauhoichinh").insertAtCaret($(this).text());
	});
});
</script>

<?php
echo "</head><body>";
$bbcodes=["b","i","u","sub","sup","code","pre","color","img","url","br","size"];
$khoic = isset($_GET['khoi']) ? $_GET['khoi'] : "";
$baic = isset($_GET['bai']) ? $_GET['bai'] : "";
$chuongc = isset($_GET['chuong']) ? $_GET['chuong'] : "";
$huong = ($khoic?"&khoi=$khoic":"").($chuongc?"&chuong=$chuongc":"").($baic?"&bai=$baic":"");
if (!isset($_GET['act'])) {
	$act = "view";
}
else
	$act = $_GET['act'];
	if ($act=="add") { //them cau hoi
		echo "<div id='tieude'>THÊM CÂU HỎI </div>";
		if (isset($_POST['addnew'])) { //add
			$tieude = mysql_real_escape_string($_POST['cauhoi']);
			$loaitru=$_POST['loaitru'];
			$sudung=(isset($_POST['sudung'])?"1":"0");
			$khoi = $_POST['khoilop'];
			$chuong = $_POST['chuong'];
			$bai = $_POST['bai'];
			$kieu = $_POST['kieu'];
			$dokho = $_POST['dokho'];
			$sql= "insert into tn_cauhoi (lop,chuong,bai,dokho,kieu,noidung,kichhoat,ngaylam) value ('$khoi','$chuong','$bai','$dokho','$kieu','$tieude','$sudung',NOW())";
			if (defined("DEBUG")) echo $sql."<br/>";
			$result = mysql_query($sql) or die (mysql_error());
			if ($result) {
				echo "THÀNH CÔNG";
				$idmoi=mysql_insert_id();
				if ($loaitru) {
					$caus = explode(",", $loaitru);
					foreach ($caus as $cau) {
						if ($cau)
							themloaitru($idmoi,$cau);
					}
				}
				redirect("?act=view&id=$idmoi",1);
			} else {
				echo "THẤT BẠI";
				redirect("?x$huong",2);
			}
			
			//echo $tieude.$muc;
		} 
		else { //input
			echo "<div><form action='?act=add' method='post'>";
			echo "Nội dung câu hỏi <br/><textarea cols='80' id='cauhoichinh' class='ckeditor' name='cauhoi' rows='4'></textarea><br/>";
			
			for ($i=0; $i<count($bbcodes);$i++) {
				echo "<span class='bbcode'>[".$bbcodes[$i]."]</span>";
			}
			echo "<p>Loại trừ với các câu hỏi: <input type='text' name='loaitru' /></p>";
			echo "<p>Sử dụng? <input type='checkbox' checked name='sudung' /></p>";
			echo "<p>Khối lớp: <input type='text' name='khoilop' size='2' value='$khoic'/> . . Chương: <input type='text' name='chuong' size='2' value='$chuongc'/>... Bài: <input type='text' name='bai' size='2' value='$baic'/></p>";
			echo "<p>Kiểu: <select name='kieu'><option value=1>Trắc nghiệm 1 lựa chọn</option><option value=2>Điền vào chỗ trống</option></select>. ";
			echo "Độ khó: <select name='dokho'><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>. </p>";
			echo "<p><input type ='submit' name='addnew' value='Thêm vào'></p>";
			echo "</form></div>";
		}
	} 
	else if ($act=="edit") { //sua cau hoi
		if (!isset($_GET['id'])) {
			redirect("?");
		}
		$cau = intval($_GET['id']);
		echo "SỬA CÂU HỎI<hr/>";
		if (isset($_POST['save'])) { //after edit
			$tieude = mysql_real_escape_string($_POST['cauhoi']);
			$loaitru=$_POST['loaitru'];
			$sudung=(isset($_POST['sudung'])?"1":"0");
			$khoi = $_POST['khoilop'];
			$chuong = $_POST['chuong'];
			$bai = $_POST['bai'];
			$kieu = $_POST['kieu'];
			$dokho = $_POST['dokho'];
			$sql= "UPDATE `tn_cauhoi` SET `lop`='$khoi', `chuong`='$chuong', `bai`='$bai', `dokho`='$dokho', `kieu`='$kieu', `noidung`='$tieude', `kichhoat`='$sudung', `ngaylam`=NOW() WHERE `maso`='$cau'";
			if (defined("DEBUG")) echo $sql."<br/>";
			$result = mysql_query($sql) or die (mysql_error());
			if ($result) {
				echo "THÀNH CÔNG";
				if (strlen($loaitru)>1) { //them cac loai tru
					mysql_query("delete from tn_loaitru where (cau1=$cau) and (cau2 not in ($loaitru))");
					$caus = explode(",", $loaitru);
					foreach ($caus as $cau1) {
						if ($cau1)
							themloaitru($cau,$cau1);
					}
				}
				else {
					mysql_query("delete from tn_loaitru where (cau1=$cau) or (cau2 = $cau)");
				}
				//redirect("?khoi=$khoi&chuong=$chuong",1);
			} else {
				echo "THẤT BẠI";
				//redirect("?",2);
			}
			redirect("?act=view&id=$cau",1);
		} else { //prepare to edit
			suacauhoi($cau);
		}
	} 
	else if ($act=="delete") { //xoa cau hoi
		if (isset($_GET['id'])) xoacauhoi($_GET['id']);
		redirect("?x$huong",1);
	} 
	else if ($act=="clone") { //nhan doi cau hoi
		if (isset($_GET['id'])) nhandoicauhoi($_GET['id'],isset($_GET['traloi']));
		//redirect("?",1);
	} 
	else { //xem cau hoi
		if (isset($_GET['id'])) { //by id
			echo "CÂU HỎI<hr/>";
			xemcauhoi($_GET['id']);
		} 
		else { //all
			lietkecauhoi($khoic,$chuongc,$baic);
		}
	}
echo "</body></html>";


function suacauhoi($cau) { //xem de bai
	global $bbcodes;
	$sql= "select * from tn_cauhoi where maso=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<div><form action='?act=edit&id=$cau' method='post'>";
		echo "Nội dung câu hỏi <br/><textarea cols='80'  class='ckeditor' name='cauhoi' rows='4' id='cauhoichinh'>".$data['noidung']."</textarea><br/>";
		for ($i=0; $i<count($bbcodes);$i++) {
				echo "<span class='bbcode'>[".$bbcodes[$i]."]</span>";
			}
		echo "<p>Loại trừ với các câu hỏi: <input type='text' name='loaitru' value='".dsloaitru($cau)."'/></p>";
		echo "<p>Sử dụng? <input type='checkbox' checked name='sudung'  ".($data['kichhoat']?"checked":"")."/></p>";
		echo "<p>Khối lớp: <input type='text' name='khoilop' size='2' value='".$data['lop']."'/>... Chương: <input type='text' name='chuong' size='2' value='".$data['chuong']."'/>... Bài: <input type='text' name='bai' size='2' value='".$data['bai']."'/></p>";
		echo "<p>Kiểu: <select name='kieu'><option value=1>Trắc nghiệm 1 lựa chọn</option><option value=2 ".($data['kieu']==2?"selected":"").">Điền vào chỗ trống</option></select>. ";
		echo "Độ khó: <select name='dokho'>";
		for ($i=1; $i<6; $i++) {
			echo "<option ".($data['dokho']==$i?"selected":"").">$i</option>";
		}
		echo "</select>. </p>";
		echo "<p><input type ='submit' name='save' value='Lưu'></p>";
		echo "</form></div>";
		echo "<p><a href='?act=delete&id=$cau' onclick=\"return (confirm('Bạn muốn xóa câu hỏi này'))\">Xóa câu hỏi này</a></p>";
		echo "<p><a href='traloi.php?act=view&cau=$cau'>Xem tra loi câu hỏi này</a></p>";
	} else {
		echo "Không có câu hỏi này";
	}
}

function xoacauhoi($cau) {
	$result = mysql_query("delete from tn_cauhoi where maso=$cau") or die(mysql_error());
	$result = mysql_query("delete from tn_traloi where cauhoi=$cau");
	$result = mysql_query("delete from tn_loaitru where cau1=$cau or cau2=$cau");
}
function nhandoicauhoi($cau,$cotraloi=true) {
	//echo "<p>Nhân đôi câu hỏi $cau</p>";
	$sql= "select `lop`, `chuong`, `bai`, `dokho`, `kieu`, `noidung` FROM `tn_cauhoi` where maso=$cau;";
	//echo "<p>$sql.</p>";
	$result = mysql_query($sql) or die (mysql_error());
	
	if ($row = mysql_fetch_array($result)) {
		mysql_query("insert into tn_cauhoi (`lop`, `chuong`, `bai`, `dokho`, `kieu`, `noidung`) values ('".$row['lop']."','".$row['chuong']."','".$row['bai']."','".$row['dokho']."','".$row['kieu']."','".mysql_real_escape_string($row['noidung'])."')");
		//echo("insert into tn_cauhoi (`lop`, `chuong`, `bai`, `dokho`, `kieu`, `noidung`) values ('".$row['lop']."','".$row['chuong']."','".$row['bai']."','".$row['dokho']."','".$row['kieu']."','".mysql_real_escape_string($row['noidung'])."')");
		$maso = mysql_insert_id();
		if ($cotraloi) {
			$result = mysql_query("SELECT `thutu`, `noidung`, `dung` FROM `tn_traloi` WHERE cauhoi=$cau");
			while ($row = mysql_fetch_array($result))	{
				//echo("</p>insert into tn_traloi (cauhoi,thutu,noidung,dung) values ($maso,".$row['thutu'].",'".mysql_real_escape_string($row['noidung'])."',".$row['dung'].");</p>");
				mysql_query("insert into tn_traloi (cauhoi,thutu,noidung,dung) values ($maso,".$row['thutu'].",'".mysql_real_escape_string($row['noidung'])."',".$row['dung'].");");
			}
		}
		//echo("<p>insert ignore into tn_loaitru (select $maso,cau2 from tn_loaitru where cau1=$cau and cau2<>$maso);</p>");
		mysql_query("insert ignore into tn_loaitru (select $maso,cau2 from tn_loaitru where cau1=$cau and $maso < cau2);");
		mysql_query("insert ignore into tn_loaitru (select cau2,$maso from tn_loaitru where cau1=$cau and $maso > cau2);");
		//echo("<p>insert ignore into tn_loaitru (select $maso,cau1 from tn_loaitru where cau2=$cau and cau1<>$maso);</p>");
		mysql_query("insert ignore into tn_loaitru (select $maso,cau1 from tn_loaitru where cau2=$cau and cau1 > $maso);");
		mysql_query("insert ignore into tn_loaitru (select cau1,$maso from tn_loaitru where cau2=$cau and cau1 < $maso);");
		//echo("<p>insert into tn_loaitru values ($maso,$cau);</p>");
		themloaitru($maso,$cau);
		echo "Thành công, với mã số $maso";
	}
	redirect("?act=view&id=".$maso,2);
}

function xemcauhoi($cau) { //xem de bai
	$sql= "select * from tn_cauhoi where maso=$cau";
	if (defined("DEBUG")) echo $sql."<br/>";
	$result = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($result)) {
		$data  = mysql_fetch_array($result);
		echo "<p><span style='margin: 10px 10px 0px 10px;'>Mã số: ". $data['maso']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Lớp: ". $data['lop']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Chương: ". $data['chuong']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Bài: ". $data['bai']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Kiểu: ". $data['kieu']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Độ khó: ". $data['dokho']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Kích hoạt: ". $data['kichhoat']."</span>";
		echo "<span style='margin: 10px 10px 0px 10px;'>Ngày tạo: ". $data['ngaylam']."</span></p>";
		echo "<p>Nội dung:</p><p style='background:#dddddd;'>". $data['noidung']."</p>";
		echo "<p>Loại trừ với các câu hỏi: ";
		$dsltru = dsloaitru($data['maso']);
		$loaitru = explode(",",$dsltru);
		foreach ($loaitru as $lt) {
			if($lt)
			echo "<a href='cauhoi.php?id=$lt'>$lt</a>, ";
		}
		echo  "</p>";
		echo "<hr/><p><a href='traloi.php?cau=$cau'>Xem các câu trả lời</a></p>";
		echo "<p><a href='?'>Quay lại danh mục câu hỏi</a></p>";
		echo "<p><a href='?act=edit&id=$cau'>Sửa câu hỏi này</a></p>";
		echo "<p><a href='preview.php?da&cau=$cau'>Xem thử câu hỏi này</a> cùng với <a href='preview.php?da&cau=$cau,$dsltru'>các câu loại trừ</a></p>";
		echo "<p><a href='?act=delete&id=$cau' onclick=\"return (confirm('Bạn muốn xóa câu hỏi này'))\">Xóa câu hỏi này</a></p>";
		echo "<p><a href='?act=clone&id=$cau'>Nhân đôi câu hỏi này</a> và <a href='?act=clone&traloi&id=$cau'>các trả lời đi kèm</a></p>";
		echo "<br/><a href='cauhoi.php?act=add&khoi=".$data['lop']."&chuong=".$data['chuong']."&bai=".$data['bai']."'>Thêm câu hỏi</a>";
		//redirect('traloi.php?cau='.$cau,8);
	} else {
		echo "Không có câu hỏi";
	}
}

function lietkecauhoi($ckhoi,$cchuong,$cbai){ //liet ke de bai theo trang
	echo "<div id='tieude'>DANH MỤC CÂU HỎI </div>";
	$sql="";
	if ($ckhoi) $sql= " where lop=$ckhoi ";
	if ($cchuong) $sql .= ($sql?" and":"where") . " chuong=$cchuong";
	if ($cbai) $sql .= ($sql?" and":"where") . " bai=$cbai";
	
	$sql="select * from tn_cauhoi " . $sql ." order by lop,chuong,bai";
	
	$result = mysql_query($sql) or die(mysql_error()); 
	if ($soluong=mysql_num_rows($result)) {
		echo "<p> So cau hoi: ".$soluong . "</p>";
		$i=1;
		echo "<table><tr><td>MS</td><td>Lớp</td><td>Chg</td><td>Bài</td><td>Khó</td><td>Kiểu</td><td>Câu hỏi</td><td>Loại trừ</td><td>Ngày tạo</td></tr>\n";
		while ($data = mysql_fetch_array($result)){
			echo "<tr class='row$i'>";
			echo "<td>".$data['maso']."</td>";
			echo "<td><a href='?khoi=".$data['lop']."'>".$data['lop']."</a></td>";
			echo "<td><a href='?khoi=".$data['lop']."&chuong=".$data['chuong']."'>".$data['chuong']."</a></td>";
			echo "<td><a href='?khoi=".$data['lop']."&chuong=".$data['chuong']."&bai=".$data['bai']."'>".$data['bai']."</a></td>";
			echo "<td>".$data['dokho']."</td>";
			echo "<td>".$data['kieu']."</td>";
			echo "<td>".($data['kichhoat']?"":"<strike>")."<a href='?act=view&id=".$data['maso']."' target='xemcauhoi'>".$data['noidung']."</a>".($data['kichhoat']?"":"</strike>")."</td>";
			echo "<td>".dsloaitru($data['maso'])."</td>";
			echo "<td>".$data['ngaylam']."</td>";
			echo "<tr>";
			$i=1-$i;
		}
		echo "</table>";
	} else {
		echo "<div class='thongbao'>Không có câu hỏi nào</div>";
	}
	//echo "<hr/>";
	echo "<p><a href='?act=add".($ckhoi?"&khoi=$ckhoi":"").($cchuong?"&chuong=$cchuong":"").($cbai?"&bai=$cbai":"")."' target='xemcauhoi'>Thêm câu hỏi</a></p>";
	//redirect("?x".($ckhoi?"&khoi=$ckhoi":"").($cchuong?"&chuong=$cchuong":"").($cbai?"&bai=$cbai":""),9);
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
function themloaitru($cau1, $cau2) {
	if ($cau1<$cau2)
		mysql_query("insert ignore into tn_loaitru values ($cau1,$cau2);");
	elseif ($cau1>$cau2)
		mysql_query("insert ignore into tn_loaitru values ($cau2,$cau1);");
}
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
