<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
include_once (G5_PATH."/head.php");

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$sql = "select * from `cmap_menu` where SUBSTRING(menu_code,1,2) like '%40%' and menu_code != 40 and menu_status = 0 order by menu_order asc";
$res = sql_query($sql);
$i=0;
while($row = sql_fetch_array($res)){
    $list["depth1"][$i]=$row;
    $sql = "select * from `cmap_depth1` where me_code = '{$row["menu_code"]}' order by id asc";
    $ress = sql_query($sql);
    $l=0;
    $cnt = 0;
    while($rows = sql_fetch_array($ress)){
        $list["depth1"][$i]["depth2"][$l]=$rows;
        $l++;
    }
    $list["cnt"][$i] = $l;
    $i++;
}
?>
<div class="width-fixed board-width">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout.php"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="construct_write">
            <h2>현장개설</h2>
            <h3><i></i> 공사 및 착·준공 관리</h3>
            <div class="save_btns">
                <input type="button" value="저장" class="basic_btn03" onclick="fnSave();">
            </div>
            <form action="<?php echo G5_URL;?>/page/mylocation/mylocation_step3_update.php" method="post" name="write_step2">
                <input type="hidden" value="<?php echo $id;?>" name="id">
            <div class="write_box">
                <table class="write_date1">
                    <tr>
                        <th>계약상 착공일</th>
                        <td>
                            <input type="text" id="datepicker1" class="datepicker" name="date1" value="<?php echo date("Y-m-d");?>">
                        </td>
                    </tr>
                </table>
                <table class="write_date2">
                    <tr>
                        <th>실 착공일</th>
                        <td>
                            <input type="text" id="datepicker2" class="datepicker" name="date2" value="<?php echo date("Y-m-d");?>">
                        </td>
                    </tr>
                </table>
                <table class="write_date3">
                    <tr>
                        <th>준공일</th>
                        <td>
                            <input type="text" id="datepicker3" class="datepicker" name="date3" value="<?php echo date("Y-m-d", strtotime(" +1 month"));?>" >
                        </td>
                    </tr>
                </table>
                <div class="clear"></div>
                <div class="sub_cates">
                    <form action="">
                        <table class="sub_table">
                            <tr>
                                <th style="width:10%">공사명</th>
                                <th style="width:20%">분야</th>
                                <th style="width:auto;">요약</th>
                                <th style="width:80px;">적용</th>
                                <th style="width:220px">착수일</th>
                                <th style="width:220px">종료일</th>
                            </tr>

                            <?php
                                for($i=0;$i<count($list["depth1"]);$i++){
                                    $a=0;
                                ?>
                                <tr class="start_tr <?php if($i==0){?>zero<?php }?>">
                                    <td class="depth1 td_center" rowspan="<?php echo $list["cnt"][$i];?>">
                                        <?php echo $list["depth1"][$i]["menu_name"];?>
                                    </td>
                                    <?php for($j=0;$j<count($list["depth1"][$i]["depth2"]);$j++){
                                        $a++;
                                        if($j!=0){ if(($j+1)==$list["cnt"][$i]){echo "<tr class='last_tr'>";}else{echo "<tr>";}}
                                        ?>
                                        <td class="depth2 td_center">
                                            <?php echo $list["depth1"][$i]["depth2"][$j]["depth_name"];?>
                                        </td>
                                        <td class="depth3">
                                            <?php echo ($list["depth1"][$i]["depth2"][$j]["depth_desc"])?$list["depth1"][$i]["depth2"][$j]["depth_desc"]:"등록된 요약이 없습니다.";?>
                                        </td>
                                        <td class="td_center depth3">
                                            <input type="hidden" name="pk_id[]" value="<?php echo $list["depth1"][$i]["depth2"][$j]["pk_id"];?>">
                                            <input type="checkbox" name="pk_id_active[]" value="1" checked id="label_<?php echo $list["depth1"][$i]["depth2"][$j]["pk_id"];?>"><label for="label_<?php echo $list["depth1"][$i]["depth2"][$j]["pk_id"];?>"></label>
                                        </td>
                                        <td class="td_center depth3">
                                            <div>
                                                <input type="text" class="basic_input01 datepicker" name="start_date[]" value="<?php echo date("Y-m-d");?>">
                                            </div>
                                        </td>
                                        <td class="td_center depth3">
                                            <div>
                                                <input type="text" class="basic_input01 datepicker" name="end_date[]" value="<?php echo date("Y-m-d", strtotime(" +1 month"));?>">
                                            </div>
                                        </td>
                                    </tr>
                                    <?php }?>
                            <?php }?>
                        </table>
                    </form>
                </div>
                <div class="btn_group2">
                    <input type="button" class="basic_btn02 width10" value="< 이전" onclick="location.href='<?php echo G5_URL;?>/page/mylocation/mylocation_step2.php?id=<?php echo $id;?>'">
                    <input type="submit" class="basic_btn01 width10" value="다음 >" >
                </div>
            </div>
            </form>
        </div>
    </section>
</div>
<script>
    function fnPrice(price){
        var pi = price.replace(/[^0-9]/g, '');
        $("#cmap_construct_price_service").val(pi);
        var han = viewKorean(pi);
        $(".return_price").html(han);
    }

    $(function(){
        $(".datepicker").datepicker({
            showOn: "both", // 버튼과 텍스트 필드 모두 캘린더를 보여준다.
            buttonImage: "<?php echo G5_IMG_URL;?>/ic_calendar.svg", // 버튼
            buttonImageOnly: true, // 버튼에 있는 이미지만 표시한다.
            changeMonth: true, // 월을 바꿀수 있는 셀렉트 박스를 표시한다.
            changeYear: true, // 년을 바꿀 수 있는 셀렉트 박스를 표시한다.
            minDate: '-100y', // 현재날짜로부터 100년이전까지 년을 표시한다.
            nextText: '다음 달', // next 아이콘의 툴팁.
            prevText: '이전 달', // prev 아이콘의 툴팁.
            numberOfMonths: [1,1], // 한번에 얼마나 많은 월을 표시할것인가. [2,3] 일 경우, 2(행) x 3(열) = 6개의 월을 표시한다.
            stepMonths: 3, // next, prev 버튼을 클릭했을때 얼마나 많은 월을 이동하여 표시하는가.
            yearRange: 'c-100:c+10', // 년도 선택 셀렉트박스를 현재 년도에서 이전, 이후로 얼마의 범위를 표시할것인가.
            showButtonPanel: true, // 캘린더 하단에 버튼 패널을 표시한다.
            currentText: '오늘 날짜' , // 오늘 날짜로 이동하는 버튼 패널
            closeText: '닫기',  // 닫기 버튼 패널
            dateFormat: "yy-mm-dd", // 텍스트 필드에 입력되는 날짜 형식.
            showAnim: "slide", //애니메이션을 적용한다.
            showMonthAfterYear: true , // 월, 년순의 셀렉트 박스를 년,월 순으로 바꿔준다.
            dayNamesMin: ['월', '화', '수', '목', '금', '토', '일'], // 요일의 한글 형식.
            monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'] // 월의 한글 형식.
        });

    });
    /*function fnDateUpdate(date){
    }*/
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
