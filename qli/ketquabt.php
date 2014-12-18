<?php
/* Dự án Kiểm tra trắc nghiệm trong mạng LAN
 * Kết quả nộp bài tập thực hành
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
<script language='javascript'>
	var x=1,y=2;
	$(document).ready(function(){
		$(".xoa").click(function(){
			//alert( $(this).parent().parent().text());
			$(this).parent().parent().remove();
			$.post("delrow.php",{id:$(this).attr("id"),tab:"th_nopbai"},function(data){
				//if (data.indexOf("OK")>=0) $(this).parent().parent().remove();
				//alert(data);
			});
		});
	});
	
</script>
<style type="text/css">

table, td
{
    border-color: #600;
    border-style: solid;
}

table
{
    border-width: 0 0 1px 1px;
    border-spacing: 0;
    border-collapse: collapse;
}

td
{
    margin: 0;
    padding: 4px;
    border-width: 1px 1px 0 0;
    background-color: #FFC;
}
.page {
	margin: 5px 5px 5px 5px;
}
</style>
<?php
echo "</head><body>";

$id=0;
$page = 1;
if (isset($_GET['p'])) $page=$_GET['p'];
$sl1page = 30;
if (isset($_GET['ep'])) $sl1page=$_GET['ep'];
echo "<div id='baitap'>";
echo "KẾT QUẢ NỘP BÀI TẬP<BR/>";
echo "<table id='ketquabaitap'>";
echo "<tr align='center'><td>Xoá</td><td>ID</td><td>Họ và tên</td><td>Lớp</td><td>Ngày giờ nộp</td><td>Tên tệp</td><td>Bài tập</td><td>IP</td></tr>";
$sql = "select * from th_nopbai order by id desc limit ".(($page-1)*$sl1page).",".$sl1page;
$result = mysql_query($sql) or die (mysql_error());
while ($data = mysql_fetch_array($result)) {
	if ($id==0) $id = $data['id'];
	echo "<tr align='center'>";
	echo "<td><span class='xoa' id='".$data['id']."'>Xoá</span></td>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['ngaygio']."</td>";
	echo "<td><a href='../upload/".$data['file']."'>".$data['file']."</a></td>";
	echo "<td>".$data['baitap']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
}
echo "</table>";
echo "<script language='javascript'>var btid=$id;</script>";
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
