<?php

require_once "config.php";
$link = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
//define("DEBUG",TRUE);
echo "<html><head><title>Trắc nghiệm</title></head><body>";
session_start();
unset($_SESSION['ten']);
//unset($_SESSION['lop']);
unset($_SESSION['ketqua']);
unset($_SESSION['gio']);
unset($_SESSION['maso']);
unset($_SESSION['mabai']);
//unset($_SESSION);
echo "Đã ra khỏi khu vực thi";
echo "</body></html>";
redirect("kt.php",1);

function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
?>
