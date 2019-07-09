<?php
include_once ("../../common.php");

$me_id="6064";

$const = sql_fetch("select * from `cmap_my_construct` where id = '{$constids}'");


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

    $pk_ids = explode("``",$evaldata["pk_ids1"]);
    $pk_scores = explode("``",$evaldata["pk_score1"]);

    for($i=0;$i<count($pk_ids);$i++){
        $scores[$pk_ids[$i]] = $pk_scores[$i];
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



$sql = "select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}')!=0 and status = 0  and id = '{$constids}' order by id desc";
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
    $diveval = explode("``",$eval1["pk_score1_total"]);
    $worklist[$c]["eval_01"] = $diveval[0];
    $worklist[$c]["eval_02"] = $diveval[1];
    $worklist[$c]["eval_03"] = $diveval[2];
    $sum = (double)$diveval[0]+(double)$diveval[1]+(double)$diveval[2];
    $worklist[$c]["sum"] = round($sum,2);


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
            $totaleval1_04 += $worklist[$c]["sum"];
            $totaleval1_cnt++;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 1 year"))){
            //작년
            $totaleval2_01 += $worklist[$c]["eval_01"];
            $totaleval2_02 += $worklist[$c]["eval_02"];
            $totaleval2_03 += $worklist[$c]["eval_03"];
            $totaleval2_04 += $worklist[$c]["sum"];
            $totaleval2_cnt++;
        }
        if(date("Y",strtotime($row["cmap_construct_finish"]))==date("Y",strtotime("- 2 year"))){
            //재작년
            $totaleval3_01 += $worklist[$c]["eval_01"];
            $totaleval3_02 += $worklist[$c]["eval_02"];
            $totaleval3_03 += $worklist[$c]["eval_03"];
            $totaleval3_04 += $worklist[$c]["sum"];
            $totaleval3_cnt++;
        }
        $alltot++;
    }

    $alltotal1 += (double)$totaleval1_01+(double)$totaleval2_01+(double)$totaleval3_01;
    $alltotal2 += (double)$totaleval1_02+(double)$totaleval2_02+(double)$totaleval3_02;
    $alltotal3 += (double)$totaleval1_03+(double)$totaleval2_03+(double)$totaleval3_03;
    $alltotal4 += (double)$totaleval1_04+(double)$totaleval2_04+(double)$totaleval3_04;
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
?>
<div class="message">
    <div class="msg_title">
        <h2>PM 보고서</h2>
        <ul>
            <!--<li onclick="">새로고침</li>-->
            <li onclick="location.href=g5_url+'/page/manager/eval_save_excel?constids=<?php echo $constids;?>'"><img src="<?php echo G5_IMG_URL;?>/ic_save.svg" alt=""></li>
            <!-- <li>다운로드</li>-->
            <!--<li onclick=""><img src="<?php /*echo G5_IMG_URL;*/?>/ic_print.svg" alt=""></li>-->
        </ul>
        <div class="close" onclick="fnEtcClose()"></div>
    </div>
    <div class="pm_preview">
        <table style="width:100%;border-spacing: 0;">
            <tr>
                <th colspan="8" style="text-align: center;border-bottom:1px solid #000;font-size:15pt;font-weight: bold;"><?php echo $const['cmap_name'];?> 건설사업관리시공 평가표</th>
            </tr>
            <tr>
                <th style="height:10pt;"></th>
            </tr>
        </table>
        <table style="border-spacing:0;border:1px solid #000;">
            <colgroup>
                <!--<col width="2%">-->
                <col width="15%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
                <col width="5%">
            </colgroup>

            <tr>
                <!--<th rowspan="2">구분</th>-->
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">현장명</th>
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">담당</th>
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">착공일</th>
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">준공일</th>
                <th colspan="3" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">시공평가 100(점)</th>
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">시공평가점수</th>
                <th rowspan="2" style="padding:5px;background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">기간경과율</th>
            </tr>
            <tr>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">공사관리</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">품질 및 성능</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff">가감점</th>
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
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["cmap_name"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $constmb["mb_name"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["cmap_construct_start"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["eval_01"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["eval_02"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["eval_03"];?></td>
                    <td style="text-align: center;border-right:0.25pt solid #000;padding:5px;" ><?php echo $worklist[$i]["sum"];?></td>
                    <td style="text-align: center;padding:5px;" ><?php echo $dayper;?></td>
                </tr>
                <?php
            } ?>
            <?php if(count($worklist)==0){?>
                <tr>
                    <td colspan="7" class="td_center">등록된 PM현장이 없습니다.</td>
                </tr>
            <?php   }?>
        </table>

            <?php
            $depth_last = 1;
            for($i=0;$i<count($list);$i++){
            ?>
        <table>
            <tr>
                <th style="height:15pt;"></th>
            </tr>
            <tr>
                <th colspan="8"><?php echo ($i+1).". ".$list[$i]["depth_name"];?></th>
            </tr>
        </table>
        <table class="" style="border-spacing: 0;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;border-left:1px solid #000">
            <colgroup>
                <col width="8%">
                <col width="24%">
                <col width="3%">
                <col width="224px">
                <col width="224px">
                <col width="224px">
                <col width="224px">
                <col width="3%">
                <!--<col width="6%">-->
            </colgroup>
            <tr>
                <!--th>직업선택</th-->
                <th rowspan="2" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">평가항목</th>
                <th rowspan="2" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">평가방법</th>
                <th rowspan="2" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">배점</th>
                <th colspan="4" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">평가등급</th>
                <th rowspan="2" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">점수</th>
                <!--<th rowspan="2" style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #000">평가방법</th>-->
            </tr>
            <tr>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">우수 ( X 1.0)</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">보통 ( X 0.8)</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">미흡 ( X 0.6)</th>
                <th style="background-color:#002060;color:#fff;text-align: center;border:0.25pt solid #fff;padding:5px;">불량 ( X 0.4)</th>
            </tr>
                <tr>
                <?php for($j=0;$j<count($list[$i]['depth2']);$j++) {
                    ?>
                    <td style="text-align: center;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;background-color:#eee;padding:5px"  rowspan="<?php echo $list[$i]['depth2'][$j]['cnt'];?>" >
                        <strong><?php echo $list[$i]['depth2'][$j]['depth_name'];?></strong>
                    </td>
                    <?php for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                        ?>
                        <td style="text-align: center;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;background-color:#eee;padding:5px" rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>" style="text-align: left">
                            <strong><?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?></strong>
                        </td>
                        <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) {
                            $total += (float)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                            ?>
                            <td style="text-align: center;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;padding:5px" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" >
                                <!-- 배점 -->
                                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?>
                            </td>
                            <?php
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
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment3"]!=""){
                                    $files3 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment3"]);
                                    $filenames3 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachmentname3"]);
                                }else{
                                    $files3 = array();
                                    $filenames3 = array();
                                }
                                $depth_last++;
                                $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                                $span = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["span"]);
                                $eval = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content']);
                                if(!$is_member){
                                    echo "<td colspan='4' class='td_center'>로그인후 이용 바랍니다.</td>";
                                }else{
                                    if(count($eval)>0){
                                        for($o=0;$o<count($eval);$o++){
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
                                            }

                                            $cals = round($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'] * $nums,1);

                                            if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "21736"){
                                                if($o==0) {
                                                    $cals = 1.5;
                                                }else{
                                                    $cals = 0;
                                                }
                                            }

                                            if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "21737"){
                                                if($o==3){
                                                    $cals = 0;
                                                }
                                            }

                                            if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "21738"){
                                                if($o==0) {
                                                    $cals = -10;
                                                }else{
                                                    $cals = 0;
                                                }
                                            }
                                            /*if(count($eval)==($o+1)) {
                                                $total2 += (float)$eval;
                                            }*/
                                            if($eval[$o]=="") continue;
                                            ?>
                                            <td style="text-align: center;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;padding:5px" colspan="<?php echo $span[$o];?>" >
                                                <!-- 항목 -->
                                                <span style="<?php if((double)$cals == (double)$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]){?>color:red;font-weight:bold;<?php }?>"><?php echo $eval[$o];?></span>
                                            </td>
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                                <td style="text-align:center;font-weight:bold;border-bottom:0.25pt solid #000;background-color:#e4f8f9;;padding:5px">
                                    <!-- 점수 -->
                                    <?php $totalScore[$i] += (double)$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]];?>
                                    <?php echo $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]];?>
                                </td>

                                </tr>
                                <?php if($list[$i]['cnt'] == $depth_last){?>
                                    <tr class="<?php if($list[$i]['depth2'][$j]['cnt'] == $depth_last){echo "finish";}?>">
                                <?php }
                                if($list[$i]['depth2'][$j]['cnt']+1 == $depth_last){?>
                                    <tr></tr>
                                <?php }
                            }
                        }
                    }
                    $depth_last = 1;
                }
                ?>
                <tr class="margin_tr"></tr>
                <tr class="sum">
                    <td style="background-color:#eee;border-right:0.25pt solid #000;padding:5px" colspan="2" class="td_center">점수</td>
                    <td style="background-color:#eee;border-right:0.25pt solid #000;padding:5px" class="td_center"><?php echo $total;?></td>
                    <td style="background-color:#eee;border-right:0.25pt solid #000;padding:5px" colspan="4"></td>
                    <td style="background-color:#eee;padding:5px" class="td_center alltotal"><?php echo $totalScore[$i];?></td>
                </tr>
                <?php
            }?>
        </table>
    </div>
</div>