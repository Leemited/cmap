<?php
include_once('./_common.php');

$main = true;

define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/index.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_PATH.'/head.php');

$sql = "select * from `cmap_mainimage` where used = 1 order by id ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $mainimage[] = $row;
}

$sql = "select * from `mainslide_time` where id = 1";
$mainslide_time = sql_fetch($sql);

$mobile_const = sql_fetch("select * from `cmap_my_construct` where id = '{$current_const["const_id"]}'");

?>
<div id="pop" style="z-index:100000;display:none;">
    <div>
        <div class="tab">
            <div class="active">현장개설</div>
            <div>건설행정/공사관리/시공확인</div>
            <div>건설사업관리/시공평가/용역평가</div>
            <div>MY CMAP</div>
        </div>
        <div class="content">
            <div id="tab1">
                <div id="yt-player1" class="tab1_video" data-property="{videoURL:'https://youtu.be/ThYVynBvoDo',containment:'#tab1',autoPlay:true, startAt:0, opacity:1,showControls:false, showYTLogo:false, useOnMobile:true, optimizeDisplay:false}" ></div>
            </div>
            <div id="tab2">
                <div id="yt-player2" class="tab2_video" data-property="{videoURL:'https://youtu.be/CxhpZzWaAZc',containment:'#tab2',autoPlay:true, startAt:0, opacity:1,showControls:false, showYTLogo:false, useOnMobile:true, optimizeDisplay:false}" ></div>
            </div>
            <div id="tab3">
                <div id="yt-player3" class="tab3_video" data-property="{videoURL:'https://youtu.be/NS9L-QFhQ5k',containment:'#tab3',autoPlay:true, startAt:0, opacity:1,showControls:false, showYTLogo:false, useOnMobile:true, optimizeDisplay:false}" ></div>
            </div>
            <div id="tab4">
                <div id="yt-player4" class="tab4_video" data-property="{videoURL:'https://youtu.be/eoVE5F4XH60',containment:'#tab4',autoPlay:true, startAt:0, opacity:1,showControls:false, showYTLogo:false, useOnMobile:true, optimizeDisplay:false}" ></div>
            </div>
        </div>
    </div>
    <div class="close" style="position:absolute; top:38.8vw; left:0; width:60vw;">
        <form name="pop_form">
            <div id="close" style="margin:auto; float:right; "><a href="javascript:closePop();">CLOSE</a></div>
            <!--<div id="check" style="float:left;padding-left:5px;"><input type="radio" name="chkbox" id="chk1" value="checkbox" style="vertical-align:middle;">&nbsp;
                <label style="line-height:30px;" for="chk1">오늘 하루동안 보지 않기</label>
            </div>
            <div id="check" style="float:left;padding-left:5px;margin-left:40px"><input type="radio" name="chkbox" id="chk2" value="checkbox2" style="vertical-align:middle;">&nbsp;
                <label style="line-height:30px;" for="chk2">다시보지 않기</label>
            </div>-->
        </form>
    </div>
</div>
<div class="etc_view messages">

</div>
<span class="etc_view_bg"></span>
<div class="video_guide">
    <a href="javascript:fnPopShow();">간편사용설명서 ▶</a>
</div>
<div class="owl-carousel" id="main" style="z-index:0;">
    <?php if(count($mainimage)==0){?>
        <div class="item" style="background-image:url('<?php echo G5_IMG_URL;?>/main_bg.jpg');background-size: cover;background-position: center bottom;background-repeat: no-repeat;height: 100vh;width:100%;">
            <div class="texts">
                <h2>함께하는 사회, 새로운 가치를 창조합니다.</h2>
                <p>C.MAP은 전문지식이 없어도 검색을 통해 쉽고 빠르게 관련 법률을 찾을 수 있습니다.<br>이를 통해 시공 단계를 좀 더 간결하고 빠르게 처리할 수 있도록 도와줍니다.</p>
            </div>
        </div>
    <?php }else{
        for($i=0;$i<count($mainimage);$i++){
            ?>
            <div class="item" style="background-image:url('<?php echo G5_DATA_URL."/file/main/".$mainimage[$i]["main_image"];?>');background-size: cover;background-position: center top;background-repeat: no-repeat;width:100%;height:100vh">
                <div class="texts">
                    <h2><?php echo nl2br($mainimage[$i]["main_text"]);?></h2>
                    <p style="color:blue"><?php echo nl2br($mainimage[$i]["sub_text"]);?></p>
                </div>
            </div>
        <?php }
    }?>
</div>
<?php if($member["mb_auth"]==false){?>
<div class="freemember">
    <a href="javascript:freeMember()">무료체험 1일<br><span>회원가입/현장개설</span></a>
</div>
<?php }?>

<div class="mobile_index">
    <div class="mylocation_list">
    <?php if($is_member){?>
        <label for="mylocM" id="mylocBtn"><?php if($mobile_const["cmap_name"]){echo $mobile_const["cmap_name"];}else{?>현장선택<?php }?></label>
        <ul class="mylocList" id="mylocM">
            <?php for($i=0;$i<count($mycont);$i++){?>
                <li class="<?php if($mycont[$i]["id"]==$current_const["const_id"]){echo "active";}?>" onclick="fnChangeConst('<?php echo $member["mb_id"];?>','<?php echo $mycont[$i]["id"];?>');"><?php echo $mycont[$i]["cmap_name"];?></li>
            <?php }?>
        </ul>
    <?php }else{?>
        <input type="button" value="로그인" onclick="location.href=g5_bbs_url+'/login'">
    <?php }?>
    </div>
    <div class="mmenu">
        <ul>
            <?php if($member["mb_level"]!=5){?>
            <li class="menu01">
                <div><img src="<?php echo G5_IMG_URL;?>/ic_mobile_menu01.svg" alt=""></div>
                <div>건설행정</div>
            </li>
            <?php }else{?>
            <li class="menu01">
                <div><img src="<?php echo G5_IMG_URL;?>/ic_mobile_menu01.svg" alt=""></div>
                <div>PM MODE</div>
            </li>
            <?php }?>
            <?php if($member["mb_level"]!=5){?>
            <li class="menu02">
                <div><img src="<?php echo G5_IMG_URL;?>/ic_mobile_menu02.svg" alt=""></div>
                <div>공사관리</div>
            </li>
            <?php }?>
            <li class="menu03">
                <div class="top_title"><h2>시공평가</h2></div>
                <div class="bottom_count"><span><?php echo ceil($eval1_total);?></span>점</div>
            </li>
            <?php if($member["mb_level"]!=5){?>
            <li class="menu04">
                <div><img src="<?php echo G5_IMG_URL;?>/ic_mobile_menu03.svg" alt=""></div>
                <div>점검/평가</div>
            </li>
            <li class="menu05">
                <div><img src="<?php echo G5_IMG_URL;?>/ic_mobile_menu04.svg" alt=""></div>
                <div>시공확인(검측)</div>
            </li>
            <?php }?>
            <li class="menu06">
                <div class="top_title"><h2>용역평가</h2></div>
                <div class="bottom_count"><span><?php echo ceil($eval2_total);?></span>점</div>
            </li>
        </ul>
    </div>
    <div class="schedule_today" onclick="<?php if($is_member && $member["mb_auth"]==true){?>location.href=g5_url+'/page/mypage/schedule#list_<?php echo date("d");?>'<?php }else {?>location.href=g5_bbs_url+'/login'<?php }?>">
        <div>SCHEDULE</div>
        <div>
            <span>오늘의 할일</span> <span><?php echo date("Y-m-d");?></span>
        </div>
    </div>
    <div class="mybox <?php if($member["mb_level"]==5){?>pm<?php }?>">
        <ul>
            <li <?php if($member['mb_level']==5){?>class="pms"<?php }?> onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{ if($member["mb_level"]==3){?>location.href=g5_url+'/page/mylocation/mylocation'<?php }else if($member["mb_level"]==5){?>location.href=g5_url+'/page/manager/pm_construct'<?php } } ?>">
                <div class="text_left"><?php if($member["mb_level"]==5){?>PM<?php }else{?>현장관리<?php }?></div>
                <div><strong><?php echo number_format(count($mycont));?></strong> 개 공구</div>
            </li>
            <li>
                <div>사용자관리</div>
                <div><strong><?php echo number_format(count($reqlist));?></strong> 건</div>
            </li>
            <li>
                <div>제출지연건</div>
                <div><strong><?php echo number_format(count($maindelaylists));?></strong> 건</div>
            </li>
            <li>
                <div>업무요청서</div>
                <div><strong><?php echo number_format(count($msglist));?></strong> 건</div>
            </li>
        </ul>
    </div>
    <div class="schedule_list_m">
        <ul>
            <li class="<?php if(date("D")=="Sun"){?>sun<?php }if(date("D")=="Sat"){?>sat<?php }?> <?php if(count($myschedule)>=4){?>more<?php }?>">
                <div>
                    <h2><?php echo date("d");?></h2>
                    <h3><?php echo date("D");?></h3>
                </div>
                <div>
                    <?php if(count($myschedule)>0){?>
                    <?php for($i=0;$i<count($myschedule);$i++){
                    $indate = explode(" ",$myschedule[$i]["insert_date"]);
                    $pkids = str_replace("``",",",$myschedule[$i]["pk_id"]);
                    $sql = "select a.id as id, a.me_code as me_code,b.pk_id as pk_id,b.depth1_id as depth1_id, b.depth2_id as depth2_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where b.pk_id in ({$pkids})";
                    $schedulesid = sql_fetch($sql);
                    if($schedulesid!=null){
                        $link = "location.href='".G5_URL."/page/view?depth1_id=".$schedulesid["depth1_id"]."&depth2_id=".$schedulesid["depth2_id"]."&me_id=".$schedulesid["me_code"]."&pk_id=".$schedulesid["pk_id"]."&constid=".$myschedule[$i]["construct_id"]."'";
                    }else{
                        $link = 'fnScheduleView()';
                    }
                    if($i>=4){continue;}
                    ?>
                <div class="" onclick="<?php echo $link;?>"> - <?php echo cut_str($myschedule[$i]["schedule_name"],15,'...');?><span><?php echo $indate[0];?></span></div>
                <?php }?>
                <?php }else{?>
                    <div>등록된 할일이 없습니다.</div>
                <?php }?>
                </div>
            </li>
            <li class="<?php if(date("D",strtotime("+ 1 day"))=="Sun"){?>sun<?php }if(date("D",strtotime("+ 1 day"))=="Sat"){?>sat<?php }?> <?php if(count($myschedule2)>=4){?>more<?php }?>">
                <div>
                    <h2><?php echo date("d" ,strtotime("+ 1 day"));?></h2>
                    <h3><?php echo date("D" ,strtotime("+ 1 day"));?></h3>
                </div>
                <div>
                    <?php if(count($myschedule2)>0){?>
                        <?php for($i=0;$i<count($myschedule2);$i++){
                            $indate = explode(" ",$myschedule2[$i]["insert_date"]);
                            $pkids = str_replace("``",",",$myschedule2[$i]["pk_id"]);
                            $sql = "select a.id as id, a.me_code as me_code,b.pk_id as pk_id,b.depth1_id as depth1_id, b.depth2_id as depth2_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where b.pk_id in ({$pkids})";
                            $schedulesid = sql_fetch($sql);
                            if($schedulesid!=null){
                                $link = "location.href='".G5_URL."/page/view?depth1_id=".$schedulesid["depth1_id"]."&depth2_id=".$schedulesid["depth2_id"]."&me_id=".$schedulesid["me_code"]."&pk_id=".$schedulesid["pk_id"]."&constid=".$myschedule2[$i]["construct_id"]."'";
                            }else{
                                $link = 'fnScheduleView()';
                            }
                            if($i>=4){continue;}
                            ?>
                            <div class="" onclick="<?php echo $link;?>"> - <?php echo cut_str($myschedule2[$i]["schedule_name"],15,'...');?><span><?php echo $indate[0];?></span></div>
                        <?php }?>
                    <?php }else{?>
                        <div>등록된 할일이 없습니다.</div>
                    <?php }?>
                </div>
            </li>
        </ul>
    </div>
    <div class="boards">
        <ul>
            <li onclick="location.href=g5_url+'/page/board/inquiry'"><img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""> 제안하기</li>
            <li onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'"><img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""> 게시판</li>
            <li onclick="fnPayment();"><img src="<?php echo G5_IMG_URL;?>/ic_payment.svg" alt=""> 결제하기</li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=66b0c8ec7fbce830de7902a16ad06d12&libraries=services"></script>
<script src="<?php echo G5_JS_URL ?>/owl.carousel.js"></script>
<script>
    var video1,video2,video3,video4;
    $(function() {
        video1 = $("#yt-player1").YTPlayer();
        video2 = $("#yt-player2").YTPlayer();
        video3 = $("#yt-player3").YTPlayer();
        video4 = $("#yt-player4").YTPlayer();

        video2.YTPStop();
        video3.YTPStop();
        video4.YTPStop();

        var owl = $("#main");
        owl.owlCarousel({
            animateOut: 'fadeOut',
            autoplay: true,
            autoplayTimeout: <?php echo $mainslide_time["slide_time"];?>000,
            autoplaySpeed: 5000,
            smartSpeed: 5000,
            mouseDrag:false,
            loop: true,
            dots: false,
            items: 1
        });

        //getLocation();

        $.ajax({
            url:g5_url+"/page/ajax/ajax.get_weather_location.php",
            method:"post",
            data:{cmap_id:"<?php echo $current_const["const_id"];?>"},
            dataType:"json"
        }).done(function(data){
            if(data.status != 1) {
                if (data.tmn[0] && data.tmx[0]) {
                    var min = Math.floor(data.tmn[0]);
                    var max = Math.floor(data.tmx[0]);
                    if (data.temp[0]) {
                        var current = data.temp[0];
                        $(".now_temp").html(current + "℃");
                    }
                    $(".temp_min_max").html(min + "℃ / " + max + "℃");
                    $(".addr").html(data.addr);
                    $(".timedesc").html(data.time);
                }
            }else{
                getLocation();
            }
        });

        $(".tab > div").click(function(){
            if(!$(this).hasClass("active")){
                $(this).addClass("active");
                $(".tab > div").not($(this)).removeClass("active");
                if($(this).text()=="현장개설"){
                    $("#tab1").show();
                    $("#tab2").hide();
                    $("#tab3").hide();
                    $("#tab4").hide();
                    video1.YTPPlay();
                    video2.YTPPause();
                    video3.YTPPause();
                    video4.YTPPause();
                }
                if($(this).text()=="건설행정/공사관리/시공확인"){
                    $("#tab1").hide();
                    $("#tab2").show();
                    $("#tab3").hide();
                    $("#tab4").hide();
                    video1.YTPPause();
                    video2.YTPPlay();
                    video3.YTPPause();
                    video4.YTPPause();
                }
                if($(this).text()=="건설사업관리/시공평가/용역평가"){
                    $("#tab1").hide();
                    $("#tab2").hide();
                    $("#tab3").show();
                    $("#tab4").hide();
                    video1.YTPPause();
                    video2.YTPPause();
                    video3.YTPPlay();
                    video4.YTPPause();
                }
                if($(this).text()=="MY CMAP"){
                    $("#tab1").hide();
                    $("#tab2").hide();
                    $("#tab3").hide();
                    $("#tab4").show();
                    video1.YTPPause();
                    video2.YTPPause();
                    video3.YTPPause();
                    video4.YTPPlay();
                }
            }
        });
    });

    // 주소-좌표 변환 객체를 생성합니다
    var geocoder = new daum.maps.services.Geocoder();

    function getLocation()
    {
        window.navigator.geolocation.getCurrentPosition(current_position);
    }

    function current_position(position)
    {
        var coords = new daum.maps.LatLng(position.coords.latitude,position.coords.longitude);
        searchAddrFromCoords(coords,getWeatherInfo);
    }


    function searchAddrFromCoords(coords, callback) {
        // 좌표로 행정동 주소 정보를 요청합니다
        geocoder.coord2RegionCode(coords.getLng(), coords.getLat(), callback);
    }

    function getWeatherInfo(result){
        $.ajax({
            url:g5_url+"/page/ajax/ajax.get_weather_location.php",
            method:"post",
            data:{addr1:result[0].region_1depth_name,addr2:result[0].region_2depth_name,addr3:result[0].region_3depth_name},
            dataType:"json"
        }).done(function(data){
            if(data.tmn[0] && data.tmx[0]){
                var min = Math.floor(data.tmn[0]);
                var max = Math.floor(data.tmx[0]);
                if(data.temp[0]) {
                    var current = data.temp[0];
                    $(".now_temp").html(current+"℃");
                }
                $(".temp_min_max").html(min+"℃ / "+max+"℃");
                $(".addr").html(data.addr);
                $(".timedesc").html(data.time);
            }
        });
    }

    function freeMember(){
        <?php if($is_member){?>
        location.href=g5_url+'/';
        <?php }else{?>
        location.href=g5_bbs_url+'/register.php?type=free';
        <?php }?>
    }

    function setCookie( name, value, expiredays ) {
        var todayDate = new Date();
        todayDate.setDate( todayDate.getDate() + expiredays );
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
    }

    /*window.onload = function() {
        if (getCookie("maindiv") == false) {
            document.getElementById("pop").style.display = "block";
        } else if (getCookie("maindiv") == "done") {
            document.getElementById("pop").style.display = "none";
        }
    }*/

    function getCookie(cName) {
        cName = cName + '=';
        var cookieData = document.cookie;
        var start = cookieData.indexOf(cName);
        var cValue = '';
        if(start != -1){
            start += cName.length;
            var end = cookieData.indexOf(';', start);
            if(end == -1)end = cookieData.length;
            cValue = cookieData.substring(start, end);
        }
        return unescape(cValue);
    }

    function closePop(day) {
        if ($("input[name=chkbox]:checked").val()=="checkbox"){
            setCookie( "maindiv", "done" , 1 );
        }else if($("input[name=chkbox]:checked").val()=="checkbox2"){
            setCookie( "maindiv", "done" , 1825);
        }
        document.getElementById("pop").style.display = "none";

        video1.YTPPause();
        video2.YTPPause();
        video3.YTPPause();
        video4.YTPPause();
    }

    function fnPopShow() {
        console.log(document.getElementById("pop").style.display);
        if(document.getElementById("pop").style.display == "none") {
            document.getElementById("pop").style.display = "block";
            $(".tab div").not($(".tab1 div:first-child")).removeClass("active");
            $(".tab div:first-child").addClass("active");

            $("#tab1").show();
            $("#tab2").hide();
            $("#tab3").hide();
            $("#tab4").hide();
            video1.YTPPlay();
            video2.YTPPause();
            video3.YTPPause();
            video4.YTPPause();
        }
    }

    $("#mylocBtn").click(function(){
        if(!$("#mylocM").hasClass("active")){
            $("#mylocM").addClass("active");
        }else{
            $("#mylocM").removeClass("active");
        }
    });

    $("#mylocM li").click(function(){
        //현장 정보 불러오기
    });
</script>

<?php
include_once(G5_PATH.'/tail.php');
?>