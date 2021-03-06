<?php
include_once ("../../common.php");
include_once (G5_PATH."/head.sub.php");

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
    $sql = "select * from `cmap_construct_work_msg` where msg_group = '{$msgs["msg_group"]}' and id < '{$msg_id}' order by msg_count asc";
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
            $sql = "select *,c.pk_id as pk_id,c.depth1_id as depth1_id,a.pk_id as depth1_pk_id,c.depth2_id as depth2_id ,d.depth_name as depth_name,a.depth_name as depth1_name,d.pk_id as depth4_pk_id from `cmap_depth4` as d left join `cmap_content` as c on d.id = c.depth4_id left join `cmap_depth1` as a on a.id = c.depth1_id where c.pk_id = '{$schedule_pk[$j]}'";
            $ddd = sql_fetch($sql);
            if(strpos($chcccid,$ddd["depth4_pk_id"])!==false) {
                continue;
            }
            if($msgdelaylist[$ddd["pk_id"]]) {
                $chcccid .= ','.$ddd["depth4_pk_id"];
                $delaylists[$ddd["pk_id"]] = $ddd;
                $delaylists[$ddd["pk_id"]]["delay_date"] = "-" . $days;
                $delaylists[$ddd["pk_id"]]["schedule_date"] = $row["schedule_date"];
            }
        }
    }
    unset($delaylist);
    $delaylist = $delaylists;
}

$delaylist = array_values($delaylist);
$delaylist = arr_sort($delaylist, "delay_date", "asc");

?>
<style>
    html,body{background-color:#fff;}
    .pages{position: relative;text-align: center;width: 100%;display: inline-block;margin-top: 10px;}
    #prints {
        padding: 3cm 1.5cm 1.5cm 1.5cm;
    }
    @media print {
        #prints {
            padding: 3cm 1.5cm 1.5cm 1.5cm;
        }
    }
</style>
    <!--<script src="<?php /*echo G5_JS_URL */?>/jquery.tools.js"></script>
<script src="<?php /*echo G5_JS_URL */?>/jquery.print-preview.js"></script>
    <a href="#" class="print_preview">인쇄하기</a>-->
<div id="savearea" style="display: inline-block">
    <div class="message" >
        <div class="msg_write print_area" id="prints" >
            <div class="msg_content">
                <div class="msg_write_container">
<!--                    <div class="msg_title1" style="background-color: transparent;opacity: 1">-->
<!--                        <div style="font-size:20pt;font-weight:bold;opacity: 1"><h2>업무연락서</h2></div>-->
<!--                    </div>-->
                    <div class="page1">
                        <table class="msg_title1">
                            <tr>
                                <td style="font-size:20pt;text-align: center"><h2>업무연락서</h2></td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <td>수&nbsp;신&nbsp;자 : </td>
                                <td>
                                    수신자참고
                                </td>
                            </tr>
                            <tr class="title">
                                <td >제&nbsp;&nbsp;&nbsp;&nbsp;목 :</td>
                                <td >
                                    <?php echo $msgs["msg_subject"];?>
                                    <div style="position: absolute;right:0;top:0"><input type="checkbox" name="msg_retype" id="msg_retype" value="1" <?php if($msgs["msg_retype"]==1){echo "checked";}?> disabled><label for="msg_retype">회신요청</label></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:0">
                                    <div style="width: 100%;height:1px;background-color:#000;"></div>
                                </td>
                            </tr>
                            <?php if($msg_id && count($parent_msg) > 1){?>
                                <tr>
                                    <td colspan="2">
                                        <div class="documt">
                                            <?php for($i=0;$i<count($parent_msg);$i++){
                                                $counts = $parent_msg[$i]["msg_count"];
                                                ?>
                                                <p ><?php echo ($i+1).". ";?><?php echo $const["cmap_name"];?><?php echo " - ".str_pad($counts,2,"0",STR_PAD_LEFT)."호";?><?php echo " (".$parent_msg[$i]["send_date"]." ".substr($parent_msg[$i]["send_time"],0,5).") 의 관련입니다.";?></p>
                                            <?php }?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                            <tr>
                                <td colspan="2" style="">
                                    <div class="read_msg_content">
                                        <!--<h2 style=""></h2>-->
                                        <p class="msg_content_detail" style="">
                                            <?php echo nl2br($msgs["msg_content"]);?>
                                        </p>
                                        <?php if($msgs["delay_view"]==1){?>붙&nbsp;&nbsp;&nbsp;&nbsp;임 : 공무행정 제출 지연 현황 1부.&nbsp;&nbsp;&nbsp;끝.<?php }?>
                                    </div>
                                </td>
                            </tr>

                        </table>
                        <table class="msg_table_small" style="position: absolute;bottom: 0;">
                            <tr>
                                <td colspan="2">
                                    <div class="send_info">
                                        <h2>
                                            <span><?php echo $msgs["msg_send_name"];?></span>
                                        <?php if($msgs["msg_sign_filename"]){?>
                                            <div class="signs" style=""><img src="<?php echo G5_DATA_URL;?>/member/<?php echo substr($mb["mb_id"],0,2);?>/<?php echo $msgs["msg_sign_filename"];?>" alt="" style="width:100%;"></div>
                                        <?php }else{?>
                                            <div class="stemp">직인생략</div>
                                        <?php }?>
                                        </h2>
                                    </div>
                                </td>
                            </tr>
                            <tr class="small_tr">
                                <td >수&nbsp;신&nbsp;자</td>
                                <td class="addmember" >
                                    <?php if($msg_id){
                                        if($msgs["msg_send_name"]!=""){
                                            $mem = explode(",",$msgs["msg_read_name"]);
                                            for($i=0;$i<count($mem);$i++){
                                                echo $mem[$i]. "&nbsp;&nbsp;";
                                            }
                                        }else {
                                            for ($i = 0; $i < count($read_mb_ids); $i++) {
                                                echo $read_mb_ids[$i]["mb_1"] . "" . $read_mb_ids[$i]["mb_4"] . "" . $read_mb_ids[$i]["mb_name"] . "&nbsp;&nbsp;";
                                            }
                                        }
                                    }?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 0">
                                    <div style="width:100%;height: 5px;background-color: #999999"></div>
                                </td>
                            </tr>
                            <tr class="small_tr">
                                <td colspan="2" >
                                    <?php $addrs = explode("//",$msgs["msg_send_addr"]);?>
                                    <p>시행 : (계약명) <?php echo $msgs["msg_send_cmap"];?> - <?php echo $msgs["msg_count"];?> (<?php echo date("Y.m.d");?>)호</p>
                                    <p>우편번호 : <?php echo $addrs[0]." ";?> 주소 : <?php echo $addrs[1];?></p>
                                    <p>전화 : <?php echo $msgs["msg_send_hp"]." ";?></p>
                                </td>
                            </tr>
                        </table>
                        <!--<span class="pages">- 1 -</span>-->
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php if($msgs["delay_view"]==1){?>
<?php  if(count($delaylist)>0){
    $pagecount = ceil(count($delaylist) / 30);
    $last = false;
    for($cnt = 0 ; $cnt < $pagecount ; $cnt++){
        if($last==true){continue;}
        if($cnt > 0) {
            $pageNum = (30 * ($cnt+1)) + 1;
            $pageNum2 = $pageNum + 30;
        }else{
            $pageNum = 0;
            $pageNum2 = 30;
        }
        if (count($delaylist) <= $pageNum2){
            $last = true;
        }
    ?>
<div class="message" id="prints" >
    <div class="msg_content">
        <div class="msg_write_container">
            <div class="page2" style="page-break-before:always">
                <div class="delay_view_t">[붙 임]</div>
                <div class="delay_cons">
                    <table class="delay_table">
                        <tr>
                            <th><input type="checkbox" id="chk_all" checked><label for="chk_all"></label></th>
                            <th>지연서류</th>
                            <th>제출기한</th>
                            <th>지연일수</th>
                        </tr>
                        <?php
                        for($i=$pageNum;$i<count($delaylist);$i++){
                            if($delaylist[$i]["pk_id"]=="1"){continue;}
                            if($msg_id) {
                                if (strpos($msgs["pk_ids"], $delaylist[$i]["pk_id"]) === false) {
                                    continue;
                                }
                            }
                            if($i>$pageNum2){continue;}
                            ?>
                            <tr>
                                <td class="td_center">
                                    <input type="checkbox" name="pk_ids[]" id="pk_id_<?php echo $delaylist[$i]["pk_id"];?>" value="<?php echo $delaylist[$i]["pk_id"];?>" >
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
            <!--<p style="padding:10px 0 0 0;">* 개인설정 및 현황에 따라 지연현황의 차이가 있을 수 있습니다.<br>* PM일 경우 개인 설정을 업데이트하여 확인 바랍니다.</p>-->
                <!--<span class="pages">- <?php /*echo $cnt+2;*/?> -</span>-->
            </div>
        </div>
    </div>
</div>
        <?php }?>
    <?php }?>
<?php }?>
</div>

<script>
    $(function(){
        html2canvas(document.querySelector("body")).then(function(canvas) {
            var imgData = canvas.toDataURL('image/png');
            var imgWidth = 210;
            var pageHeight = 297;
            var imgHeight = canvas.height * imgWidth / canvas.width;
            var heightLeft = imgHeight;
            var doc = new jsPDF('p', 'mm');
            var position = 0;
            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                doc.addPage();
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }
            doc.save('<?php echo $const["cmap_name"]."_업무연락서_".date('Ymdhis');?>.pdf');
            //window.close();
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/es6-promise/4.1.1/es6-promise.auto.js"></script>
<script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
<script src="<?php echo G5_JS_URL;?>/html2canvas.js"></script>
<?php
include_once (G5_PATH."/tail.sub.php");
?>

