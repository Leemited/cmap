<?php
include_once ("../../common.php");
include_once (G5_LIB_PATH."/mailer.lib.php");

$path = G5_DATA_PATH."/inquiry/";

@mkdir($path,0777);
@chmod($path,0777);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));


if($_FILES["file"]["tmp_name"]){
    $tmp_name = $_FILES["file"]["tmp_name"];
    $filename = $_FILES["file"]["name"];
    $filename  = get_safe_filename($filename);

    if (is_uploaded_file($tmp_name)) {

        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $filename = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

        $dest_file = $path.$filename;

        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmp_name, $dest_file) or die($_FILES['file']['error']);

        // 올라간 파일의 퍼미션을 변경합니다.
        @chmod($dest_file, G5_FILE_PERMISSION);

        $upload["filename"] = $filename;
    }
}

if($upload["filename"]!=""){
    $where = " , filename = '{$upload["filename"]}' , ori_filename = '{$_FILES["file"]["name"]}'";
}

$email = $email."@".$email2;


$sql = "insert into `cmap_inquiry` set `name` = '{$name}', email = '{$email}', inquiry_type = '{$inquiry_type}', content = '{$content}', insert_date = now() {$where}";
if(sql_query($sql)){
    $file[] = attach_file($_FILES["file"]['name'],$_FILES["file"]["tmp_name"]);
    //관리자에게 메일 송부
    mailer($name,$email,$config['cf_admin_email'],$name."님의 ".$inquiry_type."입니다.",$content,1,$file);

    //사용자에게 등록 메일 송부
    //mailer($name,$email,$config['cf_admin_email'],$name."님의 ".$inquiry_type."입니다.",$content,1,$file);

    alert("정상 등록되었습니다. \\r등록한 이메일주소로 처리사항이 전송됩니다.");
}else{
    alert("잘못된 요청입니다. \\r다시 시도해 주세요.");
}


?>