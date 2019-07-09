<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
include_once(G5_EDITOR_LIB);
if($fa_id){
    $sql = "select * from `g5_faq` where fm_id = '{$fm_id}' and fa_id='{$fa_id}'";
    $fa = sql_fetch($sql);
}
?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>자주 묻는 질문 등록</h2>
        </div>
        <div class="admin_content ">
            <div class="edit_content menu_list">
                <form action="<?php echo G5_URL;?>/admin/faq_update" onsubmit="return fnSubmit(this)">
                    <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
                    <input type="hidden" name="fa_id" value="<?php echo $fa_id; ?>">
                    <table class="faq_table">
                        <tr>
                            <th>순서</th>
                            <td>
                                <input type="text" name="fa_order" id="fa_order" required class="width20" style="width:200px;" onkeyup="number_only(this)" value="<?php echo $fa["fa_order"];?>">
                                <p>* 숫자가 작을수록 우선 정렬</p>
                            </td>
                        </tr>
                        <tr>
                            <th>질문</th>
                            <td>
                                <input type="text" value="<?php echo strip_tags($fa["fa_subject"]);?>" class="" name="fa_subject" id="fa_subject">
                            </td>
                        </tr>
                        <tr>
                            <th>답변</th>
                            <td>
                                <?php echo editor_html('fa_content', get_text($fa['fa_content'], 0)); ?>
                            </td>
                        </tr>
                    </table>
                    <div class="faq_btns" style="text-align: right;margin-top:20px;">
                        <input type="button" value="뒤로가기" onclick="location.href=g5_url+'/admin/faq?fm_id=<?php echo $fm_id;?>'">
                        <input type="submit" value="등록하기" class="admin_submit">
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
    function fnSubmit(f){

        if(f.fa_subject.value==""){
            alert("질문을 입력해 주세요.");
            f.fa_subject.focus();
            return false;
        }

        <?php echo get_editor_js('fa_content'); ?>
        return true;

    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
