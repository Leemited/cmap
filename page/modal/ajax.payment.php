<?php
include_once ("../../common.php");
?>
<div class="message" id="payment_view">
    <div class="msg_title">
        <h2>이용권 구매</h2>
        <div class="close" onclick="fnEtcClose()"></div>
    </div>
<!--    <div class="modal_title">
        <h2><i></i> 이용권 구매</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php /*echo G5_IMG_URL;*/?>/close_icon.svg" alt="">
        </div>
    </div>-->
    <div class="modal_content">
        <div class="incon">
            <h3><i class="fa fa-credit-card"></i> 구매안내</h3>
            <ul>
                <li>- 본 이용권은 홈페이지 이용을 위한 필수 조건입니다.</li>
                <!--<li>- 최초 가입 후 3일, 현장 등록 1주일은 무료로 이용 가능합니다.</li>
                <li>- 이후 해당 이용권을 구매후 이용 가능합니다.</li>-->
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
                    <td>비용(원)</td>
                    <td style="text-decoration: line-through;text-decoration-color: red">143,000<!--<br><span>(99,000/월)</span>--></td>
                    <td style="text-decoration: line-through;text-decoration-color: red">744,000<!--<br><span>(88,000/월)</span>--></td>
                    <td style="text-decoration: line-through;text-decoration-color: red">1,320,000<!--<br><span>(77,000/월)</span>--></td>
                </tr>
                <tr>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">특별혜택<br>(30%)</td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">99,000<!--<br><span>(99,000/월)</span>--></td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">528,000<!--<br><span>(88,000/월)</span>--></td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">924,000<!--<br><span>(77,000/월)</span>--></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="1개월 구매" onclick="selPayType(99000,1,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="6개월 구매" onclick="selPayType(528000,2,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="12개월 구매" onclick="selPayType(924000,3,'<?php echo $member["mb_level"];?>')"></td>
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
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">비용</td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">473,000</td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">2,750,000</td>
                    <td style="background-color:#48BFFF;color:#fff;font-weight: bold">4,620,000</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="1개월 구매" onclick="selPayType(4730000,4,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="6개월 구매" onclick="selPayType(2750000,5,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="12개월 구매" onclick="selPayType(4620000,6,'<?php echo $member["mb_level"];?>')"></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<form action="<?php echo G5_URL;?>/page/mypage/member_payment" name="order_form" method="post">
    <input type="hidden" name="amount" id="amount" value="">
    <input type="hidden" name="merchant_uid" id="" value="">
</form>
