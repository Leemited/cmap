<?php
include_once ("../../common.php");
?>
<div class="modal_in" id="payment_view">
    <div class="modal_title">
        <h2><i></i> 이용권 구매</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="modal_content">
        <div class="incon">
            <h3><i class="fa fa-credit-card"></i> 구매안내</h3>
            <ul>
                <li>- 본 이용권은 홈페이지 이용을 위한 필수 조건입니다.</li>
                <li>- 최초 가입 후 3일, 현장 등록 1주일은 무료로 이용 가능합니다.</li>
                <li>- 이후 해당 이용권을 구매후 이용 가능합니다.</li>
            </ul>
            <div class="payment_title">
                <h3><i class="fa fa-user"></i>개인 사용자</h3>
            </div>
            <table>
                <tr>
                    <th>구분</th>
                    <th>1개월</th>
                    <th>6개월</th>
                    <th>12개월</th>
                </tr>
                <tr>
                    <td>비용</td>
                    <td>90,000<br><span>(90,000/월)</span></td>
                    <td>480,000<br><span>(80,000/월)</span></td>
                    <td>840,000<br><span>(70,000/월)</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="1개월 구매" onclick="selPayType(90000,1,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="6개월 구매" onclick="selPayType(480000,2,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="12개월 구매" onclick="selPayType(840000,3,'<?php echo $member["mb_level"];?>')"></td>
                </tr>
            </table>

            <div class="payment_title">
                <h3><i class="fa fa-group"></i>기업 또는 관리자(PM MODE)</h3>
            </div>
            <table>
                <tr>
                    <th>구분</th>
                    <th>1개월</th>
                    <th>6개월</th>
                    <th>12개월</th>
                </tr>
                <tr>
                    <td>비용</td>
                    <td>430,000</td>
                    <td>2,500,000</td>
                    <td>4,200,000</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="1개월 구매" onclick="selPayType(430000,4,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="6개월 구매" onclick="selPayType(2500000,5,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="12개월 구매" onclick="selPayType(4200000,6,'<?php echo $member["mb_level"];?>')"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<form action="<?php echo G5_URL;?>/page/mypage/member_payment" name="order_form" method="post">
    <input type="hidden" name="amount" id="amount" value="">
    <input type="hidden" name="merchant_uid" id="" value="">
</form>
