<?php
include_once ("../../common.php");
/*if($member["mb_auth"]==false){
    alert("무료 이용기간이 만료 되었거나,\\r맴버쉽 기간이 만료 되었습니다. \\n맴버쉽 구매후 이용바랍니다.",G5_URL);
}*/
$sub = "sub";
$bbody = "board";
$mypage = false;
$menu_id = "";
include_once (G5_PATH."/_head.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

/********** 사용자 설정값 **********/
$startYear        = date( "Y" ) ;
$endYear        = date( "Y" ) + 4;
$today = date("d");

/********** 입력값 **********/
$year            = ( $_GET['toYear'] )? $_GET['toYear'] : date( "Y" );
$month            = ( $_GET['toMonth'] )? $_GET['toMonth'] : date( "m" );
$doms            = array( "일", "월", "화", "수", "목", "금", "토" );


if($const_id){
    $where .= " and const_id = '{$const_id}' ";
}else{
    $sql = "select * from `cmap_my_construct` where (mb_id = '{$member['mb_id']}' or instr(members,'{$member["mb_id"]}')) and status = 0 order by id desc";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $myconsts[] = $row["id"];
    }
    $inconst = implode(",",$myconsts);
    $where .= " and const_id in ({$inconst})";
}


$total=sql_fetch("select count(*) as cnt from `weather` where 1 {$where} GROUP by insert_date desc");
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select *,MIN(TMN)as TMN , MAX(TMX)as TMX from `weather` where 1  {$where} group by insert_date order by insert_date desc limit {$start}, {$rows};";
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
        <form action="" method="get">
            <select name="const_id" id="cons_id" class="basic_input01" onchange="fnChangeConst2('<?php echo $member["mb_id"];?>',this.value)">
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
            <h2>천후표</h2>
        </header>
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
                    <th>일자</th>
                    <th>지역</th>
                    <th>현장</th>
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
                    $read_mb_id = explode(",",$worklist[$i]["read_mb_id"]);
                    foreach($read_mb_id as $rmb){
                        $mb2[] = get_member($rmb);
                    }
                    ?>
                    <tr>
                        <td class="td_center"></td>
                        <td class="td_center"></td>
                        <td class="td_center"></td>
                        <td class="td_center"></td>
                        <td onclick="fnWriteMessage('<?php echo $worklist[$i]["id"];?>')"></td>
                        <td class="td_center"></td>
                        <td class="td_center"></td>
                        <td class="td_center"></td>
                    </tr>
                    <?php
                    unset($mb2);
                }?>
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

    </script>
<?php
include_once (G5_PATH."/_tail.php");
?>