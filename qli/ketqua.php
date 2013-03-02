<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Kết quả làm bài</title>";
?>
<script src="jquery.min.js" type="text/javascript"></script>
<script src="jquery-ui.min.js" type="text/javascript"></script>
<script language='javascript'>
	var x=1,y=2;
	$(document).ready(function(){
		function update1() { //tracnghiem
			$.get("getnewtn.php?id="+$("#ketquatracnghiem tr:eq(1) td:first").text(),function(data){
				//if (data.indexOf("<tr>")>=0)
				$("#ketquatracnghiem tr:first").after(data);
				if (data) {
					$("#ketquatracnghiem tr:eq(1)").effect("highlight", {color:"#ff0000"}, 5000);
				}
			});
			//$("#test").append("1");
			setTimeout(update1,1000);
		}
		setTimeout(update1,3000);
		function update2() { //tracnghiem
			$.get("getnewbt.php?id="+$("#ketquabaitap tr:eq(1) td:first").text(),function(data){
				//if (data.indexOf("<tr>")>=0)
				$("#ketquabaitap tr:first").after(data);
			});
			//$("#test").append("2");
			setTimeout(update2,3100);
		}
		setTimeout(update2,3000);
	});
	
</script>
<link rel="stylesheet" href='mystyle.css' type='text/css'>
<?php
echo "</head><body>";

$id=0;
echo "<div id='tracnghiem'>";
echo "KẾT QUẢ LÀM BÀI TRẮC NGHIỆM<BR/>";
echo "<table id='ketquatracnghiem'>";
echo "<tr align='center'><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Ngày giờ nộp</td><td>Kết quả</td><td>Bài KT</td><td>IP</td></tr>";
$sql = "select * from nopbaikiemtra order by id desc limit 5";
$result = mysql_query($sql) or die (mysql_error());
while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center' class='row".($data['id']%2)."'>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['ngaygio']."</td>";
	echo "<td>".$data['ketqua']."</td>";
	echo "<td>".$data['baikt']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var tnid=$id;</script>";
echo "<a href='ketquatn.php'>Xem thêm</a>";
echo "</div>";
echo "<hr/>";
$id=0;
echo "<div id='baitap'>";
echo "KẾT QUẢ NỘP BÀI TẬP<BR/>";
echo "<table id='ketquabaitap'>";
echo "<tr align='center'><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Ngày giờ nộp</td><td>Tên tệp</td><td>Bài tập</td><td>IP</td></tr>";
$sql = "select * from nopbai order by id desc limit 5";
$result = mysql_query($sql) or die (mysql_error());
while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center' class='row".($data['id']%2)."'>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['ngaygio']."</td>";
	echo "<td><a href='loadfile.php?f=".$data['file']."'>".$data['file']."</a></td>";
	echo "<td>".$data['baitap']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var btid=$id;</script>";
echo "<a href='ketquabt.php'>Xem thêm</a>";
echo "</div>";
echo "<div id='test'>x</div>";

echo "</body></html>";

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
