<?php
include_once ("../../common.php");

if($theme=="black"){
    $topimg = "top_black.jpg";
}
if($theme=="blue"){
    $topimg = "top_blue.jpg";
}
if($theme=="white"){
    $topimg = "top_white.jpg";
}

if($cate_theme=="black"){
    $bottomimg = "bottom_black.jpg";
}
if($cate_theme=="blue"){
    $bottomimg = "bottom_blue.jpg";
}
if($cate_theme=="white"){
    $bottomimg = "bottom_white.jpg";
}

?>
<div class="modal_in" id="theme_preview">
    <div class="modal_title">
        <h2><i></i>미리보기</h2>
        <div class="apply_preview">
            <input type="button" class="basic_btn01" value="바로적용" onclick="fnApply('<?php echo $theme;?>','<?php echo $cate_theme;?>')">
        </div>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/ic_close.svg" alt="닫기">
        </div>
    </div>
    <div class="modal_content">
        <img src="<?php echo G5_IMG_URL."/".$topimg;?>" alt="<?php echo $topimg;?>">
        <img src="<?php echo G5_IMG_URL."/".$bottomimg;?>" alt="<?php echo $bottomimg;?>">
    </div>
</div>
<script>
    function fnApply(theme,cate_theme) {
        location.href='<?php echo G5_URL?>/page/mypage/mytheme_update?theme='+theme+"&cate="+cate_theme;
    }
</script>
