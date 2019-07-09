<?php
include_once ("./_common.php");

$fa_order = ($_REQUEST["fa_order"])?$_REQUEST["fa_order"]:0;


if(trim(strip_tags($fa_subject))==""){
    alert("질문을 입력해주세요.");
}

if(trim(strip_tags($fa_content))==""){
    alert("답변을 입력해주세요.");
}

$sql_common = " fa_subject = '$fa_subject',
                fa_content = '$fa_content',
                fa_order = '$fa_order' ";

if($fa_id){
    $sql = "update `g5_faq` set {$sql_common} where fa_id = '{$fa_id}'";
}else{
    $sql = "insert into `g5_faq` set {$sql_common} , fm_id = '{$fm_id}'";
}

if(sql_query($sql)){
    if($fa_id){
        alert("수정되었습니다", G5_URL.'/admin/faq');
    }else{
        alert("등록되었습니다.", G5_URL.'/admin/faq');
    }
}else{
    alert("등록 오류입니다. 다시 시도해 주세요.");
}

?>

