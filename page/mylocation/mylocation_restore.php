<?php
include_once ("../../common.php");

if(!$id){
    alert("복구할 데이터를 선택해 주세요.");
}

$sql = "select * from `mylocation_save_log` where id = '{$id}'";
$cnt = sql_fetch($sql);

if($cnt["id"]==""|| $cnt==null){
    alert("복구할 데이터를 찾지 못했습니다.");
}

$sql = "select * from `cmap_my_construct_eval_temp` where id = '{$cnt["eval_id"]}'";
$tempEval = sql_fetch($sql);

$sql = "select * from `cmap_my_construct_map_temp` where id = '{$cnt["eval_id"]}'";
$tempMap = sql_fetch($sql);

//지연사항 및 체크사항
$sql = "insert into `cmap_my_construct_map_temp` (mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, temp_date, temp_time) select mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, now(), now() from `cmap_my_construct_map` where mb_id='{$member["mb_id"]}' and const_id = '{$cnt["const_id"]}'";
sql_query($sql);
$map_id = sql_insert_id();

$sql = "insert into `cmap_my_construct_eval_temp` (mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, temp_date, temp_time,pk_row_active) select mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, now(), now(), pk_row_active from `cmap_my_construct_eval` where mb_id='{$member["mb_id"]}' and const_id = '{$cnt["const_id"]}'";
sql_query($sql);
$eval_id = sql_insert_id();

$sql = "insert into `mylocation_save_log` set map_id = '{$map_id}', eval_id = '{$eval_id}', mb_id = '{$member["mb_id"]}', const_id = '{$cnt["const_id"]}',save_date = now(), save_time = now()";
sql_query($sql);

$sql = "update `cmap_my_construct_eval` set pk_ids1='{$tempEval["pk_ids1"]}',pk_score1 = '{$tempEval["pk_score1"]}', pk_score1_total = '{$tempEval["pk_score1_total"]}', pk_ids2 = '{$tempEval["pk_ids2"]}', pk_score2 = '{$tempEval["pk_score2"]}', pk_score2_total = '{$tempEval["pk_score2_total"]}' where mb_id='{$member["mb_id"]}' and const_id = '{$cnt["const_id"]}'";
sql_query($sql);

$sql = "update `cmap_my_construct_map` set pk_ids = '{$tempMap["pk_ids"]}', pk_actives = '{$tempMap["pk_actives"]}', pk_actives_date = '{$tempMap["pk_actives_date"]}', pk_ids_other = '{$tempMap["pk_ids_other"]}', pk_actives_other = '{$tempMap["pk_actives_other"]}', pk_actives_dates_other = '{$tempMap["pk_actives_dates_other"]}' where mb_id = '{$member["mb_id"]}' and const_id = '{$cnt["const_id"]}' ";
sql_query($sql);

alert("복구 되었습니다.");
