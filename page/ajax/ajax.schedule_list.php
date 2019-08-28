<?php
include_once ("../../common.php");

if($const){
    $where = " and construct_id = '{$const}'";
    if(strpos($const,",")!==false){
        $where = " and construct_id in ({$const})";
    }
}else{
    //if($current_const["const_id"]){
    //    $where = " and construct_id = '{$current_const["const_id"]}'";
    //}else {
        if ($member["mb_level"] == 5) {
            $sql = "select * from `cmap_my_construct` where INSTR(manager_mb_id,'{$mb_id}') != 0 and status = 0 ";
        } else {
            $sql = "select * from `cmap_my_construct` where (mb_id = '{$mb_id}' or INSTR(members,'{$mb_id}') != 0) and status = 0 ";
        }

        $res = sql_query($sql);
        while ($row = sql_fetch_array($res)) {
            $const_id[] = $row["id"];
        }
        if (count($const_id) > 0) {
            $constid = implode(",", $const_id);
            $where = " and construct_id in ({$constid})";
        }
    //}
}


$sql = "select pk_id,construct_id,schedule_date,schedule_name from `cmap_myschedule` where schedule_date = '{$date}' and status != -1 {$where} order by id";
$res = sql_query($sql);
$a = 0;
while($row = sql_fetch_array($res)){
    $list[$a] = $row;
    //지연상태
    $list[$a]["active"] = 0;
    $map_pk_id = $map_pk_actives = $map_pk_actives_date = "";

    if($member["mb_level"]==5){
        $sql = "select sen_mb_id from `cmap_my_pmmode_set` where mb_id='{$member["mb_id"]}' and const_id = '{$row["construct_id"]}'";
        $ss = sql_fetch($sql);
        if($ss!=null){
            $activesql = "select pk_ids,pk_actives,pk_actives_date from `cmap_my_construct_map` where mb_id ='{$ss["set_mb_id"]}' and const_id = '{$row["construct_id"]}'";
        }else{
            $sql = "select mb_id from `cmap_my_construct` where id = '{$row["construct_id"]}'";
            $ss2 = sql_fetch($sql);
            $activesql = "select pk_ids,pk_actives,pk_actives_date from `cmap_my_construct_map` where mb_id ='{$ss2["mb_id"]}' and const_id = '{$row["construct_id"]}'";
        }
    }else {
        $activesql = "select pk_ids,pk_actives,pk_actives_date from `cmap_my_construct_map` where mb_id ='{$member["mb_id"]}' and const_id = '{$row["construct_id"]}'";
    }
    $activechk = sql_fetch($activesql);
    $map_pk_id = explode("``",$activechk["pk_ids"]);
    $map_pk_actives = explode("``",$activechk["pk_actives"]);
    $map_pk_actives_date = explode("``",$activechk["pk_actives_date"]);

    if($row["pk_id"]) {
        $pk_ids = explode("``", $row["pk_id"]);
        for ($i = 0; $i < count($pk_ids); $i++) {
            for ($j = 0; $j < count($map_pk_id); $j++) {
                if ($pk_ids[$i] == $map_pk_id[$j]) {
                    $list[$a]["active_date"] = $map_pk_actives_date[$j];
                    if ($map_pk_actives[$j] == 0) {
                        //지연이 하나라도 있으면 지연
                        $list[$a]["active"] = 1;
                        $list[$a]["active_date"] = "0000-00-00";
                        continue;
                    }
                }
            }
        }
    }else{
        $list[$a]["active"] = 2;
    }

    $a++;
}

if(count($list)==0){?>
    <li id="">일정이 없습니다.</li>
<?php }else{?>
    <?php for($i=0;$i<count($list);$i++){
        $class = "";
        if($list[$i]["active"]==1){//지연
            if($list[$i]["schedule_date"] < date("Y-m-d") && $list[$i]["active_date"]=="0000-00-00"){
                $class = "delays";
            }
        }else if($list[$i]["active"]==0){//지연아님
            if($list[$i]["schedule_date"] < date("Y-m-d") && strtotime($list[$i]["active_date"]) > strtotime($list[$i]["schedule_date"])){
                $class = "delay_confirm";
            }else if($list[$i]["schedule_date"] < date("Y-m-d") && strtotime($list[$i]["active_date"]) <= strtotime($list[$i]["schedule_date"])){
                $class = "confirm";
            }else if($list[$i]["schedule_date"] >= date("Y-m-d")){
                $class = "confirm";
            }
        }else if($list[$i]["active"]==2){//대상아님
            $class = "confirm";
        }
        ?>
        <li id="schedule_id_<?php echo $list[$i]["pk_id"];?>" class="<?php echo $class;?>" title="<?php echo $list[$i]["schedule_name"];?>" alt="<?php echo $list[$i]["construct_id"];?>"><?php echo $list[$i]["schedule_name"];?></li>
    <?php } ?>
<?php } ?>

<script>
    $("li[id^=schedule_id_]").each(function(){
        $(this).click(function(){
            var consts = $(this).attr("alt");
            $.ajax({
                url:g5_url+'/page/ajax/ajax.current_construct_update.php',
                method:"post",
                data:{const_id:consts}
            });
            if($(this).hasClass("active")){
                $(this).removeClass("active");
                $("#del_id").val('');
                $("#edit_id").val('');
                $(".edit_con").hide();
            }else {
                $(this).addClass("active");
                $("li[id^=schedule_id_]").not($(this)).removeClass("active");
                var id = $(this).attr("id");
                var title = $(this).html();
                var ids = id.replace("schedule_id_", "");
                $("#del_id").val(ids);
                $.ajax({
                    url: g5_url + "/page/ajax/ajax.get_schedule_content.php",
                    method: "post",
                    data: {pk_id: ids,const:consts}
                }).done(function (data) {
                    $(".detail_list").show();
                    $(".detail_list .lists").html('');
                    $(".detail_list .lists").append(data);
                    /*$(".edit_con").show();*/
                    $("#edit_id").val(ids);
                    $(".title span").html(title);
                    $(".title span").attr("title",title);
                    /*$("#schedule_con").html(data);*/
                });
            }
        });
    });
</script>
