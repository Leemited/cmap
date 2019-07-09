<?php
include_once ("../common.php");

if(!$is_member){
    goto_url(G5_BBS_URL."/login");
}

if($type=="in") {
    $sql = "insert into `cmap_mymemo` set memo_content = '{$memo_content}', mb_id ='{$mb_id}', me_id='{$me_id}', depth1_id = '{$depth1_id}' , depth2_id = '{$depth2_id}',InsertDate=now(), InsertTime=now()";
    if(sql_query($sql)){
        goto_url(G5_URL.'/page/'.$return_url."?me_id=".$me_id."&depth1_id=".$depth1_id."&depth2_id=".$depth2_id);
    }else{
        alert("메모를 등록하지 못햇습니다.");
    }
}else if($type=="del"){
    $sql = "delete from `cmap_mymemo` where mb_id='{$mb_id}' and InsertDate='{$InsertDate}'";
    if(sql_query($sql)){
        goto_url(G5_URL.'/page/'.$return_url."?me_id=".$me_id."&depth1_id=".$depth1_id."&depth2_id=".$depth2_id);
    }else{
        alert("메모를 삭제하지 못햇습니다.");
    }
}

?>