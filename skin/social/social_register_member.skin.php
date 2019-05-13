<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if( ! $config['cf_social_login_use']) {     //소셜 로그인을 사용하지 않으면
    return;
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal.css">', 11);
add_stylesheet('<link rel="stylesheet" href="'.G5_JS_URL.'/remodal/remodal-default-theme.css">', 12);
add_stylesheet('<link rel="stylesheet" href="'.get_social_skin_url().'/style.css">', 13);
add_javascript('<script src="'.G5_JS_URL.'/remodal/remodal.js"></script>', 10);

$email_msg = $is_exists_email ? '등록할 이메일이 중복되었습니다.다른 이메일을 입력해 주세요.' : '';
?>

<div class="login_hd">
    <div class="head">
        <div class="line"></div>
        <h2>회원가입</h2>
        <p>건설관리지도 C.MAP에 오신 것을 환영합니다.</p>
        <div class="close" onclick="location.href=g5_url">
            <img src="<?php echo G5_IMG_URL?>/close_icon.svg" alt="">
        </div>
    </div>
<!-- 회원정보 입력/수정 시작 { -->
    <section id="mb_login" class="mbskin">


        <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>

        <!-- 새로가입 시작 -->
        <form id="fregister" name="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="w" value="<?php echo $w; ?>">
            <input type="hidden" name="url" value="<?php echo $urlencode; ?>">
            <input type="hidden" name="mb_name" value="<?php echo $user_name ? $user_name : $user_nick ?>" >
            <input type="hidden" name="provider" value="<?php echo $provider_name;?>" >
            <input type="hidden" name="action" value="register">

            <input type="hidden" name="mb_id" value="<?php echo $user_id; ?>" id="reg_mb_id">
            <input type="hidden" name="mb_nick_default" value="<?php echo isset($user_nick)?get_text($user_nick):''; ?>">
            <input type="hidden" name="mb_nick" value="<?php echo isset($user_nick)?get_text($user_nick):''; ?>" id="reg_mb_nick">

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
                    <div class="agree_box"><?php echo $config['cf_stipulation'] ?></div>
                    <fieldset class="fregister_agree">
                        <input type="checkbox" name="agree" value="1" id="agree11">
                        <label for="agree11"><span></span>회원가입약관의 내용에 동의합니다.</label>
                    </fieldset>
                </section>

                <section id="fregister_private">
                    <h2><i></i> 개인정보처리방침안내</h2>
                    <div class="agree_box"><?php echo $config['cf_stipulation'] ?></div>
                    <!--<div>
                        <table>
                            <caption>개인정보처리방침안내</caption>
                            <thead>
                            <tr>
                                <th>목적</th>
                                <th>항목</th>
                                <th>보유기간</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>이용자 식별 및 본인여부 확인</td>
                                <td>아이디, 이름, 비밀번호</td>
                                <td>회원 탈퇴 시까지</td>
                            </tr>
                            <tr>
                                <td>고객서비스 이용에 관한 통지,<br>CS대응을 위한 이용자 식별</td>
                                <td>연락처 (이메일, 휴대전화번호)</td>
                                <td>회원 탈퇴 시까지</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>-->

                    <fieldset class="fregister_agree">
                        <input type="checkbox" name="agree2" value="1" id="agree21">
                        <label for="agree21"><span></span>개인정보처리방침안내의 내용에 동의합니다.</label>
                    </fieldset>
                </section>

                <!--<div class="toggle">
                    <div class="toggle-title">
                    <span class="right_i"><i></i> 자세히보기</span>
                    <span class="title-name"><input type="checkbox" name="agree" value="1" id="agree11"> <label for="agree11">회원가입약관</label></span>
                    </div>
                    <div class="toggle-inner">
                        <p><?php /*echo conv_content($config['cf_stipulation'], 0); */?></p>
                    </div>
                </div>  <!-- END OF TOGGLE --
                <div class="toggle">
                    <div class="toggle-title">
                    <span class="right_i"><i></i> 자세히보기</span>
                    <span class="title-name"><input type="checkbox" name="agree2" value="1" id="agree21"> <label for="agree21">개인정보처리방침안내</label></span>
                    </div>
                    <div class="toggle-inner">
                        <p><?php /*echo conv_content($config['cf_privacy'], 0); */?></p>
                    </div>
                </div>  <!-- END OF TOGGLE --
                <div class="all_agree">
                    <span class="title-name"><input type="checkbox" name="chk_all" value="1" id="chk_all"> <label for="chk_all"><strong>전체약관에 동의합니다.</strong></label></span>
                </div>-->
                <section id="fregister_private">
                    <h2><i></i> 개인정보 등록</h2>
                    <div id="login_fs">
                        <?php $user_emails = explode("@",$user_email);?>
                        <input type="text" name="mb_email" id="reg_mb_email" value="<?php echo $user_emails[0];?>">
                        <input type="text" name="mb_email2" id="reg_mb_email2" value="<?php echo $user_emails[1];?>">
                        <input type="text" name="mb_email_t" value="<?php echo isset($user_email)?$user_email:''; ?>" id="reg_mb_email_t" required class="frm_input email required full_input" size="70" maxlength="100" placeholder="이메일을 입력해주세요." onchange="fnEmailChange(this.value)">
                        <?php if($email_msg){?>
                        <p class="email_msg"><?php echo $email_msg; ?></p>
                        <?php } ?>
                    </div>
                </section>

                <div class="btn_confirm">
                    <input type="submit" value="회원가입" id="btn_submit" class="btn_submit" accesskey="s">
                    <input type="button" value="취소" class="btn_submit" onclick="location.href=g5_url">
                    <!--<a href="<?php /*echo G5_URL */?>" class="btn_cancel">취소</a>-->
                </div>
            </form>
            <!-- 새로가입 끝 -->

            <!-- 기존 계정 연결 -->
            <section id="user_connect">
                <h2><i></i> 기존 아이디 연결</h2>
                <form method="post" action="<?php echo $login_action_url ?>" onsubmit="return social_obj.flogin_submit(this);">
                    <input type="hidden" id="url" name="url" value="<?php echo $login_url ?>">
                    <input type="hidden" id="provider" name="provider" value="<?php echo $provider_name ?>">
                    <input type="hidden" id="action" name="action" value="social_account_linking">
                    <div class="info">
                        <ul>
                            <li>기존 아이디에 SNS 아이디를 연결합니다.</li>
                            <li>이 후 SNS 아이디로 로그인 하시면 기존 아이디로 로그인 할 수 있습니다.</li>
                        </ul>
                    </div>

                    <div id="login_fs">
                        <span class="lg_id"><input type="text" name="mb_id" id="login_id" class="frm_input required width50 float" size="20" maxLength="20" placeholder="아이디"></span>

                        <span class="lg_pw"><input type="password" name="mb_password" id="login_pw" class="frm_input required width50 float" size="20" maxLength="20" placeholder="비밀번호"></span>
                    </div>
                    <div class="clear"></div>
                    <div class="btn_confirm">
                        <input type="submit" value="연결하기" class="btn_submit">
                    </div>
                </form>
            </section>
        </section>
    </div>
    <script>

    // submit 최종 폼체크
    function fregisterform_submit(f)
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

        // E-mail 검사
        if (f.w.value == "")  {
            var msg = reg_mb_email_check();
            msg  = msg.replace(/(\n|\r\n)/g, "");
            if (msg && msg != "!") {
                //alert(msg);
                $(".email_msg").html(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

    function flogin_submit(f)
    {
        var mb_id = $.trim($(f).find("input[name=mb_id]").val()),
            mb_password = $.trim($(f).find("input[name=mb_password]").val());

        if(!mb_id || !mb_password){
            return false;
        }

        return true;
    }

    jQuery(function($){
        if( jQuery(".toggle .toggle-title").hasClass('active') ){
            jQuery(".toggle .toggle-title.active").closest('.toggle').find('.toggle-inner').show();
        }
        jQuery(".toggle .toggle-title .right_i").click(function(){

            var $parent = $(this).parent();
            
            if( $parent.hasClass('active') ){
                $parent.removeClass("active").closest('.toggle').find('.toggle-inner').slideUp(200);
            } else {
                $parent.addClass("active").closest('.toggle').find('.toggle-inner').slideDown(200);
            }
        });
        // 모두선택
        $("input[name=chk_all]").click(function() {
            if ($(this).prop('checked')) {
                $("input[name^=agree]").prop('checked', true);
            } else {
                $("input[name^=agree]").prop("checked", false);
            }
        });
    });

    function fnEmailChange(email){
        var emails = email.split("@");
        $("#reg_mb_email").val(emails[0]);
        $("#reg_mb_email2").val(emails[1]);
    }
    </script>

</div>
<!-- } 회원정보 입력/수정 끝 -->