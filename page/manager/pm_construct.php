<?php
include_once ("../../common.php");
include_once (G5_PATH."/page/manager/manager_auth.php");

if(!$is_member){
    goto_url(G5_BBS_URL."/login?url=".G5_URL."/page/mylocation/mylocation");
}

if($member["mb_level"]<5){
    alert("권한이 없습니다.", G5_URL);
}

$sub = "sub";
$bbody = "board";
$mypage = true;
$menu_id = "depth_desc_pmmode";
include_once (G5_PATH."/head.php");

if($sfl && $stx){
    $where = " and  {$sfl} like '%{$stx}%'";
    $sql = "select * from `cmap_my_construct` where status = 0 and (instr(manager_mb_id,'{$member["mb_id"]}') = 0 || isnull(manager_mb_id)) and mb_id != '{$member["mb_id"]}' {$where} order by insert_date desc ";
    $res = sql_query($sql);
    while($row=sql_fetch_array($res)){
        $list[]=$row;
    }
}


$sql = "select count(*) as cnt from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}') != 0 and status = 0 and mb_id != '{$member["mb_id"]}'";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_my_construct` where instr(manager_mb_id,'{$member["mb_id"]}') != 0 and status = 0 and mb_id != '{$member["mb_id"]}' order by `insert_date` desc limit {$start},{$rows} ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $mycons[] = $row;
}
?>
<div class="width-fixed" style="margin-bottom: 60px;">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>PROJECT MANAGER</h2>
        </header>
        <div class="mylocation">
            <div class="myloc">
                <div class="search_box">
                    <h2>현장검색</h2>
                    <div style="width:60%">
                        <form action="./pm_construct" method="get">
                            <select name="sfl" id="sfl" class="basic_input01 width20" style="width:20%">
                                <option value="cmap_name">현장명</option>
                                <option value="cmap_name_service">용역업체명</option>
                                <option value="cmap_company_num">사업자등록번호</option>
                                <option value="mb_id">책임자명</option>
                                <option value="members">사용자명</option>
                                <option value="cmap_company_ceo">대표자명</option>
                            </select>
                            <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" class="basic_input01 width30" placeholder="검색어" ><input type="submit" class="basic_btn01 width20" value="검색" >
                        </form>
                    </div>
                </div>
                <div class="search_loc">
                    <h3><i></i> 검색된 현장</h3>
                    <!--<div class="myloc_btns">
                        <input type="button" value="현장개설" onclick="fnConstConfirm();" class="basic_btn03">
                    </div>-->
                    <table>
                        <colgroup>

                        </colgroup>
                        <thead>
                        <tr>
                            <th style="width:10%">등록자</th>
                            <th >현장명</th>
                            <th style="width:15%">등록일</th>
                            <th style="width:200px" colspan="2">EDIT</th>
                        </tr>
                        </thead>
                        <tbody class="seach_list">
                        <?php if(count($list)>0){
                            for($i=0;$i<count($list);$i++){
                                $mb = get_member($list[$i]["mb_id"]);
                                $dates = explode(" ",$list[$i]["insert_date"]);
                                if(date("Y-m-d") == $dates[0]){
                                    $str_date = $dates[1];
                                }else{
                                    $str_date = $dates[0];
                                }
                                $sql = "select * from `cmap_construct_invite` where send_mb_id = '{$member["mb_id"]}' and const_id = '{$list[$i]["id"]}'";
                                $chkInvite = sql_fetch($sql);

                            ?>
                            <tr>
                                <td><?php echo $mb["mb_id"];?></td>
                                <td><?php echo $list[$i]["cmap_name"];?></td>
                                <td class="td_center"><?php echo $str_date;?></td>
                                <td class="td_center" style="width:100px;">
                                    <?php if($chkInvite!=null){?>
                                        요청중
                                    <?php }else{?>
                                    <input type="button" value="PM 요청" class="basic_btn02" style="width:auto;padding:6px 10px;" onclick="fnConstJoinPm('<?php echo $list[$i]["mb_id"];?>','<?php echo $list[$i]["id"];?>');" >
                                    <?php }?>
                                </td>
                                <td class="td_center" style="width:100px;">
                                    <input type="button" value="상세보기" class="basic_btn02" style="width:auto;padding:6px 10px;" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $list[$i]["id"];?>'">
                                </td>
                            </tr>
                        <?php } 
                        }
                        if(count($list)==0){
                        ?>
                        <tr >
                            <td colspan="4" class="td_center">
                                검색해 주세요.
                            </td>
                        </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <div class="pm_const_list" style="position: relative">
                    <h3><i></i>Project Manager 관리지구 목록</h3>
                    <div class="myloc_btns" style="">
                        <input type="button" value="삭제" class="basic_btn03" onclick="pmDel()">
                    </div>
                    <table>
                        <tr>
                            <th style="width:10%">구분</th>
                            <th style="width:300px">현장명</th>
                            <th style="width:300px">계약업체</th>
                            <th style="width:10%">대표자</th>
                            <th style="width:15%">사업자등록번호</th>
                            <th style="width:10%">현장개설자</th>
                            <th style="width:10%">관리자</th>
                            <th style="width:10%">관리자변경</th>
                        </tr>
                        <?php if(count($mycons)==0){?>
                            <tr>
                                <td colspan="7" class="td_center">등록된 현장이 없습니다.</td>
                            </tr>
                        <?php }else {
                            for ($i = 0; $i < count($mycons); $i++) {
                                $mems = '';
                                $mb = get_member($mycons[$i]["mb_id"]);
                                $members = trim($mycons[$i]["manager_mb_id"]);
                                $mem = explode(",",$members);
                                if(!is_null($mycons[$i]["members"])){
                                    for($j=0;$j<count($mem);$j++){
                                        $mm = get_member($mem[$j]);
                                        $mems[] = $mm["mb_name"];
                                    }
                                    if(count($mems)==0){
                                        $inmem = "-";
                                    }else{
                                        $inmem = implode(",", $mems);
                                    }
                                }else{
                                    $inmem = "-";
                                }

                                ?>
                                <tr>
                                    <td class="td_center">
                                        <input type="checkbox" id="contruct_<?php echo $mycons[$i]["id"];?>" value="<?php echo $mycons[$i]["id"];?>">
                                        <label for="contruct_<?php echo $mycons[$i]["id"];?>"></label>
                                    </td>
                                    <td><span><?php echo $mycons[$i]["cmap_name"];?></span></td>
                                    <td><span><?php echo $mycons[$i]["cmap_company_service"];?></span></td>
                                    <td class="td_center"><?php echo $mycons[$i]["cmap_company_ceo"];?></td>
                                    <td class="td_center"><?php echo $mycons[$i]["cmap_company_num"];?></td>
                                    <td class="td_center"><?php echo $mb["mb_name"];?></td>
                                    <td class="td_center"><?php echo $inmem;?></td>
                                    <td class="td_center"><input type="button" value="상세보기" class="basic_btn02" style="padding:7px 10px;" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $mycons[$i]["id"];?>';"></td>
                                </tr>
                            <?php }
                        } ?>
                    </table>
                </div>
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

    function pmDel(){
        var chkleng = 0;
        var constid = '';
        $("input[id^=contruct]").each(function(){
           if($(this).prop("checked")==true){
               chkleng++;
               if(constid){constid+=',';}
               constid += $(this).val();
           }
        });
        if(chkleng==0){
            alert("삭제할 현장을 선택해 주세요.");
            return false;
        }

        if(confirm("선택한 현장을 삭제 하시겠습니까?")){
            location.href=g5_url+'/page/manager/pm_construct_del?constids='+constid;
        }
    }
</script>
<?php
include_once (G5_PATH."/tail.php");
?>
