<?php
include_once ("../../common.php");
if(!$is_member){
    goto_url(G5_BBS_URL."/login.php");
}
$sub="sub";
$mypage=true;
include_once (G5_PATH."/page/mypage/confirm.php");
include_once (G5_PATH."/_head.php");
?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>MY C.MAP</h2>
            <div class="logout">
                <a href="javascript:fnLogout();"><span></span>로그아웃</a>
            </div>
        </header>
        <aside class="mypage_menu">
            <div class="mtop">
                <?php echo $member["mb_id"];?>님 회원정보
            </div>
            <div class="mbottom">
                <ul class="mmenu">
                    <li onclick="location.href=g5_url+'/page/mypage/mypage'"><i></i>홈페이지 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/quickmenu'"><i></i>퀵메뉴 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/navigator'"><i></i>네비게이터 설정</li>
                    <li class="active" ><i></i>사용자 가이드 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd'"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>사용자 가이드 설정</h2>
            </header>
            <div class="homepage_con">

            </div>
        </article>
    </section>
</div>
<?php
include_once (G5_PATH."/_tail.php");
?>
