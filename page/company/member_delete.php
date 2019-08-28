<?php
include_once ("../../common.php");

$sql = "select count(*)as cnt from `g5_member` where mb_id = '{$mb_id}' ";
$memchk = sql_fetch($sql);
if($memchk["cnt"]==0){
    alert("이미 삭제되었거나 없는 회원입니다.");
}

$sql = "delete from `g5_member` where mb_id = '{$mb_id}'";
if(sql_query($sql)){
    //삭제후 기타 정보들을 삭제 해야될지 추후 검토
    alert("회원정보가 삭제되었습니다.",G5_URL."/page/company/");
}else{
    alert("회원정보를 삭제하지 못했습니다.");
}
?>