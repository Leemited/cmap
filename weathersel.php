<?php
include_once ("./common.php");

if($addr1) {
    $sql = "select addr2 from `weather_location` where addr1 = '{$addr1}' group by addr2 order by id" ;
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        if($row["addr2"]=="") continue;
    ?>
        <option value="<?php echo $row["addr2"];?>"><?php echo $row["addr2"];?></option>
    <?php }
}
if($addr2) {
    $sql = "select addr3 from `weather_location` where addr2 = '{$addr2}' group by addr3 order by id" ;
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        if($row["addr3"]=="") continue;
        ?>
        <option value="<?php echo $row["addr3"];?>"><?php echo $row["addr3"];?></option>
    <?php }
}
if($addr3){
    $sql = "select * from `weather_location` where addr3 = '{$addr3}' " ;
    $location = sql_fetch($sql);
    $result["lat"] = $location["lat"];
    $result["lng"] = $location["lng"];
    $result["latlng"] = $location["latlng"];
    echo json_encode($result);
}
