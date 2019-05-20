<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "";
include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');


if($const_id){
    $where .= " and const_id = '{$const_id}' ";
}
$total=sql_fetch("select count(*) as cnt from `cmap_construct_work_msg` where (send_mb_id = '{$member["mb_id"]}' or read_mb_id = '{$member["mb_id"]}') {$where}");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_construct_work_msg` where (send_mb_id = '{$member["mb_id"]}' or read_mb_id = '{$member["mb_id"]}') {$where} order by id desc";
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
<div class="search" style="position: relative;">
    <form action="">
    <select name="const_id" id="cons_id" class="basic_input01">
        <option value="">현장 선택</option>
        <?php for($i=0;$i<count($mycont);$i++){?>
            <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
        <?php }?>
    </select>
    <input type="text" class="datepicker basic_input01" id="datepicker1" name="date1" value="<?php if($date1==""){echo date("Y-m-d");}?>">
    <input type="text" class="datepicker basic_input01" id="datepicker2" name="date2" value="<?php if($date2==""){echo date("Y-m-d");}?>">
    <input type="button" class="basic_btn03" value="검색">
    </form>
    <div class="work_msg_btns">
        <input type="button" class="basic_btn02" value="업무연락서" onclick="fnWriteMessage('')">
    </div>
</div>
<div class="width-fixed board-width" style="padding:0 20px">
    <header class="sub">
        <h2>업무연락서 관리</h2>
    </header>
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
            </colgroup>
            <tr>
                <th>번호</th>
                <th>구분</th>
                <th>발신자</th>
                <th>수신자</th>
                <th>내용</th>
                <th>발신일</th>
                <th>수신일</th>
            </tr>
            <?php for($i=0;$i<count($worklist);$i++){
                if($worklist[$i]["send_mb_id"]!=$member["mb_id"]){
                    $msg_type = "수신";
                }else if($worklist[$i]["send_mb_id"]==$member["mb_id"]){
                    $msg_type = "발신";
                }
                $mb1 = get_member($worklist[$i]["send_mb_id"]);
                $mb2 = get_member($worklist[$i]["read_mb_id"]);
                ?>
                <tr>
                    <td class="td_center"><?php echo $worklist[$i]["num"];?></td>
                    <td class="td_center"><?php echo $msg_type;?></td>
                    <td class="td_center"><div><?php echo $mb1["mb_name"];?></div></td>
                    <td class="td_center"><div onclick="fnMemberView('<?php echo $mb2["mb_id"];?>')"><?php echo $mb2["mb_name"];?></div></td>
                    <td onclick="fnWriteMessage('<?php echo $worklist[$i]["id"];?>')"><?php echo ($worklist[$i]["msg_subject"])?$worklist[$i]["msg_subject"]:"제목없음";?><?php if($worklist[$i]["msg_count"]!=0){echo "_".$worklist[$i]["msg_count"];}?></td>
                    <td class="td_center"><?php echo $worklist[$i]["send_date"];?></td>
                    <td class="td_center"><?php if($worklist[$i]["read_date"]==""){echo "<span style='color:red;font-weight:bold'>미확인</span>";}else{echo $worklist[$i]["read_date"];}?></td>
                </tr>
            <?php }?>
            <?php if(count($worklist)==0){?>
                <tr>
                    <td colspan="6" class="td_center">발신/수신된 업무연락서가 없습니다.</td>
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
                        <li class="prev"><a href="<?php echo G5_URL."/admin/product_list.php?page=".($page-1)."&stx=".$stx."&sfl=".$sfl."&cate1=".$cate1."&cate2=".$cate2; ?>">&lt;</a></li>
                    <?php } ?>
                    <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                        <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/product_list.php?page=".$i."&stx=".$stx."&sfl=".$sfl."&cate1=".$cate1."&cate2=".$cate2; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                    <?php if($page<$total_page){?>
                        <li class="next"><a href="<?php echo G5_URL."/admin/product_list.php?page=".($page+1)."&stx=".$stx."&sfl=".$sfl."&cate1=".$cate1."&cate2=".$cate2; ?>">&gt;</a></li>
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