<?php
include_once ("../../common.php");
$sub = "search";
$mypage = false;
include_once (G5_PATH."/head.php");

//검색어 저장
$sql = "insert into `cmap_search_log` set search_text = '{$search_text}', search_type = '{$search_type}', search_date = now(), search_time = now()";
sql_query($sql);

//메뉴 탭
$sql = "select * from `cmap_menu` where LENGTH(menu_code)=2 and menu_status = 0 order by menu_order";
$res = sql_query($sql);
$s=0;
while($row = sql_fetch_array($res)){
    $tabmenu[$s] = $row;
    $tabmenu[$s]["cnt"] = 0;
    $s++;
}

$sql = "select * from `cmap_content` as c , `cmap_menu` as m where (select me_id from `cmap_depth1` as d where c.depth1_id = d.id) = m.menu_code and m.menu_status = 0 and  (INSTR(`content`,'{$search_text}') > 0 or INSTR(`linkname`,'{$search_text}') > 0) order by m.menu_order ";
$res = sql_query($sql);

$total=0;
$a = 0;
while($row = sql_fetch_array($res)){
    if($row["depth4_id"]){
        $sql = "select * from `cmap_depth4` where id = '{$row["depth4_id"]}'";
        $depth4 = sql_fetch($sql);
    }
    if($row["depth3_id"]){
        $sql = "select * from `cmap_depth3` where id = '{$row["depth3_id"]}'";
        $depth3 = sql_fetch($sql);
    }
    if($row["depth2_id"]){
        $sql = "select * from `cmap_depth2` where id = '{$row["depth2_id"]}'";
        $depth2 = sql_fetch($sql);
    }
    if($row["depth1_id"]){
        $sql = "select * from `cmap_depth1` where id = '{$row["depth1_id"]}'";
        $depth1 = sql_fetch($sql);
    }

    $sql = "select * from `cmap_menu` where menu_code = '{$depth1["me_code"]}'";
    $chk = sql_fetch($sql);
    if($chk["menu_status"]!=0){
        continue;
    }

    if($depth1["me_code"]){
        $sql = "select * from `cmap_menu` where menu_code = LEFT('{$depth1["me_code"]}',2) and menu_name != '' and menu_status = 0 order by menu_order";
        $depth_menu = sql_fetch($sql);

        $menusss[$total] = $depth_menu["menu_name"];
        for($j=0;$j<count($tabmenu);$j++){
            if(substr($depth_menu["menu_code"],0,2)==$tabmenu[$j]["menu_code"]){
                $sql = "select * from `cmap_menu` where menu_code = '{$depth1["me_code"]}'";
                $chk = sql_fetch($sql);
                if($chk["menu_status"]!=0){
                    continue;
                }
                $tabmenu[$j]["cnt"]++;
            }
        }
    }

    if($a!=0){
        $a2 = $total - 1;
        if($menusss[$total] != $menusss[$a2]){
            $a = 0;
        }
    }

    $content[$depth_menu["menu_name"]][$a] = $row;
    $content[$depth_menu["menu_name"]][$a]["menu"] = $depth_menu["menu_name"];
    $content[$depth_menu["menu_name"]][$a]["me_code"] = $depth1["me_code"];
    if($depth1["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] = $depth1["depth_name"];
    }
    if($depth2["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] .= " > ".$depth2["depth_name"];
    }
    if($depth3["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] .= " > ".$depth3["depth_name"];
    }
    if($depth4["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] .= " > ".$depth4["depth_name"];
    }
    $a++;
    $total++;
}

$sql = "select depth1_id,depth2_id,depth3_id,c.depth_name from `cmap_depth4` as c left join `cmap_depth3` as b on c.depth3_id = b.id left join `cmap_menu` as m on m.menu_code <> '' where (select me_id from `cmap_depth1` as d where c.depth1_id = d.id) = m.menu_code and m.menu_status = 0 and (INSTR(c.depth_name,'{$search_text}') > 0 or INSTR(b.depth_name,'{$search_text}') > 0)  order by m.menu_order ";
/*
$sql = "select * from `cmap_depth1` where INSTR(depth_name,'{$search_text}') > 0 and menu_status = 0 UNION select * from `cmap_depth2` where INSTR(depth_name,'{$search_text}') > 0 and menu_status = 0  UNION select * from `cmap_depth3` where INSTR(depth_name,'{$search_text}') > 0 UNION select * from `cmap_depth4` where INSTR(depth_name,'{$search_text}') > 0 UNION select * from `cmap_content` where INSTR(content,'{$search_text}') > 0 ";*/

$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    if($row["depth3_id"]){
        $sql = "select * from `cmap_depth3` where id = '{$row["depth3_id"]}'";
        $depth3 = sql_fetch($sql);
    }
    if($row["depth2_id"]){
        $sql = "select * from `cmap_depth2` where id = '{$row["depth2_id"]}'";
        $depth2 = sql_fetch($sql);
    }
    if($row["depth1_id"]){
        $sql = "select * from `cmap_depth1` where id = '{$row["depth1_id"]}'";
        $depth1 = sql_fetch($sql);
    }

    $sql = "select * from `cmap_menu` where menu_code = '{$depth1["me_code"]}'";
    $chk = sql_fetch($sql);
    if($chk["menu_status"]!=0){
        continue;
    }

    if($depth1["me_code"]){

        $sql = "select * from `cmap_menu` where menu_code = left('{$depth1["me_code"]}',2) and menu_name != '' and menu_status = 0 order by menu_order";
        $depth_menu = sql_fetch($sql);
        $menusss[$total] = $depth_menu["menu_name"];
        for($j=0;$j<count($tabmenu);$j++){
            if(substr($depth_menu["menu_code"],0,2)==$tabmenu[$j]["menu_code"]){
                $sql = "select * from `cmap_menu` where menu_code = '{$depth1["me_code"]}'";
                $chk = sql_fetch($sql);
                if($chk["menu_status"]!=0) {
                    continue;
                }
                $tabmenu[$j]["cnt"]++;
            }
        }
    }
    $a = count($content[$depth_menu["menu_name"]]);
    $content[$depth_menu["menu_name"]][$a] = $row;
    $content[$depth_menu["menu_name"]][$a]["menu"] = $depth_menu["menu_name"];
    $content[$depth_menu["menu_name"]][$a]["me_code"] = $depth1["me_code"];
    $content[$depth_menu["menu_name"]][$a]["content"] = $row["depth_name"];
    if($depth1["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] = $depth1["depth_name"];
    }
    if($depth2["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] .= " > ".$depth2["depth_name"];
    }
    if($depth3["depth_name"]){
        $content[$depth_menu["menu_name"]][$a]["navis"] .= " > ".$depth3["depth_name"];
    }

    //$a++;
    $total++;
}

?>
<div class="width-fixed">
    <section class="sub_sec">
        <article class="searchs">
            <form action="<?php echo G5_URL;?>/page/search/search" method="post" name="searchFrom">
                <select name="search_type" id="search_type">
                    <option value="">전체검색</option>
                    <?php for($i=0;$i<count($search_menu);$i++){?>
                        <option value="<?php echo $search_menu[$i]["menu_code"];?>" <?php if($search_type==$search_menu[$i]["menu_code"]){echo "selected";}?>><?php echo $search_menu[$i]["menu_name"];?></option>
                    <?php }?>
                </select>
                <input type="text" name="search_text" value="<?php echo $search_text;?>" class="search_input" placeholder="검색어를 입력해주세요.">
                <input type="submit" value="" name="search_btn">
            </form>
        </article>
        <div class="search_tab">
            <div class="result_text">
                <span><img src="<?php echo G5_IMG_URL;?>/ic_search.svg" alt=""> "<?php echo $search_text;?>"</span>에 대한 통합검색 결과는 총 <?php echo number_format($total);?>건 입니다.
            </div>
            <ul>
                <li onclick="location.href=g5_url+'/page/search/search?search_text=<?php echo $search_text;?>'"  class="all <?php if($search_type==""){?>active<?php }?>">통합검색 <?php echo " (".number_format($total).")건";?></li>
                <?php for($i=0;$i<count($tabmenu);$i++){?>
                    <li onclick="location.href=g5_url+'/page/search/search?search_type=<?php echo $tabmenu[$i]["menu_code"];?>&search_text=<?php echo $search_text;?>'" <?php if($tabmenu[$i]["menu_code"]==$search_type){?>class="active"<?php }?>><?php echo $tabmenu[$i]["menu_name"]." (".number_format($tabmenu[$i]["cnt"])."건)";?></li>
                <?php }?>
                <li onclick="fnPortalSearch('<?php echo $search_text;?>');">포털검색</li>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="search_list">
            <?php for($i=0;$i<count($tabmenu);$i++){
                if($search_type!='' && $search_type != $tabmenu[$i]["menu_code"]){continue;}
                ?>
                <div class="search_type_title">
                    <h2><?php echo $tabmenu[$i]["menu_name"]." (".$tabmenu[$i]["cnt"]."건)";?> <span onclick="location.href=g5_url+'/page/search/search?search_type=<?php echo $tabmenu[$i]["menu_code"];?>&search_text=<?php echo $search_text;?>&more=1'"> VIEW MORE </span></h2>
                </div>
                <?php
                for($j=0;$j<count($content[$tabmenu[$i]["menu_name"]]);$j++){
                    if($search_type=='' && $j>3){continue;}
                    else if($search_type!='' && $more == '' && $j >= 5){
                        continue;
                    }

                    $link = "me_id=".$content[$tabmenu[$i]["menu_name"]][$j]["me_code"];
                    $link .= "&depth1_id=".$content[$tabmenu[$i]["menu_name"]][$j]["depth1_id"];
                    $link .= "&depth2_id=".$content[$tabmenu[$i]["menu_name"]][$j]["depth2_id"];
                    $link .= "&pk_id=".$content[$tabmenu[$i]["menu_name"]][$j]["pk_id"];
                    if(strpos($content[$tabmenu[$i]["menu_name"]][$j]["content"],'``')!==false){
                        $sub_content = str_replace("``"," | ",$content[$tabmenu[$i]["menu_name"]][$j]["content"]);
                    }else{
                        $sub_content = $content[$tabmenu[$i]["menu_name"]][$j]["content"];
                    }
                    ?>
                    <div class="item" onclick="location.href=g5_url+'/page/view<?php if($content[$tabmenu[$i]["menu_name"]][$j]["me_code"]=="6064"){echo "2";}else if($content[$tabmenu[$i]["menu_name"]][$j]["me_code"]=="60129"){echo "3";}?>?<?php echo $link;?>'">
                        <h2><?php echo $content[$tabmenu[$i]["menu_name"]][$j]["navis"];?></h2>
                        <p><?php echo $sub_content;?></p>
                    </div>
                <?php }
                if(count($content[$tabmenu[$i]["menu_name"]])==0){ ?>
                    <div class="item no-list">
                        <p>검색된 결과가 없습니다.</p>
                    </div>
                <?php }?>
            <?php }?>
        </div>
    </section>
</div>
<script>
    function fnPortalSearch(search_text){
        $.ajax({
            url:g5_url+'/page/modal/ajax.portal.php',
            method:"post",
            data:{search_text:search_text}
        }).done(function(data){
            fnShowModal(data);
        });
    }
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
