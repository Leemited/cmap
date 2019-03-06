<?php
include_once ("../../common.php");
if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php");
}

if($theme==""){
    alert("메뉴디자인을 선택해주세요");
}

if($cate==""){
    alert("카테고리디자인을 선택해주세요");
}

$sql = "SELECT COUNT( * ) AS cnt FROM  `cmap_mymenu_theme` WHERE mb_id =  '{$member["mb_id"]}'";
$num = sql_fetch($sql);
if($num["cnt"]==0){
    $sql = "insert into `cmap_mymenu_theme` set theme = '{$theme}' , cate_theme = '{$cate}' , mb_id = '{$member["mb_id"]}', insert_date = now(), update_date = now()";
    if(sql_query($sql)){
        alert("적용 되었습니다.");
    }else{
        alert("다시 시도해주세요.");
    }
}else{
    $sql = "update `cmap_mymenu_theme` set theme = '{$theme}' , cate_theme = '{$cate}', update_date = now() where mb_id = '{$member["mb_id"]}'";
    if(sql_query($sql)){
        alert("적용 되었습니다.");
    }else{
        alert("다시 시도해주세요.");
    }
}