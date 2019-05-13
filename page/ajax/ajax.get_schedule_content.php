<?php
include_once ("../../common.php");

//$sql = "select schedule_content from `cmap_myschedule` where id = '{$id}'";
//$con = sql_fetch($sql);
$pk_id = str_replace("``",",",$pk_id);

$sql = "select * from `cmap_content` where pk_id in ({$pk_id}) order by id";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}?>
<ul>
<?php for($i=0;$i<count($list);$i++){
    $sql = "select * from `cmap_depth1` where id = '{$list[$i]["depth1_id"]}'";
    $depth1 = sql_fetch($sql);
    ?>
    <li title="<?php echo $list[$i]["content"];?>" onclick="location.href=g5_url+'/page/view?depth1_id=<?php echo $list[$i]["depth1_id"];?>&me_id=<?php echo $depth1["me_code"];?>&pk_id=<?php echo $list[$i]["pk_id"];?>'"><?php echo $list[$i]["content"];?></li>
<?php }
if(count($list)==0){
?>
    <li class="no-list">상세목록이 없습니다.</li>
<?php }?>
</ul>