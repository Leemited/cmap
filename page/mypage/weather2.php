<?php
include_once ("../../common.php");

include_once (G5_PATH."/_head.php");
include_once (G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$year   = ( $_GET['toYear'] )? $_GET['toYear'] : date( "Y" );
$month  = ( $_GET['toMonth'] )? $_GET['toMonth'] : date( "m" );
?>

<style>
    .weather_header{
        width: 100%;
        position: relative;
        top: 100px;
    }
    .weather_title{
        width: 50%;
        float: left;
    }
    .weather_search_condition{
        width: 50%;
        float: right;
    }
    .weather_result{
        width: 100%;
    }
</style>

<div class="weather_header">
    <div class="weather_title">
        <div>
            <header class="sub"><h2>천후표</h2></header>
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
    </div>
    <div class="weather_search_condition">
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
                <input type="button" class="basic_btn02" value="검색">
            </form>
            <div class="work_msg_btns">
                <input type="button" class="basic_btn03" value="저장" onclick="fnWriteMessage('')">
            </div>
        </div>
        <div>
            <div class="search" style="position: relative;">
                        
            </div>
        </div>
        <div></div>
    </div>
</div>
<div class="weather_result">
</div>

<script>
</script>

<?php
include_once (G5_PATH."/_tail.php");
?>