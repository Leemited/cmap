<?php
include_once ("../../common.php");

$members = explode(",",$mb_ids);

for($i=0;$i<count($members);$i++){
    $mbs = get_member($members[$i]);
    if($mbs["mb_9"]){
        $result["members"] .= $mbs["mb_9"]. '&nbsp;&nbsp;&nbsp;';
    }else {
        $result["members"] .= $mbs["mb_1"] . " " . $mbs["mb_4"] . " " . $mbs["mb_name"] . '&nbsp;&nbsp;&nbsp;';
    }
}

echo json_encode($result);