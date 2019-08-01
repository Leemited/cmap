<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');


if($is_member) {
    //내 현장 목록
    $managerconst=0;
    if($member["mb_level"]==5){
        $res = sql_query("select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}') != 0  and status = 0 order by id desc");
    }else {
        $res = sql_query("select * from `cmap_my_construct` where (mb_id ='{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}') != 0 ) and status = 0 order by id desc");
    }
    while ($row = sql_fetch_array($res)) {
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
    $res = sql_query("select * from `cmap_myschedule` as s left join `cmap_my_construct` as c on s.construct_id = c.id where schedule_date = '{$sch_today}' and (mb_id = '{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}') > 0 or instr(manager_mb_id,'{$member["mb_id"]}') > 0) {$com_where2} order by id limit 0, 6");

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
    $msgsql = "select * from `cmap_construct_work_msg` where (instr(read_mb_id,'{$member["mb_id"]}') != 0 or send_mb_id = '{$member["mb_id"]}') and read_status = 0 ";
    $msgres = sql_query($msgsql);
    while($msgrow = sql_fetch_array($msgres)){
        $reads = explode(",",$msgrow["read_mb_id"]);
        $chk==true;
        for($i=0;$i<count($reads);$i++){
            if($reads[$i]!=$member["mb_id"]){
                $chk==false;
            }
        }
        if($member["mb_id"]==$msgrow["send_mb_id"]){
            $chk = true;
        }
        if($chk) {
            $msglist[] = $msgrow;
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
                                $sql = "select *,c.pk_id as pk_id,d.pk_id as depth4_pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id,a.depth_name as depth1_name,d.depth_name as depth_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                                $dd = sql_fetch($sql);
                                if (strpos($ssid, $dd["pk_id"]) !== false) {
                                    continue;
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

?>

<!-- 로그인 -->
<?php if(defined('_INDEX_')) {?>
<div class="login <?php if(!defined('_INDEX_')) {?>sub<?php }?>">
    <div class="login_btns">
        <?php if(!$is_member){?>
            <img src="<?php echo G5_IMG_URL?>/main_login_btn.png" alt="로그인" onclick="location.href='<?php echo G5_BBS_URL?>/login'">
            <!--<div class="board" onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'">
                <img src="<?php /*echo G5_IMG_URL;*/?>/ic_databoard.svg" alt=""> <h2 >게시판</h2>
            </div>
            <div class="board" onclick="location.href=g5_url+'/page/board/inquiry'">
                <img src="<?php /*echo G5_IMG_URL;*/?>/ic_inquiry.svg" alt=""> <h2 >제안</h2>
            </div>-->
        <?php }else{?>
            <img src="<?php echo G5_IMG_URL?>/mypage_btn.png" alt="로그인" onclick="fnMyprofile('<?php echo $member["mb_id"];?>');">
        <?php }?>
    </div>
</div>
<div class="my_profile <?php if($menu=="on"){?>active<?php }?>">
    <div class="my_profile_top">
        <h2 >
            <span class="<?php if($member["mb_level"]==3){echo "cm";}else if($member["mb_level"]==5){echo "pm";}?>"><?php if($member["mb_level"]==3){echo "CM";}else if($member["mb_level"]==5){echo "PM";}?></span>
            <label onclick="location.href=g5_url+'/page/mypage/mypage'"><?php echo $member["mb_id"];?></label> 님
            <img src="<?php echo G5_IMG_URL?>/ic_logout_w.png" alt="" onclick="fnLogout()">
            <img src="<?php echo G5_IMG_URL?>/ic_profile_setting.svg" alt="" onclick="location.href=g5_url+'/page/mypage/mypage'">
            <div class="close" onclick="fnCloseProfile()"></div>
        </h2>
        <div class="pays">
            <?php if($member["mb_level"] >= 3 && $member["mb_level"] < 10){?>
                <span>맴버쉽기한 : <?php echo ($mypayments["payment_end_date"])?$mypayments["payment_end_date"]:"결제정보 없음";?></span>
                <?php if($member["parent_mb_id"]==""){?>
                <a href="javascript:<?php if(!$is_admin){?>fnPayment();<?php }else{?>alert('최고관리자는 구매 하실 수 없습니다.')<?php }?>">연장 <span></span> </a>
                <?php }?>
            <?php }if($member["mb_level"] <= 2 && $member["mb_level"] != 10){?>
                <span>맴버쉽 구매가 필요합니다.</span>
                <?php if($member["parent_mb_id"]==""){?>
                <a href="javascript:<?php if(!$is_admin){?>fnPayment();<?php }else{?>alert('최고관리자는 구매 하실 수 없습니다.')<?php }?>">결제 <span></span> </a>
                <?php }?>
            <?php }?>
        </div>
    </div>
    <div class="mycmap">
        <select name="mylocmap" id="mylocmap" class="cmap_sel width100" onchange="fnChangeConst('<?php echo $member["mb_id"];?>',this.value)" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }?>">
            <option value="">현장 선택</option>
            <?php for($i=0;$i<count($mycont);$i++){?>
                <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
            <?php }?>
        </select>
        <div class="cmap_menu">

            <div class="cmap_menu_td cmenu1" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{ if($member["mb_level"]==3){?>location.href=g5_url+'/page/mylocation/mylocation'<?php }else if($member["mb_level"]==5){?>location.href=g5_url+'/page/manager/pm_construct'<?php } } ?>">
                <h2 class="count_title"><?php if($member["mb_level"]==5){?>PM 지구<?php }else{ ?>현장관리<?php }?> <span><strong><?php echo number_format(count($mycont));?></strong> 개</span></h2>
                <!--<div class="counts"></div>-->
            </div>
            <div class="cmap_menu_td full_td cmenu2" onclick="<?php if($member['mb_auth']==false){?>alert('PMMODE 구매후 이용가능합니다.')<?php }else{?>location.href=g5_url+'/page/manager/'<?php }?>">
                <img src="<?php echo G5_IMG_URL?>/ic_construct.svg" alt=""><h2>PM MODE</h2>
            </div>
            <div class="cmap_menu_td cmenu3" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewRequest('<?php echo $member["mb_id"];?>','')<?php }?>">
                <input type="hidden" id="const_id" value="">
                <h2 class="count_title">사용자관리 <span><strong><?php echo number_format(count($reqlist));?></strong> 건</span></h2>
                <!--<div class="counts">

                </div>-->
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td cmenu4" onclick="location.href=g5_url+'/page/mypage/schedule'">
                <img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""> <h2>스케쥴</h2>
            </div>
            <?php }?>
            <div class="cmap_menu_td cmenu5" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewMessage('<?php echo $member["mb_id"];?>','')<?php }?>">
                <h2 class="count_title">업무연락서 <span><strong><?php echo number_format(count($msglist));?></strong> 건</span></h2>
                <!--<div class="counts">

                </div>-->
            </div>
            <div class="cmap_menu_td <?php if($member["mb_level"]!=5){?>cmenu6<?php }else{?>cmenu4 border6<?php }?>" onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'">
                <img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""> <h2>게시판</h2>
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td cmenu7" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewDelay('<?php echo $member["mb_id"];?>','')<?php }?>">
                <h2 class="count_title">제출지연건 <span><strong><?php echo number_format(count($maindelaylists));?></strong> 건</span></h2>
            </div>
            <?php }?>

            <div class="cmap_menu_td <?php if($member["mb_level"]!=5){?>cmenu8<?php }else{?>cmenu6 border8<?php }?> " onclick="location.href=g5_url+'/page/board/inquiry'">
                <img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""> <h2>제안하기</h2>
            </div>
            <div class="cmap_menu_td full_td cmenu9 <?php if($member["mb_level"]==5){?>cmenu9_1<?php }?>">
                <h3>시공평가 점수</h3>
                <div class="eval1 <?php echo $evel1_class;?>">
                    <span style="<?php if($eval1_left){?>left:calc(<?php echo ceil($eval1_left);?>% - 40px);<?php }?>" class="<?php echo $evel1_class;?>">
                        <p class="eval1_p"><?php echo ceil($eval1_total);?></p>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#FFFFFF;}
                        </style>
                        <g>
                            <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5" />
                            <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z" />
                        </g>
                        </svg>
                    </span>
                    <div><label>80</label></div>
                    <div><label>90</label></div>
                    <div></div>
                </div>
                <h3>용역평가 점수</h3>
                <div class="eval2 <?php echo $evel2_class;?>">
                    <span style="<?php if($eval2_left){?>left:calc(<?php echo ceil($eval2_left);?>% - 40px);<?php }?>" class="<?php echo $evel2_class;?>">
                        <p class="eval2_p"><?php echo $eval2_total;?></p>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#FFFFFF;}
                        </style>
                        <g>
                            <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5" />
                            <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z" />
                        </g>
                        </svg>
                    </span>
                    <div><label>80</label></div>
                    <div><label>90</label></div>
                    <div></div>
                </div>
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td full_td cmenu10">
                <h2>오늘의 할일</h2>
                <div class="more" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnScheduleView()<?php }?>">MORE ></div>
                <div class="lists">
                    <ul>
                    <?php if(count($myschedule)>0){?>
                        <?php for($i=0;$i<count($myschedule);$i++){
                            $indate = explode(" ",$myschedule[$i]["insert_date"]);
                            $pkids = str_replace("``",",",$myschedule[$i]["pk_id"]);
                            $sql = "select a.id as id, a.me_code as me_code,b.pk_id as pk_id,b.depth1_id as depth1_id, b.depth2_id as depth2_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where b.pk_id in ({$pkids})";
                            $schedulesid = sql_fetch($sql);
                            if($schedulesid!=null){
                                $link = "location.href='".G5_URL."/page/view?depth1_id=".$schedulesid["depth1_id"]."&depth2_id=".$schedulesid["depth2_id"]."&me_id=".$schedulesid["me_code"]."&pk_id=".$schedulesid["pk_id"]."&constid=".$myschedule[$i]["construct_id"]."'";
                            }else{
                                $link = 'fnScheduleView()';
                            }
                            ?>
                            <li class="" onclick="<?php echo $link;?>"> - <?php echo cut_str($myschedule[$i]["schedule_name"],15,'...');?><span><?php echo $indate[0];?></span></li>
                        <?php }?>
                    <?php }else{?>
                        <li>등록된 할일이 없습니다.</li>
                    <?php }?>
                    </ul>
                </div>
            </div>
            <?php }?>
            <div class="cmap_menu_td full_td cmenu11 <?php if($member["mb_level"]==5){?>cmenu11_1<?php }?>" >
                <div class="left" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnWeather('<?php echo $member["mb_id"];?>','<?php echo $currentConst["id"];?>');<?php }?>">
                    <div class="todays">
                    <?php
                    $w = date("w");
                    switch ($w){
                        case 0:
                            $week = "일";
                            break;
                        case 1:
                            $week = "월";
                            break;
                        case 2:
                            $week = "화";
                            break;
                        case 3:
                            $week = "수";
                            break;
                        case 4:
                            $week = "목";
                            break;
                        case 5:
                            $week = "금";
                            break;
                        case 6:
                            $week = "토";
                            break;
                    }
                    echo date("Y년 m월 d일")."(".$week.")";
                    ?>
                    </div>
                    <div class="location">
                        <h2 class="addr"> </h2><span class="timedesc"></span>
                    </div>
                </div>
                <div class="right">
                    <div class="current_temp">
                        <div class="now_temp">

                        </div>
                        <div class="temp_min_max">

                        </div>
                    </div>
                    <div class="current_btn">
                        <input type="button" name="location" value="위치" onclick="getLocation();">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="mymenu_detail">
    <div class="title">
        <h2></h2>
    </div>
    <div class="detail_list">

    </div>
    <div class="infos">
        <p><사용자 승인 시></p>
        <ul>
            <li>1. 사용자간 CMAP 현황 복사 및 공유</li>
            <li>2. 사용자간 업무연락서 발신 및 수신 </li>
        </ul>
        <br><br>
        <p>주의 :     CMAP은 건설관리 지원프로그램으로 주요내용은 별도 보안조치 및 저장하시기 바랍니다.</p>
    </div>
</div>
<?php }?>
<div class="search_area">
    <div class="search_wrap">
        <div class="searchs">
            <form action="<?php echo G5_URL;?>/page/search/search" method="post" name="searchFrom">
                <select name="search_type" id="search_type">
                    <option value="">전체검색</option>
                    <?php for($i=0;$i<count($search_menu);$i++){?>
                        <option value="<?php echo $search_menu[$i]["menu_code"];?>"><?php echo $search_menu[$i]["menu_name"];?></option>
                    <?php }?>
                </select>
                <input type="text" name="search_text" class="search_input" id="search_text" placeholder="검색어를 입력해주세요.">
                <input type="submit" value="" name="search_btn">
            </form>
        </div>
        <div class="populars">
            <h3>인기 검색어</h3>
            <div class="texts">
            <?php for($i=0;$i<count($papular);$i++){?>
                <span onclick="fnSearchPapular('<?php echo $papular[$i]["search_text"];?>')"><?php echo $papular[$i]["search_text"];?></span>
            <?php }?>
            </div>
        </div>
    </div>
</div>
<div class="siteMaps">
    <div class="siteIn">
        <header>
            <h2>전체보기</h2>
            <input type="button" value="네비게이터 설정" class="basic_btn03" onclick="location.href='<?php echo G5_URL;?>/page/mypage/navigator'">
        </header>
        <div class="navigator_tab">
            <ul class="">
                <?php for($i=0;$i<count($menulist);$i++){?>
                    <li class="" id="allmenu_header<?php echo $menulist[$i]["menu_code"];?>" onclick="fnMenusHeader('<?php echo $menulist[$i]["menu_code"];?>')"><?php echo $menulist[$i]["menu_name"];?></li>
                <?php }?>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="menus menus_head">
            <ul class="depth_menu depth_menu_heads">
            </ul>
        </div>
    </div>
</div>
<!-- 상단 시작 { -->
<div class="header_top" style="position:fixed;top:0;display:inline-block;left:0;width:100%;height:60px;z-index: 12">
    <div id="hd">
        <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

        <?php
        if(defined('_INDEX_')) { // index에서만 실행
            include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
        }
        ?><!--
        <div id="tnb">
            <ul>
                <?php /*if ($is_member) {  */?>

                <li><a href="<?php /*echo G5_BBS_URL */?>/member_confirm.php?url=<?php /*echo G5_BBS_URL */?>/register_form.php"><i class="fa fa-cog" aria-hidden="true"></i> 정보수정</a></li>
                <li><a href="<?php /*echo G5_BBS_URL */?>/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> 로그아웃</a></li>
                <?php /*if ($is_admin) {  */?>
                <li class="tnb_admin"><a href="<?php /*echo G5_ADMIN_URL */?>"><b><i class="fa fa-user-circle" aria-hidden="true"></i> 관리자</b></a></li>
                <?php /*}  */?>
                <?php /*} else {  */?>
                <li><a href="<?php /*echo G5_BBS_URL */?>/register.php"><i class="fa fa-user-plus" aria-hidden="true"></i> 회원가입</a></li>
                <li><a href="<?php /*echo G5_BBS_URL */?>/login.php"><b><i class="fa fa-sign-in" aria-hidden="true"></i> 로그인</b></a></li>
                <?php /*}  */?>

            </ul>

        </div>-->
        <nav id="gnb" class="<?php echo $myset["theme"];?> <?php echo "cate_".$myset["cate_theme"];?>">
            <div id="logo">
                <a href="<?php echo G5_URL ?>">
                    <?php if($myset["theme"]=="white"){?>
                    <img src="<?php echo G5_IMG_URL ?>/logo_b.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }else{ ?>
                    <img src="<?php echo G5_IMG_URL ?>/logo.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }?>
                </a>
            </div>
            <div class="gnb_wrap">
                <ul id="gnb_1dul">
                    <!--<li class="gnb_1dli gnb_mnal"><button type="button" class="gnb_menu_btn"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button></li>-->
                    <?php
                    if($member["mb_level"]!=5){

                    $sql = " select *
                                from `cmap_menu`
                                where menu_status = 0
                                  and menu_depth = 0
                                order by menu_order ";
                    $result = sql_query($sql, false);
                    $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                    $menu_datas = array();

                    for ($i=0; $row=sql_fetch_array($result); $i++) {//대메뉴

                        $menu_datas[$i] = $row;

                        $sql2 = " select *
                                    from `cmap_menu`
                                    where menu_status = 0
                                      and menu_depth = 1
                                      and substring(menu_code, 1, 2) = '{$row['menu_code']}'
                                    order by menu_order ";
                        $result2 = sql_query($sql2);
                        for ($k=0; $row2=sql_fetch_array($result2); $k++) {//1차
                            $menu_datas[$i]['sub'][$k] = $row2;
                            $sql3 = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by id asc ";
                            $result3 = sql_query($sql3);
                            for($l = 0; $row3 = sql_fetch_array($result3);$l++){//2차
                                if($row["menu_code"]==40) {
                                    if (in_array($row3["pk_id"], $menuchk)) {
                                        if ($menuchk_act[$row3["pk_id"]] == 1) {
                                            $menu_datas[$i]['sub'][$k]['cnt']++;
                                        }
                                    }
                                }
                                //$menus3[] = $row3;
                                if($delayhead[$row3["pk_id"]]){
                                    $menu_datas[$i]['sub'][$k]["delay"] = true;
                                    continue;
                                }else{
                                    $menu_datas[$i]['sub'][$k]["delay"] = false;
                                }
                            }
                        }
                    }
                    $i = 0;
                    foreach( $menu_datas as $row ){
                        if( empty($row) ) continue;

                        if($is_member) {
                            $sqls = "select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code='{$row["menu_code"]}'";
                            $ress = sql_query($sqls);
                            while ($rows = sql_fetch_array($ress)) {
                                $mynavimenu[] = $rows;
                            }
                        }
                    ?>
                    <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex--; ?>"> <!-- 메인 메뉴 5개 -->
                        <?php if($me_id==60 || $row["menu_name"] == "평가"){?>
                        <a href="#" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                        <?php }else{?>
                        <a href="#" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                        <?php }?>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if( empty($row2) ) continue;
                            if($is_member && count($mycont)>0) {
                                if($row["menu_code"]==40) {
                                    if ($row2["cnt"] == 0) {
                                        continue;
                                    }
                                }
                                if (count($mynavimenu) > 0) {
                                    for ($a = 0; $a < count($mynavimenu); $a++) {
                                        $ids_menu = explode("``", $mynavimenu[$a]["menu_ids"]);
                                        $subids_menu = explode("``", $mynavimenu[$a]["sub_ids"]);
                                        $actives_menu = explode("``", $mynavimenu[$a]["menu_ids_actives"]);
                                        $sub_actives_menu = explode("``", $mynavimenu[$a]["sub_ids_actives"]);
                                        for ($b = 0; $b < count($actives_menu); $b++) {
                                            $activeMenu[$ids_menu[$b]] = $actives_menu[$b];
                                        }

                                        for ($c = 0; $c < count($subids_menu); $c++) {
                                            $submenu = explode("|", $subids_menu[$c]);
                                            $subsmnues[$submenu[0]][$submenu[1]] = $sub_actives_menu[$c];
                                        }
                                    }
                                }

                                //if($activeMenu[$row2["me_id"]]==0){continue;}
                            }

                            if($row["menu_name"]=="공사관리" && $k==0){
                                echo '<span class="allmenu"></span>';
                            }

                            if($k == 0)
                                echo '<ul class="gnb_2dul">'.PHP_EOL;

                            //echo '<span class="bg">하위분류</span><ul class="gnb_2dul">'.PHP_EOL;
                            $sql = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by `id` asc";
                            $res = sql_query($sql);
                            $num = sql_num_rows($res);
                        ?>
                            <li class="gnb_2dli <?php if($num>1){?>arrows<?php }?> <?php if($row2["delay"] || $delayhead2[$row2["menu_code"]]){ if($activeMenu[$row2["me_id"]]=="1"){ ?>chk<?php } }?> " > <!-- 1depth  -->
                                <a href="<?php if($num == 1){?><?php echo G5_URL?>/page/view?me_id=<?php echo $row2["menu_code"]; ?><?php }else{ ?>#<?php }?>" class="gnb_2da"><?php echo $row2['menu_name'] ?></a>
                                <?php
                                if($num >= 10){
                                    if($k<=10){
                                        $over_top = "over_top2";
                                    }else {
                                        $over_top = "over_top";
                                    }
                                }else{
                                    $over_top = "";
                                }

                                if($num > 1){
                                    echo '<ul class="gnb_3dul '.$over_top.'">'; //2depth??

                                if($row2["menu_name"]=="용역평가"){?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=338">업체평가 (80)</a></li>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=341">기술자평가 (20)</a>
                                <?php }else{

                                while($row3 = sql_fetch_array($res)){
                                    if( empty($row3) ) continue;
                                    if($row["menu_code"]==40) {
                                        if (in_array($row3["pk_id"], $menuchk)) {
                                            if ($menuchk_act[$row3["pk_id"]] == 0) {
                                                continue;
                                            }
                                        }
                                    }
                                    /*if($is_member && count($subsmnues[$row2["me_id"]])>0) {
                                        if ($subsmnues[$row2["me_id"]][$row3["pk_id"]] == 0) {
                                            continue;
                                        }
                                    }*/
                                    if($subsmnues[$row2["me_id"]][$row3["pk_id"]] == 1){
                                      if($delayhead[$row3["pk_id"]]){
                                          $chk = "chk";
                                      }else{
                                          $chk = "";
                                      }
                                    }else{
                                      $chk = "";
                                    }

                                    if($row3["depth_name"]){
                                        if($row2["menu_code"]=='6064'){
                                        ?>
                                        <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view2?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                        <?php //}else if($me_id=='60129'){
                                            ?>
                                            <!--<li class="gnb_3dli "><a class="gnb_3da" href="<?php /*echo G5_URL*/?>/page/view3.php?me_id=<?php /*echo $row2["menu_code"]; */?>&depth1_id=<?php /*echo $row3["id"];*/?>"><?php /*echo $row3["depth_name"];*/?></a></li>-->
                                        <?php }else{?>
                                        <li class="gnb_3dli menu_<?php echo $row3["pk_id"];?> <?php echo $chk;?>">
                                            <a class="gnb_3da" href="<?php echo G5_URL?>/page/view?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                    <?php }?>
                                    <?php }?>
                                <?php $a++;} echo '</ul>';?>
                                <?php }?>
                                <?php }?>
                            </li>
                        <?php
                        $k++;
                        }   //end foreach $row2
                        if($k > 0)
                            echo '</ul>'.PHP_EOL;
                        ?>
                    </li>
                    <?php
                    $i++;
                    }   //end foreach $row

                    if ($i == 0) {  ?>
                        <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                    <?php }else{?>
                        <li class="gnb_empty">CMAP PMMODE - <?php echo $member["mb_1"];?></li>
                    <?php }?>
                </ul>
            </div>
            <div id="gnb_all">
                <h2>전체메뉴</h2>
                <ul class="gnb_al_ul">
                    <?php

                    $i = 0;
                    foreach( $menu_datas as $row ){
                    ?>
                    <li class="gnb_al_li">
                        <a href="<?php echo $row['me_link']; ?>" class="gnb_al_a"><?php echo $row['menu_name'] ?></a>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if($k == 0)
                                echo '<ul>'.PHP_EOL;
                        ?>
                            <li><a href="<?php echo $row2['me_link']; ?>" ><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $row2['menu_name'] ?></a></li>
                        <?php
                        $k++;
                        }   //end foreach $row2

                        if($k > 0)
                            echo '</ul>'.PHP_EOL;
                        ?>
                    </li>
                    <?php
                    $i++;
                    }   //end foreach $row

                    if ($i == 0) {  ?>
                        <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                </ul>
                <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="etc_btns">
                <input type="button" onclick="fnSearch();" class="search_btns">
                <div class="navigator_set">
                    <div class="icons"></div>
                </div>
            </div>
            <?php }?>
        </nav>
        <script>

        $(function(){
            $(".navigator_set").click(function(){
                if($(".icons").hasClass("active")) {
                    $(".icons").removeClass("active");
                    $(".siteMaps").removeClass("active");
                    $(".header_top .container").show();
                    $(".header_top .search").show();
                }else {
                    $(".header_top .container").attr("style","z-index:0");
                    $(".icons").addClass("active");
                    $(".siteMaps").addClass("active");
                    $(".header_top .container").hide();
                    $(".header_top .search").hide();
                    fnMenusHeader('10');
                }
            });

            $(".gnb_menu_btn").click(function(){
                $("#gnb_all").show();
            });
            $(".gnb_close_btn").click(function(){
                $("#gnb_all").hide();
            });
            $(".gnb_1dli").each(function(){
                $(this).hover(function() {
                    if($(this).hasClass("gnb_1dli_over")) {
                        var height = $(this).find($(".over_top2")).height();
                        var newHeight = height/2.1;
                        $(this).find($(".over_top2")).css({"top": "-"+newHeight+"px"});
                    }
                });
            });
            $("li.gnb_3dli").each(function(){
                $(this).mouseover(function(){
                    <?php if($myset["cate_theme"]=="blue"){?>
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#000 !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right_b.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
                    <?php }else if($myset["cate_theme"]=="black"){ ?>
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
                    <?php }else if($myset["cate_theme"]=="white"){ ?>
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
                    <?php }?>
                });
                $(this).mouseout(function(){
                    $(this).parent().parent().find($(".gnb_2da")).removeAttr("style");

                })
            });
        });
        function fnPayment(){
            $.ajax({
                url:g5_url+"/page/modal/ajax.payment.php",
                method:"post",
                data:{mb_id:"<?php echo $member["mb_id"];?>"}
            }).done(function(data){
                //$(".modal").append(data);
                //fnShowModal(data);
                $(".etc_view").html(data);
                $(".etc_view").addClass("active");
                $(".etc_view_bg").addClass("active");
            });
        }
        </script>
    </div>
    <div class="container" <?php if($main){?>id="mainscreen"<?php }?>>
    <?php if(!$main && $sub != "login" && $mypage != true){?>
        <?php if($sub!="search"){?>
    <div class="user_guide">
        <table class="user2">
            <tr>
            <?php if(!$depth1_id && !$me_code && !$me_id){?>
                <td class="navies">사용자 가이드</td>
            <?php }else{ ?>
                <?php if(count($mycont)>0){?>
                <td class="first">
                    <select name="mylocmap" id="mylocmap" class="cmap_sel" style="width:180px;" onchange="fnChangeConst2('<?php echo $member["mb_id"];?>',this.value)">
                        <option value="" <?php if($current_const["const_id"]==0){?>selected<?php }?>>현장 선택</option>
                        <?php for($i=0;$i<count($mycont);$i++){?>
                            <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                        <?php }?>
                    </select>
                </td>
                <?php } ?>
                <td class="navies">
                    <?php if(substr($incode,0,2)==60 ){?>
                        <select name="me_id" id="me_id">
                        <?php for($i=0;$i<count($depth_me);$i++) {?>
                            <option value="60<?php echo $depth_me[$i]["me_id"];?>" <?php echo get_selected('60'.$depth_me[$i]["me_id"],$me_id);?>><?php echo $depth_me[$i]["menu_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }else{ ?>
                        <select name="depth1_id" id="depth1_id" style="width:176px;" >
                        <?php for($i=0;$i<count($depth_me);$i++) {
                            if(substr($me_id,0,2)==40) {
                                if (in_array($depth_me[$i]["pk_id"], $menuchk)) {
                                    if ($menuchk_act[$depth_me[$i]["pk_id"]] == 0) {
                                        continue;
                                    }
                                }
                            }
                            ?>
                        <option value="<?php echo $depth_me[$i]["id"];?>" <?php echo get_selected($depth_me[$i]["id"],$depth1_id);?>><?php echo $depth_me[$i]["depth_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }?>
                </td>
            <?php }?>
                <td><span title="<?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?>" ><?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span></td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
        <?php }?>
    <?php }else if(!$main && $sub != "login" && $mypage != false){?>
        <?php if($sub!="search"){?>
    <div class="user_guide">
        <div class="user">
            <div>사용자 가이드</div>
            <div><span><?php if($useguide[$menu_id]["menu_desc"]){echo $useguide[$menu_id]["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span></div>
        </div>
        <div class="clear"></div>
    </div>
        <?php }?>
    <?php }?>
    <span class="widthchk" style="opacity: 0;white-space: nowrap;height: 0;display:none;"><?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span>
    </div>

    <?php if($test=="msg"){?>
        <div class="search" style="position: relative;" id="msg_search">
            <form action="" method="get">
                <select name="const_id" id="cons_id" class="basic_input01" >
                    <option value="">현장 선택</option>
                    <?php for($i=0;$i<count($mycont);$i++){?>
                        <option value="<?php echo $mycont[$i]["id"];?>" <?php if($const_id==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                    <?php }?>
                </select>
                <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker1" name="date1" value="<?php if($date1==""){echo date("Y-m-d");}else{echo $date1;}?>">
                <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker2" name="date2" value="<?php if($date2==""){echo date("Y-m-d");}else{echo $date2;}?>">
                <select name="search_type" id="search_type" class="basic_input01 width10">
                    <option value="" <?php if($_GET["search_type"]==""){?>selected<?php }?>>전체</option>
                    <option value="0" <?php if($_GET["search_type"]=="0"){?>selected<?php }?>>수신</option>
                    <option value="1" <?php if($_GET["search_type"]=="1"){?>selected<?php }?>>발신</option>
                </select>
                <select name="sfl" id="sfl" class="basic_input01 width10">
                    <option value="" <?php if($sfl==""){?>selected<?php }?>>전체</option>
                    <option value="name" <?php if($sfl=="name"){?>selected<?php }?>>작성자</option>
                    <option value="msg_subject" <?php if($sfl=="msg_subject"){?>selected<?php }?>>제목</option>
                    <option value="msg_content" <?php if($sfl=="msg_content"){?>selected<?php }?>>내용</option>
                </select>
                <input type="text" class="basic_input01 width20" id="datepicker2" name="search_text" value="<?php echo $search_text;?>" placeholder="검색어">
                <input type="submit" class="basic_btn03" value="검색">
            </form>
            <div class="work_msg_btns">
                <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
            </div>
        </div>
    <?php }if($test=="mng"){?>

        <div class="search" style="position: relative;" id="msg_search">
            <form action="" method="get">
                <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" class="basic_input01 width20" placeholder="현장명을 입력해주세요.">
                <select name="sfl" id="sfl" class="basic_input01 width10">
                    <option value="0" <?php if($sfl=="0"){?>selected<?php }?>>전체표기</option>
                    <option value="1" <?php if($sfl=="1"){?>selected<?php }?>>준공현장 표기</option>
                    <option value="2" <?php if($sfl=="2"){?>selected<?php }?>>준공현장 미표기</option>
                </select>
                <input type="submit" class="basic_btn01" value="검색">
            </form>
            <div class="work_msg_btns2">
                <input type="button" class="basic_btn03" value="총괄보고서" onclick="fnSavePm('<?php echo $mngType;?>')">
                <input type="button" class="basic_btn03" value="지구관리" onclick="location.href=g5_url+'/page/manager/pm_construct'">
                <input type="button" class="basic_btn02" value="새로고침" onclick="location.reload()">
                <!--<input type="button" class="basic_btn02" value="PM 보고서" onclick="fnSavePm('<?php /*echo $mngType;*/?>')">-->
                <input type="button" class="basic_btn02" value="업무연락서" onclick="location.href=g5_url+'/page/mypage/my_message_list'">
            </div>
        </div>
    <?php }?>
</div>
<script>
    jQuery.fn.hasOverflown = function () {
        var res;
        var cont = $('<div>'+this.text()+'</div>').css("display", "table")
            .css("z-index", "-1").css("position", "absolute")
            .css("font-family", this.css("font-family"))
            .css("font-size", this.css("font-size"))
            .css("font-weight", this.css("font-weight")).appendTo('body');
        res = (cont.width()>this.width());
        cont.remove();
        return res;
    };

    var ww = 0;
    var left = 0;
    var chk = false;
    $(function(){
        ww = $(".widthchk").width();
        chk = $(".user2 td:last-child span").hasOverflown();
        left = $(".user2 td:last-child span").css("left");
        if(chk){
            animateSpan();
        }
    });

    function animateSpan(){
        if($(".user2 td:last-child span").position().left > -ww){
            $(".user2 td:last-child span").animate({
                left: "-=5"
            }, 100, animateSpan);
        }else{
            $(".user2 td:last-child span").css({"left":"0"});
            //animateSpan2();
        }
    }
    /*
    function animateSpan2(){
        if($(".user2 td:last-child span").position().left <= 0){
            $(".user2 td:last-child span").animate({
                left: "+=8"
            }, 100, animateSpan2);
        }else{
            setTimeout(animateSpan,2000);
        }
    }*/
</script>
<!-- } 상단 끝 -->
