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

$pk_ids = explode("``",$map["pk_ids"]);
$pk_actives = explode("``",$map["pk_actives"]);
$pk_actives_date = explode("``",$map["pk_actives_date"]);

for($i=0;$i<count($pk_ids);$i++){
    if($pk_ids[$i]==$pk_id){
        if($pk_actives[$i] == 1){
            $result["msg"]="4";
            echo json_encode($result);
            return false;
            break;
        }
        $pk_actives[$i] = "1";
        $pk_actives_date[$i] = date("Y-m-d");
    }
}


$pk_activess = implode("``",$pk_actives);
$pk_actives_dates = implode("``",$pk_actives_date);


$sql = "update `cmap_my_construct_map` set pk_actives = '{$pk_activess}', pk_actives_date = '{$pk_actives_dates}' where id= '{$map["id"]}'";
$result["sqls"]=$sql;
$result["pk_id"]=$pk_id;
if(sql_query($sql)){
    $result["msg"]="6";
    $result["insert_date"] = date("Y-m-d");
    echo json_encode($result);
}else{
    $result["msg"]="5";
    echo json_encode($result);
}
?>
