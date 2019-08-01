<?php
include_once("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> <?php echo $title;?></h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con">
        <div style="display:table">
            <div style="display:table-cell;vertical-align:middle;width:30%"><img src="<?php echo G5_IMG_URL;?>/modal_confirm.svg" alt="" style="width: 100%"></div>
            <div style="display:table-cell;vertical-align:middle;width:70%">
                <?php echo $msg;?>
                <?php if($type=="confirm"){?>
                    <div>
                        <br>
                        <input type="text" name="chkText" id="chkText" class="basic_input01 width80" required placeholder="[지금삭제]를 입력해주세요.">
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="<?php if($cancel){echo $cancel; }else{?>fnCloseModal()<?php }?>">
        <input type="button" class="modal_btn01 width30" value="<?php echo $btns;?>" onclick="location.href='<?php echo $link;?>'">
    </div>
</div>
