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
    //pmsettings
    $sql = "select * from `cmap_pmmode_save` where mb_id = '{$member["mb_id"]}' and constid = '{$row["id"]}' and eval_type=1";
    $pmmd = sql_fetch($sql);

    $worklist[$c] = $row;
    $worklist[$c]["pm_price"] = (int)$pmmd["const_price"];
    $worklist[$c]["pm_percent"] = $pmmd["const_percent"];
    $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$row["id"]}'";
    $ss = sql_fetch($sql);
    if($ss!=null) {
        $eval1 = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$row["id"]}' and mb_id ='{$ss["set_mb_id"]}'");
    }else{
        $sql = "select * from `cmap_my_construct` where id = '{$row["id"]}'";
        $ss2 = sql_fetch($sql);
        $eval1 = sql_fetch("select * from `cmap_my_construct_eval` where const_id = '{$row["id"]}' and mb_id ='{$ss2["mb_id"]}'");
    }
    $diveval = explode("``",$eval1["pk_score1_total"]);
    $worklist[$c]["eval_01"] = $diveval[0];
    $worklist[$c]["eval_02"] = $diveval[1];
    $worklist[$c]["eval_03"] = $diveval[2];
    $sum = (double)$diveval[0]+(double)$diveval[1]+(double)$diveval[2];
    $worklist[$c]["sum"] = round($sum,2);


    //기간경과율 계산
    $chkstart[$c] = new DateTime($row["cmap_construct_start_temp"]);
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
            $totaleval1_04 += $worklist[$c]["sum"];
            $totaleval1_cnt++;
            $totaleval1_save += $worklist[$c]["pm_price"] * $worklist[$c]["pm_percent"];
            $totaleval1_save_cal += $worklist[$c]["pm_price"];
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 1 year"))){
            //작년
            $totaleval2_01 += $worklist[$c]["eval_01"];
            $totaleval2_02 += $worklist[$c]["eval_02"];
            $totaleval2_03 += $worklist[$c]["eval_03"];
            $totaleval2_04 += $worklist[$c]["sum"];
            $totaleval2_cnt++;
            $totaleval2_save += $worklist[$c]["pm_price"] * $worklist[$c]["pm_percent"];
            $totaleval2_save_cal += $worklist[$c]["pm_price"];
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 2 year"))){
            //재작년
            $totaleval3_01 += $worklist[$c]["eval_01"];
            $totaleval3_02 += $worklist[$c]["eval_02"];
            $totaleval3_03 += $worklist[$c]["eval_03"];
            $totaleval3_04 += $worklist[$c]["sum"];
            $totaleval3_cnt++;
            $totaleval3_save += $worklist[$c]["pm_price"] * $worklist[$c]["pm_percent"];
            $totaleval3_save_cal += $worklist[$c]["pm_price"];
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 3 year"))){
            //4년전
            $totaleval4_01 += $worklist[$c]["eval_01"];
            $totaleval4_02 += $worklist[$c]["eval_02"];
            $totaleval4_03 += $worklist[$c]["eval_03"];
            $totaleval4_04 += $worklist[$c]["sum"];
            $totaleval4_cnt++;
            $totaleval4_save += $worklist[$c]["pm_price"] * $worklist[$c]["pm_percent"];
            $totaleval4_save_cal += $worklist[$c]["pm_price"];
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 4 year"))){
            //5년전
            $totaleval5_01 += $worklist[$c]["eval_01"];
            $totaleval5_02 += $worklist[$c]["eval_02"];
            $totaleval5_03 += $worklist[$c]["eval_03"];
            $totaleval5_04 += $worklist[$c]["sum"];
            $totaleval5_cnt++;
            $totaleval5_save += $worklist[$c]["pm_price"] * $worklist[$c]["pm_percent"];
            $totaleval5_save_cal += $worklist[$c]["pm_price"];
        }
        $alltot++;
        $alltotal1 = $totaleval1_01+$totaleval2_01+$totaleval3_01;
        $alltotal2 = (double)$totaleval1_02+(double)$totaleval2_02+(double)$totaleval3_02;
        $alltotal3 = (double)$totaleval1_03+(double)$totaleval2_03+(double)$totaleval3_03;
        $alltotal4 = (double)$totaleval1_04+(double)$totaleval2_04+(double)$totaleval3_04;
    }

    /*else{
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y")){
            //올해
            $totaleval1_01 += 0;
            $totaleval1_02 += 0;
            $totaleval1_03 += 0;
            $totaleval1_04 += 0;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 1 year"))){
            //작년
            $totaleval2_01 += 0;
            $totaleval2_02 += 0;
            $totaleval2_03 += 0;
            $totaleval2_04 += 0;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 2 year"))){
            //재작년
            $totaleval3_01 += 0;
            $totaleval3_02 += 0;
            $totaleval3_03 += 0;
            $totaleval3_04 += 0;
        }
    }*/

    $c++;
}
if($totaleval1_01>0){
    $totaltoyear[0] = $totaleval1_01 / $totaleval1_cnt;
    $totaltoyear[1] = $totaleval1_02 / $totaleval1_cnt;
    $totaltoyear[2] = $totaleval1_03 / $totaleval1_cnt;
    $totaltoyear[3] = $totaleval1_04 / $totaleval1_cnt;
}

if($totaleval2_01>0){
    $totaltoyear2[0] = $totaleval2_01 / $totaleval2_cnt;
    $totaltoyear2[1] = $totaleval2_02 / $totaleval2_cnt;
    $totaltoyear2[2] = $totaleval2_03 / $totaleval2_cnt;
    $totaltoyear2[3] = $totaleval2_04 / $totaleval2_cnt;
}

if($totaleval3_01>0){
    $totaltoyear3[0] = $totaleval3_01 / $totaleval3_cnt;
    $totaltoyear3[1] = $totaleval3_02 / $totaleval3_cnt;
    $totaltoyear3[2] = $totaleval3_03 / $totaleval3_cnt;
    $totaltoyear3[3] = $totaleval3_04 / $totaleval3_cnt;
}


$alls1 = $alltotal1 / $alltot;
$alls2 = $alltotal2 / $alltot;
$alls3 = $alltotal3 / $alltot;
$alls4 = $alltotal4 / $alltot;

$yearTotalPmPrice = ($totaleval1_save+$totaleval2_save+$totaleval3_save) / ($totaleval1_save_cal+$totaleval2_save_cal+$totaleval3_save_cal);

$yearPMPer1 = round($totaleval1_save/$totaleval1_save_cal,2);
$yearPMPer2 = round($totaleval2_save/$totaleval2_save_cal,2);
$yearPMPer3 = round($totaleval3_save/$totaleval3_save_cal,2);
$yearPMPer4 = round($totaleval4_save/$totaleval4_save_cal,2);
$yearPMPer5 = round($totaleval5_save/$totaleval5_save_cal,2);

$userAgent = $_SERVER["HTTP_USER_AGENT"];
if ( preg_match("/MSIE*/", $userAgent) ) {
    // 익스플로러
    $ie = "ie";
} elseif ( preg_match("/Trident*/", $userAgent) &&  preg_match("/rv:11.0*/", $userAgent) &&  preg_match("/Gecko*/", $userAgent)) {
    $ie = "ie 11";
}

if($ie){
    $filename = iconv("utf-8","euc-kr","시공 평가 총괄표".date('Ymdhis').".xls" );
}else{
    $filename = "시공 평가 총괄표".date('Ymdhis').".xls" ;
}

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$filename );
header( "Content-Description: PHP4 Generated Data" );
?>

<table class="view_table" >
    <colgroup>
        <col width="7.72%">
        <col width="15%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
        <col width="7.72%">
    </colgroup>
    <tr>
        <th colspan="12" style="border:1px solid #000;font-size:30px;text-align: center">시공 평가 총괄표</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <!--<th rowspan="2">구분</th>-->
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">구분</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">현장명</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">담당</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">착공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">준공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">기간경과율</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">계약금액</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">평가기관<br>통보점수</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" rowspan="2">시공평가점수</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff" colspan="3">시공평가 100(점)</th>
    </tr>
    <tr>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">공사관리</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">품질 및 성능</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">가감점</th>
    </tr>
    <?php for($i=0;$i<count($worklist);$i++){
        $constmb = get_member($worklist[$i]["mb_id"]);
        //기간경과율 계산
        if(date("Y-m-d") <= $worklist[$i]["cmap_construct_start_temp"]){
            $dayper = "0%";
        }else {
            $start[$i] = new DateTime($worklist[$i]["cmap_construct_start_temp"]);
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
            <!--<td class="td_center">
                <input type="checkbox" name="const_id[]" id="const_<?php /*echo $worklist[$i]["id"];*/?>" checked value="<?php /*echo $worklist[$i]["id"];*/?>">
                <label for="const_<?php /*echo $worklist[$i]["id"];*/?>"></label>
            </td>-->
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center">
                <?php echo $i+1;?>
            </td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $worklist[$i]["id"];?>'"><?php echo $worklist[$i]["cmap_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $constmb["mb_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_start_temp"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $dayper;?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;background-color:#ADD8E6" class="td_center"><?php echo ($worklist[$i]["pm_price"])?$worklist[$i]["pm_price"]:"";?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;background-color:#ADD8E6" class="td_center"><?php echo ($worklist[$i]["pm_percent"])?$worklist[$i]["pm_percent"]:"";?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;background-color:#ADD8E6" class="td_center eval_point"><?php echo $worklist[$i]["sum"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_01"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_02"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["eval_03"];?></td>
        </tr>
        <?php
    } ?>
    <tr>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="5">구분</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">배점</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="2">계약금 가중 평균</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">100</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">65</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">35</td>
        <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"></td>
    </tr>
    <tr class="toyear">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center" colspan="5">최근 3개년 평균</td>
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center;">3개년 평균</td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center;" class="eval_point_td eval_point" colspan="2"><?php echo round($yearTotalPmPrice,2);?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls4,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls1,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls2,2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($alls3,2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center" colspan="5" rowspan="3">최근 3개년 년도별 평균 </td>
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center"><?php echo date("Y");?></td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center" class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer1;?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td eval_point"><?php echo round($totaltoyear[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear[2],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center"><?php echo date("Y",strtotime("- 1 year"));?></td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center" class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer2;?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td eval_point"><?php echo round($totaltoyear2[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear2[2],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center"><?php echo date("Y",strtotime("- 2 year"));?></td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center" class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer3;?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td eval_point"><?php echo round($totaltoyear3[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear3[2],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center" colspan="5" rowspan="2">3개년 이전</td>
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center"><?php echo date("Y",strtotime("- 3 year"));?></td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center" class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer4;?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td eval_point"><?php echo round($totaltoyear4[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear4[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear4[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear4[2],2);?></td>
    </tr>
    <tr class="years">
        <td style="background-color: #d4d2d3;color: #000;font-weight: normal;border:0.25pt solid #000;text-align: center"><?php echo date("Y",strtotime("- 4 year"));?></td>
        <td style="background-color:#ADD8E6;font-weight: normal;border:0.25pt solid #000;text-align: center" class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer5;?></td>
        <td style="background-color:#ADD8E6;border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td eval_point"><?php echo round($totaltoyear5[3],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear5[0],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear5[1],2);?></td>
        <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="eval_point_td"><?php echo round($totaltoyear5[2],2);?></td>
    </tr>
</table>

