<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
$mypage = false;
$menu_id = "";
include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
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
    $date1 = date("Y-m-d",strtotime("- 1 Year"));
    $date2 = date("Y-m-d");
    $where .= " and send_date between '{$date1}' and '{$date2}'";
}

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
<div class="search" style="position: relative;" id="msg_search">
    <form action="" method="get">
        <select name="const_id" id="cons_id" class="basic_input01" >
            <option value="">현장 선택</option>
            <?php for($i=0;$i<count($mycont);$i++){?>
                <option value="<?php echo $mycont[$i]["id"];?>" <?php if($const_id==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
            <?php }?>
        </select>
        <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker1" name="date1" value="<?php if($date1==""){echo date("Y-m-d");}else{echo $date1;}?>">
        <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker2" name="date2" value="<?php if($date2==""){echo date("Y-m-d");}else{echo $date2;}?>">
        <select name="search_type" id="search_type" class="basic_input01 width10">
            <option value="" <?php if($_GET["search_type"]==""){?>selected<?php }?>>전체</option>
            <option value="0" <?php if($_GET["search_type"]=="0"){?>selected<?php }?>>수신</option>
            <option value="1" <?php if($_GET["search_type"]=="1"){?>selected<?php }?>>발신</option>
        </select>
        <select name="sfl" id="sfl" class="basic_input01 width10">
            <option value="" <?php if($sfl==""){?>selected<?php }?>>전체</option>
            <option value="name" <?php if($sfl=="name"){?>selected<?php }?>>작성자</option>
            <option value="msg_subject" <?php if($sfl=="msg_subject"){?>selected<?php }?>>제목</option>
            <option value="msg_content" <?php if($sfl=="msg_content"){?>selected<?php }?>>내용</option>
        </select>
        <input type="text" class="basic_input01 width20" id="datepicker2" name="search_text" value="<?php echo $search_text;?>" placeholder="검색어">
        <input type="submit" class="basic_btn03" value="검색">
    </form>
    <div class="work_msg_btns">
        <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
    </div>
</div>
<div class="width-fixed board-width" style="padding:0 20px">
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
                <col width="12%">
                <col width="12%">
                <col width="*">
                <col width="8%">
                <col width="8%">
                <col width="8%">
            </colgroup>
            <tr>
                <th>번호</th>
                <th>구분</th>
                <th>발신자</th>
                <th>수신자</th>
                <th>제목</th>
                <th>발신일</th>
                <th>수신일</th>
                <th>회신완료</th>
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
                if(count($read_mb_id)==count($msg_read_members)){
                    if($worklist[$i]["msg_read_member"]==""){
                        $read_tchk = false;
                    }else {
                        $read_tchk = true;
                    }
                }else{
                    $read_tchk = false;
                }
                ?>
                <tr>
                    <td class="td_center"><?php echo $worklist[$i]["num"];?></td>
                    <td class="td_center"><?php echo $msg_type;?></td>
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
                    <td onclick="fnWriteMessage('<?php echo $worklist[$i]["id"];?>')">
                        <?php echo ($worklist[$i]["msg_subject"])?$worklist[$i]["msg_subject"]:"제목없음";?><?php if($worklist[$i]["msg_count"]!=0){echo "_".str_pad($worklist[$i]["msg_count"],0,'',STR_PAD_LEFT);}?>
                    </td>
                    <td class="td_center" title="<?php echo $worklist[$i]["send_date"].' '.$worklist[$i]["send_time"]?>"><?php echo $worklist[$i]["send_date"];?></td>
                    <td class="td_center" title="<?php echo $worklist[$i]["read_date"].' '.$worklist[$i]["read_time"]?>"><?php if($read_tchk==false){echo "<span style='color:red;font-weight:bold'>미확인</span>";}else{echo $worklist[$i]["read_date"];}?></td>
                    <td class="td_center" title="<?php echo $worklist[$i]["msg_retype_date"].' '.$worklist[$i]["msg_retype_time"]?>"><?php if($worklist[$i]["msg_retype_date"]){echo $worklist[$i]["msg_retype_date"];}else{echo "<span style='color:red;font-weight:bold'>미회신</span>";}?></td>
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
