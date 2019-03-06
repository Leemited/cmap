<?php
include_once ("../common.php");
$sub="sub";
include_once (G5_PATH."/_head.php");

if(strlen($me_id)==2){
    $sql = "select * from `cmap_depth1` where SUBSTRING(me_code,1,2) like '%{$me_id}%' order by me_code asc limit 0,1 ";
    $codes = sql_fetch($sql);
    $incode = $codes["me_code"];
    if(!$depth2_id){
        $sql = "select * from `cmap_dpeth2` where depth1_id = '{$code["id"]}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}else{
    $incode = $me_id;
    if(!$depth2_id){
        $sql = "select * from `cmap_depth1` where me_code = '{$incode}' order by id asc limit 0,1";
        $codes = sql_fetch($sql);
        $sql = "select * from `cmap_depth2` where depth1_id = '{$depth1_id}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}
if($depth1_id){
    $where = " and depth1_id = '{$depth1_id}'";
}

//해당 대메뉴에 대한 서브 메뉴
$menu_id = substr($me_id,0,2);
$sql = "select * from `cmap_menu` where menu_code like '%{$menu_id}%' and menu_status = 0 and menu_depth = 1 order by menu_order";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $search_menu[] = $row;
}

$sql = "select * from `cmap_menu` where menu_code = '{$menu_id}'";
$menu1_info = sql_fetch($sql);

$sql = "select * from `cmap_menu` where menu_code = '{$me_id}'";
$menu2_info = sql_fetch($sql);


//if($menu_id==10 || $menu_id==40) {
$sql = "select * from `cmap_menu` where menu_code like '{$menu_id}%' and menu_code != '{$menu_id}' and menu_status = 0 order by menu_order asc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $depth_me[] = $row;
}

$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
    $depth_menu[] = $row;
}
$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 {$where} group by a.id order by a.id asc ";
$res = sql_query($sql);
$i = 0;
while ($row = sql_fetch_array($res)) {
    $j = 0;
    $list[$i] = $row;

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    //while($row2 = sql_fetch_array($res2)){
    //    $depth_menu[] = $row2;
    //}

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    while ($row2 = sql_fetch_array($res2)) {
        $k = 0;
        $list[$i]['depth2'][$j] = $row2;
        $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth3` as a left join `cmap_content` as b on a.id = b.depth3_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']}  group by a.id order by a.id asc";
        $res3 = sql_query($sql);
        $num3 = sql_num_rows($res3);
        if($num3 > 0) {
            while ($row3 = sql_fetch_array($res3)) {
                $l = 0;
                $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
                $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth4` as a left join `cmap_content` as b on a.id = b.depth4_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']} and a.depth3_id = {$row3['id']}  group by a.id order by a.id asc";
                $res4 = sql_query($sql);
                $num4 = sql_num_rows($res4);
                if($num4 > 0) {
                    while ($row4 = sql_fetch_array($res4)) {
                        $m = 0;
                        $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row4;
                        $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} and depth4_id = {$row4['id']} order by id asc";
                        $res5 = sql_query($sql);
                        $depth5num = sql_num_rows($res5);
                        while ($row5 = sql_fetch_array($res5)) {
                            $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m] = $row5;
                            $m++;
                        }
                        $l++;
                    }
                }else{
                    $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} order by id asc";
                    $res6 = sql_query($sql);
                    $num4 = sql_num_rows($res6);
                    while ($row6 = sql_fetch_array($res6)) {
                        $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row6;
                        $l++;
                    }
                }
                $k++;
            }
        }else{
            $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} order by id asc";
            $res7 = sql_query($sql);
            $num3 = sql_num_rows($res7);
            while ($row7 = sql_fetch_array($res7)) {
                //$l = 0;
                $list[$i]['depth2'][$j]['depth3'][$k] = $row7;
                $k++;
            }
        }
        $j++;
    }
    $i++;
}
/*}/*else if($menu_id==30){
    if($depth1_id) {
        $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
        $res = sql_query($sql);
        while ($row = sql_fetch_array($res)) {
            $depth_menu[] = $row;
        }
    }else{
        $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
        $res = sql_query($sql);
        while ($row = sql_fetch_array($res)) {
            $depth_me[] = $row;
        }
    }

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id  from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 {$where1} group by a.id order by a.id asc ";
    $res = sql_query($sql);
    $i=0;
    if($me_id!="3035"){
        while ($row = sql_fetch_array($res)) {
            $j = 0;
            $list[$i] = $row;
            $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where b.depth1_id = {$row['id']} {$where2} group by a.id order by a.id asc";
            $res2 = sql_query($sql);
            while ($row2 = sql_fetch_array($res2)) {
                $k = 0;
                $list[$i]['depth2'][$j] = $row2;
                $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} order by id asc";
                $res3 = sql_query($sql);
                while ($row3 = sql_fetch_array($res3)) {
                    $l = 0;
                    $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
                    $k++;
                }
                $j++;
            }
            $i++;
        }
    }else{
        while($row=sql_fetch_array($res)){
            $j=0;
            $list[$i] = $row;
            $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where b.depth1_id = {$row['id']} {$where2} group by a.id order by a.id asc";
            $res2 = sql_query($sql);
            while($row2 = sql_fetch_array($res2)){
                $k=0;
                $list[$i]['depth2'][$j] = $row2;
                $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth3` as a left join `cmap_content` as b on a.id = b.depth3_id where b.depth1_id = {$row['id']} and b.depth2_id = {$row2['id']} {$where3} group by a.id order by a.id asc";
                $res3 = sql_query($sql);
                while($row3 = sql_fetch_array($res3)){
                    $l=0;
                    $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
                    $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} {$where5} order by id asc";
                    $res4 = sql_query($sql);
                    while($row4 = sql_fetch_array($res4)){
                        //$m=0;
                        $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row4;
                        $l++;
                    }
                    $k++;
                }
                $j++;
            }
            $i++;
        }
    }
}*/
$myconstruction = false;

?>
<div>
    <div class="menu_guide">
        <div><?php echo $list[0]["depth_name"];?> : </div>
    </div>
</div>
<div class="search">
    <?php if($is_member && $myconstruction){?>
        <select name="" id=""></select>
    <?php }?>
    <select name="me_id" id="me_id">
        <?php for($i=0;$i<count($depth_me);$i++) { ?>
            <option value="60<?php echo $depth_me[$i]["me_id"];?>" <?php echo get_selected('60'.$depth_me[$i]["me_id"],$me_id);?>><?php echo $depth_me[$i]["menu_name"];?></option>
        <?php }?>
    </select>
</div>
<div class="full-width">
    <div class="view">
        <div class="title">
            <?php echo $menu1_info["menu_name"];?> | <?php echo $menu2_info["menu_name"];?>
        </div>
        <table class="menu_table" >
            <tr>
                <th colspan="2"><?php echo $menu2_info["menu_name"];?></th>
            </tr>
            <tr></tr>
            <?php
            for($i=0;$i<count($depth_menu);$i++){

                ?>
                <?php if($i==3){?><tr style="height:6px;"></tr><?php }?>
                <tr class="">
                    <?php
                    if($i==0){
                        echo "<td rowspan='3' style='text-align: center;vertical-align: middle;background-color:#e4f8f9;border:1px solid #b4c6d4;font-size:16px;'>업<br>체<br>평<br>가</td>";
                    }else if($i==3){
                        echo "<td rowspan='5' style='text-align: center;vertical-align: middle;background-color:#e4f8f9;border:1px solid #b4c6d4;font-size:16px;'>기<br>술<br>자<br>평<br>가</td>";
                    }
                    ?>
                    <td class="menu_padding"><input type="button"  value="<?php echo $depth_menu[$i]['depth_name'];?>" class="depth_btn <?php if($depth_menu[$i]["id"]==$depth1_id){?>active<?php }?>" onclick="location.href=g5_url+'/page/view3.php?me_id=<?php echo $me_id;?>&depth1_id=<?php echo $depth_menu[$i]["id"];?>'"></td>
                </tr>
            <?php }?>
            <tr class="memo">
                <td colspan="2">
                    <h2>MEMO</h2>
                    <div class="memo_area" style="width:100%;height:500px;padding:10px;">

                    </div>
                </td>
            </tr>
        </table>
        <table class="view_table" >
            <tr>
                <!--th>직업선택</th-->
                <th colspan="2">평가항목</th>
                <th rowspan="2">배점</th>
                <th colspan="5">평가등급</th>
                <?php if($is_member && $myconstruction){?>
                    <th rowspan="2">점수</th>
                <?php }?>
                <th rowspan="2">평가방법</th>
            </tr>
            <tr>
                <th>중분류(배점)</th>
                <th>세부분류 (평가방법 미리보기)</th>
                <th>우수 ( X 1.0)</th>
                <th>보통 ( X 0.9)</th>
                <th>미흠 ( X 0.8)</th>
                <th>불량 ( X 0.7)</th>
                <th>불량 ( X 0.6)</th>
            </tr>
            <tr></tr>
            <?php
            $depth_last = 1;
            for($i=0;$i<count($list);$i++){
                ?>
                <tr class="first">
                <?php for($j=0;$j<count($list[$i]['depth2']);$j++) {
                    ?>
                    <td class="depth1" rowspan="<?php echo $list[$i]['depth2'][$j]['cnt'];?>">
                        <?php echo $list[$i]['depth2'][$j]['depth_name'];?>
                    </td>
                    <?php for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                        ?>
                        <td class="depth2" rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>" >
                            <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?>
                        </td>
                        <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) {
                            $total += (float)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                            if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name']=="0"){
                                echo "";
                            }else{
                            ?>
                            <td class="depth3" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" style="text-align: center" >
                                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?>
                            </td>
                            <?php }
                            for ($m = 0; $m < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5']); $m++) {
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment"]!="") {
                                    $files = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment"]);
                                    $filenames = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachmentname1"]);
                                }else{
                                    $files = array();
                                    $filenames = array();
                                }
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]!=""){
                                    $links = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]);
                                    $linknames = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["linkname"]);
                                }else{
                                    $links = array();
                                    $linknames = array();
                                }
                                if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment2"]!=""){
                                    $files2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachment2"]);
                                    $filenames2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["attachmentname2"]);
                                }else{
                                    $files2 = array();
                                    $filenames2 = array();
                                }
                                $depth_last++;
                                $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                                $span = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["span"]);
                                $eval = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content']);
                                if(count($eval)>0){
                                    for($o=0;$o<count($eval);$o++){
                                        if(count($eval)==($o+1)) {
                                            $total2 += (float)$eval;
                                        }
                                        if($eval[$o]=="") continue;
                                        ?>
                                        <td class="depth4" style="text-align: center" colspan="<?php echo $span[$o];?>" onclick="fnUpdateNumber();">
                                            <?php
                                            echo $eval[$o];
                                            ?>
                                        </td>
                                    <?php }?>
                                <?php }?>
                                <?php if($is_member && $myconstruction){?>
                                    <td class="score_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>">
                                        0
                                    </td>
                                <?php }?>
                                <td class="etc" id="">
                                    <?php if(count($files)>=1){?>
                                        <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>')">
                                    <?php }else {?>
                                        <!-- 참고 링크 -->
                                        <?php if(count($links)>=1){
                                            for($w=0;$w<count($links);$w++){?>
                                                <input type="button" value="링크" style="background-image:url('<?php echo G5_IMG_URL;?>/ic_links.svg');"  onclick="window.open('<?php echo $links[$w];?>','_blank')" title="<?php echo $linknames[$w];?>">
                                            <?php }
                                        }?>
                                        <!-- 참고 링크 -->
                                        <?php if(count($files2)>=1){
                                            for($w=0;$w<count($files2);$w++){
                                                if($files2[$w]!=""){
                                                    ?>
                                                    <input type="button" value="다운로드" style="background-image:url('<?php echo G5_IMG_URL;?>/ic_attach.svg');" onclick="location.href=g5_url+'/page/view_download.php?file=<?php echo $files2[$w];?>&filename=<?php echo $filenames2[$w];?>'" title="<?php echo $filenames2[$w];?>">
                                                <?php }
                                            }
                                        }?>
                                    <?php }?>
                                    <?php /*if(count($files)>0 && $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]){*/?><!--
                                    <input type="button" value="미리보기" onclick="fnViewEtc('<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];*/?>')">
                                <?php /*}else if(count($files)>0 && !$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]){*/?>
                                    <input type="button" value="파일" style="background-image:url('<?php /*echo G5_IMG_URL;*/?>/ic_attach.svg');" onclick="fnViewEtc('<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];*/?>')">
                                <?php /*}else if(count($files)==0 && $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["link"]){*/?>
                                    <input type="button" value="링크" style="background-image:url('<?php /*echo G5_IMG_URL;*/?>/ic_link.svg');" onclick="window.open=('')">
                                --><?php /*}*/?>
                                </td>
                                <!--<td class="depth6">
                                <?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date'];*/?>
                            </td>-->
                                </tr>
                                <?php if($list[$i]['cnt'] == $depth_last){?>
                                    <tr class="<?php if($list[$i]['depth2'][$j]['cnt'] == $depth_last){echo "finish";}?>">
                                <?php }
                                if($list[$i]['depth2'][$j]['cnt']+1 == $depth_last){?>
                                    <tr></tr>
                                <?php }
                            }
                        }
                    }
                    $depth_last = 1;
                }
                ?>
                <tr class="margin_tr"></tr>
                <tr class="sum">
                    <td colspan="2" class="td_center">점수</td>
                    <td class="td_center" style="text-align: center;"><?php echo $total;?></td>
                    <td colspan="5"></td>
                    <?php if($is_member && $myconstruction){?>
                        <td><?php echo $total2;?></td>
                    <?php }?>
                    <td></td>
                </tr>
                <?php
            }?>
        </table>
        <div class="clear"></div>
    </div>
</div>
<div class="etc_view">
    <div class="etc_title">
        <h2><img src="<?php echo G5_IMG_URL?>/ic_preview.svg" alt=""> 참고 자료</h2>
    </div>
    <div class="close" onclick="fnEtcClose()">닫기</div>
    <div class="content">
        <!--<div class="images">

        </div>
        <div class="links">

        </div>
        <div class="files">

        </div>-->
    </div>
</div>
<span class="etc_view_bg"></span>
<script src="<?php echo G5_JS_URL ?>/jquery-ui-1.9.2.custom.js"></script>
<script>
    $(function(){
        var tbl_width = $(".menu_table").width();
        tbl_width = tbl_width + 24;
        $(".view_table").attr("style","width:calc(100% - "+tbl_width+"px)");

        $("#menu_code").change(function(){
            //선택된 값으로 2dpeth의 옵션 갑 변경
            location.href=g5_url+'/page/view.php?me_id='+$(this).val();
        });
        $("#me_id").change(function(){
            var id = $(this).val();
            if(id == 60129){
                location.href = g5_url + '/page/view3.php?me_id=' + id;
            }else {
                location.href = g5_url + '/page/view2.php?me_id=' + id;
            }
        });

        $(".etc_view_bg").click(function(){
            fnEtcClose();
        });

        window.onkeydown = function(){
            if(event.keyCode==27 && $(".etc_view").hasClass("active")){
                fnEtcClose();
            }
        }

        $(function(){
            $(document).tooltip();
        });
    })

    function fnViewEtc(pk_id){
        $.ajax({
            url:g5_url+"/page/ajax/ajax.etc.php",
            method:"post",
            data:{pk_id:pk_id}
        }).done(function(data){
            $(".etc_view .content").html('');
            if(!$(".etc_view").hasClass("active")){
                $(".etc_view .content").html(data);
                $(".etc_view").addClass("active");
                $(".etc_view_bg").addClass("active");
            }else{
                $(".etc_view").removeClass("active");
                $(".etc_view_bg").removeClass("active");
            }
        });
    }
    function fnEtcClose(){
        $(".etc_view").removeClass("active");
        $(".etc_view_bg").removeClass("active");
    }

</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
