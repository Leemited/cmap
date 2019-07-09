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
<div class="etc_view messages">

</div>
<span class="etc_view_bg"></span>
<div class="owl-carousel" id="main" style="z-index:0;">
    <?php if(count($mainimage)==0){?>
    <div class="item" style="background-image:url('<?php echo G5_IMG_URL;?>/main_bg.jpg');background-size: cover;background-position: center bottom;background-repeat: no-repeat;height: 100vh;width:100%;">
        <div class="text">
            <h2>함께하는 사회, 새로운 가치를 창조합니다.</h2>
            <p>C.MAP은 전문지식이 없어도 검색을 통해 쉽고 빠르게 관련 법률을 찾을 수 있습니다.<br>이를 통해 시공 단계를 좀 더 간결하고 빠르게 처리할 수 있도록 도와줍니다.</p>
        </div>
    </div>
    <?php }else{
        for($i=0;$i<count($mainimage);$i++){
    ?>
    <div class="item" style="background-image:url('<?php echo G5_DATA_URL."/file/main/".$mainimage[$i]["main_image"];?>');background-size: cover;background-position: center top;background-repeat: no-repeat;width:100%;height:100vh">
        <div class="text">
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
    $(function() {

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

</script>

<?php
include_once(G5_PATH.'/tail.php');
?>