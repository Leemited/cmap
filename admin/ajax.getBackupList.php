<?php

include_once ("./_common.php");

$dir = G5_PATH."/admin/backup";

// 핸들 획득
$handle  = opendir($dir);

$files = array();

// 디렉터리에 포함된 파일을 저장한다.
while (false !== ($filename = readdir($handle))) {
    if($filename == "." || $filename == ".."){
        continue;
    }

    // 파일인 경우만 목록에 추가한다.
    if(is_file($dir . "/" . $filename)){
        $files[] = $filename;
    }
}

closedir($handle);

sort($files);
$i=0;
foreach ($files as $f){
    $name = explode("-",$f);
    echo "<li id='restore_".$i."'>".$name[2]."_백업<input type='button' value='복구' onclick=\"fnRestoreItem('".$f."')\"><input type='button' value='삭제' onclick=\"fnRestoreDel('".$f."','".$i."')\" class='del' style='background-color: red'></li>";
    $i++;
}