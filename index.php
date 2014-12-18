<?php
//redirect("bt.php?id=6"); //bai tap & thuc hanh
redirect("kiemtra"); // kiem tra trac nghiem
echo "Hello, How are you?";
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&", "&", $location));
    }    
}
?>
