<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
if(!$fm_id){
    $fm_id = 1;
}
$fm_id = (int) $fm_id;

$sql = " select * from {$g5['faq_master_table']} where fm_id = '$fm_id' ";
$fm = sql_fetch($sql);

$sql_common = " from {$g5['faq_table']} where fm_id = '$fm_id' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * $sql_common order by fa_order , fa_id ";
$result = sql_query($sql);

?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>자주 묻는 질문</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <table class="">
                    <tr>
                        <th>번호</th>
                        <th>질문/답변</th>
                        <th>순서</th>
                        <th>수정</th>
                    </tr>
                    <?php
                    for ($i=0; $row=sql_fetch_array($result); $i++)
                    {
                        $row1 = sql_fetch(" select COUNT(*) as cnt from {$g5['faq_table']} where fm_id = '{$row['fm_id']}' ");
                        $cnt = $row1['cnt'];

                        $s_mod = icon("수정", "");
                        $s_del = icon("삭제", "");

                        $num = $i + 1;

                        $bg = 'bg'.($i%2);
                        ?>

                        <tr class="<?php echo $bg; ?>">
                            <td class="td_num"><?php echo $num; ?></td>
                            <td class="td_left">
                                질문 : <?php echo stripslashes($row['fa_subject']); ?> <br>답변 : <?php echo strip_tags($row["fa_content"]);?>
                            </td>
                            <td class="td_num"><?php echo $row['fa_order']; ?></td>
                            <td class="td_mng td_mng_m">
                                <a href="./faqform.php?fm_id=<?php echo $row['fm_id']; ?>&amp;fa_id=<?php echo $row['fa_id']; ?>" class="btn btn_03"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span>수정</a>
                                <a href="javascript:faqDelete('<?php echo $row['fm_id']; ?>','<?php echo $row['fa_id']; ?>');" class="btn btn_02"><span class="sound_only"><?php echo stripslashes($row['fa_subject']); ?> </span>삭제</a>
                            </td>
                        </tr>

                        <?php
                    }

                    if ($i == 0) {
                        echo '<tr><td colspan="4" class="empty_table">등록된 질문이 없습니다.</td></tr>';
                    }
                    ?>
                </table>
                <div class="faq_btns" style="text-align: right;margin-top:20px;">
                    <input type="button" value="자주 묻는 질문 추가" class="admin_submit" onclick="location.href=g5_url+'/admin/faqform?fm_id=<?php echo $fm_id;?>'">
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function faqDelete(fm_id,fa_id){
        if(confirm("해당 질문을 삭제 하시겠습니까?")) {
            location.href = g5_url + '/admin/faq_delete?fm_id=' + fm_id + '&fa_id=' + fa_id;
        }
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
