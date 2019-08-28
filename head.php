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

include_once (G5_PATH."/head.config.php");

?>

<!-- 로그인 -->
<?php if(defined('_INDEX_')) {?>
<div class="login <?php if(!defined('_INDEX_')) {?>sub<?php }?>">
    <div class="login_btns">
        <?php if(!$is_member){?>
            <img src="<?php echo G5_IMG_URL?>/main_login_btn.png" alt="로그인" onclick="location.href='<?php echo G5_BBS_URL?>/login'">
            <!--<div class="board" onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'">
                <img src="<?php /*echo G5_IMG_URL;*/?>/ic_databoard.svg" alt=""> <h2 >게시판</h2>
            </div>
            <div class="board" onclick="location.href=g5_url+'/page/board/inquiry'">
                <img src="<?php /*echo G5_IMG_URL;*/?>/ic_inquiry.svg" alt=""> <h2 >제안</h2>
            </div>-->
        <?php }else{?>
            <img src="<?php echo G5_IMG_URL?>/mypage_btn.png" alt="로그인" onclick="fnMyprofile('<?php echo $member["mb_id"];?>');">
        <?php }?>
    </div>
</div>
<div class="my_profile <?php if($menu=="on"){?>active<?php }?>">
    <div class="my_profile_top">
        <h2 >
            <span class="<?php if($member["mb_level"]==3){echo "cm";}else if($member["mb_level"]==5){echo "pm";}?>"><?php if($member["mb_level"]==3){echo "CM";}else if($member["mb_level"]==5){echo "PM";}?></span>
            <label onclick="location.href=g5_url+'/page/mypage/mypage'"><?php echo $member["mb_id"];?></label> 님
            <img src="<?php echo G5_IMG_URL?>/ic_logout_w.png" alt="" onclick="fnLogout()" class="logout">
            <img src="<?php echo G5_IMG_URL?>/ic_profile_setting.svg" alt="" onclick="location.href=g5_url+'/page/mypage/mypage'" class="settings">
            <div class="close" onclick="fnCloseProfile()"></div>
        </h2>
        <div class="pays">
            <?php if($member["mb_paused_status"]==1){?>
                <span>맴버쉽 취소 처리중..</span> <?php if($member["parent_mb_id"]==""){?><a href="javascript:fnRefundCancel('<?php echo $member["mb_id"];?>')">요청취소 <span></span></a><?php }?>

            <?php }else{?>
            <?php if($member["mb_level"] >= 3 && $member["mb_level"] < 10){?>
                <span>맴버쉽기한 : <?php echo ($mypayments["payment_end_date"])?$mypayments["payment_end_date"]:"결제정보 없음";?></span>
                <?php if($member["parent_mb_id"]==""){?>
                <a href="javascript:<?php if(!$is_admin){?>fnPayment();<?php }else{?>alert('최고관리자는 구매 하실 수 없습니다.')<?php }?>">연장 <span></span> </a>
                <?php }?>
            <?php }if($member["mb_level"] <= 2 && $member["mb_level"] != 10){?>
                <span>맴버쉽 구매가 필요합니다.</span>
                <?php if($member["parent_mb_id"]==""){?>
                <a href="javascript:<?php if(!$is_admin){?>fnPayment();<?php }else{?>alert('최고관리자는 구매 하실 수 없습니다.')<?php }?>">결제 <span></span> </a>
                <?php }?>
            <?php }?>
            <?php }?>
        </div>
    </div>
    <div class="mycmap">
        <select name="mylocmap" id="mylocmap" class="cmap_sel width100" onchange="fnChangeConst('<?php echo $member["mb_id"];?>',this.value)" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }?>">
            <option value="">현장 선택</option>
            <?php for($i=0;$i<count($mycont);$i++){?>
                <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
            <?php }?>
        </select>
        <div class="cmap_menu">

            <div class="cmap_menu_td cmenu1" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{ if($member["mb_level"]==3){?>location.href=g5_url+'/page/mylocation/mylocation'<?php }else if($member["mb_level"]==5){?>location.href=g5_url+'/page/manager/pm_construct'<?php } } ?>">
                <h2 class="count_title"><?php if($member["mb_level"]==5){?>PM 지구<?php }else{ ?>현장관리<?php }?> <span><strong><?php echo number_format(count($mycont));?></strong> 개</span></h2>
                <!--<div class="counts"></div>-->
            </div>
            <div class="cmap_menu_td full_td cmenu2" onclick="<?php if($member['mb_auth']==false){?>alert('PMMODE 구매후 이용가능합니다.')<?php }else{?>location.href=g5_url+'/page/manager/'<?php }?>">
                <img src="<?php echo G5_IMG_URL?>/ic_construct.svg" alt=""><h2>PM MODE</h2>
            </div>
            <div class="cmap_menu_td cmenu3" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewRequest('<?php echo $member["mb_id"];?>','')<?php }?>">
                <input type="hidden" id="const_id" value="">
                <h2 class="count_title">사용자관리 <span><strong><?php echo number_format(count($reqlist));?></strong> 건</span></h2>
                <!--<div class="counts">

                </div>-->
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td cmenu4" onclick="location.href=g5_url+'/page/mypage/schedule'">
                <img src="<?php echo G5_IMG_URL;?>/ic_schedule.svg" alt=""> <h2>스케쥴</h2>
            </div>
            <?php }?>
            <div class="cmap_menu_td cmenu5" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewMessage('<?php echo $member["mb_id"];?>','')<?php }?>">
                <h2 class="count_title">업무연락서 <span><strong><?php echo number_format(count($msglist));?></strong> 건</span></h2>
                <!--<div class="counts">

                </div>-->
            </div>
            <div class="cmap_menu_td <?php if($member["mb_level"]!=5){?>cmenu6<?php }else{?>cmenu4 border6<?php }?>" onclick="location.href=g5_bbs_url+'/board?bo_table=databoard'">
                <img src="<?php echo G5_IMG_URL;?>/ic_databoard.svg" alt=""> <h2>게시판</h2>
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td cmenu7" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnViewDelay('<?php echo $member["mb_id"];?>','')<?php }?>">
                <h2 class="count_title">제출지연건 <span><strong><?php echo number_format(count($maindelaylists));?></strong> 건</span></h2>
            </div>
            <?php }?>
            <div class="cmap_menu_td <?php if($member["mb_level"]!=5){?>cmenu8<?php }else{?>cmenu6 border8<?php }?> " onclick="location.href=g5_url+'/page/board/inquiry_payment'">
                <img src="<?php echo G5_IMG_URL;?>/ic_inquiry.svg" alt=""> <h2>제안하기</h2>
            </div>
            <?php if($is_admin){?>
                <div class="cmap_menu_td full_td cmenu14" onclick="location.href=g5_url+'/admin/inquiry_payment'">
                    <h2 class="count_title">문의요청 <span><strong><?php echo number_format($inquirys["cnt"]);?></strong> 건</span></h2>
                </div>
            <?php }else{?>
            <div class="cmap_menu_td full_td cmenu9 <?php if($member["mb_level"]==5){?>cmenu9_1<?php }?>">
                <h3>시공평가 점수</h3>
                <div class="eval1 <?php echo $evel1_class;?>">
                    <span style="<?php if($eval1_left){?>left:calc(<?php echo ceil($eval1_left);?>% - 40px);<?php }?>" class="<?php echo $evel1_class;?>">
                        <p class="eval1_p"><?php echo ceil($eval1_total);?></p>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#FFFFFF;}
                        </style>
                        <g>
                            <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5" />
                            <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z" />
                        </g>
                        </svg>
                    </span>
                    <div><label>80</label></div>
                    <div><label>90</label></div>
                    <div></div>
                </div>
                <h3>용역평가 점수</h3>
                <div class="eval2 <?php echo $evel2_class;?>">
                    <span style="<?php if($eval2_left){?>left:calc(<?php echo ceil($eval2_left);?>% - 40px);<?php }?>" class="<?php echo $evel2_class;?>">
                        <p class="eval2_p"><?php echo ceil($eval2_total);?></p>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 50 26" style="enable-background:new 0 0 50 26;" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:#FFFFFF;}
                        </style>
                        <g>
                            <polygon class="st0" points="13.1,25.5 1.5,13 13.1,0.5 36.9,0.5 48.5,13 36.9,25.5" />
                            <path d="M36.7,1l11.2,12L36.7,25H13.3L2.2,13L13.3,1H36.7 M37.1,0H12.9L0.8,13l12.1,13h24.2l12.1-13L37.1,0L37.1,0z" />
                        </g>
                        </svg>
                    </span>
                    <div><label>80</label></div>
                    <div><label>90</label></div>
                    <div></div>
                </div>
            </div>
            <?php if($member["mb_level"]!=5){?>
            <div class="cmap_menu_td full_td cmenu10">
                <h2>오늘의 할일</h2>
                <div class="more" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnScheduleView()<?php }?>">MORE ></div>
                <div class="lists">
                    <ul>
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
                            ?>
                            <li class="" onclick="<?php echo $link;?>"> - <?php echo cut_str($myschedule[$i]["schedule_name"],15,'...');?><span><?php echo $indate[0];?></span></li>
                        <?php }?>
                    <?php }else{?>
                        <li>등록된 할일이 없습니다.</li>
                    <?php }?>
                    </ul>
                </div>
            </div>
            <?php }?>
            <?php }?>

            <div class="cmap_menu_td full_td cmenu11 <?php if($member["mb_level"]==5){?>cmenu11_1<?php } if($is_admin){?>cmenu11_2<?php }?>" >
                <div class="left" onclick="<?php if($member['mb_auth']==false){?>alert('맴버쉽 구매후 이용가능합니다.')<?php }else{?>fnWeather('<?php echo $member["mb_id"];?>','<?php echo $currentConst["id"];?>');<?php }?>">
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
            <li>1. 사용자간 CMAP 현황 복사 및 공유</li>
            <li>2. 사용자간 업무연락서 발신 및 수신 </li>
        </ul>
        <br><br>
        <p>주의 :     CMAP은 건설관리 지원프로그램으로 주요내용은 별도 보안조치 및 저장하시기 바랍니다.</p>
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
            <div class="texts">
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
<div class="header_top" style="position:fixed;top:0;display:inline-block;left:0;width:100%;height:60px;z-index: 12">
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
                    <img src="<?php echo G5_IMG_URL ?>/logo_b.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }else{ ?>
                    <img src="<?php echo G5_IMG_URL ?>/logo.svg" alt="<?php echo $config['cf_title']; ?>">
                    <?php }?>
                </a>
            </div>
            <div class="gnb_wrap">
                <ul id="gnb_1dul">
                    <!--<li class="gnb_1dli gnb_mnal"><button type="button" class="gnb_menu_btn"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">전체메뉴열기</span></button></li>-->
                    <?php
                    if($member["mb_level"]!=5){

                    $sql = " select *
                                from `cmap_menu`
                                where menu_status = 0
                                  and menu_depth = 0
                                order by menu_order ";
                    $result = sql_query($sql, false);
                    $gnb_zindex = 999; // gnb_1dli z-index 값 설정용
                    $menu_datas = array();

                    for ($i=0; $row=sql_fetch_array($result); $i++) {//대메뉴

                        $menu_datas[$i] = $row;

                        $sql2 = " select *
                                    from `cmap_menu`
                                    where menu_status = 0
                                      and menu_depth = 1
                                      and substring(menu_code, 1, 2) = '{$row['menu_code']}'
                                    order by menu_order ";
                        $result2 = sql_query($sql2);
                        for ($k=0; $row2=sql_fetch_array($result2); $k++) {//1차
                            $menu_datas[$i]['sub'][$k] = $row2;
                            $sql3 = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by id asc ";
                            $result3 = sql_query($sql3);
                            for($l = 0; $row3 = sql_fetch_array($result3);$l++){//2차
                                if($row["menu_code"]==40) {
                                    if (in_array($row3["pk_id"], $menuchk)) {
                                        if ($menuchk_act[$row3["pk_id"]] == 1) {
                                            $menu_datas[$i]['sub'][$k]['cnt']++;
                                        }
                                    }
                                }
                                //$menus3[] = $row3;
                                if($delayhead[$row3["pk_id"]]){
                                    $menu_datas[$i]['sub'][$k]["delay"] = true;
                                    continue;
                                }else{
                                    $menu_datas[$i]['sub'][$k]["delay"] = false;
                                }
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
                    <li class="gnb_1dli" style="z-index:<?php echo $gnb_zindex--; ?>"> <!-- 메인 메뉴 5개 -->
                        <?php if($me_id==60 || $row["menu_name"] == "평가"){?>
                        <a href="#" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                        <?php }else{?>
                        <a href="#" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                        <?php }?>
                        <?php
                        $k = 0;
                        foreach( (array) $row['sub'] as $row2 ){
                            if( empty($row2) ) continue;
                            if($is_member && count($mycont)>0) {
                                if($row["menu_code"]==40) {
                                    if ($row2["cnt"] == 0) {
                                        continue;
                                    }
                                }
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
                            <li class="gnb_2dli <?php if($num>1){?>arrows<?php }?> <?php if($row2["delay"] || $delayhead2[$row2["menu_code"]]){ if($activeMenu[$row2["me_id"]]=="1"){ ?>chk<?php } }?> " > <!-- 1depth  -->
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
                                    echo '<ul class="gnb_3dul '.$over_top.'">'; //2depth??

                                if($row2["menu_name"]=="용역평가"){?>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=338">업체평가 (80)</a></li>
                                    <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view3?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=341">기술자평가 (20)</a>
                                <?php }else{

                                while($row3 = sql_fetch_array($res)){
                                    if( empty($row3) ) continue;
                                    if($row["menu_code"]==40) {
                                        if (in_array($row3["pk_id"], $menuchk)) {
                                            if ($menuchk_act[$row3["pk_id"]] == 0) {
                                                continue;
                                            }
                                        }
                                    }
                                    /*if($is_member && count($subsmnues[$row2["me_id"]])>0) {
                                        if ($subsmnues[$row2["me_id"]][$row3["pk_id"]] == 0) {
                                            continue;
                                        }
                                    }*/
                                    if($subsmnues[$row2["me_id"]][$row3["pk_id"]] == 1){
                                      if($delayhead[$row3["pk_id"]]){
                                          $chk = "chk";
                                      }else{
                                          $chk = "";
                                      }
                                    }else{
                                      $chk = "";
                                    }

                                    if($row3["depth_name"]){
                                        if($row2["menu_code"]=='6064'){
                                        ?>
                                        <li class="gnb_3dli "><a class="gnb_3da" href="<?php echo G5_URL?>/page/view2?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                        <?php //}else if($me_id=='60129'){
                                            ?>
                                            <!--<li class="gnb_3dli "><a class="gnb_3da" href="<?php /*echo G5_URL*/?>/page/view3.php?me_id=<?php /*echo $row2["menu_code"]; */?>&depth1_id=<?php /*echo $row3["id"];*/?>"><?php /*echo $row3["depth_name"];*/?></a></li>-->
                                        <?php }else{?>
                                        <li class="gnb_3dli menu_<?php echo $row3["pk_id"];?> <?php echo $chk;?>">
                                            <a class="gnb_3da" href="<?php echo G5_URL?>/page/view?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
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
                    <?php }else{?>
                        <li class="gnb_empty">CMAP PMMODE - <?php echo $member["mb_1"];?></li>
                    <?php }?>
                </ul>
            </div>
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
            <?php if($member["mb_level"]!=5){?>
            <div class="etc_btns">
                <input type="button" onclick="fnSearch();" class="search_btns">
                <div class="navigator_set">
                    <div class="icons"></div>
                </div>
            </div>
            <?php }?>
        </nav>
        <script>

        $(function(){
            $(".navigator_set").click(function(){
                if($(".icons").hasClass("active")) {
                    $(".icons").removeClass("active");
                    $(".siteMaps").removeClass("active");
                    $(".header_top .container").show();
                    $(".header_top .search").show();
                }else {
                    $(".header_top .container").attr("style","z-index:0");
                    $(".icons").addClass("active");
                    $(".siteMaps").addClass("active");
                    $(".header_top .container").hide();
                    $(".header_top .search").hide();
                    fnMenusHeader('10');
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
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#000 !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right_b.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
                    <?php }else if($myset["cate_theme"]=="black"){ ?>
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
                    <?php }else if($myset["cate_theme"]=="white"){ ?>
                        $(this).parent().parent().find($(".gnb_2da")).attr({"style":"color:#FFF !important;background-image:url('<?php echo G5_IMG_URL?>/ic_arrow_right.svg');background-repeat:no-repeat;background-position:right center;background-size:28px 28px;"});
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
                //fnShowModal(data);
                $(".etc_view").html(data);
                $(".etc_view").addClass("active");
                $(".etc_view_bg").addClass("active");
            });
        }
        </script>
    </div>
    <div class="container" <?php if($main){?>id="mainscreen"<?php }?>>
    <?php if(!$main && $sub != "login" && $mypage != true){?>
        <?php if($sub!="search"){?>
    <div class="user_guide">
        <table class="user2">
            <tr>
            <?php if(!$depth1_id && !$me_code && !$me_id){?>
                <td class="navies">사용자 가이드</td>
            <?php }else{ ?>
                <?php if(count($mycont)>0){?>
                <td class="first">
                    <select name="mylocmap" id="mylocmap" class="cmap_sel" style="width:180px;" onchange="fnChangeConst2('<?php echo $member["mb_id"];?>',this.value)">
                        <option value="" <?php if($current_const["const_id"]==0){?>selected<?php }?>>현장 선택</option>
                        <?php for($i=0;$i<count($mycont);$i++){?>
                            <option value="<?php echo $mycont[$i]["id"];?>" <?php if($current_const["const_id"]==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                        <?php }?>
                    </select>
                </td>
                <?php } ?>
                <td class="navies">
                    <?php if(substr($incode,0,2)==60 ){?>
                        <select name="me_id" id="me_id">
                        <?php for($i=0;$i<count($depth_me);$i++) {?>
                            <option value="60<?php echo $depth_me[$i]["me_id"];?>" <?php echo get_selected('60'.$depth_me[$i]["me_id"],$me_id);?>><?php echo $depth_me[$i]["menu_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }else{ ?>
                        <select name="depth1_id" id="depth1_id" style="width:176px;" >
                        <?php for($i=0;$i<count($depth_me);$i++) {
                            if(substr($me_id,0,2)==40) {
                                if (in_array($depth_me[$i]["pk_id"], $menuchk)) {
                                    if ($menuchk_act[$depth_me[$i]["pk_id"]] == 0) {
                                        continue;
                                    }
                                }
                            }
                            ?>
                        <option value="<?php echo $depth_me[$i]["id"];?>" <?php echo get_selected($depth_me[$i]["id"],$depth1_id);?>><?php echo $depth_me[$i]["depth_name"];?></option>
                        <?php }?>
                        </select>
                    <?php }?>
                </td>
            <?php }?>
                <td><span title="<?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?>" ><?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span></td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
        <?php }?>
    <?php }else if(!$main && $sub != "login" && $mypage != false){?>
        <?php if($sub!="search"){?>
    <div class="user_guide">
        <div class="user">
            <div>사용자 가이드</div>
            <div><span><?php if($useguide[$menu_id]["menu_desc"]){echo $useguide[$menu_id]["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span></div>
        </div>
        <div class="clear"></div>
    </div>
        <?php }?>
    <?php }?>
    <span class="widthchk" style="opacity: 0;white-space: nowrap;height: 0;display:none;"><?php if($useguide["menu_desc"]){echo $useguide["menu_desc"];}else{echo "사용자 가이드를 입력해주세요.";}?></span>
    </div>

    <?php if($test=="msg"){?>
        <div class="search" style="position: relative;" id="msg_search">
            <form action="" method="get">
                <select name="const_id" id="cons_id" class="basic_input01" >
                    <option value="">현장 선택</option>
                    <?php for($i=0;$i<count($mycont);$i++){?>
                        <option value="<?php echo $mycont[$i]["id"];?>" <?php if($const_id==$mycont[$i]["id"]){?>selected<?php }?>><?php echo $mycont[$i]["cmap_name"];?></option>
                    <?php }?>
                </select>
                <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker1" name="date1" value="<?php if($date1==""){echo date("Y-m-d");}else{echo $date1;}?>">
                <input type="text" class="datepicker basic_input01 " style="width:120px;" id="datepicker2" name="date2" value="<?php if($date2==""){echo date("Y-m-d");}else{echo $date2;}?>">
                <select name="search_type" id="search_type" class="basic_input01 width10">
                    <option value="" <?php if($_GET["search_type"]==""){?>selected<?php }?>>전체</option>
                    <option value="0" <?php if($_GET["search_type"]=="0"){?>selected<?php }?>>수신</option>
                    <option value="1" <?php if($_GET["search_type"]=="1"){?>selected<?php }?>>발신</option>
                </select>
                <select name="sfl" id="sfl" class="basic_input01 width10">
                    <option value="" <?php if($sfl==""){?>selected<?php }?>>전체</option>
                    <option value="name" <?php if($sfl=="name"){?>selected<?php }?>>담당자</option>
                    <option value="msg_subject" <?php if($sfl=="msg_subject"){?>selected<?php }?>>제목</option>
                    <option value="msg_content" <?php if($sfl=="msg_content"){?>selected<?php }?>>내용</option>
                </select>
                <input type="text" class="basic_input01 width20" id="datepicker2" name="search_text" value="<?php echo $search_text;?>" placeholder="검색어">
                <input type="submit" class="basic_btn03" value="검색">
            </form>
            <div class="work_msg_btns">
                <input type="button" class="basic_btn02" value="작성하기" onclick="fnWriteMessage('')">
            </div>
        </div>
    <?php }if($test=="mng"){?>

        <div class="search" style="position: relative;" id="msg_search">
            <form action="" method="get">
                <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" class="basic_input01 width20" placeholder="현장명을 입력해주세요.">
                <select name="sfl" id="sfl" class="basic_input01 width10">
                    <option value="0" <?php if($sfl=="0"){?>selected<?php }?>>전체표기</option>
                    <option value="1" <?php if($sfl=="1"){?>selected<?php }?>>준공현장</option>
                    <option value="2" <?php if($sfl=="2"){?>selected<?php }?>>진행현장</option>
                </select>
                <input type="submit" class="basic_btn01" value="검색">
            </form>
            <div class="work_msg_btns2">
                <input type="button" class="basic_btn03" value="총괄보고서" onclick="fnSavePm('<?php echo $mngType;?>')">
                <input type="button" class="basic_btn03" value="지구관리" onclick="location.href=g5_url+'/page/manager/pm_construct'">
                <input type="button" class="basic_btn02" value="새로고침" onclick="location.reload()">
                <!--<input type="button" class="basic_btn02" value="PM 보고서" onclick="fnSavePm('<?php /*echo $mngType;*/?>')">-->
                <input type="button" class="basic_btn02" value="업무연락서" onclick="location.href=g5_url+'/page/mypage/my_message_list'">
            </div>
        </div>
    <?php }?>
</div>
<script>
    jQuery.fn.hasOverflown = function () {
        var res;
        var cont = $('<div>'+this.text()+'</div>').css("display", "table")
            .css("z-index", "-1").css("position", "absolute")
            .css("font-family", this.css("font-family"))
            .css("font-size", this.css("font-size"))
            .css("font-weight", this.css("font-weight")).appendTo('body');
        res = (cont.width()>this.width());
        cont.remove();
        return res;
    };

    var ww = 0;
    var left = 0;
    var chk = false;
    $(function(){
        ww = $(".widthchk").width();
        chk = $(".user2 td:last-child span").hasOverflown();
        left = $(".user2 td:last-child span").css("left");
        if(chk){
            animateSpan();
        }
    });

    function animateSpan(){
        if($(".user2 td:last-child span").position().left > -ww){
            $(".user2 td:last-child span").animate({
                left: "-=5"
            }, 100, animateSpan);
        }else{
            $(".user2 td:last-child span").css({"left":"0"});
            //animateSpan2();
        }
    }
    /*
    function animateSpan2(){
        if($(".user2 td:last-child span").position().left <= 0){
            $(".user2 td:last-child span").animate({
                left: "+=8"
            }, 100, animateSpan2);
        }else{
            setTimeout(animateSpan,2000);
        }
    }*/
    function fnRefundCancel(mb_id) {
        if(confirm("맴버쉽 취소요청을 취소하시겠습니까?")){
            location.href=g5_url+'/page/mypage/member_refund_cancel?mb_id='+mb_id;
        }
    }
</script>
<!-- } 상단 끝 -->
