<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
include_once (G5_PATH."/_head.php");

if(!$is_member) {
    alert("로그인이 필요합니다.", G5_BBS_URL . "/login.php");
}
?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2 onclick="location.href=g5_url+'/page/mypage/mypage.php'">MY C.MAP</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout.php"><span></span>로그아웃</a>
            </div>
        </header>
        <aside class="mypage_menu">
            <div class="mtop">
                <?php echo $member["mb_id"];?>님 회원정보
            </div>
            <div class="mbottom">
                <ul class="mmenu">
                    <li onclick="location.href=g5_url+'/page/mypage/mypage.php'"><i></i>홈페이지 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/quickmenu.php'"><i></i>퀵메뉴 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/navigator.php'"><i></i>네비게이터 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/guide.php'"><i></i>사용자 가이드 설정</li>
                    <li class="active"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave.php'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>개인정보 수정</h2>
            </header>
            <div class="profile_pwd">
                <h2>비밀번호 확인</h2>
                <p>회원님의 정보를 안정하게 보호하기 위해 비밀번호를 다시 한 번 확인합니다.</p>
                <div class="input_area">
                    <form action="<?php echo G5_URL?>/page/mypage/check_password.php" method="post" name="pwd_form">
                        <input type="password" name="mb_password" id="chk_password" class="basic_input01" placeholder="비밀번호" required><input type="submit" value="확인" class="basic_btn01">
                    </form>
                </div>
                <div class="desc_area">
                    <p>회원님의 개인정보를 신중히 취급하며, 회원님의 동의 없이는</p>
                    <p>기재하신 회원정보를 공개 및 변경하지 않습니다.</p>
                </div>
            </div>
        </article>
    </section>
</div>

<?php
include_once (G5_PATH."/_tail.php");
?>