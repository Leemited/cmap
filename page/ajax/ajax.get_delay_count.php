<?php
include_once ("../../common.php");

//제출 지연 현황
$delay_now = date("Y-m-d");
if(!$const_id){
    $result["cnt"]=0;
}else {
    $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$const_id}'";
    $activechk = sql_fetch($activesql);
    if($activechk==null || $activechk["pk_ids"] == "" || $activechk["pk_ids_other"] == ""){
        //스케쥴 다시 세팅
        $sql = "select * from `cmap_myschedule` where construct_id = '{$const_id}' order by pk_id asc";
        $schRes = sql_query($sql);
        while($schRow = sql_fetch_array($schRes)){
            if($schRow["pk_id"]==""){continue;}
            $rowpk[] = $schRow["pk_id"];
            $counts = count(explode("``",$schRow["pk_id"]));
            for($i=0;$i<$counts;$i++) {
                $rowactive[] = "0";
                $rowactivedate[] = "0000-00-00";
            }
        }

        $inpk = implode("``",$rowpk);
        $inpk2 = implode(",",$rowpk);
        $inactive = implode("``",$rowactive);
        $inactivedate = implode("``",$rowactivedate);


        $sql = "select * from `cmap_content` where pk_id not in ('{$inpk2}')";
        $finres = sql_query($sql);
        while($row = sql_fetch_array($finres)){
            $sql = "select *,b.menu_status as menu_status from `cmap_depth1` as a left `cmap_menu` as b on a.me_code = b.menu_code where a.id = '{$row["depth1_id"]}'";
            $chk_menu = sql_fetch($sql);
            if($chk_menu["menu_status"]!=0){continue;}
            $map_other_pk[] = $row["pk_id"];
            $map_other_pk_active[] = "0";
            $map_other_pk_dates_active[] = "0000-00-00";
        }

        $map_pk_other = implode("``",$map_other_pk);
        $map_pk_active_other = implode("``",$map_other_pk_active);
        $map_pk_active_dates_other = implode("``",$map_other_pk_dates_active);

        if($activechk==null){
            $sql = "insert into `cmap_my_construct_map` set pk_ids = '{$inpk}', pk_actives = '{$inactive}', pk_actives_date = '{$inactivedate}', pk_ids_other = '{$map_pk_other}', pk_actives_other = '{$map_pk_active_other}',pk_actives_dates_other = '{$map_pk_active_dates_other}', mb_id = '{$member["mb_id"]}', const_id = '{$const_id}'";
        }else{
            $sql = "update `cmap_my_construct_map` set pk_ids = '{$inpk}', pk_actives = '{$inactive}', pk_actives_date = '{$inactivedate}', pk_ids_other = '{$map_pk_other}', pk_actives_other = '{$map_pk_active_other}',pk_actives_dates_other = '{$map_pk_active_dates_other}' where mb_id = '{$member["mb_id"]}' and const_id = '{$const_id}'";
        }
        sql_query($sql);
    }
    $map_pk_id = explode("``",$activechk["pk_ids"]);
    $map_pk_actives = explode("``",$activechk["pk_actives"]);
    $map_pk_actives_date = explode("``",$activechk["pk_actives_date"]);

    $delaysql = "select * from `cmap_myschedule` where construct_id = '{$const_id}' and schedule_date < '{$delay_now}' and pk_id <> '' order by schedule_date desc";
    $delayres = sql_query($delaysql);
    $a=0;
    while($delayrow = sql_fetch_array($delayres)){
        $pk_ids = explode("``",$delayrow["pk_id"]);

        $diff = strtotime($delay_now) - strtotime($delayrow["schedule_date"]);

        $days = $diff / (60*60*24);
        for($i=0;$i<count($pk_ids);$i++){
            for($j=0;$j<count($map_pk_id);$j++){
                if($pk_ids[$i]==$map_pk_id[$j]){
                    if($map_pk_actives[$j]==0){
                        $sql = "select *,d.pk_id as pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                        $ddd = sql_fetch($sql);
                        if(strpos($id,$ddd["pk_id"])!==false) {
                            continue;
                        }
                        $id .= ','.$ddd["pk_id"];
                        $delaylist[$pk_ids[$i]] = $ddd;
                        $delaylist[$pk_ids[$i]]["delay_date"] = "-".$days;
                        $delayhead[$ddd["depth1_pk_id"]] = true;
                    }
                }
            }
        }
    }
}

$result["cnt"] = count($delaylist);
echo json_encode($result);
?>