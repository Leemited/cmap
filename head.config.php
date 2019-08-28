<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($is_member && $member["mb_auth"]==true) {
    //내 현장 목록
    $managerconst=0;
    if($member["mb_level"]==5){
        $res = sql_query("select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}') != 0  and status = 0 order by id desc");
    }else {
        $res = sql_query("select * from `cmap_my_construct` where (mb_id ='{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}') != 0 ) and status = 0 order by id desc");
    }
    while ($row = sql_fetch_array($res)) {
        if($row["members"]!="" && $row["mb_id"]!=$member["mb_id"]) {//내가 등록한게 아니고 맴버로 등록되었을 경우
            $mems = explode(",", $row["members"]);
            $chkMem = 0;//기본 등록아닌상태에서
            for($i=0;$i<count($mems);$i++){
                if($mems[$i]==$member["mb_id"]){
                    $chkMem = 1;//등록된상태 업데이트
                    continue;
                }
            }

            if($chkMem == 0){//최종 등록이 아니므로 while문 패스
                continue;
            }
        }
        $mycont[] = $row;
        $mycontid[] = $row["id"];
        if($row["manager_mb_id"]==$member["mb_id"]){
            $managerconst++;
        }
    }

    if($current_const["const_id"]!="" && $current_const["const_id"] != 0){//현장 저장됨
        $com_where = " and const_id = '{$current_const["const_id"]}'";
        $com_where2 = " and construct_id = '{$current_const["const_id"]}'";
    }else{//저장된 현장 없음
        if(count($mycont)==0){//등록된 현장없음

        }else{
            $current_const["const_id"] = $mycont[0]["id"];
            $com_where = " and const_id = '{$current_const["const_id"]}'";
            $com_where2 = " and construct_id = '{$current_const["const_id"]}'";
        }
    }

    //스케쥴 가져오기
    $sch_today = date("Y-m-d");
    $res = sql_query("select * from `cmap_myschedule` as s left join `cmap_my_construct` as c on s.construct_id = c.id where schedule_date = '{$sch_today}' and (c.mb_id = '{$member["mb_id"]}' or instr(c.members,'{$member["mb_id"]}') > 0 or instr(c.manager_mb_id,'{$member["mb_id"]}') > 0) {$com_where2} order by s.id limit 0, 6");

    while ($row = sql_fetch_array($res)) {
        $myschedule[] = $row;
    }

    //my현장 신청 관리
    //if($com_where){//현장이 있는경우
    //    $invitewhere = " ((read_mb_id = '{$member["mb_id"]}' or send_mb_id = '{$member["mb_id"]}') and msg_status = 0 $com_where ) or (read_mb_id = '{$member["mb_id"]}' and msg_status = 0)";
    //}else{
    $invitewhere = "(read_mb_id = '{$member["mb_id"]}' or send_mb_id = '{$member["mb_id"]}') and msg_status = 0";
    //}
    if($member["mb_level"]==5){
        $invitewhere .= " and msg_type = 3";
    }
    $reqsql = "select * from `cmap_construct_invite` where {$invitewhere} ";
    $reqres = sql_query($reqsql);
    while($reqrow = sql_fetch_array($reqres)){
        $reqlist[] = $reqrow;
    }

    //my현장 업무연락서
    $msgsql = "select read_mb_id from `cmap_construct_work_msg` where read_status = 0 ";
    $msgres = sql_query($msgsql);
    while($msgrow = sql_fetch_array($msgres)){
        $reads = explode(",",$msgrow["read_mb_id"]);
        if(count($reads)>0) {
            for ($i = 0; $i < count($reads); $i++) {
                if ($member["mb_id"]==$reads[$i]) {
                    $msglist[] = $reads[$j];
                }
            }
        }
    }

    //제출 지연 현황
    $delay_now = date("Y-m-d");
    if(!$com_where2){
        $delaylist = null;
    }else {
        if($member["mb_level"]==5){
            $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'";
            $ss = sql_fetch($sql);
            if($ss!=null){
                $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$current_const["const_id"]}'";
            }else{
                $sql = "select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'";
                $ss2 = sql_fetch($sql);
                $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss2["mb_id"]}' and const_id = '{$current_const["const_id"]}'";
            }
        }else {
            $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'";
        }
        $activechk = sql_fetch($activesql);
        if($activechk==null || ($activechk["pk_ids"]=="" && $activechk != null) || ($activechk["pk_ids_other"]=="" && $activechk != null)){
            //신규 등록해줘야됨
            //다른현장이 있다면 pk만 가져옴?? 가져와도 되나??
            //없으면 스케쥴 등록 안한것도 넣어줘야됨
            //그냥 경고후 현장 수정화면으로 이동
            $const = sql_fetch("select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'");
            if(strpos($_SERVER["REQUEST_URI"],"/page/mylocation/")!==false) {

            }else {
                if($member["mb_id"]==$const["mb_id"]) {
                    if($activechk==null) {
                        alert($const["cmap_name"] . "의 지연현황 데이터에 오류가 있습니다.\\r\\n현장수정을 통해 갱신이 필요합니다.", G5_URL . "/page/mylocation/mylocation_edit2?type=edit&constid=" . $current_const["const_id"]);
                    }
                }else{
                    $sql = "select * from `cmap_my_construct_map` where mb_id = '{$const["mb_id"]}' and const_id = '{$const["id"]}'";
                    $chkMap = sql_fetch($sql);
                    if($chkMap!=null){
                        $sql = "insert into `cmap_my_construct_map` (mb_id, const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other) select '{$member["mb_id"]}', const_id, pk_ids, pk_actives, pk_actives_date, pk_ids_other, pk_actives_other, pk_actives_dates_other from `cmap_my_construct_map` where mb_id = '{$const["mb_id"]}' and const_id = '{$const["id"]}'";
                        sql_query($sql);
                    }else{
                        //현장개설자가 데이터가 없을떄???
                        //강제로 현장개설자에 데이터 등록?
                        $sql = "select pk_id from `cmap_schedule` where construct_id = '{$cunst['id']}'";
                        $res = sql_query($sql);
                        while($row = sql_fetch_array($res)){

                        }
                    }
                }
            }
        }else {
            $map_pk_id = explode("``", $activechk["pk_ids"]);
            $map_pk_actives = explode("``", $activechk["pk_actives"]);
            $map_pk_actives_date = explode("``", $activechk["pk_actives_date"]);

            $delaysql = "select * from `cmap_myschedule` where construct_id = '{$current_const["const_id"]}' and schedule_date < '{$delay_now}' and pk_id <> '' order by schedule_date desc";
            $delayres = sql_query($delaysql);
            while ($delayrow = sql_fetch_array($delayres)) {
                $pk_ids = explode("``", $delayrow["pk_id"]);

                $diff = strtotime($delay_now) - strtotime($delayrow["schedule_date"]);

                $days = $diff / (60 * 60 * 24);
                for ($i = 0; $i < count($pk_ids); $i++) {
                    for ($j = 0; $j < count($map_pk_id); $j++) {
                        if ($pk_ids[$i] == $map_pk_id[$j]) {
                            if ($map_pk_actives[$j] == 0) {
                                $sql = "select *,d.pk_id as pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id,a.depth_name as depth1_name,d.depth_name as depth_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                                $dd = sql_fetch($sql);
                                if(substr($dd["me_code"],0,2) != 10) {
                                    if (strpos($ssid, $dd["pk_id"]) !== false) {
                                        continue;
                                    }
                                }
                                $ssid .= ',' . $dd["pk_id"];
                                $delaylist[$pk_ids[$i]] = $dd;
                                $delaylist[$pk_ids[$i]]["delay_date"] = "-" . $days;
                                $delaylist[$pk_ids[$i]]["schedule_date"] = $delayrow["schedule_date"];
                                $delayhead[$dd["depth1_pk_id"]] = true;
                                $delayhead2[$dd["me_code"]] = true;
                            }
                        }
                    }
                }
            }
        }
    }
    if(count($delaylist)>0){
        $maindelaylists = array_values($delaylist);
        $maindelaylists = arr_sort($maindelaylists, "delay_date", "asc");
    }

    //평가
    if($current_const["const_id"]) {
        if ($member["mb_level"] == 5) {
            $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'";
            $ss = sql_fetch($sql);
            if ($ss != null) {
                $maineval = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$current_const["const_id"]}' and mb_id ='{$ss["set_mb_id"]}'");
            } else {
                $sql = "select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'";
                $ss2 = sql_fetch($sql);
                $maineval = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$current_const["const_id"]}' and mb_id ='{$ss2["mb_id"]}'");
            }
        } else {
            $maineval = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$current_const["const_id"]}' and mb_id ='{$member["mb_id"]}'");
        }
        if ($maineval == null || ($maineval["pk_ids1"] == "" && $maineval["pk_score1"] == "") || ($maineval["pk_ids2"] == "" && $maineval["pk_score2"] == "")) {
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

            $maineval = sql_fetch("select * from `cmap_my_construct_eval` where mb_id = '{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'");
            if ($maineval == null) {
                $sql = "insert into `cmap_my_construct_eval` set const_id = '{$current_const["const_id"]}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0'";
            } else {
                $sql = "update `cmap_my_construct_eval` set pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0``0``0', pk_score2_total = '0``0``0``0``0``0``0``0' where mb_id = '{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'";
            }
            //echo $sql;
            sql_query($sql);

        }
    }
    //시공 토탈
    $main_evals1 = explode("``",$maineval["pk_score1_total"]);
    for($i=0;$i<count($main_evals1);$i++){
        $eval1_sum += (double)$main_evals1[$i];
    }
    $eval1_total = ceil($eval1_sum / 100 * 100);
    if($eval1_total<80) {
        if($eval1_total >= 0 && $eval1_total < 10){
            $eval1_left = 15;
        }
        if($eval1_total >= 10 && $eval1_total < 20){
            $eval1_left = 18;
        }
        if($eval1_total >= 20 && $eval1_total < 30){
            $eval1_left = 22;
        }
        if($eval1_total >= 30 && $eval1_total < 40){
            $eval1_left = 25;
        }
        if($eval1_total >= 40 && $eval1_total < 50){
            $eval1_left = 29;
        }
        if($eval1_total >= 50 && $eval1_total < 60){
            $eval1_left = 32;
        }
        if($eval1_total >= 60 && $eval1_total < 70){
            $eval1_left = 36;
        }
        if($eval1_total >= 70 && $eval1_total < 80){
            $eval1_left = 40;
        }
    }else{
        if($eval1_total >= 80 && $eval1_total < 90){
            switch ($eval1_total){
                case 80:
                    $eval1_left = 41;
                    break;
                case 81:
                    $eval1_left = 44;
                    break;
                case 82:
                    $eval1_left = 48;
                    break;
                case 83:
                    $eval1_left = 51;
                    break;
                case 84:
                    $eval1_left = 55;
                    break;
                case 85:
                    $eval1_left = 58;
                    break;
                case 86:
                    $eval1_left = 62;
                    break;
                case 87:
                    $eval1_left = 65;
                    break;
                case 88:
                    $eval1_left = 69;
                    break;
                case 89:
                    $eval1_left = 73;
                    break;
            }
            $evel1_class = "level2";
        }else if($eval1_total >= 90){
            switch ($eval1_total){
                case 90:
                    $eval1_left = 74;
                    break;
                case 91:
                    $eval1_left = 76;
                    break;
                case 92:
                    $eval1_left = 79;
                    break;
                case 93:
                    $eval1_left = 81;
                    break;
                case 94:
                    $eval1_left = 84;
                    break;
                case 95:
                    $eval1_left = 87;
                    break;
                case 96:
                    $eval1_left = 89;
                    break;
                case 97:
                    $eval1_left = 92;
                    break;
                case 98:
                    $eval1_left = 94;
                    break;
                case 99:
                    $eval1_left = 97;
                    break;
                case 100:
                    $eval1_left = 100;
                    break;
            }
            if($eval1_total>100){
                $eval1_left = 100;
            }
            $evel1_class = "level3";
        }
    }
    //용역 토탈
    $main_evals2 = explode("``",$maineval["pk_score2_total"]);
    for($i=0;$i<count($main_evals2);$i++){
        if($i <= 2) {
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
    }
    $eval2_sum_total = ($eval2_sum * 0.8) + (((($eval3_1_sum * 0.8) + $eval3_2_sum) + $eval4_sum) * 0.2);
    $eval2_total = ceil($eval2_sum_total / 100 * 100);
    if($eval2_total<80) {
        if($eval2_total >= 0 && $eval2_total < 10){
            $eval2_left = 15;
        }
        if($eval2_total >= 10 && $eval2_total < 20){
            $eval2_left = 18;
        }
        if($eval2_total >= 20 && $eval2_total < 30){
            $eval2_left = 22;
        }
        if($eval2_total >= 30 && $eval2_total < 40){
            $eval2_left = 25;
        }
        if($eval2_total >= 40 && $eval2_total < 50){
            $eval2_left = 29;
        }
        if($eval2_total >= 50 && $eval2_total < 60){
            $eval2_left = 32;
        }
        if($eval2_total >= 60 && $eval2_total < 70){
            $eval2_left = 36;
        }
        if($eval2_total >= 70 && $eval2_total < 80){
            $eval2_left = 40;
        }
    }else{
        if($eval2_total >= 80 && $eval2_total < 90){
            switch ($eval2_total){
                case 80:
                    $eval2_left = 41;
                    break;
                case 81:
                    $eval2_left = 44;
                    break;
                case 82:
                    $eval2_left = 48;
                    break;
                case 83:
                    $eval2_left = 51;
                    break;
                case 84:
                    $eval2_left = 55;
                    break;
                case 85:
                    $eval2_left = 58;
                    break;
                case 86:
                    $eval2_left = 62;
                    break;
                case 87:
                    $eval2_left = 65;
                    break;
                case 88:
                    $eval2_left = 69;
                    break;
                case 89:
                    $eval2_left = 73;
                    break;
            }
            $evel2_class = "level2";
        }else if($eval2_total >= 90){
            switch ($eval2_total){
                case 90:
                    $eval2_left = 74;
                    break;
                case 91:
                    $eval2_left = 76;
                    break;
                case 92:
                    $eval2_left = 79;
                    break;
                case 93:
                    $eval2_left = 81;
                    break;
                case 94:
                    $eval2_left = 84;
                    break;
                case 95:
                    $eval2_left = 87;
                    break;
                case 96:
                    $eval2_left = 89;
                    break;
                case 97:
                    $eval2_left = 92;
                    break;
                case 98:
                    $eval2_left = 94;
                    break;
                case 99:
                    $eval2_left = 97;
                    break;
                case 100:
                    $eval2_left = 100;
                    break;
            }
            if($eval2_total>100){
                $eval2_left = 100;
            }
            $evel2_class = "level3";
        }
    }

}
//navigator
$navisql = "select * from `cmap_menu` where menu_status = 0 and menu_depth = 0 order by menu_order ";
$navires = sql_query($navisql);
while($navirow=sql_fetch_array($navires)){
    $menulist[] = $navirow;
}

$sql = "select * from `cmap_menu` where LENGTH(menu_code)=2 and menu_status = 0 order by menu_order asc; ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $search_menu[] = $row;
}
if(substr($me_id,0,2)==50){
    $sql = "select * from `cmap_menu_desc` where depth_id = '{$depth2_id}' ";
    $useguide = sql_fetch($sql);
}else {
    $sql = "select * from `cmap_menu_desc` as a left join `cmap_depth1` as b on a.pk_id = b.pk_id where b.id = '{$depth1_id}' ";
    $useguide = sql_fetch($sql);
}

if($mypage==true){
    $sql = "select * from `cmap_menu_desc` where isnull(pk_id) and isnull(depth) and isnull(depth_id)";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $useguide[$row["menu_id"]] = $row;
    }
}

if($bo_table){
    $mypage=true;
    $sql = "select * from `cmap_menu_desc` where isnull(pk_id) and isnull(depth) and isnull(depth_id)";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $useguide[$row["menu_id"]] = $row;
    }
    switch ($bo_table){
        case "databoard":
            $menu_id = "depth_desc_cmap";
            break;
        case "boards":
            $menu_id = "depth_desc_boards";
            break;
        case "review":
            $menu_id = "depth_desc_review";
            break;
        case "free":
            $menu_id = "depth_desc_com";
            break;
    }
}

$select_const = sql_fetch("select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'");
$menuchk = explode("``",$select_const["pk_ids"]);
$menuchk_actives = explode("``",$select_const["pk_ids_actives"]);
for($i=0;$i<count($menuchk);$i++){
    $menuchk_act[$menuchk[$i]] = $menuchk_actives[$i];
}

//$sql = "select * from `cmap_menu_desc` where id = '{$depth1_id}' ";

$sql = "select *,count(search_text) as cnt from `cmap_search_log` where search_text != '' group by search_text order by cnt desc limit 0, 16";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $papular[] = $row;
}
$todays = date("Y-m-d");
$sql = "select * from `cmap_payments` where mb_id = '{$member["mb_id"]}' and order_cancel = 0 and payment_end_date >= '{$todays}' order by payment_end_date desc limit 0 , 1";
$mypayments = sql_fetch($sql);

if($is_admin) {
//결제요청
    $sql = "select count(*)as cnt from `cmap_inquiry` where `inquiry_type` = '결제문의' and `status` = 0";
    $inquirys = sql_fetch($sql);
}
?>