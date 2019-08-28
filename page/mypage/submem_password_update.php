<?php
include_once ("../../common.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login");
}

if($new_mb_password=="" || !$new_mb_password){
    alert("새패스워드를 입력해 주세요.");
    return false;
}

$sql = "update `g5_member` set mb_password = password('{$new_mb_password}') where mb_id ='{$mb_id}'";

if(sql_query($sql)){
    alert("수정되었습니다.",G5_BBS_URL."/login");
}else{
    alert("다시 시도해 주세요.");
}