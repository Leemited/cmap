<?php
include_once ("../../common.php");

$ajax_eval1_total = 0;
$ajax_eval1_left = 15;
$ajax_eval1_class = "";
$ajax_eval2_total = 0;
$ajax_eval2_left = 15;
$ajax_eval2_class = "";

//평가
$ajax_eval = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$constid}' and mb_id ='{$member["mb_id"]}'");

if($ajax_eval==null || ($ajax_eval["pk_ids1"] == "" && $ajax_eval["pk_score1"]=="")||($ajax_eval["pk_ids2"] == "" && $ajax_eval["pk_score2"]=="")){
    //현장 평가 상태 등록
    //시공평가의 pk_ids 가져오기 가변값이 아니라 오류 생길 수 있음
    $sql = "select *,a.pk_id as pk_id from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 6064 order by a.id";
    $eval1res = sql_query($sql);
    while($eval1row = sql_fetch_array($eval1res)){
        $eval1[] = $eval1row["pk_id"];
        $evalscore[] = "0";
    }
    $evals = implode("``",$eval1);
    $eval1score = implode("``",$evalscore);

    //용역평가의 pk_ids 가져오기
    $sql = "select *,a.pk_id as pk_id from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 60129 order by a.id";
    $eval2res = sql_query($sql);
    while($eval2row = sql_fetch_array($eval2res)){
        $eval2[] = $eval2row["pk_id"];
        $evalscore2[] = "0";
        /*if($eval2score==""){
            $eval2score = "0";
        }else{
            $eval2score .= "``0";
        }*/
    }
    $evals2 = implode("``",$eval2);
    $eval2score = implode("``",$evalscore2);

    if($eval==null) {
        $sql = "insert into `cmap_my_construct_eval` set const_id = '{$constid}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0'";
    }else{
        $sql = "update `cmap_my_construct_eval` set const_id = '{$constid}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0' where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'";
    }
    sql_query($sql);

    $ajax_eval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'");
}

//시공 토탈
$ajax_evals1 = explode("``",$ajax_eval["pk_score1_total"]);
for($i=0;$i<count($ajax_evals1);$i++){
    $ajax_eval1_sum += (double)$ajax_evals1[$i];
}
$ajax_eval1_total = ceil($ajax_eval1_sum / 100 * 100);
if($ajax_eval1_total<80) {
    if($ajax_eval1_total >= 0 && $ajax_eval1_total < 10){
        $ajax_eval1_left = 15;
    }
    if($ajax_eval1_total >= 10 && $ajax_eval1_total < 20){
        $ajax_eval1_left = 18;
    }
    if($ajax_eval1_total >= 20 && $ajax_eval1_total < 30){
        $ajax_eval1_left = 22;
    }
    if($ajax_eval1_total >= 30 && $ajax_eval1_total < 40){
        $ajax_eval1_left = 25;
    }
    if($ajax_eval1_total >= 40 && $ajax_eval1_total < 50){
        $ajax_eval1_left = 29;
    }
    if($ajax_eval1_total >= 50 && $ajax_eval1_total < 60){
        $ajax_eval1_left = 32;
    }
    if($ajax_eval1_total >= 60 && $ajax_eval1_total < 70){
        $ajax_eval1_left = 36;
    }
    if($ajax_eval1_total >= 70 && $ajax_eval1_total < 80){
        $ajax_eval1_left = 40;
    }
}else{
    if($ajax_eval1_total >= 80 && $ajax_eval1_total < 90){
        switch ($ajax_eval1_total){
            case 80:
                $ajax_eval1_left = 41;
                break;
            case 81:
                $ajax_eval1_left = 44;
                break;
            case 82:
                $ajax_eval1_left = 48;
                break;
            case 83:
                $ajax_eval1_left = 51;
                break;
            case 84:
                $ajax_eval1_left = 55;
                break;
            case 85:
                $ajax_eval1_left = 58;
                break;
            case 86:
                $ajax_eval1_left = 62;
                break;
            case 87:
                $ajax_eval1_left = 65;
                break;
            case 88:
                $ajax_eval1_left = 69;
                break;
            case 89:
                $ajax_eval1_left = 73;
                break;
        }
        $ajax_eval1_class = "level2";
    }else if($ajax_eval1_total >= 90){
        switch ($ajax_eval1_total){
            case 90:
                $ajax_eval1_left = 74;
                break;
            case 91:
                $ajax_eval1_left = 76;
                break;
            case 92:
                $ajax_eval1_left = 79;
                break;
            case 93:
                $ajax_eval1_left = 81;
                break;
            case 94:
                $ajax_eval1_left = 84;
                break;
            case 95:
                $ajax_eval1_left = 87;
                break;
            case 96:
                $ajax_eval1_left = 89;
                break;
            case 97:
                $ajax_eval1_left = 92;
                break;
            case 98:
                $ajax_eval1_left = 94;
                break;
            case 99:
                $ajax_eval1_left = 97;
                break;
            case 100:
                $ajax_eval1_left = 100;
                break;
        }
        if($ajax_eval1_total>100){
            $ajax_eval1_left = 100;
        }
        $ajax_eval1_class = "level3";
    }
}
//용역 토탈
$ajax_evals2 = explode("``",$ajax_eval["pk_score2_total"]);
for($i=0;$i<count($ajax_evals2);$i++){
    if($i < 2) {
        if($main_evals2[$i]!=0) {
            $eval2_sum += (double)$main_evals2[$i];
        }else{
            $eval2_sum += 0;
        }
    }else if($i > 2 && $i != 6){
        if($i > 2 && $i < 6){
            if($main_evals2[$i]!=0) {
                $eval3_1_sum += (double)$main_evals2[$i];
            }else{
                $eval3_1_sum += 0;
            }
        }
        if($i == 7){
            if($main_evals2[$i]!=0) {
                $eval3_2_sum += (double)$main_evals2[$i];
            }else{
                $eval3_2_sum += 0;
            }
        }
    }else if($i == 6){
        if($main_evals2[$i]!=0) {
            $eval4_sum += (double)$main_evals2[$i];
        }else{
            $eval4_sum += 0;
        }
    }
    $eval2_sum_total = ($eval2_sum * 0.8) + (((($eval3_1_sum * 0.8) + $eval3_2_sum) + $eval4_sum) * 0.2);
}
$ajax_eval2_total = ceil($eval2_sum_total / 100 * 100);
if($ajax_eval2_total<80) {
    if($ajax_eval2_total >= 0 && $ajax_eval2_total < 10){
        $ajax_eval2_left = 15;
    }
    if($ajax_eval2_total >= 10 && $ajax_eval2_total < 20){
        $ajax_eval2_left = 18;
    }
    if($ajax_eval2_total >= 20 && $ajax_eval2_total < 30){
        $ajax_eval2_left = 22;
    }
    if($ajax_eval2_total >= 30 && $ajax_eval2_total < 40){
        $ajax_eval2_left = 25;
    }
    if($ajax_eval2_total >= 40 && $ajax_eval2_total < 50){
        $ajax_eval2_left = 29;
    }
    if($ajax_eval2_total >= 50 && $ajax_eval2_total < 60){
        $ajax_eval2_left = 32;
    }
    if($ajax_eval2_total >= 60 && $ajax_eval2_total < 70){
        $ajax_eval2_left = 36;
    }
    if($ajax_eval2_total >= 70 && $ajax_eval2_total < 80){
        $ajax_eval2_left = 40;
    }
}else{
    if($ajax_eval2_total >= 80 && $ajax_eval2_total < 90){
        switch ($ajax_eval2_total){
            case 80:
                $ajax_eval2_left = 41;
                break;
            case 81:
                $ajax_eval2_left = 44;
                break;
            case 82:
                $ajax_eval2_left = 48;
                break;
            case 83:
                $ajax_eval2_left = 51;
                break;
            case 84:
                $ajax_eval2_left = 55;
                break;
            case 85:
                $ajax_eval2_left = 58;
                break;
            case 86:
                $ajax_eval2_left = 62;
                break;
            case 87:
                $ajax_eval2_left = 65;
                break;
            case 88:
                $ajax_eval2_left = 69;
                break;
            case 89:
                $ajax_eval2_left = 73;
                break;
        }
        $ajax_eval2_class = "level2";
    }else if($ajax_eval2_total >= 90){
        switch ($ajax_eval2_total){
            case 90:
                $ajax_eval2_left = 74;
                break;
            case 91:
                $ajax_eval2_left = 76;
                break;
            case 92:
                $ajax_eval2_left = 79;
                break;
            case 93:
                $ajax_eval2_left = 81;
                break;
            case 94:
                $ajax_eval2_left = 84;
                break;
            case 95:
                $ajax_eval2_left = 87;
                break;
            case 96:
                $ajax_eval2_left = 89;
                break;
            case 97:
                $ajax_eval2_left = 92;
                break;
            case 98:
                $ajax_eval2_left = 94;
                break;
            case 99:
                $ajax_eval2_left = 97;
                break;
            case 100:
                $ajax_eval2_left = 100;
                break;
        }
        if($ajax_eval2_total>100){
            $ajax_eval2_left = 100;
        }
        $ajax_eval2_class = "level3";
    }
}

$result["ajax_eval1_total"]  =   $ajax_eval1_total;
$result["ajax_eval1_left"]   =   $ajax_eval1_left;
$result["ajax_eval1_class"]  =   $ajax_eval1_class;
$result["ajax_eval2_total"]  =   $ajax_eval2_total;
$result["ajax_eval2_left"]   =   $ajax_eval2_left;
$result["ajax_eval2_class"]  =   $ajax_eval2_class;

echo json_encode($result);