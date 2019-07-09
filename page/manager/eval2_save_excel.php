<?php
include_once ("../../common.php");

$me_id = "60129";
//평가 항목 가저오기
$sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$constids}'";
$ss = sql_fetch($sql);
if($ss!=null) {
    $evaldata = sql_fetch("select * from `cmap_my_construct_eval` where mb_id='{$ss["set_mb_id"]}' and const_id = '{$constids}'");
}else{
    $sql = "select * from `cmap_my_construct` where id = '{$constids}'";
    $ss2 = sql_fetch($sql);
    $evaldata = sql_fetch("select * from `cmap_my_construct_eval` where mb_id='{$ss2["mb_id"]}' and const_id = '{$constids}'");
}

//$evaldata = sql_fetch("select * from `cmap_my_construct_eval` where mb_id='{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'");
$pk_ids = explode("``",$evaldata["pk_ids2"]);
$pk_scores = explode("``",$evaldata["pk_score2"]);
for($i=0;$i<count($pk_ids);$i++){
    $scores[$pk_ids[$i]]["score"] = (double)$pk_scores[$i];
    $scores[$pk_ids[$i]]["pk_row_active"] = $evaldata["pk_row_active"];
}

if(strlen($me_id)==2){
    $sql = "select * from `cmap_depth1` where SUBSTRING(me_code,1,2) like '%{$me_id}%' order by me_code asc limit 0,1 ";
    $codes = sql_fetch($sql);
    $incode = $codes["me_code"];
    if(!$depth1_id) $depth1_id = $codes["id"];
    if(!$depth2_id){
        $sql = "select * from `cmap_dpeth2` where depth1_id = '{$code["id"]}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}else{
    $incode = $me_id;
    if(!$depth2_id){
        $sql = "select * from `cmap_depth1` where me_code = '{$incode}' order by id asc limit 0,1";
        $codes = sql_fetch($sql);
        if(!$depth1_id) $depth1_id = $codes["id"];
        $sql = "select * from `cmap_depth2` where depth1_id = '{$depth1_id}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}
if($depth1_id){
    $where = " and depth1_id = '{$depth1_id}'";

    switch ($depth1_id){
        case "338":
            $step = 1;
            break;
        case "339":
            $step = 2;
            break;
        case "340":
            $step = 3;
            break;
        case "341":
            $step = 4;
            break;
        case "342":
            $step = 5;
            break;
        case "343":
            $step = 6;
            break;
        case "344":
            $step = 7;
            break;
        case "345":
            $step = 8;
            break;
    }
}

//해당 대메뉴에 대한 서브 메뉴
$menu_id = substr($me_id,0,2);
$sql = "select * from `cmap_menu` where menu_code like '%{$menu_id}%' and menu_status = 0 and menu_depth = 1 order by menu_order";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $search_menu[] = $row;
}

$sql = "select * from `cmap_menu` where menu_code = '{$menu_id}'";
$menu1_info = sql_fetch($sql);

$sql = "select * from `cmap_menu` where menu_code = '{$me_id}'";
$menu2_info = sql_fetch($sql);


//if($menu_id==10 || $menu_id==40) {
$sql = "select * from `cmap_menu` where menu_code like '{$menu_id}%' and menu_code != '{$menu_id}' and menu_status = 0 order by menu_order asc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $depth_me[] = $row;
}

$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
    $depth_menu[] = $row;
}
$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
$res = sql_query($sql);
$i = 0;
while ($row = sql_fetch_array($res)) {
    $j = 0;
    $list[$i] = $row;

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    //while($row2 = sql_fetch_array($res2)){
    //    $depth_menu[] = $row2;
    //}

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    while ($row2 = sql_fetch_array($res2)) {
        $k = 0;
        $list[$i]['depth2'][$j] = $row2;
        $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth3` as a left join `cmap_content` as b on a.id = b.depth3_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']}  group by a.id order by a.id asc";
        $res3 = sql_query($sql);
        $num3 = sql_num_rows($res3);
        if($num3 > 0) {
            while ($row3 = sql_fetch_array($res3)) {
                $l = 0;
                $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
                $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth4` as a left join `cmap_content` as b on a.id = b.depth4_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']} and a.depth3_id = {$row3['id']}  group by a.id order by a.id asc";
                $res4 = sql_query($sql);
                $num4 = sql_num_rows($res4);
                if($num4 > 0) {
                    while ($row4 = sql_fetch_array($res4)) {
                        $m = 0;
                        $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row4;
                        $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} and depth4_id = {$row4['id']} order by id asc";
                        $res5 = sql_query($sql);
                        $depth5num = sql_num_rows($res5);
                        while ($row5 = sql_fetch_array($res5)) {
                            $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m] = $row5;
                            $m++;
                        }
                        $l++;
                    }
                }else{
                    $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} order by id asc";
                    $res6 = sql_query($sql);
                    $num4 = sql_num_rows($res6);
                    while ($row6 = sql_fetch_array($res6)) {
                        $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row6;
                        $l++;
                    }
                }
                $k++;
            }
        }else{
            $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} order by id asc";
            $res7 = sql_query($sql);
            $num3 = sql_num_rows($res7);
            while ($row7 = sql_fetch_array($res7)) {
                //$l = 0;
                $list[$i]['depth2'][$j]['depth3'][$k] = $row7;
                $k++;
            }
        }
        $j++;
    }
    $i++;
}


$today = date("Y-m-d");

$sql = "select * from `cmap_my_construct` where id = '{$constids}' order by id desc";
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
    $dayper = $totals - $nowdays * 100;
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
    }

    $alltotal1 += (double)$totaleval1_01+(double)$totaleval2_01+(double)$totaleval3_01;
    $alltotal2 += (double)$totaleval1_02+(double)$totaleval2_02+(double)$totaleval3_02;
    $alltotal3 += (double)$totaleval1_03+(double)$totaleval2_03+(double)$totaleval3_03;
    $alltotal4 += (double)$totaleval1_04+(double)$totaleval2_04+(double)$totaleval3_04;
    $alltotal5 += (double)$totaleval1_05+(double)$totaleval2_05+(double)$totaleval3_05;
    $alltotal6 += (double)$totaleval1_06+(double)$totaleval2_06+(double)$totaleval3_06;
    $alltotal7 += (double)$totaleval1_07+(double)$totaleval2_07+(double)$totaleval3_07;
    $alltotal8 += (double)$totaleval1_08+(double)$totaleval2_08+(double)$totaleval3_08;
    $alltotal9 += (double)$totaleval1_09+(double)$totaleval2_09+(double)$totaleval3_09;
    $alltotal10 += (double)$totaleval1_10+(double)$totaleval2_10+(double)$totaleval3_10;

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

$const = sql_fetch("select * from `cmap_my_construct` where id = '{$constids}'");
header( "Content-type: application/vnd.ms-excel" );
header( "Content-type: application/vnd.ms-excel; charset=utf-8");
header( "Content-Disposition: attachment; filename = ".$const["cmap_name"]."건설사업관리용역 평가표".date('Ymdhis').".xls" );
header( "Content-Description: PHP4 Generated Data" );
?>

<div class="message">
    <div class="pm_preview">
        <table style="width:100%;border-spacing: 0">
            <tr>
                <th colspan="13" style="font-size:15pt;font-weight:bold;border-bottom:1px solid #000"><?php echo $const['cmap_name'];?> 건설사업관리용역 평가표</th>
            </tr>
            <tr>
                <th style="height:10pt;"></th>
            </tr>
        </table>
        <table style="width:100%;border-spacing: 0">
            <tr>
                <th style="text-align:left;">건설사업관리용역점수</th>
            </tr>
        </table>
        <table style="width:100%;border-spacing: 0;border-top:0.25pt solid #000;">
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
            </colgroup>
            <tr>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  rowspan="2">구분</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  rowspan="2">현장명</th><!--
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  rowspan="2">착공일</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  rowspan="2">준공일</th>-->
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  colspan="4">업체 평가(A)</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  colspan="6">기술자 평가(B)</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;"  rowspan="2">기간경과율</th>
            </tr>
            <tr>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >조직운영</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >현장지원</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >기술지원</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >소계</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >일반행정</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >시공관리</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >기술업무</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >가감점</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >시공상태</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >평가점수</th>
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
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" >점수</td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["cmap_name"];?></td><!--
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php /*echo $worklist[$i]["cmap_construct_start"];*/?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php /*echo $worklist[$i]["cmap_construct_finish"];*/?></td>-->
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_01"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_02"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_03"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["sum1"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_04"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_05"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_06"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_07"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["eval_08"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $worklist[$i]["sum2"];?></td>
                    <td style="border:0.25pt solid #000;color:#000;text-align: center" ><?php echo $dayper;?></td>
                </tr>
                <?php
            } ?>
            <?php if(count($worklist)==0){?>
                <tr>
                    <td colspan="7" class="td_center">등록된 PM현장이 없습니다.</td>
                </tr>
            <?php   }?>
        </table>
        <table >
            <colgroup>
                <col width="16%">
                <col width="*">
                <col width="3%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="3%">
            </colgroup>

            <?php
            $depth_last = 1;
            for($i=0;$i<count($list);$i++){
            ?>
            <table style="width: 100%;border-spacing: 0">
                <tr>
                    <th style="height:15pt;"></th>
                </tr>
                <tr>
                    <th colspan="8" style="text-align: left"><?php echo ($i+1).". ".$list[$i]["depth_name"];?></th>
                </tr>
            </table>
            <table style="width:100%;border-spacing: 0;border:0.25pt solid #000;">
                <tr>
                    <!--th>직업선택</th-->
                    <th  style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" rowspan="2"  colspan="3">평가항목</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" rowspan="2"  colspan="3">평가방법</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" rowspan="2">배점</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" colspan="5">평가등급</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" rowspan="2">점수</th>
                </tr>
                <tr>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >우수 ( X 1.0)</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >보통 ( X 0.9)</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >미흡 ( X 0.8)</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >불량 ( X 0.7)</th>
                    <th style="background-color:#002060;color:#fff;text-align: center;border:1px solid #fff;" >불량 ( X 0.6)</th>
                </tr>

                <tr>
                    <?php for($j=0;$j<count($list[$i]['depth2']);$j++) {
                    ?>
                    <td style="border:0.25pt solid #000;color:#000;background-color:#eee;" rowspan="<?php echo $list[$i]['depth2'][$j]['cnt'];?>" colspan="3">
                        <strong><?php echo $list[$i]['depth2'][$j]['depth_name'];?></strong>
                    </td>
                    <?php for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                    ?>
                    <td style="border:0.25pt solid #000;color:#000;background-color:#eee;" rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>"  colspan="3">
                        <strong><?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?></strong>
                    </td>
                    <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) {
                    $total += (float)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name']=="0"){
                        echo "";
                    }else{
                        ?>
                        <td style="border:0.25pt solid #000;color:#000;text-align: center" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" >
                            <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?>
                        </td>
                    <?php }
                    for ($m = 0; $m < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5']); $m++) {

                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment"]!="") {
                        $files = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment"]);
                        $filenames = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachmentname1"]);
                    }else{
                        $files = array();
                        $filenames = array();
                    }
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]!=""){
                        $links = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]);
                        $linknames = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["linkname"]);
                    }else{
                        $links = array();
                        $linknames = array();
                    }
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment2"]!=""){
                        $files2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment2"]);
                        $filenames2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachmentname2"]);
                    }else{
                        $files2 = array();
                        $filenames2 = array();
                    }
                    $depth_last++;
                    $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                    $span = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["span"]);
                    $eval = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content']);
                    if(!$is_member){
                        echo "<td colspan='5' class='td_center'>로그인 후 이용 가능</td>";
                    }else{
                        if(count($eval)>0){
                            for($o=0;$o<count($eval);$o++){
                                //if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22090"){continue;}
                                /*if(count($eval)==($o+1)) {
                                    $total2 += (float)$eval;
                                }*/
                                switch ($o){
                                    case 0:
                                        $nums = 1;
                                        break;
                                    case 1:
                                        $nums = 0.9;
                                        break;
                                    case 2:
                                        $nums = 0.8;
                                        break;
                                    case 3:
                                        $nums = 0.7;
                                        break;
                                    case 4:
                                        $nums = 0.6;
                                        break;
                                }

                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22130" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22135" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22134"){
                                    switch ($o){
                                        case 0:
                                            $nums = 1;
                                            break;
                                        case 1:
                                            $nums = 0.8;
                                            break;
                                        case 2:
                                            $nums = 0.6;
                                            break;
                                        case 3:
                                            $nums = 0.4;
                                            break;
                                        case 4:
                                            $nums = 0;
                                            break;
                                    }
                                }

                                if($span[$o]>1){
                                    //특별 조건 IOS 획득여부
                                    if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22078" && $o == 1){
                                        $nums = 0;
                                    }
                                }
                                $basic = (double)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                                if($basic==0){
                                    $c = $k-1;
                                    $basic = (double)$list[$i]['depth2'][$j]['depth3'][$c]['depth4'][$l]['depth_name'];
                                }
                                $cals = (double)$basic * (double)$nums;
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22126" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22127" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22128" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22129" ){
                                    $direct = (int)$eval[$o];
                                    $cals = $direct;
                                }else{
                                    $direct = null;
                                }

                                if($eval[$o]=="") continue;

                                if((round((double)$cals,2) == round((double)$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"],2)) || (($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22126" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22127" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22128" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22129" ) && $eval[$o]==$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"])){
                                    $active = "active";
                                }else{
                                    $active = "";
                                }
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22090" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22089" ) {
                                    if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22090" && $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["pk_row_active"] == 0) {
                                        $active = "";
                                    }
                                    if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22089" && $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["pk_row_active"] == 1) {
                                        $active = "";
                                    }
                                }

                                ?>
                                <td style="border:0.25pt solid #000;color:#000;text-align: center" class="depth4 scores_<?php echo $o;?> pk_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"];?> <?php echo $active ?>" colspan="<?php echo $span[$o];?>" >
                                                <span <?php if($active){?>style="color:red;font-weight:bold;"<?php }?>><?php
                                                    echo $eval[$o];
                                                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22130" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22135" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22134"){
                                                        echo " ( X ".round($nums,1).")";
                                                    }
                                                    ?>
                                                    </span>
                                </td>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                    <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']!="22090"){?>
                        <td style="border:0.25pt solid #000;text-align: center" class="score_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?> td_center" <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22089"){?>rowspan="2" <?php }?> >
                            <!-- 점수 -->
                            <?php $evaltotals[$i] += $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"];?>
                            <?php echo $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"];?>
                        </td>
                    <?php }?>
                </tr>
                <?php if($list[$i]['cnt'] == $depth_last){?>
                <tr class="<?php if($list[$i]['depth2'][$j]['cnt'] == $depth_last){echo "finish";}?>">
                    <?php }
                    if($list[$i]['depth2'][$j]['cnt']+1 == $depth_last){?>
                    <?php }
                    }
                    }
                    }
                    $depth_last = 1;
                    }
                    ?>
                <tr class="sum">
                    <td style="background-color:#eee;border:0.25pt solid #000;text-align: center" colspan="6" class="td_center">점수</td>
                    <td style="background-color:#eee;border:0.25pt solid #000;text-align: center"  ><?php echo $total;?></td>
                    <td style="background-color:#eee;border:0.25pt solid #000" colspan="5"></td>
                    <td style="background-color:#eee;border:0.25pt solid #000;text-align: center" ><?php echo $evaltotals[$i];?></td>
                </tr>
                <?php
                }?>
            </table>
    </div>
</div>
