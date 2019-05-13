<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
$menu_id = "depth_desc_quick";
include_once (G5_PATH."/_head.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php");
}
/*if($is_admin){
    alert("관리자는 관리자페이지를 통해 이용 바랍니다.");
}*/
$quickcnt =  count($setquick);
if($quickcnt>0){
    $menuorder = explode("``",$setquick["quick_menu"]);
    $menuordername = explode("``",$setquick["quick_menu_name"]);
    $menuorderstatus = explode("``",$setquick["quick_menu_status"]);
    for($i=0;$i<count($menuorder);$i++){
        if($member["mb_level"]<5 && $menuorder[$i]=="pm_mode"){
            continue;
        }
        if($menuorderstatus[$i]==0){
            $menuquick[$i] = "<li class='ui-state-default'>";
            $menuquick[$i] .= "<input type='checkbox' value='' name='".$menuorder[$i]."' id='".$menuorder[$i]."' value='1'>";
            $menuquick[$i] .= "<label for='".$menuorder[$i]."'><span></span> ".$menuordername[$i]." <img src='".G5_IMG_URL."/ic_".$menuorder[$i].".svg' alt='".$menuorder[$i]."'></label>";
            $menuquick[$i] .= "<input type='hidden' value='".trim($menuordername[$i])."' id='in_".$menuorder[$i]."'>";
            $menuquick[$i] .= "</li>";
        }else{
            $menuquick[$i] = "<li class='ui-state-default'>";
            $menuquick[$i] .= "<input type='checkbox' value='1' name='".$menuorder[$i]."'  id='".$menuorder[$i]."' value='1' checked>";
            $menuquick[$i] .= "<label for='".$menuorder[$i]."'><span></span> ".$menuordername[$i]." <img src='".G5_IMG_URL."/ic_".$menuorder[$i].".svg' alt='".$menuorder[$i]."'></label>";
            $menuquick[$i] .= "<input type='hidden' value='".trim($menuordername[$i])."' id='in_".$menuorder[$i]."'>";
            $menuquick[$i] .= "</li>";
            $preview[] = "<li class='".$menuorder[$i]."'>".$menuordername[$i]."<div><img src='".G5_IMG_URL."/ic_".$menuorder[$i].".svg' alt='".$menuorder[$i]."'></div></li>";
        }
    }
}
//$preview = array_filter($preview);
?>
<div class="width-fixed">
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
                    <li class="active"><i></i>퀵메뉴 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/navigator'"><i></i>네비게이터 설정</li>
                    <!--<li onclick="location.href=g5_url+'/page/mypage/guide'"><i></i>사용자 가이드 설정</li>-->
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd'"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>퀵메뉴 설정</h2>
                <div class="set_quick">
                    <input type="radio" name="set_quick" id="quick_on" value="1" <?php if($setquick["quick"]==1 && $quickcnt > 0 || $quickcnt == 0){?>checked<?php }?>>
                    <label for="quick_on"><span></span> 켜기</label>
                    <input type="radio" name="set_quick" id="quick_off" value="0" <?php if($setquick["quick"] == 0 && $quickcnt > 0){?>checked<?php }?>>
                    <label for="quick_off"><span></span> 끄기</label>
                </div>
            </header>
            <div class="quick_con">
                <h3>메뉴 선택</h3>
                <p>메뉴를 선택하신 후 Drag & Drop을 이용하여 이동해 보세요<br>원하는데로 순서를 적용하실 수 있습니다.</p>
                <div class="quick_settings">
                    <!--<form action="<?php /*echo G5_URL*/?>/page/mypage/quickmenu_update.php" method="post" onsubmit="return fnSubmit();">-->
                        <div class="left">
                            <ul id="sortable">
                                <?php if(count($menuquick) == 0){?>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="guide" id="guide" checked value="1">
                                    <label for="guide"><span></span> CMAP GUIDE <img src="<?php echo G5_IMG_URL;?>/ic_guide.svg" alt=""></label>
                                    <input type='hidden' value='CMAP GUIDE' id='in_guide'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="work" id="work" checked value="1">
                                    <label for="work"><span></span> 작업 요청서 작성 <img src="<?php echo G5_IMG_URL;?>/ic_work.svg" alt=""></label>
                                    <input type='hidden' value='작업 요청서 작성' id='in_work'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="schedule" id="schedule" checked value="1">
                                    <label for="schedule"><span></span> 스케쥴 <img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""></label>
                                    <input type='hidden' value='스케쥴' id='in_schedule'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="construct" id="construct" checked value="1">
                                    <label for="construct"><span></span> 현장관리 <img src="<?php echo G5_IMG_URL;?>/ic_construct.svg" alt=""></label>
                                    <input type='hidden' value='현장관리' id='in_construct'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="databoard" id="databoard" checked value="1">
                                    <label for="databoard"><span></span> 커뮤니티 <img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""></label>
                                    <input type='hidden' value='자료실' id='in_databoard'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="inquiry" id="inquiry" checked value="1">
                                    <label for="inquiry"><span></span> 제안하기 <img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""></label>
                                    <input type='hidden' value='제안하기' id='in_inquiry'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="mypage" id="mypage" checked value="1">
                                    <label for="mypage"><span></span> MY CMAP <img src="<?php echo G5_IMG_URL;?>/ic_mypage.svg" alt=""></label>
                                    <input type='hidden' value='MY CMAP' id='in_mypage'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="payment" id="payment" checked value="1">
                                    <label for="payment"><span></span> 결제하기 <img src="<?php echo G5_IMG_URL;?>/ic_payment.svg" alt=""></label>
                                    <input type='hidden' value='결제하기' id='in_payment'>
                                </li>
                                <li class="ui-state-default">
                                    <input type="checkbox" name="weather" id="weather" checked value="1">
                                    <label for="weather"><span></span> 천후표 <img src="<?php echo G5_IMG_URL;?>/ic_weather.svg" alt=""></label>
                                    <input type='hidden' value='천후표' id='in_weather'>
                                </li>
                                <?php if($member["mb_level"]>=5){?>
                                    <li class="ui-state-default">
                                        <input type="checkbox" name="pm_mode" id="pm_mode" checked value="1">
                                        <label for="pm_mode"><span></span> PM_MODE <img src="<?php echo G5_IMG_URL;?>/ic_pm_mode.svg" alt=""></label>
                                        <input type='hidden' value='PM_MODE' id='in_pm_mode'>
                                    </li>
                                <?php }?>
                                <?php }else{?>
                                    <?php for($i=0;$i<count($menuquick);$i++){
                                        echo $menuquick[$i];
                                    }?>
                                <?php }?>

                            </ul>
                        </div>
                        <div class="right">
                            <ul class="preview_ul">
                                <?php if(count($menuquick) == 0){?>
                                    <li class="guide"> CMAP GUIDE <div><img src="<?php echo G5_IMG_URL;?>/ic_guide.svg" alt=""></div></li>
                                    <li class="work"> 작업 요청서 작성 <div><img src="<?php echo G5_IMG_URL;?>/ic_work.svg" alt=""></div></li>
                                    <li class="schedule"> 스케쥴 <div><img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""></div></li>
                                    <li class="construct"> 현장관리 <div><img src="<?php echo G5_IMG_URL;?>/ic_construct.svg" alt=""></div></li>
                                    <li class="databoard"> 커뮤니티 <div><img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""></div></li>
                                    <li class="inquiry"> 제안하기 <div><img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""></div></li>
                                    <li class="mypage"> MY CMAP <div><img src="<?php echo G5_IMG_URL;?>/ic_mypage.svg" alt=""></div></li>
                                    <li class="payment"> 결제하기 <div><img src="<?php echo G5_IMG_URL;?>/ic_payment.svg" alt=""></div></li>
                                    <li class="weather"> 천후표 <div><img src="<?php echo G5_IMG_URL;?>/ic_weather.svg" alt=""></div></li>
                                    <?php if($member["mb_level"]==5){?>
                                    <li class="pm_mode"> PM MODE <div><img src="<?php echo G5_IMG_URL;?>/ic_pm_mode.svg" alt=""></div></li>
                                    <?php }?>
                                <?php }else{
                                    for($c=0;$c<count($preview);$c++){
                                        echo $preview[$c];
                                    }
                                    ?>
                                    <?php if($member["mb_level"]==5){?>
                                        <li class="pm_mode"> PM MODE <div><img src="<?php echo G5_IMG_URL;?>/ic_pm_mode.svg" alt=""></div></li>
                                    <?php }?>
                                <?php }?>
                                <li class="base">
                                    <div><img src="<?php echo G5_IMG_URL;?>/quick_base.png" alt=""></div>
                                </li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="mypage_btns no_border">
                        <input type="button" value="취소" class="basic_btn02 width20" onclick="location.reload();">
                        <input type="button" value="적용" class="basic_btn01 width20" onclick="fnSubmit()" >
                    </div>
                <!--</form>-->
            </div>
        </article>
    </section>
</div>
<script src="<?php echo G5_JS_URL ?>/jquery-ui.js"></script>
<script>
    $(function(){
        $("#sortable").sortable({
            update:  function (event, ui) {
                console.log($("#sortable").html());
            }
        });

        $("#sortable").disableSelection();

        $("#quick_on,#quick_off").click(function(){

            $.ajax({
                url:g5_url+"/page/ajax/ajax.quick_set.php",
                method:"post",
                data:{quick:$(this).val(),mb_id:"<?php echo $member["mb_id"];?>"},
                dataType:"json"
            }).done(function(data){
                console.log(data)
            });
        });
    });
    function fnSubmit(){
        var checked = "";
        var cmap_quick = "";

        $("input[type=checkbox]").each(function(e) {
            if($(this).prop("checked")==true) {
                if (e == 0) {
                    checked = 1;
                } else {
                    checked += "``1";
                }
            }else{
                if (e == 0) {
                    checked = 0;
                } else {
                    checked += "``0";
                }
            }
            if(e==0){
                cmap_quick = $(this).attr("id");
            }else{
                cmap_quick += "``"+$(this).attr("id");
            }
        });
        var cmap_name = "";
        $("li input[type=hidden]").each(function(e) {
            if (e == 0) {
                cmap_name = $(this).val();
            } else {
                cmap_name += "``" +  $(this).val();
            }
        });

        var quick = $("input[name=set_quick]:checked").val();

        $.ajax({
            url:g5_url+"/page/ajax/ajax.quick_update.php",
            method:"post",
            data:{quick_menu:cmap_quick,quick_menu_name:cmap_name,quick_menu_status:checked,mb_id:"<?php echo $member["mb_id"];?>",quick:quick},
            dataType:"json"
        }).done(function(data){
            location.reload();
        });
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
