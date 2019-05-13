<?php
include_once ("../../common.php");

if(!$id){
    alert("입력정보에 이상이 있습니다.\\r처음부터 다시 입력해 주세요.",G5_URL."/page/mylocation/mylocation_step1.php");
    return false;
}

$sql = "update `cmap_my_construct_temp` set 
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
          where id = '{$id}';
";



if(sql_query($sql)){
    goto_url(G5_URL."/page/mylocation/mylocation_step3?id=".$id);
}else{
    alert("문제가 발생되어 처음으로 돌아갑니다.", G5_URL."/page/mylocation/mylocation_step1?id=".$id);
    return false;
}
?>