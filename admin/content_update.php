<?php
include_once ("./_common.php");

if(!$content_id){
    $result["msg"] = "등록할 정보가 올바르지 않습니다.";
    echo json_encode($result);
    return false;
}

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/cmap_content', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/cmap_content', G5_DIR_PERMISSION);

$sql = "select * from `cmap_content` where id = '{$content_id}'";
$filechk = sql_fetch($sql);
$orifile_name = explode(",",$filechk["attachment"]);
$orifile_name2 = explode(",",$filechk["attachment2"]);

//$result["orifile2"] = $orifile_name;

//파일 삭제
if($fileDel1){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[0]);
    $orifile_name[0] = "";
}
if($fileDel2){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[1]);
    $orifile_name[1] = "";
}
if($fileDel3){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[2]);
    $orifile_name[2] = "";
}

//파일 삭제
if($fileDel4){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[0]);
    $orifile_name2[0] = "";
}
if($fileDel5){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[1]);
    $orifile_name2[1] = "";
}
if($fileDel6){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[2]);
    $orifile_name2[2] = "";
}

$uplink_title = implode(",",array_filter($linkname));
$uplink = implode(",",array_filter($link));
$upetcname = implode(",",array_filter($etc1name));
$upetc = implode(",",array_filter($etc1));

for ($i=0; $i<3; $i++) {
    if($_FILES["file"]["tmp_name"][$i]!=""){
        $tmp_name = $_FILES["file"]["tmp_name"][$i];
        $filename = $_FILES["file"]["name"][$i];
        $filename  = get_safe_filename($filename);

        if (is_uploaded_file($tmp_name)) {

            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
            $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);

            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
            $filename = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

            $dest_file = G5_DATA_PATH . '/cmap_content/' . $filename;

            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
            $error_code = move_uploaded_file($tmp_name, $dest_file) or die($_FILES['file']['error'][$i]);

            // 올라간 파일의 퍼미션을 변경합니다.
            chmod($dest_file, G5_FILE_PERMISSION);

            $upload["filename"][$i] = $filename;
        }
    }
}


for ($i=0; $i<3; $i++) {
    if($_FILES["files"]["tmp_name"][$i]!=""){
        $tmp_name = $_FILES["files"]["tmp_name"][$i];
        $filename = $_FILES["files"]["name"][$i];
        $filename  = get_safe_filename($filename);
        $ext = explode(".",$filename);

        if (is_uploaded_file($tmp_name)) {

            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
            $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);

            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
            $filename = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

            $dest_file = G5_DATA_PATH . '/cmap_content/' . $filename;

            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
            $error_code = move_uploaded_file($tmp_name, $dest_file) or die($_FILES['files']['error'][$i]);

            // 올라간 파일의 퍼미션을 변경합니다.
            chmod($dest_file, G5_FILE_PERMISSION);

            $upload["filenames"][$i] = $filename;
        }
    }
}


if(count($upload["filename"]) !=0 ){
    for($i=0;$i<3;$i++){
        $orifile_name[$i] = $upload["filename"][$i];
    }
}
$result["orifile"] = $orifile_name;
$filename = implode(",",$orifile_name);
$files = " , attachment = '{$filename}'";

if(count($upload["filenames"]) !=0 ){
    for($i=0;$i<3;$i++){
        $orifile_name2[$i] = $upload["filenames"][$i];
    }
}
$result["orifiles"] = $orifile_name2;
$filename2 = implode(",",$orifile_name2);
$files2 = " , attachment2 = '{$filename2}'";

$sql = "update `cmap_content` set link = '{$uplink}', linkname = '{$uplink_title}', etc1 = '{$upetc}', etcname1 = '{$upetcname}' {$files} {$files2} where id = '{$content_id}'";
$result["sql"]=$sql;
if(sql_query($sql)){
    $result["id"] = $content_id;
    $result["msg"] = "정상 처리되었습니다.";
    if(count($linkname)!=0){
        $result["linknames"] = $uplink_title;
    }
    if(count($link)!=0){
        $result["links"] = $uplink;
    }
    if(count($etc1name)!=0){
        $result["etc1names"] = $upetcname;
    }
    if(count($etc1)!=0){
        $result["etc1s"] = $upetc;
    }
    //if(count($upload["filename"])!=0){
    $result["filename"] = $filename;
    $result["filename2"] = $filename2;
}else{
    $result["msg"] = "등록 실패하였습니다. 다시 시도해 주세요.";
}
echo json_encode($result);