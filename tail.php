<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/tail.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/tail.php');
    return;
}

?>

    <!--</div>
    <div id="aside">
        <?php
/*        //공지사항
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
        // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
        echo latest('notice', 'notice', 4, 13);
        */?>
        <?php /*echo outlogin(); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 */?>
        <?php /*echo poll(); // 설문조사, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 */?>
        <?php /*echo visit(); // 접속자집계, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 */?>
    </div>-->

<!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
<div id="ft" class="<?php if($main){?>mainFt<?php } if($sub=="login"){?> login_ft<?php } if($sub=="sub"){?> sub_ft<?php }?> ">

    <div id="ft_wr">
        <div id="ft_catch"><img src="<?php echo G5_IMG_URL; ?>/ft_logo.png" alt="<?php echo G5_VERSION ?>"> Copyright © 2018 C_MAP. All rights reserved.</div>
        <div id="ft_link">
            <!--<a href="<?php /*echo G5_BBS_URL; */?>/content.php?co_id=company">회사소개</a>-->
            <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=privacy">개인정보처리방침</a>
            <a href="<?php echo G5_BBS_URL; ?>/content.php?co_id=provision">서비스이용약관</a>
            <a href="#">TEL : 000-0000<?php echo $footer["ft_tel"];?></a>
            <a href="#">Email : email@cmap.com<?php echo $footer["ft_email"];?></a>
            <!--<a href="<?php /*echo get_device_change_url(); */?>">모바일버전</a>-->
        </div>
    </div>
    <?php if(!$main) {?>
    <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
        <script>
        
        $(function() {
            $("#top_btn").on("click", function() {
                $("html, body").animate({scrollTop:0}, '500');
                return false;
            });
        });
        </script>
    <?php }?>
</div>

<?php
if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>