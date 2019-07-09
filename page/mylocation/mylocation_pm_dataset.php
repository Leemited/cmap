<?php
include_once ("../../common.php");

if(!$const_id){
    alert('선택된 현장이 없습니다.');
}

if(!$mb_id){
    alert("데이터를 취합할 사용자를 선택해 주세요.");
}

$mb = get_member($mb_id);

$sql = "select * from `cmap_my_pmmode_set` where mb_id = '{$member['mb_id']}' and const_id = '{$const_id}'";

$mypm_set = sql_fetch($sql);

if($mypm_set == null){
    $sql = "insert into `cmap_my_pmmode_set` set mb_id = '{$member["mb_id"]}', set_mb_id = '{$mb_id}', const_id = '{$const_id}'";
}else{
    $sql = "update `cmap_my_pmmode_set` set set_mb_id = '{$mb_id}' where id = '{$mypm_set["id"]}'";
}
if(sql_query($sql)){
    alert($mb_id."[".$mb["mb_name"]."]님의 데이터를 PMMODE로 불러옵니다.");
}else{
    alert("데이터 취합 정보를 업데이트 하지 못했습니다.");
}
?>