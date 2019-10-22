<?php
include_once("../../common.php");
$mb = get_member($mb_id);

if($type!="com"){
    $link = G5_URL."/page/mypage/member_refund";
}else{
    $link = G5_URL."/page/company/member_refund";
}
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
                    <form action="<?php echo $link;?>" name="refundPopup" method="post">
                        <input type="hidden" name="mb_id" id="mb_id" value="<?php echo $mb_id;?>">
                        <input type="text" name="cancel_account" id="cancel_account" class="basic_input01 width100" placeholder="환불계좌 소유주" value="<?php echo $mb["mb_name"];?>">
                        <input type="text" name="cancel_bank_name" id="cancel_bank_name" class="basic_input01 width100" placeholder="환불계좌 은행">
                        <select name="bank_name_sel" id="bank_name_sel" class="basic_input01 width100" onchange="fnBankName(this.value);">
                            <option value="">직접입력</option>
                            <option value='SC제일은행'>SC제일은행</option>
                            <option value='경남은행'>경남은행</option>
                            <option value='광주은행'>광주은행</option>
                            <option value='국민은행'>국민은행</option>
                            <option value='굿모닝신한증권'>굿모닝신한증권</option>
                            <option value='기업은행'>기업은행</option>
                            <option value='농협중앙회'>농협중앙회</option>
                            <option value='농협회원조합'>농협회원조합</option>
                            <option value='대구은행'>대구은행</option>
                            <option value='대신증권'>대신증권</option>
                            <option value='대우증권'>대우증권</option>
                            <option value='동부증권'>동부증권</option>
                            <option value='동양종합금융증권'>동양종합금융증권</option>
                            <option value='메리츠증권'>메리츠증권</option>
                            <option value='미래에셋증권'>미래에셋증권</option>
                            <option value='뱅크오브아메리카(BOA)'>뱅크오브아메리카(BOA)</option>
                            <option value='부국증권'>부국증권</option>
                            <option value='부산은행'>부산은행</option>
                            <option value='산림조합중앙회'>산림조합중앙회</option>
                            <option value='산업은행'>산업은행</option>
                            <option value='삼성증권'>삼성증권</option>
                            <option value='상호신용금고'>상호신용금고</option>
                            <option value='새마을금고'>새마을금고</option>
                            <option value='수출입은행'>수출입은행</option>
                            <option value='수협중앙회'>수협중앙회</option>
                            <option value='신영증권'>신영증권</option>
                            <option value='신한은행'>신한은행</option>
                            <option value='신협중앙회'>신협중앙회</option>
                            <option value='에스케이증권'>에스케이증권</option>
                            <option value='에이치엠씨투자증권'>에이치엠씨투자증권</option>
                            <option value='엔에이치투자증권'>엔에이치투자증권</option>
                            <option value='엘아이지투자증권'>엘아이지투자증권</option>
                            <option value='외환은행'>외환은행</option>
                            <option value='우리은행'>우리은행</option>
                            <option value='우리투자증권'>우리투자증권</option>
                            <option value='우체국'>우체국</option>
                            <option value='유진투자증권'>유진투자증권</option>
                            <option value='전북은행'>전북은행</option>
                            <option value='제주은행'>제주은행</option>
                            <option value='키움증권'>키움증권</option>
                            <option value='하나대투증권'>하나대투증권</option>
                            <option value='하나은행'>하나은행</option>
                            <option value='하이투자증권'>하이투자증권</option>
                            <option value='한국씨티은행'>한국씨티은행</option>
                            <option value='한국투자증권'>한국투자증권</option>
                            <option value='한화증권'>한화증권</option>
                            <option value='현대증권'>현대증권</option>
                            <option value='홍콩상하이은행'>홍콩상하이은행</option>
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