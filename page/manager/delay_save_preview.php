<?php
include_once ("../../common.php");
//include_once ("./excel/PHPExcel.php");

$today = date("Y-m-d");

//$sql = "select * from `cmap_my_construct` where id in ({$constids}) order by id desc";
//$res = sql_query($sql);
$sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$constids}'";
$ss = sql_fetch($sql);
if($ss!=null){
    //등록자 설정
    $activesql_pm = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$constids}'";
    $activechk_pm = sql_fetch($activesql_pm);
    $map_pk_id_pm = explode("``",$activechk_pm["pk_ids"]);
    $map_pk_actives_pm = explode("``",$activechk_pm["pk_actives"]);
    $map_pk_actives_date_pm = explode("``",$activechk_pm["pk_actives_date"]);

    $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$constids}' and schedule_date < '{$today}' and pk_id <> '' order by depth4_pk_id asc ";
    $delayres_pm = sql_query($delaysql_pm);
    $a = 0;
    $delaycount=$delaydate=$totaldates=0;
    while($delayrow_pm = sql_fetch_array($delayres_pm)){ // A. 스케쥴이 지난 일정중
        $delaycount++;
        $pk_ids = explode("``",$delayrow_pm["pk_id"]);

        $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

        $days = $diff / (60*60*24);
        $delaydate += $days;

        for($i=0;$i<count($pk_ids);$i++){
            for($j=0;$j<count($map_pk_id_pm);$j++){
                if($pk_ids[$i]==$map_pk_id_pm[$j]) {
                    if($map_pk_actives_pm[$j]==0) {// B. 미제출
                        $sql = "select *,a.pk_id as depth4_pk_id,c.depth1_id as depth1_id,c.depth4_id as depth4_id,c.pk_id from `cmap_content` as c left join `cmap_depth1` as d on c.depth1_id = d.id left join `cmap_depth4` as a on a.depth1_id = d.id where c.pk_id = '{$pk_ids[$i]}'";
                        $pk_con = sql_fetch($sql);
                        $worklistpm[$a] = $pk_con;
                        $worklistpm[$a]["delays"] = $days;
                        $a++;
                    }
                }
            }
        }
    }

}else{
    $sql = "select * from `cmap_my_construct` where id = '{$constids}'";
    $low = sql_fetch($sql);
    //등록자 설정
    $activesql_pm = "select * from `cmap_my_construct_map` where mb_id ='{$low["mb_id"]}' and const_id = '{$constids}'";
    $activechk_pm = sql_fetch($activesql_pm);
    $map_pk_id_pm = explode("``",$activechk_pm["pk_ids"]);
    $map_pk_actives_pm = explode("``",$activechk_pm["pk_actives"]);
    $map_pk_actives_date_pm = explode("``",$activechk_pm["pk_actives_date"]);

    $delaysql_pm = "select * from `cmap_myschedule` where construct_id = '{$constids}' and schedule_date < '{$today}' and pk_id <> '' order by depth4_pk_id asc ";
    $delayres_pm = sql_query($delaysql_pm);
    $a=0;
    $delaycount=$delaydate=$totaldates=0;
    while($delayrow_pm = sql_fetch_array($delayres_pm)){
        $delaycount++;
        $pk_ids = explode("``",$delayrow_pm["pk_id"]);

        $diff = strtotime($today) - strtotime($delayrow_pm["schedule_date"]);

        $days = $diff / (60*60*24);
        $delaydate += $days;

        for($i=0;$i<count($pk_ids);$i++){
            for($j=0;$j<count($map_pk_id_pm);$j++){
                if($pk_ids[$i]==$map_pk_id_pm[$j]) {
                    if($map_pk_actives_pm[$j]==0) {
                        $sql = "select *,a.pk_id as depth4_pk_id,c.depth1_id as depth1_id,c.depth4_id as depth4_id,c.pk_id from `cmap_content` as c left join `cmap_depth1` as d on c.depth1_id = d.id left join `cmap_depth4` as a on a.depth1_id = d.id where c.pk_id = '{$pk_ids[$i]}'";
                        $pk_con = sql_fetch($sql);
                        $worklistpm[$a] = $pk_con;
                        $worklistpm[$a]["delays"] = $days;
                        $a++;
                    }
                }
            }
        }
    }
}
for($i=0;$i<count($worklistpm);$i++){
    $sql = "select *,b.menu_name as menu_name from `cmap_depth1` as a left join `cmap_menu` as b on a.me_code = b.menu_code where a.id = '{$worklistpm[$i]["depth1_id"]}'  ";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $worklists[$row["menu_code"]]["menu_name"] = $row["menu_name"];
        $worklists[$row["menu_code"]]["menu_code"] = $row["menu_code"];
        $worklists[$row["menu_code"]]["depth1_pk"][$row["pk_id"]] = $row["pk_id"];
        $worklists[$row["menu_code"]][$row["pk_id"]]["depth1_name"] = $row["depth_name"];
        $worklists[$row["menu_code"]][$row["pk_id"]]["depth1_pk_id"] = $row["pk_id"];
    }
    $sql = "select a.pk_id as pk_ids,d.depth_name,d.pk_id,d.id as id,m.menu_code from `cmap_depth4` as d left join `cmap_depth1` as a on d.depth1_id = a.id left join `cmap_menu` as m on m.menu_code = a.me_code where d.id = '{$worklistpm[$i]["depth4_id"]}'";
    $res2 = sql_query($sql);
    while($row2 = sql_fetch_array($res2)){
        $worklists[$row2["menu_code"]][$row2["pk_ids"]][$row2["pk_id"]]["depth4_name"] = $row2["depth_name"];
        $worklists[$row2["menu_code"]][$row2["pk_ids"]][$row2["pk_id"]]["depth4_pk_id"] = $row2["pk_id"];
        $worklists[$row2["menu_code"]][$row2["pk_ids"]]["depth2_pk"][$row2["pk_id"]] = $row2["pk_id"];
        $delaycateCount[$row2["pk_id"]] = "chkcount";
        $worklists[$row2["menu_code"]][$row2["pk_ids"]]["menu_rows"]++;
    }

    $sql = "select m.menu_code,a.pk_id as pk_ids, e.pk_id as pk_idss, d.pk_id, d.content from `cmap_content` as d left join `cmap_depth4` as e on d.depth4_id = e.id left join `cmap_depth1` as a on d.depth1_id = a.id left join `cmap_menu` as m on m.menu_code = a.me_code where d.pk_id = '{$worklistpm[$i]["pk_id"]}'";
    $res3 = sql_query($sql);
    while($row3 = sql_fetch_array($res3)){
        $worklists[$row3["menu_code"]][$row3["pk_ids"]][$row3["pk_idss"]][$row3["pk_id"]]["content"] = $row3["content"];
        $worklists[$row3["menu_code"]][$row3["pk_ids"]][$row3["pk_idss"]][$row3["pk_id"]]["delaydate"] = $worklistpm[$i]["delays"];
        $worklists[$row3["menu_code"]][$row3["pk_ids"]][$row3["pk_idss"]]["content_pk_id"]= $row3["pk_id"];
        $worklists[$row3["menu_code"]][$row3["pk_ids"]][$row3["pk_idss"]]["content_pk"][$row3["pk_id"]]= $row3["pk_id"];
        $worklists[$row3["menu_code"]][$row3["pk_ids"]]["depth2_pk"]["count"]++;
        $worklists[$row3["menu_code"]]["menu_rows"]++;
    }
}

$const = sql_fetch("select * from `cmap_my_construct` where id = '{$constids}'");
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

        <tr>
            <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: center;">-</td>
            <td style="padding:5px;border-right:0.25pt solid #000;color:#000;"><?php echo $const["cmap_name"];?></td>
            <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: center;"><?php echo number_format($delaycount);?> 건</td>
            <td style="padding:5px;border-right:0.25pt solid #000;color:#000;text-align: center;"><?php echo number_format(count($worklistpm));?> 건</td>
            <td style="padding:5px;color:#000;text-align: center;"><?php echo number_format($delaydate);?> 일</td>
        </tr>
        <tr>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center">소계</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center">계</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format($delaycount);?> 건</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format(count($worklistpm));?> 건</td>
            <td style="padding:5px;border:0.25pt solid #fff;background-color:#002060;color:#fff;text-align: center"><?php echo number_format($delaydate);?> 일</td>
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
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">공정</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">세부공정</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연서류</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연항목</th>
            <th style="border:0.25pt solid #fff;background-color:#002060;color:#fff;padding:5px;">지연일</th>
        </tr>
        <?php
        $worklists = array_values($worklists);
        for($i=0;$i<count($worklists);$i++){
            $worklists2 = array_values($worklists[$i]["depth1_pk"]);
            ?>
            <tr>
                <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center" rowspan="<?php echo $worklists[$i]["menu_rows"];?>"><?php echo $worklists[$i]["menu_name"];?></td>
            <?php for($j=0;$j<count($worklists[$i]["depth1_pk"]);$j++){
                $pk_idss = $worklists2[$j];
                $worklists3 = array_values($worklists[$i][$pk_idss]["depth2_pk"]);
                ?>
                <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center" rowspan="<?php echo $worklists[$i][$pk_idss]["depth2_pk"]["count"];?>"><?php echo $worklists[$i][$pk_idss]["depth1_name"];?></td>
                <?php for($k=0;$k<count($worklists[$i][$pk_idss]["depth2_pk"]);$k++){
                    $pk_idss2 = $worklists3[$k];
                    $worklists4 = array_values($worklists[$i][$pk_idss][$pk_idss2]["content_pk"]);
                    if($worklists[$i][$pk_idss][$pk_idss2]["depth4_name"]==""){continue;}
                    ?>
                    <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center"  rowspan="<?php echo count($worklists[$i][$pk_idss][$pk_idss2]["content_pk"]);?>"><?php echo $worklists[$i][$pk_idss][$pk_idss2]["depth4_name"];?></td>
                    <?php for($l=0;$l<count($worklists[$i][$pk_idss][$pk_idss2]["content_pk"]);$l++){
                        $pk_idss3 = $worklists4[$l];
                        ?>
                        <td style="padding:5px;border-right:0.25pt solid #000;border-bottom:0.25pt solid #000;color:#000;text-align: center" class="contents"><?php echo $worklists[$i][$pk_idss][$pk_idss2][$pk_idss3]["content"];?></td>
                        <td style="padding:5px;border-bottom:0.25pt solid #000;color:#000;text-align: center" >- <?php echo $worklists[$i][$pk_idss][$pk_idss2][$pk_idss3]["delaydate"];?></td>
                        </tr>
                    <?php }?>
                <?php }?>
            <?php }?>
        <?php } ?>
    </table>

    </div>
</div>