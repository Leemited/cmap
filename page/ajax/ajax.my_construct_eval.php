<?php
include_once ("../../common.php");

if(!$constid){
    $result["msg"] = "1";
    echo json_encode($result);
    return false;
}

if(!$pk_id){
    $result["msg"] = "2";
    echo json_encode($result);
    return false;
}

$eval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'");

if($eval==null || ($eval["pk_ids1"] == "" && $eval["pk_score1"]=="")||($eval["pk_ids2"] == "" && $eval["pk_score2"]=="")){
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
    }
    $evals2 = implode("``",$eval2);
    $eval2score = implode("``",$evalscore2);

    if($eval==null) {
        $sql = "insert into `cmap_my_construct_eval` set const_id = '{$constid}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0', pk_row_active = '0'";
    }else{
        $sql = "update `cmap_my_construct_eval` set const_id = '{$constid}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0',pk_row_active = '0' where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'";
    }
    sql_query($sql);

    $eval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'");
}

if($page==1){
    $pk_ids = explode("``",$eval["pk_ids1"]);
    $pk_ids_score = explode("``",$eval["pk_score1"]);

    switch ($num){
        case "0":
            $nums = 1;
            break;
        case "1":
            $nums = 0.8;
            break;
        case "2":
            $nums = 0.6;
            break;
        case "3":
            $nums = 0.4;
            break;
    }

    //계산하기
    $score = round($score_cnt * $nums, 2);

    if($pk_id == "21736"){
        if($num==0){
            $score = 1.5;
        }else{
            $score = 0;
        }
    }
    if($pk_id == "21737"){
       if($num==3){
            $score = 0;
       }
    }
    if($pk_id == "21738"){
        if($num==0){
            $score = -10;
        }else{
            $score = 0;
        }
    }

    $result["score"] = $score;

    for($i=0;$i<count($pk_ids);$i++){
        if($pk_ids[$i]==$pk_id){
            $pk_ids_score[$i] = $score;
        }
    }

    $inscore = implode("``",$pk_ids_score);

    $sql = "update `cmap_my_construct_eval` set pk_score1 = '{$inscore}' where id = '{$eval["id"]}'";
    if(sql_query($sql)){
        $result["msg"] = "0";
    }else{
        $result["msg"] = "3";
    }
}else{
    $pk_ids = explode("``",$eval["pk_ids2"]);
    $pk_ids_score = explode("``",$eval["pk_score2"]);

    switch ($num){
        case "0":
            $nums = 1;
            break;
        case "1":
            $nums = 0.9;
            break;
        case "2":
            $nums = 0.8;
            break;
        case "3":
            $nums = 0.7;
            break;
        case "4":
            $nums = 0.6;
            break;
    }

    //계산하기
    $score = round($score_cnt * $nums,2);

    //특별 조건 IOS 획득여부
    if($pk_id == "22078" && $num == 1){
        $score = 0;
    }

    if($direct_point != null){
        $score = $direct_point;
    }

    $result["score"] = $score;

    for($i=0;$i<count($pk_ids);$i++){
        if($pk_ids[$i]==$pk_id){
            $pk_ids_score[$i] = $score;
        }
    }

    $inscore = implode("``",$pk_ids_score);

    if($pk_id=="22089"){
        $pk_row_active = 0;
    }else if($pk_id=="22090"){
        $pk_row_active = 1;
    }

    $sql = "update `cmap_my_construct_eval` set pk_score2 = '{$inscore}', pk_row_active = '{$pk_row_active}' where id = '{$eval["id"]}'";
    if(sql_query($sql)){
        $result["msg"] = "0";
    }else{
        $result["msg"] = "3";
    }

}

echo json_encode($result);

?>