<?php
include_once ("../../common.php");

if($id){
    $sql = "update `cmap_my_construct_temp` set 
          cmap_name = '{$cmap_name}',
          cmap_company = '{$cmap_company}',
          cmap_company_ceo = '{$cmap_company_ceo}',
          cmap_company_num = '{$cmap_company_num}',
          cmap_construct_num = '{$cmap_construct_num}',
          cmap_construct_price = '{$cmap_construct_price}',
          cmap_construct_position = '{$cmap_construct_position}',
          cmap_construct_name = '{$cmap_construct_name}',
          cmap_construct_tel = '{$cmap_construct_tel}',
          cmap_construct_zipcode = '{$cmap_construct_zipcode}',
          cmap_construct_addr1 = '{$cmap_construct_addr1}',
          cmap_construct_addr2 = '{$cmap_construct_addr2}',
          cmap_construct_addr3 = '{$cmap_construct_addr3}',
          cmap_construct_jibeon = '{$cmap_construct_jibeon}', 
          mb_id = '{$member["mb_id"]}'
          where id = '{$id}'";
}else {

    $sql = "insert into `cmap_my_construct_temp` set 
          cmap_name = '{$cmap_name}',
          cmap_company = '{$cmap_company}',
          cmap_company_ceo = '{$cmap_company_ceo}',
          cmap_company_num = '{$cmap_company_num}',
          cmap_construct_num = '{$cmap_construct_num}',
          cmap_construct_price = '{$cmap_construct_price}',
          cmap_construct_position = '{$cmap_construct_position}',
          cmap_construct_name = '{$cmap_construct_name}',
          cmap_construct_tel = '{$cmap_construct_tel}',
          cmap_construct_zipcode = '{$cmap_construct_zipcode}',
          cmap_construct_addr1 = '{$cmap_construct_addr1}',
          cmap_construct_addr2 = '{$cmap_construct_addr2}',
          cmap_construct_addr3 = '{$cmap_construct_addr3}',
          cmap_construct_jibeon = '{$cmap_construct_jibeon}',
          mb_id = '{$member["mb_id"]}'";
}
if(sql_query($sql)){
    if(!$id) {
        $id = sql_insert_id();
    }

    //날씨정보를 위한 위치 정보 가져오기
    $addr1 = explode(" ",$cmap_construct_addr1);
    if(strpos($addr1[2],"구")!==false){
        $addr1[1] = $addr1[1].$addr1[2];
        $addr1[2] = $addr1[3];
    }

    $sql = "select count(*) as cnt from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}' ) and addr2 = '{$addr1[1]}' and addr3 = '{$addr1[2]}'";
    $step1 = sql_fetch($sql);
    if($step1["cnt"]==0){
        $sql = "select count(*) as cnt from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}' ) and addr2 = '{$addr1[1]}'";
        $step2 = sql_fetch($sql);
        if($step2["cnt"]==0){
            $sql = "select count(*) as cnt from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}')";
            $step3 = sql_fetch($sql);
            if($step2["cnt"]>0){
                $sql = "select * from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}') limit 0 ,1";
                $latlng = sql_fetch($sql);
                $weather = " , weather_addr1 = '{$addr1[0]}' , weather_addr2 = '', weather_addr3 = ''";
            }
        }else{
            $sql = "select * from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}' ) and addr2 = '{$addr1[1]}' limit 0 ,1";
            $latlng = sql_fetch($sql);
            $weather = " , weather_addr1 = '{$addr1[0]}', weather_addr2 = '{$addr1[1]}' , weather_addr3 = ''";
        }
    }else{
        $sql = "select * from `weather_location` where (addr1 = '{$addr1[0]}' or addr1_short = '{$addr1[0]}' ) and addr2 = '{$addr1[1]}' and addr3 = '{$addr1[2]}' limit 0 ,1";
        $latlng = sql_fetch($sql);
        $weather = " , weather_addr1 = '{$addr1[0]}', weather_addr2 = '{$addr1[1]}', weather_addr3 = '{$addr1[2]}'";
    }
    $sql = "update `cmap_my_construct_temp` set cmap_construct_lat = '{$latlng["lat"]}' , cmap_construct_lng = '{$latlng["lng"]}' {$weather} where id = '{$id}'";
    sql_query($sql);

    goto_url(G5_URL."/page/mylocation/mylocation_step2?id=".$id);
}else{
    alert("문제가 발생되어 처음으로 돌아갑니다.", G5_URL."/page/mylocation/mylocation_step1");
    return false;
}

?>