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
    <nav id="gnb">
        <div id="logo">
            <a href="<?php echo G5_URL ?>">
                <?php if($main){?>
                <img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>">
                <?php }else{ ?>
                <img src="<?php echo G5_IMG_URL ?>/logo_b.png" alt="<?php echo $config['cf_title']; ?>">
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
                        $sql3 = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by me_code ";
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
                    <a href="<?php echo G5_URL?>/page/view.php?me_id=<?php echo $row["menu_code"]; ?>" class="gnb_1da"><?php echo $row['menu_name'] ?></a>
                    <?php
                    $k = 0;
                    foreach( (array) $row['sub'] as $row2 ){
                        if( empty($row2) ) continue;

                        if($k == 0)
                            echo '<span class="bg">하위분류</span><ul class="gnb_2dul">'.PHP_EOL;
                        $sql = "select * from `cmap_depth1` where me_code = '{$row2["menu_code"]}' order by me_code asc";
                        $res = sql_query($sql);
                        $num = sql_num_rows($res);
                    ?>
                        <li class="gnb_2dli" >
                            <a href="<?php if($num == 1){?><?php echo G5_URL?>/page/view.php?me_id=<?php echo $row2["menu_code"]; ?><?php }else{ ?>#<?php }?>" class="gnb_2da"><?php echo $row2['menu_name'] ?></a>
                            <?php
                            if($num >= 10 && $a >= 10){
                                $over_top = "over_top";
                            }else{
                                $over_top = "";
                            }

                            if($num > 1){
                                echo '<ul class="gnb_3dul '.$over_top.'">';

                            $a=1;
                            while($row3 = sql_fetch_array($res)){
                                if( empty($row3) ) continue;
                                if($row3["depth_name"]){?>
                                    <li class="gnb_3dli "><a class="gnb_2da" href="<?php echo G5_URL?>/page/view.php?me_id=<?php echo $row2["menu_code"]; ?>&depth1_id=<?php echo $row3["id"];?>"><?php echo $row3["depth_name"];?></a></li>
                                <?php }?>
                            <?php $a++;} echo '</ul>';?>
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
    });

    </script>
</div>
<!-- } 상단 끝 -->

