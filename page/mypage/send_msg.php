<?php
include_once ("../../common.php");

$pk_idss = implode("``",$_REQUEST["pk_ids"]);

if(!$msg_group){
    $msg_group = "msg_".date("Ymdhis");
}
if(!$msg_count){
    $msg_count = 0;
}

for($i=0;$i<count($mb_id);$i++){
    $mbinfo = get_member($mb_id[$i]);
    if($mbinfo["mb_9"]){
        $mbnames[]=$mbinfo["mb_9"];
    }else{
        $mbnames[] = $mbinfo["mb_1"]." ".$mbinfo["mb_4"]." ".$mbinfo["mb_name"];
    }
}

$mb_ids = implode(",",$mb_id);
$mb_names = implode(",",$mbnames);

if($msg_id) {
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $msg_chk = sql_fetch($sql);
    $const_id = $msg_chk["const_id"];
    $cmap_name = $msg_chk["msg_send_cmap"];
}else {
    $const = sql_fetch("select cmap_name from `cmap_my_construct` where id = '{$const_id}'");
    $cmap_name = $const["cmap_name"];
}

$sql = "insert into `cmap_construct_work_msg` set send_mb_id = '{$member["mb_id"]}' , read_mb_id = '{$mb_ids}', msg_subject = '{$msg_subject}', msg_content = '{$msg_content}', msg_retype = '{$msg_retype}', send_date = now(), send_time = now(), pk_ids = '{$pk_idss}', delay_view = '{$delay_view}',const_id = '{$const_id}', msg_count='{$msg_count}', msg_group='{$msg_group}', msg_sign_filename = '{$msg_sing_filename}',msg_send_name='{$member["mb_9"]}', msg_send_addr = '{$member["mb_zip1"]}//{$member["mb_addr1"]}{$member["mb_addr2"]}',msg_send_cmap = '{$cmap_name}',msg_read_name = '{$mb_names}', msg_send_hp = '{$member["mb_hp"]}'";
$msg_count++;

if(!sql_query($sql)){
    $msg[] = $mb_id[$i];
}

if($type=="resend"){//회신 완료 처리
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $msg_chk = sql_fetch($sql);
    $retype_chk = $msg_chk["msg_retype_member"];

    if($msg_chk["msg_retype_member"]!="") {
        $retype_date = date("Y-m-d");
        $retype_time = date("H:i:s");

        $where = " msg_retype_member = CONCAT(msg_retype_member, ',', '{$member["mb_id"]}'), msg_retype_date = '{$retype_date}', msg_retype_time = '{$retype_time}'";
    }else{
        $retype_date = date("Y-m-d");
        $retype_time = date("H:i:s");
        $where = " msg_retype_member = '{$member["mb_id"]}', msg_retype_date = '{$retype_date}', msg_retype_time = '{$retype_time}'";
    }

    $sql = "update `cmap_construct_work_msg` set {$where} where id = '{$msg_id}'";
    sql_query($sql);

    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $chkretype = sql_fetch($sql);
    $retypemember = explode(",",$chkretype["msg_retype_member"]);
    $readmember = explode(",",$chkretype["read_mb_id"]);
    if($chkretype["msg_retype_member"]!="") {
        if (count($retypemember) == count($readmember)) {
            $sql = "update `cmap_construct_work_msg` set msg_retype_status = 1 where id = '{$msg_id}'";
            sql_query($sql);
        }
    }
}


if(count($msg)>0){
    $msgs = implode(",",$msg);
    alert($msgs."에게 전송을 하지 못했습니다.");
}else {
    alert("전송완료");
}
?>