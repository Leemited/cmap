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
$c = 0;
while($row = sql_fetch_array($res)){
    $mng_mb_id = explode(",",$row["manager_mb_id"]);
    $mng_chk = false;
    for($i=0;$i<count($mng_mb_id);$i++){
        if($member["mb_id"]==$mng_mb_id[$i]){
            $mng_chk = true;
            continue;
        }
    }
    if($mng_chk == false){
        continue;
    }

    $pmworklist[$c] = $row;
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
                                if (strpos($idss[$c], $delayrow_pm["depth4_pk_id"]) !== false) {
                                    //$delaydate -= $days;
                                    continue;
                                }
                            }
                            $delaydate += $days;
                            $idss[$c] .= ',' . $delayrow_pm["depth4_pk_id"];
                            $delaycount++;
                            //$delaydate += $days;
                        }
                    }
                }
            }
        }
        $totaldates = round($delaydate / $delaycount,2);
        $pmworklist[$c]["delaycount"] = $delaycount;
        $pmworklist[$c]["delaydate"] = $delaydate;
        $pmworklist[$c]["delaytotal"] = $totaldates;

        unset($map_pk_id_pm);
        unset($map_pk_actives_pm);
        unset($map_pk_actives_date_pm);
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
                                if (strpos($idss[$c], $delayrow_pm["depth4_pk_id"]) !== false) {
                                    continue;
                                }
                            }
                            $delaydate += $days;
                            $idss[$c] .= ',' . $delayrow_pm["depth4_pk_id"];
                            $delaycount++;
                        }
                    }
                }
            }

            $a++;
        }
        $totaldates = round($delaydate / $delaycount,'2');
        $pmworklist[$c]["delaycount"] = $delaycount;
        $pmworklist[$c]["delaydate"] = $delaydate;
        $pmworklist[$c]["delaytotal"] = $totaldates;

        unset($map_pk_id_pm);
        unset($map_pk_actives_pm);
        unset($map_pk_actives_date_pm);
    }
    $totalDelay += $pmworklist[$c]["delaycount"];
    $totalDelayDate += $pmworklist[$c]["delaydate"];

    $c++;
}

$totalDelayDatePer = round($totalDelayDate / $totalDelay,2) ;
?>
<div class="etc_view messages">

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
            <li class="active">공무행정 제출 지연 현황</li>
            <li onclick="location.href=g5_url+'/page/manager/pm_eval?mngType=2'">시공평가 점수 관리</li>
            <li onclick="location.href=g5_url+'/page/manager/pm_eval2?mngType=3'">건설사업관리용역 평가 점수 관리</li>
        </ul>
    </div>
    <div class="view" style="padding:20px 0;">
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
                <!--<th>구분</th>-->
                <th>PM 보고서</th>
                <th>현장명</th>
                <th>담당</th>
                <th>착공일</th>
                <th>준공일</th>
                <th>기간경과율</th>
                <th>제출지연 건수 계</th>
                <th>제출지연 일수 계</th>
                <th>평균지연 일수 계</th>
            </tr>
        </table>
        <div class="pm_view">
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
                <?php for($i=0;$i<count($pmworklist);$i++){
                        $constmb = get_member($pmworklist[$i]["mb_id"]);
                        //기간경과율 계산
                        if(date("Y-m-d") <= $pmworklist[$i]["cmap_construct_start_temp"]){
                            $dayper = "0%";
                        }else {
                            $start[$i] = new DateTime($pmworklist[$i]["cmap_construct_start_temp"]);
                            $todayss[$i] = new DateTime($todays);
                            $end[$i] = new DateTime($pmworklist[$i]["cmap_construct_finish"]);
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
                            <input type="button" value="보고서" class="basic_btn02" style="padding:5px 10px;" onclick="fnPmPreview(1,'<?php echo $pmworklist[$i]["id"];?>')">
                        </td>
                        <td class="td_center" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $pmworklist[$i]["id"];?>'" ><?php echo $pmworklist[$i]["cmap_name"];?></td>
                        <td class="td_center" style=""><?php echo $constmb["mb_name"];?></td>
                        <td class="td_center"><?php echo $pmworklist[$i]["cmap_construct_start_temp"];?></td>
                        <td class="td_center"><?php echo $pmworklist[$i]["cmap_construct_finish"];?></td>
                        <td class="td_center"><?php echo $dayper;?></td>
                        <td class="td_center"><?php echo number_format($pmworklist[$i]["delaycount"]);?> 건</td>
                        <td class="td_center"><?php echo number_format($pmworklist[$i]["delaydate"]);?> 일</td>
                        <td class="td_center"><?php echo number_format($pmworklist[$i]["delaytotal"]);?> 일</td>
                    </tr>
                    <?php
                } ?>
                <?php if(count($pmworklist)==0){?>
                    <tr>
                        <td colspan="7" class="td_center">등록된 PM현장이 없습니다.</td>
                    </tr>
                <?php   }?>
            </table>
        </div>
        <table class="view_table point_view">
            <colgroup>
                <col width="5%">
                <col width="15%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <tr>
                <td colspan="5">소계</td>
                <td>계</td>
                <td><?php echo number_format($totalDelay);?> 건</td>
                <td><?php echo number_format($totalDelayDate);?> 일</td>
                <td><?php echo $totalDelayDatePer;?> 일</td>
            </tr>
        </table>
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
    })
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
