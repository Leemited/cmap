<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/head.php");

$sql ="select *,count(id) as cnt from `cmap_my_construct_temp` where mb_id = '{$member["mb_id"]}' and status = 0 order by id desc limit 0, 1";
$chkTemp = sql_fetch($sql);
if($chkTemp["cnt"] > 0 && $chk == false){
    confirm("등록 중이던 현장이 있습니다. 계속 등록하시겠습니까?",G5_URL.'/page/mylocation/mylocation_step1.php?id='.$chkTemp["id"],'./mylocation.php?chk=false');

}

$sql = "select count(*) as cnt from `cmap_my_construct` where mb_id = '{$member["mb_id"]}' or members in ('{$member['mb_id']}')";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_my_construct` where mb_id = '{$member["mb_id"]}' or members in ('{$member['mb_id']}') order by `insert_date` desc limit {$start},{$rows} ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $mycons[] = $row;
}
?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout.php"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="mylocation">
            <div class="myloc">
                <h3><i></i> 사용중인 현장</h3>
                <div class="myloc_btns">
                    <input type="button" value="현장개설" onclick="fnConstConfirm();" class="basic_btn03">
                </div>
                <table>
                    <tr>
                        <th>구분</th>
                        <th>등록자</th>
                        <th>건설현장</th>
                        <th>등록일</th>
                        <th>EDIT</th>
                    </tr>
                    <?php if(count($mycons)==0){?>
                    <tr>
                        <td colspan="5" class="td_center">등록된 현장이 없습니다.</td>
                    </tr>
                    <?php }else {
                        for ($i = 0; $i < count($mycons); $i++) {
                            if($mycons[$i]["mb_id"]==$member["mb_id"]){
                                $type = "개설현장";
                            }else{
                                $type = "사용현장";
                            }
                            $mb = get_member($mycons[$i]["mb_id"]);
                            ?>
                            <tr>
                                <td><?php echo $type;?></td>
                                <td><?php echo $mb["name"];?></td>
                                <td><?php echo $mycons[$i]["cmap_name"];?></td>
                                <td><?php echo $mycons[$i]["insert_date"];?></td>
                                <td>
                                    <input type="button" value="상세보기" class="basic_btn01">
                                    <input type="button" value="삭제하기" class="basic_btn01 <?php if($type=="사용현장"){?>disabled<?php }?>" <?php if($type=="사용현장"){?>disabled<?php }?>>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
            <div class="search_box">
                <h2>현장검색</h2>
                <div>
                    <input type="text" name="stx" id="stx" class="basic_input01 "><input type="button" class="basic_btn01" value="검색" onclick="">
                </div>
            </div>
            <div class="search_loc">
                <h3><i></i> 검색된 현장</h3>
                <div class="myloc_btns">
                    <input type="button" value="현장개설" onclick="fnConstConfirm();" class="basic_btn03">
                </div>
                <table>
                    <tr>
                        <th>구분</th>
                        <th>등록자</th>
                        <th>건설현장</th>
                        <th>등록일</th>
                        <th>EDIT</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="td_center">
                            검색해 주세요.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
