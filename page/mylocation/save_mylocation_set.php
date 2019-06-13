<?php
include_once ("../../common.php");

if(!$mb_id){
    alert("저장할 대상이 없습니다.");
}

if($mb_id!=$member["mb_id"]){
    alert("회원정보가 달라 수정 할 수 없습니다.");
}

if(!$constid){
    alert("현장정보가 없습니다.");
}

//지연사항 및 체크사항
$sql = "insert into `cmap_my_construct_map_temp` (mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, temp_date, temp_time) select mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other, now(), now() from `cmap_my_construct_map` where mb_id='{$mb_id}' and const_id = '{$constid}'";

sql_query($sql);
$map_id = sql_insert_id();

$sql = "insert into `cmap_my_construct_eval_temp` (mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, temp_date, temp_time,pk_row_active) select mb_id, const_id, pk_ids1, pk_score1, pk_score1_total, pk_ids2, pk_score2, pk_score2_total, now(), now(), pk_row_active from `cmap_my_construct_eval` where mb_id='{$mb_id}' and const_id = '{$constid}'";

sql_query($sql);
$eval_id = sql_insert_id();

$sql = "insert into `mylocation_save_log` set map_id = '{$map_id}', eval_id = '{$eval_id}', mb_id = '{$mb_id}', const_id = '{$constid}',save_date = now(), save_time = now()";
sql_query($sql);

alert('저장 되었습니다.');
?>