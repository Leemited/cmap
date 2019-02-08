<?php
include_once('./_common.php');
$chk = true;
if($type == "in"){
    //마지막 메뉴 값 가져오기
    $sql = "select * from `cmap_menu` where menu_depth = 0 order by menu_code desc limit 0, 1";
    $last = sql_fetch($sql);
    $menu_order = $last["menu_order"] + 1;
    $menu_code = (substr($last["menu_code"],0,1) + 1) . "0";

    $sql = "insert into `cmap_menu` set menu_name = '{$menu_name}', menu_order = '{$menu_order}', menu_depth=0, menu_code = '{$menu_code}'";
    if(sql_query($sql)){
        alert("등록되었습니다.");
    }else{
        alert("등록실패하였습니다.\\r다시 시도해 주세요.");
    }
}else if($type == "up") {
    $sql = "update `cmap_menu` set menu_name = '{$cate_name}', menu_order = '{$menu_order}', menu_status = '{$menu_status}' where me_id = '{$me_id}'";
    if (sql_query($sql)) {
        if ($menu_depth == 1) {
            $sql = "select * from `cmap_menu` where me_id = '{$me_id}'";
            $parent_id = sql_fetch($sql);
            $me_code = $parent_id["menu_code"];

            $sql = "update `cmap_depth1` set depth_name = '{$cate_name}', me_name = '{$cate_name}' where me_code = '{$me_code}'";
            if(sql_query($sql)){
                $chk = true;
            }else{
                $chk = false;
            }
        }
        if($chk==true) {
            alert("메뉴수정 완료");
        }else{
            alert("수정에 실패 하였습니다.");
        }
    } else {
        alert("수정에 실패 하였습니다.");
    }
}else if($type == "del"){
    $sql = "update  `cmap_menu` set menu_status = 3 where me_id = '{$me_id}'";
    if(sql_query($sql)){
        /*if($menu_depth==0) {
            $sql = "update `cmap_depth1` set menu_status = 1 where me_code = '{$menu_code}'  ";
            sql_query($sql);
        }*/
        alert("삭제처리 되었습니다.");
    }
}else if($type=="reset"){
    //if($menu_depth == 0){
        $sql = "update  `cmap_menu` set menu_status = 0 where me_id = '{$me_id}'";
        if(sql_query($sql)) {
            /*$sql = "update `cmap_depth1` set menu_status = 1 where me_code = '{$menu_code}'  ";
            sql_query($sql);*/
            alert("복구되었습니다.");
        }else{
            alert("복구에 실패 하였습니다.\\r다시 시도해 주세요.");
        }
    //}else {
    //    $sql = "update  `cmap_menu` set menu_status = 0 where me_id = '{$me_id}'";
    //}
}


//goto_url('./menu_list.php');
?>
