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
<div class="owl-carousel" id="main" style="z-index:-1">
    <div class="item" style="background-image:url('<?php echo G5_IMG_URL;?>/main_bg.jpg');background-size: cover;background-position: center bottom;background-repeat: no-repeat;height: 100vh;width:100%;">
        <div class="text">
            <h2>함께하는 사회, 새로운 가치를 창조합니다.</h2>
            <p>C.MAP은 전문지식이 없어도 검색을 통해 쉽고 빠르게 관련 법률을 찾을 수 있습니다.<br>이를 통해 시공 단계를 좀 더 간결하고 빠르게 처리할 수 있도록 도와줍니다.</p>
        </div>
    </div>
</div>
<script src="<?php echo G5_JS_URL ?>/owl.carousel.js"></script>
<script>
    $(function() {
        var owl = $("#main");
        console.log(owl);
        owl.owlCarousel({
            animateOut: 'fadeOut',
            autoplay: true,
            autoplayTimeout: 5000,
            autoplaySpeed: 2000,
            smartSpeed: 2000,
            loop: true,
            dots: true,
            items: 1
        });
    });
</script>
<?php
include_once(G5_PATH.'/tail.php');
?>