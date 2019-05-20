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
}else{//요청
    $mb = $invite["send_mb_id"];
}

$mbs = explode(',',$invite["members"]);

for($i=0;$i<count($mbs);$i++) {
    if ($mbs[$i] == $mb) {
        $sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
        sql_query($sql);
        echo "6";
        return false;
    }
}

$sql = "update `cmap_construct_invite` set msg_status = -1 where id = '{$invite_id}'";
if(sql_query($sql)) {
    //해당 현장에 members로 등록
    $members = $const["members"];
    if($members==""){
        $inmember = $mb;
    }else{
        $inmember = $members . ",". $mb;
    }

    $sql = "update `cmap_my_construct` set members = '{$inmember}' where id = '{$const_id}'";
    if(sql_query($sql)){

        $sql = "select * from `cmap_my_construct_map` where mb_id = '{$member["mb_id"]}' and const_id = '{$const_id}'";
        $map = sql_fetch($sql);
        $map_pk_ids = explode("``",$map["pk_ids"]);
        for($i=0;$i<count($map_pk_ids);$i++){
            $vpk_ids[] = $map_pk_ids[$i];
            $vpk_actives[] = 0;
            $vpk_actives_date[] = "0000-00-00";
        }

        $spk_ids = implode("``",$vpk_ids);
        $spk_actives = implode("``",$vpk_actives);
        $spk_actives_date = implode("``",$vpk_actives_date);

        $sql = "insert into `cmap_my_construct_map` set pk_ids = '{$spk_ids}',pk_actives = '{$spk_actives}', pk_actives_date = '{$spk_actives_date}', mb_id= '{$mb}', const_id = '{$const_id}'";
        sql_query($sql);

        //현장 평가 상태 등록
        //시공평가의 pk_ids 가져오기 가변값이 아니라 오류 생길 수 있음
        $sql = "select  * from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 6064 order by a.id";
        $eval1res = sql_query($sql);
        while($eval1row = sql_fetch_array($eval1res)){
            $eval1[] = $eval1row["pk_id"];
            if($eval1score==""){
                $eval1score = "0";
            }else{
                $eval1score .= "``0";
            }

        }
        $evals = implode("``",$eval1);

        //용역평가의 pk_ids 가져오기
        $sql = "select  * from `cmap_content` as a left join `cmap_depth1` as b on a.depth1_id = b.id where b.me_id = 60 and b.me_code = 60129 order by a.id";
        $eval2res = sql_query($sql);
        while($eval2row = sql_fetch_array($eval2res)){
            $eval2[] = $eval2row["pk_id"];
            if($eval2score==""){
                $eval2score = "0";
            }else{
                $eval2score .= "``0";
            }
        }
        $evals2 = implode("``",$eval2);

        $sql = "insert into `cmap_my_construct_eval` set const_id = '{$const_id}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0', pk_score2_total = '0'";
        sql_query($sql);
        
        echo "0";
    }else{
        echo "5";
        $sql = "update `cmap_construct_invite` set msg_status = 0 where id = '{$invite_id}'";
        sql_query($sql);
    }
}else{
    echo "4";
}

?>