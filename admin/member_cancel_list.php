<?php
include_once('./_common.php');
include_once ("./admin.head.php");

if($cate && $stx){
    switch ($cate){
        case "mb_name":
            $where = " and m.mb_name like '%{$stx}%'";
            break;
        case "mb_id":
            $where = " and m.mb_id like '%{$stx}%'";
            break;
        case "mb_hp":
            $where = " and m.mb_hp like '%{$stx}%'";
            break;
        case "cancel_date":
            $where = " and c.cancel_date like '%{$stx}%'";
            break;
    }
}

$sql = "select count(*)as cnt from `cmap_payments_cancel`";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=15;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_payments_cancel` as c left join `g5_member` as m on m.mb_id = c.mb_id order by c.id desc limit {$start},{$rows}";
$res = sql_query($sql);
$j=0;
while($row = sql_fetch_array($res)){
    $list[$j] = $row;
    $list[$j]["num"] = $total-($start)-$j;
    $j++;
}
?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>취소요청 관리</h2>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <div class="search_box">
                <select name="cate" id="cate" style="height:auto;">
                    <option value="mb_name">이름</option>
                    <option value="mb_id">아이디</option>
                    <option value="mb_hp">연락처</option>
                    <option value="cancel_date">취소요청일</option>
                </select>
                <label for=""></label><input type="text" name="stx" id="stx" placeholder="검색어입력" class="basic_input01">
            </div>
            <div class="edit_content">
                <table class="image_table">
                    <tr>
                        <th>번호</th>
                        <th>회원명</th>
                        <th>아이디</th>
                        <th>연락처</th>
                        <th>요청일</th>
                        <th>지연일</th>
                        <th>남은기간</th>
                        <th>환불금액</th>
                        <th>환불정보</th>
                        <th>상태</th>
                        <th>관리</th>
                    </tr>
                    <?php for($i=0;$i<count($list);$i++){
                            switch ($list[$i]["cancel_status"]){
                                case "0":
                                    $status = "취소요청";
                                    break;
                                case "1":
                                    $status = "취소완료";
                                    break;
                            }
                            $cancels = new DateTime($list[$i]["cancel_date"]);
                            $start_date = "";
                            $end_date = "";
                            $sql = "select * from `cmap_payments` where mb_id = '{$list[$i]["mb_id"]}' order by payment_date asc, payment_time asc";
                            $res = sql_query($sql);
                            while($row = sql_fetch_array($res)){
                                if($row["payment_start_date"] <= $list[$i]["cancel_date"] && $row["payment_end_date"] >=$list[$i]["cancel_date"]){
                                    $start = new DateTime($row["payment_start_date"]);
                                    $end = new DateTime($row["payment_end_date"]);
                                    $diff = date_diff($start,$end);
                                    $diff2 = date_diff($start,$cancels);
                                    $totals = $diff->days - $diff2->days;
                                    if($row["order_type"]==1) {
                                        $price += $totals * 3300;
                                    }
                                    if($row["order_type"]==2) {
                                        $price += $totals * 3000;
                                    }
                                    if($row["order_type"]==3) {
                                        $price += $totals * 2600;
                                    }
                                    if($row["order_type"]==4) {
                                        $price += $totals * 15700;
                                    }
                                    if($row["order_type"]==5) {
                                        $price += $totals * 15200;
                                    }
                                    if($row["order_type"]==6) {
                                        $price += $totals * 12600;
                                    }

                                }else{
                                    if($row["order_type"]==1) {
                                        $price += "99000";
                                    }
                                    if($row["order_type"]==2) {
                                        $price += "528000";
                                    }
                                    if($row["order_type"]==3) {
                                        $price += "924000";
                                    }
                                    if($row["order_type"]==4) {
                                        $price += "473000";
                                    }
                                    if($row["order_type"]==5) {
                                        $price += "2750000";
                                    }
                                    if($row["order_type"]==6) {
                                        $price += "4620000";
                                    }
                                }
                            }
                            $today = new DateTime(date('Y-m-d'));
                            $delay = date_diff($cancels,$today);
                            
                        ?>
                        <tr>
                            <td class="td_center"><?php echo $list[$i]["num"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_name"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_id"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_hp"];?></td>
                            <td class="td_center"><?php echo $list[$i]["cancel_date"]."<br>".$list[$i]["cancel_time"];?></td>
                            <td class="td_center"><?php echo ($delay->days==0)?"금일신청":$delay->days." 일";?></td>
                            <td class="td_center"><?php echo number_format($totals);?>일</td>
                            <td class="td_center"><?php echo number_format($price);?> 원</td>
                            <td class="td_center">
                                <?php if($list[$i]["cancel_status"]==0){?>
                                <?php echo "예금주 : ".$list[$i]["cancel_account"];?><br>
                                <?php echo "은행명 : ".$list[$i]["cancel_bank_name"];?><br>
                                <?php echo "계좌번호 : ".$list[$i]["cancel_bank_number"];?>
                                <?php }else{?>
                                    환불 완료로 인한 삭제
                                <?php }?>
                            </td>
                            <td class="td_center"><?php echo $status;?></td>
                            <td class="td_center">
                                <input type="button" value="환불완료" style="display:inline-block;position:relative;top:inherit;margin-top:0" onclick="fnRefundComplete('<?php echo $list[$i]["id"];?>','<?php echo $list[$i]["mb_id"];?>')">
                                <input type="button" value="삭제" style="display:inline-block;position:relative;top:inherit;margin-top:0" onclick="fnDeleteCancel('<?php echo $list[$i]["id"];?>','<?php echo $list[$i]["mb_id"];?>')">
                            </td>
                        </tr>
                    <?php }
                    if(count($list)==0){
                    ?>
                        <tr>
                            <td colspan="11" class="td_center">취소요청이 없습니다.</td>
                        </tr>
                    <?php }?>
                </table>
                <?php
                if($total_page>1){
                    $start_page=1;
                    $end_page=$total_page;
                    if($total_page>5){
                        if($total_page<($page+2)){
                            $start_page=$total_page-4;
                            $end_page=$total_page;
                        }else if($page>3){
                            $start_page=$page-2;
                            $end_page=$page+2;
                        }else{
                            $start_page=1;
                            $end_page=5;
                        }
                    }
                    ?>
                    <div class="num_list01">
                        <ul>
                            <?php if($page!=1){?>
                                <li class="prev"><a href="<?php echo G5_URL."/admin/member_list?page=".($page-1); ?>">&lt;</a></li>
                            <?php } ?>
                            <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                                <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/member_list?page=".$i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <?php if($page<$total_page){?>
                                <li class="next"><a href="<?php echo G5_URL."/admin/member_list?page=".($page+1); ?>">&gt;</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>
<script>
    function fnDeleteCancel(id,mb_id){
        if(confirm('해당 맴버쉽 취소 요청건을 삭제 하시겠습니까?\r\n삭제될 경우 해당 요청은 취소되며 계정은 정상상태로 변경됩니다.')){
            location.href=g5_url+'/admin/member_cancel_update?id='+id+'&type=del&mb_id='+mb_id;
        }
    }

    function fnRefundComplete(id,mb_id){
        if(confirm("해당 맴버쉽 취소 요청이 완료 하시겠습니까?\r\n해당 회원에 대한 환불정보 업데이트가 맞는지 확인 후 진행 바랍니다.\r\n환불 완료 처리시 환불정보는 삭제됩니다.")){
            location.href=g5_url+'/admin/member_cancel_update?id='+id+'&type=confirm&mb_id='+mb_id;
        }
    }
</script>
<?php
include_once ('./admin.tail.php');
?>
