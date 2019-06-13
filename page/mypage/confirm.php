<?php

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php");
}

if($member["mb_password"]==""){
    alert("비밀번호 설정이 필요합니다.",G5_URL."/page/mypage/edit_profile?chk=true");
}
?>