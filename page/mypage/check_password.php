<?php
include_once ("../../common.php");

if(!$is_member || $member["mb_id"] == ""){
    alert("로그인이 필요합니다.", G5_BBS_URL."/login.php?url=".G5_URL."/page/mypage/edit_profile_chkpwd.php");
    return false;
}else{
    $mb = get_member($member["mb_id"]);
}

if($mb_password=="" || !$mb_password){
    alert("패스워드를 입력해 주세요.");
    return false;
}

//소셜 로그인추가 체크

$is_social_login = false;
$is_social_password_check = false;

// 소셜 로그인이 맞는지 체크하고 해당 값이 맞는지 체크합니다.
if(function_exists('social_is_login_check')){
    $is_social_login = social_is_login_check();

    //패스워드를 체크할건지 결정합니다.
    //소셜로그인일때는 체크하지 않고, 계정을 연결할때는 체크합니다.
    $is_social_password_check = social_is_login_password_check($mb["mb_id"]);
}

//소셜 로그인이 맞다면 패스워드를 체크하지 않습니다.
// 가입된 회원이 아니다. 비밀번호가 틀리다. 라는 메세지를 따로 보여주지 않는 이유는
// 회원아이디를 입력해 보고 맞으면 또 비밀번호를 입력해보는 경우를 방지하기 위해서입니다.
// 불법사용자의 경우 회원아이디가 틀린지, 비밀번호가 틀린지를 알기까지는 많은 시간이 소요되기 때문입니다.
if (!$is_social_password_check && (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password'])) ) {
    alert('가입된 회원아이디가 아니거나 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}


goto_url(G5_URL."/page/mypage/edit_profile.php?chk=true&w=u&mb_id=".$mb["mb_id"]);



