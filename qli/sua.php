<?php
echo "<html><head><title>Sửa nội dung tập tin</title><meta charset='utf-8'><meta name='author' content='Trần Hữu Nam'>";
echo "</head>";
echo "<body>";
include("thamso.php");
include_once ("funcs.php");
$ds = dirspace();
if (!$local) exit("Khu vực dành riêng");
if (isset($_GET['f'])) { //da chon tep = bat buoc
	$tep=$_GET['f'];
	if (isset($_POST['sua'])) {
		$f = __DIR__ . $ds ."..". $ds .  $tep;
		file_put_contents($f,$_POST['noidung']);
		echo "Đã lưu các thay đổi.";
		redirect("?f=$tep",3);
	} else {
		echo "<h2>Sửa tập tin</h2>";
		echo "<form action='?f=".$tep."' method='post'>";
		echo "<p><textarea class='ckeditor' name='noidung' cols='120' rows='20'>";
		echo file_get_contents( __DIR__ . $ds ."..". $ds .  $tep);
		echo "</textarea></p>";
		echo "<input type='submit' name='sua' value='Lưu thay đổi'/>";
		echo "</form>";
		//echo __DIR__ . $ds . $tep ;
	}
} else {
	echo "Không tìm thấy tập tin.";
}
?>
