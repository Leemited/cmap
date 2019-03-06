<?php
include_once ("../../common.php");
if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php");
}

if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

if($mb_password=="" || !$mb_password){
    alert("패스워드를 입력해 주세요.");
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

$mb_hp = implode("-",$_POST["mb_hp"]);
$mb_email = $_POST["mb_email"]."@".$_POST["mb_email2"];


if($mb_types==0) {
    if ($mb["mb_hp"] != $mb_hp){
        alert("입력하신 휴대번호와 일치 하지 않습니다.");
    }
}else{
    if($mb["mb_email"] != $mb_email){
        alert("입력하신 이메일과 일치 하지 않습니다.");
    }
}

switch ($leave_content){
    case 0:
        $mb_7 = "이용빈도 낮음";
        break;
    case 1:
        $mb_7 = "개인정보유출 우려";
        break;
    case 2:
        $mb_7 = "기타";
        break;
}

// 회원탈퇴일을 저장
$date = date("Ymd");
$sql = " update {$g5['member_table']} set mb_leave_date = '{$date}', mb_7 = '{$mb_7}', mb_8 = '{$mb_leave_content}' where mb_id = '{$member['mb_id']}' ";
sql_query($sql);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

if (!$url)
    $url = G5_URL;

//소셜로그인 해제
if(function_exists('social_member_link_delete')){
    social_member_link_delete($member['mb_id']);
}

alert(''.$member['mb_nick'].'님께서는 '. date("Y년 m월 d일") .'에 회원에서 탈퇴 하셨습니다.', $url);