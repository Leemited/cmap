<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
include_once (G5_PATH."/_head.php");

if(!$is_member){
    alert("로그인이 필요합니다.",G5_BBS_URL."/login.php");
}
/*if($is_admin){
    alert("관리자는 관리자페이지를 통해 이용 바랍니다.");
}*/

$myset = sql_fetch("select * from `cmap_mymenu_theme` where mb_id = '{$member["mb_id"]}'");

?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>MY C.MAP</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout.php"><span></span>로그아웃</a>
            </div>
        </header>
        <aside class="mypage_menu">
            <div class="mtop">
                <?php echo $member["mb_id"];?>님 회원정보
            </div>
            <div class="mbottom">
                <ul class="mmenu">
                    <li class="active"><i></i>홈페이지 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/quickmenu.php'"><i></i>퀵메뉴 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/navigator.php'"><i></i>네비게이터 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/guide.php'"><i></i>사용자 가이드 설정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd.php'"><i></i>개인정보 수정</li>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave.php'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>홈페이지 설정</h2>
            </header>
            <div class="homepage_con">
                <form action="<?php echo G5_URL?>/page/mypage/mytheme_update.php" name="themeform" method="post">
                    <h3>메뉴디자인 설정</h3>
                    <ul>
                        <li class="float">
                            <label for="theme1">
                                <div for="theme1">
                                    <img src="<?php echo G5_IMG_URL;?>/theme1.jpg" alt="테마1">
                                </div>
                            </label>
                            <input type="radio" name="theme" id="theme1" value="black" <?php if($myset["theme"]=="black" || $myset["theme"] == ""){?>checked<?php }?>>
                            <label for="theme1"><span></span>블랙</label>
                        </li>
                        <li class="float">
                            <label for="theme2">
                                <div>
                                    <img src="<?php echo G5_IMG_URL;?>/theme2.jpg" alt="테마2">
                                </div>
                            </label>
                            <input type="radio" name="theme" id="theme2" value="blue" <?php if($myset["theme"]=="blue"){?>checked<?php }?>>
                            <label for="theme2"><span></span>블루</label>
                        </li>
                        <li class="float">
                            <label for="theme3">
                                <div>
                                    <img src="<?php echo G5_IMG_URL;?>/theme3.jpg" alt="테마3">
                                </div>
                            </label>
                            <input type="radio" name="theme" id="theme3" value="white" <?php if($myset["theme"]=="white"){?>checked<?php }?>>
                            <label for="theme3"><span></span>화이트</label>
                        </li>
                        <div class="clear"></div>
                    </ul>
                    <h3>카테고리디자인 설정</h3>
                    <ul>
                        <li class="float">
                            <label for="cate1">
                                <div>
                                    <img src="<?php echo G5_IMG_URL;?>/cate_theme1.jpg" alt="카테고리1">
                                </div>
                            </label>
                            <input type="radio" name="cate" id="cate1" value="black" <?php if($myset["cate_theme"]=="black"){?>checked<?php }?>>
                            <label for="cate1"><span></span>블랙</label>
                        </li>
                        <li class="float">
                            <label for="cate2">
                                <div>
                                    <img src="<?php echo G5_IMG_URL;?>/cate_theme2.jpg" alt="카테고리2">
                                </div>
                            </label>
                            <input type="radio" name="cate" id="cate2" value="blue" <?php if($myset["cate_theme"]=="blue"){?>checked<?php }?>>
                            <label for="cate2"><span></span>블루</label>
                        </li>
                        <li class="float">
                            <label for="cate3">
                                <div>
                                    <img src="<?php echo G5_IMG_URL;?>/cate_theme3.jpg" alt="카테고리3">
                                </div>
                            </label>
                            <input type="radio" name="cate" id="cate3" value="white" <?php if($myset["cate_theme"]=="white" || $myset["cate_theme"] == ""){?>checked<?php }?>>
                            <label for="cate3"><span></span>화이트</label>
                        </li>
                    </ul>
                    <div class="clear"></div>
                    <div class="mypage_btns">
                        <input type="button" value="미리보기" class="basic_btn02 width20" onclick="fnPreView();">
                        <input type="submit" value="적용" class="basic_btn01 width20">
                    </div>
                </form>
            </div>
        </article>
    </section>
</div>
<script>
    function fnPreView(){
        var theme = $("input[name=theme]:checked").val();
        var cate_theme = $("input[name=cate]:checked").val();
        $.ajax({
            url:g5_url+"/page/modal/ajax.theme_preview.php",
            method:"post",
            data:{theme:theme,cate_theme:cate_theme}
        }).done(function(data){
            fnShowModal(data);

            setTimeout(fnModalTop,100);
        });
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
