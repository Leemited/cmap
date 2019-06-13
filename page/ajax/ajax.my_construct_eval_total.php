<?php
include_once ("../../common.php");

$eval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'");
if($page==1) {
    $pk_ids_score = explode("``",$eval["pk_score1_total"]);
    if ($step == 1) {//공사관리
        $pk_ids_score[0] = (double)$total;
    }
    if ($step == 2) {//품질 및 성능
        $pk_ids_score[1] = (double)$total;
    }
    if ($step == 3) {//가감점
        $pk_ids_score[2] = (double)$total;
    }

    $in_total = implode("``",$pk_ids_score);

    $sql = "update `cmap_my_construct_eval` set pk_score1_total = '{$in_total}' where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'";
    sql_query($sql);
}else{
    $pk_ids_score = explode("``",$eval["pk_score2_total"]);
    if ($step == 1) {//조직 및 운영
        $pk_ids_score[0] = (double)$total;
    }
    if ($step == 2) {//현장업무 지원
        $pk_ids_score[1] = (double)$total;
    }
    if ($step == 3) {//기술지원
        $pk_ids_score[2] = (double)$total;
    }
    if ($step == 4) {//일반행정
        $pk_ids_score[3] = (double)$total;
    }
    if ($step == 5) {//시공관리
        $pk_ids_score[4] = (double)$total;
    }
    if ($step == 6) {//기술업무
        $pk_ids_score[5] = (double)$total;
    }
    if ($step == 7) {//가감점
        $pk_ids_score[6] = (double)$total;
    }
    if ($step == 8) {//시공상태
        $pk_ids_score[7] = (double)$total;
    }

    $in_total = implode("``",$pk_ids_score);

    $sql = "update `cmap_my_construct_eval` set pk_score2_total = '{$in_total}' where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'";
    sql_query($sql);
}
?>