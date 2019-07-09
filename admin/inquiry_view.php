<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
include_once(G5_EDITOR_LIB);
if(!$id){
    alert("조회할 데이터가 없습니다.");
}

$sql = "select * from `cmap_inquiry` where id = '{$id}'";
$view = sql_fetch($sql);

if($view["filename"]){
    $attchfile = "<a href='".G5_DATA_URL."/inquiry/".$view["filename"]."'>".$view["ori_filename"]."</a>";
}else{
    $attchfile="";
}

$date = explode(" ",$view["insert_date"]);
if($date == date("Y-m-d")){
    $dates = $date[1];
}else{
    $dates = $date[0];
}

?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>제안보기</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content menu_list">
                <table class="image_table">
                    <colgroup>
                        <col width="10%">
                        <col width="40%">
                        <col width="10%">
                        <col width="40%">
                    </colgroup>
                    <tr>
                        <th>등록타입</th>
                        <td><?php echo $view["inquiry_type"];?></td>
                        <th>등록일</th>
                        <td><?php echo $dates;?></td>
                    </tr>
                    <tr>
                        <th>등록자</th>
                        <td><?php echo $view["name"];?></td>
                        <th>이메일</th>
                        <td><?php echo $view["email"];?></td>
                    </tr>
                    <tr>
                        <th>첨부파일</th>
                        <td colspan="3">
                            <?php if($attchfile){
                                echo $attchfile;
                            }else{?>
                                등록 파일 없음
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <th>내용</th>
                        <td colspan="3">
                            <?php echo nl2br($view["content"]);?>
                        </td>
                    </tr>
                    <?php if($view["recomment_title"]){?>
                        <tr>
                            <th>답변제목</th>
                            <td colspan="3">
                                <?php echo $view["recomment_title"];?>
                            </td>
                        </tr>
                        <tr>
                            <th>내용</th>
                            <td colspan="3">
                                <?php echo nl2br($view["recomment_content"]);?>
                            </td>
                        </tr>
                    <?php }?>
                </table>
                <h2 style="margin-top:40px;margin-bottom:20px;font-size:20px;">답변하기</h2>
                <form action="<?php echo G5_URL;?>/admin/inquiry_recomment" name="submit_form" method="post" onsubmit="return fnSunbmit(this)">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <input type="hidden" name="send_email" value="<?php echo $view["email"];?>">
                <table class="recomment_table image_table">
                    <colgroup>
                        <col width="10%">
                        <col width="90%">
                    </colgroup>
                    <tr>
                        <th>답변제목</th>
                        <td>
                            <input type="text" name="recomment_title" id="recomment_title" class="" required>
                        </td>
                    </tr>
                    <tr>
                        <th>답변내용</th>
                        <td>
                            <?php echo editor_html('recomment_content', ''); ?>
                        </td>
                    </tr>
                </table>
                <div style="text-align: right;margin-top:40px;">
                    <input type="button" value="목록" onclick="location.href=g5_url+'/admin/inquiry?page=<?php echo $page;?>'">
                    <input type="submit" value="이메일답변" class="admin_submit">
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
    function fnSunbmit(t){
        if(confirm("이메일로 답변을 보내시겠습니까?")) {
            if (t.recomment_title.value == "") {
                alert("답변 제목을 입력해 주세요.");
                t.recomment_title.focus();
                return false;
            }
            <?php echo get_editor_js('recomment_content'); ?>
            return true;
        }else{
            return false;
        }
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
