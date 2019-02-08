<?php
include_once ("_common.php");

if($depth==1){
    $flag = true;
    $sql = "update `cmap_depth1` set id = id+1 where id >= '{$addid}'";

    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_depth2` set depth1_id = depth1_id+1 where depth1_id >= '{$addid}'";
    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_depth3` set depth1_id = depth1_id+1 where depth1_id >= '{$addid}'";
    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_depth4` set depth1_id = depth1_id+1 where depth1_id >= '{$addid}'";
    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_content` set depth1_id = depth1_id+1 where depth1_id >= '{$addid}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "insert into `cmap_depth1` set id='{$addid}', me_code = '{$me_code}' , me_id = '" . substr($me_code, 0, 2) . "'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id1 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth2`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth2_id = 1;
        } else {
            $depth2_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_depth2` set depth1_id='{$addid}', id = '{$depth2_id}' ";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id2 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth3`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth3_id = 1;
        } else {
            $depth3_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_depth3` set depth1_id='{$addid}', depth2_id = '{$depth2_id}' , id = '{$depth3_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id3 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth4`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth4_id = 1;
        } else {
            $depth4_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_depth4` set depth1_id='{$addid}', depth2_id = '{$depth2_id}', depth3_id = '{$depth3_id}',  id = '{$depth4_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id4 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth5`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth5_id = 1;
        } else {
            $depth5_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_content` set depth1_id='{$addid}', depth2_id = '{$depth2_id}', depth3_id = '{$depth3_id}', depth4_id = '{$depth4_id}',  id = '{$depth5_id}', content = '``'";
    if (!sql_query($sql)) {
        $flag = false;
    }else{
        $pk_id5 = sql_insert_id();
    }

    if ($flag == false) {
        $result["status"] = "2";
    } else {
        $result["status"] = "1";
        $result["pk_id1"] = $pk_id1;
        $result["pk_id2"] = $pk_id2;
        $result["pk_id3"] = $pk_id3;
        $result["pk_id4"] = $pk_id4;
        $result["pk_id5"] = $pk_id5;
        $result["depth2_id"] = $depth2_id;
        $result["depth3_id"] = $depth3_id;
        $result["depth4_id"] = $depth4_id;
        $result["depth5_id"] = $depth5_id;
    }
    echo json_encode($result);
}

if($depth==2){
    $flag = true;
    $sql = "update `cmap_depth2` set id = id + 1 where id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "update `cmap_depth3` set depth2_id = depth2_id+1 where depth2_id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_depth4` set depth2_id = depth2_id+1 where depth2_id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }
    $sql = "update `cmap_content` set depth2_id = depth2_id+1 where depth2_id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "insert into `cmap_depth2` set depth1_id='{$parent_id}', id = '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id2 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth3`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth3_id = 1;
        } else {
            $depth3_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_depth3` set depth1_id='{$parent_id}', depth2_id = '{$id}', id = '{$depth3_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id3 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth4`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth4_id = 1;
        } else {
            $depth4_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_depth4` set depth1_id='{$parent_id}', depth2_id = '{$id}', depth3_id = '{$depth3_id}' , id = '{$depth4_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id4 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_content`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth5_id = 1;
        } else {
            $depth5_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_content` set depth1_id='{$parent_id}', depth2_id = '{$id}', depth3_id = '{$depth3_id}', depth4_id = '{$depth4_id}', id = '{$depth5_id}' , content = '``'";
    if (!sql_query($sql)) {
        $flag = false;
    }else{
        $pk_id5 = sql_insert_id();
    }

    if ($flag == false) {
        $result["status"] = "2";
    } else {
        $result["status"] = "1";
        $result["pk_id2"] = $pk_id2;
        $result["pk_id3"] = $pk_id3;
        $result["pk_id4"] = $pk_id4;
        $result["pk_id5"] = $pk_id5;
        $result["depth2_id"] = $id;
        $result["depth3_id"] = $depth3_id;
        $result["depth4_id"] = $depth4_id;
        $result["depth5_id"] = $depth5_id;
    }
    echo json_encode($result);
}
/*if($depth == 3){
    $flag = true;
    $sql = "select * from `cmap_depth3` where id = '{$thisid}'";
    $dep = sql_fetch($sql);
    $depth1_id = $dep["depth1_id"];
    $depth2_id = $dep["depth2_id"];

    $sql = "update `cmap_depth3` set id = id + 1 where id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "update `cmap_depth4` set depth3_id = depth3_id+1 where depth3_id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "update `cmap_content` set depth3_id = depth3_id+1 where depth3_id >= '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }

    $sql = "insert into `cmap_depth3` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', id = '{$id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id3 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_depth4`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth4_id = 1;
        } else {
            $depth4_id = $maxid['maxid'] + 1;
        }
    }

    $sql = "insert into `cmap_depth4` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', depth3_id = '{$id}' , id = '{$depth4_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    } else {
        $pk_id4 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_content`";
        $maxid = sql_fetch($sql);
        if ($maxid["maxid"] == 0) {
            $depth5_id = 1;
        } else {
            $depth5_id = $maxid['maxid'] + 1;
        }
    }
    $sql = "insert into `cmap_content` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', depth3_id = '{$id}', depth4_id = '{$depth4_id}', id = '{$depth5_id}'";
    if (!sql_query($sql)) {
        $flag = false;
    }else{
        $pk_id5 = sql_insert_id();
    }

    if ($flag == false) {
        $result["status"] = "2";
    } else {
        $result["status"] = "1";
        $result["pk_id3"] = $pk_id3;
        $result["pk_id4"] = $pk_id4;
        $result["pk_id5"] = $pk_id5;
        $result["depth1_id"] = $depth1_id;
        $result["depth2_id"] = $depth2_id;
        $result["depth3_id"] = $id;
        $result["depth4_id"] = $depth4_id;
        $result["depth5_id"] = $depth5_id;
    }
    echo json_encode($result);
}
if($depth == 4){
    $flag = true;

    $sql = "select * from `cmap_depth4` where id = '{$thisid}'";
    $dep = sql_fetch($sql);
    $depth1_id = $dep["depth1_id"];
    $depth2_id = $dep["depth2_id"];
    $depth3_id = $dep["depth3_id"];

    $sql = "update `cmap_depth4` set id = id + 1 where id >= '{$id}'";
    if(!sql_query($sql)){
        $flag = false;
    }

    $sql = "update `cmap_content` set depth4_id = depth4_id+1 where depth4_id >= '{$id}'";
    if(!sql_query($sql)){
        $flag = false;
    }

    $sql ="insert into `cmap_depth4` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', depth3_id = '{$depth3_id}' , id = '{$id}'";
    if(!sql_query($sql)){
        $flag = false;
    }else{
        $pk_id4 = sql_insert_id();
        $sql = "select MAX(id) as maxid from `cmap_content`";
        $maxid = sql_fetch($sql);
        if($maxid["maxid"]==0){
            $depth5_id = 1;
        }else{
            $depth5_id = $maxid['maxid'] + 1;
        }
    }
    $sql ="insert into `cmap_content` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', depth3_id = '{$depth3_id}', depth4_id = '{$id}', id = '{$depth5_id}'";
    if(!sql_query($sql)){
        $flag = false;
    }else{
        $pk_id5 = sql_insert_id();
    }

    if($flag==false){
        $result["status"] = "2";
    }else{
        $result["status"] = "1";
        $result["pk_id4"] = $pk_id4;
        $result["pk_id5"] = $pk_id5;
        $result["depth1_id"] = $depth1_id;
        $result["depth2_id"] = $depth2_id;
        $result["depth3_id"] = $depth3_id;
        $result["depth4_id"] = $id;
        $result["depth5_id"] = $depth5_id;
    }
    echo json_encode($result);
}
if($depth == 5){
    $flag = true;
    $sql = "select * from `cmap_content` where id = '{$thisid}'";
    $dep = sql_fetch($sql);
    $depth1_id = $dep["depth1_id"];
    $depth2_id = $dep["depth2_id"];
    $depth3_id = $dep["depth3_id"];
    $depth4_id = $dep["depth4_id"];

    $sql = "update `cmap_content` set id = id + 1 where id >= '{$id}'";
    if(!sql_query($sql)){
        $flag = false;
    }

    $sql ="insert into `cmap_content` set depth1_id='{$depth1_id}', depth2_id = '{$depth2_id}', depth3_id = '{$depth3_id}', depth4_id = '{$depth4_id}', id = '{$id}'";
    if(!sql_query($sql)){
        $flag = false;
    }else{
        $pk_id5 = sql_insert_id();
    }

    if($flag==false){
        $result["status"] = "2";
    }else{
        $result["status"] = "1";
        $result["pk_id5"] = $pk_id5;
        $result["depth1_id"] = $depth1_id;
        $result["depth2_id"] = $depth2_id;
        $result["depth3_id"] = $depth3_id;
        $result["depth4_id"] = $depth4_id;
        $result["depth5_id"] = $id;
    }

    echo json_encode($result);
}*/
?>