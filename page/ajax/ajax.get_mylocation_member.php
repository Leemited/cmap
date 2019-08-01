<?php
include_once ("../../common.php");


$sql = "select * from `cmap_my_construct` where mb_id ='{$member["mb_id"]}' and id = '{$const_id}'";
$chkconst = sql_fetch($sql);

if($chkconst["members"]!=""){
    $mbs = explode(",",$chkconst["members"]);
}else{
    $result['msg']="1";
    echo json_encode($result);
    return false;
}

$result["msg"] = '0';
$result['modal_data'] = '
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 현장위임 및 삭제확인</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="'.G5_IMG_URL.'/close_icon.svg" alt="">
        </div>
    </div>
    <div class="confirm_con">
        <div>
            <ul class="member_list">';
for($i=0;$i<count($mbs);$i++){
        $mem = get_member($mbs[$i]);
    $result['modal_data'] .= '<li><input type="radio" name="mb_id[]" value="'.$mem["mb_id"].'" id="mb_id_'.$mem["mb_id"].'" ><label for="mb_id_'.$mem["mb_id"].'"></label><div>['.$mem["mb_id"].']'.$mem["mb_name"].'</div></li>';
}
$result['modal_data'].='</ul>' .
    '</div>' .
    '<div style="margin-top:20px">' .
        '<input type="text" name="delText" id="delText" placeholder="[지금삭제]를 입력해주세요." class="basic_input01 width100">'.
    '</div>' .
    '</div>' .
    '<div class="modal_btns">' .
        '<input type="button" class="modal_btn02 width30" value="취소" onclick="fnCloseModal()">' .
        '<input type="button" class="modal_btn01 width30" value="위임/삭제하기" onclick="fnConstDelete(\''.$const_id.'\')"></div>' .
    '</div>';
echo json_encode($result);
?>