<?php
include_once ("../../common.php");


if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login");
}

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

$mb_3 = implode('-',$mb_3);
$mb_tel = implode('-',$mb_tel);
$mb_hp = implode('-',$mb_hp);
$mb_email = $mb_email."@".$mb_email2;
$mb_mailling = ($_POST["mb_mailling"])?$_POST["mb_mailling"]:"0";
$mb_sms = ($_POST["mb_sms"])?$_POST["mb_sms"]:"0";

$sql = "update `g5_member` set
          mb_name = '{$mb_name}',
          mb_1 = '{$mb_1}',
          mb_2 = '{$mb_2}',
          mb_3 = '{$mb_3}',
          mb_tel = '{$mb_tel}',
          mb_zip1 = '{$mb_zip}',
          mb_addr1 = '{$mb_addr1}',
          mb_addr2 = '{$mb_addr2}',
          mb_addr3 = '{$mb_addr3}',
          mb_addr_jibeon = '{$mb_addr_jibeon}',
          mb_email = '{$mb_email}',
          mb_hp = '{$mb_hp}',
          mb_mailling = '{$mb_mailling}',
          mb_sms = '{$mb_sms}'
        where mb_id = '{$mb_id}'
        ";

if(sql_query($sql)){
    alert("수정 되었습니다.");
}else{
    alert("정보가 잘못되어 수정을 하지 못하였습니다. \\r다시 시도해주세요.");
}

?>