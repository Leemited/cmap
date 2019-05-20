<?php
include_once ("../../common.php");
if(!$const_id){
    echo "1";
    return false;
}
$count = 0;
if($msg_id){
    $msgs = sql_fetch("select * from `cmap_construct_work_msg` where id = '{$msg_id}'");
    $count = $msgs["msg_count"]+1;
    if($msgs["read_mb_id"]==$member["mb_id"] && $msgs["read_date"] == "") {
        $sql = "update `cmap_construct_work_msg` set read_date = now(), read_time = now() where id = '{$msg_id}'";
        sql_query($sql);
    }
    if($msgs["msg_count"] > 0){
        $sql = "select * from `cmap_construct_work_msg` where msg_group = '{$msgs["msg_group"]}' and msg_count < '{$count}' and id <> '{$msg_id}' order by msg_count asc";
        $parent_msg_res = sql_query($sql);
        while($parent_msg_row = sql_fetch_array($parent_msg_res)){
            $parent_msg[] = $parent_msg_row;
        }
    }

    if($msgs['mb_id']==$member["mb_id"]) {//발신자
        $mb = get_member($member["mb_id"]);
    }else {//수신자
        $mb = get_member($msgs["send_mb_id"]);
    }
}

$sql = "select * from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or instr(members, '{$member["mb_id"]}')) and status = 0 and id= '{$const_id}'";
$const = sql_fetch($sql);

if($const==null){
    echo "1";
    return false;
}

if($const["members"]==""){
    echo "2";
    return false;
}
$mbs = explode(",",$const["members"]);

if($const["mb_id"]!=$member["mb_id"]){
    $mbs[] = $const["mb_id"];
}

for($i=0;$i<count($mbs);$i++){
    if($member["mb_id"]==$mbs[$i]){continue;}
    $mem[] = get_member($mbs[$i]);
}
if($msg_id) {
    $activesql = "select * from `cmap_my_construct_map` where mb_id ='{$msgs["send_mb_id"]}' and const_id = '{$current_const["const_id"]}'";
    $activechk = sql_fetch($activesql);
    $map_pk_id = explode("``",$activechk["pk_ids"]);
    $map_pk_actives = explode("``",$activechk["pk_actives"]);
    $map_pk_actives_date = explode("``",$activechk["pk_actives_date"]);

    $delaysql = "select * from `cmap_myschedule` where construct_id = '{$current_const["const_id"]}' and schedule_date < '{$delay_now}' and pk_id <> '' order by schedule_date desc";
    $delayres = sql_query($delaysql);
    while($delayrow = sql_fetch_array($delayres)){
        $pk_ids = explode("``",$delayrow["pk_id"]);

        $diff = strtotime($delay_now) - strtotime($delayrow["schedule_date"]);

        $days = $diff / (60*60*24);


        for($i=0;$i<count($pk_ids);$i++){
            $sql = "select * from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where b.pk_id = '{$pk_ids[$i]}'";
            $chk_depth1 = sql_fetch($sql);
            //print_r2($chk_depth1);
            $delay_me_code = $chk_depth1["me_code"];


            for($j=0;$j<count($map_pk_id);$j++){
                if($pk_ids[$i]==$map_pk_id[$j]){
                    if($map_pk_actives[$j]==1){continue;}
                    $sql = "select * from `cmap_content` where pk_id = '{$pk_ids[$i]}'";
                    $item = sql_fetch($sql);
                    $delaylist[$pk_ids[$i]] = $item;
                    $delaylist[$pk_ids[$i]]["delay_date"] = "-".$days;
                    $delaylist[$pk_ids[$i]]["delay_actived_date"] = $delayrow["schedule_date"];
                    $delaylist[$pk_ids[$i]]["me_id"] = $delay_me_code;
                }
            }
        }
    }
}
$delaylist = array_values($delaylist);
$delaylist = arr_sort($delaylist, "delay_date", "asc");
?>
<div class="message" >
    <div class="msg_title">
        <h2>업무연락서</h2>
        <ul>
            <!--<li onclick="">새로고침</li>-->
            <li>저장</li>
           <!-- <li>다운로드</li>-->
            <li onclick="fnPrint('<?php echo $msg_id;?>','<?php echo $const_id;?>');">프린트</li>
        </ul>
        <div class="close" onclick="fnEtcClose()"></div>
    </div>
    <div class="msg_write print_area">
        <div class="msg_title1">
            <?php if($msg_id){?>
                <h2>업무연락서(회신용)</h2>
            <?php }else{?>
                <h2>업무연락서</h2>
            <?php }?>
        </div>
        <div class="msg_content">
        <form action="<?php echo G5_URL;?>/page/mypage/send_msg.php" method="post" name="msg_form" >
            <input type="hidden" value="<?php echo $const_id;?>" name="const_id">
            <input type="hidden" value="<?php echo $count;?>" name="msg_count">
            <input type="hidden" value="<?php if($msg_id){echo "resend";}?>" name="type">
            <input type="hidden" value="<?php echo $msg_id;?>" name="msg_id">
            <?php if($msg_id){?>
                <input type="hidden" name="mb_id[]" value="<?php echo $msgs["send_mb_id"];?>">
                <input type="hidden" name="msg_group" value="<?php echo $msgs["msg_group"];?>">
            <?php } ?>
            <input type="hidden" value="<?php echo $member["mb_id"];?>" name="send_mb_id" id="send_mb_id">
            <div class="msg_write_container">
                <table>
                    <tr>
                        <td>수신자 : </td>
                        <td>
                            <input type="text" value="<?php echo $const["cmap_name"];?>" name="cmap_name" id="cmap_name" class="read_input" readonly> <!--<input type="text" value="<?php /*echo date("Y-m-d H:1");*/?>" readonly>-->
                            <?php if($msg_id){?><span style="position: absolute;right:0;top: 20px;font-size: 15px;"><?php echo $msgs["send_date"];?></span><?php }?>
                        </td>
                    </tr>
                    <?php if(!$msg_id){?>
                    <tr>
                        <td></td>
                        <td>
                            <?php for($i=0;$i<count($mem);$i++){?>
                                <input type="checkbox" name="mb_id[]" id="mb_id_<?php echo $i;?>" value="<?php echo $mem[$i]["mb_id"];?>"><label for="mb_id_<?php echo $i;?>" style="margin-left:0;margin-right:20px;"><?php echo $mem[$i]["mb_name"];?></label>
                            <?php }?>
                        </td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td>제&nbsp;&nbsp;&nbsp;목 :</td>
                        <td>
                            <?php if(!$msg_id){?>
                            <input type="text" name="msg_subject" id="msg_subject" class="write_input" required><input type="checkbox" name="msg_retype" id="msg_retype" value="1" checked><label for="msg_retype">회신요청</label>
                            <?php }else{?>
                                <input type="hidden" name="msg_subject" id="msg_subject" class="write_input" required value="<?php echo $msgs["msg_subject"];?>">
                                <span class="msg_title"><?php echo $msgs["msg_subject"];?></span><input type="checkbox" name="msg_retype" id="msg_retype" value="1" checked><label for="msg_retype" style="position:absolute;right:0;top:20px;">회신요청</label>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:10px 0 15px 0;border-top:2px solid #000;border-bottom:2px solid #000;text-align: left">
                            <?php if(!$msg_id){?>
                            <textarea name="msg_content" id="msg_content" cols="30" rows="10" placeholder="ex) 1. 내용입력" required></textarea>
                            <?php }else{?>

                                <div class="read_msg_content" style="padding:10px;border:1px solid #ddd;">
                                    <?php echo nl2br($msgs["msg_content"]);?>
                                    <textarea name="msg_content" id="msg_content" cols="30" rows="10" style="border:none;margin-top:20px;height:120px;"></textarea>
                                </div>
                            <?php }?>
                        </td>
                    </tr>
                    <?php if($msg_id){?>

                        <tr>
                            <td colspan="2"></td>
                        </tr>
                    <?php }?>
                </table>
                <div class="delay_title">
                    <h2>공무행정 제출 지연 현황</h2>
                    <div>
                        <input type="checkbox" name="delay_view" id="delay_view" value="1"><label for="delay_view"> 표기</label>
                    </div>
                </div>
                <?php if($msgs["delay_view"]==1||!$msg_id){?>
                <div class="delay_cons">
                    <table>
                        <tr>
                            <th>적용</th>
                            <th>지연서류</th>
                            <th>제출기한</th>
                            <th>지연일수</th>
                        </tr>
                        <?php
                        for($i=0;$i<count($delaylist);$i++){
                            if($msg_id) {
                                if (strpos($msgs["pk_ids"], $delaylist[$i]["pk_id"]) === false) {
                                    continue;
                                }
                            }
                        ?>
                        <tr>
                            <td class="td_center"><input type="checkbox" name="pk_ids[]" id="pk_id_<?php echo $delaylist[$i]["pk_id"];?>" value="<?php echo $delaylist[$i]["pk_id"];?>" checked><label for="pk_id_<?php echo $delaylist[$i]["pk_id"];?>"></label></td>
                            <td title="<?php echo $delaylist[$i]["content"];?>"><?php echo cut_str($delaylist[$i]["content"],20,"...");?></td>
                            <td class="td_center"><?php echo $delaylist[$i]["delay_actived_date"];?></td>
                            <td class="td_center"><?php echo $delaylist[$i]["delay_date"];?></td>
                        </tr>
                        <?php }?>
                        <?php if(count($delaylist)==0 || ($msg_id && $msgs["pk_ids"] == "")){?>
                            <tr>
                                <td colspan="4" class="td_center">지연 현황이 없습니다.</td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
                <p style="padding:10px 0 0 0;">* 개인설정 및 현황에 따라 지연현황의 차이가 있을 수 있습니다.<br>* PM일 경우 개인 설정을 업데이트하여 확인 바랍니다.</p>
                <?php }?>
                <div class="send_info">
                    발신자 : <?php echo $const["cmap_name"];?> | <?php echo $mb["mb_4"]." ".$mb["mb_name"];?>_<?php echo str_pad($count,2,'0',STR_PAD_LEFT);?><?php if($msg_id){ echo " (".$msgs["send_date"]." ".substr($msgs["send_time"],0,5).")";}else{ echo "(".date("Y-m-d h:i").")"; } ?>
                </div>
                <?php if($msg_id && count($parent_msg)>0){?>
                <div class="delay_title second">
                    <h2>업무연락서 내역</h2>
                    <div>
                        <ul>
                            <?php for($i=0;$i<count($parent_msg);$i++){?>
                                <li onclick="location.href=g5_url+'/page/mypage/my_message_list?msg_id=<?php echo $parent_msg[$i]["id"];?>'"><?php echo ($i+1).".";?><?php echo $parent_msg[$i]["msg_subject"];?><?php echo ($parent_msg[$i]["msg_count"]+1)."호";?><?php echo "(".$parent_msg[$i]["send_date"]." ".substr($parent_msg[$i]["send_time"],0,5).")의 관련입니다.";?></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
                <?php }?>
            </div>
            <div class="msg_write_btn">
                <input type="button" class="basic_btn01 <?php if($msgs["send_mb_id"] == $member["mb_id"] || $msg_id=="" || ($msg_id && $msgs["send_mb_id"]!=$member["mb_id"] && $msgs["read_status"]==1)){?>disabled<?php }?>" <?php if($msgs["send_mb_id"] == $member["mb_id"] || $msg_id=="" ||  ($msg_id && $msgs["send_mb_id"]!=$member["mb_id"] && $msgs["read_status"]==1)){?>disabled<?php }?> value="확인" <?php if($msgs["send_mb_id"] != $member["mb_id"] || ($msg_id!="" && $msgs["send_mb_id"]!=$member["mb_id"] && $msgs["read_status"]==0)){?>onclick="location.href=g5_url+'/page/mypage/message_update?msg_id=<?php echo $msg_id;?>'" <?php }?>>
                <input type="button" class="basic_btn02 <?php if(!$msg_id || $msgs["msg_retype"]==0 || $msgs["send_mb_id"]==$member["mb_id"] || $msgs["read_status"]==1){?>disabled<?php }?>" value="회신" <?php if(!$msg_id || $msgs["msg_retype"]==0  || $msgs["send_mb_id"]==$member["mb_id"] || $msgs["read_status"]==1){?>disabled<?php }?> onclick="<?php if($msg_id && $msgs["msg_retype"]==1 && $msgs["read_status"]==0){?>return fnMsgSend()<?php }?>">
                <input type="button" class="basic_btn02 <?php if($msg_id){?>disabled<?php }?>" <?php if($msg_id){?>disabled<?php }?> value="전송" <?php if($msg_id){?>disabled<?php }?> <?php if(!$msg_id){?>onclick="return fnMsgSend('')"<?php }?>>
            </div>
        </form>
        </div>
    </div>
</div>

<script>
    function fnMsgSend(){
        <?php if(!$msg_id){?>
        var chk = $("input[id^=mb_id]:checked").length;
        if(chk==0){
            alert("수신자를 선택해 주세요.");
            return false;
        }
        <?php }?>
        if($("#msg_subject").val() == ""){
            alert("제목을 입력해 주세요.");
            return false;
        }
        if($("#msg_content").val() == ""){
            alert("내용을 입력해 주세요.");
            return false;
        }

        if($("#msg_retype").prop("checked")==false) {
            if (confirm("회신 요청이 선택되지 않았습니다. \n해당 업무연락서는 확인용으로 전송됩니다.")) {
                document.msg_form.submit();
            } else {
                return false;
            }
        }else{
            document.msg_form.submit();
        }
    }
    
    function fnPrint(id,const_id) {
        window.open(g5_url+"/page/mypage/message_print.php?msg_id="+id+"&const_id="+const_id,"popup",'width=588,height=830,ressize=no,menubar=no,toolbar=no');
    }
</script>

