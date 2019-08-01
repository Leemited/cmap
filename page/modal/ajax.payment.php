<?php
include_once ("../../common.php");
?>
<div class="message" id="payment_view" style="">
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
    <div class="payment_pop_title">
        <h2>CONSTRUCTION MANAGEMENT MAP MEMBERSHIP</h2>
    </div>
    <div class="modal_content" style="padding:20px;">
        <div class="incon">
            <!--<h3><i class="fa fa-credit-card"></i> 구매안내</h3>
            <ul>
                <li>- 본 이용권은 홈페이지 이용을 위한 필수 조건입니다.</li>
                <!--<li>- 최초 가입 후 3일, 현장 등록 1주일은 무료로 이용 가능합니다.</li>
                <li>- 이후 해당 이용권을 구매후 이용 가능합니다.</li>--
            </ul>-->
            <div class="payment_title">
                <h3><i class="fa fa-user"></i>개인 사용자</h3>
                <span>금액단위 : 원 VAT 포함</span>
            </div>
            <table>
                <colgroup>
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <tr>
                    <th>구분</th>
                    <th>1개월</th>
                    <th>6개월</th>
                    <th>12개월</th>
                </tr>
                <tr>
                    <td rowspan="3" style="background-color:#0070c0;color:#fff;font-weight: bold">CM MODE<br><span style="color:#ffff00;font-size:14px">특별혜택 (30%)</span></td>
                    <td style="text-decoration: line-through;text-decoration-color: red">143,000<!--<br><span>(99,000/월)</span>--></td>
                    <td style="text-decoration: line-through;text-decoration-color: red">744,000<!--<br><span>(88,000/월)</span>--></td>
                    <td style="text-decoration: line-through;text-decoration-color: red">1,320,000<!--<br><span>(77,000/월)</span>--></td>
                </tr>
                <tr>
                    <td style="font-weight: bold">99,000<!--<br><span>(99,000/월)</span>--></td>
                    <td style="font-weight: bold">528,000<!--<br><span>(88,000/월)</span>--></td>
                    <td style="font-weight: bold">924,000<!--<br><span>(77,000/월)</span>--></td>
                </tr>
                <tr>
                    <td><input type="button" value="신청하기" onclick="selPayType(99000,1,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="신청하기" onclick="selPayType(528000,2,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="신청하기" onclick="selPayType(924000,3,'<?php echo $member["mb_level"];?>')"></td>
                </tr>
            </table>

            <div class="payment_title">
                <h3><i class="fa fa-group"></i>기업 또는 관리자(PM MODE)</h3>
                <span>금액단위 : 원 VAT 포함</span>
            </div>
            <table>
                <colgroup>
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <tr>
                    <th>구분</th>
                    <th>1개월</th>
                    <th>6개월</th>
                    <th>12개월</th>
                </tr>
                <tr>
                    <td style="background-color:#0070c0;color:#fff;font-weight: bold" rowspan="2">PM MODE</td>
                    <td style="font-weight: bold">473,000</td>
                    <td style="font-weight: bold">2,750,000</td>
                    <td style="font-weight: bold">4,620,000</td>
                </tr>
                <tr>
                    <td><input type="button" value="신청하기" onclick="selPayType(473000,4,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="신청하기" onclick="selPayType(2750000,5,'<?php echo $member["mb_level"];?>')"></td>
                    <td><input type="button" value="신청하기" onclick="selPayType(4620000,6,'<?php echo $member["mb_level"];?>')"></td>
                </tr>
            </table>
            <div class="payment_info">
                <ul>
                    <li>건설관리지도 정책에 따라 결제는 필수입니다.</li>
                    <li>상기 요금제는 1계정 기준입니다.(중복 로그인 불가)</li>
                    <li>위 결제금액은 부가세(VAT)가 포함된 가격입니다.</li>
                    <li>개인사용자(CM MODE)와 기업관리자(PM MODE)는 사용 기능이 다르므로<br>
                       ‘서비스이용약관’을 반드시 확인하시고 결제를 진행하시기 바랍니다.</li>
                    <li>1개월은 30일 / 6개월은 180일 / 1년은 365일 기준입니다.</li>
                    <li>CM MODE(개인사용자)는 최대 10개까지 현장관리를 할 수 있습니다.</li>
                </ul>
            </div>
            <div class="payment_btns">
                <input type="button" value="개인정보처리방침" onclick="footerModal('<?php echo G5_URL; ?>/page/ajax/ajax.content.php','privacy')">
                <input type="button" value="서비스이용약관" onclick="footerModal('<?php echo G5_URL; ?>/page/ajax/ajax.content.php','provision')">
                <input type="button" value="1:1문의하기" onclick="location.href=g5_url+'/page/board/inquiry'">
            </div>
        </div>
    </div>
</div>
<form action="<?php echo G5_URL;?>/page/mypage/member_payment" name="order_form" method="post">
    <input type="hidden" name="amount" id="amount" value="">
    <input type="hidden" name="merchant_uid" id="" value="">
</form>
