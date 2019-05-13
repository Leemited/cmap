<?php
include_once ("../../common.php");
$sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
$co = sql_fetch($sql);

$str = conv_content($co['co_content'], $co['co_html'], $co['co_tag_filter_use']);
?>

<div class="modal_in" id="content">
    <div class="modal_title">
        <h2><i></i><?php echo $co["co_subject"];?></h2>
        <div class="close" onclick="fnCloseModal2()">
            <img src="<?php echo G5_IMG_URL;?>/close_icon.svg" alt="">
        </div>
    </div>

    <div id="modal_content">
        <?php echo $str; ?>
    </div>
</div>
