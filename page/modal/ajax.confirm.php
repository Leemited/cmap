<?php
include_once ("../../common.php");

$sql = "select count(*) as cnt from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or INSTR(members,'{$member["mb_id"]}') > 0) and status = 0";
$chkcnt = sql_fetch($sql);
if($chkcnt["cnt"]>=10){?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 현장 개설하기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con">
        <img src="<?php echo G5_IMG_URL;?>/modal_confirm.svg" alt="">현장 개설 갯수를 초과하였습니다.
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="확인" onclick="fnCloseModal()">
    </div>
</div>
<?php
}else{
?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 현장 개설하기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con">
        <img src="<?php echo G5_IMG_URL;?>/modal_confirm.svg" alt=""> 현장을 개설하시겠습니까?
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="fnCloseModal()">
        <input type="button" class="modal_btn01 width30" value="개설하기" onclick="location.href=g5_url+'/page/mylocation/mylocation_edit?type=insert'">
    </div>
</div>
<?php }?>