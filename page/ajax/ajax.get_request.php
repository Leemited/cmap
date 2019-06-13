<?php
include_once ("../../common.php");

if(!$mb_id){
    echo "1";
    return false;
}

if($const_id){
    $where = " (read_mb_id = '{$mb_id}' or send_mb_id = '{$mb_id}') and msg_status = 0 and i.const_id = '{$const_id}' or (read_mb_id = '{$mb_id}' and msg_status = 0)";
}else{
    $where = " (read_mb_id = '{$mb_id}' or send_mb_id = '{$mb_id}') and msg_status = 0 ";
}

$sql = "select *,i.id as invite_id,c.id as const_id from `cmap_construct_invite` as i left join `cmap_my_construct` as c on i.const_id = c.id where {$where}";
$res = sql_query($sql);
$num = sql_num_rows($res);
?>
<table>
    <colgroup>
        <col width="20%">
        <col width="*">
        <col width="35%">
    </colgroup>
    <tr>
        <th>성명</th>
        <th>현장명</th>
        <th>승인여부 </th>
    </tr>
<?php
if($num!=0){
    while($row = sql_fetch_array($res)){
        //$mb_id = 나 , send_mb_id = 보낸사람
            if($mb_id == $row["send_mb_id"]){//내가 보냄
                /*if($row["msg_type"]==0){//초대
                    
                }else{//요청
                    
                }*/
                $mb = get_member($row["send_mb_id"]);
            }else{//상대방이 보냄
                $mb = get_member($row["send_mb_id"]);
            }
?>
<tr class="main_lists" id="invite_<?php echo $row["invite_id"];?>">
    <td><?php echo $mb["mb_name"];?></td>
    <td><?php echo $row["cmap_name"];?></td>
    <td>
        <?php if($mb_id==$row["send_mb_id"] || ($row["msg_type"]==1 && $mb_id==$row["send_mb_id"])){?>
            승인대기중
        <?php }else{ ?>
            <input type="button" value="승인" onclick="fnConstJoinUp('<?php echo $row['invite_id'];?>','<?php echo $row['const_id'];?>')"><input type="button" value="거절" onclick="fnConstCancel('<?php echo $row['invite_id'];?>')">
        <?php }?>
    </td>
</tr>
<?php
    }
?>
<?php }else{?>
    <tr><td colspan="3" class="td_center">승인요청 및 요청이력이 없습니다.</td></tr>
<?php }?>
</table>
