<?php
include_once ("../common.php");
$sub="sub";

/*if(!$is_member){
    alert("로그인후 이용 가능합니다.", G5_BBS_URL."/login");
}*/

//내 현황 가져오기
$sql = "select * from `cmap_my_construct_map` where const_id = '{$current_const["const_id"]}' and mb_id ='{$member["mb_id"]}'";
$delayitem = sql_fetch($sql);

$vpk_ids = explode("``",$delayitem["pk_ids"]);
$vpk_active = explode("``",$delayitem["pk_actives"]);
$vpk_active_date = explode("``",$delayitem["pk_actives_date"]);
$vpk_ids_other = explode("``",$delayitem["pk_ids_other"]);
$vpk_actives_other = explode("``",$delayitem["pk_actives_other"]);
$vpk_actives_dates_other = explode("``",$delayitem["pk_actives_dates_other"]);

for($i=0;$i<count($vpk_ids);$i++){
    if($vpk_ids[$i]==""){continue;}
    if($vpk_active[$i]==""){$vpk_active[$i]=0;}
    if($vpk_active_date[$i]==""){$vpk_active_date[$i]="0000-00-00";}

    $delayview[$vpk_ids[$i]]["pk_id"] = $vpk_ids[$i];
    $delayview[$vpk_ids[$i]]["pk_active"] = $vpk_active[$i];
    $delayview[$vpk_ids[$i]]["pk_active_date"] = $vpk_active_date[$i];
}

for($i=0;$i<count($vpk_ids_other);$i++){
    if($vpk_ids_other[$i]==""){continue;}
    if($vpk_actives_other[$i]==""){$vpk_actives_other[$i]=0;}
    if($vpk_actives_dates_other[$i]==""){$vpk_actives_dates_other[$i]="0000-00-00";}

    $allview[$vpk_ids_other[$i]]["pk_id"] = $vpk_ids_other[$i];
    $allview[$vpk_ids_other[$i]]["pk_active"] = $vpk_actives_other[$i];
    $allview[$vpk_ids_other[$i]]["pk_active_date"] = $vpk_actives_dates_other[$i];
}

if(strlen($me_id)==2){
    $sql = "select * from `cmap_depth1` where SUBSTRING(me_code,1,2) like '%{$me_id}%' order by id asc limit 0,1 ";
    $codes = sql_fetch($sql);
    $incode = $codes["me_code"];
    $depth1_id = $codes["id"];
    if(!$depth2_id){
        $sql = "select * from `cmap_dpeth2` where depth1_id = '{$code["id"]}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}else{
    $incode = $me_id;
    if(!$depth2_id){
        if($depth1_id){
            $where = " and id='{$depth1_id}'";
        }
        $sql = "select * from `cmap_depth1` where me_code = '{$incode}' {$where} order by id asc limit 0,1";
        $codes = sql_fetch($sql);
        if(substr($me_id,0,2)== 50 || $depth1_id=='') {
            $depth1_id = $codes["id"];
        }
        $sql = "select * from `cmap_depth2` where depth1_id = '{$codes["id"]}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}

if($depth1_id){
    $where = " and depth1_id = '{$depth1_id}'";
}
if($depth2_id){
    $where2 = " and depth2_id = '{$depth2_id}'";
}

//메모 불러오기
$sql = "select * from `cmap_mymemo` where me_id = '{$me_id}' and mb_id = '{$member["mb_id"]}' {$where} {$where2} order by id desc";
$res = sql_query($sql);
$num = sql_num_rows($res);
while($row = sql_fetch_array($res)){
    $memo[] = $row;
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


include_once (G5_PATH."/_head.php");

//현장 상태 가져오기
if($current_const["const_id"]!="" && $current_const["const_id"]!=0) {
    $sql = "select * from `my_cmap_contruct_map` where const_id = '{$current_const["const_id"]}' and mb_id = '{$member["mb_id"]}'";
}

$myconstruction = false;
//$delaylist = array_values($delaylist);
?>
<!--<div>
    <div class="menu_guide">
        <div><?php /*echo $list[0]["depth_name"];*/?> : </div>
    </div>
</div>-->
<?php if($is_member){?>
<!--<div class="search">

</div>-->
<?php }?>
<div class="full-width">
    <div class="view">
        <div class="left">
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
                        <td class="menu_padding"><input type="button"  value="<?php echo $depth_menu[$i]['depth_name'];?>" class="depth_btn <?php if($depth_menu[$i]["id"]==$depth2_id){?>active<?php }?>" onclick="location.href=g5_url+'/page/view?me_id=<?php echo $me_id;?>&depth1_id=<?php echo $depth1_id;?>&depth2_id=<?php echo $depth_menu[$i]["id"];?>'" title="<?php echo $depth_menu[$i]['depth_name'];?>"></td>
                    </tr>
                <?php }?>
                <tr class="memo">
                    <td style="position: relative">
                        <form action="<?php echo G5_URL;?>/page/memo_update.php" method="post" >
                            <input type="hidden" name="return_url" value="view">
                            <input type="hidden" name="type" value="in">
                            <input type="hidden" name="me_id" value="<?php echo $me_id;?>">
                            <input type="hidden" name="depth1_id" value="<?php echo $depth1_id;?>">
                            <input type="hidden" name="depth2_id" value="<?php echo $depth2_id;?>">
                            <input type="hidden" name="mb_id" value="<?php echo $member["mb_id"];?>">
                        <h2>MEMO</h2>
                        <input type="submit" class="" value="등록" style="width:calc(25% - 10px);background-color:transparent;padding:5px;text-align: center;border:none;font-size:14px;position:absolute;top:6px;right:5px;border:1px solid #ddd;">
                        <div class="" style="text-align: center;padding:5px 5px 10px 5px;margin-bottom: 5px;border-bottom: 5px solid #fff">
                            <textarea name="memo_content" id="memo_content" style="font-size:14px;background-color:transparent;border:1px solid #ddd;color:#000;padding:5px;width:100%;text-align: left;height:50px;" placeholder="메모를 입력해주세요."></textarea>
                        </div>
                        </form>
                        <div class="memo_area" style="width:100%;height:300px;padding:5px;">
                            <ul>
                                <?php for($a = 0; $a<count($memo);$a++){?>
                                    <li title="<?php echo $memo[$a]["memo_content"];?>"><?php echo nl2br($memo[$a]["memo_content"]);?> <i class="fa fa-close" onclick="location.href=g5_url+'/page/memo_update?type=del&return_url=view&me_id=<?php echo $me_id;?>&mb_id=<?php echo $member["mb_id"];?>&depth1_id=<?php echo $depth1_id;?>&depth2_id=<?php echo $depth2_id;?>&id=<?php echo $memo[$a]["id"];?>'"></i></li>
                                <?php }?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="right">
            <table class="view_table_scroll" >
                <tr>
                    <!--th>직업선택</th-->
                    <th style="width:12%;">구분</th>
                    <th style="width:12%;">항목</th>
                    <th style="width:auto;">주요확인내용</th>
                    <th style="width:120px;">참고</th>
                    <?php if($is_member && $mycont && $current_const["const_id"]!=0){?>
                        <th style="width:5%;">확인</th>
                        <th style="width:10%;">제출일</th>
                        <th style="width:6%;">지연일</th>
                    <?php }?>
                </tr>
            </table>
        <table class="view_table" >
            <?php
            if($depth5num > 0) {
            ?>
            <thead>
            <tr>
                <!--th>직업선택</th-->
                <th style="width:12%;">구분</th>
                <th style="width:12%;">항목</th>
                <th style="width:auto;">주요확인내용</th>
                <th style="width:120px;">참고</th>
                <?php if($is_member && $mycont  && $current_const["const_id"]!=0){?>
                    <th style="width:5%;">확인</th>
                    <th style="width:10%;">제출일</th>
                    <th style="width:6%;">지연일</th>
                <?php }?>
            </tr>
            </thead>
            <tbody>
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
                        <strong><?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?></strong>
                    </td>
                        <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) { ?>
                        <td class="depth2 <?php if($pk_id==$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['pk_id']){echo "active";}?>" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" >
                            <strong><?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?></strong>
                        </td>
                            <?php
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

                                /*switch ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['submit_date_type'])
                                {
                                    case 0 :
                                        $sd_type="착공일로 부터";
                                        break;
                                    case 1 :
                                        $sd_type="시험일1로 부터";
                                        break;
                                    case 2 :
                                        $sd_type="시험일1로 부터";
                                        break;
                                    case 3 :
                                        $sd_type="준공일로 부터";
                                        break;
                                    case -1 :
                                        $sd_type="";
                                        break;
                                }*/

                            $depth_last++;
                            $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                            ?>
                            <td class="depth3 <?php if($pk_id==$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]){echo "active";}?>" <?php if($delaylist[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["delay_date"]){?>style="border-left:2px solid red"<?php }?>>
                                <?php if(!$is_member){?>
                                    <span class="gray">로그인 후 이용 가능합니다.</span>
                                <?php }else {?>
                                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content'];?>
                                <?php }?>
                            </td>
                            <td class="etc" id="">
                                <?php if(!$is_member){?>
                                <?php }else{ ?>
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
                                            <input type="button" value="다운로드" style="background-image:url('<?php echo G5_IMG_URL;?>/ic_attach.svg');" onclick="location.href=g5_url+'/page/view_download?file=<?php echo $files2[$w];?>&filename=<?php echo $filenames2[$w];?>'" title="<?php echo $filenames2[$w];?>">
                                        <?php }
                                            }
                                    }?>
                                <?php }?>
                                <?php }?>
                            </td>
                            <?php if($is_member && $mycont && $current_const["const_id"]!=0){?>
                            <td class="confirm" id="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" >
                                <?php if($member["mb_6"]==1){?>
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date_type']!="-1"){?>
                                <input type="checkbox" id="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" <?php if($delayview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active"]==1){ ?>checked onclick="alert('이미 처리된 항목입니다.');return false;"<?php }else{?>onclick="fnCheckDelay('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>','<?php echo $current_const["const_id"];?>');" <?php }?>>
                                <label for="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>"></label>
                                <?php }?>
                                <?php }else{?>
                                    <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date_type']!="-1"){?>
                                        <input type="checkbox" id="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" <?php if($delayview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active"]==1){ ?>checked onclick="alert('이미 처리된 항목입니다.');return false;"<?php }else{?>onclick="fnCheckDelay('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>','<?php echo $current_const["const_id"];?>');" <?php }?>>
                                        <label for="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>"></label>
                                    <?php }else{?>
                                        <input type="checkbox" id="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" <?php if($allview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active"]==1){ ?>checked onclick="alert('이미 처리된 항목입니다.');return false;"<?php }else{?>onclick="fnCheckDelay2('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>','<?php echo $current_const["const_id"];?>');" <?php }?>>
                                        <label for="chk_pk_id_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>" class="other"></label>
                                    <?php }?>
                                <?php }?>
                            </td>
                            <td class="date td_center" id="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>">
                                <?php if($member["mb_6"]==1){?>
                                <?php if($delayview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active_date"] != "0000-00-00"){ echo $delayview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active_date"]; }?>
                                <?php }else{?>
                                    <?php if($allview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active_date"] != "0000-00-00"){ echo $allview[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["pk_active_date"]; }?>
                                <?php }?>
                            </td>
                            <td class="depth6">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date_type']!="-1"){
                                    echo ($delaylist[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["delay_date"])?$delaylist[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']]["delay_date"]:"-";
                                }?>
                            </td>
                            <?php }?>
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
            <thead>
            <colgroup>
                <col width="10%">
                <col width="12%">
                <col width="*">
                <col width="10%">
                <col width="6%">
            </colgroup>
                <tr>
                    <th>구분</th>
                    <th>항목</th>
                    <th>주요확인내용</th>
                    <th>참고</th>
                    <th>기준일</th>
                </tr>
            </thead>
            <tbody>
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
                <td rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>"  class="depth2 <?php if($pk_id==$list[$i]['depth2'][$j]['depth3'][$k]['pk_id']){echo "active";}?>">
                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?>
                </td>
                <?php
                for ($l = 0; $l < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']); $l++) {
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachment"]!="") {
                        $files = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachment"]);
                        $filenames = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachmentname1"]);
                    }else{
                        $files = array();
                        $filenames = array();
                    }
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["link"]!=""){
                        $links = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["link"]);
                        $linknames = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["linkname"]);
                    }else{
                        $links = array();
                        $linknames = array();
                    }
                    if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachment2"]!=""){
                        $files2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachment2"]);
                        $filenames2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["attachmentname2"]);
                    }else{
                        $files2 = array();
                        $filenames2 = array();
                    }
                    /*switch ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['submit_date_type'])
                    {
                        case 0 :
                            $sd_type="착공일로 부터";
                            break;
                        case 1 :
                            $sd_type="시험일1로 부터";
                            break;
                        case 2 :
                            $sd_type="시험일1로 부터";
                            break;
                        case 3 :
                            $sd_type="준공일로 부터";
                            break;
                        case -1 :
                            $sd_type="";
                            break;
                    }*/

                $depth_last++;?>
                <td class="depth3">
                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['content'];?>
                </td>
                <td class="etc">
                    <?php if(!$is_member){?>
                    <?php }else{ ?>
                        <?php if(count($files)>=1){?>
                            <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['pk_id'];?>')">
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
                                        <input type="button" value="다운로드" style="background-image:url('<?php echo G5_IMG_URL;?>/ic_attach.svg');" onclick="location.href=g5_url+'/page/view_download?file=<?php echo $files2[$w];?>&filename=<?php echo $filenames2[$w];?>'" title="<?php echo $filenames2[$w];?>">
                                    <?php }
                                }
                            }?>
                        <?php }?>
                    <?php }?>
                </td>
                <td class="depth6">
                    <?php echo $sd_type.$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['submit_date'];?>
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
                                if($list[$i]['depth2'][$j]['depth3'][$k]["attachment"]!="") {
                                    $files = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]["attachment"]);
                                    $filenames = explode("``", $list[$i]['depth2'][$j]['depth3'][$k]["attachmentname1"]);
                                }else{
                                    $files = array();
                                    $filenames = array();
                                }
                                if($list[$i]['depth2'][$j]['depth3'][$k]["link"]!=""){
                                    $links = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]["link"]);
                                    $linknames = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]["linkname"]);
                                }else{
                                    $links = array();
                                    $linknames = array();
                                }
                                if($list[$i]['depth2'][$j]['depth3'][$k]["attachment2"]!=""){
                                    $files2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]["attachment2"]);
                                    $filenames2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]["attachmentname2"]);
                                }else{
                                    $files2 = array();
                                    $filenames2 = array();
                                }
                                $depth_last++; ?>
                                <td class="depth3 <?php if($pk_id==$list[$i]['depth2'][$j]['depth3'][$k]['pk_id']){echo "active";}?>">
                                    <?php if(!$is_member){?>
                                        <span class="gray">로그인 후 이용 가능합니다.</span>
                                    <?php }else{?>
                                    <?php echo $list[$i]['depth2'][$j]['depth3'][$k]['content']; ?>
                                    <?php }?>
                                </td>
                                <td class="etc" >
                                    <?php if(!$is_member){?>
                                    <?php }else{ ?>
                                        <?php if(count($files)>=1){?>
                                            <input type="button" value="미리보기" onclick="fnViewEtc('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['pk_id'];?>')">
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
                                                        <input type="button" value="다운로드" style="background-image:url('<?php echo G5_IMG_URL;?>/ic_attach.svg');" onclick="location.href=g5_url+'/page/view_download?file=<?php echo $files2[$w];?>&filename=<?php echo $filenames2[$w];?>'" title="<?php echo $filenames2[$w];?>">
                                                    <?php }
                                                }
                                            }?>
                                        <?php }?>
                                    <?php }?>
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
    $(document).scroll(function(){
        var top = $(this).scrollTop();
        if(top > 160){
            if($(".view_table").height()>960 || $(".menu_table").height() > 960) {
                $(".title").hide();
                $(".left").attr("style", "position:fixed;top:3px");
                $(".right").attr("style", "margin-left:220px;");
                $(".view_table_scroll").attr("style", "display:table;position: fixed;top: 0;");
                $(".view_table thead").attr("style", "opacity:0");
            }
        }else{
            $(".title").show();
            $(".left").removeAttr("style");
            $(".right").removeAttr("style");
            $(".view_table_scroll").removeAttr("style");
            $(".view_table thead").removeAttr("style");
        }
    });
    //var tbl_width = $(".menu_table").width();
    //tbl_width = tbl_width + 24;
    //$(".view_table").attr("style","width:calc(100% - "+tbl_width+"px)");

    $("#menu_code").change(function(){
        //선택된 값으로 2dpeth의 옵션 갑 변경
        location.href=g5_url+'/page/view?me_id='+$(this).val();
    });
    $("#depth1_id").change(function(){
        location.href=g5_url+'/page/view?me_id=<?php echo $me_id;?>&depth1_id='+$(this).val();
    });

    $(function(){
        $(document).tooltip();
    });
});

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
function fnCheckDelay(pk_id,const_id){
    $.ajax({
        url:g5_url+'/page/ajax/ajax.my_delay_check.php',
        method:"post",
        data:{pk_id:pk_id,const_id:const_id},
        dataType:"json"
    }).done(function(data){
        if(data.msg=="1"){
            alert("현장을 선택해주세요.");
        }else if(data.msg=="2"){
            alert("선택된 항목이 없습니다.");
        }else if(data.msg=="3"){
            alert("개인설정 오류로 저장할 수 없습니다.")
        }else if(data.msg=="4"){
            alert("이미 제출/확인 된 항목입니다.");
        }else if(data.msg=="5"){
            alert("알 수 없는 오류입니다.\n관리자에게 문의 바랍니다.");
        }else {
            location.reload();
        }
    });
}

function fnCheckDelay2(pk_id,const_id){
    $.ajax({
        url:g5_url+'/page/ajax/ajax.my_delay_check2.php',
        method:"post",
        data:{pk_id:pk_id,const_id:const_id},
        dataType:"json"
    }).done(function(data){
        if(data.msg=="1"){
            alert("현장을 선택해주세요.");
        }else if(data.msg=="2"){
            alert("선택된 항목이 없습니다.");
        }else if(data.msg=="3"){
            alert("개인설정 오류로 저장할 수 없습니다.")
        }else if(data.msg=="4"){
            alert("이미 제출/확인 된 항목입니다.");
        }else if(data.msg=="5"){
            alert("알 수 없는 오류입니다.\n관리자에게 문의 바랍니다.");
        }else {
            location.reload();
        }
    });
}
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
