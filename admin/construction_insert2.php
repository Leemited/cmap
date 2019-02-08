<?php
include_once ("./_common.php");
include_once (G5_LIB_PATH."/Excel/reader.php");

if(!$me_code){
    alert("선택된 메뉴가 없습니다.");
    return false;
}
if(!$me_name){
    alert("선택된 메뉴정보가 없습니다.");
    return false;
}

$me_id = substr($me_code,0,2);

ini_set('memory_limit','-1');

$path = G5_DATA_PATH."/excel/";

@mkdir($path, G5_DIR_PERMISSION);
@chmod($path, G5_DIR_PERMISSION);

if(!is_uploaded_file($_FILES['insert_file']['tmp_name'])){
    echo "Error_file";
}

// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
$error_code = move_uploaded_file($_FILES['insert_file']['tmp_name'], $path.$_FILES['insert_file']["name"]) or die($_FILES['insert_file']['error'][$i]);

$data = new Spreadsheet_Excel_Reader();

$data ->setOutputEncoding('UTF-8');

$data ->read(G5_DATA_PATH."/excel/".$_FILES['insert_file']["name"]);

error_reporting(E_ALL ^ E_NOTICE);

$cnt = count($data->sheets);
if($cnt > 1) {
    for ($j=0;$j<$cnt;$j++) {
        for ($i = 2; $i <= $data->sheets[$j]['numRows']+2; $i++) {
            $all['depth1'][] = $data->sheets[$j]['cells'][$i][1];
            $all['depth2'][] = $data->sheets[$j]['cells'][$i][2];
            $all['depth3'][] = $data->sheets[$j]['cells'][$i][3];
        }
    }
}else if($cnt==1){
    for ($i = 2; $i <= $data->sheets[0]['numRows']+2; $i++) {
        $all['depth1'][] = $data->sheets[0]['cells'][$i][1];
        $all['depth2'][] = $data->sheets[0]['cells'][$i][2];
        $all['depth3'][] = $data->sheets[0]['cells'][$i][3];
    }
}else{
    alert("엑셀 파일에 오류가 있습니다.\\r\\n다시 확인후 업로드 바랍니다.");
    return false;
}

for($i=0;$i<count($all["depth1"]);$i++){

    if($all["depth1"][$i]!="") {
        $sql = "select MAX(id) as max from `cmap_depth1`";
        $maxid = sql_fetch($sql);
        if($maxid["max"]==0){
            $first_id = 1;
        }else {
            $first_id = $maxid["max"] + 1;
        }
        $sql = "insert into `cmap_depth1` set id = '{$first_id}',depth_name = '{$all['depth1'][$i]}', menu_id = '{$menu_id}', me_code = '{$me_code}', me_name = '{$me_name}', me_id = '".$me_id."'";
        sql_query($sql);
    }

    if($all["depth2"][$i]!="") {
        $sql = "select MAX(id) as max from `cmap_depth2`";
        $maxid = sql_fetch($sql);
        if($maxid["max"]==0){
            $depth2_max_id = 1;
        }else {
            $depth2_max_id = $maxid["max"] + 1;
        }

        $sql = "insert into `cmap_depth2` set depth_name = '{$all['depth2'][$i]}', depth1_id = '{$first_id}', id = {$depth2_max_id}";
        sql_query($sql);
        //$depth2_id = sql_insert_id();
    }
    if($all['depth3'][$i]!=""){
        $sql = "select MAX(id) as max from `cmap_content`";
        $maxid = sql_fetch($sql);
        if($maxid["max"]==0){
            $depth5_max_id = 1;
        }else {
            $depth5_max_id = $maxid["max"] + 1;
        }

        $sql = "insert into `cmap_content` set content = '{$all['depth3'][$i]}', depth1_id = '{$first_id}', depth2_id = '{$depth2_max_id}', id = {$depth5_max_id}";
        sql_query($sql);
        //$depth3_id = sql_insert_id();
    }
}

alert('등록되었습니다.');

?>