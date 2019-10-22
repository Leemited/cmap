<?php
include_once ("./_common.php");

$mem = explode(",",$members);

for($i=0;$i<count($mem);$i++){
    $mbs = explode("||",$mem[$i]);
    if($mb_id == $mbs[0]) {
        $mem[$i] = $mbs[0] . "||" . $mbs[1] . "||2";
    }
}

$inmem = implode(",",$mem);

$sql = "update `cmap_inquiry` set payments_mb_id = '{$inmem}' where id = '{$id}'";

if(sql_query($sql)){
    alert("반려가 정상처리되었습니다.");
}else{
    alert("반려처리가 실패하였습니다.");
}

?>