<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$sql = "select * from `cmap_mainimage` order by id";
$res = sql_query($sql);
while($row=sql_fetch_array($res)){
    $list[] = $row;
}

$sql = "select * from `mainslide_time` where id = 1";
$mainslide_time = sql_fetch($sql);
?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>메인이미지 관리</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <form action="<?php echo G5_URL;?>/admin/main_image_time" method="post">
                <table class="image_table">
                    <colgroup>
                        <col width="20%">
                        <col width="*">
                        <col width="20%">
                    </colgroup>
                    <tr>
                        <th>이미지 회전 시간</th>
                        <td class="slide_td">
                            <input type="radio" name="slide_time" id="time1" style="width:auto" <?php if($mainslide_time["slide_time"]==3){echo "checked";}?> value="3">  <label style="margin-right:10px;" for="time1">3초</label>
                            <input type="radio" name="slide_time" id="time2" style="width:auto" <?php if($mainslide_time["slide_time"]==5){echo "checked";}?> value="5">  <label style="margin-right:10px;" for="time2">5초</label>
                            <input type="radio" name="slide_time" id="time3" style="width:auto" <?php if($mainslide_time["slide_time"]==8){echo "checked";}?> value="8">  <label style="margin-right:10px;" for="time3">8초</label>
                            <input type="radio" name="slide_time" id="time4" style="width:auto" <?php if($mainslide_time["slide_time"]==10){echo "checked";}?> value="10"> <label style="margin-right:10px;" for="time4">10초</label>
                            <input type="radio" name="slide_time" id="time5" style="width:auto" <?php if($mainslide_time["slide_time"]==12){echo "checked";}?> value="12"> <label style="margin-right:10px;" for="time5">12초</label>
                            <input type="radio" name="slide_time" id="time6" style="width:auto" <?php if($mainslide_time["slide_time"]==15){echo "checked";}?> value="15"> <label style="margin-right:10px;" for="time5">12초</label>
                            <input type="radio" name="slide_time" id="time7" style="width:auto" <?php if($mainslide_time["slide_time"]==20){echo "checked";}?> value="20"> <label style="margin-right:10px;" for="time5">12초</label>
                        </td>
                        <td><input type="submit" value="저장" style="display:block;position: relative;right:auto;top:auto;margin:0 auto" onclick="location.href=g5_url+'/admin/main_image_write?imgid=<?php echo $list[$i]["id"];?>'"></td>
                    </tr>
                </table>
                </form>
                <table class="image_table">
                    <colgroup>
                        <col width="30%">
                        <col width="30%">
                        <col width="30%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <th>이미지</th>
                        <th>메인 텍스트</th>
                        <th>서브 텍스트</th>
                        <th>사용유무</th>
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
                        <td><?php echo ($list[$i]["used"]==0)?"미사용":"사용";?></td>
                        <td>
                            <input type="button" value="수정" style="display:block;position: relative;right:auto;top:auto;margin:0 auto" onclick="location.href=g5_url+'/admin/main_image_write?imgid=<?php echo $list[$i]["id"];?>'">
                            <input type="button" value="삭제" style="display:block;position: relative;right:auto;top:auto;margin:0 auto" onclick="location.href=g5_url+'/admin/main_image_update?type=del&imgid=<?php echo $list[$i]["id"];?>'">
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
