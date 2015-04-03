<?php
echo "<html><head><title>Đề bài</title><meta name='author' content='Tran Huu Nam'></head><body>";
include("thamso.php");

if (!isset($_GET['d'])) {
	exit ("Hãy chọn một mã đề.");
}
$made = $_GET['d'];
$handle = @fopen(__DIR__ ."\\baitap\\".$made.".txt", "r");
if ($handle) {
	$buffer = fgets($handle);
	echo "<h2>".$buffer."</h2>";
	echo "<div id='dsbaitoan'>";
	$i=1;
	while (!feof($handle)) {
		$buffer = fgets($handle);
		$vitri=strpos($buffer," ");
		//echo "vt".$vitri;
		if ($vitri===false)
			echo "<p><a href='bt.php?id=".$buffer."'>Bài số $i</a></p>";
		else
			echo "<p><a href='bt.php?id=".substr($buffer,0,1)."'> Bài $i. ".substr($buffer,$vitri+1)."</a></p>";
		$i++;
	}
	echo "</div>";
	fclose($handle);
}
else {
	exit ("Không thấy mã đề <span class='maudo'>".$made."</span>");
}

?>
