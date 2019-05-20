<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
include_once (G5_PATH."/head.php");

if($constid){
    $sql = "select * from `cmap_my_construct_temp` where id = '{$constid}'";
    $view = sql_fetch($sql);
    $priceKorea = getConvertNumberToKorean($view["cmap_construct_price_service"]);
}

add_javascript(G5_POSTCODE_JS, 0);
?>
<div class="width-fixed construct-width">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="construct_write">
            <h2>현장개설</h2>
            <h3><i></i> 공사개요</h3>
            <form action="<?php echo G5_URL;?>/page/mylocation/mylocation_step2_update" method="post" name="write_step2">
                <input type="hidden" value="<?php echo $constid;?>" name="constid">
            <div class="write_box">
                <table>
                    <tr>
                        <th>건설사업관리용역명 <span>*</span></th>
                        <td>
                            <input type="text" class="basic_input01" name="cmap_name_service" id="cmap_name_service" required value="<?php echo $view["cmap_name_service"];?>">
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th rowspan="10" class="depth1">건<br>설<br>사<br>업<br>관<br>리<br>용<br>역</th>
                        <th rowspan="3" class="depth2">회<br>사<br>정<br>보</th>
                        <th>용역업체 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_company_service" id="cmap_company_service" required value="<?php echo $view["cmap_company_service"];?>"></td>
                    </tr>
                    <tr>
                        <th>대표자 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_company_ceo_service" id="cmap_company_ceo_service" required value="<?php echo $view["cmap_company_ceo_service"];?>"></td>
                    </tr>
                    <tr>
                        <th class="end">사업자등록번호 <span>*</span></th>
                        <td class="end"><input type="text" class="basic_input01" name="cmap_company_num_service" id="cmap_company_num_service" required placeholder="'-' 제외하고 입력" value="<?php echo $view["cmap_company_num_service"];?>"></td>
                    </tr>
                    <tr class="empty">
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <th rowspan="6" class="depth2">현<br>장<br>정<br>보</th>
                        <th>계약번호 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_num_service" id="cmap_construct_num_service" required placeholder="'-' 제외하고 입력" value="<?php echo $view["cmap_construct_num_service"];?>"></td>
                    </tr>
                    <tr>
                        <th>용역금액</th>
                        <td class="price">
                            <input type="text" class="basic_input01 price_input" name="cmap_construct_price_service" id="cmap_construct_price_service" onkeyup="fnPrice(this.value)" required value="<?php echo $view["cmap_construct_price_service"];?>"> 원
                            <p>일금 <span class="return_price"><?php echo $priceKorea;?></span>원</p>
                        </td>
                    </tr>
                    <tr>
                        <th>직책</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_position_service" id="cmap_construct_position_service" value="<?php echo $view["cmap_construct_position_service"];?>"></td>
                    </tr>
                    <tr>
                        <th>성명</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_name_service" id="cmap_construct_name_service" value="<?php echo $view["cmap_construct_name_service"];?>"></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_tel_service" id="cmap_construct_tel_service"  placeholder="'-' 제외하고 입력" value="<?php echo $view["cmap_construct_tel_service"];?>"></td>
                    </tr>
                    <tr>
                        <th class="end">주소 <span>*</span></th>
                        <td class="end">
                            <input type="text" name="cmap_construct_zipcode_service" value="<?php echo $view["cmap_construct_zipcode_service"];?>" id="reg_mb_zip" class="basic_input01" size="5" maxlength="6"  placeholder="우편번호" readonly required>
                            <button type="button" class="basic_btn02" onclick="win_zip2('write_step2', 'cmap_construct_zipcode_service', 'cmap_construct_addr1_service', 'cmap_construct_addr2_service', 'cmap_construct_addr3_service', 'cmap_construct_jibeon_service');">주소 검색</button><br>
                            <input type="text" name="cmap_construct_addr1_service" value="<?php echo $view["cmap_construct_addr1_service"];?>" id="reg_mb_addr1"  class="basic_input01 " size="50"  placeholder="기본주소" readonly required>
                            <input type="text" name="cmap_construct_addr2_service" value="<?php echo $view["cmap_construct_addr2_service"];?>" id="reg_mb_addr2" class="basic_input01" size="50"  placeholder="상세주소" required>
                            <input type="hidden" name="cmap_construct_addr3_service" value="<?php echo $view["cmap_construct_addr3_service"];?>" id="reg_mb_addr3" class="basic_input01" size="50" readonly="readonly"  placeholder="참고항목">
                            <input type="hidden" name="cmap_construct_jibeon_service" value="<?php echo $view["cmap_construct_jibeon_service"];?>">
                        </td>
                    </tr>
                </table>
                <div class="btn_group">
                    <input type="button" class="basic_btn02 width20" value="< 이전" onclick="location.href='<?php echo G5_URL;?>/page/mylocation/mylocation_step1?constid=<?php echo $constid;?>'">
                    <input type="submit" class="basic_btn01 width20" value="다음 >" >
                </div>
            </div>
            </form>
        </div>
    </section>
</div>
<script>
    function fnPrice(price){
        var pi = price.replace(/[^0-9]/g, '');
        $("#cmap_construct_price_service").val(pi);
        var han = viewKorean(pi);
        $(".return_price").html(han);
    }
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
