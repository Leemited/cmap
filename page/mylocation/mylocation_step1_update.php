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
          cmap_construct_jibeon = '{$cmap_construct_jibeon}' 
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
          cmap_construct_jibeon = '{$cmap_construct_jibeon}'";

}
if(sql_query($sql)){
    if(!$id) {
        $id = sql_insert_id();
    }
    goto_url(G5_URL."/page/mylocation/mylocation_step2.php?id=".$id);
}else{
    alert("문제가 발생되어 처음으로 돌아갑니다.", G5_URL."/page/mylocation/mylocation_step1.php");
    return false;
}

?>