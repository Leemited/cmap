<?php
include_once("../../common.php");
$mb = get_member($mb_id);
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
                <div>
                    <form action="<?php echo G5_URL;?>/page/mypage/member_refund" name="refundPopup" method="post">
                        <input type="hidden" name="mb_id" id="mb_id" value="<?php echo $mb_id;?>">
                        <input type="text" name="cancel_account" id="cancel_account" class="basic_input01 width100" placeholder="환불계좌 소유주" value="<?php echo $mb["mb_name"];?>">
                        <input type="text" name="cancel_bank_name" id="cancel_bank_name" class="basic_input01 width100" placeholder="환불계좌 은행">
                        <select name="bank_name_sel" id="bank_name_sel" class="basic_input01 width100" onchange="fnBankName(this.value);">
                            <option value="">직접입력</option>
                            <option value="국민은행">국민은행</option>
                        </select>
                        <input type="text" name="cancel_bank_number" id="cancel_bank_number" onkeyup="number_only(this);" class="basic_input01 width100" placeholder="'-'를 제외한 계좌번호">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="<?php if($cancel){echo $cancel; }else{?>fnCloseModal()<?php }?>">
        <input type="button" class="modal_btn01 width30" value="<?php echo $btns;?>" onclick="return fnRefund()">
    </div>
</div>
<script>
    function fnBankName(bank){
        if(bank==""){
            $("#cancel_bank_name").show();
        }else{
            $("#cancel_bank_name").hide();
        }
        $("#cancel_bank_name").val(bank);
    }
    function fnRefund(){
        if($("#mb_id").val()==""){
            alert("회원정보가 없습니다.");
            return false;
        }
        if($("#cancel_account").val()==""){
            alert("환불계좌 소유주를 입력해주세요.");
            return false;
        }
        if($("#cancel_bank_name").val()==""){
            alert("환불계좌 은행을 선택/입력 해주세요.");
            return false;
        }

        if($("#cancel_bank_number").val()==""){
            alert("환불계좌 번호를 입력해주세요.");
            return false;
        }

        document.refundPopup.submit();
    }
</script>