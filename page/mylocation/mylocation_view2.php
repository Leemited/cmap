<?php
include_once ("../../common.php");
$sub = "sub";
$bbody = "board";
include_once (G5_PATH."/head.php");

$sql = "select * from `cmap_my_construct` where id = '{$id}'";
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
                    <li onclick="location.href=g5_url+'/page/mylocation/mylocation_view?id=<?php echo $id;?>';">현장정보</li>
                    <li class="active">공사정보</li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="myloc">
                <h3><i></i> 공종현황</h3>
                <div class="myloc_btns">
                    <?php if($chk==true){?>
                    <input type="button" value="초대하기" onclick="fnConstInvite();" class="basic_btn02">
                        <?php if($member["mb_id"]==$view["mb_id"]){?>
                    <input type="button" value="수정" onclick="fnConstEdit('2','<?php echo $id;?>');" class="basic_btn03">
                        <?php }?>
                    <input type="button" value="복사" onclick="fnConstCopy();" class="basic_btn02">
                    <input type="button" value="복구" onclick="fnConstRestore();" class="basic_btn02">
                    <input type="button" value="저장" onclick="fnConstSave();" class="basic_btn02">
                    <?php }?>
                </div>
                <div class="">
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
