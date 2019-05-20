<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
$menu_id = "depth_desc_edit";
include_once (G5_PATH."/page/mypage/confirm.php");
include_once (G5_PATH."/_head.php");
if(!$chk){
    alert("비밀번호 인증이 필요합니다.",G5_URL."/page/mypage/edit_profile_chkpwd");
}

$mb_3 = explode("-",$member["mb_3"]);
$tel = explode("-",$member["mb_tel"]);
$hp = explode("-",$member["mb_hp"]);
$email = explode("@",$member["mb_email"]);

add_javascript(G5_POSTCODE_JS, 0);
?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2 onclick="location.href=g5_url+'/page/mypage/mypage'">MY C.MAP</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout"><span></span>로그아웃</a>
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
                    <!--<li onclick="location.href=g5_url+'/page/mypage/guide'"><i></i>사용자 가이드 설정</li>-->
                    <li class="active"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>개인정보 수정</h2>
            </header>
            <div class="register_edit">
                <ul>
                    <li>고객님의 주소와 연락처 등 개인정보를 수정하실 수 있습니다.</li>
                    <li>휴대전화번호와 이메일은 한번 더 확인하시어, 정보를 제공해 드리는 SMS, 메일서비스 혜택을 받으시기 바랍니다.</li>
                </ul>
                <h2>
                    <span></span> 나의 정보관리
                </h2>
                <div class="profile_form">
                    <form action="<?php echo G5_URL;?>/page/mypage/update_profile" name="edit_form" method="post">
                        <input type="hidden" name="mb_id" value="<?php echo $member["mb_id"];?>">
                        <table>
                            <tr>
                                <th>성명 <span>*</span></th>
                                <td>
                                    <input type="text" name="mb_name" id="reg_mb_name" value="<?php echo $member["mb_name"];?>" required class="basic_input01 width70">
                                </td>
                            </tr>
                            <tr>
                                <th>회사명</th>
                                <td>
                                    <input type="text" name="mb_1" id="reg_mb_1" value="<?php echo $member["mb_1"];?>" class="basic_input01 width70">
                                </td>
                            </tr>
                            <tr>
                                <th>직책</th>
                                <td>
                                    <input type="text" name="mb_4" id="reg_mb_4" value="<?php echo $member["mb_4"];?>" class="basic_input01 width70">
                                </td>
                            </tr>
                            <tr>
                                <th>대표자</th>
                                <td>
                                    <input type="text" name="mb_2" id="reg_mb_2" value="<?php echo $member["mb_2"];?>" class="basic_input01 width70">
                                </td>
                            </tr>
                            <tr>
                                <th>사업자 등록번호</th>
                                <td>
                                    <input type="text" name="mb_3[]" id="mb_3_1" value="<?php echo $mb_3[0];?>" class="basic_input01 width20">
                                    -
                                    <input type="text" name="mb_3[]" id="mb_3_2" value="<?php echo $mb_3[1];?>" class="basic_input01 width20">
                                    -
                                    <input type="text" name="mb_3[]" id="mb_3_3" value="<?php echo $mb_3[2];?>" class="basic_input01 width20">
                                </td>
                            </tr>
                            <tr>
                                <th>회사 전화번호</th>
                                <td>
                                    <select name="mb_tel[]" id="mb_tel1" class="basic_input01 left_input width20" >
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
                                    </select>
                                    -
                                    <input type="text" name="mb_tel[]" id="mb_tel2" value="<?php echo $tel[1];?>" class="basic_input01 width20" maxlength="4">
                                    -
                                    <input type="text" name="mb_tel[]" id="mb_tel3" value="<?php echo $tel[2];?>" class="basic_input01 width20" maxlength="4">
                                </td>
                            </tr>
                            <tr>
                                <th>회사 주소</th>
                                <td>
                                    <input type="text" name="mb_zip" id="reg_mb_zip" value="<?php echo $member["mb_zip1"];?>" class="basic_input01 mbtm10" readonly>
                                    <button type="button" class="basic_btn02" onclick="win_zip('edit_form', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
                                    <input type="text" name="mb_addr1" id="reg_mb_addr1" value="<?php echo $member["mb_addr1"];?>" class="basic_input01 width70 mbtm10" readonly><br>
                                    <input type="text" name="mb_addr2" id="reg_mb_addr2" value="<?php echo $member["mb_addr2"];?>" class="basic_input01 width70">
                                    <input type="hidden" name="mb_addr3" id="reg_mb_addr3" value="<?php echo $member["mb_addr3"];?>" class="basic_input01">
                                    <input type="hidden" name="mb_addr_jibeon" id="reg_mb_addr_jibeon" value="<?php echo $member["mb_addr_jibeon"];?>" class="basic_input01">
                                </td>
                            </tr>
                            <tr>
                                <th>본인인증 <span>*</span></th>
                                <td class="cert">
                                    <div>
                                        <input type="text" name="mb_email" id="mb_email" value="<?php echo $email[0];?>" class="basic_input01 width20">
                                        @
                                        <input type="text" name="mb_email2" id="mb_email2" value="<?php echo $email[1];?>" class="basic_input01 width20" >
                                        <select name="mb_email_sel" id="mb_email_sel" class="basic_input01" onchange="$('#mb_email2').val(this.value)">
                                            <option value="">직접입력</option>
                                            <option value="naver.com" <?php echo get_selected($email[0],"naver.com");?>>naver.com</option>
                                            <option value="gmail.com" <?php echo get_selected($email[0],"gmail.com");?>>gmail.com</option>
                                            <option value="hanmail.net" <?php echo get_selected($email[0],"hanmail.net");?>>hanmail.net</option>
                                            <option value="daum.net" <?php echo get_selected($email[0],"daum.net");?>>daum.net</option>
                                        </select><br>
                                        <input type="checkbox" name="mb_mailling" id="reg_mb_mailling" <?php echo get_checked($member["mb_mailling"],"1");?> value="1">
                                        <label for="reg_mb_mailling"><span></span> 이메일을 통해 C.MAP의 다양한 정보를 받아보겠습니다.</label>
                                    </div>
                                    <div>
                                        <select name="mb_hp[]" id="mb_hp1" class="basic_input01 left_input width20" >
                                            <option value="010" <?php echo get_selected($hp[0],"010");?>>010</option>
                                            <!--<option value="017">017</option>
                                            <option value="018">018</option>
                                            <option value="019">019</option>-->
                                            <option value="070" <?php echo get_selected($hp[0],"070");?>>070</option>
                                        </select>
                                        -
                                        <input type="text" name="mb_hp[]" id="mb_hp2" value="<?php echo $hp[1];?>" class="basic_input01 width20" maxlength="4">
                                        -
                                        <input type="text" name="mb_hp[]" id="mb_hp3" value="<?php echo $hp[2];?>" class="basic_input01 width20" maxlength="4">
                                        <input type="button" value="인증하기" class="basic_btn01 bg_gray" onclick="alert('준비중입니다.')">
                                        <br>
                                        <input type="checkbox" name="mb_sms" id="reg_mb_sms" <?php echo get_checked($member["mb_sms"],"1");?> value="1">
                                        <label for="reg_mb_sms"><span></span> SMS를 통해 C.MAP의 다양한 정보를 받아보겠습니다.</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>비밀번호 확인 <span>*</span></th>
                                <td>
                                    <input type="password" name="mb_password" id="reg_mb_password" value="" required class="basic_input01">
                                    <label for="reg_mb_password">정보를 수정 하시려면 기존 비밀번호를 입력 하시기 바랍니다.</label>
                                </td>
                            </tr>
                        </table>
                        <div class="mypage_btns">
                            <input type="submit" class="basic_btn01 width20" value="나의정보 수정">
                        </div>
                    </form>
                    <form action="<?php echo G5_URL;?>/page/mypage/password_update" method="post" name="password_form" onsubmit="return fnPass();">
                        <input type="hidden" name="pass_confirm" id="pass_confirm" value="N">
                        <table>
                            <tr>
                                <th>기존 비밀번호 <span></span></th>
                                <td><input type="password" name="mb_password" id="reg_mb_password" required class="basic_input01 width50"></td>
                            </tr>
                            <tr>
                                <th>새 비밀번호 <span></span></th>
                                <td>
                                    <input type="password" name="new_mb_password" id="reg_new_mb_password" required class="basic_input01 width50">
                                    <span class="pwd_msg">비밀번호는 공백없는 8~16자의 영문/숫자를 조합하여 입력</span>
                                </td>
                            </tr>
                            <tr>
                                <th>새 비밀번호 확인 <span></span></th>
                                <td>
                                    <input type="password" name="new_mb_password_confirm" id="reg_new_mb_password_confirm" required class="basic_input01 width50">
                                    <span class="chkpwd_msg">비밀번호 확인을 위해 한 번 더 입력해 주시기 바랍니다.</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <p><strong>주의하세요!</strong></p>
                                    <p>아이디와 같은 비밀번호나 주민등록번호, 생일, 학번, 전화번호 등 개인정보와 관련된 숫자나 연속된 숫자, 통일 반복된 숫자 등<br>
                                        다른 사람이 쉽게 알아 낼 수 있는 비밀번호는 사용하지 않도록 주의하시기 바랍니다.</p>
                                </td>
                            </tr>
                        </table>
                        <div class="mypage_btns">
                            <input type="submit" class="basic_btn01 width20" value="비밀번호 수정">
                        </div>
                    </form>
                </div>
            </div>
        </article>
    </section>
</div>
<script>
    $(function(){
        $("#reg_new_mb_password").keyup(function(){
            var pwd = $(this).val();
            var num = pwd.search(/[0-9]/g);

            var eng = pwd.search(/[a-z]/ig);

            var spe = pwd.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

            if(pwd.length < 8 || pwd.length > 20){
                $(".pwd_msg").html("8자리 ~ 20자리 이내로 입력해주세요.");
            }
            else if(pwd.search(/₩s/) != -1){
                $(".pwd_msg").html("비밀번호는 공백업이 입력해주세요.");
            }
            else if(num < 0 || eng < 0 || spe < 0 ){
                $(".pwd_msg").html("영문,숫자, 특수문자를 혼합하여 입력해주세요.");
            }else{
                $(".pwd_msg").html("비밀번호 확인");
                var pwd = $("#reg_new_mb_password").val();
                var chkpwd = $("#reg_new_mb_password_confirm").val();
                if(pwd != chkpwd){
                    $(".chkpwd_msg").html("새비밀번호와 다릅니다.");
                    $("#pass_confirm").val("N");
                }else{
                    $(".chkpwd_msg").html("비밀번호 확인");
                    $("#pass_confirm").val("Y");
                }
            }
        });
        $("#reg_new_mb_password_confirm").keyup(function(){
            var pwd = $("#reg_new_mb_password").val();
            var chkpwd = $(this).val();
            if(pwd != chkpwd){
                $(".chkpwd_msg").html("새비밀번호와 다릅니다.");
                $("#pass_confirm").val("N");
            }else{
                $(".chkpwd_msg").html("비밀번호 확인");
                $("#pass_confirm").val("Y");
            }
        });
    });

    function fnPass(){
        if($("#pass_confirm").val()=="N"){
            alert("입력한 비밀번호가 서로 다릅니다.");
            return false;
        }
        return true;
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
