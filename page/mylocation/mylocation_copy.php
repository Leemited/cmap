<?php
include_once ("../../common.php");

if(!$mb_id){
    alert("복사할 대상이 없습니다.");
}

if(!$const_id){
    alert("선택된 현장정보가 없습니다.");
}

$sql = "select * from `cmap_my_construct_eval` where mb_id = '{$mb_id}' and const_id = '{$const_id}'";
$copyEval = sql_fetch($sql);

$sql = "select * from `cmap_my_construct_map` where mb_id = '{$mb_id}' and const_id = '{$const_id}'";
$copyMap = sql_fetch($sql);

//지연사항 및 체크사항
$sql = "insert into `cmap_my_construct_map_temp` (mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, temp_date, temp_time) select mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, now(), now() from `cmap_my_construct_map` where mb_id='{$member["mb_id"]}' and const_id = '{$const_id}'";
sql_query($sql);
$map_id = sql_insert_id();

$sql = "insert into `cmap_my_construct_eval_temp` (mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, temp_date, temp_time,pk_row_active) select mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, now(), now(), pk_row_active from `cmap_my_construct_eval` where mb_id='{$member["mb_id"]}' and const_id = '{$const_id}'";
sql_query($sql);
$eval_id = sql_insert_id();

$sql = "insert into `mylocation_save_log` set map_id = '{$map_id}', eval_id = '{$eval_id}', mb_id = '{$member["mb_id"]}', const_id = '{$const_id}',save_date = now(), save_time = now()";
sql_query($sql);

$sql = "update `cmap_my_construct_eval` set pk_ids1='{$copyEval["pk_ids1"]}',pk_score1 = '{$copyEval["pk_score1"]}', pk_score1_total = '{$copyEval["pk_score1_total"]}', pk_ids2 = '{$copyEval["pk_ids2"]}', pk_score2 = '{$copyEval["pk_score2"]}', pk_score2_total = '{$copyEval["pk_score2_total"]}' where mb_id='{$member["mb_id"]}' and const_id = '{$const_id}'";
sql_query($sql);

$sql = "update `cmap_my_construct_map` set pk_ids = '{$copyMap["pk_ids"]}', pk_actives = '{$copyMap["pk_actives"]}', pk_actives_date = '{$copyMap["pk_actives_date"]}', pk_ids_other = '{$copyMap["pk_ids_other"]}', pk_actives_other = '{$copyMap["pk_actives_other"]}', pk_actives_dates_other = '{$copyMap["pk_actives_dates_other"]}' where mb_id = '{$member["mb_id"]}' and const_id = '{$const_id}' ";
sql_query($sql);

alert("복사되었습니다.");
?>