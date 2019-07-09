<?php
include_once ("../../common.php");
$sub = "sub";
$mypage = true;
include_once (G5_PATH."/head.php");


if($constid){
    $sql = "select * from `cmap_my_construct_temp` where id = '{$constid}'";
    $view = sql_fetch($sql);
    $priceKorea = getConvertNumberToKorean($view["cmap_construct_price"]);
}

add_javascript(G5_POSTCODE_JS, 0);
?>
<div class="width-fixed construct-width">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="javascript:fnLogout();"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="construct_write">
            <h2>현장개설</h2>
            <h3><i></i> 공사개요</h3>
            <form action="<?php echo G5_URL;?>/page/mylocation/mylocation_step1_update" method="post" name="write_step1">
                <input type="hidden" value="<?php echo $constid;?>" name="constid">
            <div class="write_box">
                <table>
                    <tr>
                        <th>건설공사명 <span>*</span></th>
                        <td>
                            <input type="text" class="basic_input01" name="cmap_name" id="cmap_name" required value="<?php echo $view["cmap_name"];?>">
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <th rowspan="10" class="depth1">건<br>설<br>공<br>사</th>
                        <th rowspan="3" class="depth2">회<br>사<br>정<br>보</th>
                        <th>시공업체 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_company" id="cmap_company" required value="<?php echo $view["cmap_company"];?>"></td>
                    </tr>
                    <tr>
                        <th>대표자 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_company_ceo" id="cmap_company_ceo" required value="<?php echo $view["cmap_company_ceo"];?>"></td>
                    </tr>
                    <tr>
                        <th class="end">사업자등록번호 <span>*</span></th>
                        <td class="end"><input type="text" class="basic_input01" name="cmap_company_num" id="cmap_company_num" required placeholder="'-' 제외하고 입력" onkeyup="number_only(this)" value="<?php echo $view["cmap_company_num"];?>"></td>
                    </tr>
                    <tr class="empty">
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <th rowspan="6" class="depth2">현<br>장<br>정<br>보</th>
                        <th>계약번호 <span>*</span></th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_num" id="cmap_construct_num" required placeholder="'-' 제외하고 입력" value="<?php echo $view["cmap_construct_num"];?>"></td>
                    </tr>
                    <tr>
                        <th>공사금액</th>
                        <td class="price">
                            <input type="text" class="basic_input01 price_input" name="cmap_construct_price" id="cmap_construct_price" required onkeyup="fnPrice(this.value)" value="<?php echo $view["cmap_construct_price"];?>"> 원
                            <p>일금 <span class="return_price"><?php echo $priceKorea;?></span>원</p>
                        </td>
                    </tr>
                    <tr>
                        <th>직책</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_position" id="cmap_construct_position" value="<?php echo $view["cmap_construct_position"];?>"></td>
                    </tr>
                    <tr>
                        <th>성명</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_name" id="cmap_construct_name" value="<?php echo $view["cmap_construct_name"];?>"></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td><input type="text" class="basic_input01" name="cmap_construct_tel" id="cmap_construct_tel"  placeholder="'-' 제외하고 입력" onkeyup="number_only(this)" value="<?php echo $view["cmap_construct_tel"];?>"></td>
                    </tr>
                    <tr>
                        <th class="end">주소 <span>*</span></th>
                        <td class="end">
                            <input type="text" name="cmap_construct_zipcode" value="<?php echo $view["cmap_construct_zipcode"];?>" id="reg_mb_zip" class="basic_input01" size="5" maxlength="6"  placeholder="우편번호" readonly required>
                            <button type="button" class="basic_btn02" onclick="win_zip2('write_step1', 'cmap_construct_zipcode', 'cmap_construct_addr1', 'cmap_construct_addr2', 'cmap_construct_addr3', 'cmap_construct_jibeon');">주소 검색</button><br>
                            <input type="text" name="cmap_construct_addr1" value="<?php echo $view["cmap_construct_addr1"];?>" id="reg_mb_addr1"  class="basic_input01" size="50"  placeholder="기본주소" readonly required>
                            <input type="text" name="cmap_construct_addr2" value="<?php echo $view["cmap_construct_addr2"];?>" id="reg_mb_addr2" class="basic_input01" size="50"  placeholder="상세주소" required>
                            <input type="hidden" name="cmap_construct_addr3" value="<?php echo $view["cmap_construct_addr3"];?>" id="reg_mb_addr3" class="frm_input frm_address full_input" size="50" readonly="readonly"  placeholder="참고항목">
                            <input type="hidden" name="cmap_construct_jibeon" value="<?php echo $view["cmap_construct_jibeon"];?>">
                        </td>
                    </tr>
                </table>
                <div class="btn_group">
                    <input type="button" class="basic_btn02 width20" value="< 이전" onclick="location.href=g5_url+'/page/mylocation/mylocation'">
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
    $("#cmap_construct_price").val(pi);
    var han = viewKorean(pi);
    $(".return_price").html(han);
}
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
