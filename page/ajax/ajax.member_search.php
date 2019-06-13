<?php
include_once ("../../common.php");

$sql = "select * from `g5_member` where {$sch_type} like '%{$search}%' and mb_id <> '{$member["mb_id"]}'";
$res = sql_query($sql);
while($row =sql_fetch_array($res)){
    $mem[] = $row;
}
for($i=0;$i<count($mem);$i++){
?>
<li><div><?php echo "[".$mem[$i]["mb_id"]."] ".$mem[$i]["mb_name"];?></div><div class="basic_btn02" onclick="fnConstJoin('<?php echo $mem[$i]["mb_id"];?>','<?php echo $constid;?>')"> 초대하기 </div></li>
<?php }
if(count($mem)==0){
?>
<li class="no-list">검색 결과가 없습니다.</li>
<?php }?>
