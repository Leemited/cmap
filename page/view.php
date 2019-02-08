<?php
include_once ("../common.php");

include_once (G5_PATH."/_head.php");

if(strlen($me_id)==2){
    $sql = "select * from `cmap_depth1` where SUBSTRING(me_code,1,2) like '%{$me_id}%' order by me_code asc limit 0,1 ";
    $codes = sql_fetch($sql);
    $incode = $codes["me_code"];
}else{
    $incode = $me_id;
}

if($depth1_id){
    $where = " and depth1_id = '{$depth1_id}'";
}

if($depth2_id){
    $where2 = " and depth2_id = '{$depth2_id}'";
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
$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 group by a.id order by a.id asc ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $depth_me[] = $row;
}
$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 {$where} group by a.id order by a.id asc ";
$res = sql_query($sql);
$i = 0;
while ($row = sql_fetch_array($res)) {
    $j = 0;
    $list[$i] = $row;
    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    while($row2 = sql_fetch_array($res2)){
        $depth_menu[] = $row2;
    }

    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} {$where2} group by a.id order by a.id asc";
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
<div class="guide">
    <div class="user">
        <div>사용자 가이드</div>
        <div>사용자가이드 예시입니다. 아직 개발 중입니다.</div>
    </div>
    <div class="menu_guide">
        <div><?php echo $list[0]["depth_name"];?> : </div>
    </div>
</div>
<div class="search">
    <?php if($is_member && $myconstruction){?>
        <select name="" id=""></select>
    <?php }?>
    <select name="depth1_id" id="depth1_id">
        <?php for($i=0;$i<count($depth_me);$i++) { ?>
            <option value="<?php echo $depth_me[$i]["id"];?>" <?php echo get_selected($depth_me[$i]["id"],$depth1_id);?>><?php echo $depth_me[$i]["depth_name"];?></option>
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
                <th>작업선택</th>
            </tr>
            <tr></tr>
            <?php
            for($i=0;$i<count($depth_menu);$i++){
            ?>
                <tr>
                    <td class="menu_padding"><input type="button"  value="<?php echo $depth_menu[$i]['depth_name'];?>" class="depth_btn <?php if($depth_menu[$i]["id"]==$depth2_id){?>active<?php }?>" onclick="location.href=g5_url+'/page/view.php?me_id=<?php echo $me_id;?>&depth1_id=<?php echo $depth1_id;?>&depth2_id=<?php echo $depth_menu[$i]["id"];?>'"></td>
                </tr>
            <?php }?>
            <tr>
                <td>
                    <h2>MEMO</h2>
                    <div class="memo_area" style="width:100%;height:500px;padding:10px;">

                    </div>
                </td>
            </tr>
        </table>
        <table class="view_table" >
            <?php
            if($depth5num > 0) {
            ?>
            <colgroup>
                <!--<col width="10%">-->
                <!--col width="10%"-->
                <col width="12%">
                <col width="12%">
                <col width="*">
                <col width="10%">
                <?php if($is_member && $myconstruction){?>
                <col width="5%">
                <col width="10%">
                <?php }?>
                <col width="6%">
            </colgroup>
            <tr>
                <!--th>직업선택</th-->
                <th>구분</th>
                <th>항목</th>
                <th>주요확인내용</th>
                <th>참고</th>
                <?php if($is_member && $myconstruction){?>
                <th>확인</th>
                <th>제출일</th>
                <?php }?>
                <th>지연일</th>
            </tr>
            <tr></tr>
            <?php
            $depth_last = 1;
            for($i=0;$i<count($list);$i++){
            ?>
            <tr class="first">
                <?php for($j=0;$j<count($list[$i]['depth2']);$j++) {
                    ?>
                <!--<td class="depth1" rowspan="<?php //echo $list[$i]['depth2'][$j]['cnt'];?>">
                    <?php //echo $list[$i]['depth2'][$j]['depth_name'];?>
                </td>-->
                    <?php for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                    ?>
                    <td class="depth1" rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>" >
                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?>
                    </td>
                        <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) { ?>
                        <td class="depth2" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" >
                            <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?>
                        </td>
                            <?php
                            for ($m = 0; $m < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5']); $m++) {
                            $depth_last++;
                            $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                            ?>
                            <td class="depth3">
                                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content'];?>
                            </td>
                            <td class="etc" id="">
                                <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>')">
                            </td>
                            <?php if($is_member && $myconstruction){?>
                            <td class="confirm" id="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>">

                            </td>
                            <td class="date" id="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>">

                            </td>
                            <?php }?>
                            <td class="depth6">
                                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date'];?>
                            </td>
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
             }?>
            <?php }else {
                ?>
                <?php if($num4 > 0 && $num3 > 0){
                    ?>
            <colgroup>
                <col width="10%">
                <col width="12%">
                <col width="*">
                <col width="10%">
                <col width="6%">
            </colgroup>
            <tbody>
            <tr>
                <th>구분</th>
                <th>항목</th>
                <th>주요확인내용</th>
                <th>참고</th>
                <th>기준일</th>
            </tr>
            <tr></tr>
            <?php
            $depth_last = 1;
            for($i=0;$i<count($list);$i++){?>
            <tr >
            <?php for($j=0;$j<count($list[$i]['depth2']);$j++) {?>
            <td rowspan="<?php echo $list[$i]['depth2'][$j]['cnt'];?>" class="depth1">
                <?php echo $list[$i]['depth2'][$j]['depth_name'];?>
            </td>
            <?php
            for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) { ?>
            <td rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>"  class="depth2">
                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?>
            </td>
            <?php
            for ($l = 0; $l < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']); $l++) {
            $depth_last++;?>
            <td class="depth3">
                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['content'];?>
            </td>
            <td class="etc">
                <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>')">
            </td>
            <td class="depth6">
                <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['submit_date'];?>
            </td>
            </tr>
            <?php if($list[$i]['cnt'] >= $depth_last){?>
            <tr class="<?php if($list[$i]['cnt'] == $depth_last){echo "finish_".$list[$i]["id"];}?>">
                <?php }
                }
                }
                } $depth_last = 1;
                }?>
                <?php }
                if($num3 > 0 && (!$num4 || $num4 == 0)){
                    ?>
                    <colgroup>
                        <!--<col width="10%">-->
                        <col width="12%">
                        <col width="*">
                        <col width="6%">
                    </colgroup>
                    <tr>
                        <th>공정단계별</th>
                        <th>주요검사항목</th>
                        <th>참고</th>
                    </tr>
                    <tr></tr>
                    <?php
                    $depth_last = 1;
                    for ($i = 0; $i < count($list); $i++) {
                        ?>
                        <tr>
                        <?php for ($j = 0; $j < count($list[$i]['depth2']); $j++){ ?>
                            <td rowspan="<?php echo $list[$i]['depth2'][$j]['cnt']; ?>"  class="depth1" >
                                <?php echo $list[$i]['depth2'][$j]['depth_name']; ?>
                            </td>
                            <?php
                            for ($k = 0; $k < count($list[$i]['depth2'][$j]['depth3']); $k++) {
                                $depth_last++; ?>
                                <td class="depth3">
                                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['content']; ?>
                                </td>
                                <td class="etc" >
                                    <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>')">
                                </td>
                                </tr>
                                <?php if($list[$i]['cnt'] >= $depth_last){?>
                                    <tr class="<?php if($list[$i]['cnt'] == $depth_last){echo "finish_".$list[$i]["id"];}?>">
                                <?php }
                            }
                        }$depth_last = 1;
                    }
                    ?>
                <?php }?>
            <?php } ?>
        </table>
    </div>
</div>
<div class="etc_view">
    <div class="etc_title">
        <h2>참고 자료</h2>
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
<script>
$(function(){
    $("#menu_code").change(function(){
        //선택된 값으로 2dpeth의 옵션 갑 변경
        location.href=g5_url+'/page/view.php?me_id='+$(this).val();
    });
    $("#depth1_id").change(function(){
        location.href=g5_url+'/page/view.php?me_id=<?php echo $me_id;?>&depth1_id='+$(this).val();
    });
})

function fnViewEtc(pk_id){
    $.ajax({
        url:g5_url+"/page/ajax/ajax.etc.php",
        method:"post",
        data:{pk_id:pk_id}
    }).done(function(data){
        console.log(data);
        $(".etc_view .content").html('');
        if(!$(".etc_view").hasClass("active")){
            $(".etc_view .content").html(data);
           $(".etc_view").addClass("active");
        }else{
            $(".etc_view").removeClass("active");
        }
    });
}
function fnEtcClose(){
    $(".etc_view").removeClass("active");
}
</script>
<?php
$sub="sub";
include_once (G5_PATH."/_tail.php");
?>