<?php
include_once ("../../common.php");


$today = date("Y-m-d");

if($sfl==1){
    $where .= " and cmap_construct_finish < '{$todays}'";
}

if($sfl==2){
    $where .= " and cmap_construct_finish > '{$todays}'";
}

if($stx){
    $where .= " and (cmap_name like '%{$stx}%' or cmap_name_service like '%{$stx}%')";
}

$sql = "select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}')!=0 and status = 0  {$where} order by id desc";
$res = sql_query($sql);
$c = 0;
while($row = sql_fetch_array($res)){

    $worklist[$c] = $row;
    $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$row["id"]}'";
    $ss = sql_fetch($sql);
    if($ss!=null){
        //등록자 설정
        $activesql_pm = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$row["id"]}'";
        $activechk_pm = sql_fetch($activesql_pm);
        $map_pk_id_pm = explode("``",$activechk_pm["pk_ids"]);
        $map_pk_actives_pm = explode("``",$activechk_pm["pk_actives"]);
        $map_pk_actives_date_pm = explode("``",$activechk_pm["pk_actives_date"]);

        $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$row["id"]}' and schedule_date < '{$today}' and pk_id <> '' ";
        $delayres_pm = sql_query($delaysql_pm);
        $a=0;
        $delaycount=$delaydate=$totaldates=0;
        while($delayrow_pm = sql_fetch_array($delayres_pm)){
            $pk_ids = explode("``",$delayrow_pm["pk_id"]);

            $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

            $days = $diff / (60*60*24);
            for($i=0;$i<count($pk_ids);$i++){
                for($j=0;$j<count($map_pk_id_pm);$j++){
                    if($pk_ids[$i]==$map_pk_id_pm[$j]){
                        if($map_pk_actives_pm[$j]==0) {
                            $sql = "select *,c.pk_id as pk_id,d.pk_id as depth4_pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id,a.depth_name as depth1_name,d.depth_name as depth_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                            $ddd = sql_fetch($sql);
                            if(substr($ddd["me_code"],0,2)!=10) {
                                if (strpos($id[$c], $delayrow_pm["depth4_pk_id"]) !== false) {
                                    continue;
                                }
                            }
                            $id[$c] .= ',' . $delayrow_pm["depth4_pk_id"];
                            $delaycount++;
                            $delaydate += $days;
                        }
                        /*if($map_pk_actives_pm[$j]==1){
                            $delaycount--;
                            $delaydate -= $days;
                        }*/
                    }
                }
            }
        }
        $totaldates = round($delaydate / $delaycount,2);
        $worklist[$c]["delaycount"] = $delaycount;
        $worklist[$c]["delaydate"] = $delaydate;
        $worklist[$c]["delaytotal"] = $totaldates;
    }else{
        //등록이 안되어 있으면 개설자 설정
        //등록자 설정
        $activesql_pm = "select * from `cmap_my_construct_map` where mb_id ='{$row["mb_id"]}' and const_id = '{$row["id"]}'";
        $activechk_pm = sql_fetch($activesql_pm);
        $map_pk_id_pm = explode("``",$activechk_pm["pk_ids"]);
        $map_pk_actives_pm = explode("``",$activechk_pm["pk_actives"]);
        $map_pk_actives_date_pm = explode("``",$activechk_pm["pk_actives_date"]);

        $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$row["id"]}' and schedule_date < '{$today}' and pk_id <> '' ";
        $delayres_pm = sql_query($delaysql_pm);
        $a=0;
        $delaycount=$delaydate=$totaldates=0;
        while($delayrow_pm = sql_fetch_array($delayres_pm)){
            $pk_ids = explode("``",$delayrow_pm["pk_id"]);

            $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

            $days = $diff / (60*60*24);

            for($i=0;$i<count($pk_ids);$i++){
                for($j=0;$j<count($map_pk_id_pm);$j++){
                    if($pk_ids[$i]==$map_pk_id_pm[$j]){
                        if($map_pk_actives_pm[$j]==0) {
                            $sql = "select *,c.pk_id as pk_id,d.pk_id as depth4_pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id,a.depth_name as depth1_name,d.depth_name as depth_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                            $ddd = sql_fetch($sql);
                            if(substr($ddd["me_code"],0,2)!=10) {
                                if (strpos($id[$c], $delayrow_pm["depth4_pk_id"]) !== false) {
                                    continue;
                                }
                            }
                            $id[$c] .= ',' . $delayrow_pm["depth4_pk_id"];
                            $delaydate += $days;
                            $delaycount++;

                        }
                        /*if($map_pk_actives_pm[$j]==1){
                            $delaycount--;
                            $delaydate -= $days;
                        }*/
                    }
                }
            }
        }
        $totaldates = round($delaydate / $delaycount,'2');
        $worklist[$c]["delaycount"] = $delaycount;
        $worklist[$c]["delaydate"] = $delaydate;
        $worklist[$c]["delaytotal"] = $totaldates;
    }
    $totalDelay += $worklist[$c]["delaycount"];
    $totalDelayDate += $worklist[$c]["delaydate"];

    $c++;
}
$totalDelayDatePer = round($totalDelayDate / $totalDelay,2) ;

$userAgent = $_SERVER["HTTP_USER_AGENT"];
if ( preg_match("/MSIE*/", $userAgent) ) {
    // 익스플로러
    $ie = "ie";
} elseif ( preg_match("/Trident*/", $userAgent) &&  preg_match("/rv:11.0*/", $userAgent) &&  preg_match("/Gecko*/", $userAgent)) {
    $ie = "ie 11";
}

if($ie){
    $filename = iconv("utf-8","euc-kr","공무행정 제출 지연 총괄표".date('Ymdhis').".xls");
}else{
    $filename = "공무행정 제출 지연 총괄표".date('Ymdhis').".xls";
}

header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$filename );
header( "Content-Description: PHP4 Generated Data" );
?>

<table class="view_table" >
    <colgroup>
        <!--<col width="5%">-->
        <col width="10%">
        <col width="20%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
        <col width="10%">
    </colgroup>
    <tr>
        <th colspan="9" style="border:1px solid #000;font-size:30px;text-align: center">공무행정 제출 지연 총괄표</th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        <!--<th>구분</th>-->
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">PM 보고서</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">현장명</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">담당</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">착공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">준공일</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">기간경과율</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">제출지연 건수 계</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">제출지연 일수 계</th>
        <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">평균지연 일수 계</th>
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
                <?php echo ($i+1);?>
            </td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $worklist[$i]["id"];?>'" ><?php echo $worklist[$i]["cmap_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center" style=""><?php echo $constmb["mb_name"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_start_temp"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $dayper;?></td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo number_format($worklist[$i]["delaycount"]);?> 건</td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo number_format($worklist[$i]["delaydate"]);?> 일</td>
            <td style="border:0.25pt solid #000;color:#000;text-align: center;" class="td_center"><?php echo $worklist[$i]["delaytotal"];?> 일</td>
        </tr>
        <?php
    } ?>
<tr>
    <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000" colspan="5">소계</td>
    <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000">계</td>
    <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo number_format($totalDelay);?> 건</td>
    <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo number_format($totalDelayDate);?> 일</td>
    <td style="background-color: #9ca0ae;text-align: center;color: #fff;font-weight: bold;border:0.25pt solid #000"><?php echo $totalDelayDatePer;?> 일</td>
</tr>
</table>