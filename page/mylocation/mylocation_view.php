<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/head.php");

$sql = "select * from `cmap_my_construct` where id = '{$constid}'";
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
                    <li onclick="location.href=g5_url+'/page/mylocation/mylocation_view2?constid=<?php echo $constid;?>';">공사정보</li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="myloc">
                <h3><i></i> 공사개요</h3>
                <div class="myloc_btns">
                    <input type="button" value="목록" onclick="location.href=g5_url+'/page/mylocation/mylocation'" class="basic_btn02">
                    <?php if($chk!=false){?>
                    <input type="button" value="초대하기" onclick="fnConstInvite('<?php echo $constid;?>');" class="basic_btn02">
                    <!--<input type="button" value="공유하기" onclick="fnConstShare('<?php /*echo $id;*/?>');" class="basic_btn02">-->
                    <?php if($member["mb_id"]==$view["mb_id"]){?>
                    <input type="button" value="수정" onclick="fnConstEdit('1','<?php echo $constid;?>');" class="basic_btn03">
                    <?php }?>
                    <input type="button" value="복사" onclick="fnConstCopy('<?php echo $constid;?>');" class="basic_btn02">
                    <input type="button" value="복구" onclick="fnConstRestore('<?php echo $member["mb_id"];?>','<?php echo $constid;?>');" class="basic_btn02">
                    <input type="button" value="저장" onclick="fnConstSave('<?php echo G5_URL;?>/page/mylocation/save_mylocation_set?mb_id=<?php echo $member["mb_id"];?>&constid=<?php echo $constid;?>');" class="basic_btn02">
                        <?php if($member["mb_id"]!=$view["mb_id"]){?>
                        <input type="button" value="탈퇴" onclick="fnConstLeave('<?php echo $member["mb_id"]?>','<?php echo $constid;?>');" class="basic_btn02">
                        <?php }?>
                    <?php }else{?>
                    <input type="button" value="사용요청" onclick="fnConstJoin();" class="basic_btn02">
                    <?php }?>
                </div>
                <div class="info_construct1">
                    <table>
                        <colgroup>
                            <col width="25%">
                            <col width="*">
                        </colgroup>
                        <tr>
                            <th colspan="2">공사정보</th>
                        </tr>
                        <tr>
                            <th>공사명</th>
                            <td class="td_center" title="<?php echo $view["cmap_name"];?>"><?php echo cut_str($view["cmap_name"],22,'...');?></td>
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
                        <colgroup>
                            <col width="25%">
                            <col width="*">
                        </colgroup>
                        <tr>
                            <th colspan="2">용역정보</th>
                        </tr>
                        <tr>
                            <th>공사명</th>
                            <td class="td_center" title="<?php echo $view["cmap_name_service"];?>"><?php echo cut_str($view["cmap_name_service"],22,'...');?></td>
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
                            <th class="td_center">
                                <?php
                                $mb = get_member($view["mb_id"]);
                                $sql = "select * from `mylocation_save_log` where mb_id ='{$mb["mb_id"]}' and const_id = '{$constid}' order by save_date desc,save_time desc limit 0, 1";
                                $last_save = sql_fetch($sql);
                                 echo ($last_save["save_date"])?$last_save["save_date"]:"저장내역없음";
                                ?>
                            </th>
                            <td class="td_center">
                                <?php if($member["mb_id"]!=$view["mb_id"]){?>
                                <input type="checkbox" name="copy[]" id="copy_<?php echo $inmb["mb_no"];?>" value="<?php echo $inmb["mb_id"];?>">
                                <label for="copy_<?php echo $inmb["mb_no"];?>"></label>
                                <?php }?>
                            </td>
                        </tr>
                        <?php
                        if($view["members"] != ""){
                            $members = explode(",",$view["members"]);
                            for($i=0;$i<count($members);$i++){
                                $mb = get_member($members[$i]);
                                $sql = "select * from `mylocation_save_log` where mb_id ='{$mb["mb_id"]}' and const_id = '{$constid}' order by save_date desc,save_time desc limit 0, 1";
                                $last_save = sql_fetch($sql);
                            ?>
                                <tr>
                                    <th>사용자<?php echo $i+1;?></th>
                                    <td><?php echo $mb["mb_name"];?></td>
                                    <th class="td_center"><?php echo ($last_save["save_date"])?$last_save["save_date"]:"저장내역없음";?></th>
                                    <td class="td_center">
                                        <?php if($member["mb_id"]!=$mb["mb_id"]){?>
                                        <input type="checkbox" name="copy[]" id="copy_<?php echo $mb["mb_no"];?>" value="<?php echo $mb["mb_id"];?>">
                                        <label for="copy_<?php echo $mb["mb_no"];?>"></label>
                                        <?php }?>
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
            <?php if($member["mb_level"]==5 && $view["manager_mb_id"]==$member["mb_id"]){?>
            <div class="mb_area " >
                <h3><i></i> 책임기술자 버전 <span>(관련법령에 따라 책임기술자가 업무를 분장하고, 기술자별 업무 처리 현황을 자동으로 업데이트 합니다.)</span></h3>
                <div class="info_construct5">
                    <table>
                        <tr>
                            <th colspan="2">사용자정보</th>
                            <th >최종저장일</th>
                            <th >대상설정</th>
                        </tr>
                        <tr>
                            <th>개설자</th>
                            <td><?php echo $inmb["mb_name"];?></td>
                            <th class="td_center">
                                <?php
                                $mb = get_member($view["mb_id"]);
                                $sql = "select * from `mylocation_save_log` where mb_id ='{$mb["mb_id"]}' and const_id = '{$constid}' order by save_date desc,save_time desc limit 0, 1";
                                $last_save = sql_fetch($sql);
                                echo ($last_save["save_date"])?$last_save["save_date"]:"저장내역없음";
                                ?>
                            </th>
                            <td class="td_center">
                                <?php if($member["mb_id"]!=$view["mb_id"]){?>
                                    <input type="checkbox" name="pm_copy[]" id="pm_copy_<?php echo $inmb["mb_no"];?>" value="<?php echo $inmb["mb_id"];?>" onclick="location.href=g5_url+'/page/mylocation/mylocation_pm_dataset?mb_id='+this.value">
                                    <label for="pm_copy_<?php echo $inmb["mb_no"];?>"></label>
                                <?php }?>
                            </td>
                        </tr>
                        <?php
                        if($view["members"] != ""){
                            $members = explode(",",$view["members"]);
                            for($i=0;$i<count($members);$i++){
                                $mb = get_member($members[$i]);
                                $sql = "select * from `mylocation_save_log` where mb_id ='{$mb["mb_id"]}' and const_id = '{$constid}' order by save_date desc,save_time desc limit 0, 1";
                                $last_save = sql_fetch($sql);
                                ?>
                                <tr>
                                    <th>사용자<?php echo $i+1;?></th>
                                    <td><?php echo $mb["mb_name"];?></td>
                                    <th class="td_center"><?php echo ($last_save["save_date"])?$last_save["save_date"]:"저장내역없음";?></th>
                                    <td class="td_center">
                                        <?php if($member["mb_id"]!=$mb["mb_id"]){?>
                                            <input type="checkbox" name="pm_copy[]" id="pm_copy_<?php echo $mb["mb_no"];?>" value="<?php echo $mb["mb_id"];?>" onclick="location.href=g5_url+'/page/mylocation/mylocation_pm_dataset?mb_id='+this.value">
                                            <label for="pm_copy_<?php echo $mb["mb_no"];?>"></label>
                                        <?php }?>
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
                <!--<div class="info_construct2">
                    <table>
                        <tr>
                            <th rowspan="2" class="point">건설행정</th>
                            <th colspan="4">공사관리</th>
                            <th rowspan="2" >평가</th>
                            <th rowspan="2" class="point">공사감독가이드</th>
                        </tr>
                        <tr>
                             <th>공무관리</th>
                             <th class="point">시공관리</th>
                             <th>품질관리</th>
                             <th class="point">시공확인</th>
                        </tr>
                    </table>
                </div>-->
            </div>
            <?php }?>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/tail.php");
?>
