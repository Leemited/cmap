<?php
include_once ("../../common.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login");
}

/*if($mb_password=="" || !$mb_password){
    alert("패스워드를 입력해 주세요.");
    return false;
}*/

$mb_3 = implode('-',$mb_3);
$mb_tel = implode('-',$mb_tel);
$mb_hp = implode('-',$mb_hp);

$sql = "update `g5_member` set
          mb_name = '{$mb_name}',
          mb_1 = '{$mb_1}',
          mb_2 = '{$mb_2}',
          mb_3 = '{$mb_3}',
          mb_4 = '{$mb_4}',
          mb_9 = '{$mb_9}',
          mb_tel = '{$mb_tel}',
          mb_zip1 = '{$mb_zip}',
          mb_addr1 = '{$mb_addr1}',
          mb_addr2 = '{$mb_addr2}',
          mb_addr3 = '{$mb_addr3}',
          mb_addr_jibeon = '{$mb_addr_jibeon}',
          mb_email = '{$mb_email}',
          mb_hp = '{$mb_hp}'
        where mb_id = '{$mb_id}'
        ";

// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/member/'.substr($mb_id,0,2));
@chmod(G5_DATA_PATH.'/member/'.substr($mb_id,0,2));

$dir = G5_DATA_PATH.'/member/'.substr($mb_id,0,2);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

if($_FILES["mb_8"]["tmp_name"]){
    $filename = $_FILES["mb_8"]["name"];
    $tmp_file = $_FILES["mb_8"]["tmp_name"];

    if (is_uploaded_file($tmp_file)) {
        //파일 사이즈 체크
        $size = getimagesize($filename);
        if($size[0]!=$size[1]){
            alert("파일 가로와 높이는 1:1로 올려주시기 바랍니다.");
            return false;
        }
        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $upload_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

        $dest_file = $dir."/".$upload_file;

        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['mb_8']['error'][$i]);

        // 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file, G5_FILE_PERMISSION);
    }
}

if($_FILES["mb_7"]["tmp_name"]){
    $filename2 = $_FILES["mb_7"]["name"];
    $tmp_file2 = $_FILES["mb_7"]["tmp_name"];

    if (is_uploaded_file($tmp_file2)) {
        //파일 사이즈 체크
        $size = getimagesize($filename2);
        if($size[0]!=$size[1]){
            alert("파일 가로와 높이는 1:1로 올려주시기 바랍니다.");
            return false;
        }
        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename2 = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename2);

        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $upload_file2 = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename2);

        $dest_file2 = $dir."/".$upload_file2;

        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmp_file2, $dest_file2) or die($_FILES['mb_7']['error'][$i]);

        // 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file2, G5_FILE_PERMISSION);
    }
}

if(sql_query($sql)){
    if($upload_file){
        $sql = "update `g5_member` set mb_8 = '{$upload_file}' where mb_id = '{$mb_id}' ";
        sql_query($sql);
    }
    if($upload_file2){
        $sql = "update `g5_member` set mb_7 = '{$upload_file2}' where mb_id = '{$mb_id}' ";
        sql_query($sql);
    }
    alert("수정 되었습니다.");
}else{
    alert("정보가 잘못되어 수정을 하지 못하였습니다. \\r다시 시도해주세요.");
}

?>