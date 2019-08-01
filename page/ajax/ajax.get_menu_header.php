<?php
include_once ("../../common.php");

if(!$menu_id){
    $where = " and menu_code like '{$menulist[0]["menu_code"]}%' and menu_code != '{$menulist[0]["menu_code"]}'";
}else{
    $where = " and menu_code like '{$menu_id}%' and menu_code != '{$menu_id}'";
}
$sql = "select * from `cmap_menu` where menu_depth = 1 and menu_name != '' and menu_status = 0 {$where}  order by menu_order";
$res = sql_query($sql);
$i=0;
while($row = sql_fetch_array($res)){
    $sql = "select count(*) as cnt from `cmap_depth1` where me_code = '{$row["menu_code"]}'";
    $menucnt = sql_fetch($sql);
    $menudepth[$i] = $row;
    $menudepth[$i]["cnt"]=$menucnt["cnt"];
    $menudepth[$i]["maxcnt"] = $menucnt["cnt"];
    if($i<=5){
        if($i>0) {
            //echo $menudepth[$i - 1]["maxcnt"]."//".$menudepth[$i]["maxcnt"]."<br>";
            if ($menudepth[$i - 1]["maxcnt"] > $menudepth[$i]["maxcnt"]) {
                //echo "A<br>";
                $menudepth[$i]["maxcnt"] = $menudepth[$i - 1]["maxcnt"];
            } else if ($menudepth[$i - 1]["maxcnt"] < $menudepth[$i]["maxcnt"]) {
                //echo "B<br>";
                $menudepth[$i - 1]["maxcnt"] = $menudepth[$i]["maxcnt"];
            }
        }
    }else if($i<=11){
        if($i>6) {
            if ($menudepth[$i - 1]["maxcnt"] > $menudepth[$i]["maxcnt"]) {
                $menudepth[$i]["maxcnt"] = $menudepth[$i - 1]["maxcnt"];
            } else if ($menudepth[$i - 1]["maxcnt"] < $menudepth[$i]["maxcnt"]) {
                $menudepth[$i - 1]["maxcnt"] = $menudepth[$i]["maxcnt"];
            }
        }
    }else if($i<=17){
        if($i>12) {
            if ($menudepth[$i - 1]["maxcnt"] > $menudepth[$i]["maxcnt"]) {
                $menudepth[$i]["maxcnt"] = $menudepth[$i - 1]["maxcnt"];
            } else if ($menudepth[$i - 1]["maxcnt"] < $menudepth[$i]["maxcnt"]) {
                $menudepth[$i - 1]["maxcnt"] = $menudepth[$i]["maxcnt"];
            }
        }
    }else if($i<=23){
        if($i>18) {
            if ($menudepth[$i - 1]["maxcnt"] > $menudepth[$i]["maxcnt"]) {
                $menudepth[$i]["maxcnt"] = $menudepth[$i - 1]["maxcnt"];
            } else if ($menudepth[$i - 1]["maxcnt"] < $menudepth[$i]["maxcnt"]) {
                $menudepth[$i - 1]["maxcnt"] = $menudepth[$i]["maxcnt"];
            }
        }
    }
    $i++;
}

if($menu_id){
    $wh = " and menu_code = '{$menu_id}'";
}else{
    $wh = " and menu_code = '{$menulist[0]["menu_code"]}'";
}

$sql = "select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' {$wh}";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $mynavi[] = $row;
}

if(count($mynavi)==0){
    for($i=0;$i<count($menudepth);$i++){
        $menudepth[$i]["active"]=1;
        $sql = "select * from `cmap_depth1` where me_code = '{$menudepth[$i]["menu_code"]}'";
        $res = sql_query($sql);
        while($row = sql_fetch_array($res)){
            $subs[$menudepth[$i]["me_id"]][$row["pk_id"]]=1;
        }
    }
}else{
    for($a=0;$a<count($mynavi);$a++) {
        $ids = explode("``", $mynavi[$a]["menu_ids"]);
        $subids = explode("``", $mynavi[$a]["sub_ids"]);
        $actives = explode("``", $mynavi[$a]["menu_ids_actives"]);
        $sub_actives = explode("``", $mynavi[$a]["sub_ids_actives"]);
        for ($i = 0; $i < count($actives); $i++) {
            $menudepth[$i]["active"] = $actives[$i];
        }

        for($c=0;$c<count($subids);$c++){
            $sub = explode("|",$subids[$c]);
            $subs[$sub[0]][$sub[1]] = $sub_actives[$c];
        }
    }
}
 for($i=0;$i<count($menudepth);$i++){
    //$sql = "select count(*) as cnt from `cmap_depth1`";
    if($menudepth[$i]["cnt"]>1){
        $sql = "select * from `cmap_depth1` where me_code ='{$menudepth[$i]["menu_code"]}' order by id ";
        $res = sql_query($sql);
    }
    if($menu_id=="60"){
        if($menudepth.$menudepth[$i]["me_id"] == "6064"){
            $link = "/page/view2?me_id=".$menudepth[$i]["menu_code"];
        }else{
            $link = "/page/view3?me_id=".$menudepth[$i]["menu_code"];
        }
    }else{
        $link = "/page/view?me_id=".$menudepth[$i]["menu_code"];
    }
     ?>
    <li class="depths" style="height:<?php echo ($menudepth[$i]["maxcnt"]>1)?((48 * $menudepth[$i]["maxcnt"]) + 50)."px":"auto";?>" >
        <div title="<?php echo $row["depth_name"];?>" onclick="location.href=g5_url+'<?php echo $link;?>'"><span><?php echo $menudepth[$i]["menu_name"];?></span></div>
        <ul class="depth_menu2">
            <?php while($row = sql_fetch_array($res)){
                if($menu_id=="60") {
                    if($menudepth.$menudepth[$i]["me_id"] == "6064"){
                        $link = "/page/view2?me_id=".$menudepth[$i]["menu_code"] . "&depth1_id=" . $row["id"];
                    }else{
                        $link = "/page/view3?me_id=".$menudepth[$i]["menu_code"] . "&depth1_id=" . $row["id"];
                    }
                }else{
                    $link = "/page/view?me_id=" . $menudepth[$i]['menu_code'] . "&depth1_id=" . $row["id"];
                }
                ?>
                <li title="<?php echo $row["depth_name"];?>" onclick="location.href=g5_url+'<?php echo $link;?>'"><?php echo $row["depth_name"];?></li>
            <?php }?>
        </ul>
    </li>
<?php }?>