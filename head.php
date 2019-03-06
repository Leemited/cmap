<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH.'/head.php');
    return;
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

?>

<!-- 로그인 -->
<?php if(defined('_INDEX_')) {?>
<div class="login">
    <div class="login_btns">
        <?php if(!$is_member){?>
            <img src="<?php echo G5_IMG_URL?>/main_login_btn.png" alt="로그인" onclick="location.href='<?php echo G5_BBS_URL?>/login.php'">
        <?php }else{?>
            <img src="<?php echo G5_IMG_URL?>/mypage_btn.png" alt="로그인" onclick="fnMyprofile('<?php echo $member["mb_id"];?>');">
        <?php }?>
    </div>
</div>
<div class="my_profile">
    <div class="my_profile_top">
        <h2 onclick="location.href=g5_url+'/page/mypage/mypage.php'">
            <label><?php echo $member["mb_id"];?></label> 님
            <img src="<?php echo G5_IMG_URL?>/ic_profile_setting.svg" alt="">
        </h2>
        <a href="javascript:fnPayment();">결제</a>
        <div class="close" onclick="fnCloseProfile()"></div>
    </div>
    <div class="mycmap">
        <select name="mylocmap" id="mylocmap" class="cmap_sel width100">
            <option value="">현장 선택</option>
        </select>
        <div class="cmap_menu">
            <div class="cmap_menu_td cmenu1">
                <h2>사용자관리</h2>
                <div class="counts">
                    <span>0</span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu2">
                <h2>작업요청서</h2>
                <div class="counts">
                    <span>0</span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu3" onclick="location.href=g5_url+'/page/mylocation/mylocation.php'">
                <div class="img"><img src="<?php echo G5_IMG_URL?>/ic_construct.svg" alt=""></div>
                <div>현장관리</div>
            </div>
            <div class="cmap_menu_td cmenu4">
                <h2>제출 지연 현황</h2>
                <div class="counts">
                    <span>0</span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu5" onclick="location.href=g5_bbs_url+'/board.php?bo_table=databoard'">
                <img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""> 커뮤니티
            </div>
            <div class="cmap_menu_td cmenu6" onclick="location.href=g5_url+'/page/mypage/schedule.php'">
                <img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""> 스케쥴
            </div>
            <div class="cmap_menu_td cmenu7" onclick="location.href=g5_url+'/page/board/inquiry.php'">
                <img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""> 제안하기
            </div>
            <div class="cmap_menu_td full_td cmenu8">
                <h3>시공평가 점수</h3>
                <div>

                </div>
                <h3>용역평가 점수</h3>
                <div>

                </div>
            </div>
            <div class="cmap_menu_td full_td cmenu9">
                <h2>오늘의 할일</h2>
                <div class="more">MORE ></div>
                <div class="lists">

                </div>
            </div>
            <div class="cmap_menu_td full_td cmenu10" onclick="fnWeather('<?php echo $member["mb_id"];?>');">
                <div>
                    <div>
                    <?php
                    $w = date("w");
                    switch ($w){
                        case 0:
                            $week = "일";
                            break;
                        case 1:
                            $week = "월";
                            break;
                        case 2:
                            $week = "화";
                            break;
                        case 3:
                            $week = "수";
                            break;
                        case 4:
                            $week = "목";
                            break;
                        case 5:
                            $week = "금";
                            break;
                        case 6:
                            $week = "토";
                            break;
                    }
                    echo date("Y년m월d일")."(".$week.")";
                    ?>
                    </div>
                    <div>
                        <input type="button" name="location" value="위치" onclick="getLocation();">
                    </div>
                </div>
            </div>
        <?php if($member["mb_level"]==5){?>
            <div class="cmap_menu_td full_td cmenu11">
                PM
            </div>
        <?php }?>
        </div>
    </div>
</div>
<?php }?>
<!-- 상단 시작 { -->
<div id="hd">
    <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php
    if(defined('_INDEX_')) { // index에서만 실행
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
    }
    ?><!--
    <div id="tnb">
        <ul>
            <?php /*if ($is_member) {  */?>

            <li><a href="<?php /*echo G5_BBS_URL */?>/member_confirm.php?url=<?php /*echo G5_BBS_URL */?>/register_form.php"><i class="fa fa-cog" aria-hidden="true"></i> 정보수정</a></li>
            <li><a href="<?php /*echo G5_BBS_URL */?>/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> 로그아웃</a></li>
            <?php /*if ($is_admin) {  */?>
            <li class="tnb_admin"><a href="<?php /*echo G5_ADMIN_URL */?>"><b><i class="fa fa-user-circle" aria-hidden="true"></i> 관리자</b></a></li>
            <?php /*}  */?>
            <?php /*} else {  */?>
            <li><a href="<?php /*echo G5_BBS_URL */?>/register.php"><i class="fa fa-user-plus" aria-hidden="true"></i> 회원가입</a></li>
            <li><a href="<?php /*echo G5_BBS_URL */?>/login.php"><b><i class="fa fa-sign-in" aria-hidden="true"></i> 로그인</b></a></li>
            <?php /*}  */?>

        </ul>
  
    </div>-->
    <nav id="gnb" class="<?php echo $myset["theme"];?> <?php echo "cate_".$myset["cate_theme"];?>">
        <div id="logo">
            <a href="<?php echo G5_URL ?>">
                <?php if($myset["theme"]=="white"){?>
                <img src="<?php echo G5_IMG_URL ?>/logo_b.png" alt="<?php echo $config['cf_title']; ?>">
                <?php }else{ ?>
                <img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>">
                <?php }?>
            </a>
        </div>
        <div class="gnb_wrap">
            <ul id="gnb_1dul">
                <!--<li class="gnb_1dli gnb_mnal"><button type="button" class="gnb_menu_btn"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button></li>-->
                <?php
                $sql = " select *
                            from `cmap_menu`
                            where menu_status = 0 
                              and menu_depth = 0
                            order by menu_order ";
                $result = sql_query($sql, false);
                $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                $menu_datas = array();

                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    $menu_datas[$i] = $row;

                    $sql2 = " select *
                                from `cmap_menu`
                                where menu_status = 0
                                  and menu_depth = 1
                                  and substring(menu_code, 1, 2) = '{$row['menu_code']}'
                                order by menu_order ";
                    $result2 = sql_query($sql2);
                    for ($k=0; $row2=sql_fetch_array($result2); $k++) {
                        $menu_datas[$i]['sub'][$k] = $row2;
                        $sql3 = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by `id` asc ";
                        $result3 = sql_query($sql3);
                        for($l = 0; $row3 = sql_fetch_array($result3);$l++){
                            $menus3[] = $row3;
                        }
                    }
                }

                $i = 0;
                foreach( $menu_datas as $row ){
                    if( empty($row) ) continue; 
                ?>
                <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex--; ?>">
                    <?php if($me_id==60 || $row["menu_name"] == "평가"){?>
                    <a href="<?php echo G5_URL?>/page/view2.php?me_id=<?php echo $row["menu_code"]; ?>" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                    <?php }else{?>
                    <a href="<?php echo G5_URL?>/page/view.php?me_id=<?php echo $row["menu_code"]; ?>" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                    <?php }?>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){
                        if( empty($row2) ) continue;

                        if($row["menu_name"]=="공사관리" && $k==0){
                            echo '<span class="allmenu"></span>';
                        }

                        if($k == 0)
                            echo '<ul class="gnb_2dul">'.PHP_EOL;

                        //echo '<span class="bg">하위분류</span><ul class="gnb_2dul">'.PHP_EOL;
                        $sql = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by `id` asc";
                        $res = sql_query($sql);
                        $num = sql_num_rows($res);
                    ?>
                        <li class="gnb_2dli <?php if($num>1){?>arrows<?php }?>" >
                            <a href="<?php if($num == 1){?><?php echo G5_URL?>/page/view.php?me_id=<?php echo $row2["menu_code"]; ?><?php }else{ ?>#<?php }?>" class="gnb_2da"><?php echo $row2['menu_name'] ?></a>
                            <?php
                            if($num >= 10){
                                if($k<=10){
                                    $over_top = "over_top2";
                                }else {
                                    $over_top = "over_top";
                                }
                            }else{
                                $over_top = "";
                            }

                            if($num > 1){
                                echo '<ul class="gnb_3dul '.$over_top.'">';

                            if($row2["menu_name"]=="용역평가"){?>
                                <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3.php?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=339">업체평가 (80)</a></li>
                                <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3.php?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=342">기술자평가 (20)</a></li>
                            <?php }else{

                            while($row3 = sql_fetch_array($res)){
                                if( empty($row3) ) continue;
                                if($row3["depth_name"]){
                                    if($me_id=='6064'){
                                    ?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view2.php?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                    <?php //}else if($me_id=='60129'){
                                        ?>
                                        <!--<li class="gnb_3dli "><a class="gnb_3da" href="<?php /*echo G5_URL*/?>/page/view3.php?me_id=<?php /*echo $row2["menu_code"]; */?>&depth1_id=<?php /*echo $row3["id"];*/?>"><?php /*echo $row3["depth_name"];*/?></a></li>-->
                                    <?php }else{?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view.php?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                <?php }?>
                                <?php }?>
                            <?php $a++;} echo '</ul>';?>
                            <?php }?>
                            <?php }?>
                        </li>
                    <?php
                    $k++;
                    }   //end foreach $row2
                    if($k > 0)
                        echo '</ul>'.PHP_EOL;
                    ?>
                </li>
                <?php
                $i++;
                }   //end foreach $row

                if ($i == 0) {  ?>
                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                <?php } ?>
            </ul>
            <div id="gnb_all">
                <h2>전체메뉴</h2>
                <ul class="gnb_al_ul">
                    <?php
                    
                    $i = 0;
                    foreach( $menu_datas as $row ){
                    ?>
                    <li class="gnb_al_li">
                        <a href="<?php echo $row['me_link']; ?>" class="gnb_al_a"><?php echo $row['menu_name'] ?></a>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if($k == 0)
                                echo '<ul>'.PHP_EOL;
                        ?>
                            <li><a href="<?php echo $row2['me_link']; ?>" ><i class="fa fa-caret-right" aria-hidden="true"></i> <?php echo $row2['menu_name'] ?></a></li>
                        <?php
                        $k++;
                        }   //end foreach $row2

                        if($k > 0)
                            echo '</ul>'.PHP_EOL;
                        ?>
                    </li>
                    <?php
                    $i++;
                    }   //end foreach $row

                    if ($i == 0) {  ?>
                        <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list.php">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                </ul>
                <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
    </nav>
    <script>
    
    $(function(){
        $(".gnb_menu_btn").click(function(){
            $("#gnb_all").show();
        });
        $(".gnb_close_btn").click(function(){
            $("#gnb_all").hide();
        });
        $(".gnb_1dli").each(function(){
            $(this).hover(function() {
                if($(this).hasClass("gnb_1dli_over")) {
                    var height = $(this).find($(".over_top2")).height();
                    var newHeight = height/2.1;
                    $(this).find($(".over_top2")).css({"top": "-"+newHeight+"px"});
                }
            });
        });
        $("li.gnb_3dli").each(function(){
            $(this).mouseover(function(){
                <?php if($myset["cate_theme"]=="blue"){?>
                    $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#000 !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right_b.svg');background-repeat:no-repeat;background-position:right center;background-size:28px;"});
                <?php }else if($myset["cate_theme"]=="black"){ ?>
                    $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px;"});
                <?php }else if($myset["cate_theme"]=="white"){ ?>
                    $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px;"});
                <?php }?>
            });
            $(this).mouseout(function(){
                $(this).parent().parent().find($(".gnb_2da")).removeAttr("style");

            })
        });
    });
    function fnPayment(){
        $.ajax({
            url:g5_url+"/page/modal/ajax.payment.php",
            method:"post",
            data:{mb_id:"<?php echo $member["mb_id"];?>"}
        }).done(function(data){
            //$(".modal").append(data);
            fnShowModal(data);
        });
    }
    </script>
</div>
<?php if(!$main && $sub != "login" && $mypage != true){?>
<div class="user_guide">
    <div class="user">
        <div>사용자 가이드</div>
        <div>사용자가이드 예시입니다. 아직 개발 중입니다.</div>
    </div>
        <div class="clear"></div>
</div>
<?php }?>
<!-- } 상단 끝 -->

