<?php
include_once ("../../common.php");

if(!$is_member){
    goto_url(G5_BBS_URL."/login?url=".G5_URL."/page/mylocation/mylocation");
}

$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/head.php");

$sql ="select *,count(id) as cnt from `cmap_my_construct_temp` where mb_id = '{$member["mb_id"]}' and status = 0 order by id desc limit 0, 1";
$chkTemp = sql_fetch($sql);
if($chkTemp["cnt"] > 0 && $chk == false){
    confirm("등록 중이던 현장이 있습니다. 계속 등록하시겠습니까?",G5_URL.'/page/mylocation/mylocation_step1?id='.$chkTemp["id"],'./mylocation?chk=false');

}

$sql = "select count(*) as cnt from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or members in ('{$member['mb_id']}')) and status = 0";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or members in ('{$member['mb_id']}')) and status = 0 order by `insert_date` desc limit {$start},{$rows} ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $mycons[] = $row;
}
?>
<div class="width-fixed" style="margin-bottom: 60px;">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout"><span></span>로그아웃</a>
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
                        <th style="width:10%">구분</th>
                        <th style="width:10%">등록자</th>
                        <th>건설현장</th>
                        <th style="width:10%">등록일</th>
                        <th style="width:250px;">EDIT</th>
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
                            $indate = explode(" ", $mycons[$i]["insert_date"]);
                            if(strtotime($indate[0]) == strtotime(date("Y-m-d"))){
                                $insert_date = $indate[1];
                            }else{
                                $insert_date = $indate[0];
                            }
                            ?>
                            <tr>
                                <td class="td_center"><?php echo $type;?></td>
                                <td class="td_center"><?php echo $mb["mb_name"];?></td>
                                <td><?php echo $mycons[$i]["cmap_name"];?></td>
                                <td class="td_center"><?php echo $insert_date;?></td>
                                <td class="td_center">
                                    <input type="button" value="상세보기" class="basic_btn02 width30" style="padding:7px 0" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?id=<?php echo $mycons[$i]["id"];?>';">
                                    <input type="button" value="삭제하기" class="basic_btn02 width30 <?php if($type=="사용현장"){?>disabled<?php }?>"  style="padding:7px 0" <?php if($type=="사용현장"){?>disabled<?php }?> onclick="fnDelete('/page/mylocation/mylocation_delete?id=<?php echo $mycons[$i]["id"];?>')">
                                </td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
            <div class="search_box">
                <h2>현장검색</h2>
                <div>
                    <input type="text" name="stx" id="stx" class="basic_input01" placeholder="현장명 또는 용역명" onkeyup="fnMylocSearchStx()"><input type="button" class="basic_btn01" value="검색" onclick="fnMylocSearch()">
                </div>
            </div>
            <div class="search_loc">
                <h3><i></i> 검색된 현장</h3>
                <div class="myloc_btns">
                    <input type="button" value="현장개설" onclick="fnConstConfirm();" class="basic_btn03">
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>등록자</th>
                        <th>건설현장</th>
                        <th>등록일</th>
                        <th>EDIT</th>
                    </tr>
                    </thead>
                    <tbody class="seach_list">
                    <tr >
                        <td colspan="4" class="td_center">
                            검색해 주세요.
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
    function fnMylocSearchStx(){
        if(window.event.keyCode == 13){
            fnMylocSearch();
        }
    }
    function fnMylocSearch(){
        var stx = $("#stx").val();
        if(stx==''){
            alert("현장명 또는 용역명을 입력해주세요.");
            return false;
        }
        $.ajax({
            url:g5_url+"/page/ajax/ajax.mylocation_search.php",
            method:"post",
            data:{stx:stx}
        }).done(function(data){
            console.log(data);
            $(".seach_list").html(data);
        });
    }
    function fnDelete(url){
        if(confirm("해당 건설현장을 삭제하시겠습니까?\n삭제시 다시 복구 할 수 없습니다.\n신중히 선택해 주시기 바랍니다.")){
            location.href=g5_url+url;
        }
    }
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
