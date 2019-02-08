<?php
include_once('./_common.php');

$main = true;

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.php');
?>
<!-- 로그인 -->
<div class="login">
    <div class="login_btns">
    <?php if(!$is_member){?>
        <img src="<?php echo G5_IMG_URL?>/main_login_btn.png" alt="로그인" onclick="location.href='<?php echo G5_BBS_URL?>/login.php'">
    <?php }else{?>
        <img src="<?php echo G5_IMG_URL?>/mypage_btn.png" alt="로그인">
    <?php }?>
        <div class="my_profile">

        </div>
    </div>
</div>

<script>
    $(".login_btns").click(function(){

    });
</script>
<?php
include_once(G5_PATH.'/tail.php');
?>