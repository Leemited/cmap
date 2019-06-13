<?php
include_once ("../../common.php");

$pk_idss = implode("``",$_REQUEST["pk_ids"]);

if(!$msg_group){
    $msg_group = "msg_".date("Ymdhis");
}
if(!$msg_count){
    $msg_count = 0;
}

$mb_ids = implode(",",$mb_id);

if($msg_id) {
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $msg_chk = sql_fetch($sql);
    $const_id = $msg_chk["const_id"];
}

$sql = "insert into `cmap_construct_work_msg` set send_mb_id = '{$member["mb_id"]}' , read_mb_id = '{$mb_ids}', msg_subject = '{$msg_subject}', msg_content = '{$msg_content}', msg_retype = '{$msg_retype}', send_date = now(), send_time = now(), pk_ids = '{$pk_idss}', delay_view = '{$delay_view}',const_id = '{$const_id}', msg_count='{$msg_count}', msg_group='{$msg_group}' ";

$msg_count++;
if(!sql_query($sql)){
    $msg[] = $mb_id[$i];
}

if($type=="resend"){//회신 완료 처리
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $msg_chk = sql_fetch($sql);

    if(!strpos($msg_chk["msg_retype_member"],$member["mb_id"])!==false){
        if($msg_chk["msg_retype_member"]==""){
            $msg_in_member = $member["mb_id"];
        }else {
            $msg_in_member = $msg_chk["msg_retype_member"].",".$member["mb_id"];
        }

        $retype_member_cnt = explode(",",$msg_chk["msg_retype_member"]);
        $in_member_cnt = explode(",",$msg_in_member);
        if(count($retype_member_cnt) == count($in_member_cnt)){
            $inwhere = " , msg_retype_date = now(), msg_retype_time = now()";
        }

        $sql = "update `cmap_construct_work_msg` set msg_retype_member = '{$msg_in_member}' {$inwhere} where id = '{$msg_id}'";
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