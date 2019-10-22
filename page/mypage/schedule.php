<?php
include_once ("../../common.php");
if($member["mb_auth"]==false){
    alert("무료 이용기간이 만료 되었거나,\\r맴버쉽 기간이 만료 되었습니다. \\n맴버쉽 구매후 이용바랍니다.",G5_URL);
}
$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "depth_desc_schedule";
include_once (G5_PATH."/_head.php");

/********** 사용자 설정값 **********/
$startYear        = date( "Y" ) ;
$endYear        = date( "Y" ) + 4;
$today = date("d");

/********** 입력값 **********/
$year            = ( $_GET['toYear'] )? $_GET['toYear'] : date( "Y" );
$month            = ( $_GET['toMonth'] )? $_GET['toMonth'] : date( "m" );
$doms            = array( "일", "월", "화", "수", "목", "금", "토" );

/*********** 2주계산 ********/
$week2 = date("Y-m-d", mktime( 0, 0, 0, date("m"),  $today + $data[13] , $startYear ));

/********** 계산값 **********/
$mktime            = mktime( 0, 0, 0, $month, 1, $year );      // 입력된 값으로 년-월-01을 만든다
$days            = date( "t", $mktime );                        // 현재의 year와 month로 현재 달의 일수 구해오기
$startDay        = date( "w", $mktime );                        // 시작요일 알아내기

// 지난달 일수 구하기
$prevDayCount    = date( "t", mktime( 0, 0, 0, $month, 0, $year ) ) - $startDay + 1;

$nowDayCount    = 1;                                            // 이번달 일자 카운팅
$nextDayCount    = 1;                                          // 다음달 일자 카운팅

// 지난달 일수 구하기
$prevDayCountMini    = date( "t", mktime( 0, 0, 0, $month, 0, $year ) ) - $startDay + 1;

$nowDayCountMini    = 1;                                            // 이번달 일자 카운팅
$nextDayCountMini    = 1;                                          // 다음달 일자 카운팅

// 지난달 일수 구하기
$prevDayCountMini2    = date( "t", mktime( 0, 0, 0, $month, 0, $year ) ) - $startDay + 1;

$nowDayCountMini2    = 1;                                            // 이번달 일자 카운팅
$nextDayCountMini2   = 1;                                          // 다음달 일자 카운팅


// 이전, 다음 만들기
$prevYear        = ( $month == 1 )? ( $year - 1 ) : $year;
$prevMonth        = ( $month == 1 )? 12 : ( $month - 1 );
$nextYear        = ( $month == 12 )? ( $year + 1 ) : $year;
$nextMonth        = ( $month == 12 )? 1 : ( $month + 1 );

// 출력행 계산
$setRows = ceil( ( $startDay + $days ) / 7 );

$holiday = sql_fetch("select * from `cmap_holidays` where year = '{$year}'");

//설날 3일
$newyear = explode("~", $holiday["holidays2"]);
$holiday2_1 = $newyear[0];
$holiday2_2 = date("Y-m-d",strtotime(" +1 day",strtotime($newyear[0])));
$holiday2_3 = $newyear[1];
//추석 3일
$thank = explode("~", $holiday["holidays8"]);
$holiday8_1 = $thank[0];
$holiday8_2 = date("Y-m-d",strtotime(" +1 day",strtotime($thank[0])));
$holiday8_3 = $thank[1];

/*if($current_const["const_id"] && $constid=="" && $type == ""){
    $constid = $current_const["const_id"];
}*/

//스케쥴 가져오기
if(isset($_GET["constid"]) && $_GET["constid"]!=""){
    $constid = $_GET["constid"];
    $where = " and construct_id = '{$constid}'";
    $sql = "update `cmap_my_current_construct` set const_id = '{$_GET["constid"]}' where mb_id = '{$member["mb_id"]}'";
    sql_query($sql);
}else if(isset($_GET["constid"]) && $_GET["constid"]==""){
    //내가 속한 현장 가져오기
    if($member["mb_level"]==5){
        $sql = "select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}')!= 0 and status = 0";
    }else {
        $sql = "select * from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}')!= 0) and status = 0";
    }
    $cres = sql_query($sql);
    while ($row = sql_fetch_array($cres)) {
        $const_id["const_id"][] = $row["id"];
    }
    if (count($const_id) != 0) {
        $constid = implode(",", $const_id["const_id"]);
        $where .= " and construct_id in ({$constid})";
    }
}else{
    $constid = $current_const["const_id"];
    $where = " and construct_id = '{$current_const["const_id"]}'";
}

if($where) {
    $sql = "select * from `cmap_myschedule` where status != -1 {$where} order by id";
    $res = sql_query($sql);
    while ($row = sql_fetch_array($res)) {
        /*$sql = "select * from `cmap_my_construct` where id = '{$row["construct_id"]}'";
        $chkstatus = sql_fetch($sql);
        if($chkstatus["status"] == -1) continue;*/
        $myschedule[$row["schedule_date"]][] = $row;
        $myschedule[$row["schedule_date"]]["construct_id"] = $row["construct_id"];
    }
}

if(strpos($constid,",")!==false){
    $delayWhere = " and construct_id in ({$constid})";
    $delayWhere2 = " and const_id in ({$constid})";
    $delayWhere3 = " and id in ({$constid})";
    $order = "order by construct_id desc ,schedule_date desc";
}else {
    $delayWhere = " and construct_id = '{$constid}' ";
    $delayWhere2 = " and const_id = '{$constid}' ";
    $delayWhere3 = " and id = '{$constid}' ";
    $order = "order by schedule_date desc";
}

if($member["mb_level"]==5){
    $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' {$delayWhere2}";
    $ss = sql_fetch($sql);
    if($ss!=null){
        $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' {$delayWhere2}";
    }else{
        $sql = "select * from `cmap_my_construct` where 1 {$delayWhere3}";
        $ss2 = sql_fetch($sql);
        $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss2["mb_id"]}' {$delayWhere2}";
    }
}else{
    $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' {$delayWhere2}";
}
$activeres = sql_query($activesql);
while($activechk = sql_fetch_array($activeres)) {
    $map_pk_id = explode("``", $activechk["pk_ids"]);
    $map_pk_actives = explode("``", $activechk["pk_actives"]);
    $map_pk_actives_date = explode("``", $activechk["pk_actives_date"]);

    $delaysql = "select * from `cmap_myschedule` where pk_id <> '' and construct_id = '{$activechk["const_id"]}' and mb_id <> '' {$order}";
    $delayres = sql_query($delaysql);
    $a = 0;
    while ($delayrow = sql_fetch_array($delayres)) {
        $pk_ids = explode("``", $delayrow["pk_id"]);

        $diff = strtotime($delay_now) - strtotime($delayrow["schedule_date"]);

        $delaydays = $diff / (60 * 60 * 24);
        for ($i = 0; $i < count($pk_ids); $i++) {
            for ($j = 0; $j < count($map_pk_id); $j++) {
                if ($pk_ids[$i] == $map_pk_id[$j]) {
                    $sql = "select *,d.pk_id as pk_id,c.depth1_id as depth1_id,a.pk_id as depth1_pk_id,c.depth2_id as depth2_id ,d.depth_name as depth_name,a.depth_name as depth1_name,c.pk_id as c_pk_id,a.me_code  from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                    $ddd = sql_fetch($sql);

                    if ($map_pk_actives[$j] == 0) {
                        if(substr($ddd["me_code"],0,2) != 10) {
                            if (strpos($chcccid, $ddd["pk_id"]) !== false) {
                                continue;
                            }
                        }
                        $chcccid .= ','.$ddd["pk_id"];
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]] = $ddd;
                        if($delay_now > $delayrow["schedule_date"]) {
                            if (strpos($chcccid, $delayrow["construct_id"] . "_" .$ddd["pk_id"]) !== false) {

                            } else {
                                $delaylists[$delayrow["construct_id"] . "_" . $pk_ids[$i]] = $ddd;
                                $delaylists[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["delay_date"] = "-".$delaydays;
                                //$chcccid .= ',' . $delayrow["construct_id"] . "_" .$ddd["pk_id"];
                            }
                        }
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["delay_date"] = "-" . $delaydays;
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["active"] = $map_pk_actives[$j];
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["active_date"] = $map_pk_actives_date[$j];
                    }else {
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["delay_date"] = "-";
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["active"] = $map_pk_actives[$j];
                        $schdelaylist[$delayrow["construct_id"] . "_" . $pk_ids[$i]]["active_date"] = $map_pk_actives_date[$j];
                    }
                }
            }
        }
    }
    unset($map_pk_id);
    unset($map_pk_actives);
    unset($map_pk_actives_date);
}
//$schdelaylist = $delaylists;
//print_r2($delaylists);
$delaylists = array_values($delaylists);
$delaylists = arr_sort($delaylists,"delay_date","asc");

$delaylists = array_filter($delaylists);

?>
<div class="etc_view messages">

</div>
<span class="etc_view_bg"></span>
<div class="full-width cal-padding" style="">
    <div class="backbg"></div>
    <section class="cal">
        <header class="sub schedule_titles">
            <h2>스케쥴 <span class="years"><?php echo $year.". ";?></span><span class="months"><?php echo $month.". ";?></span><span class="days"><?php echo $today;?></span><img src="<?php echo G5_IMG_URL;?>/ic_calendar.svg" alt="" onclick="fnCalOn();"></h2>
            <div class="big_month">
                <a class="prev_year" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 1)?($prevYear - 1):$prevYear?>&toMonth=<?php echo $month ?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_prev.png" alt=""> </a>
                <a class="prev_month" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $prevYear?>&toMonth=<?php echo $prevMonth?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_prev.png" alt=""> </a>
                <span ondblclick="fnSelectDate('<?php echo $year;?>','<?php echo (strlen($month)==1)?"0".$month:$month;?>','<?php echo $_REQUEST["constid"];?>');"><?php echo $year;?>. <?php echo (strlen($month)==1)?"0".$month:$month;?></span>
                <a class="next_month" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $nextYear?>&toMonth=<?php echo $nextMonth?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_next.png" alt=""> </a>
                <a class="next_year" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 12)?($nextYear + 1):$nextYear?>&toMonth=<?php echo $month?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_next.png" alt=""> </a>
            </div>
            <input type="button" value="TODAY" class="basic_btn02 " style="margin-right:20px;" onclick="location.href=g5_url+'/page/mypage/schedule?toYear=<?php echo date("Y");?>&toMonth=<?php echo date("m");?>&constid=<?php echo $constid;?>'">
            <div class="info">
                <ul>
                    <li class="memo"> 제출예정</li>
                    <li class="confirm"> 제출완료</li>
                    <li class="lateconfirm"> 지연제출</li>
                    <li class="late"> 제출지연</li>
                </ul>
            </div>
            <div class="contruct_sel_m">
                <select name="cons_id" id="cons_id" onchange="fnScheduleConst(this.value)">
                    <option value="">현장선택</option>
                    <?php for($i=0;$i<count($mycont);$i++){?>
                        <option value="<?php echo $mycont[$i]["id"];?>" <?php if($constid==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                    <?php }?>
                </select>
            </div>
        </header>

        <table class="schedule_tbl top">
            <colgroup>
                <col width="14.28%">
                <col width="14.28%">
                <col width="14.28%">
                <col width="14.28%">
                <col width="14.28%">
                <col width="14.28%">
                <col width="14.28%">
            </colgroup>
            <tr>
                <?php for( $i = 0; $i < count( $doms ); $i++ ) { ?>
                    <th>
                        <?php
                        switch($i){
                            case 0 : echo "<span class='sun'>SUN</span>";break;
                            case 1 : echo "<span>MON</span>";break;
                            case 2 : echo "<span>TUE</span>";break;
                            case 3 : echo "<span>WED</span>";break;
                            case 4 : echo "<span>THU</span>";break;
                            case 5 : echo "<span>FRI</span>";break;
                            case 6 : echo "<span class='sat'>SAT</span>";break;
                        }
                        ?>
                    </th>
                <?php } ?>
            </tr>
        </table>
        <div style="" class="calendar_wrap">
            <table class="schedule_tbl">
                <colgroup>
                    <col width="14.28%">
                    <col width="14.28%">
                    <col width="14.28%">
                    <col width="14.28%">
                    <col width="14.28%">
                    <col width="14.28%">
                    <col width="14.28%">
                </colgroup>
                <!--<thead>
                <tr>
                    <?php /*for( $i = 0; $i < count( $doms ); $i++ ) { */?>
                        <th>
                            <?php
/*                            switch($i){
                                case 0 : echo "<span class='sun'>SUN</span>";break;
                                case 1 : echo "<span>MON</span>";break;
                                case 2 : echo "<span>TUE</span>";break;
                                case 3 : echo "<span>WED</span>";break;
                                case 4 : echo "<span>THU</span>";break;
                                case 5 : echo "<span>FRI</span>";break;
                                case 6 : echo "<span class='sat'>SAT</span>";break;
                            }
                            */?>
                        </th>
                    <?php /*} */?>
                </tr>
                </thead>-->
                <tbody>
                <?php for( $rows = 0; $rows < $setRows; $rows++ ) { ?> <!-- 주차를 나누는 for 문 -->
                    <tr>
                        <?php for( $cols = 0; $cols < 7; $cols++ )  {
                            // 셀 인덱스 만들자
                            $cellIndex    = ( 7 * $rows ) + $cols;
                            // 이번달이라면
                            if ( $startDay <= $cellIndex && $nowDayCount <= $days ) {
                                if(strlen($nowDayCount)==1){
                                    $ndate = "0".$nowDayCount;
                                }else{
                                    $ndate = $nowDayCount;
                                }
                                if(strlen($month)==1){
                                    $mon = "0".$month;
                                }else{
                                    $mon = $month;
                                }
                                $key = $year."-".$mon."-".$ndate;

                                ?>
                                <td onclick="fnScheduleList('<?php echo $key;?>','<?php echo $constid;?>')" class="days <?php echo "d_".$key;?> <?php if($cols==6){ echo "sat_tbl"; } ?>" <?php if( date("d") == $nowDayCount && date("m") == $month) {?>id="today"<?php }?>> <!-- 일주일 내의 일을 나누는 for 문 -->
                                    <?php if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCount, $year ) ) == 6 ) { 	// 토요일
                                        if( date("d") == $nowDayCount  && date("m") == $month ) { ?>
                                            <div><p class="calendar_date sat today" style="color:orange;font-weight:bold"><b><?php echo $nowDayCount++?></b></p></div>
                                        <?php	} else {	?>
                                            <div><p class="calendar_date sat"><?php echo $nowDayCount++?></p></div>
                                        <?php	}
                                    } else if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCount, $year ) ) == 0 ) { 	// 일요일
                                        if( date("d") == $nowDayCount && date("m") == $month) { ?>
                                            <div><p class="calendar_date sun today" style="color:orange;font-weight:bold"><b><?php echo $nowDayCount++?></b></p></div>
                                        <?php	} else {	?>
                                            <div><p class="calendar_date sun"><?php echo $nowDayCount++?></p></div>
                                        <?php	}
                                    } else { 	// 평일
                                        if( strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_3) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays1"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays3"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays4"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays5"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays6"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays7"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays9"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays10"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays11"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_3)){//공휴일?>
                                            <div><p class="calendar_date sun today" ><b><?php echo $nowDayCount++?></b></p></div>
                                        <?php }else {
                                            if (date("d") == $nowDayCount && date("m") == $month) { ?>
                                                <div class="calendar_date today"><p style="color:orange; font-weight:bold"><?php echo $nowDayCount++ ?></p>
                                                </div>
                                            <?php } else { ?>
                                                <div class="calendar_date"><p><?php echo $nowDayCount++ ?></p></div>
                                            <?php }
                                        }
                                    }


                                    if(count($myschedule[$key]) > 0){ ?>
                                        <ul>
                                            <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                                $chkActive = 0;
                                                // 0 = 제출완료 , 1 = 제출지연, 3 = 지연제출, 2 = 제출예정
                                                if($myschedule[$key][$item]["pk_id"]!=""){
                                                    $sch_pk_id = explode("``",$myschedule[$key][$item]["pk_id"]);
                                                    for($chk=0;$chk<count($sch_pk_id);$chk++) {
                                                        if ($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"] != "0000-00-00") { //제출했음
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==1) {
                                                                if ($schdelaylist[$myschedule[$key][$item]["construct_id"] . "_" . $sch_pk_id[$chk]]["active_date"] > $key) { // 지연일때
                                                                    $chkActive = 3;
                                                                } else { //지연이 아닐때
                                                                    $chkActive = 0;
                                                                }
                                                            }
                                                        }else{ //제출안함
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==0) {//0 = 제출안함
                                                                if(date("Y-m-d") <= $key) {
                                                                    $chkActive = 2;
                                                                }else {
                                                                    $chkActive = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                if($item>5 || $myschedule[$key][$item]["schedule_name"] == ""){continue;}
                                                ?>
                                                <li class="<?php if($chkActive==1){?>delays<?php }else if($chkActive==0){?>confirm<?php }else if($chkActive==3){?>lateconfirm<?php }?>" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>">
                                                    <span><?php echo $myschedule[$key][$item]["schedule_name"];?></span>
                                                </li>
                                            <?php }?>
                                        </ul>
                                    <?php }
                                    if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                    ?>
                                </td>
                                <?php	// 이전달이라면
                            } else if ( $cellIndex < $startDay ) {
                                    $presmon = (strlen($prevMonth)==1)?"0".$prevMonth:$prevMonth;
                                    $key = $year."-".$presmon."-".$prevDayCount;
                                ?>
                                <td class="days <?php if($cols==6){ echo 'sat_tbl'; } ?>" onclick="fnScheduleList('<?php echo $key;?>','<?php echo $constid;?>')">
                                    <div><p class="calendar_date other_m"><?php echo $prevDayCount++?></p></div>
                                    <?php
                                    if(count($myschedule[$key]) > 0){?>
                                        <ul>
                                            <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                                $chkActive = 0;
                                                // 0 = 제출완료 , 1 = 제출지연, 3 = 지연제출, 2 = 제출예정
                                                if($myschedule[$key][$item]["pk_id"]!=""){
                                                    $sch_pk_id = explode("``",$myschedule[$key][$item]["pk_id"]);
                                                    for($chk=0;$chk<count($sch_pk_id);$chk++) {
                                                        //echo $schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"]."<br>";
                                                        if ($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"] != "0000-00-00") { //제출했음
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==1) {
                                                                if ($schdelaylist[$myschedule[$key][$item]["construct_id"] . "_" . $sch_pk_id[$chk]]["active_date"] > $key) {
                                                                    $chkActive = 3;
                                                                } else {
                                                                    $chkActive = 0;
                                                                }
                                                            }
                                                        }else{ //제출안함
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==0) {//0 = 제출안함
                                                                if(date("Y-m-d") <= $key) {
                                                                    $chkActive = 2;
                                                                }else {
                                                                    $chkActive = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                /*if($chkActive==0){
                                                    if(date("Y-m-d") <= $key){
                                                        $chkActive = 2;
                                                    }
                                                }*/
                                                if($item>5 || $myschedule[$key][$item]["schedule_name"] == ""){continue;}
                                                ?>
                                                <li class="<?php if($chkActive==1){?>delays<?php }else if($chkActive==0){?>confirm<?php }?>" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>">
                                                    <span><?php echo $myschedule[$key][$item]["schedule_name"];?></span>
                                                </li>
                                            <?php }?>
                                        </ul>
                                    <?php }
                                    if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                    ?>
                                </td>
                                <?php 	// 다음달 이라면
                            } else if ( $cellIndex >= $days ) {
                                $nextmon = (strlen($nextMonth)==1)?"0".$nextMonth:$nextMonth;
                                $nextday = (strlen($nextDayCount)==1)?"0".$nextDayCount:$nextDayCount;
                                $key = $year."-".$nextmon."-".$nextday;
                                ?>
                                <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?> onclick="fnScheduleList('<?php echo $key;?>','<?php echo $constid;?>')">
                                    <div><p class="calendar_date other_m"><?php echo $nextDayCount++?></p></div>
                                    <?php
                                    if(count($myschedule[$key]) > 0){?>
                                        <ul>
                                            <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                                $chkActive = 0;
                                                // 0 = 제출완료 , 1 = 제출지연, 3 = 지연제출, 2 = 제출예정
                                                if($myschedule[$key][$item]["pk_id"]!=""){
                                                    $sch_pk_id = explode("``",$myschedule[$key][$item]["pk_id"]);
                                                    for($chk=0;$chk<count($sch_pk_id);$chk++) {
                                                        //echo $schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"]."<br>";
                                                        if ($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"] != "0000-00-00") { //제출했음
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==1) {
                                                                if ($schdelaylist[$myschedule[$key][$item]["construct_id"] . "_" . $sch_pk_id[$chk]]["active_date"] > $key) {
                                                                    $chkActive = 3;
                                                                } else {
                                                                    $chkActive = 0;
                                                                }
                                                            }
                                                        }else{ //제출안함
                                                            if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==0) {//0 = 제출안함
                                                                if(date("Y-m-d") <= $key) {
                                                                    $chkActive = 2;
                                                                }else {
                                                                    $chkActive = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                /*if($chkActive==0){
                                                    if(date("Y-m-d") <= $key){
                                                        $chkActive = 2;
                                                    }
                                                }*/
                                                if($item>5 || $myschedule[$key][$item]["schedule_name"] == ""){continue;}
                                                ?>
                                                <li class="<?php if($chkActive==1){?>delays<?php }else if($chkActive==0){?>confirm<?php }?>" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>">
                                                    <span><?php echo $myschedule[$key][$item]["schedule_name"];?></span>
                                                </li>
                                            <?php }?>
                                        </ul>
                                    <?php }
                                    if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                    ?>
                                </td>
                            <?php }
                        }
                        ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    <div class="mini_calendar">
        <div class="mini_dates">
            <div class="mini_day_big">
                <div class="round_box">
                    <div class="sel_days">
                        <?php echo $days;?>
                    </div>
                    <div class="sel_months">
                        <?php echo strtoupper(date("M",strtotime($month)));?>
                    </div>
                </div>
            </div>
            <div class="mini_month">
                <a class="prev_year" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 1)?($prevYear - 1):$prevYear?>&toMonth=<?php echo $month ?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_prev.png" alt=""> </a>
                <a class="prev_month" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $prevYear?>&toMonth=<?php echo $prevMonth?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_prev.png" alt=""> </a>
                <span ><?php echo $year;?>. <?php echo (strlen($month)==1)?"0".$month:$month;?></span>
                <a class="next_month" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $nextYear?>&toMonth=<?php echo $nextMonth?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_next.png" alt=""> </a>
                <a class="next_year" href="<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 12)?($nextYear + 1):$nextYear?>&toMonth=<?php echo $month?>&constid=<?php echo $_REQUEST["constid"];?>">
                    <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_next.png" alt=""> </a>
            </div>
        </div>
        <div class="mini_cals">
            <div class="mini_month">
                <?php for( $i = 0; $i < count( $doms ); $i++ ) { ?>
                    <div>
                        <?php
                        switch($i){
                            case 0 : echo "<span class='sun'>S</span>";break;
                            case 1 : echo "<span>M</span>";break;
                            case 2 : echo "<span>T</span>";break;
                            case 3 : echo "<span>W</span>";break;
                            case 4 : echo "<span>T</span>";break;
                            case 5 : echo "<span>F</span>";break;
                            case 6 : echo "<span class='sat'>S</span>";break;
                        }
                        ?>
                    </div>
                <?php }?>
            </div>
            <div class="mini_days">
                <?php for( $rows = 0; $rows < $setRows; $rows++ ) { ?> <!-- 주차를 나누는 for 문 -->
                    <div>
                        <?php for( $cols = 0; $cols < 7; $cols++ )  {
                            // 셀 인덱스 만들자
                            $cellIndex    = ( 7 * $rows ) + $cols;
                            // 이번달이라면
                            if ( $startDay <= $cellIndex && $nowDayCountMini <= $days ) {
                                if(strlen($nowDayCountMini)==1){
                                    $ndate = "0".$nowDayCountMini;
                                }else{
                                    $ndate = $nowDayCountMini;
                                }
                                if(strlen($month)==1){
                                    $mon = "0".$month;
                                }else{
                                    $mon = $month;
                                }
                                $key = $year."-".$mon."-".$ndate;

                                ?>
                                <div >
                                    <!-- 일주일 내의 일을 나누는 for 문 -->
                                    <?php if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ) ) == 6 ) { 	// 토요일
                                        if( date("d") == $nowDayCountMini  && date("m") == $month ) { ?>
                                            <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date sat today" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p style="color:orange;font-weight:bold"><b><?php echo $nowDayCountMini++?></b></p></a>
                                        <?php	} else {	?>
                                            <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date sat" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p ><?php echo $nowDayCountMini++?></p></a>
                                        <?php	}
                                    } else if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ) ) == 0 ) { 	// 일요일
                                        if( date("d") == $nowDayCountMini && date("m") == $month) { ?>
                                            <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date sun today" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p style="color:orange;font-weight:bold"><b><?php echo $nowDayCountMini++?></b></p></a>
                                        <?php	} else {	?>
                                            <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date sun" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p ><?php echo $nowDayCountMini++?></p></a>
                                        <?php	}
                                    } else { 	// 평일
                                        if( strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday8_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday8_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday8_3) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays1"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays3"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays4"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays5"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays6"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays7"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays9"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays10"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday["holidays11"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday2_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday2_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCountMini, $year ))) == strtotime($holiday2_3)){//공휴일?>
                                            <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date sun today" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p ><b><?php echo $nowDayCountMini++?></b></p></a>
                                        <?php }else {
                                            if (date("d") == $nowDayCountMini && date("m") == $month) { ?>
                                                <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date today" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p style="font-weight:bold"><?php echo $nowDayCountMini++ ?></p></a>
                                            <?php } else { ?>
                                                <a href='#list_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>' class="calendar_date" id="cal_<?php echo (strlen($nowDayCountMini)==1)?"0".$nowDayCountMini:$nowDayCountMini;?>"><p><?php echo $nowDayCountMini++ ?></p></a>
                                            <?php }
                                        }
                                    }
                                    if(count($myschedule[$key]) >0){ echo "<span class='schedule_mores'></span>";}
                                    ?>
                                </div>
                                <?php	// 이전달이라면
                            } else if ( $cellIndex < $startDay ) {
                                $presmonMini = (strlen($prevMonthMini)==1)?"0".$prevMonthMini:$prevMonthMini;
                                $key = $year."-".$presmonMini."-".$prevDayCountMini;
                                ?>
                                <div >
                                    <div><p class="calendar_date other_m"><?php echo $prevDayCountMini++?></p></div>
                                    <?php
                                    if(count($myschedule[$key]) > 0){ echo "<span class=''></span>";}
                                    ?>
                                </div>
                                <?php 	// 다음달 이라면
                            } else if ( $cellIndex >= $days ) {
                                $nextmonMini = (strlen($nextMonthMini)==1)?"0".$nextMonthMini:$nextMonthMini;
                                $nextdayMini = (strlen($nextDayCountMini)==1)?"0".$nextDayCountMini:$nextDayCountMini;
                                $key = $year."-".$nextmonMini."-".$nextdayMini;
                                ?>
                                <div >
                                    <div><p class="calendar_date other_m"><?php echo $nextDayCountMini++?></p></div>
                                    <?php
                                    if(count($myschedule[$key]) > 0){ echo "<span class='schedule_mores'></span>";}
                                    ?>
                                </div>
                            <?php }
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="schedule_list_m schedules">
        <ul>
            <?php for( $rows = 0; $rows < $setRows; $rows++ ) { //주차를 나누는 for 문
                for( $cols = 0; $cols < 7; $cols++ )  {
                // 이번달이라면
                if ( $nowDayCountMini2 <= $days ) {
                    if(strlen($nowDayCountMini2)==1){
                        $ndate = "0".$nowDayCountMini2;
                    }else{
                        $ndate = $nowDayCountMini2;
                    }
                    if(strlen($month)==1){
                        $mon = "0".$month;
                    }else{
                        $mon = $month;
                    }
                    $key = $year."-".$mon."-".$ndate;
                    $dayMini2 = $year."-".$mon."-".$nowDayCountMini2++;
                    ?>
                    <li onclick="fnScheduleList('<?php echo $key;?>','<?php echo $constid;?>','m')" id="list_<?php echo $ndate;?>" class="<?php if(date("D",strtotime($dayMini2))=="Sun"){?>sun<?php }if(date("D",strtotime($dayMini2))=="Sat"){?>sat<?php }?> <?php if(count($myschedule)>=4){?>more<?php }?>">
                        <div>
                            <h2><?php echo date("d",strtotime($dayMini2));?></h2>
                            <h3><?php echo date("D",strtotime($dayMini2));?></h3>
                        </div>
                        <div>
                            <?php
                            if(count($myschedule[$key]) > 0){ ?>
                                <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                    $chkActive = 0;
                                    // 0 = 제출완료 , 1 = 제출지연, 3 = 지연제출, 2 = 제출예정
                                    if($myschedule[$key][$item]["pk_id"]!=""){
                                        $sch_pk_id = explode("``",$myschedule[$key][$item]["pk_id"]);
                                        for($chk=0;$chk<count($sch_pk_id);$chk++) {
                                            if ($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active_date"] != "0000-00-00") { //제출했음
                                                if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==1) {
                                                    if ($schdelaylist[$myschedule[$key][$item]["construct_id"] . "_" . $sch_pk_id[$chk]]["active_date"] > $key) { // 지연일때
                                                        $chkActive = 3;
                                                    } else { //지연이 아닐때
                                                        $chkActive = 0;
                                                    }
                                                }
                                            }else{ //제출안함
                                                if($schdelaylist[$myschedule[$key][$item]["construct_id"]."_".$sch_pk_id[$chk]]["active"]==0) {//0 = 제출안함
                                                    if(date("Y-m-d") <= $key) {
                                                        $chkActive = 2;
                                                    }else {
                                                        $chkActive = 1;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($item == 5){?><span class="list_more"></span><?php }
                                    if($item > 4 || $myschedule[$key][$item]["schedule_name"] == ""){continue;}
                                    ?>
                                    <p class="<?php if($chkActive==1){?>delays<?php }else if($chkActive==0){?>confirm<?php }else if($chkActive==3){?>lateconfirm<?php }?>" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>">
                                        <span><?php echo $myschedule[$key][$item]["schedule_name"];?></span>
                                    </p>
                                <?php }?>
                            <?php }else{?>
                                <div>일정이 없습니다.</div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php }?>
                <?php }?>
            <?php }?>
        </ul>
    </div>
    <section class="cal_data">
        <div class="cal_data_mobile_close" onclick="$('.cal_data').hide();">
            <img src="<?php echo G5_IMG_URL;?>/ic_arrow_down.svg" alt="">
        </div>
        <!--<div class="delay_msg_btns">
            <input type="button" value="TODAY" class="basic_btn02 " style="margin-right:20px;" onclick="location.href=g5_url+'/page/mypage/schedule?toYear<?php /*echo date("Y");*/?>&toMonth=<?php /*echo date("m");*/?>&constid=<?php /*echo $constid;*/?>'">
            <input type="button" class="basic_btn02" value="업무연락서" onclick="fnWriteMessage('')">
        </div>-->
        <div>
            <ul class="tab">
                <li class="active" >일정관리</li>
                <li>제출지연</li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="contruct_sel">
            <select name="cons_id" id="cons_id" onchange="fnScheduleConst(this.value)">
                <option value="">현장선택</option>
                <?php for($i=0;$i<count($mycont);$i++){?>
                    <option value="<?php echo $mycont[$i]["id"];?>" <?php if($constid==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                <?php }?>
            </select>
        </div>
        <div class="tab1">
            <div class="list_con">
                <input type="hidden" id="del_id" value="">
                <h2>일정목록 <!--<span class="del" onclick="fnScheduleDel();"></span>--></h2 >
                <ul class="schedule_list">

                </ul>
            </div>
            <div class="detail_list">
                <h2>상세목록 <span class="close" onclick="$('.detail_list').hide();"></span></h2>
                <div class="lists">

                </div>
            </div>
        </div>
        <div class="tab2">
            <div></div>
            <div class="list_con">
                <ul class="delay_list_ul">
                <?php for($i=0;$i<count($delaylists);$i++){
                    ?>
                    <li onclick="location.href=g5_url+'/page/view?me_id=<?php echo $delaylists[$i]["me_code"];?>&depth1_id=<?php echo $delaylists[$i]["depth1_id"];?>&depth2_id=<?php echo $delaylists[$i]["depth2_id"];?>&pk_id=<?php echo $delaylists[$i]["c_pk_id"];?>&constid=<?php echo $_REQUEST["constid"];?>'">
                        <?php if(substr($delaylists[$i]["me_code"],0,2)=="10"){?>
                        <div><?php echo "[".$delaylists[$i]["depth1_name"]."]".$delaylists[$i]["content"];?></div>
                        <?php }else{?>
                        <div><?php echo "[".$delaylists[$i]["depth1_name"]."]".$delaylists[$i]["depth_name"];?></div>
                        <?php }?>
                        <div><?php echo $delaylists[$i]["delay_date"];?></div>
                    </li>
                <?php }?>
                    <?php if(count($delaylists)==0){?>
                        <li class="empty_li" >제출지연 목록이 없습니다.</li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </section>
</div>
<script>
    function fnScheduleList(key,id,screen){
        if(!$(".d_"+key).hasClass("selected")) {
            $(".d_" + key).addClass("selected");
            $(".days").not($(".d_" + key)).removeClass("selected");
        }

        var mb_id = '<?php echo $member["mb_id"];?>';
        $.ajax({
            url:g5_url+'/page/ajax/ajax.schedule_list.php',
            method:"post",
            data:{date:key,mb_id:mb_id,const:id}
        }).done(function(data){
            $("#del_id").val('');
            $(".schedule_list").html('');
            $(".schedule_list").append(data);
            if(screen=="m"){
                $(".cal_data").show();
            }
        });
    }

    function fnScheduleConst(id){
        location.href=g5_url+'/page/mypage/schedule?type=schedule&constid='+id+"&toYear=<?php echo $year;?>&toMonth=<?php echo $month;?>";
    }

    function fnScheduleConUp(){
        var con = $("#schedule_con").val();
        var id = $("#edit_id").val();
        $.ajax({
            url:g5_url+"/page/ajax/ajax.schedule_content_up.php",
            method:"post",
            data:{id:id,con:con}
        }).done(function(data){
            //$("#debug").html("저장완료");
            if(data=="1") {
                alert("저장/등록 완료");
            }else{
                alert("저장 정보 오류");
            }
        });
    }

    function fnScheduleDel(){
        var id = $("#del_id").val();
        if(id==''){
            alert("삭제할 일정을 선택해 주세요.");
            return false;
        }
        $.ajax({
            url:g5_url+"/page/ajax/ajax.schedule_delete.php",
            method:"post",
            data:{id:id}
        }).done(function(data){
           $("#schedule_id_"+id).remove();
            $("#cal_"+id).remove();
        });
    }

    $(function(){

        //fnScheduleList("<?php echo date("Y-m-d");?>");

        /*$(document).scroll(function(){
           var top = $(this).scrollTop();
           if(top > 350){
               $(".cal_data").addClass("cal_top");
               $(".schedule_tbl_scroll").addClass("cal_top");
               $(".big_month").addClass("cal_top");
               $(".info").addClass("cal_top");
               $(".todays").addClass("cal_top");
               $("header.sub").addClass("cal_top");
               $(".backbg").addClass("active");
           }else{
               $(".cal_data").removeClass("cal_top");
               $(".schedule_tbl_scroll").removeClass("cal_top");
               $(".big_month").removeClass("cal_top");
               $(".info").removeClass("cal_top");
               $(".todays").removeClass("cal_top");
               $("header.sub").removeClass("cal_top");
               $(".backbg").removeClass("active");
           }
        });*/
        //$(".tab2").hide();

        $(".tab li").click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".tab li").not($(this)).removeClass("active");
            }
            if($(this).html()=="제출지연"){
                $(".tab1").hide();
                $(".tab2").show();

            }else{
                $(".tab1").show();
                $(".tab2").hide();
            }
        });
    });

    function fnSelectDate(year,month,constid){
        $.ajax({
            url:g5_url+'/page/modal/ajax.cal_date_sel.php',
            method:"post",
            data:{year:year,month:month,constid:constid}
        }).done(function(data){
            fnShowModal(data);
        });
    }

    function fnCalOn(){
        if(!$(".mini_calendar").hasClass('active')){
            $(".mini_calendar").addClass("active");
        }else{
            $(".mini_calendar").removeClass("active");
        }
    }
    $(function(){
        if($(window).width() < 1400) {
            var top = $(window).scrollTop();
            if (top > 160) {
                $(".header_top").css("position", "fixed");
                $(".cal").css({"position": "fixed", "top": "60px"});
                $(".mini_calendar").css({"position": "fixed", "top": "220px"});
                //chk = true;
            } else {
                $(".header_top").css("position", "relative");
                $(".cal").css({"position": "relative", "top": "0"});
                $(".mini_calendar").css({"position": "absolute", "top": "unset"})
                //chk = false;
            }
            $(window).scroll(function () {
                var top = $(this).scrollTop();
                if (top > 160) {
                    $(".header_top").css("position", "fixed");
                    $(".cal").css({"position": "fixed", "top": "60px"});
                    $(".mini_calendar").css({"position": "fixed", "top": "220px"});
                    //chk = true;
                } else {
                    $(".header_top").css("position", "relative");
                    $(".cal").css({"position": "relative", "top": "0"});
                    $(".mini_calendar").css({"position": "absolute", "top": "unset"})
                    //chk = false;
                }
            });
        }
        $(window).resize(function(){
            if($(this).width() < 1400) {
                $(window).scroll(function () {
                    var top = $(this).scrollTop();
                    if (top > 160) {
                        $(".header_top").css("position", "fixed");
                        $(".cal").css({"position": "fixed", "top": "60px"});
                        $(".mini_calendar.active").css({"position": "fixed", "top": "220px"});
                        //chk = true;
                    } else {
                        $(".header_top").css("position", "relative");
                        $(".cal").css({"position": "relative", "top": "0"});
                        $(".mini_calendar.active").css({"position": "absolute", "top": "unset"})
                        //chk = false;
                    }
                });
            }else{
                $(".header_top").css("position", "fixed");
            }
        });

        $('.calendar_date').on('click', function(event){
            //console.log($(this).attr("id"));
            event.preventDefault();
            var top = $(window).scrollTop();
            var hash = $(this).attr("id").replace("cal_","");

            console.log(top + "//" + hash);
            if(Number(hash) >= 4 ) {
                if(top<160){
                    console.log("A");
                    $('html,body').animate({scrollTop: $(this.hash).offset().top - 450}, 500);
                    $(".mini_calendar").removeClass("active");
                }else {
                    console.log("b");
                    $('html,body').animate({scrollTop: $(this.hash).offset().top - 230}, 500);
                    $(".mini_calendar").removeClass("active");
                }
            }else if(top > 150 && Number(hash) < 4){
                $('html,body').animate({scrollTop: 0}, 500);
                $(".mini_calendar").removeClass("active");
            }
        });
    });
    /*$(function(){
        var $footer = $("#ft").height();
        $(".schedule_list_m.schedules").css("height","calc(100vh - 60px - 24vw - "+$footer+"px)");
    });

    $(window).resize(function(){
        var $footer = $("#ft").height();
        $(".schedule_list_m.schedules").css("height","calc(100vh - 60px - 24vw - "+$footer+"px)");
    });*/
</script>

<?php
include_once (G5_PATH."/_tail.php");
