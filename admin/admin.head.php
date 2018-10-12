<?php
// 접근 권한 검사
if (!$member['mb_id'])
{
    alert('로그인이 필요합니다..', G5_BBS_URL.'/login.php?sub=login&url=' . urlencode(G5_URL."/admin/"));
}else if ($member["mb_level"] < 10)
{
    alert("접근 권한이 없습니다.",G5_URL);
}

include_once(G5_PATH."/head.sub.php");
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/default.css">', 0);
include_once (G5_PATH."/head.sub.php");
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/admin.css">', 0);
?>
<div class="full-width">
    <header>
        <div class="top">
            <!--<div class="logo" >
                <a href="<?php /*echo G5_URL*/?>/admin/"><img src="<?php /*echo G5_IMG_URL*/?>/logo.png" alt=""></a>
            </div>-->
            <div class="title">
                <a href="<?php echo G5_URL?>/admin/"><h2>CMAP ADMIN PAGE</h2></a>
            </div>
            <div class="clear"></div>
            <div class="loginfo">
                <ul>
                    <li><a href="<?php echo G5_BBS_URL?>/logout.php">로그아웃</a></li>
                    <li><a href="<?php echo G5_URL?>">HOMEPAGE</a></li>
                </ul>
            </div>
            <div class="top_bg"></div>
        </div>
        <div class="left">
            <div class="lnb">
                <ul data-accordion-group id="admin-menu">
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">카테고리관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/category_list.php"; ?>">카테고리 관리</a></div>
                            <!-- <div><a href="<?php echo G5_URL."/admin/.php"; ?>">카테고리 예문 관리</a></div> -->
                            <div><a href="<?php echo G5_URL."/admin/category_temp_list.php"; ?>">제안하기 관리</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">홈페이지관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/filter_write.php"; ?>">금칙어관리</a></div>
                            <div><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=help"; ?>">도움말관리</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">게시물관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/product_list.php"; ?>">게시글 관리</a></div>
                            <div><a href="<?php echo G5_URL."/admin/product_ad_list.php"; ?>">광고관리</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">회원관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/member_list.php"; ?>">회원관리</a></div>
                            <div><a href="<?php echo G5_URL."/admin/member_company_list.php"; ?>">사업자신청관리</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">고객센터</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/board_list.php?bo_table=notice"; ?>">공지사항</a></div>
                            <div><a href="<?php echo G5_URL."/admin/qa_list.php?"; ?>">1:1문의</a></div>
                            <div><a href="<?php echo G5_URL."/admin/faq_list.php?fm_id=1"; ?>">faq</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">정산관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/"; ?>">거래내역</a></div>
                            <div><a href="<?php echo G5_URL."/admin/"; ?>">배송상태</a></div>
                            <div><a href="<?php echo G5_URL."/admin/"; ?>">정산</a></div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- <div class="left">
			<div><?php //echo visit("basic")?></div>
		</div> -->
            <div class="left_bg"></div>
        </div>
    </header>