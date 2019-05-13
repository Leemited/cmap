<?php
include_once ("../../common.php");

?>
<div class="modal_in" id="modal_invite">
    <div class="modal_title">
        <h2><i></i> 현장초대</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="닫기">
        </div>
    </div>
    <div class="search">
        <select name="sch_type" id="sch_type" class="basic_input01 width20">
            <option value="mb_name">이름</option>
            <option value="mb_id">아이디</option>
            <option value="mb_hp">연락처</option>
        </select><input type="text" id="search" class="basic_input01 width60" placeholder="검색어 입력"><input type="button" value="검색" class="basic_btn01 width20" onclick="fnMemberSearch()">
    </div>
    <div class="modal_content">
        <div>
            <ul class="member_list">
                <li class="no-list">검색이 필요합니다.</li>
            </ul>
        </div>
    </div>
</div>
<script>
    function fnMemberSearch(){
        var id = "<?php echo $id;?>";
        var sch_type = $("#sch_type").val();
        var search = $("#search").val();
        $.ajax({
            url:g5_url+"/page/ajax/ajax.member_search.php",
            method:"post",
            data:{sch_type:sch_type,search:search,id:id}
        }).done(function(data){
            $(".member_list").html(data);
        });
    }
</script>
