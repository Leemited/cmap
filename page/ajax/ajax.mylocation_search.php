<?php
include_once ("../../common.php");

$sql= "select * from `cmap_my_construct` where status != -1 and cmap_name like '%{$stx}%' and mb_id != '{$member["mb_id"]}' order by insert_date desc";
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

    $sql =  "select * from `cmap_construct_invite` where const_id = '{$list[$i]["id"]}' and send_mb_id = '{$member["mb_id"]}' and msg_status = 0";

    $chk_send = sql_fetch($sql);
    
    if($list[$i]["members"]!="") {
        $chk_members = false;
        $members = explode(",", $list[$i]["members"]);
        for ($j = 0; $j < count($member);$j++){
            if($members[$j]==$member["mb_id"]){
                $chk_members = true;
                continue;
            }
        }
    }
?>
<tr>
    <td class="td_center"><?php echo $mb["mb_name"];?></td>
    <td><div style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;width: 100%;display:inline-block;max-width:860px;"><?php echo "현장명 : ".$list[$i]["cmap_name"]." | 용역명 : ".$list[$i]["cmap_name_service"];?></div></td>
    <td class="td_center"><?php echo $insert_date;?></td>
    <td class="td_center <?php if($chk_send==null && !$chk_members){?>last2<?php }?>"  <?php if($chk_send!=null){?>colspan="2"<?php }else{if($chk_members){?>colspan="2"<?php }}?>>
        <?php if($chk_send!=null){?>
            <span>승인대기중</span>
        <?php }else{?>
            <?php if($chk_members){?>
                <span>현장참여중</span>
            <?php }else{?>
                <input type="button" value="상세보기" class="basic_btn02" style="padding:7px 10px" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $list[$i]["id"];?>'">
    </td>
    <td class="td_center last2">
                <input type="button" value="사용요청" class="basic_btn02" style="padding:7px 10px" onclick="fn_join('<?php echo $list[$i]["id"];?>','<?php echo $member["mb_id"];?>')">
            <?php }?>
        <?php }?>
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
