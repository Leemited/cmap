<?php
include_once ("../../common.php");
if($member["mb_level"]==5){
    include_once (G5_PATH."/page/manager/manager_auth.php");
}

if(!$is_member){
    goto_url(G5_BBS_URL."/login.php");
}

$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "depth_desc_workmsg";
$test = "msg";
if(isset($_GET["const_id"])){
    $const_id = $_GET["const_id"];
}else{
    $const_id = $current_const["const_id"];
}

if($const_id){
    $where .= " and const_id = '{$const_id}' ";
}
if($_GET["date1"] && $_GET["date2"]){
    $where .= " and send_date between '{$_GET["date1"]}' and '{$_GET["date2"]}'";
}else{
    $date1 = date("Y-m-d", strtotime("- 1 Year"));
    $date2 = date("Y-m-d");
    $where .= " and send_date between '{$date1}' and '{$date2}'";
}
include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


if($search_text) {
    if ($_GET["sfl"] == "name") {
        $sql = "select * from `g5_member` where mb_name like '%{$search_text}%' and mb_leave_date = ''";
        $res = sql_query($sql);
        while($row = sql_fetch_array($res)){
            if($row["mb_id"]==$member["mb_id"]){
                continue;
            }
            if($mb_ids==""){
                $mb_ids = "'".$row["mb_id"]."'";
            }else{
                $mb_ids .= ",'".$row["mb_id"]."'";
            }
        }
    } else if ($_GET["sfl"]!="") {
        $where .= " and `{$sfl}` like '%{$_GET["search_text"]}%'";
    }
}

if($_GET["search_type"]=="0"){
    if($mb_ids){
        $mm = explode(",",$mb_ids);
        for($i=0;$i<count($mm);$i++){
            if($where1=="") {
                $where1 = " and (FIND_IN_SET({$mm[$i]},read_mb_id) ";
            }else{
                $where1 .= " or FIND_IN_SET({$mm[$i]},read_mb_id) ";
            }
            if(($i+1)==count($mm)){
                $where1 .= ") and send_mb_id = '{$member["mb_id"]}' ";
            }
        }
    }else {
        $where .= " and instr(read_mb_id,'{$member["mb_id"]}') != 0";
    }
}else if($_GET["search_type"]=="1"){
    if($mb_ids){
        $where .= " and send_mb_id in ({$mb_ids}) and instr(read_mb_id,'{$member["mb_id"]}') != 0";
    }else {
        $where .= " and send_mb_id = '{$member["mb_id"]}'";
    }
}else if(!$_GET["search_type"] || $_GET["search_type"]==""){
    if($mb_ids){
        $mm = explode(",",$mb_ids);
        for($i=0;$i<count($mm);$i++){
            if($where2=="") {
                $where2 = " or (FIND_IN_SET({$mm[$i]},read_mb_id) ";
            }else{
                $where2 .= " or FIND_IN_SET({$mm[$i]},read_mb_id) ";
            }
            if(($i+1)==count($mm)){
                $where2 .= ") ";
            }
        }
        $where .= " and (send_mb_id = '{$member["mb_id"]}' or instr(read_mb_id,'{$member["mb_id"]}') != 0) and (send_mb_id in ({$mb_ids}) {$where2})";
    }else {
        $where .= " and (send_mb_id = '{$member["mb_id"]}' or instr(read_mb_id,'{$member["mb_id"]}') != 0)";
    }
}

if($msg_id){
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $msgs = sql_fetch($sql);
    $const_id = $msgs["const_id"];
}

$total=sql_fetch("select count(*) as cnt from `cmap_construct_work_msg` where  1 {$where} {$where1}");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=15;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_construct_work_msg` where 1 {$where} {$where1} order by id desc limit {$start},{$rows}";
$res = sql_query($sql);
$c = 0;
while($row = sql_fetch_array($res)){
    $worklist[$c] = $row;
    $worklist[$c]['num']=$total-($start)-$c;
    $c++;
}

?>
<div class="etc_view messages">

</div>
<span class="etc_view_bg"></span>

<div class="width-fixed board-width" style="padding-left:20px;padding-right:20px;padding-top:180px;">
    <header class="sub">
        <h2>업무연락서 관리</h2>
    </header>
<!--    <div style="text-align: right;display: inline-block;width: 100%;padding-bottom: 10px;">
        <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
    </div>-->
    <div class="view" style="padding:20px 0;">
        <table class="view_table">
            <colgroup>
                <col width="5%">
                <col width="10%">
                <col width="10%">
                <col width="12%">
                <col width="12%">
                <col width="*">
                <col width="8%">
                <col width="8%">
                <col width="8%">
                <!--<col width="8%">-->
            </colgroup>
            <tr>
                <th>번호</th>
                <th>구분</th>
                <th>문서번호</th>
                <th>발신자</th>
                <th>수신자</th>
                <th>제목</th>
                <th>발신일</th>
                <th>수신일</th>
                <th>회신완료</th>
                <!--<th>회신기한</th>-->
            </tr>
            <?php for($i=0;$i<count($worklist);$i++){
                if($worklist[$i]["send_mb_id"]!=$member["mb_id"]){
                    $msg_type = "수신";
                }else if($worklist[$i]["send_mb_id"]==$member["mb_id"]){
                    $msg_type = "발신";
                }
                $mb1 = get_member($worklist[$i]["send_mb_id"]);
                $read_mb_id = explode(",",$worklist[$i]["read_mb_id"]);
                foreach($read_mb_id as $rmb){
                    $mb2[] = get_member($rmb);
                }
                $msg_read_members = explode(",",$worklist[$i]["msg_read_member"]);
                $msg_read_date = explode(",",$worklist[$i]["read_date"]);
                $msg_read_time = explode(",",$worklist[$i]["read_time"]);
                if(count($read_mb_id)==count($msg_read_members)){
                    if($worklist[$i]["msg_read_member"]==""){
                        $read_tchk = false;
                    }else {
                        $read_tchk = true;
                    }
                }else{
                    $read_tchk = false;
                }
                
                $msg_read_info = "";

                if($msg_read_info==""){
                    $msg_read_info = "수신현황 \r";
                    $mm = explode(",",$worklist[$i]["read_mb_id"]);
                    for($j=0;$j<count($mm);$j++){
                        $mss = get_member($mm[$j]);
                        if(in_array($mm[$j],$msg_read_members)){
                            if ($msg_read_info) {
                                $msg_read_info .= "\n";
                            }
                            for($a=0;$a<count($msg_read_members);$a++){
                                if($mm[$j]==$msg_read_members[$a]){
                                    $msg_read_info .= $mss["mb_name"] . " : ".$msg_read_date[$a]." ".$msg_read_date[$a];
                                }
                            }
                        }else {
                            if ($msg_read_info) {
                                $msg_read_info .= "\n";
                            }
                            $msg_read_info .= $mss["mb_name"] . " : 미수신";
                        }
                    }
                }
                $msg_retype_members = explode(",",$worklist[$i]["msg_retype_member"]);
                $msg_retype_date = explode(",",$worklist[$i]["msg_retype_date"]);
                $msg_retype_time = explode(",",$worklist[$i]["msg_retype_time"]);
                $msg_retype_info = "";

                if($msg_retype_info==""){
                    $msg_retype_info = "회신 현황 \r";
                    $mms = explode(",",$worklist[$i]["read_mb_id"]);
                    for($j=0;$j<count($mms);$j++){
                        $mss2 = get_member($mms[$j]);
                        if(in_array($mms[$j],$msg_retype_members)){
                            if ($msg_retype_info) {
                                $msg_retype_info .= "\n";
                            }
                            for($a=0;$a<count($msg_retype_members);$a++){
                                if($mm[$j]==$msg_retype_members[$a]){
                                    $msg_retype_info .= $mss2["mb_name"] . " : ".$msg_retype_date[$a]." ".$msg_retype_time[$a];
                                }
                            }
                        }else {
                            if ($msg_retype_info) {
                                $msg_retype_info .= "\n";
                            }
                            $msg_retype_info .= $mss2["mb_name"] . " : 미회신";
                        }
                    }
                }
                ?>
                <tr>
                    <td class="td_center"><?php echo $worklist[$i]["num"];?></td>
                    <td class="td_center"><?php echo $msg_type;?></td>
                    <td class="td_center"><?php if($worklist[$i]["msg_count"]!=0){echo str_pad($worklist[$i]["msg_count"],0,'',STR_PAD_LEFT);}?> 호</td>
                    <td class="td_center"><div><?php echo $mb1["mb_name"];?></div></td>
                    <td class="td_center">
                        <div onclick="fnMemberView('<?php echo $mb2["mb_id"];?>')">
                            <?php
                            $a=0;
                            foreach ($mb2 as $mbs){
                                if($a!=0){echo ",";}
                                ?>
                                <?php echo $mbs["mb_name"];?>
                            <?php $a++;}?>
                        </div>
                    </td>
                    <td onclick="fnWriteMessage2('<?php echo $worklist[$i]["id"];?>')" style="text-decoration: underline;cursor: pointer;padding:5px">
                        <?php echo ($worklist[$i]["msg_subject"])?$worklist[$i]["msg_subject"]:"제목없음";?>
                    </td>
                    <td class="td_center" title="<?php echo $worklist[$i]["send_date"].' '.$worklist[$i]["send_time"]?>"><?php echo $worklist[$i]["send_date"];?></td>
                    <td class="td_center" title="<?php echo $msg_read_info;?>"><?php if($read_tchk==false){echo "<span style='color:red;font-weight:bold'>미확인</span>";}else{echo array_pop(explode(",",$worklist[$i]["read_date"]));}?></td>
                    <td class="td_center" title="<?php echo $msg_retype_info?>"><?php if($worklist[$i]["msg_retype"]==1){if($worklist[$i]["msg_retype_status"]==1){echo $worklist[$i]["msg_retype_date"];}else{echo "<span style='color:red;font-weight:bold'>미회신</span>";} } else{ echo "-"; }?></td>
                    <!--<td></td>-->
                </tr>
            <?php
                unset($mb2);
            }?>
            <?php if(count($worklist)==0){?>
                <tr>
                    <td colspan="8" class="td_center">발신/수신된 업무연락서가 없습니다.</td>
                </tr>
            <?php   }?>
        </table>
        <?php
        if($total_page>1){
            $start_page=1;
            $end_page=$total_page;
            if($total_page>5){
                if($total_page<($page+2)){
                    $start_page=$total_page-4;
                    $end_page=$total_page;
                }else if($page>3){
                    $start_page=$page-2;
                    $end_page=$page+2;
                }else{
                    $start_page=1;
                    $end_page=5;
                }
            }
            ?>
            <div class="num_list01">
                <ul>
                    <?php if($page!=1){?>
                        <li class="prev"><a href="<?php echo G5_URL."/page/mypage/my_message_list?page=".($page-1)."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?>">&lt;</a></li>
                    <?php } ?>
                    <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                        <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php if($page!=$i){?><?php echo G5_URL."/page/mypage/my_message_list?page=".$i."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?><?php }else{?>#<?php }?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                    <?php if($page<$total_page){?>
                        <li class="next"><a href="<?php echo G5_URL."/page/mypage/my_message_list?page=".($page+1)."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?>">&gt;</a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
        ?>
    </div>
</div>
<script>
    $(function(){
        <?php if($msg_id){?>
        setTimeout(function(){fnWriteMessage2('<?php echo $msg_id;?>')},1000);
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
