<?php
function redirect($location, $delaytime = 0) {
    if ($delaytime>0) {    
        header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
    } else {
        header("Location: ".str_replace("&amp;", "&", $location));
    }    
}
function dirspace() {
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/Win/',$agent)) 
		return "\\"; //windows
	else 
		return "//";  //Linux and others
}
?>
