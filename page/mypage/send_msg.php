<?php
include_once ("../../common.php");

$pk_idss = implode("``",$_REQUEST["pk_ids"]);

if(!$msg_group){
    $msg_group = "msg_".date("Ymdhis");
}
if(!$msg_count){
    $msg_count = 0;
}

for($i = 0; $i<count($mb_id);$i++){
    $sql = "insert into `cmap_construct_work_msg` set send_mb_id = '{$member["mb_id"]}' , read_mb_id = '{$mb_id[$i]}', msg_subject = '{$msg_subject}', msg_content = '{$msg_content}', msg_retype = '{$msg_retype}', send_date = now(), send_time = now(), pk_ids = '{$pk_idss}', delay_view = '{$delay_view}',const_id = '{$const_id}', msg_count='{$msg_count}', msg_group='{$msg_group}' ";

    if(!sql_query($sql)){
        $msg[] = $mb_id[$i];
    }

    if($type=="resend"){//회신 완료 처리
        $sql = "update `cmap_construct_work_msg` set read_status = 1 where id = '{$msg_id}'";
        sql_query($sql);
    }
}

if(count($msg)>0){
    $msgs = implode(",",$msg);
    alert($msgs."에게 전송을 하지 못했습니다.");
}else {
    alert("전송완료");
}
?>