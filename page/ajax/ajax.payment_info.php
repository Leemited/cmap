<?php
include_once("../../common.php");
?>
<style>
    .confirm_con h2{background-color:#00aced;color:#fff;padding:10px}
    .confirm_con h2:before{content:"";background-color:#fff;margin-right:10px;width:5px;height:20px;display:inline-block;vertical-align:middle;}
</style>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> <?php echo $title;?></h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con">
        <div style="display:table">
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
        <input type="button" class="modal_btn01 width30" value="<?php echo $btns;?>" onclick="document.forms.submit();">
    </div>
</div>
<form action="<?php echo $link;?>" method="post" id="forms" name="forms">
    <input type="hidden" name="payments" value="<?php echo $payments;?>">
    <input type="hidden" name="od_type" value="<?php echo $od_type;?>">
</form>
