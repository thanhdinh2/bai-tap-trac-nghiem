<?php
/* Dự án Kiểm tra trắc nghiệm trong mạng LAN
 * Xem kết quả thi trắc nghiệm
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
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
	var x=1,y=2; doi=true;
	var tt = new Array();
	$(document).ready(function(){
		function update1() { //tracnghiem
			$.get("getnewtn.php?id="+$("#ketquatracnghiem tr:eq(1) td:first").text(),function(data){
				//if (data.indexOf("<tr>")>=0)
				//alert(data);
				//$("#ketquatracnghiem tr:first").after(data);
				if (data) {
					var bao=0;
					//$("#ambao").get(0).play();
					//$("#ketquatracnghiem tr:eq(1)").effect("highlight", {color:"#ff0000"}, 5000);
					var obj = $.parseJSON(data);
					for (var i=0; i<obj.length; i++) {
						var dong = "#row_"+obj[i][0];
						if ($(dong).length>0) {
							//$("#bian").append(obj[i][0]+"; ");
							if ((typeof tt[obj[i][0]]=='undefined') || (tt[obj[i][0]]!=obj[i][1]))
							{
								tt[obj[i][0]]=obj[i][1];
								$(dong).html(obj[i][2]);
								if (obj[i][1]>1) 
									bao++;
							}
								
						}
						else {
							$("#ketquatracnghiem tr:first").after("<tr align='center' id='row_"+obj[i][0]+"' class='row"+(obj[i][0]%2)+"'>"+obj[i][1]+"</tr>");
						}
					}
					if (bao>0) $("#ambao").get(0).play();
				}
			});
			//$("#test").append("1");
			setTimeout(update1,2000);
		}
		setTimeout(update1,2000);
		function update2() { //bai tap thuc hanh
			$.get("getnewbt.php?id="+$("#ketquabaitap tr:eq(1) td:first").text(),function(data){
				//if (data.indexOf("<tr>")>=0)
				$("#ketquabaitap tr:first").after(data);
			});
			//$("#test").append("2");
			setTimeout(update2,3700);
		}
		setTimeout(update2,3000);
		$(".row1").click(function(){
			$("#ambao").get(0).play();
		});
	});
	
</script>
<link rel="stylesheet" href='mystyles.css' type='text/css'>
<?php
echo "</head><body>";

$id=0;
echo "<div id='tracnghiem'>";
echo "KẾT QUẢ LÀM BÀI TRẮC NGHIỆM<BR/>";
echo "<table id='ketquatracnghiem'>";
echo "<tr align='center'><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Lúc vào</td><td>Bắt đầu</td><td>Giờ nộp</td><td>Thời gian</td><td>Kết quả</td><td>Bài KT</td><td>IP</td></tr>";
$sql = "select *,lucnop-lucdau as tgian from tn_ketqua order by id desc limit 10";
$result = mysql_query($sql) or die (mysql_error());
while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center' class='row".($data['id']%2)."' id='row_".$data['id']."'>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['lucvao']."</td>";
	echo "<td>".($data['lucdau']=='0000-00-00 00:00:00'?"-":$data['lucdau'])."</td>";
	echo "<td>".($data['lucnop']=='0000-00-00 00:00:00'?"-":$data['lucnop'])."</td>";
	echo "<td>";
	$phut=(int)($data['tgian']/100);
	$giay=$data['tgian'] % 100;
	if ($giay>=60) $giay-=40;
	echo  $phut .":". ($giay<=9?"0":"").$giay;
	echo "</td>";
	echo "<td>".$data['caudung']."</td>";
	echo "<td>".$data['bai']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var tnid=$id;</script>";
echo "<a href='ketquatn.php'>Xem thêm</a><span id='bian'></span>";
echo "</div>";
echo "<hr/>";
$id=0;
echo "<div id='baitap'>";
echo "KẾT QUẢ NỘP BÀI TẬP<BR/>";
echo "<table id='ketquabaitap'>";
echo "<tr align='center'><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Ngày giờ nộp</td><td>Tên tệp</td><td>Bài tập</td><td>IP</td></tr>";
$sql = "select * from th_nopbai order by id desc limit 5";
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
echo "<audio id='ambao' src='../media/ping_3.wav'>Không có</audio>";
echo "</body></html>";

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
