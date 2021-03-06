<?php
include_once ("../../common.php");
if(!$is_member){
    goto_url(G5_BBS_URL."/login.php");
}
$sub = "sub";
$mypage = true;
$menu_id = "depth_desc_edit";
include_once (G5_PATH."/page/mypage/confirm.php");
include_once (G5_PATH."/_head.php");

$sql = "select * from `g5_member` where parent_mb_id = '{$member["mb_id"]}' and mb_level = 3 order by mb_datetime asc";
$res = sql_query($sql);
$o = 0;
$today = date("Y-m-d");
while($row = sql_fetch_array($res)){
    //print_r2($row);
    $list[$o] = $row;
    $sql = "select * from `cmap_payments` where mb_id = '{$row["mb_id"]}' and order_cancel = 0";
    $ress = sql_query($sql);
    while($row2=sql_fetch_array($ress)) {
        if ($row2["payment_start_date"] && $row2["payment_end_date"] > $today) {
            $list[$o]["payments"] = "활성";
            if($list[$o]["start"]=="") {
                $list[$o]["start"] = $row2["payment_start_date"];
            }
            if($list[$o]["end"] < $row2["payment_end_date"] || $list[$o]["end"]=="") {
                $list[$o]["end"] = $row2["payment_end_date"];
            }
            $list[$o]["month"] += $row2["payment_month"];
        }
    }
    if($list[$o]["payments"]==""){
        $list[$o]["payments"] = "비활성";
        $list[$o]["start"] = "결제정보 없음";
        $list[$o]["end"] = "결제정보 없음";
        $list[$o]["month"] = "-";
    }
    $o++;
}

?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2 onclick="location.href=g5_url+'/page/mypage/mypage'">MY C.MAP</h2>
            <div class="logout">
                <a href="javascript:fnLogout();"><span></span>로그아웃</a>
            </div>
        </header>
        <aside class="mypage_menu">
            <div class="mtop">
                <?php echo $member["mb_id"];?>님 회원정보
            </div>
            <div class="mbottom">
                <ul class="mmenu">
                    <li onclick="location.href=g5_url+'/page/mypage/edit_profile_chkpwd'"><i></i>개인정보 수정</li>
                    <?php if($member["mb_level"]==5 && $member["parent_mb_id"]==""){?>
                        <li class="active"><i></i>부계정 등록</li>
                    <?php }?>
                    <li onclick="location.href=g5_url+'/page/mypage/member_leave'"><i></i>회원탈퇴</li>
                </ul>
            </div>
        </aside>
        <article class="mypage_con">
            <header>
                <h2>부계정 등록</h2>
            </header>
            <div class="register_edit">
                <ul>
                    <li>해당 아이디 생성후 결제가 완료되어야 사용 가능합니다.</li>
                </ul>
                <h2>
                    <span></span> 나의 정보관리
                </h2>
                <div class="profile_form">
                    <form action="<?php echo G5_URL;?>/page/mypage/submember_insert.php" method="post" class="submember_form" onsubmit="return fnAddMember();">
                        <input type="hidden" name="mb_level" value="3">
                        <input type="hidden" name="parent_mb_id" value="<?php echo $member["mb_id"];?>">
                        <table >
                            <tr>
                                <td class="td_center" style="background-color: #ddd">PM 추가</td>
                                <td class="td_center" style="padding:5px;"><input type="text" class="basic_input01 width100" name="mb_id" id="mb_id" placeholder="아이디"></td>
                                <td class="td_center" style="padding:5px;"><input type="text" class="basic_input01 width100" name="mb_password" id="mb_password" placeholder="패스워드"></td>
                                <td class="td_center" style="padding:5px;"><input type="submit" class="basic_btn02" style="width:auto;margin:auto"  value="추가하기" ></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <h2 class="second">
                    <span></span> 나의 부계정 목록
                </h2>
                <div class="profile_form">
                    <table class="submem_list">
                        <colgroup>
                            <col width="8%">
                            <col width="10%">
                            <col width="10%">
                            <col width="10%">
                            <col width="12%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                            <col width="8%">
                        </colgroup>
                        <tr>
                            <th>구분</th>
                            <th colspan="">아이디</th>
                            <th colspan="2">사용기간</th>
                            <th >계약기간</th>
                            <th colspan="3">기간</th>
                            <th>선택</th>
                            <th>상태</th>
                            <th>관리</th>
                        </tr>
                        <?php for($i=0;$i<count($list);$i++){?>
                        <tr class="company_mbs">
                            <td class="td_center">PM <?php echo $i+1;?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_id"];?></td>
                            <td class="td_center"><?php echo $list[$i]["start"];?></td>
                            <td class="td_center"><?php echo $list[$i]["end"];?></td>
                            <td class="td_center"><?php echo ($list[$i]["month"]!="-")?$list[$i]["month"]." 개월":$list[$i]["month"];?></td>
                            <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="1" id="<?php echo $list[$i]["mb_id"];?>month1" ><label for="<?php echo $list[$i]["mb_id"];?>month1">1개월</label></td>
                            <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="2" id="<?php echo $list[$i]["mb_id"];?>month6" ><label for="<?php echo $list[$i]["mb_id"];?>month6">6개월</label></td>
                            <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="3" id="<?php echo $list[$i]["mb_id"];?>month12" ><label for="<?php echo $list[$i]["mb_id"];?>month12">12개월</label></td>
                            <td class="td_center">
                                <input type="button" value="선택해제" onclick="fnRadioCancel('<?php echo $list[$i]["mb_id"];?>')" class="basic_btn02" style="padding:6px;margin:auto;width:auto">
                            </td>
                            <td class="td_center"
                            ><?php if($list[$i]["payments"]=="활성"){?><input type="button" class="basic_btn02" style="padding:6px;margin:auto;width:auto" value="연장하기" onclick="fnPayments('<?php echo $list[$i]["mb_id"];?>')"><?php }else{?>
                                    <input type="button" value="결제하기" class="basic_btn02" style="padding:6px;margin:auto;width:auto" onclick="fnPayments('<?php echo $list[$i]["mb_id"];?>')"><?php }?>
                            </td>
                            <td class="td_center">
                                <input type="button" class="basic_btn02" style="padding:6px;margin:auto;width:auto" value="상세보기" onclick="location.href=g5_url+'/page/mypage/submem_edit_profile?mb_id=<?php echo $list[$i]["mb_id"];?>'">
                            </td>
                        </tr>
                        <?php }?>
            <?php if(count($list)==0){?>
                            <tr>
                                <td colspan="11" class="td_center">등록된 부계정이 없습니다.</td>
                            </tr>
                        <?php }else{?>
                            <tr class="blod_tr">
                                <td colspan="2" class="td_center">합계</td>
                                <td colspan="2" class="td_center">계약기간</td>
                                <td colspan="3" style="text-align: right;padding:5px;"><span class="totalMonth">0</span> 개월</td>
                                <td colspan="2" class="td_center">
                                    총 금액 :
                                </td>
                                <td colspan="2" style="text-align: right;padding:5px;">
                                    <span class="totalPrice">0</span> 원
                                </td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
                <div style="text-align: center;padding:30px 0 0;">
                    <input type="hidden" name="payments" id="payments" value="">
                    <input type="button" value="문의하기" class="basic_btn02" onclick="location.href=g5_url+'/page/board/inquiry'">
                    <input type="button" value="선택일괄결제" class="basic_btn01" onclick="fnAllPayment();">
                </div>
            </div>
        </article>
    </section>
</div>
<script>
    function fnRadioCancel(mb_id){
        $("input[name=order_type_"+mb_id+"]").prop("checked",false);
        fnCalc();
    }
    function fnAddMember(){
        var regExpPw = /^.*(?=^.{6,15}$)(?=.*\d)(?=.*[a-zA-Z])(?=.*[!@#$%^&+=]).*$/;

        if($("#mb_id").val()==""){
            alert("아이디를 입력해주세요.");
            return false;
        }

        if($("#mb_password").val()==""){
            alert("비밀번호를 입력해주세요.");
            return false;
        }

        if($("#mb_password").val().length < 6){
            alert("비밀번호는 최소 6글자 이상 입력해주세요.");
            return false;
        }

        if(!regExpPw.test($("#mb_password").val())){
            alert("숫자 영문조합, 특수문자 1개 이상 필수 6자리 이상 최대 15자리를 입력 바랍니다. ");
            return false;
        }
    }

    function fnPayments(mb_id){
        var payment_type = "";
        $("input[name=order_type_"+mb_id+"]").each(function(){
            if($(this).prop("checked")==true) {
                payment_type = $(this).val();
            }
        });

        if(payment_type==""){
            alert("기간을 선택해주세요.");
            return false;
        }

        var amount = 0;
        switch (payment_type){
            case "1":
                amount = 99000;
                break;
            case "2":
                amount = 528000;
                break;
            case "3":
                amount = 924000;
                break;
        }

        $.ajax({
            url:g5_url+'/page/ajax/ajax.sel_order_type_com.php',
            method:"post",
            data:{amount:amount,payment_type:payment_type,mb_id:mb_id}
        }).done(function(data){
            fnShowModal(data);
            //memberPayment(amount,payment_type,data);
        });
    }

    function fnAllPayment(){
        if($("#payments").val()==""){
            alert("선택된 회원이 없습니다.");
            return false;
        }

        var payments = $("#payments").val();

        $.ajax({
            url:g5_url+'/page/ajax/ajax.company_sub_member.php',
            method:"post",
            data:{payments:payments,type:"cm"}
        }).done(function(data){
            if(data==1){
                alert("신규 결제 할 계정이 없습니다.");
            }else{
                fnShowModal(data);
            }
        });
    }

    $(function(){
        $("input[name^=order_type_]").click(function(){
            fnCalc();
        });
    });

    function fnCalc(){
        var payments = '';
        var totalPrice = 0;
        var totalMonth = 0;
        $("input[name^=order_type_]").each(function(){
            var price = 0;
            if($(this).prop("checked")==true){
                var id = $(this).attr("name").replace("order_type_","");
                var order_type = $(this).val();
                var month = 1;
                switch (order_type){
                    case "1":
                        month = 1;
                        price = 99000;
                        break;
                    case "2":
                        month = 6;
                        price = 528000;
                        break;
                    case "3":
                        month = 12;
                        price = 924000;
                        break;
                }
                if(payments){payments+=",";}
                payments += order_type+"``"+id+"``"+month+"``"+price;
                totalPrice+=price;
                totalMonth+=month;
            }
        });
        $(".totalMonth").html(totalMonth);
        $(".totalPrice").html(number_format(totalPrice));
        $("#payments").val(payments);
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
