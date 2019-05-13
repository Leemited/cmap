<?php
include_once ("../../common.php");

if(!$invite_id){
    echo "1";
    return false;
}

$sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
if(sql_query($sql)){
    //todo:초대나 요청자에게 푸시 알림
    echo "2";
}else{
    echo "1";
}

?>