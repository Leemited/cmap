<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원가입약관 동의 시작 { -->
<div class="login_hd" style="margin:120px 0;">
    <div class="head">
        <div class="line"></div>
        <h2>회원가입</h2>
        <p>건설관리지도 C.MAP에 오신 것을 환영합니다.</p>
        <div class="close" onclick="location.href=g5_url">
            <img src="<?php echo G5_IMG_URL?>/close_icon.svg" alt="">
        </div>
    </div>


<!-- 로그인 시작 { -->
	<div id="mb_login" class="mbskin">

		<?php
		// 소셜로그인 사용시 소셜로그인 버튼
		//@include_once(get_social_skin_path().'/social_register.skin.php');
		?>

		<form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">
            <input type="hidden" name="type" value="<?php echo $type;?>">
		<!--<p>회원가입약관 및 개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.</p>-->
        <section id="fregister_term">
            <h2>개인정보 수집 및 이용 동의 </h2>
            <div class="info">
                <ul>
                    <li>본 사이트는 개인정보보호법 및 "정보통신망 이용촉진 및 정보보호 등에 관한 법률"등 개인정보보호에 관한 제반 법령을 준수하고 있습니다. </li>
                    <li>가입 시 입력하신 정보는 본 사이트의 고객 지원 정보로만 사용되며, 회원탈퇴시 모든 정보가 삭제됩니다. </li>
                    <li>회원탈퇴는 로그인 후 '회원정보 변경'에서 가능합니다.</li>
                </ul>
            </div>
            <div id="fregister_chkall">
                <fieldset class="fregister_agree">
                <input type="checkbox" name="chk_all"  value="1"  id="chk_all">
                <label for="chk_all"><span></span>전체선택</label>
                </fieldset>
            </div>
        </section>
		<section id="fregister_term">
			<h2><i></i> 이용약관</h2>
			<article class="agree_box">
                <?php echo $config['cf_stipulation']; ?>
            </article>
			<fieldset class="fregister_agree">
				<input type="checkbox" name="agree" value="1" id="agree11">
				<label for="agree11"><span></span>회원가입약관의 내용에 동의합니다.</label>
			</fieldset>
		</section>

		<section id="fregister_private">
			<h2><i></i> 개인정보처리방침안내</h2>
            <div class="agree_box" >
                <?php echo $config['cf_privacy']; ?>
            </div>

			<fieldset class="fregister_agree">
				<input type="checkbox" name="agree2" value="1" id="agree21">
				<label for="agree21"><span></span>개인정보처리방침안내의 내용에 동의합니다.</label>
			</fieldset>
		</section>

		<div class="btn_confirm">
			<input type="submit" class="btn_submit" value="다음">
		</div>

		</form>

		<script>
		function fregister_submit(f)
		{
			if (!f.agree.checked) {
				alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
				f.agree.focus();
				return false;
			}

			if (!f.agree2.checked) {
				alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
				f.agree2.focus();
				return false;
			}

			return true;
		}
		
		jQuery(function($){
			// 모두선택
			$("input[name=chk_all]").click(function() {
				if ($(this).prop('checked')) {
					$("input[name^=agree]").prop('checked', true);
				} else {
					$("input[name^=agree]").prop("checked", false);
				}
			});
		});

		</script>
	</div>
</div>

<!-- } 회원가입 약관 동의 끝 -->
