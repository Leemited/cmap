<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$sql = "select * from `cmap_mainimage` order by id";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $list[] = $row;
}
?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>메인이미지 관리</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <table class="image_table">
                    <colgroup>
                        <col width="30%">
                        <col width="30%">
                        <col width="30%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <th>이미지</th>
                        <th>메인 텍스트</th>
                        <th>서브 텍스트</th>
                        <th>관리</th>
                    </tr>
                    <?php for($i=0;$i<count($list);$i++){?>
                    <tr>
                        <td>
                            <img src="<?php echo G5_DATA_URL."/file/main/".$list[$i]["main_image"];?>" alt="" style="width:100%;">
                        </td>
                        <td>
                            <?php echo nl2br($list[$i]["main_text"]);?>
                        </td>
                        <td><?php echo nl2br($list[$i]["sub_text"]);?></td>
                        <td>
                            <input type="button" value="수정" style="display:block;position: relative;right:auto;top:auto;margin:0 auto" onclick="location.href=g5_url+'/admin/main_image_write?id=<?php echo $list[$i]["id"];?>'">
                            <input type="button" value="삭제" style="display:block;position: relative;right:auto;top:auto;margin:0 auto">
                        </td>
                    </tr>
                    <?php }?>
                    <?php if(count($list)==0){?>
                        <tr>
                            <td colspan="4" style="text-align: center">등록 된 메인이미지가 없습니다.</td>
                        </tr>
                    <?php }?>
                </table>
                <div class="" style="text-align: right">
                    <input type="button" value="신규등록" class="admin_submit" onclick="location.href=g5_url+'/admin/main_image_write'">
                </div>                 
            </div>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/admin/admin.tail.php");

?>
