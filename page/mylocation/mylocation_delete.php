<?php
include_once ("../../common.php");

if(!$constid){
    alert("삭제할 현장을 선택해 주세요.");
    return false;
}
if($mb_id){
    $sql = "select * from `cmap_my_construct` where id = '{$constid}'";
    $chkcon = sql_fetch($sql);
    $mem = explode(",",$chkcon["members"]);

    for($i=0;$i<count($mem);$i++){
        if($mem[$i] == $mb_id){
            continue;
        }
        $newMem[] = $mem[$i];
    }

    $inmem = implode(",",$newMem);
    
    $sql = "update `cmap_my_construct` set mb_id = '{$mb_id}',members = '{$inmem}' where id = '{$constid}'";
    sql_query($sql);

    $sql = "update `cmap_myschedule` set mb_id = '{$mb_id}' where construct_id = '{$constid}'";
    sql_query($sql);

    alert("현장이 위임/삭제 되었습니다.");
}else {
    $sql = "update `cmap_my_construct` set status = -1 where id = '{$constid}'";
    if (sql_query($sql)) {
        $sql = "delete from `cmap_myschedule` where construct_id = '{$constid}'";
        sql_query($sql);

        $sql = "delete from `cmap_my_construct_eval` where const_id = '{$constid}'";
        sql_query($sql);

        $sql = "delete from `cmap_my_construct_map` where const_id = '{$constid}'";
        sql_query($sql);

        $sql = "delete from `cmap_my_construct_eval_temp` where const_id = '{$constid}'";
        sql_query($sql);

        $sql = "delete from `cmap_my_construct_eval_temp` where const_id = '{$constid}'";
        sql_query($sql);

        $sql = "delete from `cmap_construct_invite` where const_id = '{$constid}'";
        sql_query($sql);

        /*$sql = "delete from `cmap_construct_work_msg` where const_id = '{$constid}'";
        sql_query($sql);*/

        alert("삭제되었습니다.");
    } else {
        alert("정보 오류로 인해 삭제가 되지 않았습니다.");
    }
}
?>