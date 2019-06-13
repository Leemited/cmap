<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 포털 검색</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>
    <div class="modal_content">
        <ul class="search">
            <li><img src="<?php echo G5_IMG_URL;?>/search_naver.png" alt="" onclick="fnSearch('naver','<?php echo $search_text;?>')"><p>네이버</p></li>
            <li><img src="<?php echo G5_IMG_URL;?>/search_daum.png" alt="" onclick="fnSearch('daum','<?php echo $search_text;?>')"><p>다음</p></li>
            <li><img src="<?php echo G5_IMG_URL;?>/search_google.png" alt="" onclick="fnSearch('google','<?php echo $search_text;?>')"><p>구글</p></li>
        </ul>
    </div>
<!--    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="fnCloseModal()">
    </div>-->
</div>
<script>
    function fnSearch(portal,search_text){
        if(portal=="naver"){
            window.open('https://search.naver.com/search.naver?sm=top_hty&fbm=0&ie=utf8&query='+search_text,'_blank');
        }else if(portal=="daum"){
            window.open('https://search.daum.net/search?w=tot&DA=YZR&t__nil_searchbox=btn&sug=&sugo=&q='+search_text,'_blank');
        }else if(portal=="google"){
            window.open('https://www.google.com/search?ei=IUjnXO3wJsSVr7wPjo6NiAU&q='+search_text+'&oq='+search_text,'_blank');
        }
    }
</script>
