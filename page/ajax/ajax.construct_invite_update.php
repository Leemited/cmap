<?php
include_once ("../../common.php");

if(!$invite_id){
    echo "1";
    return false;
}
if(!$const_id){
    echo "2";
    return false;
}

$sql = "select * from `cmap_my_construct` where id = '{$const_id}'";
$const = sql_fetch($sql);

if($const["status"] != 0){
    echo "3";
    return false;
}

$sql = "select * from `cmap_construct_invite` where id = '{$invite_id}'";
$invite = sql_fetch($sql);

if($invite["msg_type"]==0){//초대
    $mb = $invite["read_mb_id"];
}else if($invite["msg_type"]==1){//요청
    $mb = $invite["send_mb_id"];
}else if($invite["msg_type"]==3){//PMMODE
    $mb = $invite["send_mb_id"];
}


$sql = "select count(*) as cnt from `cmap_my_construct` where (mb_id = '{$mb}' or INSTR(members,'{$mb}') > 0) and status = 0";
$chkcnt = sql_fetch($sql);

if($chkcnt["cnt"]>=10){
    $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
    sql_query($sql);
    echo "8";
    return false;
}

if($invite["msg_type"]!=3) {
    $mbs = explode(',', $invite["members"]);

    for ($i = 0; $i < count($mbs); $i++) {
        if ($mbs[$i] == $mb) {
            $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
            sql_query($sql);
            echo "6";
            return false;
        }
    }

    $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
    if (sql_query($sql)) {
        //해당 현장에 members로 등록
        $members = $const["members"];
        if ($members == "") {
            $inmember = $mb;
        } else {
            $inmember = $members . "," . $mb;
        }

        $sql = "update `cmap_my_construct` set members = '{$inmember}' where id = '{$const_id}'";
        if (sql_query($sql)) {

            //지연 필수 항목
            $sql = "select * from `cmap_my_construct_map` where mb_id = '{$invite["read_mb_id"]}' and const_id = '{$const_id}'";
            $map = sql_fetch($sql);
            
            if($map==null) {

            }else { // 현장 정보가 있을경우
                $map_pk_ids = explode("``", $map["pk_ids"]);
                for ($i = 0; $i < count($map_pk_ids); $i++) {
                    $vpk_ids[] = $map_pk_ids[$i];
                    $vpk_actives[] = 0;
                    $vpk_actives_date[] = "0000-00-00";
                }

                $spk_ids = implode("``", $vpk_ids);
                $spk_ids2 = implode(",", $vpk_ids);
                $spk_actives = implode("``", $vpk_actives);
                $spk_actives_date = implode("``", $vpk_actives_date);

                $sql = "insert into `cmap_my_construct_map` set pk_ids = '{$spk_ids}',pk_actives = '{$spk_actives}', pk_actives_date = '{$spk_actives_date}', mb_id= '{$mb}', const_id = '{$const_id}'";
                sql_query($sql);

                //나머지 업데이트
                $map_id = sql_insert_id();

                $sql = "select * from `cmap_content` where pk_id not in ('{$spk_ids2}')";
                $finres = sql_query($sql);
                while ($row = sql_fetch_array($finres)) {
                    $sql = "select *,b.menu_status as menu_status from `cmap_depth1` as a left `cmap_menu` as b on a.me_code = b.menu_code where a.id = '{$row["depth1_id"]}'";
                    $chk_menu = sql_fetch($sql);
                    if ($chk_menu["menu_status"] != 0) {
                        continue;
                    }
                    $map_other_pk[] = $row["pk_id"];
                    $map_other_pk_active[] = "0";
                    $map_other_pk_dates_active[] = "0000-00-00";
                }

                $map_pk_other = implode("``", $map_other_pk);
                $map_pk_active_other = implode("``", $map_other_pk_active);
                $map_pk_active_dates_other = implode("``", $map_other_pk_dates_active);

                $sql = "update `cmap_my_construct_map` set pk_ids_other = '{$map_pk_other}', pk_actives_other = '{$map_pk_active_other}',pk_actives_dates_other = '{$map_pk_active_dates_other}' where id = '{$map_id}'";
                sql_query($sql);
            }
            
            //현장 평가 상태 등록
            //시공평가의 pk_ids 가져오기 가변값이 아니라 오류 생길 수 있음
            $sql = "select *,a.pk_id as pk_id from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 6064 order by a.id";
            $eval1res = sql_query($sql);
            while ($eval1row = sql_fetch_array($eval1res)) {
                $eval1[] = $eval1row["pk_id"];
                $evalscore[] = "0";
            }
            $evals = implode("``", $eval1);
            $eval1score = implode("``", $evalscore);

            //용역평가의 pk_ids 가져오기
            $sql = "select *,a.pk_id as pk_id from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 60129 order by a.id";
            $eval2res = sql_query($sql);
            while ($eval2row = sql_fetch_array($eval2res)) {
                $eval2[] = $eval2row["pk_id"];
                $evalscore2[] = "0";
            }
            $evals2 = implode("``", $eval2);
            $eval2score = implode("``", $evalscore2);

            $maineval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$mb}' and const_id = '{$current_const["const_id"]}'");

            if ($maineval == null) {
                $sql = "insert into `cmap_my_construct_eval` set const_id = '{$current_const["const_id"]}' , mb_id ='{$mb}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0'";
            } else {
                $sql = "update `cmap_my_construct_eval` set pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0' where mb_id = '{$mb}' and const_id = '{$current_const["const_id"]}'";
            }
            sql_query($sql);


            echo "0";
        } else {
            echo "5";
            $sql = "update `cmap_construct_invite` set msg_status = 0 where id = '{$invite_id}'";
            sql_query($sql);
        }
    } else {
        echo "4";
    }
}else{
    $mbs = explode(',', $invite["manager_mb_id"]);

    for ($i = 0; $i < count($mbs); $i++) {
        if ($mbs[$i] == $mb) {
            $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
            sql_query($sql);
            echo "6";
            return false;
        }
    }

    $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
    if (sql_query($sql)) {
        //해당 현장에 Manager 등록
        $managers = $const["manager_mb_id"];
        if ($managers == "") {
            $inmngs = $mb;
        } else {
            $inmngs = $managers . "," . $mb;
        }

        $sql = "update `cmap_my_construct` set manager_mb_id = '{$inmngs}' where id = '{$const_id}'";
        if (sql_query($sql)) {
            echo "0";
        }else{
            echo "5";
            $sql = "update `cmap_construct_invite` set msg_status = 0 where id = '{$invite_id}'";
            sql_query($sql);
        }
    }
}
?>