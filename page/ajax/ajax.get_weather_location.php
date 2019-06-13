<?php
include_once ("../../common.php");

$addr1 = str_replace(" ", "", $addr1);
$addr2 = str_replace(" ", "", $addr2);
$addr3 = str_replace(" ", "", $addr3);

if($cmap_id){
    $sql = "select * from `cmap_my_construct` where id = '{$cmap_id}'";
    $loc = sql_fetch($sql);
    $addr1 = $loc["weather_addr1"];
    $addr2 = $loc["weather_addr2"];
    $addr3 = $loc["weather_addr3"];
}

$sql = "select count(*) as cnt from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}') and addr2 = '{$addr2}' and addr3 = '{$addr3}' ";

$step1 = sql_fetch($sql);
if($step1["cnt"]==0){
    $sql = "select count(*) as cnt from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}') and addr2 = '{$addr2}' ";
    $step2 = sql_fetch($sql);
    if($step2["cnt"]==0){
        $sql = "select count(*) as cnt from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}') ";
        $step3 = sql_fetch($sql);
        if($step3["cnt"]==0){
            $result["status"] = 1;
        }else{
            $sql = "select lat,lng from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}')  ";
            $result["status"] = 0;
            $result = sql_fetch($sql);
            $where = " and (addr1 = '{$addr1}' or addr1_short = '{$addr1}') and addr2 = '' and addr3 = ''";
        }
    }else{
        $sql = "select lat,lng from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}') and addr2 = '{$addr2}'";
        $result["status"] = 0;
        $result = sql_fetch($sql);
        $where = " and (addr1 = '{$addr1}' or addr1_short = '{$addr1}')  and addr2 = '{$addr2}' and addr3 = ''";
    }
}else{
    $sql = "select lat,lng from weather_location where (addr1 = '{$addr1}' or addr1_short = '{$addr1}')  and addr2 = '{$addr2}' and addr3 = '{$addr3}' ";
    $result["status"] = 0;
    $result = sql_fetch($sql);
    $where = " and (addr1 = '{$addr1}' or addr1_short = '{$addr1}') and addr2 = '{$addr2}' and addr3 = '{$addr3}'";
}

if($result["status"] != 1){
    $today = date("Ymd");
    $today2 = date("Y-m-d");

    $totime = date("H");
    $totime2 = date("H",strtotime("-1 hour"));

    $base_date = $today;

    $base_time = date("H");
    $time = date("i");

    if($base_time == 0){
        $base_date = date("Ymd",strtotime("- 1 day"));
    }

    if($time < 30){
        $base_time = date("H",strtotime("- 1 hour"));
        if($base_time < 0){
            $base_time = "23";
        }
    }

    $base_time = $base_time."00";



    //저장된 데이터있으면 불러오기
    $sql = "select *,count(id) as cnt from `weather` where insert_date = '{$today2}' and lat = '{$result['lat']}' and lng = '{$result['lng']}' {$where} limit 0 , 1";
    $weather = sql_fetch($res);
    if($weather["cnt"] == 0) {
        //없는경우 임시 데이터로 불러오기

        //오늘 최고 최저 기온
        $url = "http://newsky2.kma.go.kr/service/SecndSrtpdFrcstInfoService2/ForecastSpaceData?ServiceKey=n1t%2B4j2iWa7OlDB0dGxtEk0TRjTN%2Fs9XVV%2FoUgexCxN5i%2BPQA%2BbkmslYrOWgK82GK28prPQB4rfMA4vQZlALXA%3D%3D&numOfRows=50&base_date=" . $base_date . "&base_time=0200&nx=" . $result["lat"] . "&ny=" . $result["lng"];
        $result["url1"]=$url;
        $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

        curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
        curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
        curl_setopt($ch, CURLOPT_HEADER, 0);//헤더 정보를 보내도록 함(*필수)
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
        $res = curl_exec ($ch);
        curl_close($ch);
        $object = simplexml_load_string($res);
        $i=0;
        foreach ($object->body->items->item as $obj) {
            if ($today == $obj->fcstDate) {
                if($obj->category=="TMN") $result["tmn"] = $obj->fcstValue;
                if($obj->category=="TMX") $result["tmx"] = $obj->fcstValue;
                //if($obj->category=="T1H") $result["t1h"] = $obj->fcstValue;
            }
        }

        //현재 정보
        $url = "http://newsky2.kma.go.kr/service/SecndSrtpdFrcstInfoService2/ForecastTimeData?ServiceKey=n1t%2B4j2iWa7OlDB0dGxtEk0TRjTN%2Fs9XVV%2FoUgexCxN5i%2BPQA%2BbkmslYrOWgK82GK28prPQB4rfMA4vQZlALXA%3D%3D&numOfRows=50&base_date=" . $base_date . "&base_time=".$base_time."&nx=" . $result["lat"] . "&ny=" . $result["lng"];
        $result["url2"]=$url;

        $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

        curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
        curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
        curl_setopt($ch, CURLOPT_HEADER, 0);//헤더 정보를 보내도록 함(*필수)
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)
        $res = curl_exec ($ch);
        curl_close($ch);

        $object = simplexml_load_string($res);
        $result["obj"] = $object;
        $i=0;
        $result["temp"] = 0;
        foreach ($object->body->items->item as $obj) {
            if ($today == $obj->fcstDate) {
                if ($obj->category == "T1H"){
                    $result["temp"] = $obj->fcstValue;
                    break;
                }
            }
        }
        
    }else{
        $result["tmn"] = $weather["TMN"];//최저온도
        $result["tmx"] = $weather["TMX"];//최고온도
    }
    $result["addr"] = $addr2;
    //$result["url"] = $url;
    $result["msg"] = "정보 조회 완료";
    $result["time"] = $totime."시 기준";
}else{
    $result["msg"]="날씨정보를 가져올수 없습니다.";
}
echo json_encode($result);
