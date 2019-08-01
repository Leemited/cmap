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
</div>

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
<?php if($is_member){?>
<?php if(!$main && $sub != "login" && $mypage != true){?>
    <?php if($setquick["quick"]==1){
        ?>
        <div class="quick">
            <div class="quick_container">
                <ul>
                    <?php for($i=0;$i<count($quickmenu);$i++){
                        echo $quickmenu[$i];
                    }?>
                </ul>
            </div>
            <div class="quick_btns" onclick="fnQuickView()">
                <img src="<?php echo G5_IMG_URL;?>/quick_btns.png" alt="">
            </div>
            <span></span>
        </div>
    <?php } ?>
<?php } ?>
<?php } ?>
<!-- 하단 시작 { -->
<div id="ft" class="company_ft">

    <div id="ft_wr">
        <div id="ft_catch"><img src="<?php echo G5_IMG_URL; ?>/ft_logo.svg" alt="<?php echo G5_VERSION ?>"> Copyright © 2018 C_MAP. All rights reserved.</div>
        <div id="ft_link">
            <!--<a href="<?php /*echo G5_BBS_URL; */?>/content.php?co_id=company">회사소개</a>-->
            <a href="#">건설기술혁신처</a>
            <a href="#">대표자 : 이지연</a>
            <a href="#">사업자번호 : 398-18-00805</a>
            <a href="#">통신판매업신고 : 제2019-경남진주-0136호 </a>
            <a href="javascript:footerModal('<?php echo G5_URL; ?>/page/ajax/ajax.content.php','privacy')"  >개인정보처리방침</a>
            <a href="javascript:footerModal('<?php echo G5_URL; ?>/page/ajax/ajax.content.php','provision')">서비스이용약관</a>
            <a href="#">TEL : 055-763-7222</a>
            <a href="#">주소 : 52852 경상남도 진주시 충의로 20-16, 3층 310호</a>
            <a href="mailto:aplatoxin80@naver.com">Email : aplatoxin80@naver.com</a>
            <!--<a href="<?php /*echo get_device_change_url(); */?>">모바일버전</a>-->
        </div>
    </div>

<!--    <?php /*if(!$main) {*/?>
    <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
    <script>

        $(function() {
            $("#top_btn").on("click", function() {
                $("html, body").animate({scrollTop:0}, '500');
                return false;
            });
        });
    </script>
    --><?php /*}*/?>
</div>
<div class="ft_content">
    <div class="modal"></div>
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