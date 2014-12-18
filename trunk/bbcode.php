<?php
/* Dự án kiểm tra trắc nghiệm trong mạng LAN
 * Đăng nhập đơn giản và làm bài trắc nghiệm cho thí sinh
 * Ngày 10/11/2014
 * Tác giả: Trần Hữu Nam - thnam@thptnguyendu.edu.vn
 * 
 */
 function showBBcodes($text) {

// BBcode array
$find = array(
'~\[b\](.*?)\[/b\]~s',
'~\[i\](.*?)\[/i\]~s',
'~\[u\](.*?)\[/u\]~s',
'~\[pre\](.*?)\[/pre\]~s',
'~\[size=(.*?)\](.*?)\[/size\]~s',
'~\[color=(.*?)\](.*?)\[/color\]~s',
'~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
'~\[img\](.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s',
'~\[br\]~s',
'~\[code\](.*?)\[/code\]~s',
'~\[sup\](.*?)\[/sup\]~s',
'~\[sub\](.*?)\[/sub\]~s'
);

// HTML tags to replace BBcode
$replace = array(
'<b>$1</b>',
'<i>$1</i>',
'<span style="text-decoration:underline;">$1</span>',
'<pre>$1</'.'pre>',
'<span style="font-size:$1px;">$2</span>',
'<span style="color:$1;">$2</span>',
'<a href="$1">$1</a>',
'<img src="image/$1"/>',
'<br/>',
'<span class="codetext">$1</span>',
'<sup>$1</sup>',
'<sub>$1</sub>'
);
// Replacing the BBcodes with corresponding HTML tags
return preg_replace($find,$replace,$text);
}
 ?>