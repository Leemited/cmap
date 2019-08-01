<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

?>

<!-- 상단 시작 { -->
<div class="header_top" style="position:fixed;top:0;display:inline-block;left:0;width:100%;height:60px;z-index: 12">
    <div id="hd">
        <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

        <?php
        if(defined('_INDEX_')) { // index에서만 실행
            include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
        }
        ?>
        <nav id="gnb" class="<?php echo $myset["theme"];?> <?php echo "cate_".$myset["cate_theme"];?>">
            <div id="logo">
                <a href="<?php echo G5_URL ?>">
                    <?php if($myset["theme"]=="white"){?>
                    <img src="<?php echo G5_IMG_URL ?>/logo_b.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }else{ ?>
                    <img src="<?php echo G5_IMG_URL ?>/logo.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }?>
                </a>
            </div>
            <div class="gnb_wrap">
                <ul id="gnb_1dul">
                    <li class="gnb_empty">
                        <span>C</span>MAP ENTERPRISE MEMBER <?php echo $member["mb_1"];?>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!-- } 상단 끝 -->
