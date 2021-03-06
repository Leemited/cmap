<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<div class="login_hd">
    <div class="head">
        <div class="line"></div>
        <h2>로그인</h2>
        <p>건설관리지도 C.MAP에 오신 것을 환영합니다.</p>
        <div class="close" onclick="location.href=g5_url">
            <img src="<?php echo G5_IMG_URL?>/close_icon.svg" alt="">
        </div>
    </div>


<!-- 로그인 시작 { -->
	<div id="mb_login" class="mbskin">
		<h1><span class="color_s">C</span>onstruction Management <span class="color_s">Map</span></h1>

		<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
		<input type="hidden" name="url" value="<?php echo $login_url ?>">

		<fieldset id="login_fs">
			<legend>회원로그인</legend>
			<label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
			<input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" placeholder="아이디" value="<?php echo $mb_id;?>">

			<label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
			<input type="password" name="mb_password" id="login_pw" required class="frm_input required" size="20" maxLength="20" placeholder="비밀번호">

			<input type="checkbox" name="auto_login" id="login_auto_login">
			<label for="login_auto_login"><span></span>로그인 상태 유지</label>

			<input type="submit" value="로그인" class="btn_submit">

	<!--
			<input type="submit" value="로그인" class="btn_submit">
			<input type="checkbox" name="auto_login" id="login_auto_login">
			<label for="login_auto_login">자동로그인</label>
	-->

		</fieldset>

		<?php
		// 소셜로그인 사용시 소셜로그인 버튼
		@include_once(get_social_skin_path().'/social_login.skin.php');
		?>

		<aside id="login_info">
			<h2>회원로그인 안내</h2>
			<div>
                <div class="fpasswd">
                    <a href="javascript:fnFindId()" id="login_password_lost">아이디 찾기</a>
                    <span class="lost_line"></span>
                    <a href="javascript:fnFindPw()" id="login_password_lost">비밀번호 찾기</a>
                </div>
				<a href="./register" class="registers">회원 가입</a>
			</div>
		</aside>

		</form>


	</div>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    return true;
}
function fnFindId(){
    $.ajax({
        url:g5_url+"/page/modal/ajax.find_id.php",
        method:"post"
    }).done(function(data){
        fnShowModal(data);
    });
}
function fnFindPw(){
    $.ajax({
        url:g5_url+"/page/modal/ajax.find_pw.php",
        method:"post"
    }).done(function(data){
        fnShowModal(data);
    });
}
</script>
<!-- } 로그인 끝 -->
