<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "depth_desc_schedule";
include_once (G5_PATH."/_head.php");

/********** 사용자 설정값 **********/
$startYear        = date( "Y" ) ;
$endYear        = date( "Y" ) + 4;
$today = date("d");

/********** 입력값 **********/
$year            = ( $_GET['toYear'] )? $_GET['toYear'] : date( "Y" );
$month            = ( $_GET['toMonth'] )? $_GET['toMonth'] : date( "m" );
$doms            = array( "일", "월", "화", "수", "목", "금", "토" );

/*********** 2주계산 ********/
$week2 = date("Y-m-d", mktime( 0, 0, 0, date("m"),  $today + $data[13] , $startYear ));

/********** 계산값 **********/
$mktime            = mktime( 0, 0, 0, $month, 1, $year );      // 입력된 값으로 년-월-01을 만든다
$days            = date( "t", $mktime );                        // 현재의 year와 month로 현재 달의 일수 구해오기
$startDay        = date( "w", $mktime );                        // 시작요일 알아내기

// 지난달 일수 구하기
$prevDayCount    = date( "t", mktime( 0, 0, 0, $month, 0, $year ) ) - $startDay + 1;

$nowDayCount    = 1;                                            // 이번달 일자 카운팅
$nextDayCount    = 1;                                          // 다음달 일자 카운팅

// 이전, 다음 만들기
$prevYear        = ( $month == 1 )? ( $year - 1 ) : $year;
$prevMonth        = ( $month == 1 )? 12 : ( $month - 1 );
$nextYear        = ( $month == 12 )? ( $year + 1 ) : $year;
$nextMonth        = ( $month == 12 )? 1 : ( $month + 1 );

// 출력행 계산
$setRows = ceil( ( $startDay + $days ) / 7 );

$holiday = sql_fetch("select * from `cmap_holidays` where year = '{$year}'");

//설날 3일
$newyear = explode("~", $holiday["holidays2"]);
$holiday2_1 = $newyear[0];
$holiday2_2 = date("Y-m-d",strtotime(" +1 day",strtotime($newyear[0])));
$holiday2_3 = $newyear[1];
//추석 3일
$thank = explode("~", $holiday["holidays8"]);
$holiday8_1 = $thank[0];
$holiday8_2 = date("Y-m-d",strtotime(" +1 day",strtotime($thank[0])));
$holiday8_3 = $thank[1];

if($current_const["id"] && $id=="" && $type == ""){
    $id = $current_const["const_id"];
}

//스케쥴 가져오기
if($id){
    $where = " and construct_id = '{$id}'";
}else {
//내가 속한 현장 가져오기
    $sql = "select * from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}')!= 0) and status = 0";
    $cres = sql_query($sql);
    while ($row = sql_fetch_array($cres)) {
        $const_id["const_id"][] = $row["id"];
    }
    if (count($const_id) != 0) {
        $constid = implode(",", $const_id["const_id"]);
        $where .= " and construct_id in ({$constid})";
    }
}
$sql = "select * from `cmap_myschedule` where status != -1 {$where} order by id";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    /*$sql = "select * from `cmap_my_construct` where id = '{$row["construct_id"]}'";
    $chkstatus = sql_fetch($sql);
    if($chkstatus["status"] == -1) continue;*/
    $myschedule[$row["schedule_date"]][] = $row;
}
?>
<div class="full-width" style="padding:0 20px">
    <header class="sub">
        <h2>스케쥴</h2>
    </header>
    <section class="cal">
        <div class="info">
            <ul>
                <li class="confirm"> 제출대상 및 적기제출</li>
                <li class="late"> 제출지연</li>
                <li class="memo"> 사용자 메모</li>
            </ul>
        </div>
        <div class="todays">
            <input type="button" value="TODAY" class="basic_btn02 "onclick="location.href=g5_url+'/page/mypage/schedule?toYear<?php echo date("Y");?>&toMonth=<?php echo date("m");?>&id=<?php echo $id;?>'">
        </div>
        <div class="big_month">
            <a class="prev_year" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 1)?($prevYear - 1):$prevYear?>&toMonth=<?php echo $month ?>&id=<?php echo $id;?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_prev.png" alt=""> </a>
            <a class="prev_month" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $prevYear?>&toMonth=<?php echo $prevMonth?>&id=<?php echo $id;?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_prev.png" alt=""> </a>
            <span><?php echo $year;?>. <?php echo (strlen($month)==1)?"0".$month:$month;?></span>
            <a class="next_month" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo $nextYear?>&toMonth=<?php echo $nextMonth?>&id=<?php echo $id;?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_next.png" alt=""> </a>
            <a class="next_year" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule?toYear=<?php echo ($month != 12)?($nextYear + 1):$nextYear?>&toMonth=<?php echo $month?>&id=<?php echo $id;?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_next.png" alt=""> </a>
        </div>
        <table class="schedule_tbl">
            <tr>	<!-- 요일 -->
                <?php for( $i = 0; $i < count( $doms ); $i++ ) { ?>
                    <th>
                        <?php
                        switch($i){
                            case 0 : echo "<span class='sun'>SUN</span>";break;
                            case 1 : echo "<span>MON</span>";break;
                            case 2 : echo "<span>TUE</span>";break;
                            case 3 : echo "<span>WED</span>";break;
                            case 4 : echo "<span>THU</span>";break;
                            case 5 : echo "<span>FRI</span>";break;
                            case 6 : echo "<span class='sat'>SAT</span>";break;
                        }
                        ?>
                    </th>
                <?php } ?>
            </tr>
            <?php for( $rows = 0; $rows < $setRows; $rows++ ) { ?> <!-- 주차를 나누는 for 문 -->
                <tr>
                    <?php for( $cols = 0; $cols < 7; $cols++ )  {
                        // 셀 인덱스 만들자
                        $cellIndex    = ( 7 * $rows ) + $cols;
                        // 이번달이라면
                        if ( $startDay <= $cellIndex && $nowDayCount <= $days ) {
                            if(strlen($nowDayCount)==1){
                                $ndate = "0".$nowDayCount;
                            }else{
                                $ndate = $nowDayCount;
                            }
                            if(strlen($month)==1){
                                $mon = "0".$month;
                            }else{
                                $mon = $month;
                            }
                            $key = $year."-".$mon."-".$ndate;

                            ?>
                            <td onclick="fnScheduleList('<?php echo $key;?>','<?php echo $id;?>')" class="days <?php echo "d_".$key;?> <?php if($cols==6){ echo "sat_tbl"; } ?>" <?php if( date("d") == $nowDayCount && date("m") == $month) {?>id="today"<?php }?>> <!-- 일주일 내의 일을 나누는 for 문 -->
                                <?php if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCount, $year ) ) == 6 ) { 	// 토요일
                                    if( date("d") == $nowDayCount  && date("m") == $month ) { ?>
                                        <div><p class="calendar_date sat today" style="color:orange;font-weight:bold"><b><?php echo $nowDayCount++?></b></p></div>
                                    <?php	} else {	?>
                                        <div><p class="calendar_date sat"><?php echo $nowDayCount++?></p></div>
                                    <?php	}
                                } else if ( date( "w", mktime( 0, 0, 0, $month, $nowDayCount, $year ) ) == 0 ) { 	// 일요일
                                    if( date("d") == $nowDayCount && date("m") == $month) { ?>
                                        <div><p class="calendar_date sun today" style="color:orange;font-weight:bold"><b><?php echo $nowDayCount++?></b></p></div>
                                    <?php	} else {	?>
                                        <div><p class="calendar_date sun"><?php echo $nowDayCount++?></p></div>
                                    <?php	}
                                } else { 	// 평일
                                    if( strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday8_3) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays1"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays3"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays4"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays5"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays6"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays7"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays9"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays10"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday["holidays11"]) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_1) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_2) || strtotime(date( "Y-m-d", mktime( 0, 0, 0, $month, $nowDayCount, $year ))) == strtotime($holiday2_3)){//공휴일?>
                                        <div><p class="calendar_date sun today" ><b><?php echo $nowDayCount++?></b></p></div>
                                    <?php }else {
                                        if (date("d") == $nowDayCount && date("m") == $month) { ?>
                                            <div class="calendar_date today"><p style="color:orange; font-weight:bold"><?php echo $nowDayCount++ ?></p>
                                            </div>
                                        <?php } else { ?>
                                            <div class="calendar_date"><p><?php echo $nowDayCount++ ?></p></div>
                                        <?php }
                                    }
                                }

                                if(count($myschedule[$key]) > 0){?>
                                    <ul>
                                        <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                            //todo : 적기 제출 상태 체크 및 단순 메모 체크
                                            if($item>5){continue;}
                                            ?>
                                            <li class="" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>"><?php echo $myschedule[$key][$item]["schedule_name"];?></li>
                                        <?php }?>
                                    </ul>
                                <?php }
                                if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                ?>
                            </td>
                            <?php	// 이전달이라면
                        } else if ( $cellIndex < $startDay ) {
                                $presmon = (strlen($prevMonth)==1)?"0".$prevMonth:$prevMonth;
                                $key = $year."-".$presmon."-".$prevDayCount;
                            ?>
                            <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?> onclick="fnScheduleList('<?php echo $key;?>','<?php echo $id;?>')">
                                <div><p class="calendar_date other_m"><?php echo $prevDayCount++?></p></div>
                                <?php
                                if(count($myschedule[$key]) > 0){?>
                                    <ul>
                                        <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                            //todo : 적기 제출 상태 체크 및 단순 메모 체크
                                            if($item>5){continue;}
                                            ?>
                                            <li class="" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>"><?php echo $myschedule[$key][$item]["schedule_name"];?></li>
                                        <?php }?>
                                    </ul>
                                <?php }
                                if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                ?>
                            </td>
                            <?php 	// 다음달 이라면
                        } else if ( $cellIndex >= $days ) {
                            $nextmon = (strlen($nextMonth)==1)?"0".$nextMonth:$nextMonth;
                            $nextday = (strlen($nextDayCount)==1)?"0".$nextDayCount:$nextDayCount;
                            $key = $year."-".$nextmon."-".$nextday;
                            ?>
                            <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?> onclick="fnScheduleList('<?php echo $key;?>','<?php echo $id;?>')">
                                <div><p class="calendar_date other_m"><?php echo $nextDayCount++?></p></div>
                                <?php
                                if(count($myschedule[$key]) > 0){?>
                                    <ul>
                                        <?php for($item = 0; $item <count($myschedule[$key]);$item++){
                                            //todo : 적기 제출 상태 체크 및 단순 메모 체크
                                            if($item>5){continue;}
                                            ?>
                                            <li class="" id="cal_<?php echo $myschedule[$key][$item]["id"];?>" title="<?php echo $myschedule[$key][$item]["schedule_name"];?>"><?php echo $myschedule[$key][$item]["schedule_name"];?></li>
                                        <?php }?>
                                    </ul>
                                <?php }
                                if(count($myschedule[$key]) > 5){ echo "<span class='schedule_mores'>...</span>";}
                                ?>
                            </td>
                        <?php }
                    }
                    ?>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="cal_data">
        <div>
            <!-- todo : 해당 탭 구현 -->
            <ul class="tab">
                <li class="active" >일정관리</li>
                <li>제출지연</li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="contruct_sel">
            <!-- todo : 현장선택시 해당 스케쥴만 가져오기 or 최초 진입시 기본값 설정? -->
            <select name="cons_id" id="cons_id" onchange="fnScheduleConst(this.value)">
                <option value="">현장선택</option>
                <?php for($i=0;$i<count($mycont);$i++){?>
                    <option value="<?php echo $mycont[$i]["id"];?>" <?php if($id==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                <?php }?>
            </select>
        </div>
        <div class="tab1">
            <div class="list_con">
                <input type="hidden" id="del_id" value="">
                <h2>일정목록 <span class="del" onclick="fnScheduleDel();"></span></h2 >
                <ul class="schedule_list">

                </ul>
            </div>
            <div class="detail_list">
                <h2>상세목록 <span class="close" onclick="$('.detail_list').hide();"></span></h2>
                <div class="lists">

                </div>
            </div>
        </div>
        <div class="tab2">

        </div>
    </section>
</div>
<script>
    function fnScheduleList(key,id){
        if(!$(".d_"+key).hasClass("selected")) {
            $(".d_" + key).addClass("selected");
            $(".days").not($(".d_" + key)).removeClass("selected");
        }

        var mb_id = '<?php echo $member["mb_id"];?>';
        $.ajax({
            url:g5_url+'/page/ajax/ajax.schedule_list.php',
            method:"post",
            data:{date:key,mb_id:mb_id,id:id}
        }).done(function(data){
            $("#del_id").val('');
            $(".schedule_list").html('');
            $(".schedule_list").append(data);
        });
    }

    function fnScheduleConst(id){
        location.href=g5_url+'/page/mypage/schedule?type=schedule&id='+id+"&toYear=<?php echo $year;?>&toMonth=<?php echo $month;?>";
    }

    function fnScheduleConUp(){
        var con = $("#schedule_con").val();
        var id = $("#edit_id").val();
        $.ajax({
            url:g5_url+"/page/ajax/ajax.schedule_content_up.php",
            method:"post",
            data:{id:id,con:con}
        }).done(function(data){
            //$("#debug").html("저장완료");
            if(data=="1") {
                alert("저장/등록 완료");
            }else{
                alert("저장 정보 오류");
            }
        });
    }

    function fnScheduleDel(){
        var id = $("#del_id").val();
        if(id==''){
            alert("삭제할 일정을 선택해 주세요.");
            return false;
        }
        $.ajax({
            url:g5_url+"/page/ajax/ajax.schedule_delete.php",
            method:"post",
            data:{id:id}
        }).done(function(data){
           $("#schedule_id_"+id).remove();
            $("#cal_"+id).remove();
        });
    }

    $(function(){
        fnScheduleList("<?php echo date("Y-m-d");?>");

        $(document).scroll(function(){
           var top = $(this).scrollTop();
           if(top > 350){
               $(".cal_data").addClass("cal_top");
           }else{
               $(".cal_data").removeClass("cal_top");
           }
        });
        $(".tab2").hide();

        $(".tab li").click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".tab li").not($(this)).removeClass("active");
            }
            if($(this).html()=="제출지연"){
                $(".tab1").hide();
                $(".tab2").show();
            }else{
                $(".tab1").show();
                $(".tab2").hide();
            }
        });
    });
</script>
<?php
include_once (G5_PATH."/_tail.php");