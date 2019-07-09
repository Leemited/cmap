<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
@ini_set('memory_limit', '-1');
$menu_name = "사용자 가이드";

$sql = "select * from `cmap_menu` where LENGTH(menu_code) = 2 and menu_status = 0  order by menu_order ";
$res = sql_query($sql);
while ($row = sql_fetch_array($res)) {
    $guidemenu[] = $row;
}

if($me_code) {
    $sql = "select * from `cmap_menu` where SUBSTRING(menu_code,1,2) like '{$me_code}%' and menu_code != '{$me_code}' and menu_status = 0  order by menu_order ";
    $res = sql_query($sql);
    $i=0;
    while($row = sql_fetch_array($res)){
        if($row["menu_name"]=="건설사업관리/체크리스트"){
            $sql = "select * from `cmap_depth1` where me_code like '{$me_code}%' order by id asc";
            $res2 = sql_query($sql);
            $a=0;
            while($row2 = sql_fetch_array($res2)){
                $list["depth1"][$a] = $row2;
                $list["depth1"][$a]["menu_name"] = $row2["depth_name"];
                $sql = "select *,c.id as id,c.pk_id as pk_id from `cmap_depth2` as c left join `cmap_menu_desc` as b on c.pk_id = b.pk_id where c.depth1_id = '{$row2["id"]}' order by c.id asc";
                $ress = sql_query($sql);
                $l = 0;
                $cnt = 0;
                while ($rows = sql_fetch_array($ress)) {
                    $list["depth1"][$a]["depth2"][$l] = $rows;
                    $list["depth1"][$a]["depth2"][$l]["depth"] = 2;
                    $l++;
                }
                $list["cnt"][$i] = $l;
                $a++;
            }
        }else {
            $list["depth1"][$i] = $row;
            $sql = "select *,c.id as id,c.pk_id as pk_id,b.menu_desc as menu_desc from `cmap_depth1` as c left join `cmap_menu_desc` as b on c.pk_id = b.pk_id  where c.me_code like '{$row["menu_code"]}%' order by c.id asc";
            $ress = sql_query($sql);
            $l = 0;
            while ($rows = sql_fetch_array($ress)) {
                $list["depth1"][$i]["depth2"][$l] = $rows;
                $list["depth1"][$i]["depth2"][$l]["depth"] = 1;
                $l++;
            }
            $list["cnt"][$i] = $l;
            $i++;
        }
    }
}else{
    $sql = "select * from `cmap_menu_desc` where isnull(pk_id) and isnull(depth) and isnull(depth_id)";
    $res = sql_query($sql);
    while($row = sql_fetch_array($res)){
        $list[$row["menu_id"]] = $row;
    }
}
?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2><?php echo $menu_name;?></h2>
        </div>
        <div class="admin_tab">
            <ul>
                <li <?php if($me_code==''){?>class="active"<?php }?> onclick="location.href=g5_url+'/admin/navigator'">일반항목</li>
                <?php for ($i=0;$i<count($guidemenu);$i++){?>
                    <li <?php if($me_code==$guidemenu[$i]["menu_code"]){?>class="active"<?php }?> onclick="location.href=g5_url+'/admin/navigator?me_code=<?php echo $guidemenu[$i]["menu_code"];?>'"><?php echo $guidemenu[$i]["menu_name"];?></li>
                <?php }?>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <!--<div class="search_box">
                <form action="">
                    <input type="hidden" name="me_name" id="me_name" value="<?php //echo $menu_name;?>">
                    <input type="hidden" name="menu_code" id="me_code" value="<?php //echo $incode;?>">
                    <select name="cmap_depth1" id="cmap_depth1">
                        <option value="">공사명</option>
                        <?php //for($i=0;$i<count($option1);$i++){?>
                            <option value="<?php //echo $option1[$i]['id'];?>" <?php //if($cmap_depth1==$option1[$i]["id"]){?>selected<?php //}?>><?php //echo $option1[$i]['depth_name'];?></option>
                        <?php //}?>
                    </select>
                    <select name="cmap_depth2" id="cmap_depth2">
                        <option value="">작업</option>
                    </select>
                    <select name="cmap_depth3" id="cmap_depth3">
                        <option value="">구분</option>
                    </select>
                    <select name="cmap_depth4" id="cmap_depth4">
                        <option value="">항목</option>
                    </select>
                    <input type="submit" value="검색" class="admin_submit">
                </form>
            </div>-->
            <div class="edit_content">
                <table id="edit_table" >
                    <thead>
                    <tr>
                        <th style="width:10%">항목</th>
                        <?php if($me_code!=10){?>
                        <th style="width:12%">분야</th>
                        <?php }?>
                        <th style="width:auto">내용</th>
                        <th style="width:100px;">관리</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($me_code){
                    for($i=0;$i<count($list["depth1"]);$i++){
                        $a=0;
                        ?>
                    <tr class="start_tr <?php if($i==0){?>zero<?php }?>">
                        <td class="depth1 td_center" rowspan="<?php echo $list["cnt"][$i];?>">
                            <?php echo $list["depth1"][$i]["menu_name"];?>
                        </td>
                        <?php for($j=0;$j<count($list["depth1"][$i]["depth2"]);$j++){

                            $a++;
                            if($j!=0){ if(($j+1)==$list["cnt"][$i]){echo "<tr class='last_tr'>";}else{echo "<tr>";}}
                            ?>
                            <?php if($me_code!=10){?>
                            <td class="depth2 td_center">
                                <?php echo $list["depth1"][$i]["depth2"][$j]["depth_name"];?>
                            </td>
                            <?php }?>
                            <td class="depth3">
                                <input type="text" value="<?php echo $list["depth1"][$i]["depth2"][$j]["menu_desc"];?>" id="depth_desc_<?php echo $list["depth1"][$i]["depth2"][$j]["pk_id"]?>" >
                            </td>
                            <td class="td_center">
                                <input type="button" value="수정" class="" onclick="fnGuideUp('<?php echo $list["depth1"][$i]["depth2"][$j]["pk_id"];?>','<?php echo $list["depth1"][$i]["depth2"][$j]["id"];?>','<?php echo $list["depth1"][$i]["depth2"][$j]["depth"];?>','<?php echo str_replace("\r\n","",trim($list["depth1"][$i]["depth2"][$j]["depth_name"]));?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto">
                            </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                    <?php }else{?>
                        <tr>
                            <td rowspan="6">마이페이지</td>
                            <td>홈페이지 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_home"]["menu_desc"];?>" id="depth_desc_home"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('홈페이지 설정','depth_desc_home','<?php echo $list["depth_desc_home"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>퀵메뉴 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_quick"]["menu_desc"];?>" id="depth_desc_quick"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('퀵메뉴','depth_desc_quick','<?php echo $list["depth_desc_quick"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>네비게이터 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_navi"]["menu_desc"];?>" id="depth_desc_navi"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('네비게이터','depth_desc_navi','<?php echo $list["depth_desc_navi"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>사용자가이드 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_guide"]["menu_desc"];?>" id="depth_desc_guide"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('사용자가이드','depth_desc_guide','<?php echo $list["depth_desc_guide"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>개인정보 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_edit"]["menu_desc"];?>" id="depth_desc_edit"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('개인정보','depth_desc_edit','<?php echo $list["depth_desc_edit"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>회원탈퇴 설정</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_leave"]["menu_desc"];?>" id="depth_desc_leave"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('회원탈퇴','depth_desc_leave','<?php echo $list["depth_desc_leave"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td rowspan="4">게시판</td>
                            <td>CMAP 소식</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_cmap"]["menu_desc"];?>" id="depth_desc_cmap"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('CMAP 소식','depth_desc_cmap','<?php echo $list["depth_desc_board"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>유권해석</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_boards"]["menu_desc"];?>" id="depth_desc_boards"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('유권해석','depth_desc_boards','<?php echo $list["depth_desc_board"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>

                        <tr>
                            <td>사용후기</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_review"]["menu_desc"];?>" id="depth_desc_review"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('사용후기','depth_desc_review','<?php echo $list["depth_desc_board"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>게시판</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_com"]["menu_desc"];?>" id="depth_desc_com"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('게시판','depth_desc_com','<?php echo $list["depth_desc_board"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>제안하기</td>
                            <td>제안하기</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_inquiry"]["menu_desc"];?>" id="depth_desc_inquiry"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('제안하기','depth_desc_inquiry','<?php echo $list["depth_desc_inquiry"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>스케쥴</td>
                            <td>스케쥴</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_schedule"]["menu_desc"];?>" id="depth_desc_schedule"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('스케쥴','depth_desc_schedule','<?php echo $list["depth_desc_schedule"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>천후표</td>
                            <td>천후표</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_weather"]["menu_desc"];?>" id="depth_desc_weather"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('천후표','depth_desc_weather','<?php echo $list["depth_desc_weather"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>현장관리</td>
                            <td>현장관리</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_construct"]["menu_desc"];?>" id="depth_desc_construct"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('현장관리','depth_desc_construct','<?php echo $list["depth_desc_construct"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>PM MODE</td>
                            <td>PM MODE</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_pmmode"]["menu_desc"];?>" id="depth_desc_pmmode"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('PM MODE','depth_desc_pmmode','<?php echo $list["depth_desc_pmmode"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                        <tr>
                            <td>업무연락서</td>
                            <td>업무연락서</td>
                            <td><input type="text" value="<?php echo $list["depth_desc_workmsg"]["menu_desc"];?>" id="depth_desc_workmsg"></td>
                            <td class="td_center"><input type="button" value="수정" class=""  onclick="fnGuideUp2('PM MODE','depth_desc_workmsg','<?php echo $list["depth_desc_workmsg"]["id"];?>');" style="display:block;position: relative;right:auto;top:auto;margin:0 auto"></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<div class="debug"></div>
<script>
    function fnGuideUp(pk_id,id,depth,depth_name){
        var content = $("#depth_desc_"+pk_id).val();
        if(content==""){
            alert("내용을 입력해주세요.");
            return false;
        }
        location.href=g5_url+"/admin/navigator_update?pk_id="+pk_id+"&content="+content+"&id="+id+"&depth="+depth+"&menu_name="+depth_name;
    }

    function fnGuideUp2(depth_name,content,id){
        var menu_desc = $("#"+content).val();
        if(menu_desc==""){
            alert("내용을 입력해주세요.");
            return false;
        }
        location.href=g5_url+"/admin/navigator_update?menu_desc="+menu_desc+"&menu_name="+depth_name+"&id="+id+"&menu_id="+content;
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
