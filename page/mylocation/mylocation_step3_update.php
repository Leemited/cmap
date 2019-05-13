<?php
include_once ("../../common.php");

$pk_ids = implode("``",$pk_id);
$pk_id_actives = implode("``",$pk_id_active);
$start_dates = implode("``",$start_date);
$end_dates = implode("``",$end_date);
$test_dates = implode("``",$test_date);
for($i=0;$i<count($pk_id_active);$i++){
    if($pk_id_active[$i]==1) {
        $activeid[] = $pk_id[$i];
        $active_dates .= "``";
        $a++;
    }
}
for($i=0;$i<count($activeid);$i++){
    $starts[$activeid[$i]] = $start_date[$i];
    $ends[$activeid[$i]] = $end_date[$i];
    $tests[$activeid[$i]] = $test_date[$i];
}

if($type != "save") {

    $sql = "update `cmap_my_construct_temp` SET
        cmap_construct_start_temp = '{$date1}',
        cmap_construct_start = '{$date2}',
        cmap_construct_finish = '{$date3}',
        cmap_construct_inmove = '{$date4}',
        pk_ids = '{$pk_ids}',
        pk_ids_actives = '{$pk_id_actives}',
        start_date = '{$start_dates}',
        end_date = '{$end_dates}',
        test_date = '{$test_dates}',
        status = 1
        where id = '{$id}'
        ";

    sql_query($sql);

    $sql = "insert into `cmap_my_construct`  
                (cmap_name, cmap_company, cmap_company_ceo, cmap_company_num, cmap_construct_num, cmap_construct_price, cmap_construct_position, cmap_construct_name, cmap_construct_tel, cmap_construct_zipcode, cmap_construct_addr1, cmap_construct_addr2, cmap_construct_addr3, cmap_construct_jibeon, cmap_name_service, cmap_company_service, cmap_company_ceo_service, cmap_company_num_service, cmap_construct_num_service, cmap_construct_price_service, cmap_construct_position_service, cmap_construct_name_service, cmap_construct_tel_service, cmap_construct_zipcode_service, cmap_construct_addr1_service, cmap_construct_addr2_service, cmap_construct_addr3_service, cmap_construct_jibeon_service, cmap_construct_start_temp, cmap_construct_start, cmap_construct_finish,cmap_construct_inmove, insert_date, status, mb_id, pk_ids, pk_ids_actives, start_date, end_date,test_date,cmap_construct_lat,cmap_construct_lng,weather_addr1,weather_addr2,weather_addr3) select cmap_name, cmap_company, cmap_company_ceo, cmap_company_num, cmap_construct_num, cmap_construct_price, cmap_construct_position, cmap_construct_name, cmap_construct_tel, cmap_construct_zipcode, cmap_construct_addr1, cmap_construct_addr2, cmap_construct_addr3, cmap_construct_jibeon, cmap_name_service, cmap_company_service, cmap_company_ceo_service, cmap_company_num_service, cmap_construct_num_service, cmap_construct_price_service, cmap_construct_position_service, cmap_construct_name_service, cmap_construct_tel_service, cmap_construct_zipcode_service, cmap_construct_addr1_service, cmap_construct_addr2_service, cmap_construct_addr3_service, cmap_construct_jibeon_service, cmap_construct_start_temp, cmap_construct_start, cmap_construct_finish,cmap_construct_inmove, now(), 0, mb_id, pk_ids, pk_ids_actives, start_date, end_date,test_date, cmap_construct_lat,cmap_construct_lng,weather_addr1,weather_addr2,weather_addr3 from `cmap_my_construct_temp` where id = '{$id}'";

    if(sql_query($sql)){
        $id = sql_insert_id();

        $sql = "select * from `cmap_my_construct` where id = '{$id}'";
        $const = sql_fetch($sql);

        //현장 스케쥴 제출 상태 등록
        //제출 현황 개수 맞추기
        $in_pk_ids = explode("``",$const["pk_ids"]);
        for($i=0;$i<count($in_pk_ids);$i++){
            if($pk_active==""){
                $pk_active = "0";
                $pk_active_date = "0000-00-00";
            }else{
                $pk_active .= "``0";
                $pk_active_date .= "``0000-00-00";
            }
        }

        $sql = "insert into `cmap_my_construct_map` set const_id = '{$id}', mb_id='{$member["mb_id"]}' , pk_ids = '{$const["pk_ids"]}}', pk_actives = '{$pk_active}', pk_actives_date = '{$pk_active_date}'";
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

        $sql = "insert into `cmap_my_construct_eval` set const_id = '{$id}' , mb_id ='{$member["mb_id"]}', pk_ids1 = '{$evals}', pk_score1 = '{$eval1score}', pk_ids2 = '{$evals2}', pk_score2 = '{$eval2score}' , pk_score1_total = '0', pk_score2_total = '0'";
        sql_query($sql);

        //등록자 제출 현황 및 점수 등록
        $sql = "insert into `cmap_my_construct_map` SET 
                        mb_id ='{$member["mb_id"]}',
                        const_id = '{$id}',
                        pk_ids = '{$pk_ids}',
                        pk_actives = '{$pk_id_actives}',
                        pk_actives_date = '{$active_dates}'
                        ";
        sql_query($sql);

        $sql = "insert into `cmap_my_construct_eval` SET 
                        
                        ";

        //계약상 착공일 등록
        $sql = "insert into `cmap_myschedule` set
                  `mb_id` = '{$member["mb_id"]}',
                  `schedule_name` = '계약상착공일',
                  `schedule_date` = '{$date1}',
                  `insert_date` = now(),
                  `update_date` = now(),
                  `construct_id` = '{$id}',
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
                  `construct_id` = '{$id}',
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
                  `construct_id` = '{$id}',
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
          `construct_id` = '{$id}',
          `pk_id` = '',
          status = 0,
          schedule_type = 0
        ";
        sql_query($sql);


        // 실착공일 및 준공일 기준 건설행정 스케쥴 등록
        // 건설 행정 목록 불러오기
        $sql = "select *,m.id as id from `cmap_menu` as c left join `cmap_depth1` as m on c.menu_code = m.me_code where SUBSTR(c.menu_code,1,2) = 10 and c.menu_code != 10 and c.menu_status = 0 order by c.menu_order";
        $ress = sql_query($sql);
        while($me_code = sql_fetch_array($ress)) {
            $sql = "select * from `cmap_content` where submit_date_type != -1 and depth1_id = '{$me_code["id"]}' order by depth1_id desc, depth2_id desc, depth3_id desc, depth4_id desc, submit_date_type asc";
            $res = sql_query($sql);
            while ($row = sql_fetch_array($res)) {
                if($row["submit_date_type"]== 1 || $row["submit_date_type"] == 2) continue;

                if (strpos($row["submit_date"], "-") !== false) {
                    $date = $row["submit_date"]." day ";
                } else {
                    $date = "+ " . $row["submit_date"]." day ";
                }

                if ($row["submit_date_type"] == 0) { // 계약상착공일
                    $nowdate = date("Y-m-d", strtotime($date , strtotime($date1)));
                }
                if ($row["submit_date_type"] == 1) { // 입주예정일
                    $nowdate = date("Y-m-d", strtotime($date, strtotime($date4)));
                }
                if ($row["submit_date_type"] == 2) { //시험예정일
                    $nowdate = date("Y-m-d", strtotime($date, strtotime($tests[$row["pk_id"]])));
                }
                if ($row["submit_date_type"] == 3) { // 준공일
                    $nowdate = date("Y-m-d", strtotime($date, strtotime($date3)));
                }
                if ($row["submit_date_type"] == 4) { // 실착공일
                    $nowdate = date("Y-m-d", strtotime($date , strtotime($date2)));
                }

                $nowYear = substr($nowdate,0,4);
                //공휴일 체크
                $holiday = sql_fetch("select * from `cmap_holidays` where year = '{$nowYear}'");

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

                //최종확인
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
                                      `schedule_name` = '{$row["content"]}',
                                      `schedule_date` = '{$schedule_date}',
                                      `insert_date` = now(),
                                      `update_date` = now(),
                                      `construct_id` = '{$id}',
                                      `pk_id` = '{$row["pk_id"]}',
                                      status = 0,
                                      schedule_type = 1
                                    ";
                sql_query($sql);
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

            $sql = "select * from `cmap_depth4` where depth1_id = '{$row["id"]}' order by id";
            $ress = sql_query($sql);
            $i=0;
            while($rowss = sql_fetch_array($ress)) {
                $sch_name[$i]["title"] = $schedule_name . $rowss["depth_name"];
                //$pkids[$i]=$rowss["depth1_id"];

                $sql = "select * from `cmap_content` where depth4_id = '{$rowss["id"]}' and (`submit_date_type` = 0 or `submit_date_type` = 3 or `submit_date_type` = 1 or `submit_date_type` = 2) order by id ";
                $res2 = sql_query($sql);
                while($row2 = sql_fetch_array($res2)) {
                    if (strpos($row2["submit_date"], "-") !== false) {
                        $date = $row2["submit_date"]." day";
                    } else {
                        $date = "+" . $row2["submit_date"]. " day";
                    }
                    if ($row2["submit_date_type"] == 0) { //준공일
                        $nowdate = date("Y-m-d", strtotime($date, strtotime($starts[$row2["pk_id"]])));
                    }
                    if ($row2["submit_date_type"] == 1) { //입주예정일
                        $nowdate = date("Y-m-d", strtotime($date, strtotime($date4)));
                    }
                    if ($row2["submit_date_type"] == 2) { //시험예정일
                        $nowdate = date("Y-m-d", strtotime($date, strtotime($tests[$row2["pk_id"]])));
                    }
                    if ($row2["submit_date_type"] == 3) { //완공일
                        $nowdate = date("Y-m-d", strtotime($date, strtotime($ends[$row2["pk_id"]])));
                    }
                    if ($row2["submit_date_type"] == 4) { //실착공일
                        $nowdate = date("Y-m-d", strtotime($date , strtotime($date2)));
                    }

                    $nowYear = substr($nowdate,0,4);
                    //공휴일 체크
                    $holiday = sql_fetch("select * from `cmap_holidays` where year = '{$nowYear}'");

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

                }

                $pk = implode("``",$pk_id_sql[$i]);

                if($pk != "") {
                    $sql = "insert into `cmap_myschedule` set 
                      `mb_id` = '{$member["mb_id"]}',
                      `schedule_name` = '{$sch_name[$i]["title"]}',
                      `schedule_date` = '{$s_date[$i]["sch_date"]}',
                      `insert_date` = now(),
                      `update_date` = now(),
                      `construct_id` = '{$id}',
                      `pk_id` = '{$pk}',
                      status = 0,
                      schedule_type = 2
                    ";

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
        //완료후 개인설정에도 저장
        //평가 항목 별로 저장
        //$sql = "insert into `cmap_my_construct_eval` set mb_id = '{$member["mb_id"]}' ";

        alert("현장 등록이 완료 되었습니다.",G5_URL."/page/mylocation/mylocation");
    }else{
        alert("등록오류 입니다.\\r 다시 시도해 주세요." );
    }
}else {
    $sql = "update `cmap_my_construct_temp` SET 
        cmap_construct_start_temp = '{$date1}',
        cmap_construct_start = '{$date2}',
        cmap_construct_finish = '{$date3}',
        cmap_construct_inmove = '{$date4}',
        pk_ids = '{$pk_ids}',
        pk_ids_actives = '{$pk_id_actives}',
        start_date = '{$start_dates}',
        end_date = '{$end_dates}'
        where id = '{$id}'
        ";
    if (sql_query($sql)) {
        if ($type == "save") {
            goto_url(G5_URL . "/page/mylocation/mylocation_step3?id=" . $id);
        }
    } else {
        alert("문제가 발생되어 처음으로 돌아갑니다.", G5_URL . "/page/mylocation/mylocation_step1?id=" . $id);
    }
}
?>