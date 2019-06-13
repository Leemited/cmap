<?php
include_once ("../../common.php");
$sql = "select * from `mylocation_save_log` where mb_id = '{$mb_id}' and const_id = '{$constid}' order by save_date desc, save_time desc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}

?>
<div class="modal_in" id="find_id">
    <div class="modal_title">
        <h2><i></i> 복구하기</h2>
        <div class="close" onclick="fnCloseModal()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="닫기">
        </div>
    </div>
    <div class="confirm_con">
        <div>
            <ul class="member_list" style="max-height:40vh;overflow-y: auto">
                <?php for($i=0;$i<count($list);$i++){?>
                    <li><input type="radio" name="save_id[]" id="save_id_<?php echo $list[$i]["id"];?>" value="<?php echo $list[$i]["id"];?>"><label for="save_id_<?php echo $list[$i]["id"];?>"></label><div><?php echo $list[$i]["save_date"];?> <?php echo $list[$i]["save_time"];?> 저장됨</div><div class="basic_btn02" style="width:auto;" onclick="fnDelReStore('<?php echo $list[$i]["id"];?>')">삭제하기</div></li>
                <?php }
                if(count($list)==0){?>
                    <li class="empty_li">저장된 데이터가 없습니다.</li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="modal_btns">
        <input type="button" class="modal_btn02 width30" value="취소" onclick="fnCloseModal()">
        <?php if(count($list)>0){?>
        <input type="button" class="modal_btn01 width30" value="복구하기" onclick="fnReStores()">
        <?php }?>
    </div>
</div>
<script>
    function fnDelReStore(id){
        $.ajax({
            url:g5_url+'/page/mylocation/ajax.my_set_delete.php',
            method:"post",
            data:{id:id}
        }).done(function(data){
            if(data==1){
                alert("삭제할 데이터를 선택해 주세요.");
            }else if(data==3){
                alert("삭제가 실패 되었습니다.")
            }else {
                $("#save_id_" + id).parent().remove();
            }
        });
    }
    function fnReStores(){
        var chkid = $("input[id^=save_id]:checked").val();
        if(chkid==""){
            alert("복구할 데이터를 선택해 주세요.");
        }
        if(confirm("지연현황 및 평가데이터를 복구 합니다.\r\n.계속 진행하시겠습니까?")) {
            location.href = g5_url + '/page/mylocation/mylocation_restore?id=' + chkid
        }
    }
</script>