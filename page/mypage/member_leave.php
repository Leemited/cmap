<?php
include_once ("../../common.php");
$sub="sub";
$mypage=true;
include_once (G5_PATH."/_head.php");
?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>MY C.MAP</h2>
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
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd.php'"><i></i>개인정보 수정</li>
                    <li class="active" ><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>회원 탈퇴</h2>
            </header>
            <div class="register_edit">
                <h3>그 동안 CMAP을 이용해주셔서 감사힙니다.</h3>
                <p>불편하였던 점이나 불만사항을 남겨주시면 더 좋은 모습으로 찾아 뵙기 위해 노력하겠습니다.</p>
                <h4>회원 탈퇴 안내</h4>
                <ul class="leaveul">
                    <li>회원 탈퇴 시 고객님의 정보는 전자상거래 등에서의 소비자 보호에 관한 법률에 의거한 C.MAP 고객정보 정책에 따라 관리됩니다.</li>
                    <li>한 번 탈퇴한 아이디는 다시 사용할 수 없습니다.</li>
                </ul>
                <h2><span></span>탈퇴사유 확인</h2>
                <form action="<?php echo G5_URL;?>/page/mypage/member_leave_update.php" method="post" name="leave_form">
                    <div class="profile_form mbtm30">
                        <table class="leave_table">
                            <tr>
                                <th>탈퇴 사유</th>
                                <td>
                                    <input type="radio" name="leave_content" id="not_used" value="0"><label for="not_used"> 이용빈도 낮음</label>
                                    <input type="radio" name="leave_content" id="privacy" value="1"><label for="privacy"> 개인정보유출 우려</label>
                                    <input type="radio" name="leave_content" id="etc" value="2" checked><label for="etc"> 기타</label>
                                </td>
                            </tr>
                            <tr class="mb_leave_etc">
                                <th>기타</th>
                                <td>
                                    <textarea name="mb_leave_content" id="mb_leave_content" cols="30" rows="10" class="basic_input01 width100">
                                    </textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <h2><span></span>본인 확인</h2>
                    <div class="profile_form mbtm30">
                        <table class="leave_table">
                            <tr>
                                <th>아이디</th>
                                <td><?php echo $member["mb_id"];?></td>
                            </tr>
                            <tr>
                                <th>비밀번호 <span>*</span></th>
                                <td><input type="password" name="mb_password" id="reg_mb_password" class="basic_input01 width30"></td>
                            </tr>
                            <tr>
                                <th rowspan="2">본인확인 <span>*</span></th>
                                <td>
                                     <p>회원정보에 등록된 휴대전화 또는 이메일 중 하나를 선택하여 입력하여 주세요.</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="mb_types" id="hp" value="0" checked><label for="hp" > 휴대전화</label>
                                    <input type="radio" name="mb_types" id="email" value="1"><label for="email"> 이메일</label>
                                    <span class="mb_types">
                                        <input type="text" name="mb_hp[]" id="reg_mb_hp1" class="basic_input01 width20" maxlength="3" required onkeyup="number_only(this);"> - <input type="text" name="mb_hp[]" id="reg_mb_hp2" class="basic_input01 width20" maxlength="4" required onkeyup="number_only(this);"> - <input type="text" name="mb_hp[]" id="reg_mb_hp3" class="basic_input01 width20"maxlength="4" required onkeyup="number_only(this);">
                                        <!--<input type="text" name="mb_email" id="reg_mb_email" class="basic_input01 width20" required> @ <input type="text" name="mb_email2" id="reg_mb_email2" class="basic_input01 width20" required>-->
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                <div class="mypage_btns">
                    <input type="submit" value="탈퇴하기" class="basic_btn01 width10">
                    <input type="button" value="취소하기" class="basic_btn02 width10" onclick="location.href=g5_url">
                </div>
                </form>
            </div>
        </article>
    </section>
</div>
<script>
    $(function(){
        var item = '<input type="text" name="mb_hp[]" id="reg_mb_hp1" class="basic_input01 width20" maxlength="3" required onkeyup="number_only(this);"> - <input type="text" name="mb_hp[]" id="reg_mb_hp2" class="basic_input01 width20" maxlength="4" required onkeyup="number_only(this);"> - <input type="text" name="mb_hp[]" id="reg_mb_hp3" class="basic_input01 width20"maxlength="4" required onkeyup="number_only(this);">';
        var item2 = '<input type="text" name="mb_email" id="reg_mb_email" class="basic_input01 width30" required> @ <input type="text" name="mb_email2" id="reg_mb_email2" class="basic_input01 width30" required>';
        $("input[name=mb_types]").click(function(){
            if($(this).val()==0){
                $(".mb_types").html(item);
            }else{
                $(".mb_types").html(item2);
            }
        });
        $("input[name=leave_content]").click(function(){
            if($(this).val() != 2) {
                $(".mb_leave_etc").css("display","none");
            }else{
                $(".mb_leave_etc").css("display","table-row");
            }
        })
    })
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
