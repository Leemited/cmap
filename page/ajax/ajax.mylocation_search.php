<?php
include_once ("../../common.php");

$sql= "select * from `cmap_my_construct` where status != -1 and cmap_name like '%{$stx}%' and mb_id != '{$member["mb_id"]}'";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}
for ($i=0;$i<count($list);$i++) {
    $mb = get_member($list[$i]["mb_id"]);
    $indate = explode(" ", $list[$i]["insert_date"]);
    if(strtotime($indate[0]) == strtotime(date("Y-m-d"))){
        $insert_date = $indate[1];
    }else{
        $insert_date = $indate[0];
    }
?>
<tr>
    <td class="td_center"><?php echo $mb["mb_name"];?></td>
    <td><?php echo "현장명 : ".$list[$i]["cmap_name"]." | 용역명 : ".$list[$i]["cmap_name_service"];?></td>
    <td class="td_center"><?php echo $insert_date;?></td>
    <td class="td_center">
        <input type="button" value="상세보기" class="basic_btn02 width30" style="padding:7px 0" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?id=<?php echo $list[$i]["id"];?>'">
        <input type="button" value="사용요청" class="basic_btn02 width30" style="padding:7px 0" onclick="fn_join('<?php echo $list[$i]["id"];?>','<?php echo $member["mb_id"];?>')">
    </td>
</tr>
<?php
}
if(count($list)==0) {
?>
<tr>
    <td colspan="4">검색된 현장/용역이 없습니다.</td>
</tr>
<?php
}
?>
