<?php
include_once ("./_common.php");

if($id){
    $sql = "update `cmap_mainimage` set main_text = '{$main_text}', sub_text = '{$sub_text}',insertDate=now(),insertTime=now() where id = '{$id}' ";
}else {
    $sql = "insert into `cmap_mainimage` set main_text = '{$main_text}', sub_text = '{$sub_text}',insertDate=now(),insertTime=now() ";
}
if(sql_query($sql)){

    if(!$id){
        $id = sql_insert_id();
    }

    $path = G5_DATA_PATH."/file/main";

    @mkdir($path, G5_DIR_PERMISSION);
    @chmod($path, G5_DIR_PERMISSION);
    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

    if($_FILES["main_image"]["tmp_name"]){
        if (is_uploaded_file($_FILES["main_image"]["tmp_name"])) {
            $filename = $_FILES["main_image"]["name"];
            $tmp_file = $_FILES["main_image"]["tmp_name"];
            // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
            $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

            shuffle($chars_array);
            $shuffle = implode('', $chars_array);

            // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
            $upload_file = abs(ip2long($_SERVER['REMOTE_ADDR'])) . '_' . substr($shuffle, 0, 8) . '_' . replace_filename($filename);

            $dest_file = $path."/".$upload_file;

            // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
            $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['main_image']['error'][$i]);

            // 올라간 파일의 퍼미션을 변경합니다.
            chmod($dest_file, G5_FILE_PERMISSION);
            if($upload_file){
                $sql = "update `cmap_mainimage` set main_image = '{$upload_file}' where id = '{$id}' ";
                sql_query($sql);
            }
        }
    }

    alert("등록완료",G5_URL."/admin/main_image");
}