<?php
include_once ("./_common.php");

$sql = "insert into `cmap_menu` set menu_name='{$menu_name}' , menu_depth = 1 ";
if(sql_query($sql)){
    $sql = "select me_id from `cmap_menu` order by me_id desc limit 0, 1";
    $id = sql_fetch($sql);
    $mid = $menu_code.$id["me_id"];
    $sql = "update `cmap_menu` set menu_code = '{$mid}' where me_id = '{$id['me_id']}'";
    if(sql_query($sql)){
        echo "1";
    }else{
        $sql = "delete from `cmap_menu` where me_id = '{$id['me_id']}'";
        sql_query($sql);
        echo "2";
    }
}else{
    echo "3";
}