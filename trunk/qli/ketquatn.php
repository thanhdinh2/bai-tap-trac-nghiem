<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Kết quả làm bài</title>";
?>
<script src="jquery.min.js" type="text/javascript"></script>
<script language='javascript'>
	var x=1,y=2;
	$(document).ready(function(){
		$(".xoa").click(function(){
			//alert( $(this).parent().parent().text());
			$(this).parent().parent().remove();
			$.post("delrow.php",{id:$(this).attr("id"),tab:"nopbaikiemtra"},function(data){
				//if (data.indexOf("OK")>=0) $(this).parent().parent().remove();
				//alert(data);
			});
		});
	});
	
</script>

<link rel="stylesheet" href='mystyle.css' type='text/css'>
<?php
echo "</head><body>";

$id=0;
$page = 1;
if (isset($_GET['p'])) $page=$_GET['p'];
$sl1page = 30;
if (isset($_GET['ep'])) $sl1page=$_GET['ep'];

echo "<div id='tracnghiem'>";
echo "KẾT QUẢ LÀM BÀI TRẮC NGHIỆM<BR/>";
echo "<table id='ketquatracnghiem'>";
echo "<tr align='center'><td>Xoá</td><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Ngày giờ nộp</td><td>Kết quả</td><td>Bài KT</td><td>IP</td></tr>";
$sql = "select * from nopbaikiemtra order by id desc limit ".(($page-1)*$sl1page).",".$sl1page;
$result = mysql_query($sql) or die (mysql_error());
$r=1;
while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center' class='row".($r++%2)."'>";
	echo "<td><span class='xoa' id='".$data['id']."'>Xoá</span></td>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".strtoupper($data['lop'])."</td>";
	echo "<td>".$data['ngaygio']."</td>";
	echo "<td>".$data['ketqua']."</td>";
	echo "<td>".$data['baikt']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var tnid=$id;</script>";
echo "</div>";

echo "<div id='page'>";
if ($page>1) echo "<a href='?p=".($page-1)."' class='page'>Trang trước</a>";
echo "<span class='page'>Trang ".$page."</span>";
if ($id>0) echo "<a href='?p=".($page+1)."' class='page'>Trang sau</a>";
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
