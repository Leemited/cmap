<?php
include_once ("../../common.php");

$result["data"]=$_REQUEST;

if($members!="") {
    $inmem = explode(",", $members);
    $in = false;
    $result["cntss"] = count($inmem);
    if(count($inmem) >= 1) {
        for ($i = 0; $i < count($inmem); $i++) {
            if($mb_id==$inmem[$i]){
                if($chk=="false"){
                    $inmem[$i] = "";
                }
                $in = false;
                continue;
            }else{
                if($chk=="false"){
                    $in = false;
                    continue;
                }else{
                    $in = true;
                }
            }
        }

        $result["in"] = $in;

        if($in==true){
            $inmem[] = $mb_id;
        }

        $inmem2 = array_filter($inmem);
        $inmem3 = array_values($inmem2);

        for($i=0;$i<count($inmem3);$i++){
            $mbs = get_member($inmem3[$i]);
            $mb_ids[] = $inmem3[$i];
            $remembers[] = $mbs["mb_1"]."".$mbs["mb_4"]."".$mbs["mb_name"];
        }
    }else{
        if($members == $mb_id && $chk == false){
            $remembers[] = "";
        }
    }
}else{
    $mbs = get_member($mb_id);
    $mb_ids[] = $mb_id;
    $remembers[] = $mbs["mb_1"]."".$mbs["mb_4"]."".$mbs["mb_name"];
}

$result["add_id"]= implode(",",$mb_ids);
$result["add_member"] = implode("&nbsp;&nbsp;",$remembers);

echo json_encode($result);