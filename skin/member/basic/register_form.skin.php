<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
if($is_member) {
    $email = explode("@", $member["mb_email"]);
    $hp = explode("-", $member["mb_hp"]);
    $tel = explode("-", $member["mb_tel"]);
}
?>

<!-- 회원정보 입력/수정 시작 { -->
<div class="login_hd" style="margin:120px 0;position:relative;-webkit-transform: translateY(0);-moz-transform: translateY(0);-ms-transform: translateY(0);-o-transform: translateY(0);transform: translateY(0);top:0;left:0">
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
        <script src="<?php echo G5_JS_URL ?>/jquery.register_form.js"></script>
        <?php if($config['cf_cert_use'] && ($config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
            <script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>
        <?php } ?>

        <form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="w" value="<?php echo $w ?>">
            <input type="hidden" name="url" value="<?php echo $urlencode ?>">
            <input type="hidden" name="agree" value="<?php echo $agree ?>">
            <input type="hidden" name="agree2" value="<?php echo $agree2 ?>">
            <input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
            <input type="hidden" name="cert_no" value="">
            <input type="hidden" name="mb_level" id="mb_level" value="">
            <input type="hidden" name="type" value="<?php echo $type;?>">
            <input type="hidden" name="mb_nick" value="<?php echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; ?>" id="reg_mb_nick" required class="frm_input required nospace  half_input" size="10" maxlength="20" placeholder="닉네임">
            <?php if (isset($member['mb_sex'])) {  ?><input type="hidden" name="mb_sex" value="<?php echo $member['mb_sex'] ?>"><?php }  ?>
            <?php if (isset($member['mb_nick_date']) && $member['mb_nick_date'] > date("Y-m-d", G5_SERVER_TIME - ($config['cf_nick_modify'] * 86400))) { // 닉네임수정일이 지나지 않았다면  ?>
                <input type="hidden" name="mb_nick_default" value="<?php echo get_text($member['mb_nick']) ?>">
                <input type="hidden" name="mb_nick" value="<?php echo get_text($member['mb_nick']) ?>">
            <?php }  ?>
            <div id="register_form"  class="form_01">
                <div>
                    <h2><i></i>개인정보 입력</h2>
                    <ul>
                        <li class="sel_mb_type">
                            <input type="radio" name="mb_type" id="mb_type1" value="0" <?php if($mmember["mb_type"]==0 || !$is_member){?>checked<?php }?>><label for="mb_type1"> 개인</label>
                            <input type="radio" name="mb_type" id="mb_type2" value="1" <?php if($mmember["mb_type"]==1){?>checked<?php }?>><label for="mb_type2"> 기업</label>
                        </li>
                        <li>
                            <label for="reg_mb_id" class="sound_only">아이디<strong>필수</strong></label>
                            <input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" <?php echo $required ?> <?php echo $readonly ?> class="frm_input half_input <?php echo $required ?> <?php echo $readonly ?>" minlength="3" maxlength="20" placeholder="아이디" onchange="$('#reg_mb_nick').val(this.value)">
                            <input type="button" value="중복확인" class="confirm_btn" onclick="fnIdchk()">
                            <input type="hidden" name="mb_id_chk" value="N" id="reg_mb_id_chk">
                            <span id="msg_mb_id"></span>
                            <!--<span class="frm_info">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>-->
                        </li>
                        <li>
                            <label for="reg_mb_password" class="sound_only">비밀번호<strong class="sound_only">필수</strong></label>
                            <input type="password" name="mb_password" id="reg_mb_password" <?php echo $required ?> class="frm_input half_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호">
                        </li>
                        <li>
                            <label for="reg_mb_password_re" class="sound_only">비밀번호 확인<strong>필수</strong></label>
                            <input type="password" name="mb_password_re" id="reg_mb_password_re" <?php echo $required ?> class="frm_input half_input right_input <?php echo $required ?>" minlength="3" maxlength="20" placeholder="비밀번호 확인">
                        </li>
                        <li>
                            <label for="reg_mb_name" class="sound_only">이름<strong>필수</strong></label>
                            <input type="text" id="reg_mb_name" name="mb_name" value="<?php echo get_text($member['mb_name']) ?>" <?php echo $required ?> <?php echo $readonly; ?> class="frm_input half_input <?php echo $required ?> <?php echo $readonly ?>" size="10" placeholder="이름">

                        </li>
                        <?php /*if ($req_nick) {  */?><!--
                    <li>
                        <label for="reg_mb_nick" class="sound_only">닉네임<strong>필수</strong></label>

                            <input type="hidden" name="mb_nick_default" value="<?php /*echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; */?>">
                            <input type="text" name="mb_nick" value="<?php /*echo isset($member['mb_nick'])?get_text($member['mb_nick']):''; */?>" id="reg_mb_nick" required class="frm_input required nospace  half_input" size="10" maxlength="20" placeholder="닉네임">
                            <span id="msg_mb_nick"></span>
                            <span class="frm_info">
                                공백없이 한글,영문,숫자만 입력 가능 (한글2자, 영문4자 이상)<br>
                                닉네임을 바꾸시면 앞으로 <?php /*echo (int)$config['cf_nick_modify'] */?>일 이내에는 변경 할 수 없습니다.
                            </span>

                    </li>
                    --><?php /*}  */?>

                        <li>
                            <label for="reg_mb_email" class="sound_only">E-mail<strong>필수</strong></label>

                            <?php if ($config['cf_use_email_certify']) {  ?>
                                <span class="frm_info">
                            <?php if ($w=='') { echo "E-mail 로 발송된 내용을 확인한 후 인증하셔야 회원가입이 완료됩니다."; }  ?>
                            <?php if ($w=='u') { echo "E-mail 주소를 변경하시면 다시 인증하셔야 합니다."; }  ?>
                        </span>
                            <?php }  ?>
                            <input type="hidden" name="old_email" value="<?php echo $member['mb_email'] ?>">
                            <input type="text" name="mb_email" value="<?php echo ($email[0])?$email[0]:"";?>" id="reg_mb_email" required class="frm_input email required" size="70" maxlength="100" placeholder="E-mail"> <div class="email_marks"><span>@</span></div>
                            <input type="text" name="mb_email2" value="<?php echo ($email[1])?$email[1]:"";?>" id="reg_mb_email2" required class="frm_input email required" size="70" maxlength="100" placeholder="">
                            <select name="mb_email_sel" id="mb_email_sel" class="frm_input email" onchange="$('#reg_mb_email2').val(this.value)">
                                <option value="">직접입력</option>
                                <option value="naver.com">naver.com</option>
                                <option value="gmail.com">gmail.com</option>
                                <option value="hanmail.net">hanmail.net</option>
                                <option value="daum.net">daum.net</option>
                            </select>
                            <div class="agress">
                                <input type="checkbox" name="mb_mailling" value="1" id="reg_mb_mailling" <?php echo ($member['mb_mailling'])?'checked':''; ?>>
                                <label for="reg_mb_mailling" class="frm_label"><span></span>이메일을 통해 C.MAP의 다양한 정보를 받아보겠습니다.</label>
                            </div>
                        </li>

                        <?php if ($config['cf_use_homepage']) {  ?>
                            <li>
                                <label for="reg_mb_homepage" class="sound_only">홈페이지<?php if ($config['cf_req_homepage']){ ?><strong>필수</strong><?php } ?></label>
                                <input type="text" name="mb_homepage" value="<?php echo get_text($member['mb_homepage']) ?>" id="reg_mb_homepage" <?php echo $config['cf_req_homepage']?"required":""; ?> class="frm_input full_input <?php echo $config['cf_req_homepage']?"required":""; ?>" size="70" maxlength="255" placeholder="홈페이지">
                            </li>
                        <?php }  ?>

                        <li class="hp_crets">
                            <?php /*if ($config['cf_use_tel']) {  */?><!--

                        <label for="reg_mb_tel" class="sound_only">전화번호<?php /*if ($config['cf_req_tel']) { */?><strong>필수</strong><?php /*} */?></label>
                        <input type="text" name="mb_tel" value="<?php /*echo get_text($member['mb_tel']) */?>" id="reg_mb_tel" <?php /*echo $config['cf_req_tel']?"required":""; */?> class="frm_input half_input <?php /*echo $config['cf_req_tel']?"required":""; */?>" maxlength="20" placeholder="전화번호">
                    --><?php /*}  */?>

                            <label for="reg_mb_hp" class="sound_only">휴대폰번호<?php if ($config['cf_req_hp']) { ?><strong>필수</strong><?php } ?></label>
                            <!--<select name="mb_hp[]" id="mb_hp1" class="frm_input <?php /*echo $required;*/?>" <?php /*echo $required;*/?>>
                                <option value="010" <?php /*echo get_selected($tel[0],"010");*/?>>010</option>
                                <option value="070" <?php /*echo get_selected($tel[0],"070");*/?>>070</option>
                            </select> - <input type="text" name="mb_hp[]" id="mb_hp2" value="<?php /*echo $hp[0];*/?>" class="frm_input <?php /*echo $required;*/?>" <?php /*echo $required;*/?> maxlength="4"> - <input type="text" name="mb_hp[]" id="mb_hp3" value="<?php /*echo $hp[0];*/?>" class="frm_input <?php /*echo $required;*/?>" <?php /*echo $required;*/?> maxlength="4">-->
                            <input type="text" name="mb_hp" value="<?php echo get_text($member['mb_hp']) ?>" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input left_input half_input <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20" placeholder="휴대폰 본인확인버튼을 눌러주세요." readonly style="width:50%">
                            <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                                <input type="hidden" name="old_mb_hp" value="<?php echo get_text($member['mb_hp']) ?>">
                            <?php } ?>
                            <?php
                            if($config['cf_cert_use']) {
                                if($config['cf_cert_ipin'])
                                    echo '<button type="button" id="win_ipin_cert" class="btn_frmline">아이핀 본인확인</button>'.PHP_EOL;
                                if($config['cf_cert_hp'])
                                    echo '<button type="button" id="win_hp_cert" class="btn_frmline">휴대폰 본인확인</button>'.PHP_EOL;

                                echo '<noscript>본인확인을 위해서는 자바스크립트 사용이 가능해야합니다.</noscript>'.PHP_EOL;
                            }
                            ?>
                            <?php
                            if ($config['cf_cert_use'] && $member['mb_certify']) {
                                if($member['mb_certify'] == 'ipin')
                                    $mb_cert = '아이핀';
                                else
                                    $mb_cert = '휴대폰';
                                ?>

                                <div id="msg_certify">
                                    <strong><?php echo $mb_cert; ?> 본인확인</strong><?php if ($member['mb_adult']) { ?> 및 <strong>성인인증</strong><?php } ?> 완료
                                </div>
                            <?php } ?>
                            <?php if ($config['cf_cert_use']) { ?>
                                <span class="frm_info">휴대폰 본인확인 후에는 이름과 휴대폰번호가 자동 입력되어 수동으로 입력할수 없게 됩니다.</span>
                            <?php } ?>
                            <div class="agress">
                                <input type="checkbox" name="mb_sms" value="1" id="reg_mb_sms" <?php echo ($member['mb_sms'])?'checked':''; ?>>
                                <label for="reg_mb_sms" class="frm_label"><span></span>SMS를 통해 C.MAP의 다양한 정보를 받아보겠습니다.</label>
                            </div>
                        </li>
                        <li class="company_regi">
                            <label for="reg_mb_password_re" class="sound_only">회사명</label>
                            <input type="text" name="mb_1" id="reg_mb_1" class="frm_input half_input  " placeholder="회사명">
                        </li>
                        <li class="company_regi">
                            <label for="reg_mb_password_re" class="sound_only">직책</label>
                            <input type="text" name="mb_4" id="reg_mb_4" class="frm_input half_input  " placeholder="직책">
                        </li>
                        <li class="company_regi">
                            <label for="reg_mb_password_re" class="sound_only">대표자</label>
                            <input type="text" name="mb_2" id="reg_mb_2" class="frm_input half_input  " placeholder="대표자">
                        </li>
                        <li class="company_regi">
                            <label for="reg_mb_password_re" class="sound_only">사업자번호</label>
                            <input type="hidden" name="company_auth" id="company_auth" value="<?php if($w==''){?>N<?php }else{?>Y<?php }?>">
                            <input type="text" name="mb_3[]" id="reg_mb_3_1" value="<?php echo $mb3[0];?>" class="frm_input half_input left_input" style="width:22%" placeholder="사업자번호"><span class="lab left_input"> - </span>
                            <input type="text" name="mb_3[]" id="reg_mb_3_2" value="<?php echo $mb3[0];?>" class="frm_input half_input left_input" style="width:22%" placeholder="사업자번호"><span class="lab left_input"> - </span>
                            <input type="text" name="mb_3[]" id="reg_mb_3_3" value="<?php echo $mb3[0];?>" class="frm_input half_input left_input" style="width:22%" placeholder="사업자번호">
                            <button type="button" class="btn_frmline" style="width:22%;" onclick="fnCompanyChk();">사업자 확인</button>
                        </li>

                        <li class="company_regi">
                            <label for="reg_mb_password_re" class="sound_only">회사전화번호</label>
                            <select name="mb_tel[]" id="mb_tel1" class="frm_input left_input" >
                                <option value="010" <?php echo get_selected($tel[0],"010");?>>010</option>
                                <!--<option value="017">017</option>
                                <option value="018">018</option>
                                <option value="019">019</option>-->
                                <option value="070" <?php echo get_selected($tel[0],"070");?>>070</option>
                                <option value="02" <?php echo get_selected($tel[0],"02");?>>02</option>
                                <option value="031" <?php echo get_selected($tel[0],"031");?>>031</option>
                                <option value="032" <?php echo get_selected($tel[0],"032");?>>032</option>
                                <option value="033" <?php echo get_selected($tel[0],"033");?>>033</option>
                                <option value="041" <?php echo get_selected($tel[0],"041");?>>041</option>
                                <option value="042" <?php echo get_selected($tel[0],"042");?>>042</option>
                                <option value="043" <?php echo get_selected($tel[0],"043");?>>043</option>
                                <option value="044" <?php echo get_selected($tel[0],"044");?>>044</option>
                                <option value="051" <?php echo get_selected($tel[0],"051");?>>051</option>
                                <option value="052" <?php echo get_selected($tel[0],"052");?>>052</option>
                                <option value="053" <?php echo get_selected($tel[0],"053");?>>053</option>
                                <option value="054" <?php echo get_selected($tel[0],"054");?>>054</option>
                                <option value="055" <?php echo get_selected($tel[0],"055");?>>055</option>
                                <option value="061" <?php echo get_selected($tel[0],"061");?>>061</option>
                                <option value="062" <?php echo get_selected($tel[0],"062");?>>062</option>
                                <option value="063" <?php echo get_selected($tel[0],"063");?>>063</option>
                                <option value="064" <?php echo get_selected($tel[0],"064");?>>064</option>
                            </select><span class="lab left_input"> - </span><input type="text" name="mb_tel[]" id="mb_tel2" value="<?php echo $tel[1];?>" class="frm_input left_input" maxlength="4" placeholder="회사번호"><span class="lab left_input"> - </span><input type="text" name="mb_tel[]" id="mb_tel3"  value="<?php echo $tel[2];?>" class="frm_input left_input" maxlength="4" placeholder="회사번호">
                        </li>

                        <?php if ($config['cf_use_addr']) { ?>
                            <li>
                                <?php if ($config['cf_req_addr']) { ?><strong class="sound_only">필수</strong><?php }  ?>
                                <label for="reg_mb_zip" class="sound_only">우편번호<?php echo $config['cf_req_addr']?'<strong class="sound_only"> 필수</strong>':''; ?></label>
                                <input type="text" name="mb_zip" value="<?php echo $member['mb_zip1'].$member['mb_zip2']; ?>" id="reg_mb_zip" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="5" maxlength="6"  placeholder="우편번호" readonly>
                                <button type="button" class="btn_frmline" onclick="win_zip('fregisterform', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                                <input type="text" name="mb_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="reg_mb_addr1" <?php echo $config['cf_req_addr']?"required":""; ?> class="frm_input frm_address full_input <?php echo $config['cf_req_addr']?"required":""; ?>" size="50"  placeholder="기본주소" readonly>
                                <label for="reg_mb_addr1" class="sound_only">기본주소<?php echo $config['cf_req_addr']?'<strong> 필수</strong>':''; ?></label><br>
                                <input type="text" name="mb_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="reg_mb_addr2" class="frm_input frm_address full_input" size="50"  placeholder="상세주소">
                                <label for="reg_mb_addr2" class="sound_only">상세주소</label>
                                <br>
                                <input type="hidden" name="mb_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly"  placeholder="참고항목">
                                <label for="reg_mb_addr3" class="sound_only">참고항목</label>
                                <input type="hidden" name="mb_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">

                            </li>
                        <?php }  ?>
                        <?php /*if ($config['cf_use_signature']) {  */?><!--
                    <li>
                        <label for="reg_mb_signature" class="sound_only">서명<?php /*if ($config['cf_req_signature']){ */?><strong>필수</strong><?php /*} */?></label>
                        <textarea name="mb_signature" id="reg_mb_signature" <?php /*echo $config['cf_req_signature']?"required":""; */?> class="<?php /*echo $config['cf_req_signature']?"required":""; */?>"   placeholder="서명"><?php /*echo $member['mb_signature'] */?></textarea>
                    </li>
                    <?php /*}  */?>

                    <?php /*if ($config['cf_use_profile']) {  */?>
                    <li>
                        <label for="reg_mb_profile" class="sound_only">자기소개</label>
                        <textarea name="mb_profile" id="reg_mb_profile" <?php /*echo $config['cf_req_profile']?"required":""; */?> class="<?php /*echo $config['cf_req_profile']?"required":""; */?>" placeholder="자기소개"><?php /*echo $member['mb_profile'] */?></textarea>
                    </li>
                    <?php /*}  */?>

                    <?php /*if ($config['cf_use_member_icon'] && $member['mb_level'] >= $config['cf_icon_level']) {  */?>
                    <li>
                        <label for="reg_mb_icon" class="frm_label">회원아이콘</label>
                        <input type="file" name="mb_icon" id="reg_mb_icon" >

                        <span class="frm_info">
                            이미지 크기는 가로 <?php /*echo $config['cf_member_icon_width'] */?>픽셀, 세로 <?php /*echo $config['cf_member_icon_height'] */?>픽셀 이하로 해주세요.<br>
                            gif, jpg, png파일만 가능하며 용량 <?php /*echo number_format($config['cf_member_icon_size']) */?>바이트 이하만 등록됩니다.
                        </span>

                        <?php /*if ($w == 'u' && file_exists($mb_icon_path)) {  */?>
                        <img src="<?php /*echo $mb_icon_url */?>" alt="회원아이콘">
                        <input type="checkbox" name="del_mb_icon" value="1" id="del_mb_icon">
                        <label for="del_mb_icon">삭제</label>
                        <?php /*}  */?>

                    </li>
                    --><?php /*}  */?>

                        <?php /*if ($member['mb_level'] >= $config['cf_icon_level'] && $config['cf_member_img_size'] && $config['cf_member_img_width'] && $config['cf_member_img_height']) {  */?><!--
                    <li class="reg_mb_img_file">
                        <label for="reg_mb_img" class="frm_label">회원이미지</label>
                        <input type="file" name="mb_img" id="reg_mb_img" >

                        <span class="frm_info">
                            이미지 크기는 가로 <?php /*echo $config['cf_member_img_width'] */?>픽셀, 세로 <?php /*echo $config['cf_member_img_height'] */?>픽셀 이하로 해주세요.<br>
                            gif, jpg, png파일만 가능하며 용량 <?php /*echo number_format($config['cf_member_img_size']) */?>바이트 이하만 등록됩니다.
                        </span>

                        <?php /*if ($w == 'u' && file_exists($mb_img_path)) {  */?>
                        <img src="<?php /*echo $mb_img_url */?>" alt="회원이미지">
                        <input type="checkbox" name="del_mb_img" value="1" id="del_mb_img">
                        <label for="del_mb_img">삭제</label>
                        <?php /*}  */?>

                    </li>
                    --><?php /*} */?>

                        <?php
                        //회원정보 수정인 경우 소셜 계정 출력
                        if( $w == 'u' && function_exists('social_member_provider_manage') ){
                            social_member_provider_manage();
                        }
                        ?>
                        <!--
                    <?php /*if ($w == "" && $config['cf_use_recommend']) {  */?>
                    <li>
                        <label for="reg_mb_recommend" class="sound_only">추천인아이디</label>
                        <input type="text" name="mb_recommend" id="reg_mb_recommend" class="frm_input" placeholder="추천인아이디">
                    </li>
                    --><?php /*}  */?>

                        <!--<li class="is_captcha_use">
                            자동등록방지
                            <?php /*echo captcha_html(); */?>
                        </li>-->
                    </ul>
                </div>

            </div>
            <div class="btn_confirm">
                <input type="button" onclick="location.href='<?php echo G5_URL ?>'" class="btn_cancel" value="취소">
                <input type="submit" value="<?php echo $w==''?'가입하기':'정보수정'; ?>" id="btn_submit" class="btn_submit" accesskey="s">
            </div>
        </form>

        <script>
            $(function() {
                $("#reg_zip_find").css("display", "inline-block");

                <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
                // 아이핀인증
                $("#win_ipin_cert").click(function() {
                    if(!cert_confirm())
                        return false;

                    var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php";
                    certify_win_open('kcb-ipin', url);
                    return;
                });

                <?php } ?>
                <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                // 휴대폰인증
                $("#win_hp_cert").click(function() {
                    if(!cert_confirm())
                        return false;

                    <?php
                    switch($config['cf_cert_hp']) {
                        case 'kcb':
                            $cert_url = G5_OKNAME_URL.'/hpcert1';
                            $cert_type = 'kcb-hp';
                            break;
                        case 'kcp':
                            $cert_url = G5_KCPCERT_URL.'/kcpcert_form';
                            $cert_type = 'kcp-hp';
                            break;
                        case 'lg':
                            $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq';
                            $cert_type = 'lg-hp';
                            break;
                        default:
                            echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                            echo 'return false;';
                            break;
                    }
                    ?>

                    certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>");
                    return;
                });
                <?php } ?>
                <?php if($is_member){?>

                <?php }?>
                $("input[name=mb_type]").click(function(){
                    //console.log($(this).val());
                    //console.log($("input[name=mb_type]:checked").val());
                    if($(this).val()==0){
                        $(".company_regi").hide();
                        $(".hp_crets").show();
                        $("#mb_level").val(2);
                        $("#company_auth").val("N");
                        $("#reg_mb_hp").attr("required",true);
                        $("#reg_mb_hp").addClass("required");
                        $("#reg_mb_1").removeAttr("required");
                        $("#reg_mb_1").removeClass("required");
                        $("#reg_mb_2").removeAttr("required");
                        $("#reg_mb_2").removeClass("required");
                        $("#reg_mb_4").removeAttr("required");
                        $("#reg_mb_4").removeClass("required");
                        $("#mb_tel2").removeAttr("required");
                        $("#mb_tel2").removeClass("required");
                        $("#mb_tel3").removeAttr("required");
                        $("#mb_tel3").removeClass("required");
                        $("#reg_mb_3_1").removeAttr("required");
                        $("#reg_mb_3_1").removeClass("required");
                        $("#reg_mb_3_2").removeAttr("required");
                        $("#reg_mb_3_2").removeClass("required");
                        $("#reg_mb_3_3").removeAttr("required");
                        $("#reg_mb_3_3").removeClass("required");
                    }else{
                        $(".company_regi").show();
                        $(".hp_crets").hide();
                        $("#mb_level").val(6);
                        $("#mb_level").val(6);
                        $("#reg_mb_hp").removeAttr("required");
                        $("#reg_mb_hp").removeClass("required");
                        $("#reg_mb_1").attr("required",true);
                        $("#reg_mb_1").addClass("required");
                        $("#reg_mb_2").attr("required",true);
                        $("#reg_mb_2").addClass("required");
                        $("#reg_mb_4").attr("required",true);
                        $("#reg_mb_4").addClass("required");
                        $("#mb_tel2").attr("required",true);
                        $("#mb_tel2").addClass("required");
                        $("#mb_tel3").attr("required",true);
                        $("#mb_tel3").addClass("required");
                        $("#reg_mb_3_1").attr("required",true);
                        $("#reg_mb_3_1").addClass("required");
                        $("#reg_mb_3_2").attr("required",true);
                        $("#reg_mb_3_2").addClass("required");
                        $("#reg_mb_3_3").attr("required",true);
                        $("#reg_mb_3_3").addClass("required");
                    }
                });
            });

            // submit 최종 폼체크
            function fregisterform_submit(f)
            {
                // 회원아이디 검사
                if (f.w.value == "") {
                    var msg = reg_mb_id_check();
                    msg  = msg.replace(/(\n|\r\n)/g, "");
                    if (msg) {
                        alert(msg);
                        f.mb_id.select();
                        return false;
                    }
                }

                if($("#reg_mb_id_chk").val()=="N"){
                    alert("아이디 중복확인 바랍니다.");
                    f.mb_id.select();
                    return false;
                }

                if (f.w.value == "") {
                    if (f.mb_password.value.length < 3) {
                        alert("비밀번호를 3글자 이상 입력하십시오.");
                        f.mb_password.focus();
                        return false;
                    }
                }

                if (f.mb_password.value != f.mb_password_re.value) {
                    alert("비밀번호가 같지 않습니다.");
                    f.mb_password_re.focus();
                    return false;
                }

                if (f.mb_password.value.length > 0) {
                    if (f.mb_password_re.value.length < 3) {
                        alert("비밀번호를 3글자 이상 입력하십시오.");
                        f.mb_password_re.focus();
                        return false;
                    }
                }

                // 이름 검사
                if (f.w.value=="") {
                    if (f.mb_name.value.length < 1) {
                        alert("이름을 입력하십시오.");
                        f.mb_name.focus();
                        return false;
                    }
                }

                <?php if($w == '' && $config['cf_cert_use'] && $config['cf_cert_req']) { ?>
                // 본인확인 체크
                if(f.cert_no.value=="") {
                    alert("회원가입을 위해서는 본인확인을 해주셔야 합니다.");
                    return false;
                }
                <?php } ?>

                // 닉네임 검사
                if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
                    var msg = reg_mb_nick_check();
                    msg  = msg.replace(/(\n|\r\n)/g, "");
                    if (msg) {
                        alert(msg);
                        f.reg_mb_nick.select();
                        return false;
                    }
                }

                // E-mail 검사
                if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
                    var msg = reg_mb_email_check();
                    msg  = msg.replace(/(\n|\r\n)/g, "");
                    if (msg) {
                        alert(msg);
                        f.reg_mb_email.select();
                        return false;
                    }
                }

                <?php if (($config['cf_use_hp'] || $config['cf_cert_hp']) && $config['cf_req_hp']) {  ?>
                // 휴대폰번호 체크
                if($("input[name=mb_type]:checked").val()==0) {
                    var msg = reg_mb_hp_check();
                    msg = msg.replace(/(\n|\r\n)/g, "");
                    if (msg) {
                        alert(msg);
                        $("#mb_hp2").focus();
                        return false;
                    }
                }
                <?php } ?>

                if($("input[name=mb_type]:checked").val()==1){
                    if($("#reg_mb_1").val()==""){
                        alert("회사명을 입력해주세요.");
                        return false;
                    }
                    
                    if($("#reg_mb_4").val()==""){
                        alert("직책을 입력해주세요.");
                        return false;
                    }

                    if($("#reg_mb_2").val()==""){
                        alert("대표자명을 입력해주세요.");
                        return false;
                    }

                    if($("#reg_mb_3_1").val() == "" || $("#reg_mb_3_2").val() == "" || $("#reg_mb_3_2").val() == ""){
                        alert("사업자 번호를 입력해주세요.");
                        return false;
                    }

                    if($("#company_auth").val()=="N"){
                        alert("사업자 인증이 필요합니다.");
                        return false;
                    }

                    if($("#mb_tel2").val()==""){
                        alert("회사번호를 입력해주세요.");
                        return false;
                    }
                    if($("#mb_tel3").val()==""){
                        alert("회사번호를 입력해주세요.");
                        return false;
                    }
                }

                if (typeof f.mb_icon != "undefined") {
                    if (f.mb_icon.value) {
                        if (!f.mb_icon.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                            alert("회원아이콘이 이미지 파일이 아닙니다.");
                            f.mb_icon.focus();
                            return false;
                        }
                    }
                }

                if (typeof f.mb_img != "undefined") {
                    if (f.mb_img.value) {
                        if (!f.mb_img.value.toLowerCase().match(/.(gif|jpe?g|png)$/i)) {
                            alert("회원이미지가 이미지 파일이 아닙니다.");
                            f.mb_img.focus();
                            return false;
                        }
                    }
                }

                if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
                    if (f.mb_id.value == f.mb_recommend.value) {
                        alert("본인을 추천할 수 없습니다.");
                        f.mb_recommend.focus();
                        return false;
                    }

                    var msg = reg_mb_recommend_check();
                    msg  = msg.replace(/(\n|\r\n)/g, "");
                    if (msg) {
                        alert(msg);
                        f.mb_recommend.select();
                        return false;
                    }
                }

                <?php /*echo chk_captcha_js();  */?>

                document.getElementById("btn_submit").disabled = "disabled";

                return true;
            }
            function fnIdchk(){
                var mb_id = $("#reg_mb_id").val();
                if(mb_id==""){
                    $("#msg_mb_id").html("아이디를 입력해주세요.");
                    setTimeout(function(){$("#msg_mb_id").html('');},1000);
                    return false;
                }
                $.ajax({
                    url:g5_url+"/page/modal/ajax.mb_id_chk.php",
                    method:"post",
                    data:{mb_id:mb_id},
                    dataType:"json"
                }).done(function (data) {
                    $("#reg_mb_id_chk").val(data.chk);
                    if(data.chk=="Y"){
                        $("#msg_mb_id").html("가입 가능한 아이디 입니다.");
                        setTimeout(function(){$("#msg_mb_id").html('');},1000);
                    }else{
                        $("#msg_mb_id").html("이미 가입 되어 있거나 가입 불가능한 아이디 입니다.");
                        setTimeout(function(){$("#msg_mb_id").html('');},1000);
                    }
                })
            }

            function fnTelCert(hp){
                var hps = hp.split("-");
                $("#mb_hp1").val(hps[0]);
                $("#mb_hp2").val(hps[1]);
                $("#mb_hp3").val(hps[2]);
            }

            function fnCompanyChk(){
                var com_num = $("#reg_mb_3_1").val()+$("#reg_mb_3_2").val()+$("#reg_mb_3_3").val();
                $.ajax({
                    url:g5_url+'/page/ajax/ajax.register_company_chk.php',
                    method:"post",
                    data:{com_num:com_num,com1:$("#reg_mb_3_1").val(),com2:$("#reg_mb_3_2").val(),com3:$("#reg_mb_3_3").val()},
                    dataType:'json'
                }).done(function(data){
                    console.log(data);
                    if(data.message){
                        alert(data.message);
                    }else{
                        if(data.state=="normal"){
                            alert("사업자 인증이 완료 되었습니다.");
                            $("#company_auth").val("Y");
                        }else{
                            alert("현재 운영 중이 아닌 사업자 입니다.");
                            $("#company_auth").val("N");
                        }
                    }
                })
            }
        </script>
    </div>
</div>
<!-- } 회원정보 입력/수정 끝 -->