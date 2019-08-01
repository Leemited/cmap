<?php
include_once("../../common.php");
$coms = $com1."-".$com2.'-'.$com3;
$sql = "select count(*) as cnt from `g5_member` where mb_3 = '{$coms}' and mb_level = 6";
$chk = sql_fetch($sql);
if($chk["cnt"]>0){
    $arry["message"] = "이미 가입된 사업자 번호입니다.";
    echo json_encode($arry);
    return false;
}
$url = "https://business.api.friday24.com/closedown/".$com_num;

$key = "HNSzH04L6Enx4M0r8LYc";
$header[] = "Authorization: Bearer ".$key;

$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
curl_setopt($ch, CURLOPT_HEADER, 0);//헤더 정보를 보내도록 함(*필수)
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//헤더 정보
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
$res = curl_exec($ch);

curl_close($ch);

$res = str_replace("{","",$res);
$res = str_replace("}","",$res);
$res = str_replace("\"","",$res);
$res = str_replace("\\r","",$res);
$res = str_replace("\\n","",$res);
$res = trim($res);
$res = explode(",",$res);
for($i=0;$i<count($res);$i++){
    $data = explode(":",$res[$i]);
    $arry[$data[0]]=$data[1];
}
echo json_encode($arry);
?>