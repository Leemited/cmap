<?php
include_once ("./common.php");
include_once (G5_PATH."/_head.php");
$sql = "select addr1 from `weather_location` GROUP by addr1 order by id asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
    $weather[] = $row;
}
?>
도/시 : <select name="addr1" id="addr1" onchange="fnAddr2(this.value)">
    <option value="">도/시 선택</option>
    <?php for($i=0;$i<count($weather);$i++){?>
        <option value="<?php echo $weather[$i]["addr1"];?>"><?php echo $weather[$i]["addr1"];?></option>
    <?php }?>
</select>

구/군 : <select name="addr2" id="addr2" onchange="fnAddr3(this.value)">
    <option value="">도/시를 선택해주세요</option>
</select>

동/면/읍 : <select name="addr3" id="addr3" onchange="latlng(this.value)">
    <option value="">구/군을 선택해주세요</option>
</select>
<script>
function fnAddr2(addr1){
    $.ajax({
        url:"./weathersel.php",
        method:"post",
        data:{addr1:addr1}
    }).done(function (data) {
        console.log(data);
        $("#addr2").html(data);
    })
}
function fnAddr3(addr2){
    $.ajax({
        url:"./weathersel.php",
        method:"post",
        data:{addr2:addr2}
    }).done(function (data) {
        console.log(data);
        $("#addr3").html(data);
    })
}
function latlng(addr3){
    $.ajax({
        url:"./weathersel.php",
        method:"post",
        data:{addr3:addr3},
        dataType:"json"
    }).done(function (data) {
        console.log(data)
    })
}
</script>
<?php
include_once (G5_PATH."/_tail.php");