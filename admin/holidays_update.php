<?php
include_once ("../common.php");

$sql = "select count(year) as cnt from `cmap_holidays` where `year` = '{$year}' ";
$chk = sql_fetch($sql);

//설날
$holidays2 = implode("~",$holidays2);
//추석
$holidays8 = implode("~",$holidays8);


if($chk["cnt"] > 0){
    $sql = "update `cmap_holidays` set 
              holidays1 = '{$holidays1}',
              holidays2 = '{$holidays2}',
              holidays3 = '{$holidays3}',
              holidays4 = '{$holidays4}',
              holidays5 = '{$holidays5}',
              holidays6 = '{$holidays6}',
              holidays7 = '{$holidays7}',
              holidays8 = '{$holidays8}',
              holidays9 = '{$holidays9}',
              holidays10 = '{$holidays10}',
              holidays11 = '{$holidays11}',
              holidays12 = '{$holidays12}'
            where `year` = '{$year};'
            ";
}else{
    $sql = "insert into `cmap_holidays` set 
              `year` = '{$year}',
              holidays1 = '{$holidays1}',
              holidays2 = '{$holidays2}',
              holidays3 = '{$holidays3}',
              holidays4 = '{$holidays4}',
              holidays5 = '{$holidays5}',
              holidays6 = '{$holidays6}',
              holidays7 = '{$holidays7}',
              holidays8 = '{$holidays8}',
              holidays9 = '{$holidays9}',
              holidays10 = '{$holidays10}',
              holidays11 = '{$holidays11}',
              holidays12 = '{$holidays12}'
            ";
}
if(sql_query($sql)){
    alert("등록 되었습니다.");
}else {
    alert("다시시도해 주세요.");
}
?>