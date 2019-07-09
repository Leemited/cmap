<?php
include_once ("../../common.php");

if(!$mb_id){
    echo "1";
    return false;
}

$sql = "select * from `cmap_construct_work_msg` where (instr(read_mb_id,'{$member["mb_id"]}') != 0) and read_status = 0 ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $wlist[] = $row;
}

?>
<style>
    .detail_list{width: calc(100% + 5px);}
</style>
<div class="work_list_btns">
    <input type="button" value="업무연락서 목록" onclick="location.href=g5_url+'/page/mypage/my_message_list'">
</div>
<table>
    <colgroup>
        <col width="15%">
        <col width="25%">
        <col width="60%">
    </colgroup>
    <tr>
        <th>구분</th>
        <th>성명</th>
        <th>현장/내용</th>
    </tr>
    <?php
    if(count($wlist)!=0){
        for($i=0;$i<count($wlist);$i++) {
            $workconst = sql_fetch("select * from `cmap_my_construct` where id = '{$wlist[$i]["const_id"]}'");
            if($wlist[$i]["send_mb_id"]==$member["mb_id"]){
                $msg_type = "발신";
                $mb = get_member($member["mb_id"]);
            }else{
                $msg_type = "수신";
                $mb = get_member($wlist[$i]["send_mb_id"]);
            }
            ?>
            <tr class="main_lists" id="msg_<?php echo $wlist[$i]["id"]; ?>" style="cursor:pointer" onclick="location.href=g5_url+'/page/mypage/my_message_list?msg_id=<?php echo $wlist[$i]["id"];?>&const_id=<?php echo $wlist[$i]["const_id"];?>'">
                <td style="text-align: center;padding:10px;height:auto"><?php echo $msg_type;?></td>
                <td style="text-align: center;padding:10px;height:auto"><?php echo $mb["mb_name"];?></td>
                <td style="padding:10px;height:auto;text-align: left;"><label style="display:block;text-overflow: ellipsis;white-space: nowrap;overflow: hidden"><?php echo $workconst["cmap_name"];?>/<?php echo $wlist[$i]["msg_subject"];?></label></td>
            </tr>
            <?php
        }
        ?>
    <?php }else if(count($wlist)==0){?>
        <tr><td colspan="3" class="td_center">수신/발신된 업무연락서가 없습니다.</td></tr>
    <?php }?>
</table>
