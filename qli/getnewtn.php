<?php

require_once "../config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
$sql="";
if (isset($_GET['id'])) {
	$sql = "select *,lucnop-lucdau as tgian from tn_ketqua  order by id desc limit 20";
}
else {
	$sql = "select *,lucnop-lucdau as tgian from tn_ketqua order by lucdau desc limit 20";
}
$result = mysql_query($sql) or die (mysql_error());
$ketqua=array();
while ($data = mysql_fetch_array($result)) {
	/*echo "<tr align='center' class='row".($data['id']%2)."'>";
	echo "<td>".$data['id']."</td>";
	echo "<td align='left'>".$data['hoten']."</td>";
	echo "<td>".$data['lop']."</td>";
	echo "<td>".$data['lucvao']."</td>";
	echo "<td>".$data['lucdau']."</td>";
	echo "<td>".$data['lucnop']."</td>";
	echo "<td>".$data['tgian']."</td>";
	echo "<td>".$data['caudung']."</td>";
	echo "<td>".$data['bai']."</td>";
	echo "<td align='left'>".$data['ip']."</td>";
	echo "</tr>";
	*/
	$danop=$data['lucdau'] != '0000-00-00 00:00:00'?1:0;
	if ($data['lucnop'] != '0000-00-00 00:00:00') $danop++;
	$kq= "<td>".$data['id']."</td>";
	$kq.= "<td align='left'>".$data['hoten']."</td>";
	$kq.= "<td>".$data['lop']."</td>";
	$kq.= "<td>".substr($data['lucvao'],-8)."</td>";
	$kq.= "<td>".($danop>0?substr($data['lucdau'],-8):"-")."</td>";
	$kq.= "<td>";
	if ($danop>1)
		$kq.=substr($data['lucnop'],-8);
	else
		$kq.="-";
	$kq.="</td>";
	$kq.= "<td>";
	
	if ($danop>1){
		$phut=(int)($data['tgian']/100);
		$giay=$data['tgian'] % 100;
		if ($giay>=60) $giay-=40;
		$kq.= $phut .":". ($giay<=9?"0":"").$giay;
	}
	else {
		$kq.="-";
	}
	$kq.="</td>";
	$kq.= "<td><b>".($danop>1?$data['caudung']:"-")."</b></td>";
	$kq.= "<td><em>".$data['bai']."</em></td>";
	$kq.= "<td align='right'>".substr($data['ip'],-3)."</td>";
	array_push($ketqua,[$data['id'],$danop,$kq]);
}
echo json_encode($ketqua);
?>
