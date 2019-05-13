<?php
include_once ("../../common.php");

if(!$mb_id){
    echo "1";
    return false;
}

//제출 지연 현황
$delay_now = date("Y-m-d");
if($const_id){
    $where = " and construct_id = '{$const_id}'";
    $where2 = " and const_id = '{$const_id}'";
}else{
    $where = " and construct_id in ('{$const_ids}')";
    $where2 = " and const_id in ('{$const_ids}')";
}
$delaysql = "select * from `cmap_myschedule` where schedule_date < '{$delay_now}' {$where} order by id desc";
$delayres = sql_query($delaysql);
while($row = sql_fetch_array($delayres)){
    $delay_id = explode("``",$row["pk_id"]);

    $diff = strtotime($delay_now) - strtotime($row["schedule_date"]);

    $days = ceil($diff / (60*60*24));

    if($row["schedule_type"]==0){continue;}
    if($row["schedule_type"] == 1){
        for($i=0;$i<count($delay_id);$i++) {
            $sql = "select * from `cmap_my_construct_map` where mb_id = '{$member["mb_id"]}' {$where2}";
            $chk = sql_fetch($sql);
            $delay_pk_ids = explode("``",$chk["pk_ids"]);
            $delay_pk_actives = explode("``",$chk["pk_actives"]);
            for($j=0;$j<count($delay_pk_ids);$j++){
                if($delay_pk_ids[$j] == $delay_id[$i] && $delay_pk_actives[$j]==1){continue;}
                $sql = "select *,c.pk_id as pk_id from `cmap_content` as c left join `cmap_depth1` as d on c.depth1_id = d.id where c.pk_id = '{$delay_id[$i]}'";
                //echo $sql."<br>";
                $item = sql_fetch($sql);
                $delaylist[$delay_id[$i]] = $item;
                $delaylist[$delay_id[$i]]["delay_date"] = "-".$days;
            }
        }
    }else{
        for($i=0;$i<count($delay_id);$i++) {
            $sql = "select * from `cmap_my_construct_map` where mb_id = '{$member["mb_id"]}' {$where2}";
            $chk = sql_fetch($sql);
            $delay_pk_ids = explode("``",$chk["pk_ids"]);
            $delay_pk_actives = explode("``",$chk["pk_actives"]);
            for($j=0;$j<count($delay_pk_ids);$j++){
                if($delay_pk_ids[$j] == $delay_id[$i] && $delay_pk_actives[$j]==1){continue;}
                $sql = "select *,c.pk_id as pk_id from `cmap_content` as c left join `cmap_depth1` as d on c.depth1_id = d.id where c.pk_id = '{$delay_id[$i]}'";
                //echo $sql."<br>";
                $item = sql_fetch($sql);
                $delaylist[$delay_id[$i]] = $item;
                $delaylist[$delay_id[$i]]["delay_date"] = "-".$days;
            }
        }
    }
}

$delaylist = array_values($delaylist);

$delaylist = arr_sort($delaylist,"delay_date", "asc");
?>
<table>
    <tr>
        <th>지연서류</th>
        <th>지연일</th>
    </tr>
<?php
if(count($delaylist)!=0){
    for($i=0;$i<count($delaylist);$i++) {
?>
    <tr id="delay_<?php echo $delaylist[$i]["pk_id"]; ?>" style="cursor:pointer" onclick="location.href=g5_url+'/page/view?me_id=<?php echo $delaylist[$i]["me_code"];?>&depth1_id=<?php echo $delaylist[$i]["depth1_id"]; ?>&depth2_id=<?php echo $delaylist[$i]["depth2_id"]; ?>&pk_id=<?php echo $delaylist[$i]["pk_id"]; ?>'">
        <td style="text-align: left;padding:10px;"><span title="<?php echo $delaylist[$i]["content"]; ?>"><?php echo $delaylist[$i]["content"]; ?></span></td>
        <td><?php echo $delaylist[$i]["delay_date"]; ?></td>
    </tr>
<?php
    }
?>
<?php }else{?>
    <tr><td colspan="3" class="td_center">승인요청 및 요청이력이 없습니다.</td></tr>
<?php }?>
</table>
