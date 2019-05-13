<?php
include_once ("../common.php");

if($pk_id) {
    $sql = "select count(*) as cnt, id from `cmap_menu_desc` where pk_id = '{$pk_id}' and depth = '{$depth}'";
    $chkcnt = sql_fetch($sql);
    if ($chkcnt["cnt"] == 0) {
        $sql = "insert into `cmap_menu_desc` set 
              pk_id = '{$pk_id}',
              menu_name = '{$menu_name}',
              menu_desc = '{$content}',
              depth = '{$depth}',
              depth_id = '{$id}'
            ";
    } else {
        $sql = "update `cmap_menu_desc` set 
              pk_id = '{$pk_id}',
              menu_name = '{$menu_name}',
              menu_desc = '{$content}',
              depth = '{$depth}',
              depth_id = '{$id}'
              where id = '{$chkcnt["id"]}'
            ";
    }

    if (sql_query($sql)) {
        alert("수정 완료");
    } else {
        alert("수정 정보 오류로 인한 수정 실패");
    }
}else{
    if($id){
        $sql = "update `cmap_menu_desc` set menu_desc = '{$menu_desc}', menu_name = '{$menu_name}' where id = '{$id}'";
    }else{
        $sql = "insert into `cmap_menu_desc` set menu_desc = '{$menu_desc}', menu_name = '{$menu_name}' , menu_id = '{$menu_id}' ";
    }
    if(sql_query($sql)){
        alert("수정 완료");
    }else{
        alert("수정 정보 오류로 인한 수정 실패");
    }
}