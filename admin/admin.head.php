<?php
// 접근 권한 검사
if (!$member['mb_id'])
{
    alert('로그인이 필요합니다..', G5_BBS_URL.'/login?sub=login');
}else if ($member["mb_level"] < 10)
{
    alert("접근 권한이 없습니다.",G5_URL);
}

include_once(G5_PATH."/head.sub.php");

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/default.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/admin.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/jquery-ui.min.css">', 0);
$sql = "select * from `cmap_menu` where menu_status = 0 and menu_depth = 0 order by menu_order asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $cmap_menu[] = $row;
}

?>
<div class="full-width">
    <header>
        <div class="top">
            <!--<div class="logo" >
                <a href="<?php /*echo G5_URL*/?>/admin/"><img src="<?php /*echo G5_IMG_URL*/?>/logo.png" alt=""></a>
            </div>-->
            <div class="title">
                <a href="<?php echo G5_URL?>/admin/"><h2>CMAP ADMIN PAGE</h2></a>
            </div>
            <div class="clear"></div>
            <div class="loginfo">
                <ul>
                    <li><a href="<?php echo G5_BBS_URL?>/logout">로그아웃</a></li>
                    <li><a href="<?php echo G5_URL?>">HOMEPAGE</a></li>
                </ul>
            </div>
            <div class="top_bg"></div>
        </div>
        <div class="left">
            <div class="lnb">
                <ul data-accordion-group id="admin-menu">
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">메뉴관리</div>
                        <div data-content class="list-item">
                            <?php
                            for($i=0;$i<count($cmap_menu);$i++){
                                //$sql = "select * from `$g5[menu_table]` where SUBSTRING(me_code,1,2) = {$menu[$i]['me_code']} and me_code != {$menu[$i]['me_code']}";
                                //$res = sql_query($sql);
                            ?>
                            <div >
                                <?php if($cmap_menu[$i]["menu_name"]!="건설사업관리" && $cmap_menu[$i]["menu_name"]!="시공확인/검측" && $cmap_menu[$i]["menu_name"]!="평가"){?>
                                <a href="<?php echo G5_URL?>/admin/construction?menu_code=<?php echo $cmap_menu[$i]["menu_code"];?>&menu_name=<?php echo $cmap_menu[$i]["menu_name"];?>"><?php echo $cmap_menu[$i]["menu_name"];?></a>
                                <?php }else if($cmap_menu[$i]["menu_name"]=="시공확인/검측"){?>
                                <a href="<?php echo G5_URL?>/admin/construction2?menu_code=<?php echo $cmap_menu[$i]["menu_code"];?>&menu_name=<?php echo $cmap_menu[$i]["menu_name"];?>"><?php echo $cmap_menu[$i]["menu_name"];?></a>
                                <?php }else if($cmap_menu[$i]["menu_name"]=="건설사업관리"){?>
                                    <a href="<?php echo G5_URL?>/admin/evaluation?menu_code=<?php echo $cmap_menu[$i]["menu_code"];?>&menu_name=<?php echo $cmap_menu[$i]["menu_name"];?>"><?php echo $cmap_menu[$i]["menu_name"];?></a>
                                <?php }else if($cmap_menu[$i]["menu_name"]=="평가"){?>
                                    <a href="<?php echo G5_URL?>/admin/evaluation3?menu_code=<?php echo $cmap_menu[$i]["menu_code"];?>&menu_name=<?php echo $cmap_menu[$i]["menu_name"];?>"><?php echo $cmap_menu[$i]["menu_name"];?></a>
                                <?php }?>
                            </div>
                            <!--<ul data-accordion-group id="admin-menu">
                                <li class="accordion" data-accordion>
                                    <div data-control class="list-title"><?php /*echo $menu[$i]["me_name"];*/?></div>
                                    <div data-content class="list-item">
                                        <?php /*while($row=sql_fetch_array($res)){*/?>
                                            <div>
                                                <a href="<?php /*echo G5_URL*/?>/admin/construction?me_id=<?php /*echo $row["me_code"];*/?>"><?php /*echo $row["me_name"];*/?></a>
                                            </div>
                                        <?php /*}*/?>
                                    </div>
                                </li>
                            </ul>-->
                            <?php }?>
                            <!--<div><a href="<?php /*echo G5_URL."/admin/management?"; */?>">공사관리</a></div>
                            <div><a href="<?php /*echo G5_URL."/admin/evaluation"; */?>">시공확인</a></div>
                            <div><a href="<?php /*echo G5_URL."/admin/guide"; */?>">점검/평가</a></div>-->
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">홈페이지관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/navigator"; ?>">사용자 가이드</a></div>
                            <div><a href="<?php echo G5_URL."/admin/holidays"; ?>">공휴일 관리</a></div>
                            <div><a href="<?php echo G5_URL."/admin/main_image"; ?>">메인이미지 관리</a></div>
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">게시물관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/faq?fm_id=1"; ?>">자주묻는질문관리</a></div>
                            <div><a href="<?php echo G5_URL."/admin/inquiry"; ?>">제안관리</a></div>
                            <!--<div><a href="<?php /*echo G5_URL."/admin/board"; */?>">게시판관리</a></div>-->
                        </div>
                    </li>
                    <li class="accordion" data-accordion>
                        <div data-control class="list-title">회원관리</div>
                        <div data-content class="list-item">
                            <div><a href="<?php echo G5_URL."/admin/member_list"; ?>">회원관리</a></div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="left_bg"></div>
        </div>
    </header>