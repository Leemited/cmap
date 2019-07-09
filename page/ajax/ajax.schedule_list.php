<?php
include_once ("../../common.php");

if($const){
    $where = " and construct_id = '{$const}'";
}else{
    if($member["mb_level"]==5){
        $sql = "select * from `cmap_my_construct` where INSTR(manager_mb_id,'{$mb_id}') != 0 and status = 0 ";
    }else {
        $sql = "select * from `cmap_my_construct` where (mb_id = '{$mb_id}' or INSTR(members,'{$mb_id}') != 0) and status = 0 ";
    }
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $const_id[] = $row["id"];
    }
    if(count($const_id)>0) {
        $constid = implode(",", $const_id);
        $where = " and construct_id in ({$constid})";
    }
}

$sql = "select * from `cmap_myschedule` where schedule_date = '{$date}' and status != -1 {$where} order by id";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $list[] = $row;
}

if(count($list)==0){?>
    <li id="">일정이 없습니다.</li>
<?php }else{?>
    <?php for($i=0;$i<count($list);$i++){?>
        <li id="schedule_id_<?php echo $list[$i]["pk_id"];?>" title="<?php echo $list[$i]["schedule_name"];?>" alt="<?php echo $list[$i]["construct_id"];?>"><?php echo $list[$i]["schedule_name"];?></li>
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
