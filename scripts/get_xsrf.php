<?php
$cookieFile = '/data/data/com.termux/files/home/biraconsultant_cookies.txt';
$lines = file($cookieFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach($lines as $line){
    if(str_contains($line, "XSRF-TOKEN")){
        $parts = explode("\t", $line);
        $token = end($parts);
        echo $token;
    }
}
?>