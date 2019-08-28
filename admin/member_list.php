<?php
include_once('./_common.php');
include_once ("./admin.head.php");

$sql = "select count(*)as cnt from `g5_member`";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=15;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `g5_member` order by mb_datetime desc limit {$start},{$rows}";
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
            <h2>회원관리</h2>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <div class="search_box">
                <select name="cate" id="cate" style="height:auto;">
                    <option value="">이름</option>
                    <option value="">아이디</option>
                    <option value="">관리아이디</option>
                    <option value="">전화번호</option>
                    <option value="">회사명</option>
                </select>
                <label for=""></label><input type="text" name="stx" id="stx" placeholder="검색어입력" class="basic_input01">
            </div>
            <div class="edit_content">
                <table class="image_table">
                    <tr>
                        <th>번호</th>
                        <th>회원명</th>
                        <th>아이디</th>
                        <th>등록일</th>
                        <th>최종접속일</th>
                        <th>등급</th>
                        <th>관리아이디</th>
                        <th>관리</th>
                    </tr>
                    <?php for($i=0;$i<count($list);$i++){
                        switch ($list[$i]["mb_level"]){
                            case "1":
                                $mb_levels = "일반회원";
                                break;
                            case "2":
                                $mb_levels = "일반회원";
                                break;
                            case "3":
                                $mb_levels = "개인회원";
                                break;
                            case "5":
                                $mb_levels = "PM";
                                break;
                            case "6":
                                $mb_levels = "사업자회원";
                                break;
                        }
                        ?>
                        <tr>
                            <td class="td_center"><?php echo $list[$i]["num"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_name"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_id"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_datetime"];?></td>
                            <td class="td_center"><?php echo $list[$i]["mb_today_login"];?></td>
                            <td class="td_center"><?php echo $mb_levels;?></td>
                            <td class="td_center"><?php echo ($list[$i]["parent_mb_id"])?$list[$i]["parent_mb_id"]:"-";?></td>
                            <td class="td_center">
                                <input type="button" value="상세보기" style="display:inline-block;position:relative;top:inherit;margin-top:0" onclick="location.href=g5_url+'/admin/member_view.php?mb_id=<?php echo $list[$i]["mb_id"];?>'">
                                <input type="button" value="삭제" style="display:inline-block;position:relative;top:inherit;margin-top:0">
                            </td>
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
<?php
include_once ('./admin.tail.php');
?>
