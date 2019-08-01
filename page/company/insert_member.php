<?php
include_once ("../../common.php");

$sql = "select count(*)as cnt from `g5_member` where mb_id = '{$mb_id}'";
$chkmem = sql_fetch($sql);

if($chkmem["cnt"] > 0){
    alert("해당 아이디는 이미 등록되어 있는 아이디입니다.");
}

$sql = "insert into `g5_member` SET 
          mb_id = '{$mb_id}',
          mb_password = password('{$mb_password}'),
          mb_level = '{$mb_level}',
          mb_name = '".$member["mb_1"]."_PM_".date("Ymd")."',
          mb_nick = '{$mb_id}',
          mb_datetime = now(),
          mb_open_date = now(),
          mb_1 = '{$member["mb_1"]}',
          mb_2 = '{$member["mb_2"]}',
          mb_3 = '{$member["mb_3"]}',
          parent_mb_id = '{$member["mb_id"]}'
        ";
if(sql_query($sql)){
    alert("등록완료 되었습니다. 반드시 결제 후 사용 바랍니다.");
}else{
    alert("등록 오류 입니다.");
}

?>