<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");


$sql = "select count(*)as cnt from `cmap_inquiry` where `inquiry_type` <> '결제문의'";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_inquiry` where `inquiry_type` <> '결제문의' order by insert_date desc limit {$start},{$rows}";
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
            <h2>제안관리</h2>
        </div>
        <div class="admin_content">
            <div class="edit_content">
                <table class="image_table">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="5%">
                        <col width="*">
                        <col width="12%">
                        <col width="7%">
                        <col width="10%">
                    </colgroup>
                    <tr>
                        <th>번호</th>
                        <th>등록자</th>
                        <th>문의타입</th>
                        <th>내용</th>
                        <th>등록일</th>
                        <th>파일</th>
                        <th>관리</th>
                    </tr>
                    <?php for($i=0;$i<count($list);$i++){
                        if($list[$i]["filename"]){
                            $file = "<img src='".G5_IMG_URL."/ic_board_attach.svg' alt='{$list[$i]['ori_filename']}' style='width:40px;'>";
                        }else{
                            $file="";
                        }

                        $date = explode(" ",$list[$i]["insert_date"]);
                        if($date == date("Y-m-d")){
                            $dates = $date[1];
                        }else{
                            $dates = $date[0];
                        }
                        ?>
                    <tr>
                        <td class="td_center"><?php echo $list[$i]["num"];?></td>
                        <td class="td_center"><?php echo $list[$i]["name"];?></td>
                        <td class="td_center"><?php echo $list[$i]["inquiry_type"];?></td>
                        <td style="padding:10px;"><?php echo $list[$i]["content"];?></td>
                        <td class="td_center"><?php echo $dates;?></td>
                        <td class="td_center"><?php echo $file;?></td>
                        <td class="td_center">
                            <input type="button" value="답변" style="display:inline-block;position: relative;right:auto;top:auto;margin:0" onclick="location.href=g5_url+'/admin/inquiry_view?id=<?php echo $list[$i]["id"];?>&page=<?php echo $page;?>'">
                            <input type="button" value="삭제" style="display:inline-block;position: relative;right:auto;top:auto;margin:0;background-color:red;">
                        </td>
                    </tr>
                    <?php }
                    if(count($list)==0){?>
                        <tr>
                            <td class="td_center" colspan="7">목록이 없습니다.</td>
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
                                <li class="prev"><a href="<?php echo G5_URL."/admin/inquiry?page=".($page-1); ?>">&lt;</a></li>
                            <?php } ?>
                            <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                                <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php echo G5_URL."/admin/inquiry?page=".$i; ?>"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <?php if($page<$total_page){?>
                                <li class="next"><a href="<?php echo G5_URL."/admin/inquiry?page=".($page+1); ?>">&gt;</a></li>
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
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
