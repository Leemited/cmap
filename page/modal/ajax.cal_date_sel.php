<?php
include_once ("../../common.php");
//include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
$startYear = date("Y",strtotime(' - 30 year'));
$endYear = date("Y",strtotime(' + 30 year'));
?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 년월 선택</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con cal_sel">
        <select name="yearSel" id="yearSel">
            <?php for($i=$startYear;$i<$endYear;$i++){?>
            <option value="<?php echo $i;?>" <?php if($i==$year){?>selected<?php }?>><?php echo $i;?></option>
            <?php }?>
        </select> 년 /
        <select name="monthSel" id="monthSel">
            <?php for($i=1;$i< 13;$i++){
                $mon = (strlen($i)==1)?"0".$i:$i;
                ?>
                <option value="<?php echo $mon;?>" <?php if($month==$mon){?>selected<?php }?>><?php echo $mon;?></option>
            <?php }?>
        </select> 월
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="fnCloseModal()">
        <input type="button" class="modal_btn01 width30" value="이동하기" onclick="fnScheduleMove();">
    </div>
</div>

<script>
    function fnScheduleMove(){
        var year = $("#yearSel").val();
        var month = $("#monthSel").val();
        if(confirm(year+"년 "+month+"월로 이동하시겠습니까?")) {
            location.href = g5_url + '/page/mypage/schedule?toYear=' + year + '&toMonth=' + month + '&constid=<?php echo $constid;?>';
        }
    }
</script>
