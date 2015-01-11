<?php

require('FileDownload.class.php');
$file = 'book.zip';
$name = time().'.zip';
$obj = new FileDownload();
$flag = $obj->download($file, $name);
//$flag = $obj->download($file, $name, true); // 断点续传

if(!$flag){
    echo 'file not exists';
}

?>