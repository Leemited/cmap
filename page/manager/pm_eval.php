<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
$mypage = false;
$menu_id = "";
if($member["mb_level"]<5){
    alert("권한이 없습니다.", G5_URL);
}

include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$total=sql_fetch("select count(*) as cnt from `cmap_my_construct` where manager_mb_id='{$member["mb_id"]}' {$where} {$where1}");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=15;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_my_construct` where manager_mb_id='{$member["mb_id"]}'  {$where} {$where1} order by id desc limit {$start},{$rows}";
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
        <select name="search_type" id="search_type" class="basic_input01 width10">
            <option value="" <?php if($_GET["search_type"]==""){?>selected<?php }?>>전체</option>
            <option value="0" <?php if($_GET["search_type"]=="0"){?>selected<?php }?>>수신</option>
            <option value="1" <?php if($_GET["search_type"]=="1"){?>selected<?php }?>>발신</option>
        </select>
        <select name="const_id" id="cons_id" class="basic_input01" >
            <option value="">현장 선택</option>
            <?php for($i=0;$i<count($mycont);$i++){?>
                <option value="<?php echo $mycont[$i]["id"];?>" <?php if($const_id==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
            <?php }?>
        </select>
        <select name="sfl" id="sfl" class="basic_input01 width10">
            <option value="" <?php if($sfl==""){?>selected<?php }?>>전체</option>
            <option value="name" <?php if($sfl=="name"){?>selected<?php }?>>작성자</option>
            <option value="msg_subject" <?php if($sfl=="msg_subject"){?>selected<?php }?>>제목</option>
            <option value="msg_content" <?php if($sfl=="msg_content"){?>selected<?php }?>>내용</option>
        </select>
        <input type="submit" class="basic_btn01" value="검색">
    </form>
    <div class="work_msg_btns">
        <input type="button" class="basic_btn03" value="지구관리">
        <input type="button" class="basic_btn02" value="새로고침">
        <input type="button" class="basic_btn02" value="저장">
        <input type="button" class="basic_btn02" value="업무연락서" onclick="location.href=g5_url+'/page/mypage/my_message_list'">
    </div>
</div>
<div class="width-fixed board-width" style="padding:0 20px">
    <header class="sub">
        <h2>PROJECT MANAGER</h2>
    </header>
    <!--    <div style="text-align: right;display: inline-block;width: 100%;padding-bottom: 10px;">
            <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
        </div>-->
    <div class="pm_tab">
        <ul>
            <li onclick="location.href=g5_url+'/page/manager/'">공무행정 제출 지연 현황</li>
            <li class="active">시공평가 점수 관리</li>
            <li onclick="location.href=g5_url+'/page/manager/pm_eval2'">건설사업관리용역 평가 점수 관리</li>
        </ul>
    </div>
    <div class="view" style="padding:20px 0;">
        <table class="view_table" >
            <colgroup>
                <col width="5%">
                <col width="*">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
                <col width="10%">
            </colgroup>
            <tr>
                <th>구분</th>
                <th>현장명</th>
                <th>담당</th>
                <th>착공일</th>
                <th>준공일</th>
                <th>기간경과율</th>
                <th>제출지연 건수 계</th>
                <th>제출지연 일수 계</th>
                <th>평군지연 일수 계</th>
            </tr>
            <?php for($i=0;$i<count($worklist);$i++){
                $constmb = get_member($worklist[$i]["mb_id"]);
                //기간경과율 계산
                $start = new DateTime($worklist[$i]["cmap_construct_start"]);
                $todays = new DateTime($todays);
                $end = new DateTime($worklist[$i]["cmap_construct_finish"]);
                $totaldays = date_diff($start,$end);
                $nows = date_diff($start,$todays);
                $todals = $totaldays->days;
                $nowdays = $nows->days;
                $dayper = $nowdays - $totals * 100;
                if($dayper>100){
                    $dayper = "준공";
                }else{
                    $dayper .= "%";
                }
                //제출지연건수 (PM이 선택한 사람만 가져오기)

                //제출지연일수

                //


                ?>
                <tr>
                    <td class="td_center">
                        <input type="checkbox" name="const_id[]" id="const_<?php echo $worklist[$i]["id"];?>">
                        <label for="const_<?php echo $worklist[$i]["id"];?>"></label>
                    </td>
                    <td class="td_center" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $worklist[$i]["id"];?>'"><?php echo $worklist[$i]["cmap_name"];?></td>
                    <td class="td_center"><?php echo $constmb["mb_name"];?></td>
                    <td class="td_center"><?php echo $worklist[$i]["cmap_construct_start"];?></td>
                    <td class="td_center"><?php echo $worklist[$i]["cmap_construct_finish"];?></td>
                    <td class="td_center"><?php echo $dayper;?></td>
                    <td class="td_center"></td>
                    <td class="td_center"></td>
                    <td class="td_center"></td>
                </tr>
                <?php
            } ?>
            <?php if(count($worklist)==0){?>
                <tr>
                    <td colspan="7" class="td_center">발신/수신된 업무연락서가 없습니다.</td>
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
                        <li class="prev"><a href="<?php echo G5_URL."/page/manager/?page=".($page-1)."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?>">&lt;</a></li>
                    <?php } ?>
                    <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                        <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php if($page!=$i){?><?php echo G5_URL."/page/manager/?page=".$i."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?><?php }else{?>#<?php }?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                    <?php if($page<$total_page){?>
                        <li class="next"><a href="<?php echo G5_URL."/page/manager/?page=".($page+1)."&sfl=".$sfl."&date1=".$date1."&date2=".$date2."&search_type=".$search_type."&search_text=".$search_text."&const_id=".$const_id; ?>">&gt;</a></li>
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
