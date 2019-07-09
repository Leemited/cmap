<?php
include_once ("../../common.php");

if($member["mb_level"]<5){
    alert("권한이 없습니다.", G5_URL);
}

$today = date("Y-m-d");

if($sfl==1){
    $where .= " and cmap_construct_finish < '{$todays}";
}

$sql = "select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}')!=0 and status = 0  {$where} order by id desc";
$res = sql_query($sql);
$c=0;
while($row = sql_fetch_array($res)){
    $worklist[$c] = $row;
    $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$row["id"]}'";
    $ss = sql_fetch($sql);
    if($ss!=null) {
        $eval1 = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$row["id"]}' and mb_id ='{$ss["set_mb_id"]}'");
    }else{
        $sql = "select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'";
        $ss2 = sql_fetch($sql);
        $eval1 = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$row["id"]}' and mb_id ='{$ss2["mb_id"]}'");
    }
    //echo $eval1["pk_score2_total"];
    $diveval = explode("``",$eval1["pk_score2_total"]);
    $worklist[$c]["eval_01"] = $diveval[0];
    $worklist[$c]["eval_02"] = $diveval[1];
    $worklist[$c]["eval_03"] = $diveval[2];
    $worklist[$c]["eval_04"] = $diveval[3];
    $worklist[$c]["eval_05"] = $diveval[4];
    $worklist[$c]["eval_06"] = $diveval[5];
    $worklist[$c]["eval_07"] = $diveval[6];
    $worklist[$c]["eval_08"] = $diveval[7];
    $sum1 = (double)$diveval[0]+(double)$diveval[1]+(double)$diveval[2];
    $sum2 = ((((double)$diveval[3]+(double)$diveval[4]+(double)$diveval[5])*0.8)+(double)$diveval[7])+(double)$diveval[6];
    $worklist[$c]["sum1"] = round($sum1,2);
    $worklist[$c]["sum2"] = round($sum2,2);


    //기간경과율 계산
    $chkstart[$c] = new DateTime($row["cmap_construct_start"]);
    $chktodayss[$c] = new DateTime($todays);
    $chkend[$c] = new DateTime($row["cmap_construct_finish"]);
    $totaldays = date_diff($chkstart[$c],$chkend[$c]);
    $nows = date_diff($chkstart[$c],$chktodayss[$c]);
    $totals = $totaldays->days;
    $nowdays = $nows->days;
    $dayper = ($nowdays / $totals) * 100;
    if($dayper>=100){
        if(date("Y",strtotime($row["cmap_construct_finish"])) == date("Y")){
            //올해
            $totaleval1_01 += $worklist[$c]["eval_01"];
            $totaleval1_02 += $worklist[$c]["eval_02"];
            $totaleval1_03 += $worklist[$c]["eval_03"];
            $totaleval1_04 += $worklist[$c]["eval_04"];
            $totaleval1_05 += $worklist[$c]["eval_05"];
            $totaleval1_06 += $worklist[$c]["eval_06"];
            $totaleval1_07 += $worklist[$c]["eval_07"];
            $totaleval1_08 += $worklist[$c]["eval_08"];
            $totaleval1_09 += $worklist[$c]["sum1"];
            $totaleval1_10 += $worklist[$c]["sum2"];
            $totaleval1_cnt++;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 1 year"))){
            //작년
            $totaleval2_01 += $worklist[$c]["eval_01"];
            $totaleval2_02 += $worklist[$c]["eval_02"];
            $totaleval2_03 += $worklist[$c]["eval_03"];
            $totaleval2_04 += $worklist[$c]["eval_04"];
            $totaleval2_05 += $worklist[$c]["eval_05"];
            $totaleval2_06 += $worklist[$c]["eval_06"];
            $totaleval2_07 += $worklist[$c]["eval_07"];
            $totaleval2_08 += $worklist[$c]["eval_08"];
            $totaleval2_09 += $worklist[$c]["sum1"];
            $totaleval2_10 += $worklist[$c]["sum2"];
            $totaleval2_cnt++;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 2 year"))){
            //재작년
            $totaleval3_01 += $worklist[$c]["eval_01"];
            $totaleval3_02 += $worklist[$c]["eval_02"];
            $totaleval3_03 += $worklist[$c]["eval_03"];
            $totaleval3_04 += $worklist[$c]["eval_04"];
            $totaleval3_05 += $worklist[$c]["eval_05"];
            $totaleval3_06 += $worklist[$c]["eval_06"];
            $totaleval3_07 += $worklist[$c]["eval_07"];
            $totaleval3_08 += $worklist[$c]["eval_08"];
            $totaleval3_09 += $worklist[$c]["sum1"];
            $totaleval3_10 += $worklist[$c]["sum2"];
            $totaleval3_cnt++;
        }
        $alltot++;
        $alltotal1 = (double)$totaleval1_01+(double)$totaleval2_01+(double)$totaleval3_01;
        $alltotal2 = (double)$totaleval1_02+(double)$totaleval2_02+(double)$totaleval3_02;
        $alltotal3 = (double)$totaleval1_03+(double)$totaleval2_03+(double)$totaleval3_03;
        $alltotal4 = (double)$totaleval1_04+(double)$totaleval2_04+(double)$totaleval3_04;
        $alltotal5 = (double)$totaleval1_05+(double)$totaleval2_05+(double)$totaleval3_05;
        $alltotal6 = (double)$totaleval1_06+(double)$totaleval2_06+(double)$totaleval3_06;
        $alltotal7 = (double)$totaleval1_07+(double)$totaleval2_07+(double)$totaleval3_07;
        $alltotal8 = (double)$totaleval1_08+(double)$totaleval2_08+(double)$totaleval3_08;
        $alltotal9 = (double)$totaleval1_09+(double)$totaleval2_09+(double)$totaleval3_09;
        $alltotal10 = (double)$totaleval1_10+(double)$totaleval2_10+(double)$totaleval3_10;

        $alltotal11 = round((($totaleval1_09 * 0.8) + ($totaleval1_10 * 0.2)) / $totaleval1_cnt ,2);
        $alltotal12 = round((($totaleval2_09 * 0.8) + ($totaleval2_10 * 0.2)) / $totaleval2_cnt,2);
        $alltotal13 = round((($totaleval3_09 * 0.8) + ($totaleval3_10 * 0.2)) / $totaleval3_cnt,2);

        $alls = round(((($totaleval1_09 + $totaleval2_09 + $totaleval3_09) *0.8) + (($totaleval1_10 + $totaleval2_10 + $totaleval3_10)*0.2)) / ($totaleval1_cnt + $totaleval2_cnt + $totaleval3_cnt),2);
    }


    $c++;
}
if($totaleval1_01>0){
    $totaltoyear[0] = $totaleval1_01 / $totaleval1_cnt;
    $totaltoyear[1] = $totaleval1_02 / $totaleval1_cnt;
    $totaltoyear[2] = $totaleval1_03 / $totaleval1_cnt;
    $totaltoyear[3] = $totaleval1_04 / $totaleval1_cnt;
    $totaltoyear[4] = $totaleval1_05 / $totaleval1_cnt;
    $totaltoyear[5] = $totaleval1_06 / $totaleval1_cnt;
    $totaltoyear[6] = $totaleval1_07 / $totaleval1_cnt;
    $totaltoyear[7] = $totaleval1_08 / $totaleval1_cnt;
    $totaltoyear[8] = $totaleval1_09 / $totaleval1_cnt;
    $totaltoyear[9] = $totaleval1_10 / $totaleval1_cnt;
}

if($totaleval2_01>0){
    $totaltoyear2[0] = $totaleval2_01 / $totaleval2_cnt;
    $totaltoyear2[1] = $totaleval2_02 / $totaleval2_cnt;
    $totaltoyear2[2] = $totaleval2_03 / $totaleval2_cnt;
    $totaltoyear2[3] = $totaleval2_04 / $totaleval2_cnt;
    $totaltoyear2[4] = $totaleval2_05 / $totaleval2_cnt;
    $totaltoyear2[5] = $totaleval2_06 / $totaleval2_cnt;
    $totaltoyear2[6] = $totaleval2_07 / $totaleval2_cnt;
    $totaltoyear2[7] = $totaleval2_08 / $totaleval2_cnt;
    $totaltoyear2[8] = $totaleval2_09 / $totaleval2_cnt;
    $totaltoyear2[9] = $totaleval2_10 / $totaleval2_cnt;

}

if($totaleval3_01>0){
    $totaltoyear3[0] = $totaleval3_01 / $totaleval3_cnt;
    $totaltoyear3[1] = $totaleval3_02 / $totaleval3_cnt;
    $totaltoyear3[2] = $totaleval3_03 / $totaleval3_cnt;
    $totaltoyear3[3] = $totaleval3_04 / $totaleval3_cnt;
    $totaltoyear3[4] = $totaleval3_05 / $totaleval3_cnt;
    $totaltoyear3[5] = $totaleval3_06 / $totaleval3_cnt;
    $totaltoyear3[6] = $totaleval3_07 / $totaleval3_cnt;
    $totaltoyear3[7] = $totaleval3_08 / $totaleval3_cnt;
    $totaltoyear3[8] = $totaleval3_09 / $totaleval3_cnt;
    $totaltoyear3[9] = $totaleval3_10 / $totaleval3_cnt;
}

$alls1 = $alltotal1 / $alltot;
$alls2 = $alltotal2 / $alltot;
$alls3 = $alltotal3 / $alltot;
$alls4 = $alltotal4 / $alltot;
$alls5 = $alltotal5 / $alltot;
$alls6 = $alltotal6 / $alltot;
$alls7 = $alltotal7 / $alltot;
$alls8 = $alltotal8 / $alltot;
$alls9 = $alltotal9 / $alltot;
$alls10 = $alltotal10 / $alltot;

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$const["cmap_name"]." 건설사업관리용역 평가 총괄표".date('Ymdhis').".xls" );
header( "Content-Description: PHP4 Generated Data" );
?>
<table class="view_table eval2_table" >
    <colgroup>
        <col width="5%">
        <col width="*">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
        <col width="6%">
    </colgroup>
    <tr>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">구분</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">현장명</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">담당</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">착공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">준공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">기간경과율</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">총점</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" colspan="4">업체 평가(A)</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" colspan="6">기술자 평가(B)</th>
    </tr>
    <tr>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">조직운영</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">현장지원</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">기술지원</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">소계</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">일반행정</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">시공관리</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">기술업무</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">가감점</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">시공상태</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">소계</th>
    </tr>
    <?php for($i=0;$i<count($worklist);$i++){
        $constmb = get_member($worklist[$i]["mb_id"]);
        //기간경과율 계산
        if(date("Y-m-d") <= $worklist[$i]["cmap_construct_start"]){
            $dayper = "0%";
        }else {
            $start[$i] = new DateTime($worklist[$i]["cmap_construct_start"]);
            $todayss[$i] = new DateTime($todays);
            $end[$i] = new DateTime($worklist[$i]["cmap_construct_finish"]);
            $totaldays = date_diff($start[$i], $end[$i]);
            $nows = date_diff($start[$i], $todayss[$i]);
            $totals = $totaldays->days;
            $nowdays = $nows->days;
            $dayper = round(($nowdays / $totals) * 100, 2);
            if ($dayper > 100) {
                $dayper = "준공";
            } else if ($dayper <= 99 && $dayper >= 0) {
                $dayper .= "%";
            } else {
                $dayper = "0%";
            }
        }
        ?>
        <tr>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo ($i+1);?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $worklist[$i]["id"];?>'"><?php echo $worklist[$i]["cmap_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $constmb["mb_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_start"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $dayper;?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo round(($worklist[$i]["sum1"]*0.8)+($worklist[$i]["sum2"]*0.2), 2);?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_01"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_02"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_03"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center eval_point"><?php echo $worklist[$i]["sum1"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_04"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_05"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_06"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_07"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_08"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center eval_point"><?php echo $worklist[$i]["sum2"];?></td>
        </tr>
        <?php
    } ?>
    <tr>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="5">구분</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">배점</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">총점</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">30</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">20</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">50</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">100</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">35</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">40</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">25</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">±5</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">20</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">105</td>
    </tr>
    <tr class="toyear">
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="5">최근 3개년 평균</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">3개년 평균</td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;"><?php echo $alls;?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls1,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls2,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls3,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls9,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls4,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls5,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls6,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls7,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls8,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls10,2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="5" rowspan="3">최근 3개년 년도별 평균 </td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo date("Y");?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;"><?php echo $alltotal11;?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[2],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[8],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[4],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[5],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[6],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[7],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[9],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo date("Y",strtotime("- 1 year"));?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;"><?php echo $alltotal12;?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[2],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[8],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[4],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[5],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[6],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[7],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[9],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo date("Y",strtotime("- 2 year"));?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;"><?php echo $alltotal13;?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[2],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[8],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[4],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[5],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[6],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[7],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[9],2);?></td>
    </tr>
</table>
