<?php
/* Dự án Kiểm tra trắc nghiệm trong mạng LAN
 * Kết quả nộp bài làm trắc nghiệm
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
$id=0;
$page = 1;
if (isset($_GET['p'])) $page=$_GET['p'];
$sl1page = 30;

$xemten="";
if (isset($_GET['ten'])) {
	$xemten=$_GET['ten'];
	$sl1page=100;
}
$xoa = "";//isset($_GET['xoa']) ? "" :"0";
$xid=isset($_GET['id'])?intval($_GET['id']):0;
echo "<html><head><title>Kết quả kiểm tra trắc nghiệm</title>";
//if (!isset($_GET['p'])) echo "<meta http-equiv='refresh' content='5'>";
?>
<script src="jquery.min.js" type="text/javascript"></script>
<script language='javascript'>
	var x=1,y=2;
	$(document).ready(function(){
		$(".xoa").click(function(){
			//alert( $(this).parent().parent().text());
			$(this).parent().parent().remove();
			$.post("delrow.php",{id:$(this).attr("id"),tab:"tn_ketqua"},function(data){
				//if (data.indexOf("OK")>=0) $(this).parent().parent().remove();
				//alert(data);
			});
		});
		
	});
	
</script>

<link rel="stylesheet" href='mystyles.css' type='text/css'>
<?php
echo "</head><body>";



echo "<div id='tracnghiem'>";
echo "KẾT QUẢ LÀM BÀI TRẮC NGHIỆM<BR/>";
echo "<div><a href='?id=".($xid+1)."'>Xem ke sau</a> <a href='?id=".($xid-1)."'>Xem ke truoc</a></div>";
echo "<table id='ketquatracnghiem'>";
echo "<tr align='center'><td>Xoá</td><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Kết quả</td><td>Bài KT</td><td>Thời gian</td><td>Ngày giờ lam</td><td>Ngày giờ nộp</td><td>IP</td></tr>";
if ($xid>0) {
	$sql="select hoten from tn_ketqua where id=$xid;";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result)) {
		$xemten = $row['hoten'];
	}
}

$sql = "select *,lucnop-lucdau as tgian from tn_ketqua where (hoten like '%$xemten%') order by ".($xemten?"hoten,id":"id")." desc limit ".(($page-1)*$sl1page).",".$sl1page;
$result = mysql_query($sql) or die (mysql_error());
$r=1;

while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center' class='row".($r++%2)."'>";
	echo "<td><span class='xoa$xoa' id='".$data['id']."'>Xoá</span></td>";
	echo "<td><a href='?id=".$data['id']."'>".$data['id']."</a></td>";
	echo "<td align='left'><a href='ketquatn.php?ten=".$data['hoten']."'>".$data['hoten']."</a></td>";
	echo "<td>".strtoupper($data['lop'])."</td>";
	echo "<td><a href='xembai.php?id=".$data['id']."'>".$data['caudung']."</a></td>";
	echo "<td>".$data['bai']."</td>";
	echo "<td>";
	$phut=(int)($data['tgian']/100);
	$giay=$data['tgian'] % 100;
	if ($giay>=60) $giay-=40;
	echo  $phut .":". ($giay<=9?"0":"").$giay;	
	echo "</td>";
	echo "<td>".$data['lucdau']."</td>";
	echo "<td>".$data['lucnop']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var tnid=$id;</script>";
echo "</div>";
if ($xid)


echo "<div id='page'>";
if ($page>2) echo "<a href='?p=".($page-2)."' class='page'>Trang ".($page-2)."</a>";
if ($page>1) echo "<a href='?p=".($page-1)."' class='page'>Trang ".($page-1)."</a>";
echo "<span class='page'>Trang ".$page."</span>";
if ($id>0) echo "<a href='?p=".($page+1)."' class='page'>Trang ".($page+1)."</a>";
if ($id>1) echo "<a href='?p=".($page+2)."' class='page'>Trang ".($page+2)."</a>";
echo "</div>";

echo "</body></html>";

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
