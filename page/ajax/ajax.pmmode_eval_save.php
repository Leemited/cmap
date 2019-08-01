<?php
include_once ("../../common.php");

$sql = "select * from `cmap_pmmode_save` where mb_id ='{$member["mb_id"]}' and constid = '{$constid}' and eval_type = '{$eval_type}'";
$chk = sql_fetch($sql);
if($chk!=null){
    $sql = "update `cmap_pmmode_save` set {$type} = '{$val}' where  mb_id = '{$member["mb_id"]}' and constid = '{$constid}' and eval_type = '{$eval_type}'";
}else{
    $sql = "insert into `cmap_pmmode_save` set {$type} = '{$val}' , mb_id = '{$member["mb_id"]}', constid = '{$constid}', eval_type = '{$eval_type}'";
}
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}
?>