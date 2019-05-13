<?php
include_once ("../../common.php");
//DB 유무 체크
$sql = "select count(*)as cnt from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '{$menu_code}'";
$cnt = sql_fetch($sql);

$menu1 = explode("``",$_REQUEST["depth1"]);
$menu2 = explode("``",$_REQUEST["depth2"]);

for($i = 0;$i<count($menu1);$i++){
    $ext_menu = explode(":",$menu1[$i]);
    $menu_ids[$i] = $ext_menu[0];
    $menu_ids_actives[$i] = $ext_menu[1];
}

for($i = 0;$i<count($menu2);$i++){
    $ext_menu = explode(":",$menu2[$i]);
    $submenu_ids[$i] = $ext_menu[0];
    $submenu_ids_actives[$i] = $ext_menu[1];
}

$menuids = implode("``",$menu_ids);
$menuidsactives = implode("``",$menu_ids_actives);

$submenuids = implode("``",$submenu_ids);
$submenuidsactives = implode("``",$submenu_ids_actives);

if($cnt["cnt"] > 0){
    $sql = "update `cmap_navigator` set menu_ids = '{$menuids}', menu_ids_actives = '{$menuidsactives}', sub_ids = '{$submenuids}', sub_ids_actives = '{$submenuidsactives}', update_date = now(), update_time = now() where menu_code = '{$menu_code}' and mb_id = '{$member["mb_id"]}'";
}else if($cnt["cnt"]==0){
    $sql = "insert into `cmap_navigator` set menu_ids = '{$menuids}', menu_ids_actives = '{$menuidsactives}', sub_ids = '{$submenuids}', sub_ids_actives = '{$submenuidsactives}', update_date = now(), update_time = now() , mb_id = '{$member["mb_id"]}', menu_code='{$menu_code}' ";
}
if(sql_query($sql)){
    echo "success";
}else{
    echo "failed";
}


?>