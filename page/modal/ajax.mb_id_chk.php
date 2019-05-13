<?php
include_once('../../common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$msg = exist_mb_id($mb_id);

if($msg) {
    $result["msg"] = $msg;
    $result["chk"] = "N";
}else{
    $result["chk"] = "Y";
}
echo json_encode($result);