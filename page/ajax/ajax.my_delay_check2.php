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

if($member["mb_level"]>=5){
    $result["msg"]="7";
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

$chk_pk_ids_other = explode("``",$map["pk_ids_other"]);
$chk_pk_actives_other = explode("``",$map["pk_actives_other"]);
$chk_pk_actives_dates_other = explode("``",$map["pk_actives_dates_other"]);

$chkPks = false;

for($i=0;$i<count($chk_pk_ids_other);$i++){
    if($chk_pk_ids_other[$i]==$pk_id) {
        $chkPks = true;
        if ($chk_pk_actives_other[$i] == 1) {
            $chk_pk_actives_other[$i] = "0";
            $chk_pk_actives_dates_other[$i] = "0000-00-00";
            $insert_date = '';
        } else {
            $chk_pk_actives_other[$i] = "1";
            $chk_pk_actives_dates_other[$i] = date("Y-m-d");
            $insert_date = date("Y-m-d");
        }
    }
}

if($chkPks==false) {//신규
    $chk_pk_ids_other[] = $pk_id;
    $chk_pk_actives_other[] = "1";
    $chk_pk_actives_dates_other[] = date("Y-m-d");
    $insert_date = date("Y-m-d");
}

$chk_pk_ids_otherss = implode("``",$chk_pk_ids_other);
$chk_pk_actives_otherss = implode("``",$chk_pk_actives_other);
$chk_pk_actives_dates_otherss = implode("``",$chk_pk_actives_dates_other);

$sql = "update `cmap_my_construct_map` set pk_ids_other = '{$chk_pk_ids_otherss}', pk_actives_other = '{$chk_pk_actives_otherss}', pk_actives_dates_other = '{$chk_pk_actives_dates_otherss}' where id= '{$map["id"]}'";
$result["sql"]=$sql;
if(sql_query($sql)){
    $result["msg"]="6";
    $result["insert_date"] = $insert_date;
    echo json_encode($result);
}else{
    $result["err"] = sql_query($sql);
    $result["msg"]="5";
    echo json_encode($result);
}
?>

