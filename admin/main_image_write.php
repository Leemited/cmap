<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

if($imgid){
    $view = sql_fetch("select * from `cmap_mainimage` where id = '{$imgid}'");
}
?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>메인이미지 등록/수정</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <form action="./main_image_update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="imgid" value="<?php echo $imgid;?>">
                <table class="edit_table">
                    <colgroup>
                        <col width="140px">
                        <col width="*">
                    </colgroup>
                    <tr>
                        <th>이미지</th>
                        <td>
                            <input type="text" id="filename" class="adm_input" style="width:50%" value="<?php echo $view["main_image"];?>">
                            <input type="file" name="main_image" id="main_image" onchange="$('#filename').val(this.value);" style="display:none;">
                            <label for="main_image" class="btn_del_menu btn_02" style="padding:5px 14px;font-size:15px;background-color:#000;color:#fff;font-weight:bold;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;">파일등록</label>
                        </td>
                    </tr>
                    <tr>
                        <th>메인 텍스트</th>
                        <td>
                            <textarea id="main_text" name="main_text" class="adm_input" style="width:100%;resize:none"><?php echo nl2br($view["main_text"]);?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>서브 텍스트</th>
                        <td>
                            <textarea id="sub_text" name="sub_text" class="adm_input" style="width:100%;resize:none"><?php echo nl2br($view["sub_text"]);?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>사용 유무</th>
                        <td>
                            <input type="radio" name="used" id="used" style="width:auto" value="1">  <label style="margin-right:10px;" for="time1">사용</label>
                            <input type="radio" name="used" id="notused" style="width:auto" value="0">  <label style="margin-right:10px;" for="time1">미사용</label>
                        </td>
                    </tr>
                </table>
                <div class="" style="text-align: right">
                    <input type="submit" value="<?php if($imgid){echo "수정";}else{ echo "등록";}?>" class="admin_submit" >
                    <input type="button" value="목록" class="admin_submit" onclick="location.href=g5_url+'/admin/main_image'">
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/admin/admin.tail.php");

?>
