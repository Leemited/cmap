<?php
include_once ("_common.php");

$time = date("i");
$base_time = date("H");
$today = date("Ymd");
$today2 = date("Y-m-d");
/*if($time > 30){
    $base_time = date("H", strtotime("+ 1 hour"));
    if($base_time < 0){
        $today = date("Ymd",strtotime("- 1 day"));
        $base_time = "23";
    }
}*/

$base_time = $base_time."00";
switch ($base_time){
    case "0500":
        $baseTime = "12시";
        $sqlTime = " , base_time2 = '12시'";
        $times = 2;
        break;
    case "0200":
        $baseTime = "09시";
        $sqlTime = " , base_time = '9시'";
        break;
}

/*
$sql = "select cmap_construct_lat, cmap_construct_lng, weather_addr1, weather_addr2, weather_addr3, id
        from `cmap_my_construct`
        where status = 0 and cmap_construct_lat != '' and cmap_construct_lng != ''";
*/
//$base_time='1200';

$sql = "select cmap_construct_lat, cmap_construct_lng, weather_addr1, weather_addr2, weather_addr3,
            concat(weather_addr1, ' ', weather_addr2, ' ', weather_addr3) 'weather_add',
            id
        from `cmap_my_construct`
        where status = 0 and cmap_construct_lat != '' and cmap_construct_lng != ''";

$res = sql_query($sql);

while($rs_row = sql_fetch_array($res)){    
    $url = "http://newsky2.kma.go.kr/service/SecndSrtpdFrcstInfoService2/ForecastSpaceData?".
            "ServiceKey=n1t%2B4j2iWa7OlDB0dGxtEk0TRjTN%2Fs9XVV%2FoUgexCxN5i%2BPQA%2BbkmslYrOWgK82GK28prPQB4rfMA4vQZlALXA%3D%3D".
            "&base_date=".$today.
            "&base_time=".$base_time.
            "&nx=".$rs_row["cmap_construct_lat"].
            "&ny=".$rs_row["cmap_construct_lng"].
            "&numOfRows=99999999";

    $ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)

    curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
    curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
    curl_setopt($ch, CURLOPT_HEADER, 0);//헤더 정보를 보내도록 함(*필수)
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능(테스트 시 기본값은 1인듯?)

    $weather_res = curl_exec ($ch);

    curl_close($ch);

    $weather_xml = simplexml_load_string($weather_res);

    //print_r2($weather_xml);
    //return;

    foreach($weather_xml->body->items->item as $item){
        //print_r2($item);
        if($item->fcstTime=='0900'){
            $c_str = "";
            $c_missing = "";
            switch($item->category){
                case "POP": //강수확률
                    $c_str='POP'.$times;
                    $c_missing='-1';
                    break;
                case "PTY": //강수형태
                    $c_str='PTY'.$times;
                    $c_missing='-1';
                    break;
                case "R06"://6시간 강수량
                    $c_str='R06'.$times;
                    $c_missing='-1';
                    break;
                case "REH"://습도
                    $c_str='REH'.$times;
                    $c_missing='-1';
                    break;
                case "S06"://6시간 신적설
                    $c_str='S06'.$times;
                    $c_missing='-1';
                    break;
                case "SKY": //하늘상태
                    $c_str='SKY'.$times;
                    $c_missing='-1';
                    break;
                case "T3H": //3시간 기온
                    $c_str='T3H'.$times;
                    $c_missing='-50';
                    break;
                case "TMN": //아침 최저기온
                    $c_str='TMN'.$times;
                    $c_missing='-50';
                    break;
                case "TMX": //낮 최고기온
                    $c_str='TMX'.$times;
                    $c_missing='-50';
                    break;
                case "VEC": //낮 최고기온
                    $c_str='VEC'.$times;
                    $c_missing='-50';
                    break;
                case "UUU": //낮 최고기온
                    $c_str='UUU'.$times;
                    $c_missing='-50';
                    break;
                case "VVV": //낮 최고기온
                    $c_str='VVV'.$times;
                    $c_missing='-50';
                    break;
                case "WAV": //낮 최고기온
                    $c_str='WAV'.$times;
                    $c_missing='-50';
                    break;
                case "VEC": //낮 최고기온
                    $c_str='VEC'.$times;
                    $c_missing='-50';
                    break;
                case "WSD": //낮 최고기온
                    $c_str='WSD'.$times;
                    $c_missing='-50';
                    break;
            }
        }
        else if($item->fcstTime=='1200'){
            $c_str = "";
            $c_missing = "";
            switch($item->category){
                case "POP": //강수확률
                    $c_str='POP2'.$times;
                    $c_missing='-1';
                    break;
                case "PTY": //강수형태
                    $c_str='PTY2'.$times;
                    $c_missing='-1';
                    break;
                case "R06"://6시간 강수량
                    $c_str='R062'.$times;
                    $c_missing='-1';
                    break;
                case "REH"://습도
                    $c_str='REH2'.$times;
                    $c_missing='-1';
                    break;
                case "S06"://6시간 신적설
                    $c_str='S062'.$times;
                    $c_missing='-1';
                    break;
                case "SKY": //하늘상태
                    $c_str='SKY2'.$times;
                    $c_missing='-1';
                    break;
                case "T3H": //3시간 기온
                    $c_str='T3H2'.$times;
                    $c_missing='-50';
                    break;
                case "TMN": //아침 최저기온
                    $c_str='TMN2'.$times;
                    $c_missing='-50';
                    break;
                case "TMX": //낮 최고기온
                    $c_str='TMX2'.$times;
                    $c_missing='-50';
                    break;
                case "VEC": //낮 최고기온
                    $c_str='VEC2'.$times;
                    $c_missing='-50';
                    break;
                case "UUU": //낮 최고기온
                    $c_str='UUU2'.$times;
                    $c_missing='-50';
                    break;
                case "VVV": //낮 최고기온
                    $c_str='VVV2'.$times;
                    $c_missing='-50';
                    break;
                case "WAV": //낮 최고기온
                    $c_str='WAV2'.$times;
                    $c_missing='-50';
                    break;
                case "VEC": //낮 최고기온
                    $c_str='VEC2'.$times;
                    $c_missing='-50';
                    break;
                case "WSD": //낮 최고기온
                    $c_str='WSD2'.$times;
                    $c_missing='-50';
                    break;
            }
        }
        
        if($item->category=='TMN'){
            $sql_duplicate_check="
            select count(id)as cnt, id from `weather`
            where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";
            $rs_dp_ch=sql_fetch($sql_duplicate_check);
            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into 
                            `weather` (
                                lat, lng, addr1, addr2, addr3, insert_date, update_date, TMN
                            )
                            values (
                                {$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue}
                            )";
            }
            else{
                $sql_w_inu="update `weather` set TMN = {$item->fcstValue}, update_date = now() where id = {$rs_dp_ch['id']}";
            }
            sql_query($sql_w_inu);
        }

        if($item->category=='TMX'){
            $sql_duplicate_check="
            select count(id)as cnt, id from `weather`
            where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";
            $rs_dp_ch=sql_fetch($sql_duplicate_check);
            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into 
                            `weather` (
                                lat, lng, addr1, addr2, addr3, insert_date, update_date, TMX
                            )
                            values (
                                {$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue}
                            )";
            }
            else{
                $sql_w_inu="update `weather` set TMX = {$item->fcstValue}, update_date = now() where id = {$rs_dp_ch['id']}";
            }
            sql_query($sql_w_inu);
        }
        
        if($item->fcstTime=='0900'){
            $sql_duplicate_check="
                select count(id)as cnt, id from `weather`
                where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";

            $rs_dp_ch=sql_fetch($sql_duplicate_check);

            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into `weather` (lat, lng, addr1, addr2, addr3, insert_date, update_date, {$c_str})
                    values ({$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue})";
            }
            else{
                $sql_w_inu="update `weather` set {$c_str} = {$item->fcstValue}, update_date = now() {$sqlTime}
                    where id = {$rs_dp_ch['id']}";
            }

            sql_query($sql_w_inu);
        }
        if($item->fcstTime=='1200'){
            $sql_duplicate_check="
                select count(id)as cnt, id from `weather`
                where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";

            $rs_dp_ch=sql_fetch($sql_duplicate_check);

            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into `weather` (lat, lng, addr1, addr2, addr3, insert_date, update_date, {$c_str})
                    values ({$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue})";
            }
            else{
                $sql_w_inu="update `weather` set {$c_str} = {$item->fcstValue}, update_date = now() {$sqlTime}
                    where id = {$rs_dp_ch['id']}";
            }

            sql_query($sql_w_inu);
        }

        if($item->category=='R06'){
            $rain_str="";
            switch($item->fcstTime){
                case "0000":
                    $rain_str="rain00";
                    break;
                case "0600":
                    $rain_str="rain06";
                    break;
                case "1200":
                    $rain_str="rain12";
                    break;
                case "1800":
                    $rain_str="rain18";
                    break;
            }

            $sql_duplicate_check="
            select count(id)as cnt, id from `weather`
            where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";
            $rs_dp_ch=sql_fetch($sql_duplicate_check);

            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into 
                            `weather` (
                                lat, lng, addr1, addr2, addr3, insert_date, update_date, {$rain_str}
                            )
                            values (
                                {$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue}
                            )";
            }
            else{
                $sql_w_inu="update `weather` set {$rain_str} = {$item->fcstValue}, update_date = now() where id = {$rs_dp_ch['id']}";
            }

            sql_query($sql_w_inu);
        }

        if($item->category=='S06'){
            $rain_str="";
            switch($item->fcstTime){
                case "0000":
                    $rain_str="snow00";
                    break;
                case "0600":
                    $rain_str="snow06";
                    break;
                case "1200":
                    $rain_str="snow12";
                    break;
                case "1800":
                    $rain_str="snow18";
                    break;
            }

            $sql_duplicate_check="
            select count(id)as cnt, id from `weather`
            where lat = '{$rs_row["cmap_construct_lat"]}' and lng = '{$rs_row["cmap_construct_lng"]}' and date_format(insert_date, '%Y%m%d') = {$item->fcstDate}";
            $rs_dp_ch=sql_fetch($sql_duplicate_check);

            if($rs_dp_ch["cnt"]==0){
                $sql_w_inu="insert into 
                            `weather` (
                                lat, lng, addr1, addr2, addr3, insert_date, update_date, {$rain_str}
                            )
                            values (
                                {$rs_row['cmap_construct_lat']}, {$rs_row['cmap_construct_lng']}, '{$rs_row['weather_addr1']}', '{$rs_row['weather_addr2']}', '{$rs_row['weather_addr3']}', {$item->fcstDate}, now(), {$item->fcstValue}
                            )";
            }
            else{
                $sql_w_inu="update `weather` set {$rain_str} = {$item->fcstValue}, update_date = now() where id = {$rs_dp_ch['id']}";
            }

            sql_query($sql_w_inu);
        }
        //print_r2($sql_duplicate_check);
        //print_r2($sql_w_inu);
    }
}

/*
for($i=0;$row = sql_fetch_array($res);$i++) {

    if($row["weather_addr1"]){
        $where = " and addr1 = '{$row["weather_addr1"]}'";
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

    $url = "http://newsky2.kma.go.kr/service/SecndSrtpdFrcstInfoService2/ForecastSpaceData?ServiceKey=n1t%2B4j2iWa7OlDB0dGxtEk0TRjTN%2Fs9XVV%2FoUgexCxN5i%2BPQA%2BbkmslYrOWgK82GK28prPQB4rfMA4vQZlALXA%3D%3D&base_date=" . $today . "&base_time=".$base_time."&nx=" . $row["cmap_construct_lat"] . "&ny=" . $row["cmap_construct_lng"]."&numOfRows=20";

    $set = getData($url,$setaddr,$row,$today);

    $to = date("Y-m-d");

    $sql2 = "select count(*) as cnt from `weather` where insert_date = '{$today2}' and lat = '{$row["cmap_construct_lat"]}' and lng = '{$row["cmap_construct_lng"]}' and const_id = '{$row["id"]}' {$where}";
    $weather = sql_fetch($sql2);
    if($weather["cnt"]==0){
        $sql3 = "insert into `weather` set {$set} , insert_date = now() , update_date = now(), insert_time = now() , const_id = '{$row["id"]}'";
    }else{
        $sql3 = "update `weather` set {$set}, update_date = now() where const_id='{$row["id"]}'";
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
            }
        }
    }
    return $set;
}
*/

?>