<?php
/**
 * User: leemited
 * Date: 2018-10-12
 * Time: 오후 5:12
 */
include_once ("./_common.php");

switch ($depth){
    case "0":
        $table = "cmap_depth2";
        $where = " depth1_id = {$id}";
        break;
    case "1":
        $table = "cmap_depth3";
        $where = " depth2_id = {$id}";
        break;
    case "2":
        $table = "cmap_depth4";
        $where = " depth3_id = {$id}";
        break;
}

$sql = "select * from {$table} where {$where} order by id asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $option[] = $row;
}
for($i=0;$i<count($option);$i++) {
    ?>
    <option value="<?php echo $option[$i]["id"];?>" <?php if($option[$i]["id"] == $depthid){?>selected<?php }?>><?php echo $option[$i]["depth_name"];?></option>
    <?php
}
?>
