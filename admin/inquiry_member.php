<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

if($id == "" || !$id){
    alert("문의사항 정보를 가져올 수 없습니다.");
}

$sql = "select * from `cmap_inquiry` where id = '{$id}'";
$view = sql_fetch($sql);
$inquity_mb = get_member($view["mb_id"]);
$members = explode(",",$view["payments_mb_id"]);
?>

<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>결제문의 <?php echo "[".$inquity_mb["mb_name"]."]";?>님의 결제문의</h2>
            <div class="newwin_btn_top">
                <input type="button" onclick="location.href=g5_url+'/admin/inquiry_payment.php?page=<?php echo $page;?>'" class="newwin_btn" value="목록">
            </div>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <table class="image_table">
                    <colgroup>
                        <col width="10%">
                        <col width="20%">
                        <col width="15%">
                        <col width="15%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>구분</th>
                        <th>신청개월</th>
                        <th>금액</th>
                        <th>상태</th>
                        <th>승인</th>
                        <th>반려</th>
                    </tr>
                    <?php for($i=0;$i<count($members);$i++){
                            $data = explode("||",$members[$i]);
                            $mb = get_member($data[0]);
                            if($mb["mb_level"]==3){
                                $level = "CM";
                                if($data[1]==1){
                                    $price = 99000;
                                }else if($date[1]==6){
                                    $price = 528000;
                                }else {
                                    $price = 924000;
                                }
                            }else if($mb["mb_level"]==5){
                                $level = "PM";
                                if($data[1]==1){
                                    $price = 473000;
                                }else if($date[1]==6){
                                    $price = 2750000;
                                }else {
                                    $price = 4620000;
                                }
                            }
                            if($data[2]==1){
                                $status = "승인";
                            }else if($data[2]==2){
                                $status = "반려";
                            }else{
                                $status = "미승인";
                                $st_chk = true;
                            }
                        ?>
                        <tr>
                            <td class="td_center"><?php echo $i+1;?></td>
                            <td class="td_center"><?php echo $mb["mb_id"];?></td>
                            <td class="td_center"><?php echo $level;?></td>
                            <td class="td_center"><?php echo $data[1];?>월</td>
                            <td class="td_center"><?php echo number_format($price);?> 원</td>
                            <td class="td_center"><?php echo $status;?></td>
                            <td class="td_center">
                                <?php if($status == "미승인"){?>
                                <input type="button" value="승인" style="display:inline-block;position: relative;right:auto;top:auto;margin:0" onclick="fnMemberConfirm('<?php echo $mb["mb_id"];?>','<?php echo $data[1];?>','<?php echo $id;?>','<?php echo $view["payments_mb_id"];?>')">
                                <?php }?>
                            </td>
                            <td class="td_center">
                                <?php if($status == "미승인"){?>
                                <input type="button" value="반려" style="display:inline-block;position: relative;right:auto;top:auto;margin:0;background-color:red;" onclick="fnMemberCancel('<?php echo $mb["mb_id"];?>','<?php echo $view["payments_mb_id"];?>','<?php echo $id;?>')">
                                <?php }?>
                            </td>
                        </tr>
                    <?php }
                    if(count($members)==0){?>
                        <tr>
                            <td class="td_center" colspan="7">목록이 없습니다.</td>ㄴ
                        </tr>
                    <?php }?>
                </table>
                <?php if($st_chk==true){?>
                <div class="" style="text-align: right;margin-top:20px;">
                    <input type="button" value="전체승인" class="admin_submit" onclick="fnMemberConfirmAll('<?php echo $view["payments_mb_id"];?>','<?php echo $id;?>');">
                    <input type="button" value="전체반려" class="admin_submit btn_cancel" onclick="fnMemberCancelAll('<?php echo $view["payments_mb_id"];?>','<?php echo $id;?>');">
                </div>
                <?php }?>
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
                                <li class="prev"><a href="<?php echo G5_URL."/admin/inquiry_payment?page=".($page-1); ?>">&lt;</a></li>
                            <?php } ?>
                            <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                                <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/inquiry_payment?page=".$i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <?php if($page<$total_page){?>
                                <li class="next"><a href="<?php echo G5_URL."/admin/inquiry_payment?page=".($page+1); ?>">&gt;</a></li>
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
    function fnMemberConfirm(mb_id,month,id,members){
        if(confirm("해당 회원의 결제를 승인하시겠습니까?")){
            location.href=g5_url+'/admin/inquiry_member_update?mb_id='+mb_id+'&month='+month+"&id="+id+"&members="+members;
        }
    }
    function fnMemberCancel(mb_id,members,id){
        if(confirm("해당 회원의 결제 상태를 반려하시겠습니까?")){
            location.href=g5_url+'/admin/inquiry_member_cancel?mb_id='+mb_id+'&members='+members+'&id='+id;
        }
    }
    function fnMemberConfirmAll(members,id){
        if(confirm("해당 미승인 상태 회원의 결제를 승인하시겠습니까?")){
            location.href=g5_url+'/admin/inquiry_member_update_all?members='+members+"&id="+id;
        }
    }
    function fnMemberCancelAll(members,id){
        if(confirm("해당 미승인 상태 회원의 결제를 반려하시겠습니까?")){
            location.href=g5_url+'/admin/inquiry_member_cancel_all?members='+members+"&id="+id;
        }
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
