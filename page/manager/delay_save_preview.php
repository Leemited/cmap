<?php
include_once ("../../common.php");
//include_once ("./excel/PHPExcel.php");

$today = date("Y-m-d");

$sql = "select * from `cmap_my_construct` where id in ({$constids}) order by id desc";
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

        $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$row["id"]}' and schedule_date < '{$today}' and pk_id <> '' order by depth4_pk_id asc ";
        $delayres_pm = sql_query($delaysql_pm);
        $a=0;
        $delaycount[$c]=$delaydate=$totaldates=0;
        while($delayrow_pm = sql_fetch_array($delayres_pm)){
            $sch_name = explode("|",$delayrow_pm["schedule_name"]);
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth1_name"]= trim($sch_name[0]);
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth4_name"]=trim(array_pop(explode("|",$delayrow_pm["schedule_name"])));
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth4_pk_id"]=$delayrow_pm["depth4_pk_id"];
            $pk_ids = explode("``",$delayrow_pm["pk_id"]);

            $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

            $days = $diff / (60*60*24);
            for($i=0;$i<count($pk_ids);$i++){
                for($j=0;$j<count($map_pk_id_pm);$j++){
                    if($pk_ids[$i]==$map_pk_id_pm[$j]) {
                        if($map_pk_actives_pm[$j]==0) {
                            $sql = "select c.content,c.depth4_id,b.id,b.pk_id,c.pk_id as pkid from `cmap_content` as c left join `cmap_depth4` as b on c.depth4_id = b.id where c.pk_id = '{$pk_ids[$i]}'";
                            $ddd = sql_fetch($sql);
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["contents_cnt"]++;
                            $worklist[$c]["allTotal"] = $a++;
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["content"][$ddd["pkid"]] = $ddd;
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["content"][$ddd["pkid"]]["delaydate"] = $days;
                            $worklist[$c]["totalDate"] += $days;
                        }
                    }
                }
            }
        }
        $totaldates = round($delaydate / $delaycount[$c],2);
        $worklist[$c]["delaycount"] = $delaycount[$c];
        $worklist[$c]["delaydate"] = $delaydate;
        $worklist[$c]["delaytotal"] = $totaldates;
    }else{
        //등록자 설정
        $activesql_pm = "select * from `cmap_my_construct_map` where mb_id ='{$row["mb_id"]}' and const_id = '{$row["id"]}'";
        $activechk_pm = sql_fetch($activesql_pm);
        $map_pk_id_pm = explode("``",$activechk_pm["pk_ids"]);
        $map_pk_actives_pm = explode("``",$activechk_pm["pk_actives"]);
        $map_pk_actives_date_pm = explode("``",$activechk_pm["pk_actives_date"]);

        $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$row["id"]}' and schedule_date < '{$today}' and pk_id <> '' order by depth4_pk_id asc ";
        $delayres_pm = sql_query($delaysql_pm);
        $a=0;
        $delaycount[$c]=$delaydate=$totaldates=0;
        while($delayrow_pm = sql_fetch_array($delayres_pm)){
            $sch_name = explode("|",$delayrow_pm["schedule_name"]);
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth1_name"]= trim($sch_name[0]);
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth4_name"]=trim(array_pop(explode("|",$delayrow_pm["schedule_name"])));
            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["depth4_pk_id"]=$delayrow_pm["depth4_pk_id"];
            $pk_ids = explode("``",$delayrow_pm["pk_id"]);

            $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

            $days = $diff / (60*60*24);
            for($i=0;$i<count($pk_ids);$i++){
                for($j=0;$j<count($map_pk_id_pm);$j++){
                    if($pk_ids[$i]==$map_pk_id_pm[$j]) {
                        if($map_pk_actives_pm[$j]==0) {
                            $sql = "select c.content,c.depth4_id,b.id,b.pk_id,c.pk_id as pkid from `cmap_content` as c left join `cmap_depth4` as b on c.depth4_id = b.id where c.pk_id = '{$pk_ids[$i]}'";
                            $ddd = sql_fetch($sql);
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["contents_cnt"]++;
                            $worklist[$c]["allTotal"] = $a++;
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["content"][$ddd["pkid"]] = $ddd;
                            $worklist[$c]['subs'][$delayrow_pm["depth4_pk_id"]]["content"][$ddd["pkid"]]["delaydate"] = $days;
                            $worklist[$c]["totalDate"] += $days;
                        }
                    }
                }
            }
        }
        $totaldates = round($delaydate / $delaycount[$c],2);
        $worklist[$c]["delaycount"] = $delaycount[$c];
        $worklist[$c]["delaydate"] = $delaydate;
        $worklist[$c]["delaytotal"] = $totaldates;
    }
    $totalDelay += $worklist[$c]["delaycount"];
    $totalDelayDate += $worklist[$c]["delaydate"];
    $totalDelayDatePer += $worklist[$c]["delaytotal"];

    $c++;
}

?>
<div style="" class="message">
    <div class="msg_title">
        <h2>PM 보고서</h2>
        <ul>
            <!--<li onclick="">새로고침</li>-->
            <li onclick="location.href=g5_url+'/page/manager/delay_save_excel?constids=<?php echo $constids;?>'"><img src="<?php echo G5_IMG_URL;?>/ic_save.svg" alt=""></li>
            <!-- <li>다운로드</li>-->
            <!--<li onclick=""><img src="<?php /*echo G5_IMG_URL;*/?>/ic_print.svg" alt=""></li>-->
        </ul>
        <div class="close" onclick="fnEtcClose()"></div>
    </div>
    <div class="pm_preview" style="">
    <table style="width:100%;">
        <tr>
            <th colspan="5" style="font-weight:bold;text-align:center;font-size:15pt;border-bottom:1px solid #000">제출지연현황</th>
        </tr>
        <tr>
            <th colspan="5" style="height:10pt;"></th>
        </tr>
        <tr>
            <th colspan="3" style="font-size:12pt;font-weight:bold;text-align: left">□ 제출지연 총괄표</th>
            <td colspan="2" style="text-align: right"><?php echo date('Y-m-d');?></td>
        </tr>
    </table>
    <table style="width:100%;border-top:1px solid #000;border-left:1px solid #000;border-bottom: 1px solid #000; border-right: 1px solid #000;border-spacing: 0;margin-bottom:20px;">
        <tr>
            <th style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;">구분</th>
            <th style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;">현장명</th>
            <th style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;">지연서류</th>
            <th style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;">지연항목</th>
            <th style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;">지연일</th>
        </tr>
        <?php for($i=0;$i<count($worklist);$i++){
            $total_list0 += count($worklist[$i]["subs"]);
            if($worklist[$i]["allTotal"]==0){
                $total = $worklist[$i]["allTotal"];
                $total_list1 += $worklist[$i]["allTotal"];
            }else{
                $total = $worklist[$i]["allTotal"] + 1;
                $total_list1 += $worklist[$i]["allTotal"]+1;
            }
            $total_list2 += $worklist[$i]["totalDate"];
            ?>
            <tr>
                <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: center;"><?php echo ($i+1);?></td>
                <td style="padding:5px;border-right:0.25pt solid #000;color:#000;"><?php echo $worklist[$i]["cmap_name"];?></td>
                <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: right;"><?php echo number_format(count($worklist[$i]["subs"]));?></td>
                <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: right;"><?php echo number_format($total);?></td>
                <td style="padding:5px;color:#000;text-align: right;"><?php echo number_format($worklist[$i]["totalDate"]);?></td>
            </tr>
        <?php }?>
        <tr>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center">소계</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center">계</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format($total_list0);?> 건</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format($total_list1);?> 일</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format($total_list2);?> 일</td>
        </tr>
    </table>
    <table style="width:100%;">
        <tr>
            <th style="height:10pt"></th>
        </tr>
        <tr>
            <th colspan="5" style="font-size:12pt;font-weight:bold;text-align: left">□ 제출지연 세부내역</th>
        </tr>
    </table>
    <table style="width:100%;border-top:1px solid #000;border-left:1px solid #000;border-bottom: 1px solid #000; border-right: 1px solid #000;border-spacing: 0;margin-bottom:20px;">
        <colgroup>
            <col width="15%">
            <col width="25%">
            <col width="25%">
            <col width="25%">
            <col width="10%">
        </colgroup>
        <tr>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">구분</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">현장명</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연서류</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연항목</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연일</th>
        </tr>
        <?php
        $cb = 0;
        $ca = 0;
        for($i=0;$i<count($worklist);$i++){
            $worklist2 = array_values($worklist[$i]['subs']);
            ?>
            <tr>
            <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center" rowspan="<?php echo ($worklist[$i]["allTotal"]+2);?>"><?php echo ($i+1);?></td>
            <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;" <?php if(($worklist[$i]["allTotal"]+1)>1){?>rowspan="<?php echo ($worklist[$i]["allTotal"]+1);?>" <?php }?>><?php echo $worklist[$i]["cmap_name"];?></td>
            <?php
            for($j=0;$j<count($worklist2);$j++){
                $worklist3 = array_values($worklist2[$j]["content"]);
                $cb++;
                ?>
                <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center" <?php if(count($worklist2[$j]["content"])>1){?>rowspan="<?php echo count($worklist2[$j]["content"]);?>" <?php }?>><?php echo $worklist2[$j]["depth1_name"];?><?php echo " | ". $worklist2[$j]["depth4_name"];?></td>
                <?php for($d=0;$d<count($worklist3);$d++){
                    $delays3[$i]+=$worklist3[$d]["delaydate"];
                    $ca++;
                    ?>
                    <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center"><?php echo $worklist3[$d]["content"];?></td>
                    <td style="padding:5px;border-bottom:0.25pt solid #000;color:#000;text-align: center"><?php echo $worklist3[$d]["delaydate"];?></td>
                    </tr>
                <?php }?>
                </tr>
                <?php if($j==count($worklist2)-1){?>
                    <tr>
                        <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;">제출지연현황</td>
                        <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;"><?php echo number_format($cb);?> 건</td>
                        <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;"><?php echo number_format($ca);?> 건</td>
                        <td style="padding:5px;color:#000;border-bottom:0.25pt solid #000;text-align: center;background-color:#eee;"><?php echo number_format($delays3[$i]);?> 일</td>
                    </tr>
                <?php }?>
            <?php } ?>

            <?php if(count($worklist2)==0){?>
                <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align:center">-</td>
                <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align:center">-</td>
                <td style="padding:5px;border-bottom:0.25pt solid #000;color:#000;text-align:center">0</td>
                </tr>
                <tr>
                    <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;">제출지연 현황</td>
                    <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;">0 건</td>
                    <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;">0 건</td>
                    <td style="padding:5px;border-bottom:0.25pt solid #000;color:#000;text-align: center;background-color:#eee;">0 일</td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>
    </div>
</div>