<?php
include_once ("../../common.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login");
}

if($mb_password=="" || !$mb_password){
    alert("기존패스워드를 입력해 주세요.");
    return false;
}

if($new_mb_password=="" || !$new_mb_password){
    alert("새패스워드를 입력해 주세요.");
    return false;
}

$mb = get_member($member["mb_id"]);

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

if (!$is_social_password_check && (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password'])) ) {
    alert('기본 비밀번호가 틀립니다.\\n비밀번호는 대소문자를 구분합니다.');
}

$sql = "update `g5_member` set mb_password = password('{$new_mb_password}') where mb_id ='{$mb["mb_id"]}'";

if(sql_query($sql)){
    session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    session_destroy(); // 세션해제함
    alert("수정되었습니다. 보안을 위해 재로그인 바랍니다.",G5_BBS_URL."/login");
}else{
    alert("다시 시도해 주세요.");
}