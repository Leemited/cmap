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

$sql = "select * from `cmap_menu` where LENGTH(menu_code)=2 and menu_status = 0 order by menu_order asc; ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $search_menu[] = $row;
}
if(substr($me_id,0,2)==50){
    $sql = "select * from `cmap_menu_desc` where depth_id = '{$depth2_id}' ";
    $useguide = sql_fetch($sql);
}else {
    $sql = "select * from `cmap_menu_desc` where depth_id = '{$depth1_id}' ";
    $useguide = sql_fetch($sql);
}

if($mypage==true){
    $sql = "select * from `cmap_menu_desc` where isnull(pk_id) and isnull(depth) and isnull(depth_id)";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $useguide[$row["menu_id"]] = $row;
    }
}

if($bo_table){
    $mypage=true;
    $sql = "select * from `cmap_menu_desc` where isnull(pk_id) and isnull(depth) and isnull(depth_id)";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $useguide[$row["menu_id"]] = $row;
    }
    switch ($bo_table){
        case "databoard":
            $menu_id = "depth_desc_cmap";
            break;
        case "boards":
            $menu_id = "depth_desc_boards";
            break;
        case "review":
            $menu_id = "depth_desc_review";
            break;
        case "free":
            $menu_id = "depth_desc_com";
            break;
    }
}


//$sql = "select * from `cmap_menu_desc` where id = '{$depth1_id}' ";

$sql = "select *,count(search_text) as cnt from `cmap_search_log` where search_text != '' group by search_text order by cnt desc limit 0, 16";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $papular[] = $row;
}
?>

<!-- 로그인 -->
<?php if(defined('_INDEX_')) {?>
<div class="login <?php if(!defined('_INDEX_')) {?>sub<?php }?>">
    <div class="login_btns">
        <?php if(!$is_member){?>
            <img src="<?php echo G5_IMG_URL?>/main_login_btn.png" alt="로그인" onclick="location.href='<?php echo G5_BBS_URL?>/login'">
        <?php }else{?>
            <img src="<?php echo G5_IMG_URL?>/mypage_btn.png" alt="로그인" onclick="fnMyprofile('<?php echo $member["mb_id"];?>');">
        <?php }?>
    </div>
</div>
<div class="my_profile">
    <div class="my_profile_top">
        <h2 onclick="location.href=g5_url+'/page/mypage/mypage'">
            <label><?php echo $member["mb_id"];?></label> 님
            <img src="<?php echo G5_IMG_URL?>/ic_profile_setting.svg" alt="">
        </h2>
        <a href="javascript:fnPayment();">결제</a>
        <div class="close" onclick="fnCloseProfile()"></div>
    </div>
    <div class="mycmap">
        <select name="mylocmap" id="mylocmap" class="cmap_sel width100" onchange="fnChangeConst('<?php echo $member["mb_id"];?>',this.value)">
            <option value="">현장 선택</option>
            <?php for($i=0;$i<count($mycont);$i++){?>
                <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
            <?php }?>
        </select>
        <div class="cmap_menu">
            <div class="cmap_menu_td cmenu1" onclick="fnViewRequest('<?php echo $member["mb_id"];?>','')">
                <input type="hidden" id="const_id" value="">
                <h2>사용자관리</h2>
                <div class="counts">
                    <span><?php echo number_format(count($reqlist));?></span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu2" onclick="fnViewMessage('<?php echo $member["mb_id"];?>','')">
                <h2>작업요청서</h2>
                <div class="counts">
                    <span><?php echo number_format(count($msglist));?></span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu3" onclick="location.href=g5_url+'/page/mylocation/mylocation'">
                <div class="img"><img src="<?php echo G5_IMG_URL?>/ic_construct.svg" alt=""></div>
                <div>현장관리</div>
            </div>
            <div class="cmap_menu_td cmenu4" onclick="fnViewDelay('<?php echo $member["mb_id"];?>','')">
                <h2>제출 지연 현황</h2>
                <div class="counts">
                    <span><?php echo number_format(count($delaylist));?></span> 건
                </div>
            </div>
            <div class="cmap_menu_td cmenu5" onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'">
                <img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""> 커뮤니티
            </div>
            <div class="cmap_menu_td cmenu6" onclick="location.href=g5_url+'/page/mypage/schedule'">
                <img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""> 스케쥴
            </div>
            <div class="cmap_menu_td cmenu7" onclick="location.href=g5_url+'/page/board/inquiry'">
                <img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""> 제안하기
            </div>
            <div class="cmap_menu_td full_td cmenu8">
                <h3>시공평가 점수</h3>
                <div class="eval1">
                    <?php if(count($evalsall)==0){?>
                        <span>
                            <p>0</p>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <g>
                                <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5 	"/>
                                <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z"/>
                            </g>
                            </svg>
                        </span>
                        <div><label>80</label></div>
                        <div><label>90</label></div>
                        <div></div>
                    <?php }else{?>
                        <span>
                            <p>0</p>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <g>
                                <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5 	"/>
                                <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z"/>
                            </g>
                            </svg>
                        </span>
                        <div></div>
                        <div></div>
                        <div></div>
                    <?php }?>
                </div>
                <h3>용역평가 점수</h3>
                <div class="eval2">
                    <?php if(count($evalsall)==0){?>
                        <span>
                            <p>0</p>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <g>
                                <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5 	"/>
                                <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z"/>
                            </g>
                            </svg>
                        </span>
                        <div></div>
                        <div></div>
                        <div></div>
                    <?php }else{?>
                        <span>
                            <p>0</p>
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                            <style type="text/css">
                                .st0{fill:#FFFFFF;}
                            </style>
                            <g>
                                <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5 	"/>
                                <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z"/>
                            </g>
                            </svg>
                        </span>
                        <div></div>
                        <div></div>
                        <div></div>
                    <?php }?>
                </div>
            </div>
            <div class="cmap_menu_td full_td cmenu9">
                <h2>오늘의 할일</h2>
                <div class="more" onclick="fnScheduleView()">MORE ></div>
                <div class="lists">
                    <ul>
                    <?php if(count($myschedule)>0){?>
                        <?php for($i=0;$i<count($myschedule);$i++){
                            $indate = explode(" ",$myschedule[$i]["insert_date"]);
                            $pkids = str_replace("``",",",$myschedule[$i]["pk_id"]);
                            $sql = "select a.id as id, a.me_code as me_code,b.pk_id as pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where b.pk_id in ({$pkids})";
                            $id = sql_fetch($sql);
                            ?>
                            <li class="" onclick="location.href=g5_url+'/page/view?depth1_id=<?php echo $id["id"];?>&me_id=<?php echo $id["me_code"];?>&pk_id=<?php echo $id["pk_id"];?>'"> - <?php echo cut_str($myschedule[$i]["schedule_name"],15,'...');?><span><?php echo $indate[0];?></span></li>
                        <?php }?>
                    <?php }else{?>
                        <li>등록된 할일이 없습니다.</li>
                    <?php }?>
                    </ul>
                </div>
            </div>
            <div class="cmap_menu_td full_td cmenu10" onclick="fnWeather('<?php echo $member["mb_id"];?>','<?php echo $currentConst["id"];?>');">
                <div class="left">
                    <div class="todays">
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
                    echo date("Y년 m월 d일")."(".$week.")";
                    ?>
                    </div>
                    <div class="location">
                        <h2 class="addr"> </h2><span class="timedesc"></span>
                    </div>
                </div>
                <div class="right">
                    <div class="current_temp">
                        <div class="now_temp">

                        </div>
                        <div class="temp_min_max">

                        </div>
                    </div>
                    <div class="current_btn">
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
<div class="mymenu_detail">
    <div class="title">
        <h2></h2>
    </div>
    <div class="detail_list">

    </div>
    <div class="infos">
        <p><사용자 승인 시></p>
        <ul>
            <li>1. C.MAP 정보의 상호 공유</li>
            <li>2. 기술자별 업무분장 및 현황관리(책임자 버전)</li>
            <li>3. 정확한 정보관리 일원화로 협업 가능</li>
            <li>4. 업무 분장시 로그인 마다 최신정보 업데이트</li>
        </ul>
        <br><br>
        <p>주의 : C.MAP은 기준에 따라 업무를 수행할 수 있도록한 지원 프로그램입니다.</p>
        <p>보안프로그램이 아니므로 중요사항은 반드시 보안 또는 별도저장 하시기 바랍니다.</p>
    </div>
</div>
<?php }?>
<div class="search_area">
    <div class="search_wrap">
        <div class="searchs">
            <form action="<?php echo G5_URL;?>/page/search/search" method="post" name="searchFrom">
                <select name="search_type" id="search_type">
                    <option value="">전체검색</option>
                    <?php for($i=0;$i<count($search_menu);$i++){?>
                        <option value="<?php echo $search_menu[$i]["menu_code"];?>"><?php echo $search_menu[$i]["menu_name"];?></option>
                    <?php }?>
                </select>
                <input type="text" name="search_text" class="search_input" id="search_text" placeholder="검색어를 입력해주세요.">
                <input type="submit" value="" name="search_btn">
            </form>
        </div>
        <div class="populars">
            <h3>인기 검색어</h3>
            <div class="text">
            <?php for($i=0;$i<count($papular);$i++){?>
                <span onclick="fnSearchPapular('<?php echo $papular[$i]["search_text"];?>')"><?php echo $papular[$i]["search_text"];?></span>
            <?php }?>
            </div>
        </div>
    </div>
</div>
<div class="siteMaps">
    <div class="siteIn">
        <header>
            <h2>전체보기</h2>
            <input type="button" value="네비게이터 설정" class="basic_btn03" onclick="location.href='<?php echo G5_URL;?>/page/mypage/navigator'">
        </header>
        <div class="navigator_tab">
            <ul class="">
                <?php for($i=0;$i<count($menulist);$i++){?>
                    <li class="" id="allmenu_header<?php echo $menulist[$i]["menu_code"];?>" onclick="fnMenusHeader('<?php echo $menulist[$i]["menu_code"];?>')"><?php echo $menulist[$i]["menu_name"];?></li>
                <?php }?>
            </ul>
            <div class="clear"></div>
        </div>
        <div class="menus menus_head">
            <ul class="depth_menu depth_menu_heads">
            </ul>
        </div>
    </div>
</div>
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

                    if($is_member) {
                        $sqls = "select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code='{$row["menu_code"]}'";
                        $ress = sql_query($sqls);
                        while ($rows = sql_fetch_array($ress)) {
                            $mynavimenu[] = $rows;
                        }
                    }

                ?>
                <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex--; ?>">
                    <?php if($me_id==60 || $row["menu_name"] == "평가"){?>
                    <a href="<?php echo G5_URL?>/page/view2?me_id=<?php echo $row["menu_code"]; ?>" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                    <?php }else{?>
                    <a href="<?php echo G5_URL?>/page/view?me_id=<?php echo $row["menu_code"]; ?>" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                    <?php }?>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){
                        if( empty($row2) ) continue;

                        if($is_member) {
                            if (count($mynavimenu) > 0) {
                                for ($a = 0; $a < count($mynavimenu); $a++) {
                                    $ids_menu = explode("``", $mynavimenu[$a]["menu_ids"]);
                                    $subids_menu = explode("``", $mynavimenu[$a]["sub_ids"]);
                                    $actives_menu = explode("``", $mynavimenu[$a]["menu_ids_actives"]);
                                    $sub_actives_menu = explode("``", $mynavimenu[$a]["sub_ids_actives"]);
                                    for ($b = 0; $b < count($actives_menu); $b++) {
                                        $activeMenu[$ids_menu[$b]] = $actives_menu[$b];
                                    }

                                    for ($c = 0; $c < count($subids_menu); $c++) {
                                        $submenu = explode("|", $subids_menu[$c]);
                                        $subsmnues[$submenu[0]][$submenu[1]] = $sub_actives_menu[$c];
                                    }
                                }
                            }

                            //if($activeMenu[$row2["me_id"]]==0){continue;}
                        }

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
                            <a href="<?php if($num == 1){?><?php echo G5_URL?>/page/view?me_id=<?php echo $row2["menu_code"]; ?><?php }else{ ?>#<?php }?>" class="gnb_2da"><?php echo $row2['menu_name'] ?></a>
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
                                <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=339">업체평가 (80)</a></li>
                                <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=342">기술자평가 (20)</a></li>
                            <?php }else{

                            while($row3 = sql_fetch_array($res)){
                                if( empty($row3) ) continue;
                                /*if($is_member && count($subsmnues[$row2["me_id"]])>0) {
                                    if ($subsmnues[$row2["me_id"]][$row3["pk_id"]] == 0) {
                                        continue;
                                    }
                                }*/
                                if($row3["depth_name"]){
                                    if($row2["menu_code"]=='6064'){
                                    ?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view2?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                    <?php //}else if($me_id=='60129'){
                                        ?>
                                        <!--<li class="gnb_3dli "><a class="gnb_3da" href="<?php /*echo G5_URL*/?>/page/view3.php?me_id=<?php /*echo $row2["menu_code"]; */?>&depth1_id=<?php /*echo $row3["id"];*/?>"><?php /*echo $row3["depth_name"];*/?></a></li>-->
                                    <?php }else{?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
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
                    <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <a href="<?php echo G5_ADMIN_URL; ?>/menu_list">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
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
                        <li class="gnb_empty">메뉴 준비 중입니다.<?php if ($is_admin) { ?> <br><a href="<?php echo G5_ADMIN_URL; ?>/menu_list">관리자모드 &gt; 환경설정 &gt; 메뉴설정</a>에서 설정하실 수 있습니다.<?php } ?></li>
                    <?php } ?>
                </ul>
                <button type="button" class="gnb_close_btn"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>
        </div>
        <div class="etc_btns">
            <input type="button" onclick="fnSearch();" class="search_btns">
            <div class="navigator_set">
                <div class="icons"></div>
            </div>
        </div>
    </nav>
    <script>
    
    $(function(){
        $(".navigator_set").click(function(){
            if($(".icons").hasClass("active")) {
                $(".icons").removeClass("active");
                $(".siteMaps").removeClass("active");
            }else {
                $(".icons").addClass("active");
                $(".siteMaps").addClass("active");
            }
        });

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
<div class="container" <?php if($main){?>id="mainscreen"<?php }?>>
<?php if(!$main && $sub != "login" && $mypage != true){?>
<div class="user_guide">
    <div class="user">
        <?php if(!$depth1_id && !$me_code && !$me_id){?>
            <div>사용자 가이드</div>
        <?php }else{ ?>
            <div>
                    <?php if(substr($incode,0,2)==60 ){?>
                        <select name="me_id" id="me_id">
                        <?php for($i=0;$i<count($depth_me);$i++) {?>
                            <option value="60<?php echo $depth_me[$i]["me_id"];?>" <?php echo get_selected('60'.$depth_me[$i]["me_id"],$me_id);?>><?php echo $depth_me[$i]["menu_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }else{ ?>
                        <select name="depth1_id" id="depth1_id">
                        <?php for($i=0;$i<count($depth_me);$i++) {?>
                        <option value="<?php echo $depth_me[$i]["id"];?>" <?php echo get_selected($depth_me[$i]["id"],$depth1_id);?>><?php echo $depth_me[$i]["depth_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }?>
            </div>
        <?php }?>
        <div><?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></div>
    </div>
        <div class="clear"></div>
</div>
<?php }else if(!$main && $sub != "login" && $mypage != false){?>
<div class="user_guide">
    <div class="user">
        <div>사용자 가이드</div>
        <div><?php if($useguide[$menu_id]["menu_desc"]){echo $useguide[$menu_id]["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></div>
    </div>
    <div class="clear"></div>
</div>
<?php }?>
<!-- } 상단 끝 -->

