<?php
include_once ("../common.php");
$sub="sub";
if($member["mb_auth"]==false){
    alert("무료 이용기간이 만료 되었거나,\\r맴버쉽 기간이 만료 되었습니다. \\n맴버쉽 구매후 이용바랍니다.",G5_URL);
}
//평가 항목 가저오기
if($is_member && $mycont && $current_const["const_id"]!=0){
    $evaldata = sql_fetch("select * from `cmap_my_construct_eval` where mb_id='{$member["mb_id"]}' and const_id = '{$current_const["const_id"]}'");
    $pk_ids = explode("``",$evaldata["pk_ids2"]);
    $pk_scores = explode("``",$evaldata["pk_score2"]);
    for($i=0;$i<count($pk_ids);$i++){
        $scores[$pk_ids[$i]]["score"] = (double)$pk_scores[$i];
        $scores[$pk_ids[$i]]["pk_row_active"] = $evaldata["pk_row_active"];
    }
}
if(strlen($me_id)==2){
    $sql = "select * from `cmap_depth1` where SUBSTRING(me_code,1,2) like '%{$me_id}%' order by me_code asc limit 0,1 ";
    $codes = sql_fetch($sql);
    $incode = $codes["me_code"];
    if(!$depth1_id) $depth1_id = $codes["id"];
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
        if(!$depth1_id) $depth1_id = $codes["id"];
        $sql = "select * from `cmap_depth2` where depth1_id = '{$depth1_id}' order by id asc limit 0, 1";
        $depth2 = sql_fetch($sql);
        $depth2_id = $depth2["id"];
    }
}
if($depth1_id){
    $where = " and depth1_id = '{$depth1_id}'";

    switch ($depth1_id){
        case "339":
            $step = 1;
            break;
        case "340":
            $step = 2;
            break;
        case "341":
            $step = 3;
            break;
        case "342":
            $step = 4;
            break;
        case "343":
            $step = 5;
            break;
        case "344":
            $step = 6;
            break;
        case "345":
            $step = 7;
            break;
        case "346":
            $step = 8;
            break;
    }
}

//메모 불러오기
$sql = "select * from `cmap_mymemo` where me_id = '{$me_id}' and mb_id = '{$member["mb_id"]}' {$where}  order by id desc";
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

include_once (G5_PATH."/_head.php");

?>

<div class="full-width">
    <div class="view">
        <div class="left">
        <div class="title">
            <?php echo $menu1_info["menu_name"];?> | <?php echo $menu2_info["menu_name"];?>
        </div>
        <table class="menu_table view3" >
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
                    <td class="menu_padding"><input type="button"  style="width:180px;" value="<?php echo $depth_menu[$i]['depth_name'];?>" class="depth_btn <?php if($depth_menu[$i]["id"]==$depth1_id){?>active<?php }?>" onclick="location.href=g5_url+'/page/view3?me_id=<?php echo $me_id;?>&depth1_id=<?php echo $depth_menu[$i]["id"];?>'" ></td>
                </tr>
            <?php }?>
            <tr class="memo">
                <td style="position: relative" colspan="2">
                    <form action="<?php echo G5_URL;?>/page/memo_update.php" method="post" >
                        <input type="hidden" name="return_url" value="view3">
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
                    <?php }?>
                    <th style="width:6%;">지연일</th>
                </tr>
            </table>
        <table class="view_table" >
            <tr>
                <!--th>직업선택</th-->
                <th colspan="2">평가항목</th>
                <th rowspan="2">배점</th>
                <th colspan="5">평가등급</th>
                <?php if($is_member && $mycont && $current_const["const_id"]!=0){?>
                    <th rowspan="2">점수</th>
                <?php }?>
                <th rowspan="2">평가방법</th>
            </tr>
            <tr>
                <th>중분류(배점)</th>
                <th>세부분류 (평가방법 미리보기)</th>
                <th style="width:11%">우수 ( X 1.0)</th>
                <th style="width:11%">보통 ( X 0.9)</th>
                <th style="width:11%">미흡 ( X 0.8)</th>
                <th style="width:11%">불량 ( X 0.7)</th>
                <th style="width:11%">불량 ( X 0.6)</th>
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
                        <strong><?php echo $list[$i]['depth2'][$j]['depth_name'];?></strong>
                    </td>
                    <?php for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                        ?>
                        <td class="depth2" rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>" >
                            <strong><?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?></strong>
                        </td>
                        <?php for ($l=0;$l<count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']);$l++) {
                            $total += (float)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                            if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name']=="0"){
                                echo "";
                            }else{
                            ?>
                            <td class="depth3" style="text-align: center;background-color:#e4f8f9" rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" style="text-align: center" >
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
                                if(!$is_member){
                                    echo "<td colspan='5' class='td_center'>로그인 후 이용 가능</td>";
                                }else{
                                if(count($eval)>0){
                                    for($o=0;$o<count($eval);$o++){
                                        //if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22090"){continue;}
                                        /*if(count($eval)==($o+1)) {
                                            $total2 += (float)$eval;
                                        }*/
                                        switch ($o){
                                            case 0:
                                                $nums = 1;
                                                break;
                                            case 1:
                                                $nums = 0.9;
                                                break;
                                            case 2:
                                                $nums = 0.8;
                                                break;
                                            case 3:
                                                $nums = 0.7;
                                                break;
                                            case 4:
                                                $nums = 0.6;
                                                break;
                                        }

                                        if($span[$o]>1){
                                            //특별 조건 IOS 획득여부
                                            if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22078" && $o == 1){
                                                $nums = 0;
                                            }
                                        }
                                        $basic = (double)$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];
                                        if($basic==0){
                                            $c = $k-1;
                                            $basic = (double)$list[$i]['depth2'][$j]['depth3'][$c]['depth4'][$l]['depth_name'];
                                        }
                                        $cals = (double)$basic * (double)$nums;
                                        if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22126" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22127" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22128" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22129" ){
                                            $direct = (int)$eval[$o];
                                            $cals = $direct;
                                        }else{
                                            $direct = null;
                                        }

                                        if($eval[$o]=="") continue;


                                        if((round((double)$cals,2) == round((double)$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"],2)) || (($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22126" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22127" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22128" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]== "22129" ) && $eval[$o]==$scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"])){
                                            $active = "active";
                                        }else{
                                            $active = "";
                                        }
                                        if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22090" || $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22089" ) {
                                            if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22090" && $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["pk_row_active"] == 0) {
                                                $active = "";
                                            }
                                            if ($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"] == "22089" && $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["pk_row_active"] == 1) {
                                                $active = "";
                                            }
                                        }

                                        ?>
                                        <td class="depth4 scores_<?php echo $o;?> pk_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"];?> <?php echo $active ?>" id="scoretd_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"];?>_<?php echo $o;?>"  style="text-align: center" colspan="<?php echo $span[$o];?>" onclick="fnUpdateNumber('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"];?>','<?php echo $o;?>','<?php echo $current_const["const_id"];?>','<?php echo $basic;?>','<?php echo $direct;?>');">
                                            <?php
                                            echo $eval[$o];
                                            ?>
                                        </td>
                                    <?php }?>
                                <?php }?>
                                <?php }?>
                                <?php if($is_member && $mycont && $current_const["const_id"]!=0 ){?>
                                    <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id']!="22090"){?>
                                    <td class="score_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?> td_center" style="font-weight:bold;background-color:#e4f8f9; " <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]=="22089"){?>rowspan="2" <?php }?> >
                                        <!-- 점수 -->
                                        <?php echo $scores[$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"]]["score"];?>
                                    </td>
                                    <?php }?>
                                <?php }?>
                                <td class="etc" id="" >
                                    <?php if($is_member){?>
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
                    <?php if($is_member && $mycont && $current_const["const_id"]!=0){?>
                        <td class="td_center alltotal"></td>
                    <?php }?>
                    <td></td>
                </tr>
                <?php
            }?>
        </table>
        <div class="clear"></div>
        </div>
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
        setTotal();

        $(document).scroll(function(){
            var top = $(this).scrollTop();
            if(top > 160){
                if($(".view_table").height()>960 || $(".menu_table").height() > 960) {
                    $(".left").attr("style", "position:fixed;top:20px");
                    $(".right").attr("style", "margin-left:220px;");
                    $(".view_table_scroll").attr("style", "display:table;position: fixed;top: 0;");
                    $(".view_table thead").attr("style", "opacity:0");
                }
            }else{
                $(".left").removeAttr("style");
                $(".right").removeAttr("style");
                $(".view_table_scroll").removeAttr("style");
                $(".view_table thead").removeAttr("style");
            }
        });
        /*var tbl_width = $(".menu_table").width();
        tbl_width = tbl_width + 24;
        $(".view_table").attr("style","width:calc(100% - "+tbl_width+"px)");*/

        $("#menu_code").change(function(){
            //선택된 값으로 2dpeth의 옵션 갑 변경
            location.href=g5_url+'/page/view?me_id='+$(this).val();
        });
        $("#me_id").change(function(){
            var id = $(this).val();
            if(id == 60129){
                location.href = g5_url + '/page/view3?me_id=' + id;
            }else {
                location.href = g5_url + '/page/view2?me_id=' + id;
            }
        });

        <?php if($is_member && $mycont && $current_const["const_id"]!=0){?>
        $("td[id^=scoretd_]").each(function(e){
            $(this).click(function(){
                var id = $(this).attr("id");
                var split_id = id.split("_");
                if(!$(this).hasClass("active")) {
                    $(this).addClass("active");
                    $("td[id^=scoretd_"+split_id[1]+"]").not($(this)).removeClass("active");
                }
            });
        });
        <?php }?>

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

    function fnUpdateNumber(pk_id,num,constid,score_cnt,direct_point){
        $.ajax({
            url:g5_url+'/page/ajax/ajax.my_construct_eval.php',
            method:"post",
            data:{pk_id:pk_id,num:num,constid:constid,score_cnt:score_cnt,page:2,direct_point:direct_point},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            if(data.msg=="1"){
                alert("선택된 현장이 없습니다.");
                return false;
            }else if(data.msg=="2"){
                alert("선택된 항목이 없습니다.");
                return false;
            }else if(data.msg=="3"){
                alert("점수 기록에 실패 하였습니다.");
                return false;
            }else{
                if(pk_id=="22090" || pk_id == "22089"){
                    if(pk_id=="22090"){
                        //pk_id="22089";
                        $(".pk_22089").removeClass("active");
                    }else{
                        $(".pk_22090").removeClass("active");
                    }
                    $(".score_22089").text(data.score);
                }else {
                    $(".score_" + pk_id).text(data.score);
                }
                setTotal();
            }
        });
    }
    function setTotal(){
        var score = 0;
        $("td[class^=score]").each(function(e){
            console.log(e);
            score += Number($(this).text());
        });
        console.log(score);
        score = score.toFixed(2);
        $(".alltotal").text(score);

        $.ajax({
            url:g5_url+'/page/ajax/ajax.my_construct_eval_total.php',
            method:"post",
            data:{step:"<?php echo $step;?>",page:2,constid:"<?php echo $current_const["const_id"];?>",total:score}
        }).done(function(data){
            console.log(data);
        })
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
