<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/head.php");

$sql = "select * from `cmap_my_construct` where id = '{$id}'";
$view = sql_fetch($sql);
$inmb = get_member($view["mb_id"]);

//참여한 맴버 체크
$chk=true;
if($view["mb_id"]!=$member["mb_id"]) {
    $chk = false;
    if (strpos($view["members"], $member["mb_id"]) !== false) {
        $chk = true;
    }
}

?>
<div class="width-fixed">
    <section class="sub_sec" id="mypages">
        <header class="top">
            <h2>현장관리</h2>
            <div class="logout">
                <a href="<?php echo G5_BBS_URL;?>/logout"><span></span>로그아웃</a>
            </div>
        </header>
        <div class="mylocation">
            <div class="myloc_tab">
                <ul>
                    <li class="active">현장정보</li>
                    <li onclick="location.href=g5_url+'/page/mylocation/mylocation_view2?id=<?php echo $id;?>';">공사정보</li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="myloc">
                <h3><i></i> 공사개요</h3>
                <div class="myloc_btns">
                    <input type="button" value="목록" onclick="location.href=g5_url+'/page/mylocation/mylocation'" class="basic_btn02">
                    <?php if($chk!=false){?>
                    <input type="button" value="초대하기" onclick="fnConstInvite('<?php echo $id;?>');" class="basic_btn02">
                    <!--<input type="button" value="공유하기" onclick="fnConstShare('<?php /*echo $id;*/?>');" class="basic_btn02">-->
                    <?php if($member["mb_id"]==$view["mb_id"]){?>
                    <input type="button" value="수정" onclick="fnConstEdit('1','<?php echo $id;?>');" class="basic_btn03">
                    <?php }?>
                    <input type="button" value="복사" onclick="fnConstCopy();" class="basic_btn02">
                    <input type="button" value="복구" onclick="fnConstRestore();" class="basic_btn02">
                    <input type="button" value="저장" onclick="fnConstSave();" class="basic_btn02">
                    <input type="button" value="탈퇴" onclick="fnConstLeave();" class="basic_btn02">
                    <?php }else{?>
                    <input type="button" value="사용요청" onclick="fnConstJoin();" class="basic_btn02">
                    <?php }?>
                </div>
                <div class="info_construct1">
                    <table>
                        <tr>
                            <th colspan="2">공사정보</th>
                        </tr>
                        <tr>
                            <th>공사명</th>
                            <td class="td_center"><?php echo $view["cmap_name"];?></td>
                        </tr>
                        <tr>
                            <th>계약번호</th>
                            <td class="td_center"><?php echo $view["cmap_construct_num"];?></td>
                        </tr>
                        <tr>
                            <th>공사금액</th>
                            <td class="td_center"><?php echo $view["cmap_company"];?></td>
                        </tr>
                        <tr>
                            <th>현장대리인</th>
                            <td class="td_center"><?php echo $view["cmap_construct_name"];?></td>
                        </tr>

                    </table>
                </div>
                <div class="info_construct2">
                    <table>
                        <tr>
                            <th colspan="2">용역정보</th>
                        </tr>
                        <tr>
                            <th>공사명</th>
                            <td class="td_center"><?php echo $view["cmap_name_service"];?></td>
                        </tr>
                        <tr>
                            <th>계약번호</th>
                            <td class="td_center"><?php echo $view["cmap_construct_num_service"];?></td>
                        </tr>
                        <tr>
                            <th>공사금액</th>
                            <td class="td_center"><?php echo $view["cmap_company_service"];?></td>
                        </tr>
                        <tr>
                            <th>현장대리인</th>
                            <td class="td_center"><?php echo $view["cmap_construct_name_service"];?></td>
                        </tr>

                    </table>
                </div>
                <div class="info_construct3">
                    <table>
                        <tr>
                            <th colspan="2">사용자정보</th>
                            <th>최종저장일</th>
                            <th>복사대상</th>
                        </tr>
                        <tr>
                            <th>개설자</th>
                            <td><?php echo $inmb["mb_name"];?></td>
                            <th class="td_center"><?php echo "";?></th>
                            <td class="td_center"><input type="checkbox" name="copy[]" id="copy_<?php echo $inmb["mb_no"];?>" value="<?php echo $inmb["mb_id"];?>">
                                <label for="copy_<?php echo $inmb["mb_no"];?>"></label>
                            </td>
                        </tr>
                        <?php
                        if($view["members"] != ""){
                            $members = explode("||",$view["members"]);
                            for($i=0;$i<count($members);$i++){
                                $mb = get_member($members[$i]);
                            ?>
                                <tr>
                                    <th>사용자<?php echo $i+1;?></th>
                                    <td><?php echo $mb["mb_name"];?></td>
                                    <th class="td_center"><?php echo "";?></th>
                                    <td class="td_center">
                                        <input type="checkbox" name="copy[]" id="copy_<?php echo $mb["mb_no"];?>" value="<?php echo $mb["mb_id"];?>">
                                        <label for="copy_<?php echo $mb["mb_no"];?>"></label>
                                    </td>
                                </tr>
                            <?php
                                unset($mb);
                            }
                        }else{
                        ?>
                        <tr>
                            <td colspan="4" class="td_center">등록된 사용자가 없습니다.</td>
                        </tr>
                        <?php }?>
                    </table>
                </div>
                <div class="clear"></div>
            </div>
            <?php if($member["mb_level"]==5 && $view["pm_id"]==$member["mb_id"]){?>
            <div class="mb_area">
                pm영역
            </div>
            <?php }?>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
