<?php
include_once ("../../common.php");
$payments = explode(",",$payments);
for($i=0;$i<count($payments);$i++){
    $data = explode("``",$payments[$i]);
    $price += $data[3];
    $mb_ids_price[] = $data[3];
    $mb_ids[] = $data[1];
    $mb_ids_month[] = $data[2];
}
$mbs = implode(",",$mb_ids);
$months = implode(",",$mb_ids_month);
$prices = implode(",",$mb_ids_price);
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
            <li onclick="memberPaymentComAll('card','<?php echo $member["mb_name"];?>','<?php echo $member["mb_hp"];?>','<?php echo $member["mb_email"];?>','<?php echo $mbs;?>','<?php echo $member["mb_id"];?>','<?php echo $price;?>','<?php echo $months;?>','<?php echo $prices;?>','<?php echo $type;?>')"><img src="<?php echo G5_IMG_URL;?>/ic_order_card.svg" alt="">카드</li>
            <li onclick="memberPaymentComAll('trans','<?php echo $member["mb_name"];?>','<?php echo $member["mb_hp"];?>','<?php echo $member["mb_email"];?>','<?php echo $mbs;?>','<?php echo $member["mb_id"];?>','<?php echo $price;?>','<?php echo $months;?>','<?php echo $prices;?>','<?php echo $type;?>')"><img src="<?php echo G5_IMG_URL;?>/ic_order_bank.svg" alt="">계좌이체</li>
            <!--<li onclick="memberPayment('<?php /*echo $amount;*/?>','<?php /*echo $payment_type;*/?>',' vbank','<?php /*echo $mb["mb_name"];*/?>','<?php /*echo $mb["mb_hp"];*/?>','<?php /*echo $mb["mb_email"];*/?>','<?php /*echo $mb["mb_id"];*/?>')"><img src="<?php /*echo G5_IMG_URL;*/?>/ic_order_cyber.svg" alt="">가상계좌</li>-->
        </ul>
    </div>
</div>

