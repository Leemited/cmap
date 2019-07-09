<?php
include_once ("../../common.php");

if($constid) {
    if($type=="insert"){
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
          cmap_name_service = '{$cmap_name_service}',
          cmap_company_service = '{$cmap_company_service}',
          cmap_company_ceo_service = '{$cmap_company_ceo_service}',
          cmap_company_num_service = '{$cmap_company_num_service}',
          cmap_construct_num_service = '{$cmap_construct_num_service}',
          cmap_construct_price_service = '{$cmap_construct_price_service}',
          cmap_construct_position_service = '{$cmap_construct_position_service}',
          cmap_construct_name_service = '{$cmap_construct_name_service}',
          cmap_construct_tel_service = '{$cmap_construct_tel_service}',
          cmap_construct_zipcode_service = '{$cmap_construct_zipcode_service}',
          cmap_construct_addr1_service = '{$cmap_construct_addr1_service}',
          cmap_construct_addr2_service = '{$cmap_construct_addr2_service}',
          cmap_construct_addr3_service = '{$cmap_construct_addr3_service}',
          cmap_construct_jibeon_service = '{$cmap_construct_jibeon_service}'
          where id = '{$constid}'";
    }else {
        $sql = "update `cmap_my_construct` set 
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
          cmap_name_service = '{$cmap_name_service}',
          cmap_company_service = '{$cmap_company_service}',
          cmap_company_ceo_service = '{$cmap_company_ceo_service}',
          cmap_company_num_service = '{$cmap_company_num_service}',
          cmap_construct_num_service = '{$cmap_construct_num_service}',
          cmap_construct_price_service = '{$cmap_construct_price_service}',
          cmap_construct_position_service = '{$cmap_construct_position_service}',
          cmap_construct_name_service = '{$cmap_construct_name_service}',
          cmap_construct_tel_service = '{$cmap_construct_tel_service}',
          cmap_construct_zipcode_service = '{$cmap_construct_zipcode_service}',
          cmap_construct_addr1_service = '{$cmap_construct_addr1_service}',
          cmap_construct_addr2_service = '{$cmap_construct_addr2_service}',
          cmap_construct_addr3_service = '{$cmap_construct_addr3_service}',
          cmap_construct_jibeon_service = '{$cmap_construct_jibeon_service}'
          where id = '{$constid}'";
    }
    if (sql_query($sql)) {
        if($type=="insert") {
            goto_url(G5_URL . "/page/mylocation/mylocation_step3?constid=" . $constid);
        }else{
            goto_url(G5_URL . "/page/mylocation/mylocation_view?constid=" . $constid);
        }
    } else {
        alert("정보오류로 인해 수정되지 못했습니다.", G5_URL . "/page/mylocation/mylocation_view");
        return false;
    }
}else{
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
                cmap_name_service = '{$cmap_name_service}',
                cmap_company_service = '{$cmap_company_service}',
                cmap_company_ceo_service = '{$cmap_company_ceo_service}',
                cmap_company_num_service = '{$cmap_company_num_service}',
                cmap_construct_num_service = '{$cmap_construct_num_service}',
                cmap_construct_price_service = '{$cmap_construct_price_service}',
                cmap_construct_position_service = '{$cmap_construct_position_service}',
                cmap_construct_name_service = '{$cmap_construct_name_service}',
                cmap_construct_tel_service = '{$cmap_construct_tel_service}',
                cmap_construct_zipcode_service = '{$cmap_construct_zipcode_service}',
                cmap_construct_addr1_service = '{$cmap_construct_addr1_service}',
                cmap_construct_addr2_service = '{$cmap_construct_addr2_service}',
                cmap_construct_addr3_service = '{$cmap_construct_addr3_service}',
                cmap_construct_jibeon_service = '{$cmap_construct_jibeon_service}',
                mb_id = '{$member["mb_id"]}'";
    if(sql_query($sql)){
        if(!$constid) {
            $constid = sql_insert_id();
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
        $sql = "update `cmap_my_construct_temp` set cmap_construct_lat = '{$latlng["lat"]}' , cmap_construct_lng = '{$latlng["lng"]}' {$weather} where id = '{$constid}'";
        sql_query($sql);

        goto_url(G5_URL."/page/mylocation/mylocation_step3?constid=".$constid);
    }else{
        alert("문제가 발생되어 처음으로 돌아갑니다.", G5_URL."/page/mylocation/mylocation");
        return false;
    }
}
?>