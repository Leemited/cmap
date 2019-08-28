<?php
include_once ("../../common.php");

//$sql = "select schedule_content from `cmap_myschedule` where id = '{$id}'";
//$con = sql_fetch($sql);

if($member["mb_level"]==5){
    $sql = "select * from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$const}'";
    $ss = sql_fetch($sql);
    if($ss!=null){
        $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$const}'";
    }else{
        $sql = "select * from `cmap_my_construct` where id = '{$const}'";
        $ss2 = sql_fetch($sql);
        $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$ss2["mb_id"]}' and const_id = '{$const}'";
    }
}else {
    $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$const}'";
}

$activechk = sql_fetch($activesql);
$map_pk_id = explode("``",$activechk["pk_ids"]);
$map_pk_actives = explode("``",$activechk["pk_actives"]);
$map_pk_actives_date = explode("``",$activechk["pk_actives_date"]);

$pk_id = str_replace("``",",",$pk_id);

$sql = "select * from `cmap_myschedule` where construct_id = '{$const}'";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    if($row["pk_id"]==""){continue;}
    $pk_ids = explode("``",$row["pk_id"]);
    for($i=0;$i<count($pk_ids);$i++){
        $pklist[$pk_ids[$i]] = $row["schedule_date"];
    }
}
$sql = "select * from `cmap_content` where pk_id in ({$pk_id}) order by id";
$res = sql_query($sql);
$a = 0;
while($row = sql_fetch_array($res)){
    $list[$a] = $row;
    $list[$a]["active"] = 0;
    for($i=0;$i<count($map_pk_id);$i++){
        if($row["pk_id"]==$map_pk_id[$i]){
            $list[$a]["active_date"] = $map_pk_actives_date[$i];
            $list[$a]["schedule_date"] = $pklist[$map_pk_id[$i]];
            if($map_pk_actives[$i]==0){
                $list[$a]["active"] = 1;
                $list[$a]["active_date"] = "0000-00-00";
            }
        }
    }
    $a++;
}?>
<ul>
<?php for($i=0;$i<count($list);$i++){
    $sql = "select * from `cmap_depth1` where id = '{$list[$i]["depth1_id"]}'";
    $depth1 = sql_fetch($sql);
    $class = "";
    if($list[$i]["active"]==1){//지연
        if(strtotime($list[$i]["schedule_date"]) < strtotime(date("Y-m-d")) && $list[$i]["active_date"]=="0000-00-00"){
            $class = "delays";
        }
    }else if($list[$i]["active"]==0){//지연아님
        if($list[$i]["schedule_date"] < date("Y-m-d") && strtotime($list[$i]["active_date"]) > strtotime($list[$i]["schedule_date"])){
            $class = "delay_confirm";
        }else if($list[$i]["schedule_date"] < date("Y-m-d") && strtotime($list[$i]["active_date"]) <= strtotime($list[$i]["schedule_date"])){
            $class = "confirm";
        }else if($list[$i]["schedule_date"] >= date("Y-m-d")){
            $class = "confirm";
        }
    }else if($list[$i]["active"]==2){//대상아님
        $class = "confirm";
    }
    ?>
    <li title="<?php echo $list[$i]["content"];?>" class="<?php echo $class;?>" onclick="location.href=g5_url+'/page/view?depth1_id=<?php echo $list[$i]["depth1_id"];?>&me_id=<?php echo $depth1["me_code"];?>&depth2_id=<?php echo $list[$i]["depth2_id"];?>&pk_id=<?php echo $list[$i]["pk_id"];?>&constid=<?php echo $_REQUEST["const"];?>'"><?php echo $list[$i]["content"];?></li>
<?php }
if(count($list)==0){
?>
    <li class="no-list">상세목록이 없습니다.</li>
<?php }?>
</ul>