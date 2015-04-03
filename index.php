<?php
//redirect("bt.php?id=8"); //bai tap & thuc hanh
//redirect("de.php?d=bt141223"); //nhom bai tap & thuc hanh
redirect("kt.php"); // kiem tra trac nghiem
echo "Hello, How are you?";
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&", "&", $location));
    }    
}
?>
