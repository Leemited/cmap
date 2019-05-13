<?php
include_once ("./_common.php");

if(!$content_id){
    $result["msg"] = "등록할 정보가 올바르지 않습니다.";
    echo json_encode($result);
    return false;
}

$result["files"] = $_FILES;

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/cmap_content', G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/cmap_content', G5_DIR_PERMISSION);

$sql = "select * from `cmap_content` where pk_id = '{$content_id}'";
$result["sss"]=$sql;
$filechk = sql_fetch($sql);
$orifile_name = explode("``",$filechk["attachment"]);
$orifile_name2 = explode("``",$filechk["attachment2"]);
$ori_name = explode("``",$filechk["attachmentname"]);
$ori_name2 = explode("``",$filechk["attachmentname2"]);

$arrayFile1 = $_POST["filenames"];
$arrayFile2 = $_POST["filenames2"];

//파일 삭제

if($fileDel1=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[0]);
    $orifile_name[0] = "";
    $ori_name[0] = "";
    $arrayFile1[0] = "";
}
if($fileDel2=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[1]);
    $orifile_name[1] = "";
    $ori_name[1] = "";
    $arrayFile1[1] = "";
}
if($fileDel3=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[2]);
    $orifile_name[2] = "";
    $ori_name[2] = "";
    $arrayFile1[2] = "";
}

//파일 삭제
if($fileDel4=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[0]);
    $orifile_name2[0] = "";
    $ori_name2[0] = "";
    $arrayFile2[0] = "";
}
if($fileDel5=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[1]);
    $orifile_name2[1] = "";
    $ori_name2[1] = "";
    $arrayFile2[1] = "";
}
if($fileDel6=="1"){
    @unlink(G5_DATA_URL . '/cmap_content/' . $orifile_name[2]);
    $orifile_name2[2] = "";
    $ori_name2[2] = "";
    $arrayFile2[2] = "";
}
$filenames = implode("``",$arrayFile1);
$filenames2 = implode("``",$arrayFile2);

$uplink_title = implode("``",array_filter($linkname));
$uplink = implode("``",array_filter($link));
$upetcname = implode("``",array_filter($etc1name));
$upetc = implode("``",array_filter($etc1));

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

for ($i=0; $i<3; $i++) {
    $result["filesssss"] = $_FILES["file"]["tmp_name"][$i];
    if($_FILES["file"]["tmp_name"][$i] != ""){
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

            $file_ext = explode(".",$dest_file);

            //shell_exec("convert  xc:white -verbose -density 400 -trim ".$dest_file." -resize 100%x100% -quality 100 -sharpen 0x1.0 * ".$file_ext[0].".png");

            $upload["filename"][$i] = $filename;

            $result['filenamaaa'] = $filename;
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

            //shell_exec("convert  xc:white -verbose -density 300 -trim ".$dest_file." -resize 100%x100% -quality 100 -sharpen 0x1.0 * ".$file_ext[0].".png");

            $upload["filenames"][$i] = $filename;
        }
    }
}


if(count($upload["filename"]) !=0 ){
    for($i=0;$i<3;$i++){
        if($upload["filename"][$i]!="")
            $orifile_name[$i] = $upload["filename"][$i];
    }
    $result["orifile"] = $orifile_name;
    $filename = implode("``",$orifile_name);
    $files = " , attachment = '{$filename}'";
}else{
    $filename = $filechk["attachment"];
    $result["orifile"] = $orifile_name;
    $filename = implode("``",$orifile_name);
    $files = " , attachment = '{$filename}'";
}

if(count($upload["filenames"]) !=0 ){
    for($i=0;$i<3;$i++){
        if($upload["filenames"][$i]!="")
            $orifile_name2[$i] = $upload["filenames"][$i];
    }
    $result["orifiles"] = $orifile_name2;
    $filename2 = implode("``",$orifile_name2);
    $files2 = " , attachment2 = '{$filename2}'";
}else{
    $filename2 = $filechk["attachment2"];;
    $result["orifiles"] = $orifile_name2;
    $filename2 = implode("``",$orifile_name2);
    $files2 = " , attachment2 = '{$filename2}'";
}

if($filenames == "````" || $filenames == "``"){
    $filenames = "";
    $filename = "";
}
if($filenames2 == "````" || $filenames2 == "``"){
    $filenames2 = "";
    $filename2 = "";
}

//$result["filename"] = $filenames;
$result["filename2"] = $filenames2;

$sql = "update `cmap_content` set link = '{$uplink}', linkname = '{$uplink_title}', etc1 = '{$upetc}', etcname1 = '{$upetcname}', attachmentname1	= '{$filenames}', attachmentname2 = '{$filenames2}' {$files} {$files2} where pk_id = '{$content_id}'";
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
    $result["file_names"] = $filenames;
    $result["file_names2"] = $filenames2;
    $result["filename"] = $filename;
    $result["filename2"] = $filename2;
}else{
    $result["msg"] = "등록 실패하였습니다. 다시 시도해 주세요.";
}
echo json_encode($result);