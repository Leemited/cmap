<?php
include_once ("../../common.php");

$constids = explode(",",$constids);

for($i=0;$i<count($constids);$i++){
    $sql = "select * from `cmap_my_construct` where id = '{$constids[$i]}'";
    $pmconst = sql_fetch($sql);

    $pm_managers = explode(",",$pmconst["manager_mb_id"]);
    $pm_new = "";
    for($j=0;$j<count($pm_managers);$j++){
        if($pm_managers[$j]==$member["mb_id"]){

        }else{
            if($pm_new){$pm_new.=",";}
            $pm_new .= $pm_managers[$j];
        }
    }
    $sql = "update `cmap_my_construct` set manager_mb_id = '{$pm_new}' where id = '{$constids[$i]}'";
    sql_query($sql);
}

goto_url(G5_URL."/page/manager/pm_construct");
?>