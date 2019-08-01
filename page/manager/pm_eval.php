<?php
include_once ("../../common.php");
include_once (G5_PATH."/page/manager/manager_auth.php");

$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "depth_desc_pmmode";
$test = "mng";
if(!$is_member){
    goto_url(G5_BBS_URL."/login?url=".G5_URL."/page/mylocation/mylocation");
}

if($member["mb_level"]<5){
    alert("권한이 없습니다.", G5_URL);
}

include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

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
if($totaleval4_01>0){
    $totaltoyear4[0] = $totaleval4_01 / $totaleval4_cnt;
    $totaltoyear4[1] = $totaleval4_02 / $totaleval4_cnt;
    $totaltoyear4[2] = $totaleval4_03 / $totaleval4_cnt;
    $totaltoyear4[3] = $totaleval4_04 / $totaleval4_cnt;
}
if($totaleval5_01>0){
    $totaltoyear5[0] = $totaleval5_01 / $totaleval5_cnt;
    $totaltoyear5[1] = $totaleval5_02 / $totaleval5_cnt;
    $totaltoyear5[2] = $totaleval5_03 / $totaleval5_cnt;
    $totaltoyear5[3] = $totaleval5_04 / $totaleval5_cnt;
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
?>
<div class="etc_view messages eval" >

</div>
<span class="etc_view_bg"></span>
<div class="width-fixed board-width" style="padding:150px 20px 0 20px">
    <header class="sub">
        <h2>PROJECT MANAGER</h2>
    </header>
    <!--    <div style="text-align: right;display: inline-block;width: 100%;padding-bottom: 10px;">
            <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
        </div>-->
    <div class="pm_tab">
        <ul>
            <li onclick="location.href=g5_url+'/page/manager/?mngType=1'">공무행정 제출 지연 현황</li>
            <li class="active">시공평가 점수 관리</li>
            <li onclick="location.href=g5_url+'/page/manager/pm_eval2?mngType=3'">건설사업관리용역 평가 점수 관리</li>
        </ul>
    </div>
    <div class="eval2_view">
        <div class="view" style="padding:0;">
            <div class="pm_view eval_view">
                <table class="view_table table_head eval2_table" >
                    <colgroup>
                        <!--<col width="2%">-->
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
                        <!--<th rowspan="2">구분</th>-->
                        <th rowspan="2">PM 보고서</th>
                        <th rowspan="2">현장명</th>
                        <th rowspan="2">담당</th>
                        <th rowspan="2">착공일</th>
                        <th rowspan="2">준공일</th>
                        <th rowspan="2">기간경과율</th>
                        <th rowspan="2">계약금액</th>
                        <th rowspan="2">평가기관<br>통보점수</th>
                        <th rowspan="2">시공평가점수</th>
                        <th colspan="3">시공평가 100(점)</th>
                    </tr>
                    <tr>
                        <th>공사관리</th>
                        <th>품질 및 성능</th>
                        <th>가감점</th>
                    </tr>
                </table>
                <div class="pm_in_view">
                    <table class="view_table eval2_table" >
                        <colgroup>
                            <!--<col width="2%">-->
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
                                <!--<td class="td_center">
                                    <input type="checkbox" name="const_id[]" id="const_<?php /*echo $worklist[$i]["id"];*/?>" checked value="<?php /*echo $worklist[$i]["id"];*/?>">
                                    <label for="const_<?php /*echo $worklist[$i]["id"];*/?>"></label>
                                </td>-->
                                <td class="td_center">
                                    <input type="button" value="보고서" class="basic_btn02" style="padding:5px 10px;" onclick="fnPmPreview(2,'<?php echo $worklist[$i]["id"];?>')">
                                </td>
                                <td class="td_center" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $worklist[$i]["id"];?>'" title="<?php echo $worklist[$i]["cmap_name"];?>" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;"><?php echo $worklist[$i]["cmap_name"];?></td>
                                <td class="td_center"><?php echo $constmb["mb_name"];?></td>
                                <td class="td_center"><?php echo $worklist[$i]["cmap_construct_start"];?></td>
                                <td class="td_center"><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
                                <td class="td_center"><?php echo $dayper;?></td>
                                <td class="td_center "><input type="text" class="basic_input01 width100 td_center" onchange="fnEvalPm('<?php echo $worklist[$i]["id"];?>',this.value,'const_price')" value="<?php echo ($worklist[$i]["pm_price"])?$worklist[$i]["pm_price"]:"";?>" <?php if($worklist[$i]["pm_price"]==""){?>placeholder="계약금액 입력"<?php }?>></td>
                                <td class="td_center "><input type="text" class="basic_input01 width100 td_center" onchange="fnEvalPm('<?php echo $worklist[$i]["id"];?>',this.value,'const_percent')" value="<?php echo ($worklist[$i]["pm_percent"])?$worklist[$i]["pm_percent"]:"";?>" <?php if($worklist[$i]["pm_percent"]==""){?>placeholder="계약금액 입력"<?php }?>></td>
                                <td class="td_center eval_point"><?php echo $worklist[$i]["sum"];?></td>
                                <td class="td_center"><?php echo $worklist[$i]["eval_01"];?></td>
                                <td class="td_center"><?php echo $worklist[$i]["eval_02"];?></td>
                                <td class="td_center"><?php echo $worklist[$i]["eval_03"];?></td>
                            </tr>
                            <?php
                        } ?>
                        <?php if(count($worklist)==0){?>
                            <tr>
                                <td colspan="12" class="td_center">등록된 PM현장이 없습니다.</td>
                            </tr>
                        <?php   }?>
                    </table>
                </div>
            <table class="view_table point_view eval2_table">
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
                    <td colspan="5">구분</td>
                    <td>배점</td>
                    <td colspan="2">계약금액 가중 평점</td>
                    <td>100</td>
                    <td>65</td>
                    <td>35</td>
                    <td></td>
                </tr>
                <tr class="toyear">
                    <td colspan="5">최근 3개년 평균</td>
                    <td>3개년 평균</td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo round($yearTotalPmPrice,2);?></td>
                    <td class="eval_point_td eval_point"><?php echo round($alls4,2);?></td>
                    <td class="eval_point_td"><?php echo round($alls1,2);?></td>
                    <td class="eval_point_td"><?php echo round($alls2,2);?></td>
                    <td class="eval_point_td"><?php echo round($alls3,2);?></td>
                </tr>
                <tr class="years">
                    <td colspan="5" rowspan="3">최근 3개년 년도별 평균 </td>
                    <td><?php echo date("Y");?></td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer1;?></td>
                    <td class="eval_point_td eval_point"><?php echo round($totaltoyear[3],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear[0],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear[1],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear[2],2);?></td>
                </tr>
                <tr class="years">
                    <td><?php echo date("Y",strtotime("- 1 year"));?></td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer2;?></td>
                    <td class="eval_point_td eval_point"><?php echo round($totaltoyear2[3],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear2[0],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear2[1],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear2[2],2);?></td>
                </tr>
                <tr class="years">
                    <td><?php echo date("Y",strtotime("- 2 year"));?></td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer3;?></td>
                    <td class="eval_point_td eval_point"><?php echo round($totaltoyear3[3],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear3[0],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear3[1],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear3[2],2);?></td>
                </tr>
                <tr class="years">
                    <td rowspan="2" colspan="5">3개년 이전</td>
                    <td><?php echo date("Y",strtotime("- 3 year"));?></td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer4;?></td>
                    <td class="eval_point_td eval_point"><?php echo round($totaltoyear4[3],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear4[0],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear4[1],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear4[2],2);?></td>
                </tr>
                <tr class="years">
                    <td><?php echo date("Y",strtotime("- 4 year"));?></td>
                    <td class="eval_point_td eval_point" colspan="2"><?php echo $yearPMPer5;?></td>
                    <td class="eval_point_td eval_point"><?php echo round($totaltoyear5[3],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear5[0],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear5[1],2);?></td>
                    <td class="eval_point_td"><?php echo round($totaltoyear5[2],2);?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
    $(function(){
        <?php if($msg_id){?>
        fnWriteMessage('<?php echo $msg_id;?>')
        <?php }?>

        $(".datepicker").datepicker({
            showOn: "both", // 버튼과 텍스트 필드 모두 캘린더를 보여준다.
            buttonImage: "<?php echo G5_IMG_URL;?>/ic_calendar.png", // 버튼
            buttonImageOnly: true, // 버튼에 있는 이미지만 표시한다.
            changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
            changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
            minDate: '-20y', // 현재날짜로부터 100년이전까지 년을 표시한다.
            nextText: '다음 달', // next 아이콘의 툴팁.
            prevText: '이전 달', // prev 아이콘의 툴팁.
            numberOfMonths: [1,1], // 한번에 얼마나 많은 월을 표시할것인가. [2,3] 일 경우, 2(행) x 3(열) = 6개의 월을 표시한다.
            stepMonths: 1, // next, prev 버튼을 클릭했을때 얼마나 많은 월을 이동하여 표시하는가.
            yearRange: 'c-20:c+20', // 년도 선택 셀렉트박스를 현재 년도에서 이전, 이후로 얼마의 범위를 표시할것인가.
            showButtonPanel: true, // 캘린더 하단에 버튼 패널을 표시한다.
            currentText: '오늘 날짜' , // 오늘 날짜로 이동하는 버튼 패널
            closeText: '닫기',  // 닫기 버튼 패널
            dateFormat: "yy-mm-dd", // 텍스트 필드에 입력되는 날짜 형식.
            showMonthAfterYear: true , // 월, 년순의 셀렉트 박스를 년,월 순으로 바꿔준다.
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], // 요일의 한글 형식.
            monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] // 월의 한글 형식.
        });

        /*var tbheight = $(".eval_view .view_table").height();
        var viewheight = $(".eval_view").height();
        if(viewheight < tbheight){
            $(".table_head").css("padding-right","5px");
            $(".point_view").css({"width":"1555px","left":"calc(50% - 3px)"});
        }*/
    });

    function fnEvalPm(constid,val,type){
        if(val>100 && type == "const_percent"){
            alert("통보점수는 100보다 클수 없습니다.");
            return false;
        }
        $.ajax({
            url:g5_url+'/page/ajax/ajax.pmmode_eval_save.php',
            method:"post",
            data:{constid:constid,val:val,type:type,eval_type:1}
        }).done(function(data){
            if(data==1){
                location.reload();
            }
        });
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
