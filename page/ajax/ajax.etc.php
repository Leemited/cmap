<?php
include_once ("../../common.php");

$sql = "select * from `cmap_content` where pk_id = '{$pk_id}'";
$etc = sql_fetch($sql);

if($etc["link"]!=""){
    $links = explode("``",$etc["link"]);
    $linknames = explode("``",$etc["linkname"]);
    echo "<h1>참고링크</h1>";
    for($i=0;$i<count($links);$i++){
        if($links[$i]!=""){
            echo "링크".$i." : " . "<a href='{$links[$i]}' target='_blank' >".$linknames[$i]."</a><br>";
        }
    }
}

if($etc["etc1"]!=""){
    $etcs = explode("``",$etc["etc1"]);
    $etcnames = explode("``",$etc["etcname1"]);
    echo "<h1>참고사례</h1>";
    for($i=0;$i<count($etcs);$i++){
        if($etcs[$i]!=""){
            echo "참고" .$i. " : <a href='".$etcs[$i]."' target='_blank' >".$etcnames[$i]."</a><br>";
        }
    }
}

if($etc["attachment"]!=""){
    $attachment = explode("``",$etc["attachment"]);
    $attachmentname = explode("``",$etc["attachmentname1"]);
    for($i=0;$i<count($attachment);$i++){
        if($attachment[$i]!=""){
            echo "참고파일 : " . $attachment[$i]."// 파일명 : ".$attachmentname[$i]."<br>";
        }
    }
}

if($etc["attachment2"]!=""){
    $attachment2 = explode("``",$etc["attachment2"]);
    $attachmentname2 = explode("``",$etc["attachmentname2"]);
    for($i=0;$i<count($attachment2);$i++){
        if($attachment2[$i]!=""){
            echo "사례파일 : " . $attachment2[$i]."// 파일명 : ".$attachmentname2[$i]."<br>";
        }
    }
}