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
            <table>
                <tr>
                    <th>구분</th>
                    <th>1개월</th>
                    <th>6개월</th>
                    <th>12개월</th>
                </tr>
                <tr>
                    <td>비용</td>
                    <td>77,000</td>
                    <td>396,000</td>
                    <td>660,000</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="1개월 구매"></td>
                    <td><input type="button" value="6개월 구매"></td>
                    <td><input type="button" value="12개월 구매"></td>
                </tr>
            </table>
        </div>
    </div>
    <!--<div class="modal_title">
        <h2><i></i> PM(관리자용) 구매</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php /*echo G5_IMG_URL;*/?>/ic_close.svg" alt="닫기">
        </div>
    </div>
    <div class="modal_content">
        <div class="incon">
            <h3>구매안내</h3>

        </div>
    </div>-->
</div>
<script>
    function fnApply(theme,cate_theme) {
        location.href='<?php echo G5_URL?>/page/mypage/mytheme_update?theme='+theme+"&cate="+cate_theme;
    }
</script>
