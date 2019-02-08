<?php
include_once ("./_common.php");

$path = G5_PATH."/admin/backup/".$filename;
if(is_file($path)) {
    if (!unlink($path)) {
        echo 2;
    } else {
        echo 1;
    }
}else{
    echo 3;
}