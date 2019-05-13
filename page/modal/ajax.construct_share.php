<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> SNS로 기술자 초대하기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div>
        <img src="" alt="">
        <img src="" alt="" class="fb-share-button" data-href="<?php echo G5_URL;?>">
        <img src="" alt="">
    </div>
</div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
