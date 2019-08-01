<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
$menu_id = "depth_desc_construct";
$mypage = true;
include_once (G5_PATH."/head.php");

$sql = "select * from `cmap_my_construct` where id = '{$constid}'";
$view = sql_fetch($sql);
$cate = explode("``",$view["pk_ids"]);
$cate_actives = explode("``",$view["pk_ids_actives"]);
$cate_start = explode("``",$view["start_date"]);
$cate_end = explode("``",$view["end_date"]);

//참여한 맴버 체크
$chk=true;
if($view["mb_id"]!=$member["mb_id"]) {
    $chk = false;
    if (strpos($view["members"], $member["mb_id"]) !== false) {
        $chk = true;
    }
}

if($member["mb_level"]==5){
    $sql = "select * from `cmap_my_pmmode_set` where mb_id = '{$member["mb_id"]}' and const_id = '{$constid}'";
    $pmSet = sql_fetch($sql);

    $mng_members = explode(",",$view["manager_mb_id"]);
    $mmchk = false;
    for($i = 0 ; $i<count($mng_members);$i++){
        if($mng_members[$i]==$member["mb_id"]){
            $mmchk = true;
        }
    }
}

?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="javascript:fnLogout();"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="mylocation">
            <div class="myloc_tab">
                <ul>
                    <li onclick="location.href=g5_url+'/page/mylocation/mylocation_view?constid=<?php echo $constid;?>';">현장정보</li>
                    <li class="active">공사정보</li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="myloc">
                <h3><i></i> 공종현황</h3>
                <div class="myloc_btns">
                    <?php if($member["mb_level"]==5){?>
                        <input type="button" value="목록" onclick="location.href=g5_url+'/page/manager/pm_construct'" class="basic_btn02">
                    <?php }else{?>
                        <input type="button" value="목록" onclick="location.href=g5_url+'/page/mylocation/mylocation'" class="basic_btn02">
                    <?php }?>
                    <?php if($chk!=false && $member["mb_level"]<5){?>
                        <input type="button" value="초대하기" onclick="fnConstInvite('<?php echo $constid;?>');" class="basic_btn02">
                        <!--<input type="button" value="공유하기" onclick="fnConstShare('<?php /*echo $id;*/?>');" class="basic_btn02">-->
                        <?php if($member["mb_id"]==$view["mb_id"]){?>
                            <input type="button" value="수정" onclick="fnConstEdit('2','<?php echo $constid;?>');" class="basic_btn03">
                        <?php }?>
                        <input type="button" value="복사" onclick="fnConstCopy('<?php echo $constid;?>');" class="basic_btn02">
                        <input type="button" value="복구" onclick="fnConstRestore('<?php echo $member["mb_id"];?>','<?php echo $constid;?>');" class="basic_btn02">
                        <input type="button" value="저장" onclick="fnConstSave('<?php echo G5_URL;?>/page/mylocation/save_mylocation_set?mb_id=<?php echo $member["mb_id"];?>&constid=<?php echo $constid;?>');" class="basic_btn02">
                        <?php if($member["mb_id"]!=$view["mb_id"]){?>
                            <input type="button" value="탈퇴" onclick="fnConstLeave('<?php echo $member["mb_id"]?>','<?php echo $constid;?>');" class="basic_btn02">
                        <?php }?>
                    <?php }else{?>
                        <?php if($member["mb_level"]==5){
                            if($mmchk==false){
                                ?>
                                <input type="button" value="PM요청" onclick="fnConstJoinPm('<?php echo $view["mb_id"];?>','<?php echo $constid;?>');" class="basic_btn02">
                            <?php }
                        }else if($member["mb_level"]<5){?>
                            <input type="button" value="사용요청" onclick="fnConstJoin('<?php echo $member["mb_id"];?>','<?php echo $constid;?>');" class="basic_btn02">
                        <?php }?>
                    <?php }?>
                </div>
                <div class="">
                    <table class="write_date1">
                        <tr>
                            <th>계약상 착공일</th>
                            <td>
                                <?php echo ($view["cmap_construct_start_temp"])?$view["cmap_construct_start_temp"]:date("Y-m-d");?>
                            </td>
                        </tr>
                    </table>
                    <table class="write_date2">
                        <tr>
                            <th>실 착공일</th>
                            <td>
                                <?php echo ($view["cmap_construct_start"])?$view["cmap_construct_start"]:date("Y-m-d");?>
                            </td>
                        </tr>
                    </table>
                    <table class="write_date3">
                        <tr>
                            <th>준공일</th>
                            <td>
                                <?php echo ($view["cmap_construct_finish"])?$view["cmap_construct_finish"]:date("Y-m-d", strtotime(" +1 month"));?>
                            </td>
                        </tr>
                    </table>
                    <table class="write_date4">
                        <tr>
                            <th>입주예정일</th>
                            <td>
                                <?php echo ($view["cmap_construct_inmove"])?$view["cmap_construct_inmove"]:date("Y-m-d", strtotime(" +1 month"));?>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <th>공종명</th>
                            <th>세부 공종명</th>
                            <th>착공일자</th>
                            <th>준공일자</th>
                        </tr>
                        <?php for($i=0;$i<count($cate);$i++){
                            if($cate_actives[$i]=="0"){continue;}
                            $sql = "select * from `cmap_depth1` as c left join `cmap_menu` as m on m.menu_code = c.me_code where c.pk_id = '{$cate[$i]}'";
                            $depth = sql_fetch($sql);
                            ?>
                            <tr>
                                <td class="td_center depth3"><?php echo $depth["menu_name"];?></td>
                                <td class="td_center"><?php echo $depth["depth_name"];?></td>
                                <td class="td_center depth3"><?php echo $cate_start[$i];?></td>
                                <td class="td_center"><?php echo $cate_end[$i];?></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
