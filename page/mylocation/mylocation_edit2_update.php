<?php
include_once ("../../common.php");

//기존 스케쥴 삭제
$sql = "delete from `cmap_myschedule` where construct_id = '{$constid}' and mb_id = '{$member["mb_id"]}'";

sql_query($sql);

$pk_ids = implode("``",$pk_id);
$pk_id_actives = implode("``",$pk_id_active);
$start_dates = implode("``",$start_date);
$end_dates = implode("``",$end_date);
$test_dates = implode("``",$test_date);
for($i=0;$i<count($pk_id_active);$i++){
    if($pk_id_active[$i]==1) {
        $activeid[] = $pk_id[$i];
        $activeinstarts[] = $start_date[$i];
        $activeinends[] = $end_date[$i];
        $activeintests[] = $test_date[$i];
        $a++;
    }
}

for($i=0;$i<count($activeid);$i++){
    $starts[$activeid[$i]] = $activeinstarts[$i];
    $ends[$activeid[$i]] = $activeinends[$i];
    $tests[$activeid[$i]] = $activeintests[$i];
}

//계약상 착공일 등록
$sql = "insert into `cmap_myschedule` set
          `mb_id` = '{$member["mb_id"]}',
          `schedule_name` = '계약상착공일',
          `schedule_date` = '{$date1}',
          `insert_date` = now(),
          `update_date` = now(),
          `construct_id` = '{$constid}',
          `pk_id` = '',
          status = 0,
          schedule_type = 0
        ";
sql_query($sql);

//실 착공일 등록
$sql = "insert into `cmap_myschedule` set 
          `mb_id` = '{$member["mb_id"]}',
          `schedule_name` = '실착공일',
          `schedule_date` = '{$date2}',
          `insert_date` = now(),
          `update_date` = now(),
          `construct_id` = '{$constid}',
          `pk_id` = '',
          status = 0,
          schedule_type = 0
        ";
sql_query($sql);

//실 착공일 등록
$sql = "insert into `cmap_myschedule` set 
          `mb_id` = '{$member["mb_id"]}',
          `schedule_name` = '준공일',
          `schedule_date` = '{$date3}',
          `insert_date` = now(),
          `update_date` = now(),
          `construct_id` = '{$constid}',
          `pk_id` = '',
          status = 0,
          schedule_type = 0
        ";
sql_query($sql);

//입주 예정일 등록
$sql = "insert into `cmap_myschedule` set 
          `mb_id` = '{$member["mb_id"]}',
          `schedule_name` = '입주예정일',
          `schedule_date` = '{$date4}',
          `insert_date` = now(),
          `update_date` = now(),
          `construct_id` = '{$constid}',
          `pk_id` = '',
          status = 0,
          schedule_type = 0
        ";
sql_query($sql);

// 실착공일 및 준공일 기준 건설행정 스케쥴 등록
// 건설 행정 목록 불러오기
$sql = "select m.id as id from `cmap_menu` as c left join `cmap_depth1` as m on c.menu_code = m.me_code where SUBSTR(c.menu_code,1,2) = 10 and c.menu_code != 10 and c.menu_status = 0 order by c.menu_order";
$ress = sql_query($sql);
while($me_code = sql_fetch_array($ress)) {
    $sql = "select c.submit_date_type,c.submit_date,c.content,c.pk_id as pk_id,c.depth1_id as depth1_id,c.depth2_id as depth2_id,c.depth3_id as depth3_id,c.depth4_id as depth4_id,b.pk_id as depth4_pk_id from `cmap_content` as c left join `cmap_depth4` as b on c.depth4_id = b.id where c.submit_date_type != -1 and c.depth1_id = '{$me_code["id"]}' order by c.depth1_id desc, c.depth2_id desc, c.depth3_id desc, c.depth4_id desc, c.submit_date_type asc";
    $res = sql_query($sql);

    while ($row = sql_fetch_array($res)) {
        if($row["submit_date_type"] == 2) continue;

        if (strpos($row["submit_date"], "-") !== false) {
            $date = $row["submit_date"]." day ";
        } else {
            $date = "+ " . $row["submit_date"]." day ";
        }

        if ($row["submit_date_type"] == 0) { //계약상착공일
            $nowdate = date("Y-m-d", strtotime($date, strtotime($date1)));
        }
        if ($row["submit_date_type"] == 1) { //입주예정일
            $nowdate = date("Y-m-d", strtotime($date, strtotime($date4)));
        }
        if ($row["submit_date_type"] == 3) { // 준공일
            $nowdate = date("Y-m-d", strtotime($date, strtotime($date3)));
        }
        if ($row["submit_date_type"] == 4) { // 실착곡일
            $nowdate = date("Y-m-d", strtotime($date , strtotime($date2)));
        }

        $nowYear = substr($nowdate,0,4);
        //공휴일 체크
        $holiday = sql_fetch("select * from `cmap_holidays` where year = '{$nowYear}' ");

        $holiday2 = explode("~",$holiday["holidays2"]);
        $holiday2_1 = date("Y-m-d", strtotime("+ 1 day", strtotime($holiday2[0])));
        $holiday8 = explode("~",$holiday["holidays8"]);
        $holiday8_1 = date("Y-m-d", strtotime("+ 1 day", strtotime($holiday8[0])));

        $w = date("w", strtotime($nowdate));

        if ($w == 0) { //일요일
            $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
        }
        if ($w == 6) { //토요일
            $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
        }

        //1일 공휴일 or 공휴일 시작일
        if (strtotime($nowdate) == strtotime($holiday["holidays1"]) || strtotime($nowdate) == strtotime($holiday["holidays3"]) || strtotime($nowdate) == strtotime($holiday["holidays4"]) || strtotime($nowdate) == strtotime($holiday["holidays5"]) || strtotime($nowdate) == strtotime($holiday["holidays6"]) || strtotime($nowdate) == strtotime($holiday["holidays7"]) || strtotime($nowdate) == strtotime($holiday["holidays9"]) || strtotime($nowdate) == strtotime($holiday["holidays10"]) || strtotime($nowdate) == strtotime($holiday["holidays11"]) || strtotime($nowdate) == strtotime($holiday2[0]) || strtotime($nowdate) == strtotime($holiday8[0])) {
            $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
        }

        //추석 /설 중간일
        if (strtotime($nowdate) == strtotime($holiday2_1) || strtotime($nowdate) == strtotime($holiday8_1)) {
            $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
        }

        //추석 /설 마지막일
        if (strtotime($nowdate) == strtotime($holiday2[1]) || strtotime($nowdate) == strtotime($holiday8[1])) {
            $nowdate = date("Y-m-d", strtotime("-3 day", strtotime($nowdate)));
        }

        //최종 확인

        $w = date("w", strtotime($nowdate));

        if ($w == 0) { //일요일
            $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
        }
        if ($w == 6) { //토요일
            $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
        }

        //echo $row["submit_date"]."//".$row["submit_date_type"]."//".date("Y-m-d" ,strtotime($date." day",strtotime($nowdate)))."<br>";
        $schedule_date = date("Y-m-d", strtotime($nowdate));
        $sql = "insert into `cmap_myschedule` set 
                                  `mb_id` = '{$member["mb_id"]}',
                                  `schedule_name` = '".str_replace("'","\'",$row["content"])."',
                                  `schedule_date` = '{$schedule_date}',
                                  `insert_date` = now(),
                                  `update_date` = now(),
                                  `construct_id` = '{$constid}',
                                  `pk_id` = '{$row["pk_id"]}',
                                  `depth4_pk_id` = '{$row["depth4_pk_id"]}',
                                  status = 0,
                                  schedule_type = 1
                                ";
        sql_query($sql);


        $map_pk_ids[] = $row["pk_id"];
        $map_pk_actives[] = "0";
        $map_pk_actives_date[] = "0000-00-00";
    }
}


// 실착공일 및 준공일 기준 공사관리 스케쥴 등록
// 현재 설정된 항목망 저장하기
$a = 0;
$chkId = implode(",",$activeid);
$sql = "select d.id as id, c.menu_name as menu_name, d.depth_name as depth_name,d.pk_id as pk_id from `cmap_depth1` as d left join `cmap_menu` as c on d.me_code = c.menu_code where d.pk_id in ({$chkId}) order by d.id ";
$res = sql_query($sql);
while ($row = sql_fetch_array($res)) {
    $schedule_name = $row["depth_name"]." | ";

    $sql = "select depth_name,id,pk_id from `cmap_depth4` where depth1_id = '{$row["id"]}' order by id";
    $ress = sql_query($sql);
    $i=0;
    while($rowss = sql_fetch_array($ress)) {
        $sch_name[$i]["title"] = $schedule_name . $rowss["depth_name"];
        //$pkids[$i]=$rowss["depth1_id"];

        $sql = "select submit_date,submit_date_type,pk_id,content from `cmap_content` where depth4_id = '{$rowss["id"]}' and (`submit_date_type` = 0 or `submit_date_type` = 3 or `submit_date_type` = 1 or `submit_date_type` = 2) order by id ";
        $res2 = sql_query($sql);
    while($row2 = sql_fetch_array($res2)) {
            if (strpos($row2["submit_date"], "-") !== false) {
                $date = $row2["submit_date"]." day";
            } else {
                $date = "+" . $row2["submit_date"]. " day";
            }

            if ($row2["submit_date_type"] == 0) {
                $nowdate = date("Y-m-d", strtotime($date, strtotime($starts[$row["pk_id"]])));
            }
            if ($row2["submit_date_type"] == 3) {
                $nowdate = date("Y-m-d", strtotime($date, strtotime($ends[$row["pk_id"]])));
            }
            if ($row2["submit_date_type"] == 1) {
                $nowdate = date("Y-m-d", strtotime($date, strtotime($date4)));
            }
            if ($row2["submit_date_type"] == 2) {
                $nowdate = date("Y-m-d", strtotime($date, strtotime($tests[$row["pk_id"]])));
            }
            $nowYear = substr($nowdate,0,4);
            //공휴일 체크
            $holiday = sql_fetch("select * from `cmap_holidays` where year = '{$nowYear}' ");

            $holiday2 = explode("~",$holiday["holidays2"]);
            $holiday2_1 = date("Y-m-d", strtotime("+ 1 day", strtotime($holiday2[0])));
            $holiday8 = explode("~",$holiday["holidays8"]);
            $holiday8_1 = date("Y-m-d", strtotime("+ 1 day", strtotime($holiday8[0])));

            $w = date("w", strtotime($nowdate));

            if ($w == 0) { //일요일
                $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
            }
            if ($w == 6) { //토요일
                $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
            }

            //echo $row["pk_id"]."//".$row2["submit_date_type"]."//".$date."//".$starts[$row["pk_id"]]."//".$ends[$row["pk_id"]]."//".$nowdate."<br>";
            if (strtotime($nowdate) == strtotime($holiday["holidays1"]) || strtotime($nowdate) == strtotime($holiday["holidays3"]) || strtotime($nowdate) == strtotime($holiday["holidays4"]) || strtotime($nowdate) == strtotime($holiday["holidays5"]) || strtotime($nowdate) == strtotime($holiday["holidays6"]) || strtotime($nowdate) == strtotime($holiday["holidays7"]) || strtotime($nowdate) == strtotime($holiday["holidays9"]) || strtotime($nowdate) == strtotime($holiday["holidays10"]) || strtotime($nowdate) == strtotime($holiday["holidays11"]) || strtotime($nowdate) == strtotime($holiday2[0]) || strtotime($nowdate) == strtotime($holiday8[0])) {
                $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
            }

            if (strtotime($nowdate) == strtotime($holiday2[1]) || strtotime($nowdate) == strtotime($holiday8[1])) {
                $nowdate = date("Y-m-d", strtotime("-3 day", strtotime($nowdate)));
            }

            if (strtotime($nowdate) == strtotime($holiday2_1) || strtotime($nowdate) == strtotime($holiday8_1)) {
                $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
            }

            //최종확인
            $w = date("w", strtotime($nowdate));

            if ($w == 0) { //일요일
                $nowdate = date("Y-m-d", strtotime("-2 day", strtotime($nowdate)));
            }
            if ($w == 6) { //토요일
                $nowdate = date("Y-m-d", strtotime("-1 day", strtotime($nowdate)));
            }

            $s_date[$i]["sch_date"] = $nowdate;
            //echo $rowss["submit_date"]."//".$rowss["submit_date_type"]."//".$date."//".$schedule_date."//".$nowdate."<br>";
            $pk_id_sql[$i][] = $row2["pk_id"];

            $map_pk_ids[] = $row2["pk_id"];
            $map_pk_actives[] = "0";
            $map_pk_actives_date[] = "0000-00-00";

        }
        $pk = implode("``",$pk_id_sql[$i]);
        if($pk != "") {
            $sql = "insert into `cmap_myschedule` set 
              `mb_id` = '{$member["mb_id"]}',
              `schedule_name` = '{$sch_name[$i]["title"]}',
              `schedule_date` = '{$s_date[$i]["sch_date"]}',
              `insert_date` = now(),
              `update_date` = now(),
              `construct_id` = '{$constid}',
              `pk_id` = '{$pk}',
              `depth4_pk_id` = '{$rowss["pk_id"]}',
              status = 0,
              schedule_type = 2
            ";

            //echo $sql."//".$starts[$row["pk_id"]]."//".$date."<br>";

            sql_query($sql);
            //echo $sql . "<br>";
            unset($sch_name);
            unset($pkids);
            unset($pk_id_sql);
            unset($s_date);
        }
        $i++;
    }
}


$sql = "update `cmap_my_construct` SET
    cmap_construct_start_temp = '{$date1}',
    cmap_construct_start = '{$date2}',
    cmap_construct_finish = '{$date3}',
    cmap_construct_inmove = '{$date4}',
    pk_ids = '{$pk_ids}',
    pk_ids_actives = '{$pk_id_actives}',
    start_date = '{$start_dates}',
    end_date = '{$end_dates}',
    test_date = '{$test_dates}'
    where id = '{$constid}'
    ";

if(sql_query($sql)){
    //완료후 개인설정에도 저장
    $map_pks = implode("``",$map_pk_ids);
    $map_pkact = implode("``",$map_pk_actives);
    $map_pktime = implode("``",$map_pk_actives_date);

    $sql = "select * from `cmap_my_construct_map` where mb_id= '{$member["mb_id"]}' and const_id = '{$constid}'";
    $chkMap = sql_fetch($sql);
    if($chkMap==null){
        $sql = "insert into `cmap_my_construct_map` set pk_ids = '{$map_pks}',pk_actives = '{$map_pkact}', pk_actives_date = '{$map_pktime}', mb_id= '{$member["mb_id"]}', const_id = '{$constid}'";
    }else {
        $sql = "update `cmap_my_construct_map` set pk_ids = '{$map_pks}',pk_actives = '{$map_pkact}', pk_actives_date = '{$map_pktime}' where  mb_id= '{$member["mb_id"]}' and const_id = '{$constid}'";
    }
    sql_query($sql);

    $sql = "select * from `cmap_content` where pk_id not in ('{$map_pks2}')";
    $finres = sql_query($sql);
    while($row = sql_fetch_array($finres)){
        $sql = "select *,b.menu_status as menu_status from `cmap_depth1` as a left `cmap_menu` as b on a.me_code = b.menu_code where a.id = '{$row["depth1_id"]}'";
        $chk_menu = sql_fetch($sql);
        if($chk_menu["menu_status"]!=0){continue;}
        $map_other_pk[] = $row["pk_id"];
        $map_other_pk_active[] = "0";
        $map_other_pk_dates_active[] = "0000-00-00";
    }

    $map_pk_other = implode("``",$map_other_pk);
    $map_pk_active_other = implode("``",$map_other_pk_active);
    $map_pk_active_dates_other = implode("``",$map_other_pk_dates_active);

    $sql = "update `cmap_my_construct_map` set pk_ids_other = '{$map_pk_other}', pk_actives_other = '{$map_pk_active_other}', pk_actives_dates_other = '{$map_pk_active_dates_other}' where mb_id= '{$member["mb_id"]}' and const_id = '{$constid}'";
    sql_query($sql);

    alert("현장 수정이 완료 되었습니다.",G5_URL."/page/mylocation/mylocation");
}else{
    alert("수정오류 입니다.\\r 다시 시도해 주세요." );
}
?>