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
?>
<div id="pop" style="z-index:100000;">
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
    <div class="close" style="position:absolute; top:40vw; left:0; width:60vw;">
        <form name="pop_form">
            <div id="close" style="margin:auto; float:right; "><a href="javascript:closePop();">CLOSE</a></div>
            <div id="check" style="float:left;padding-left:5px;"><input type="radio" name="chkbox" id="chk1" value="checkbox" style="vertical-align:middle;">&nbsp;
                <label style="line-height:30px;" for="chk1">오늘 하루동안 보지 않기</label>
            </div>
            <div id="check" style="float:left;padding-left:5px;margin-left:40px"><input type="radio" name="chkbox" id="chk2" value="checkbox2" style="vertical-align:middle;">&nbsp;
                <label style="line-height:30px;" for="chk2">다시보지 않기</label>
            </div>
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
    <a href="javascript:freeMember()">무료체험 5일<br><span>회원가입/현장개설</span></a>
</div>
<?php }?>
    <!-- Channel Plugin Scripts -->
    <!--script>
        window.channelPluginSettings = {
            "pluginKey": "af9f9598-783e-4d07-a7a5-afb03aa9c787"
        };
        (function() {
            var w = window;
            if (w.ChannelIO) {
                return (window.console.error || window.console.log || function(){})('ChannelIO script included twice.');
            }
            var d = window.document;
            var ch = function() {
                ch.c(arguments);
            };
            ch.q = [];
            ch.c = function(args) {
                ch.q.push(args);
            };
            w.ChannelIO = ch;
            function l() {
                if (w.ChannelIOInitialized) {
                    return;
                }
                w.ChannelIOInitialized = true;
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://cdn.channel.io/plugin/ch-plugin-web.js';
                s.charset = 'UTF-8';
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            }
            if (document.readyState === 'complete') {
                l();
            } else if (window.attachEvent) {
                window.attachEvent('onload', l);
            } else {
                window.addEventListener('DOMContentLoaded', l, false);
                window.addEventListener('load', l, false);
            }
        })();
    </script>
    <!-- End Channel Plugin -->
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

    window.onload = function() {
        if (getCookie("maindiv") == false) {
            document.getElementById("pop").style.display = "block";
        } else if (getCookie("maindiv") == "done") {
            document.getElementById("pop").style.display = "none";
        }
    }

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
</script>

<?php
include_once(G5_PATH.'/tail.php');
?>