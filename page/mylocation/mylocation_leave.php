<?php
include_once ("../../common.php");

$sql = "select * from `cmap_my_construct` where id = '{$const_id}'";
$con = sql_fetch($sql);

$mems = explode(",",$con["members"]);
for($i=0;$i<count($mems);$i++){
    if($mb_id==$mems[$i]){
        $mems[$i]="";
    }
}
$members = implode(",",array_filter($mems));

$sql = "update `cmap_my_construct` set members = '{$members}' where id = '{$const_id}'";
if(sql_query($sql)){
    $sql = "delete from `cmap_construct_invite` where (send_mb_id = '{$mb_id}' or read_mb_id = '{$mb_id}') and constid = '{$const_id}'";
    sql_query($sql);
    $sql = "delete from `cmap_my_construct_eval` where mb_id = '{$mb_id}' and constid = '{$const_id}'";
    sql_query($sql);
    $sql = "delete from `cmap_my_construct_map` where mb_id = '{$mb_id}' and constid = '{$const_id}'";
    sql_query($sql);
    $sql = "delete from `cmap_my_construct_eval_temp` where mb_id = '{$mb_id}' and constid = '{$const_id}'";
    sql_query($sql);
    $sql = "delete from `cmap_my_construct_map_temp` where mb_id = '{$mb_id}' and constid = '{$const_id}'";
    sql_query($sql);

    alert("탈퇴 완료 하였습니다.",G5_URL."/page/mylocation/mylocation");
}else{
    alert("정보 오류로 인해 탈퇴되지 못하였습니다.");
}
?>