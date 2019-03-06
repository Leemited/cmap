<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
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


?>
<div class="width-fixed board-width">
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
        <div class="big_month">
            <a class="prev_year" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule.php?toYear=<?php echo ($month != 1)?($prevYear - 1):$prevYear?>&toMonth=<?php echo $month ?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_year_prev.png" alt=""> </a>
            <a class="prev_month" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule.php?toYear=<?php echo $prevYear?>&toMonth=<?php echo $prevMonth?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_prev.png" alt=""> </a>
            <span><?php echo $year;?>. <?php echo $month;?></span>
            <a class="next_month" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule.php?toYear=<?php echo $nextYear?>&toMonth=<?php echo $nextMonth?>'">
                <img src="<?php echo G5_IMG_URL?>/cal_arrow_m_next.png" alt=""> </a>
            <a class="next_year" href="javascript:location.href='<?php echo G5_URL?>/page/mypage/schedule.php?toYear=<?php echo ($month != 12)?($nextYear + 1):$nextYear?>&toMonth=<?php echo $month?>'">
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
                        if ( $startDay <= $cellIndex && $nowDayCount <= $days ) { ?>
                            <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?> <?php if( date("d") == $nowDayCount && date("m") == $month) {?>id="today"<?php }?>> <!-- 일주일 내의 일을 나누는 for 문 -->
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
                                    if( date("d") == $nowDayCount && date("m") == $month ) {	?>
                                        <div class="calendar_date today"><p style="color:orange; font-weight:bold"><?php echo $nowDayCount++?></p></div>
                                    <?php	}else {	?>
                                        <div class="calendar_date"><p><?php echo $nowDayCount++?></p></div>
                                    <?php	}
                                }
                                ?>
                            </td>
                            <?php	// 이전달이라면
                        } else if ( $cellIndex < $startDay ) { ?>
                            <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?>><div><p class="calendar_date other_m"><?php echo $prevDayCount++?></p></div></td>
                            <?php 	// 다음달 이라면
                        } else if ( $cellIndex >= $days ) { ?>
                            <td <?php if($cols==6){ echo" class='sat_tbl'"; } ?>><div><p class="calendar_date other_m"><?php echo $nextDayCount++?></p></div></td>
                        <?php }
                    }
                    ?>
                </tr>
            <?php } ?>
        </table>
    </section>
    <section class="cal_data">
        <div>
            <ul>
                <li>일정관리</li>
                <li>제출지연</li>
            </ul>
        </div>
        <div class="contruct_sel">
            <select name="" id=""></select>
        </div>
        <div>
            <h2>일정목록</h2>
            <ul>

            </ul>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/_tail.php");