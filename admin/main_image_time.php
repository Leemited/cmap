<?php
include_once ("./_common.php");

if($slide_time==""){
    alert("적용할 시간을 선택해 주세요.");
    return false;
}

$sql = "update `mainslide_time` set slide_time = '{$slide_time}' where id = 1";
sql_query($sql);

alert("변경 완료");

?>