<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 결제방식 선택</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="modal_content">
        <ul class="sel_order">
            <li onclick="memberPayment('<?php echo $amount;?>','<?php echo $payment_type;?>','card','<?php echo $member["mb_name"];?>','<?php echo $member["mb_hp"];?>','<?php echo $member["mb_email"];?>','<?php echo $member["mb_id"];?>')"><img src="<?php echo G5_IMG_URL;?>/ic_order_card.svg" alt="">카드</li>
            <li onclick="memberPayment('<?php echo $amount;?>','<?php echo $payment_type;?>','trans','<?php echo $member["mb_name"];?>','<?php echo $member["mb_hp"];?>','<?php echo $member["mb_email"];?>','<?php echo $member["mb_id"];?>')"><img src="<?php echo G5_IMG_URL;?>/ic_order_bank.svg" alt="">계좌이체</li>
            <!--<li onclick="memberPayment('<?php /*echo $amount;*/?>','<?php /*echo $payment_type;*/?>',' vbank','<?php /*echo $member["mb_name"];*/?>','<?php /*echo $member["mb_hp"];*/?>','<?php /*echo $member["mb_email"];*/?>','<?php /*echo $member["mb_id"];*/?>')"><img src="<?php /*echo G5_IMG_URL;*/?>/ic_order_cyber.svg" alt="">가상계좌</li>-->
        </ul>
    </div>
</div>

