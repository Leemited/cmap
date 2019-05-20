<?php
include_once ("../../common.php");

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

if(sql_query($sql)){
    goto_url(G5_URL."/page/mylocation/mylocation_view?constid=".$constid);
}else{
    alert("정보오류로 인해 수정되지 못했습니다.", G5_URL."/page/mylocation/mylocation_view");
    return false;
}

?>