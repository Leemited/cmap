<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
@ini_set('memory_limit', '-1');
$menu_name = "공휴일 관리";

if(!$year){
    $year = date("Y");
}

$sql = "select * from `cmap_holidays` order by `year` asc";
$res = sql_query($sql);
while ($row = sql_fetch_array($res)) {
    $getYear[$row["year"]] = $row;
}


?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2><?php echo $menu_name;?></h2>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <div class="sub_title">
                공휴일 입력
            </div>
            <div class="edit_content">
                <table class="edit_table">
                    <tr>
                        <th style="width:300px;">연도</th>
                        <th>공휴일</th>
                        <th>관리</th>
                    </tr>
                    <?php for ($i=0;$i<7;$i++){ $timeStemp = " + ". $i . " year";
                        $in_year = date("Y", strtotime($timeStemp));
                        if($getYear[$in_year]["holidays2"]!="") {
                            $holidays2 = explode("~",$getYear[$in_year]["holidays2"]);
                        }
                        if($getYear[$in_year]["holidays8"]!="") {
                            $holidays8 = explode("~",$getYear[$in_year]["holidays8"]);
                        }

                        ?>
                    <form action="<?php echo G5_URL;?>/admin/holidays_update" name="holiday_form_<?php echo $i;?>" method="post">
                    <tr>
                        <td>
                            <input type="text" name="year" style="border:1px solid #ddd;background-color:#fff;" value="<?php echo $in_year;?>">
                        </td>
                        <td>
                            <ul class="holiday_li">
                                <li><label for="date1">신정(새해)</label> <input type="text" name="holidays1" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date1" value="<?php echo ($getYear[$in_year]["holidays1"])?$getYear[$in_year]["holidays1"]:$in_year."-01-01";?>"></li>
                                <li><label for="date2_1">구정(설날)</label> <input type="text" name="holidays2[]" style="border:1px solid #ddd;background-color:#fff;width:30%" id="date2_1" value="<?php echo ($holidays2[0])?$holidays2[0]:'';?>"> ~  <input type="text" name="holidays2[]" style="border:1px solid #ddd;background-color:#fff;width:30%" id="date2_2" value="<?php echo ($holidays2[1])?$holidays2[1]:'';?>"></li>
                                <li><label for="date3">삼일절</label> <input type="text" name="holidays3" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date3" value="<?php echo ($getYear[$in_year]["holidays3"])?$getYear[$in_year]["holidays3"]:$in_year."-03-01";?>"></li>
                                <li><label for="date4">부처님오신날</label> <input type="text" name="holidays4" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date4" value="<?php echo ($getYear[$in_year]["holidays4"])?$getYear[$in_year]["holidays4"]:$in_year."-01-01";?>"></li>
                                <li><label for="date5">어린이날</label> <input type="text" name="holidays5" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date5" value="<?php echo ($getYear[$in_year]["holidays5"])?$getYear[$in_year]["holidays5"]:$in_year."-05-05";?>"></li>
                                <li><label for="date6">현충일</label> <input type="text" name="holidays6" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date6" value="<?php echo ($getYear[$in_year]["holidays6"])?$getYear[$in_year]["holidays6"]:$in_year."-06-06";?>"></li>
                                <li><label for="date7">광복절</label> <input type="text" name="holidays7" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date7" value="<?php echo ($getYear[$in_year]["holidays7"])?$getYear[$in_year]["holidays7"]:$in_year."-08-15";?>"></li>
                                <li><label for="date8_1">추석</label> <input type="text" name="holidays8[]" style="border:1px solid #ddd;background-color:#fff;width:30%" id="date8_1" value="<?php echo ($holidays8[0])?$holidays8[0]:'';?>"> ~ <input type="text" name="holidays8[]" style="border:1px solid #ddd;background-color:#fff;width:30%" id="date8_2" value="<?php echo ($holidays8[1])?$holidays8[1]:'';?>"></li>
                                <li><label for="date9">개천절</label> <input type="text" name="holidays9" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date9" value="<?php echo ($getYear[$in_year]["holidays9"])?$getYear[$in_year]["holidays9"]:$in_year."-10-03";?>"></li>
                                <li><label for="date10">한글날</label> <input type="text" name="holidays10" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date10" value="<?php echo ($getYear[$in_year]["holidays10"])?$getYear[$in_year]["holidays10"]:$in_year."-10-09";?>"></li>
                                <li><label for="date11">성탄절</label> <input type="text" name="holidays11" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date11" value="<?php echo ($getYear[$in_year]["holidays11"])?$getYear[$in_year]["holidays11"]:$in_year."-12-25";?>"></li>
                                <li><label for="date12">기타공휴일</label> <input type="text" name="holidays12" style="border:1px solid #ddd;background-color:#fff;width:60%" id="date12" placeholder="'|'로 구분해주세요" value="<?php echo ($getYear[$in_year]["holidays12"])?$getYear[$in_year]["holidays12"]:'';?>"></li>
                            </ul>
                        </td>
                        <td >
                            <input type="submit" value="수정" style="border:none;background-color:#000;color:#fff;">
                        </td>
                        </form>
                    </tr>
                    <?php
                        unset($holidays2);
                        unset($holidays8);
                    }
                    ?>
                </table>
            </div>
        </div>
    </section>
</div>
<div class="debug"></div>
<script>
    function fnGuideUp(pk_id,idx){
        var content = $("#depth_desc_"+idx).val();
        location.href=g5_url+"/"
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
