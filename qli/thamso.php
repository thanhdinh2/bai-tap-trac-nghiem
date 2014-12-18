<?php
$whitelist = array(
    '127.0.0.1',
    '::1'
);
$MYIP=$_SERVER['REMOTE_ADDR'];
$local = in_array($MYIP, $whitelist);
date_default_timezone_set("Asia/Ho_Chi_Minh");


?>
