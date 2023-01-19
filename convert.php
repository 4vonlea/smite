<?php

$listFiles = glob("application/views/template/email/*.php");

foreach($listFiles as $file){
    $filename = basename($file);
    $content = file_get_contents($file);
    $content = str_replace(["<br/>","<br />"],"\n",$content);
    $content = str_replace(["<strong>","</strong>"],"*",$content);
    $content = preg_replace_callback('/'.preg_quote('<?=').'[\s\S]+?'.preg_quote('?>').'/', function($matches) {
        static $inc = 0;
        $inc++;
        return "{{".$inc."}}";
    }, $content);
    $content = strip_tags($content);
    file_put_contents("./convert/$filename",$content);
}
