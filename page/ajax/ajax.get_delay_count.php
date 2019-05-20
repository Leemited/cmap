<?php
include_once ("../../common.php");

//제출 지연 현황
$delay_now = date("Y-m-d");
if(!$const_id){
    $result["cnt"]=0;
}else {
    $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$const_id}'";
    $activechk = sql_fetch($activesql);
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