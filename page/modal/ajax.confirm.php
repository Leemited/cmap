<?php
include_once ("../../common.php");

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
        <input type="button" class="modal_btn01 width30" value="개설하기" onclick="location.href=g5_url+'/page/mylocation/mylocation_step1.php'">
    </div>
</div>
