<?php
include_once ("../../common.php");

if(!$const_id){
    $result["msg"]="1";
    echo json_encode($result);
    return false;
}

if(!$pk_id){
    $result["msg"]="2";
    echo json_encode($result);
    return false;
}

$sql = "select * from `cmap_my_construct_map` where const_id = '{$const_id}' and mb_id ='{$member["mb_id"]}'";
$map = sql_fetch($sql);

if(is_null($map)){
    $result["msg"]="3";
    echo json_encode($result);
    return false;
}

$chk_pk_ids = explode("``",$map["pk_ids"]);
$chk_pk_actives = explode("``",$map["pk_actives"]);
$chk_pk_actives_date = explode("``",$map["pk_actives_date"]);

$chk_cont1 = count($chk_pk_ids);
$chk_cont2 = count($chk_pk_actives);
$chk_cont3 = count($chk_pk_actives_date);

if($chk_cont1 != $chk_cont2 && $chk_cont1 != $chk_cont3){
    for($i=0;$i<count($chk_pk_ids);$i++){
        $re_pk_actives[] = "0";
        $re_pk_actives_date[] = "0000-00-00";
    }
    $re_pk_activess = implode("``",$re_pk_actives);
    $re_pk_activess_date = implode("``",$re_pk_actives_date);

    $sql = "update `cmap_my_construct_map` set pk_actives = '{$re_pk_activess}', pk_actives_date = '{$re_pk_activess_date}' where const_id = '{$const_id}' and mb_id ='{$member["mb_id"]}'";
    sql_query($sql);

    $sql = "select * from `cmap_my_construct_map` where const_id = '{$const_id}' and mb_id ='{$member["mb_id"]}'";
    $map = sql_fetch($sql);

    $chk_pk_actives = explode("``",$map["pk_actives"]);
    $chk_pk_actives_date = explode("``",$map["pk_actives_date"]);
}

for($i=0;$i<count($chk_pk_ids);$i++){
    $result["pppp"][] = $chk_pk_ids[$i]."//".$pk_id;
    if($chk_pk_ids[$i]==""){continue;}
    if($chk_pk_ids[$i]==$pk_id){
        if($chk_pk_actives[$i] == 1){
            $chk_pk_actives[$i] = "0";
            $chk_pk_actives_date[$i] = "0000-00-00";
            $insert_date = "";
        }else {
            $chk_pk_actives[$i] = "1";
            $chk_pk_actives_date[$i] = date("Y-m-d");
            $insert_date = date("Y-m-d");
        }
    }
}
$inpk_activess = implode("``",$chk_pk_actives);
$inpk_actives_dates = implode("``",$chk_pk_actives_date);

$sql = "update `cmap_my_construct_map` set pk_actives = '{$inpk_activess}', pk_actives_date = '{$inpk_actives_dates}' where id= '{$map["id"]}'";
if(sql_query($sql)){
    $result["msg"]="6";
    $result["insert_date"] = $insert_date;
    echo json_encode($result);
}else{
    $result["msg"]="5";
    echo json_encode($result);
}
?>
