<?php
include_once ("../../common.php");
$sub="sub";
$mypage=true;
$menu_id = "depth_desc_navi";
include_once (G5_PATH."/page/mypage/confirm.php");
include_once (G5_PATH."/_head.php");

$menu_id = $_REQUEST["menu_id"];
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
?>
<div class="full-width" style="padding:0 20px;">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>MY C.MAP</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout"><span></span>로그아웃</a>
            </div>
        </header>
        <aside class="mypage_menu">
            <div class="mtop">
                <?php echo $member["mb_id"];?>님 회원정보
            </div>
            <div class="mbottom">
                <ul class="mmenu">
                    <li onclick="location.href=g5_url+'/page/mypage/mypage'"><i></i>홈페이지 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/quickmenu'"><i></i>퀵메뉴 설정</li>
                    <li class="active" ><i></i>네비게이터 설정</li>
                    <!--<li onclick="location.href=g5_url+'/page/mypage/guide'"><i></i>사용자 가이드 설정</li>-->
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd'"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>네비게이터 설정 </h2>
            </header>
            <div class="navigator_con" style="margin-bottom:40px;">
                <ul class="sel_set">
                    <li><input type="radio" name="set_navichk" id="set_navichk1" value="0" <?php if($member["mb_6"]==0 || $member["mb_6"]==""){echo "checked";}?> onclick="fnNaviSet(0)"><label for="set_navichk1"></label> <span>전체항목 표기 : 전체항목에 대한 이행여부를 확인할 수 있도록 확인기능을 표기합니다.</span>
                    </li>
                    <li><input type="radio" name="set_navichk" id="set_navichk2" value="1" <?php if($member["mb_6"]==1 ){echo "checked";}?>><label for="set_navichk2" onclick="fnNaviSet(1)"></label> <span>주요항목 표기 : 공사수행 시 의무 또는 필수로 처리하여야 하는 주요 행정업무에 대하여 이행여부 확인기능을 표기합니다.
</span></li>
                </ul>
            </div>
            <header>
                <h2>네비게이터 세부설정 <input type="button" onclick="fnNaviSave();" value="저장" class="basic_btn02" id="navi_save"></h2>
            </header>
            <div class="navigator_con">
                <p>건설관리업무의 적부여부를 설정하여 볼 수 있습니다.</p>
                <div class="navigator_tab">
                    <ul>
                        <?php for($i=0;$i<count($menulist);$i++){?>
                            <li class="<?php if(($menu_id=='' && $i==0) || $menu_id == $menulist[$i]["menu_code"]){?>active<?php }?>" onclick="fnMenus('<?php echo $menulist[$i]["menu_code"];?>')"><?php echo $menulist[$i]["menu_name"];?></li>
                        <?php }?>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="all_chks">
                    <input type="checkbox" name="all_chk" id="all_chk" style="display: none" > <label for="all_chk">전체선택 / 해제</label>
                </div>
                <div class="menus">
                    <form action="" name="naviForm" id="naviForm">
                    <ul class="depth_menu">
                        <?php for($i=0;$i<count($menudepth);$i++){
                            //$sql = "select count(*) as cnt from `cmap_depth1`";
                            if($menudepth[$i]["cnt"]>1){
                                $sql = "select * from `cmap_depth1` where me_code ='{$menudepth[$i]["menu_code"]}'";
                                $res = sql_query($sql);
                            }
                            ?>
                            <li class="depths" style="height:<?php echo ((48 * $menudepth[$i]["maxcnt"]) + 50);?>px">
                                <div title="<?php echo $row["depth_name"];?>">
                                    <input type="checkbox" name="menu_depth1[]" value="<?php echo $menudepth[$i]["me_id"];?>" <?php if($menudepth[$i]["active"]==1){?>checked<?php }?> id="menu_depth1_<?php echo $menudepth[$i]["me_id"];?>" style="display: none">
                                    <label for="menu_depth1_<?php echo $menudepth[$i]["me_id"];?>"></label>
                                    <span><?php echo $menudepth[$i]["menu_name"];?></span>
                                </div>
                                <ul class="depth_menu2">
                                    <?php while($row = sql_fetch_array($res)){ ?>
                                        <li title="<?php echo $row["depth_name"];?>">
                                            <input type="checkbox" name="menu_depth2[]" class="menu_parent_<?php echo $menudepth[$i]["me_id"];?>" id="menu_depth2_<?php echo $row["pk_id"];?>" style="display: none" value="<?php echo $row["pk_id"];?>" <?php if($subs[$menudepth[$i]["me_id"]][$row["pk_id"]]== 1){?>checked<?php }?>>
                                            <label for="menu_depth2_<?php echo $row["pk_id"];?>"></label>
                                            <?php echo $row["depth_name"];?>
                                        </li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?>
                    </ul>
                    </form>
                    <div class="clear"></div>
                </div>
            </div>
        </article>
    </section>
</div>
<script>
    function fnMenus(me_code) {
        location.href=g5_url+'/page/mypage/navigator?menu_id='+me_code;
    }

    var checkCnt = $("input[type=checkbox]").length - 1;

    $(function(){
        $("#all_chk").click(function(){
           if($(this).prop("checked")==true){
               $("input[type=checkbox]").each(function(){
                    $(this).prop("checked",true) ;
               });
           }else{
               $("input[type=checkbox]").each(function(){
                   $(this).removeAttr("checked");
               });
           }
        });
        $("input[id^=menu_depth1_]").each(function(){
            $(this).click(function(){
                var id = $(this).val();
                if($(this).prop("checked")==true){
                    $(".menu_parent_"+id).prop("checked",true);
                }else{
                    $(".menu_parent_"+id).removeAttr("checked");
                    $("#all_chk").removeAttr("checked");
                }
            });
            //allCheck();
        });

        $("input[id^=menu_depth2_]").each(function(){
            $(this).click(function(){
                allCheck();
            });
        });
    });

    function allCheck(){
        var check1 = $("input[id^=menu_depth1_]:checked").length;
        var check2 = $("input[id^=menu_depth2_]:checked").length;
        var checked = check1 + check2;
        console.log(checked+"//"+checkCnt);
        if(checked != checkCnt){
            $("#all_chk").removeAttr("checked");
        }else{
            $("#all_chk").prop("checked",true);
        }
    }

    function fnNaviSave(){
        var depth1='',depth2='';
        $("input[id^='menu_depth1_']").each(function(){
            if($(this).prop("checked")){
                if(depth1==''){
                    depth1 = $(this).val()+":1";
                }else {
                    depth1 += "``"+$(this).val()+":1";
                }
            }else{
                if(depth1==''){
                    depth1 = $(this).val()+":0";
                }else {
                    depth1 += "``"+$(this).val()+":0";
                }
            }
        });
        $("input[id^='menu_depth2_']").each(function(){
            var parent = $(this).attr("class").replace("menu_parent_","");
            if($(this).prop("checked")){
                if(depth2==''){
                    depth2 = parent+"|"+$(this).val()+":1";
                }else {
                    depth2 += "``"+parent+"|"+$(this).val()+":1";
                }
            }else{
                if(depth2==''){
                    depth2 = parent+"|"+$(this).val()+":0";
                }else {
                    depth2 += "``"+parent+"|"+$(this).val()+":0";
                }
            }
        });

        $.ajax({
            url:g5_url+"/page/ajax/ajax.navigator_update.php",
            method:"post",
            data:{depth1:depth1,depth2:depth2,menu_code:"<?php echo $menu_id;?>"}
        }).done(function(data){
            console.log(data);
            if(data=="success"){
                alert("저장 완료");
            }else if(data=="failed"){
                alert("저장 실패, 새로고침후 다시 시도해 주세요.")
            }
        });
    }

    function fnNaviSet(value){
        var chk = $("input[name^=set_navichk]:checked").length
        if(chk<=0){
            alert("설정을 선택해주세요");
            return false;
        }

        $.ajax({
            url:g5_url+'/page/mypage/ajax.update_naviset.php',
            method:"post",
            data:{value:value}
        });
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
