<?php
/* Dự án Bài tập thực hành
 * Lựa chọn quản lí
 * Ngày 14/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 */
require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Bảng quản lí</title>";
?>


</head><body>
<p><a href='baitn.php'>Quản lí các bài trắc nghiệm</a></p>
<p><a href='cauhoi.php'>Soạn câu hỏi trắc nghiệm</a></p>
<p><a href='soanbt.php'>Soạn bài tập thực hành</a></p>
<p><a href='soanbh.php'>Soạn bài học - đơn vị kiến thức</a></p>
<p><a href='ketqua.php'>Kết quả</a></p>
<p><a href='sua.php?f=index.php'>Sửa tệp index.php</a></p>
</body></html>



