<?php
include_once ("../../common.php");
if(!$is_member){
    goto_url(G5_BBS_URL.'/login');
}
include_once (G5_PATH."/head.com.php");

$sql = "select * from `g5_member` where parent_mb_id = '{$member["mb_id"]}' and mb_level = 5 order by mb_datetime asc";
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
<div class="etc_view">

</div>
<div class="etc_view_bg"></div>
<div class="width-fixed-com">
    <header class="com_head">
        <h2><?php echo $member["mb_1"];?> 관리자 페이지</h2>
        <div class="member_edit">
            <a href="<?php echo G5_URL;?>/page/company/edit_member.php?mb_id=<?php echo $member["mb_id"];?>"><span></span> 회원정보수정</a>
        </div>
        <div class="logout">
            <a href="javascript:fnLogout();"><span></span>로그아웃</a>
        </div>
    </header>
    <div class="view">
        <div class="company_tab">
            <ul>
                <li class="active">PM 회원 관리</li>
                <li onclick="location.href=g5_url+'/page/company/cm_member'" >CM 회원 관리</li>
            </ul>
        </div>
        <div class="company_info" >
            <p>
                * 해당 아이디 생성후 결제가 완료되어야 사용 가능합니다.
            </p>

            <form action="<?php echo G5_URL;?>/page/company/insert_member" method="post" class="company_mb_form" onsubmit="return fnAddMember();">
                <input type="hidden" name="mb_level" value="5">
                <input type="hidden" name="parent_mb_id" value="<?php echo $member["mb_id"];?>">
                <table class="view_table table_head" >
                    <tr>
                        <td class="td_center" style="background-color: #ddd">PM 추가</td>
                        <td class="td_center" style="padding:5px;"><input type="text" class="basic_input01 width100" name="mb_id" id="mb_id" placeholder="아이디"></td>
                        <td class="td_center" style="padding:5px;"><input type="text" class="basic_input01 width100" name="mb_password" id="mb_password" placeholder="패스워드"></td>
                        <td class="td_center" style="padding:5px;"><input type="submit" class="basic_btn02" style="padding:6px 10px;width:auto"  value="추가하기" ></td>
                    </tr>
                </table>
            </form>
        </div>
        <table class="view_table table_head" >
            <tr>
                <th>구분</th>
                <th colspan="">아이디</th>
                <th colspan="2">사용기간</th>
                <th >계약기간</th>
                <th colspan="3">기간</th>
                <th>선택</th>
                <th>상태</th>
                <th colspan="">관리</th>
            </tr>
            <?php for($i=0;$i<count($list);$i++){?>
            <tr class="company_mbs">
                <td class="td_center">PM <?php echo $i+1;?></td>
                <td class="td_center"><?php echo $list[$i]["mb_id"];?></td>
                <td class="td_center"><?php echo $list[$i]["start"];?></td>
                <td class="td_center"><?php echo $list[$i]["end"];?></td>
                <td class="td_center"><?php echo ($list[$i]["month"]!="-")?$list[$i]["month"]." 개월":$list[$i]["month"];?></td>
                <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="4" id="<?php echo $list[$i]["mb_id"];?>month1" ><label for="<?php echo $list[$i]["mb_id"];?>month1">1개월</label></td>
                <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="5" id="<?php echo $list[$i]["mb_id"];?>month6" ><label for="<?php echo $list[$i]["mb_id"];?>month6">6개월</label></td>
                <td class="td_center"><input type="radio" name="order_type_<?php echo $list[$i]["mb_id"];?>" value="6" id="<?php echo $list[$i]["mb_id"];?>month12" ><label for="<?php echo $list[$i]["mb_id"];?>month12">12개월</label></td>
                <td class="td_center">
                    <input type="button" value="선택해제" onclick="fnRadioCancel('<?php echo $list[$i]["mb_id"];?>')" class="basic_btn02" style="padding:6px 10px;width:auto">
                </td>
                <td class="td_center"
                ><?php if($list[$i]["payments"]=="활성"){?><input type="button" class="basic_btn02" style="padding:6px 10px;width:auto" value="연장하기" onclick="fnPayments('<?php echo $list[$i]["mb_id"];?>')"><?php }else{?>
        <input type="button" value="결제하기" class="basic_btn02" style="padding:6px 10px;width:auto" onclick="fnPayments('<?php echo $list[$i]["mb_id"];?>')"><?php }?>
                </td>
                <td class="td_center">
                    <input type="button" class="basic_btn02" style="padding:6px 10px;width:auto" value="상세보기" onclick="location.href=g5_url+'/page/company/edit_member?mb_id=<?php echo $list[$i]["mb_id"];?>'">
                </td>
            </tr>
            <?php }?>
            <?php if(count($list)==0){?>
                <tr>
                    <td colspan="11" class="td_center">등록된 부계정이 없습니다.</td>
                </tr>
            <?php }else{?>
                <tr class="blod_tr">
                    <td colspan="4" class="td_center">합계</td>
                    <td  class="td_center">계약기간</td>
                    <td colspan="3" style="text-align: right;padding:5px;"><span class="totalMonth">0</span> 개월</td>
                    <td class="td_center">
                        총 결제 금액 :
                    </td>
                    <td colspan="2" style="text-align: right;padding:5px;">
                        <span class="totalPrice">0</span> 원
                    </td>
                </tr>
            <?php }?>
        </table>
        <div style="text-align: right;padding:30px 0 0;">
            <input type="hidden" name="payments" id="payments" value="">
            <input type="hidden" name="amount" id="amount" value="">
            <input type="button" value="문의하기" class="basic_btn01" onclick="location.href=g5_url+'/page/board/inquiry'">
            <input type="button" value="선택일괄결제" class="basic_btn01" onclick="fnAllPayment();">
        </div>
    </div>
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
            case "4":
                amount = 473000;
                break;
            case "5":
                amount = 2750000;
                break;
            case "6":
                amount = 4620000;
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

       if(Number($("#amount").val()) > 10000000){
           $.ajax({
               url:g5_url+'/page/ajax/ajax.alert.php',
               method:"post",
               data:{title:"결제금액 초과시 결제방법",msg:"<h2>결제한도</h2><br>카드 : 10,000,000원 / 1회<br>이체 : 2,000,000원 / 1일<br><br><h2>결제방법</h2><br>카드 : 10,000,000원 이내로 분할 결제 (1일 제한 없음)<br>이체 : 2,000,000원 이내로 분할 이체(1일 제한 있음)<br>기타 직접이체 및 전자세금계산서 발금 등은 아래의 문의하기로 남겨주시면 담당자가 신속히 연락드리겠습니다.",link:g5_url+'/page/board/inquiry',btns:"문의하기",payments:payments,od_type:"pm"}
           }).done(function(data){
               fnShowModal(data);
           });
           return false;
       }

       $.ajax({
           url:g5_url+'/page/ajax/ajax.company_sub_member.php',
           method:"post",
           data:{payments:payments,type:"pm"}
       }).done(function(data){
           fnShowModal(data);
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
                   case "4":
                       month = 1;
                       price = 473000;
                       break;
                   case "5":
                       month = 6;
                       price = 2750000;
                       break;
                   case "6":
                       month = 12;
                       price = 4620000;
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
       $("#amount").val(totalPrice);
       $("#payments").val(payments);
   }
   </script>
<?php
include_once (G5_PATH."/tail.com.php");
