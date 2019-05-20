<?php
include_once ("../../common.php");
include_once (G5_PATH."/head.sub.php");

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

    $send = get_member($msgs["read_mb_id"]);

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
<div id="prints">
<div class="message" >
    <div class="msg_write print_area">
        <div class="msg_title1">
            <h2>업무연락서</h2>
        </div>
        <div class="msg_content">
                <div class="msg_write_container">
                    <table>
                        <tr>
                            <td>수신자 : </td>
                            <td>
                                <?php echo $const["cmap_name"];?> / <?php echo $send["mb_name"];?>
                                <?php if($msg_id){?><span style="position: absolute;right:0;top: 20px;font-size: 15px;"><?php echo $msgs["send_date"];?></span><?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td>제&nbsp;&nbsp;&nbsp;목 :</td>
                            <td>
                                <?php echo ($msgs["msg_subject"])?$msgs["msg_subject"]:"제목없음";?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:15px 0 15px 0;border-top:2px solid #000;border-bottom:2px solid #000;text-align: left;height:300px;vertical-align: top;">
                                <?php echo nl2br($msgs["msg_content"]);?>
                            </td>
                        </tr>
                    </table>
                    <div class="delay_title">
                        <h2>공무행정 제출 지연 현황</h2>
                    </div>
                    <?php if($msgs["delay_view"]==1||!$msg_id){?>
                        <div class="delay_cons">
                            <table>
                                <tr>
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
                    <?php }?>
                    <div class="send_info">
                        발신자 : <?php echo $const["cmap_name"];?> | <?php echo $mb["mb_4"]." ".$mb["mb_name"];?>_<?php echo str_pad($count,2,'0',STR_PAD_LEFT);?><?php if($msg_id){ echo " (".$msgs["send_date"]." ".substr($msgs["send_time"],0,5).")";}else{ echo "(".date("Y-m-d h:i").")"; } ?>
                    </div>
                </div>
        </div>
    </div>
</div>
</div>
<!-- MeadCoScriptXJS Library -->
<script src="<?php echo G5_JS_URL;?>/meadco-core.js"></script>
<script src="<?php echo G5_JS_URL;?>/meadco-scriptxprint.js"></script>
<script src="<?php echo G5_JS_URL;?>/meadco-scriptxprinthtml.js"></script>
<!-- A promise library will be required if targetting IE. -->
<script type="text/javascript">
    /*$(window).on('load', function () {
        MeadCo.ScriptX.Print.HTML.connect(
            "https://scriptxservices.meadroid.com/api/v1/printHtml",
            "{1b6f3198-38e5-41e1-8dee-3bdd26613b54}");
        var settings = MeadCo.ScriptX.Print.HTML.settings;
        settings.header = "";
        settings.footer = "";
        settings.page.orientation =
            MeadCo.ScriptX.Print.HTML.PageOrientation.PORTRAIT;

        var margins = settings.page.margins;
        margins.left = 12.5;
        margins.top = 12.5;
        margins.bottom = 12.5;
        margins.right = 12.5;

        MeadCo.ScriptX.Print.HTML.printDocument(false);
    });*/
    $(function(){
        window.print();
        window.close();
    });
</script>
<?php
include_once (G5_PATH."/tail.sub.php");