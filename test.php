<?php
include_once ("_common.php");

$time = date("i");
$base_time = date("H");
$today = date("Ymd");
$today2 = date("Y-m-d");
if($time > 30){
    $base_time = date("H", strtotime("+ 1 hour"));
    echo $base_time."<br>";
    if($base_time < 0){
        $today = date("Ymd",strtotime("- 1 day"));
        $base_time = "23";
    }
}

$base_time = $base_time."00";

$sql = "select cmap_construct_lat,cmap_construct_lng,weather_addr1,weather_addr2,weather_addr3,id from `cmap_my_construct` where status = 0 and cmap_construct_lat != '' and cmap_construct_lng != ''";
$res = sql_query($sql);
for($i=0;$row = sql_fetch_array($res);$i++) {

    if($row["weather_addr1"]){
        $where = " and addr1 = '{$row["weather_addr1"]}' ";
        $setaddr = " , addr1 = '{$row["weather_addr1"]}'";
    }
    if($row["weather_addr2"]){
        $where .= " and addr2 = '{$row["weather_addr2"]}'";
        $setaddr .= " , addr2 = '{$row["weather_addr2"]}'";
    }
    if($row["weather_addr3"]){
        $where .= " and addr3 = '{$row["weather_addr3"]}'";
        $setaddr .= " , addr3 = '{$row["weather_addr3"]}'";
    }

    $url = "http://newsky2.kma.go.kr/service/SecndSrtpdFrcstInfoService2/ForecastSpaceData?ServiceKey=n1t%2B4j2iWa7OlDB0dGxtEk0TRjTN%2Fs9XVV%2FoUgexCxN5i%2BPQA%2BbkmslYrOWgK82GK28prPQB4rfMA4vQZlALXA%3D%3D&base_date=" . $today . "&base_time=".$base_time."&nx=" . $row["cmap_construct_lat"] . "&ny=" . $row["cmap_construct_lng"];

    echo $url."<br><br>";

    $set = getData($url,$setaddr,$row,$today);

    $to = date("Y-m-d");

    $sql2 = "select count(*) as cnt from `weather` where insert_date = '{$today2}' and lat = '{$row["cmap_construct_lat"]}' and lng = '{$row["cmap_construct_lng"]}' and const_id = '{$row["id"]}' {$where}";
    $weather = sql_fetch($sql2);
    if($weather["cnt"]==0){
        $sql3 = "insert into `weather` set {$set} , insert_date = now() , update_date = now(), insert_time = now() , const_id = '{$row["id"]}'";
    }else{
        $sql3 = "update `weather` set {$set}, update_date = now() where id='{$weather["id"]}'";
    }
    sql_query($sql3);
}

function getData($url,$setaddr,$row,$today){
    $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

    curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
    curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
    curl_setopt($ch, CURLOPT_HEADER, 0);//헤더 정보를 보내도록 함(*필수)
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
    $res = curl_exec ($ch);
    curl_close($ch);

    $object = simplexml_load_string($res);
    $set = " lat= '{$row["cmap_construct_lat"]}' , lng = '{$row["cmap_construct_lng"]}' {$setaddr}";
    foreach ($object->body->items->item as $obj){
        if($today==$obj->fcstDate) {
            switch ($obj->category) {
                case "POP": //강수확률
                    $set .= " , POP = '{$obj->fcstValue}'";
                    break;
                case "PTY": //강수형태
                    $set .= " , PTY = '{$obj->fcstValue}'";
                    break;
                case "R06"://6시간 강수량
                    $set .= " , R06 = '{$obj->fcstValue}'";
                    break;
                case "REH"://습도
                    $set .= " , REH = '{$obj->fcstValue}'";
                    break;
                case "S06"://6시간 신적설
                    $set .= " , S06 = '{$obj->fcstValue}'";
                    break;
                case "SKY": //하늘상태
                    $set .= " , SKY = '{$obj->fcstValue}'";
                    break;
                case "T3H": //3시간 기온
                    $set .= " , T3H = '{$obj->fcstValue}'";
                    break;
                case "TMN": //아침 최저기온
                    $set .= " , TMN = '{$obj->fcstValue}'";
                    break;
                case "TMX": //낮 최고기온
                    $set .= " , TMX = '{$obj->fcstValue}'";
                    break;
                case "UUU": //풍속(동서성분)
                    $set .= " , UUU = '{$obj->fcstValue}'";
                    break;
                case "VVV": //풍속(남북성분)
                    $set .= " , VVV = '{$obj->fcstValue}'";
                    break;
                case "WAV": //파고
                    $set .= " , WAV = '{$obj->fcstValue}'";
                    break;
                case "VEC": //풍향
                    $set .= " , VEC = '{$obj->fcstValue}'";
                    break;
                case "WSD": //풍속
                    $set .= " , WSD = '{$obj->fcstValue}'";
                    break;
            }
        }
    }
    return $set;
}
?>