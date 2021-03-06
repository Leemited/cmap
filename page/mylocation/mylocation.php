<?php
include_once ("../../common.php");

if(!$is_member){
    goto_url(G5_BBS_URL."/login?url=".G5_URL."/page/mylocation/mylocation");
}

if($member["mb_level"] == 5){
    goto_url(G5_URL."/page/manager/pm_construct");
}
$menu_id = "depth_desc_construct";
$mypage = true;
$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/_head.php");

$sql ="select *,count(id) as cnt from `cmap_my_construct_temp` where mb_id = '{$member["mb_id"]}' and status = 0 order by id desc limit 0, 1";
$chkTemp = sql_fetch($sql);
if($chkTemp["cnt"] > 0 && $chk == false){
    echo "<script>fnConstRe('".$chkTemp["id"]."', '".G5_URL."/page/mylocation/mylocation_edit?constid=".$chkTemp["id"]."&type=insert')</script>";
    //confirm("등록 중이던 현장이 있습니다. 계속 등록하시겠습니까?",G5_URL.'/page/mylocation/mylocation_step1?id='.$chkTemp["id"],'./mylocation?chk=false');
}

$sql = "select count(*) as cnt from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}') != 0) and status = 0";
$total = sql_fetch($sql);
if(!$page)
    $page=1;
$total=$total['cnt'];
$rows=10;
$start=($page-1)*$rows;
$total_page=ceil($total/$rows);

$sql = "select * from `cmap_my_construct` where (mb_id = '{$member["mb_id"]}' or instr(members,'{$member["mb_id"]}') != 0) and status = 0 order by `insert_date` desc limit {$start},{$rows} ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    if($row['members']!="" && $row["mb_id"]!=$member['mb_id']) {
        $memChk = 0;
        $mem = explode(",", $row["members"]);
        for ($i = 0; $i < count($mem); $i++) {
            if($mem[$i]==$member["mb_id"]){
                $memChk = 1;
            }
        }
        if($memChk == 0){
            $total--;
            $total_page=ceil($total/$rows);
            continue;
        }
    }
    $mycons[] = $row;
}
?>
<div class="width-fixed" style="margin-bottom: 60px;">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="javascript:fnLogout();"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="mylocation">
            <div class="myloc">
                <div class="search_box">
                    <h2>현장검색</h2>
                    <div>
                        <input type="text" name="stx" id="stx" class="basic_input01" placeholder="현장명 또는 용역명" onkeyup="fnMylocSearchStx()"><input type="button" class="basic_btn01" value="검색" onclick="fnMylocSearch()">
                        <input type="button" value="현장개설" onclick="fnConstConfirm();" class="basic_btn03">
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
                            <th style="width: 60%">건설현장</th>
                            <th style="width:10%">등록일</th>
                            <th style="width:200px"  colspan="2">EDIT</th>
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
                <h3><i></i> 사용중인 현장</h3>
                <div class="myloc_btns">

                </div>
                <table>
                    <tr>
                        <th style="width:10%">구분</th>
                        <th style="width:10%">등록자</th>
                        <th>건설현장</th>
                        <th style="width:10%">등록일</th>
                        <th style="width:250px;" colspan="2">EDIT</th>
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
                                <td class="td_center last">
                                    <input type="button" value="상세보기" class="basic_btn02 width30" style="padding:7px 0" onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $mycons[$i]["id"];?>';">
                                </td>
                                <td class="td_center last">
                                    <?php if($type=="개설현장"){?>
                                    <input type="button" value="삭제하기" class="basic_btn02 width30 <?php if($type=="사용현장"){?>disabled<?php }?>"  style="padding:7px 0" <?php if($type=="사용현장"){?>disabled<?php }?> onclick="fnDelete('/page/mylocation/mylocation_delete?constid=<?php echo $mycons[$i]["id"];?>','<?php echo $mycons[$i]["id"];?>')">
                                    <?php }?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </table>
            </div>
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
                            <li class="prev"><a href="<?php echo G5_URL."/page/mylocation/mylocation?page=".($page-1); ?>">&lt;</a></li>
                        <?php } ?>
                        <?php for($i=$start_page;$i<=$end_page;$i++){ ?>
                            <li class="<?php echo $page==$i?"active":""; ?>"><a href="<?php if($page!=$i){?><?php echo G5_URL."/page/mylocation/mylocation?page=".$i; ?><?php }else{?>#<?php }?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        <?php if($page<$total_page){?>
                            <li class="next"><a href="<?php echo G5_URL."/page/mylocation/mylocation?page=".($page+1); ?>">&gt;</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php
            }
            ?>
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
            $(".seach_list").html(data);
        });
    }
    function fnDelete(url,const_id){
        var msg = '해당 건설현장을 삭제하시겠습니까?<br>삭제 시 다시 복구 할 수 없습니다.<br>신중히 선택해 주시기 바랍니다.';
        //참여 인원 체크
        $.ajax({
            url:g5_url+'/page/ajax/ajax.get_mylocation_member.php',
            method:"post",
            data:{const_id:const_id},
            dataType:"json"
        }).done(function(data){
            if(data.msg==0){
                fnShowModal(data.modal_data);
            }else{
                $.ajax({
                    url:g5_url+"/page/modal/ajax.alert.php",
                    method:"post",
                    data:{title:"현장삭제",msg:msg,link:g5_url+url,btns:"삭제하기",type:"confirm"}
                }).done(function(data){
                    fnShowModal(data);
                });
            }
        });


        /*$.ajax({
            url:g5_url+"/page/modal/ajax.alert.php",
            method:"post",
            data:{title:"현장삭제",msg:"해당 건설현장을 삭제하시겠습니까?<br>삭제 시 다시 복구 할 수 없습니다.<br>신중히 선택해 주시기 바랍니다.",link:g5_url+url,btns:"삭제하기"}
        }).done(function(data){
            fnShowModal(data);
        });*/
    }

    function fnConstDelete(constid){
        var chk_mb_id = $(".member_list li input:checked").length;
        var mb_id = $(".member_list li input:checked").val();
        if(chk_mb_id==0){
            alert("위임할 대상을 선택해 주세요.");
            return false;
        }
        if($("#delText").val()==""){
            alert("지금삭제 문구를 입력해주세요.");
            $("#delText").focus();
            return false;
        }
        if($("#delText").val()!="지금삭제"){
            alert("삭제요청문구가 다릅니다. \r\n다시 확인해 주세요.");
            $("#delText").focus();
            return false;
        }
        location.href=g5_url+'/page/mylocation/mylocation_delete?constid='+constid+'&mb_id='+mb_id;
    }
</script>
<?php
include_once (G5_PATH."/_tail.php");
?>
