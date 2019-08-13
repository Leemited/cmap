<?php
include_once ("../../common.php");

if(!$mb_id){
    echo "1";
    return false;
}

//제출 지연 현황
$delay_now = date("Y-m-d");
if(!$const_id){
    unset($getdelaylist);
}else {
    if($member["mb_level"]==5){
        $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$const_id}'";
        $ss = sql_fetch($sql);
        if($ss!=null){
            $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$const_id}'";
        }else{
            $sql = "select * from `cmap_my_construct` where id = '{$const_id}'";
            $ss2 = sql_fetch($sql);
            $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss2["mb_id"]}' and const_id = '{$const_id}'";
        }
    }else {
        $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$const_id}'";
    }
    $activechk = sql_fetch($activesql);
    $map_pk_id = explode("``",$activechk["pk_ids"]);
    $map_pk_actives = explode("``",$activechk["pk_actives"]);
    $map_pk_actives_date = explode("``",$activechk["pk_actives_date"]);

    $delaysql = "select * from `cmap_myschedule` where construct_id = '{$const_id}' and schedule_date < '{$delay_now}' and pk_id <> '' order by schedule_date desc";
    $delayres = sql_query($delaysql);
    while($delayrow = sql_fetch_array($delayres)){
        $pk_ids = explode("``",$delayrow["pk_id"]);

        $diff = strtotime($delay_now) - strtotime($delayrow["schedule_date"]);

        $days = $diff / (60*60*24);
        for($i=0;$i<count($pk_ids);$i++){
            for($j=0;$j<count($map_pk_id);$j++){
                if($pk_ids[$i]==$map_pk_id[$j]){
                    if($map_pk_actives[$j]==0){
                        $sql = "select *,d.pk_id as pk_id,c.depth1_id as depth1_id,a.pk_id as depth1_pk_id,c.depth2_id as depth2_id ,d.depth_name as depth_name,a.depth_name as depth1_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$pk_ids[$i]}'";
                        $ddds = sql_fetch($sql);
                        if(strpos($chcccid,$ddds["pk_id"])!==false) {
                            continue;
                        }
                        $chcccid .= ','.$ddds["pk_id"];
                        $getdelaylist[$pk_ids[$i]] = $ddds;
                        $getdelaylist[$pk_ids[$i]]["delay_date"] = "-".$days;
                    }
                }
            }
        }
    }
}
if(count($getdelaylist)>0) {
    $getdelaylist = array_values($getdelaylist);
    $getdelaylist = arr_sort($getdelaylist, "delay_date", "asc");
}
echo count($getdelaylist);
?>
<style>
    .detail_list{width: calc(100% + 5px);}
</style>
<table>
    <colgroup>
        <col width="70%">
        <col width="30%">
    </colgroup>
    <tr>
        <th>지연서류</th>
        <th>지연일</th>
    </tr>
<?php
if(count($getdelaylist)!=0){
    for($i=0;$i<count($getdelaylist);$i++) {
?>
    <tr class="main_lists" id="delay_<?php echo $getdelaylist[$i]["pk_id"]; ?>" style="cursor:pointer" onclick="location.href=g5_url+'/page/view?me_id=<?php echo $getdelaylist[$i]["me_code"];?>&depth1_id=<?php echo $getdelaylist[$i]["depth1_id"]; ?>&depth2_id=<?php echo $getdelaylist[$i]["depth2_id"]; ?>'">
        <?php if(substr($getdelaylist[$i]["me_code"],0,2)=="10"){?>
            <td style="text-align: left;padding:10px;height:auto"><span title="<?php echo $getdelaylist[$i]["content"]; ?>"><?php echo "[".$getdelaylist[$i]["depth1_name"]."]"; ?><?php echo cut_str($getdelaylist[$i]["content"],15,"..."); ?></span></td>
        <?php }else{?>
            <td style="text-align: left;padding:10px;height:auto"><span title="<?php echo $getdelaylist[$i]["depth_name"]; ?>"><?php echo "[".$getdelaylist[$i]["depth1_name"]."]"; ?><?php echo $getdelaylist[$i]["depth_name"]; ?></span></td>
        <?php }?>
        <td style="padding:10px;height:auto"><?php echo $getdelaylist[$i]["delay_date"]; ?></td>
    </tr>
<?php
    }
?>
<?php }if(count($getdelaylist)==0){?>
    <tr><td colspan="2" class="td_center">승인요청 및 요청이력이 없습니다. 현장선택을 확인해 주세요.</td></tr>
<?php }?>
</table>
