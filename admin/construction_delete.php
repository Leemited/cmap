<?php
include_once ("./_common.php");

if($depth==1) {
    $sql = "delete from `cmap_depth1` where id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth2` where depth1_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth3` where depth1_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth4` where depth1_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_content` where depth1_id = '{$id}'";
    sql_query($sql);

    /*$sql = "update `cmap_depth1` set id = id - 1 where id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_depth2` set depth1_id = depth1_id - 1 where depth1_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_depth3` set depth1_id = depth1_id - 1 where depth1_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_depth4` set depth1_id = depth1_id - 1 where depth1_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_content` set depth1_id = depth1_id - 1 where depth1_id > '{$id}'";
    sql_query($sql);*/
}
if($depth==2){
    $sql = "select * from  `cmap_depth2` where id = '{$id}'";
    $item = sql_fetch($sql);

    $sql = "select COUNT(*)as cnt from `cmap_depth2` where depth1_id = '{$item['depth1_id']}'";
    $count = sql_fetch($sql);
    if($count["cnt"]<=1){
        alert("상세 항목이 하나 일경우는 상위 목록을 삭제해 주세요;");
        return false;
    }

    $sql = "delete from `cmap_depth2` where id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth3` where depth2_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth4` where depth2_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_content` where depth2_id = '{$id}'";
    sql_query($sql);

    /*$sql = "update `cmap_depth2` set id = id - 1 where id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_depth3` set depth2_id = depth2_id - 1 where depth2_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_depth4` set depth2_id = depth2_id - 1 where depth2_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_content` set depth2_id = depth2_id - 1 where depth2_id > '{$id}'";
    sql_query($sql);*/
}

if($depth==3){
    $sql = "select * from  `cmap_depth3` where id = '{$id}'";
    $item = sql_fetch($sql);

    $sql = "select COUNT(*)as cnt from `cmap_depth3` where depth2_id = '{$item['depth2_id']}'";
    $count = sql_fetch($sql);
    if($count["cnt"]<=1){
        alert("상세 항목이 하나 일경우는 상위 목록을 삭제해 주세요;");
        return false;
    }

    $sql = "delete from `cmap_depth3` where id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_depth4` where depth3_id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_content` where depth3_id = '{$id}'";
    sql_query($sql);

    /*$sql = "update `cmap_depth3` set id = id - 1 where id > '{$id}'";
    sql_query($sql);
    
    $sql = "update `cmap_depth4` set depth3_id = depth3_id - 1 where depth3_id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_content` set depth3_id = depth3_id - 1 where depth3_id > '{$id}'";
    sql_query($sql);*/
}

if($depth==4){
    $sql = "select * from  `cmap_depth4` where id = '{$id}'";
    $item = sql_fetch($sql);

    $sql = "select COUNT(*)as cnt from `cmap_depth4` where depth3_id = '{$item['depth3_id']}'";
    $count = sql_fetch($sql);
    if($count["cnt"]<=1){
        alert("상세 항목이 하나 일경우는 상위 목록을 삭제해 주세요;");
        return false;
    }

    $sql = "delete from `cmap_depth4` where id = '{$id}'";
    sql_query($sql);

    $sql = "delete from `cmap_content` where depth4_id = '{$id}'";
    sql_query($sql);

    /*$sql = "update `cmap_depth4` set id = id - 1 where id > '{$id}'";
    sql_query($sql);

    $sql = "update `cmap_content` set depth4_id = depth4_id - 1 where depth4_id > '{$id}'";
    sql_query($sql);*/
}

if($depth==5) {
    $sql = "select * from  `cmap_content` where id = '{$id}'";
    $item = sql_fetch($sql);

    $sql = "select COUNT(*)as cnt from `cmap_content` where depth4_id = '{$item['depth4_id']}'";
    $count = sql_fetch($sql);
    if($count["cnt"]<=1){
        alert("상세 항목이 하나 일경우는 상위 목록을 삭제해 주세요;");
        return false;
    }else {
        $sql = "delete from `cmap_content` where id = '{$id}'";
        sql_query($sql);

        /*$sql = "update `cmap_content` set id = id - 1 where id > '{$id}'";
        sql_query($sql);*/
    }
}

alert("삭제되었습니다.");
?>