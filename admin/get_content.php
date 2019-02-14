<?php
include_once ("_common.php");

if(!$pk_id){
    $result["status"]=0;
    echo json_encode($result);
    return false;
}

$sql = "select * from `cmap_content` where pk_id = '{$pk_id}'";
$result["sql"] = $sql;
if($con = sql_fetch($sql)){
    $linkname = explode("``",$con["linkname"]);
    for($i=0;$i<count($linkname);$i++){
        $result["linkname{$i}"] = $linkname[$i];
    }
    $link = explode("``",$con["link"]);
    for($i=0;$i<count($link);$i++){
        $result["link{$i}"] = $link[$i];
    }
    $etcname1 = explode("``",$con["etcname1"]);
    for($i=0;$i<count($etcname1);$i++){
        $result["etcname1_{$i}"] = $etcname1[$i];
    }
    $etc1 = explode("``",$con["etc1"]);
    for($i=0;$i<count($etc1);$i++){
        $result["etc1_{$i}"] = $etc1[$i];
    }
    $file = explode("``",$con["attachment"]);
    for($i=0;$i<count($file);$i++){
        $result["file{$i}"] = $file[$i];
    }
    $file2 = explode("``",$con["attachment2"]);
    for($i=0;$i<count($file2);$i++){
        $result["files{$i}"] = $file2[$i];
    }
    $filename1 = explode("``",$con["attachmentname1"]);
    for($i=0;$i<count($filename1);$i++){
        if($file[$i]!="") {
            $result["filename{$i}"] = $filename1[$i];
        }
    }
    $filename2 = explode("``",$con["attachmentname2"]);
    for($i=0;$i<count($filename2);$i++){
        if($file2[$i]!="") {
            $result["filesname{$i}"] = $filename2[$i];
        }
    }
    $result["status"] = 1;
}else{
    $result["status"] = 2;
}

echo json_encode($result);