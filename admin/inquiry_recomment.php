<?php
include_once ("./_common.php");
include_once(G5_LIB_PATH.'/mailer.lib.php');
if(!$recomment_title){
    alert("답변제목을 입력해주세요.");
}

if(!trim(strip_tags($recomment_content))){
    alert("답변내용을 입력해주세요");
}

$sql = "update `cmap_inquiry` set recomment_title = '{$recomment_title}' , recomment_content = '{$recomment_content}', recomment_date = now(), recomment_time = now() where id = '{$id}'";
if(sql_query($sql)){
    $content = "";

    $content .= '<div style="margin:0 auto;width:600px;border:10px solid #f7f7f7">';
    $content .= '<div style="border:1px solid #dedede">';
    $content .= '<h1 style="padding:30px 30px;background:#f7f7f7;color:#555;font-size:1.4em;margin:0">';
    $content .= '제안하기 안내';
    $content .= '</h1>';
    $content .= '<span style="display:block;padding:10px 30px 30px;background:#f7f7f7;text-align:right">';
    $content .= '<a href="'.G5_URL.'" target="_blank">'.$config['cf_title'].'</a>';
    $content .= '</span>';
    $content .= '<div style="padding:20px"> 제목 : '.$recomment_title.'</div>';
    $content .= '<div style="padding:20px">'.$recomment_content.'</div>';
    $content .= '<div style="padding:20px;background-color:#dedede;font-size:14px;text-align: center">';
    $content .= "건설관리지도 | 대표자 : 이지연 | 사업자번호 : 398-18-00805 | TEL : 055-763-7222";
    $content .= '</div>';
    $content .= '</div>';
    $content .= '</div>';
    mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $send_email, $recomment_title, $content, 1);

    alert($send_email."로 이메일 전송 및 답변이 완료 되었습니다.");
}else{
    alert("답변오류입니다.");
}
?>