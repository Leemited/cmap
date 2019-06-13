<?php
include_once ("../../common.php");
/*if(!$const_id){
    echo "1";
    return false;
}*/
$count = 0;
$chk_read = true;//확인 불가능 false = 가능 , 신규등록은 불가능
if($msg_id){
    $msgs = sql_fetch("select * from `cmap_construct_work_msg` where id = '{$msg_id}'");

    /*if($msgs["read_mb_id"]==$member["mb_id"] && $msgs["read_date"] == "") {
        $sql = "update `cmap_construct_work_msg` set read_date = now(), read_time = now() where id = '{$msg_id}'";
        sql_query($sql);
    }*/

    $read_mbs = explode(",",$msgs["read_mb_id"]);
    for($i=0;$i<count($read_mbs);$i++){
        $read_mb_ids[] = get_member($read_mbs[$i]);
    }

    $msg_reads = explode(",",$msgs["msg_read_member"]);
    if(count($read_mbs)==count($msg_reads)){
        $chk_read = true;//불가능
    }else{
        for($i=0;$i<count($msg_reads);$i++){
            if($msg_reads[$i]==$member["mb_id"]){
                $chk_read= true;//불가능 이미 읽음
                continue;
            }else{
                $chk_read = false; // 가능
            }
        }
    }

    //if($msgs["msg_count"] > 0){
    $sql = "select * from `cmap_construct_work_msg` where msg_group = '{$msgs["msg_group"]}' order by msg_count asc";
    $parent_msg_res = sql_query($sql);
    while($parent_msg_row = sql_fetch_array($parent_msg_res)){
        $parent_msg[] = $parent_msg_row;
    }
    $mb = get_member($msgs["send_mb_id"]);

    $sql = "select * from `cmap_my_construct` where id= '{$msgs["const_id"]}'";
    $const = sql_fetch($sql);
}else{

    $mb = $member;
    $sql = "select * from `cmap_my_construct` where id= '{$const_id}'";
    $const = sql_fetch($sql);

}

$sql = "select MAX(msg_count)as max_count from `cmap_construct_work_msg` where msg_group = '{$msgs["msg_group"]}' or const_id = '{$const_id}'";
$countmx = sql_fetch($sql);
$count = $countmx["max_count"]+1;

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
    //내 현황 가져오기
    $sql = "select * from `cmap_construct_work_msg` where const_id = '{$const["id"]}' and send_mb_id ='{$mb["mb_id"]}'";
    $delayitem = sql_fetch($sql);

    $vpk_ids = explode("``",$delayitem["pk_ids"]);
    $todays = date("Y-m-d");
    for($i=0;$i<count($vpk_ids);$i++){
        if ($vpk_ids[$i] != "") {
            $msgdelaylist[$vpk_ids[$i]] = $vpk_ids[$i];
        }
    }

    $sql = "select * from `cmap_myschedule` where construct_id = '{$const["id"]}' and pk_id <> '' order by schedule_date desc";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)) {
        $diff = strtotime($todays) - strtotime($row["schedule_date"]);

        $days = $diff / (60 * 60 * 24);
        $schedule_pk = explode("``",$row["pk_id"]);
        for($j=0;$j<count($schedule_pk);$j++) {
            if($delaylist[$schedule_pk[$j]]) {
                $sql = "select *,c.pk_id as pk_id,d.pk_id as depth4_pk_id,c.depth1_id as depth1_id, a.pk_id as depth1_pk_id,a.depth_name as depth1_name,d.depth_name as depth_name from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$schedule_pk[$j]}'";
                $ddd = sql_fetch($sql);

                $msgdelaylist[$schedule_pk[$j]] = $ddd;
                $msgdelaylist[$schedule_pk[$j]]["delay_date"] = "-" . $days;
                $msgdelaylist[$schedule_pk[$j]]["schedule_date"] = $row["schedule_date"];
            }
        }
    }
    $delaylist = $msgdelaylist;
}

$delaylist = array_values($delaylist);
$delaylist = arr_sort($delaylist, "delay_date", "asc");

?>
<div class="message" >
    <div class="msg_title">
        <h2>업무연락서</h2>
        <ul>
            <!--<li onclick="">새로고침</li>-->
            <li onclick="fnSave('<?php echo $msg_id;?>','<?php echo $msg_id["const_id"];?>');"><img src="<?php echo G5_IMG_URL;?>/ic_save.svg" alt=""></li>
           <!-- <li>다운로드</li>-->
            <li onclick="fnPrint('<?php echo $msg_id;?>','<?php echo $msg_id["const_id"];?>');"><img src="<?php echo G5_IMG_URL;?>/ic_print.svg" alt=""></li>
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
        <form action="<?php echo G5_URL;?>/page/mypage/send_msg" method="post" name="msg_form" >
            <input type="hidden" value="<?php echo $const_id;?>" name="const_id">
            <input type="hidden" value="<?php echo $count;?>" name="msg_count">
            <input type="hidden" value="<?php if($msg_id){echo "resend";}?>" name="type">
            <input type="hidden" value="<?php echo $msg_id;?>" name="msg_id">
            <input type="hidden" value="" name="in_members" id="in_members">
            <?php if($msg_id){?>
                <!--<input type="hidden" name="mb_id[]" value="<?php /*echo $msgs["send_mb_id"];*/?>">-->
                <input type="hidden" name="msg_group" value="<?php echo $msgs["msg_group"];?>">
            <?php } ?>
            <input type="hidden" value="<?php echo $member["mb_id"];?>" name="send_mb_id" id="send_mb_id">
            <tr class="msg_write_container">
                <table>
                    <tr>
                        <td>수&nbsp;&nbsp;&nbsp;신&nbsp;&nbsp;&nbsp;자 : </td>
                        <td>
                            수신자참고
                        </td>
                    </tr>
                    <tr>
                        <td>선&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;택 : </td>
                        <td>
                            <?php for($i=0;$i<count($mem);$i++){?>
                                <input type="checkbox" name="mb_id[]" id="mb_id_<?php echo $i;?>" value="<?php echo $mem[$i]["mb_id"];?>" <?php if($msgs["send_mb_id"]==$mem[$i]["mb_id"]){?>checked<?php }?>><label for="mb_id_<?php echo $i;?>" style="margin-left:0;margin-right:20px;"><?php echo $mem[$i]["mb_name"];?></label>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목 :</td>
                        <td>
                            <input type="text" name="msg_subject" id="msg_subject" class="write_input" required placeholder="제목을 입력바랍니다."><input type="checkbox" name="msg_retype" id="msg_retype" value="1" checked><label for="msg_retype">회신요청</label>
                        </td>
                    </tr>
                    <?php if($msg_id && count($parent_msg) > 1){?>
                    <tr>
                        <td colspan="2">
                            <div style="border:1px solid #ddd;padding:0px 11px;min-height:110px;max-height:110px;vertical-align: top;height:110px;overflow-y: auto">
                            <?php for($i=0;$i<count($parent_msg);$i++){
                                $counts = $parent_msg[$i]["msg_count"]+1;
                                ?>
                                    <p onclick="location.href=g5_url+'/page/mypage/my_message_list?msg_id=<?php echo $parent_msg[$i]["id"];?>'" style="padding: 8px 0"><?php echo ($i+1).". ";?><?php echo $const["cmap_name"];?><?php echo " - ".str_pad($counts,2,"0",STR_PAD_LEFT)."호";?><?php echo " (".$parent_msg[$i]["send_date"]." ".substr($parent_msg[$i]["send_time"],0,5).") 의 관련입니다.";?></p>
                            <?php }?>
                            </div>
                        </td>
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="2" style="padding:10px 0 0 0;text-align: left">
                            <div class="read_msg_content" style="padding:10px;border:1px solid #ddd;">
                            <?php if(!$msg_id){?>
                                <textarea name="msg_content" id="msg_content" cols="30" rows="10" placeholder="업무연락 할 내용을 기입하세요" style="border:none;height:120px;padding:0" required></textarea>
                            <?php }else{?>
                                <p class="msg_content_detail">
                                <?php echo nl2br($msgs["msg_content"]);?>
                                </p>
                                <textarea name="msg_content" id="msg_content" cols="30" rows="10" style="border:none;margin-top:20px;height:120px;padding:0" placeholder="업무연락 할 내용을 기입하세요"></textarea>
                            <?php }?>
                                <input type="checkbox" name="delay_view" id="delay_view" value="1"><label for="delay_view" style="margin-left:0"> 붙임 : 공무행정 제출 지연 현황 1부.&nbsp;&nbsp;&nbsp;끝.</label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="send_info">
                                <p style="margin-top:8px"><?php echo $mb["mb_1"];?> <?php echo $mb["mb_4"];?> <?php echo $mb["mb_name"];?></p>
                                <?php if($mb["mb_8"]){?>
                                    <div style="width:58px;position: absolute;right:0;top:0;"><img src="<?php echo G5_DATA_URL;?>/member/<?php echo substr($mb["mb_id"],0,2);?>/<?php echo $mb["mb_8"];?>" alt="" style="width:100%;"></div>
                                <?php }else{?>
                                    <div style="border:1px solid #000;padding:10px;font-size:20px;font-family: batangche;position:absolute;right:0;top:15px;">직인생략</div>
                                <?php }?>
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="msg_table_small">
                    <tr>
                        <td>수신자</td>
                        <td class="addmember">
                            <?php if($msg_id){
                                for($i=0;$i<count($read_mb_ids);$i++){
                                    echo $read_mb_ids[$i]["mb_1"]."".$read_mb_ids[$i]["mb_4"]."".$read_mb_ids[$i]["mb_name"]."&nbsp;&nbsp;";
                                }
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background-color: #EEEEEE;font-size: 0px;padding: 3px;"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p>시행 : (계약명) <?php echo $const["cmap_name"];?> - <?php echo $msgs["msg_count"];?> (<?php echo date("Y.m.d");?>)호</p>
                            <?php if($mb["mb_addr1"] || $mb["mb_zip1"]){?><p>우편번호 : <?php echo $mb["mb_zip1"]." ";?> 주소 : <?php echo $mb["mb_addr1"]. " ". $mb["mb_addr2"];?></p><?php }?>
                            <?php if($mb["mb_tel"] || $mb["mb_7"]){?><p><?php if($mb["mb_tel"]){?>전화 : <?php echo $mb["mb_tel"]." ";?> <?php } if($mb["mb_7"]){?>주소 : <?php echo $mb["mb_7"];?><?php }?></p><?php }?>
                        </td>
                    </tr>
                </table>
                <?php if($msgs["delay_view"]==1 || !$msg_id){?>
                <div style="font-size:15px;padding:30px 0 8px 0;margin-top:30px;border-top:1px solid #ddd;">[붙 임]</div>
                <div class="delay_cons">
                    <table class="delay_table">
                        <tr>
                            <th><input type="checkbox" id="chk_all" checked><label for="chk_all"></label></th>
                            <th>지연서류</th>
                            <th>제출기한</th>
                            <th>지연일수</th>
                        </tr>
                        <?php
                        for($i=0;$i<count($delaylist);$i++){
                            if($delaylist[$i]["pk_id"]=="1"){continue;}
                            if($msg_id) {
                                if (strpos($msgs["pk_ids"], $delaylist[$i]["pk_id"]) === false) {
                                    continue;
                                }
                            }
                        ?>
                        <tr>
                            <td class="td_center">
                                <input type="checkbox" name="pk_ids[]" id="pk_id_<?php echo $delaylist[$i]["pk_id"];?>" value="<?php echo $delaylist[$i]["pk_id"];?>" checked>
                                <label for="pk_id_<?php echo $delaylist[$i]["pk_id"];?>"></label>
                            </td>
                            <td title="<?php echo $delaylist[$i]["depth_name"];?>"><?php echo "[".$delaylist[$i]["depth1_name"]."]".cut_str($delaylist[$i]["depth_name"],20,"...");?></td>
                            <td class="td_center"><?php echo $delaylist[$i]["schedule_date"];?></td>
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
            </div>
            <div class="msg_write_btn">
                <input type="button" class="basic_btn01 <?php if($chk_read == true){?>disabled<?php }?>" <?php if($chk_read == true){?>disabled<?php }?> value="수신확인" <?php if($chk_read == false){?>onclick="location.href=g5_url+'/page/mypage/message_update?msg_id=<?php echo $msg_id;?>'" <?php }?>>
                <input type="button" class="basic_btn02 <?php if(!$msg_id || $msgs["msg_retype"]==0 || $msgs["send_mb_id"]==$member["mb_id"] || $msgs["msg_retype_status"]==1){?>disabled<?php }?>" value="회신" <?php if(!$msg_id || $msgs["msg_retype"]==0  || $msgs["send_mb_id"]==$member["mb_id"] || $msgs["msg_retype_status"]==1){?>disabled<?php }?> onclick="<?php if($msg_id && $msgs["msg_retype"]==1 && $msgs["msg_retype_status"]==0){?>return fnMsgSend()<?php }?>">
                <input type="button" class="basic_btn02 <?php if($msg_id){?>disabled<?php }?>" <?php if($msg_id){?>disabled<?php }?> value="전송" <?php if($msg_id){?>disabled<?php }?> <?php if(!$msg_id){?>onclick="return fnMsgSend('')"<?php }?>>
            </div>
        </form>
    </div>
</div>

<script>
    function fnMsgSend(){
        <?php if($msg_id && $chk_read !=true){?>
            alert("수신확인이 선진행 되어야 합니다.");
            return false;
        <?php }?>

        var chk = $("input[id^=mb_id]:checked").length;

        if(chk==0){
            alert("수신자를 선택해 주세요.");
            return false;
        }

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
        window.open("https://xn--z69akkg7o1wgdnk53m.com/page/mypage/message_print.php?msg_id="+id+"&const_id="+const_id,"popup",'width=720,height=1020,ressize=no,menubar=no,toolbar=no');
    }

    $(function(){
        $("#chk_all").click(function(){
            if($(this).prop("checked")==true){
                $("input[name^=pk_ids]").each(function(){
                    $(this).attr("checked",true);
                });
            }else{
                $("input[name^=pk_ids]").each(function(){
                    $(this).removeAttr("checked");
                });
            }
        })

        $("input[name^=mb_id]").click(function(){
            var members = $("#in_members").val();
            var chk = $(this).prop("checked");
            $.ajax({
                url: g5_url + '/page/ajax/ajax.get_member.php',
                method: "post",
                data: {mb_id: $(this).val(), members: members,chk:chk},
                dataType: "json"
            }).done(function (data) {
                $(".addmember").html(data.add_member);
                $("#in_members").val(data.add_id);
            });
        });
    });
</script>

