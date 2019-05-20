<?php
include_once ("../../common.php");

$sql = "update `cmap_my_construct_temp` set status = 1 where id = '{$constid}'";
echo $sql;
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}
?>