<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$sql = " select * from `cmap_menu` where menu_depth = 0 and menu_status != 3 order by menu_order ";
$result = sql_query($sql);
while($row = sql_fetch_array($result)){
    $menus[] = $row;
}

?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>메뉴관리</h2>
        </div>
        <div class="local_desc01 local_desc">
            <p><strong>주의!</strong> 메뉴삭제시 해당 메뉴에 포함된 모든 정보가 삭제상태로 변경 됩니다.</p>
        </div>
        
        <div>
            <ul>
                <li>사용메뉴</li>
                <li onclick="location.href='<?php echo G5_URL?>/admin/menu_del_list.php'">삭제메뉴</li>
            </ul>
        </div>

        <form action="<?php echo G5_URL?>/admin/menu_list_update.php" method="post" >
            <input type="hidden" name="type" value="in">
            <input type="hidden" name="menu_depth" value="1">
            <div class="admin_content">
                <h2>대메뉴 등록</h2>
                <div id="menulist"  class="edit_content menu_list">
                    <table>
                        <colgorup>
                            <col width="*">
                            <col width="15%">
                        </colgorup>
                        <tr>
                            <th>메뉴명</th>
                            <th>관리</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="menu_name" id="menu_name" required></td>
                            <td><input type="submit" value="등록"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </form>

        <!--<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" >-->
            <input type="hidden" name="token" value="">
            <div class="admin_content">
                <h2>전체메뉴 관리</h2>
                <div id="menulist"  class="edit_content menu_list">
                    <table>
                        <colgroup>
                            <col width="*">
                            <!--<col width="*">-->
                            <col width="10%">
                            <col width="10%">
                            <col width="15%">
                        </colgroup>
                        <tr>
                            <th scope="">메뉴</th>
                            <!--<th scope="">링크</th>-->
                            <th scope="">사용여부</th>
                            <th scope="">순서</th>
                            <th scope="">관리</th>
                        </tr>
                        <tbody>
                        <?php
                        for ($i=0;$i<count($menus); $i++)
                        {

                            $bg = 'bg'.($i%2);
                            $sub_menu_class = '';

                            $search  = array('"', "'");
                            $replace = array('&#034;', '&#039;');
                            $me_name = str_replace($search, $replace, $menus[$i]['me_name']);
                            ?>
                            <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($menus[$i]['menu_code'], 0, 2); ?>">
                                <td class="td_category<?php echo $sub_menu_class; ?>" >
                                    <input type="text" name="me_name[]" value="<?php echo $menus[$i]["menu_name"]; ?>" id="me_name_<?php echo $menus[$i]["me_id"]; ?>" required class="required tbl_input full_input">
                                </td>
                                <!--<td>
                                    <label for="me_link_<?php /*echo $i; */?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                                    <input type="text" name="me_link[]" value="<?php /*echo $menus[$i]['me_link'] */?>" id="me_link_<?php /*echo $i; */?>" required class="required tbl_input full_input">
                                </td>-->
                                <td class="td_mng">
                                    <label for="me_target_<?php echo $i; ?>" class="sound_only">새창</label>
                                    <select name="menu_status[]" id="me_target_<?php echo $menus[$i]["me_id"]; ?>">
                                        <option value="1" <?php echo get_selected($menus[$i]['menu_status'], '1', true); ?>>사용안함</option>
                                        <option value="0" <?php echo get_selected($menus[$i]['menu_status'], '0', true); ?>>사용함</option>
                                    </select>
                                </td>
                                <td class="td_num">
                                    <input type="text" name="menu_order[]" value="<?php echo $menus[$i]['menu_order'] ?>" id="me_order_<?php echo $menus[$i]["me_id"]; ?>" class="tbl_input" size="5">
                                </td>
                                <td class="td_mng">
                                    <?php if(strlen($menus[$i]['menu_code']) == 2) { ?>
                                        <button type="button" class="btn_add_submenu btn_03 " onclick="fnAddMenu('<?php echo $menus[$i]['menu_name'];?>','<?php echo $menus[$i]['menu_code'];?>')">추가</button>
                                    <?php } ?>
                                    <button onclick="fnUpdateMenu('<?php echo G5_URL?>/admin/menu_list_update.php','<?php echo $menus[$i]["me_id"];?>','<?php echo $menus[$i]["menu_depth"];?>')" >수정</button>
                                    <button type="button" class="btn_del_menu btn_02" onclick="fnDelMenu('<?php echo G5_URL?>/admin/menu_list_update.php?type=del&menu_depth=0&me_id=<?php echo $menus[$i]["me_id"]?>&menu_code=<?php echo $menus[$i]["menu_code"];?>');">삭제</button>
                                </td>
                            </tr>
                            <?php
                            $sql = "select * from `cmap_menu` where menu_depth=1 and SUBSTRING(menu_code,1,2) = '{$menus[$i]["menu_code"]}' and menu_status != 3 order by menu_order";
                            $res = sql_query($sql);
                            for($j=0;$row = sql_fetch_array($res);$j++){?>
                                <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['menu_code'], 0, 2); ?>">
                                    <td class="td_category sub_menu_class" >
                                        <input type="text" name="me_name[]" value="<?php echo $row["menu_name"]; ?>" id="me_name_<?php echo $row["me_id"]; ?>" required class="required tbl_input full_input">
                                    </td>
                                    <!--<td>
                                    <label for="me_link_<?php /*echo $i; */?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                                    <input type="text" name="me_link[]" value="<?php /*echo $row['me_link'] */?>" id="me_link_<?php /*echo $i; */?>" required class="required tbl_input full_input">
                                </td>-->
                                    <td class="td_mng">
                                        <label for="me_target_<?php echo $j; ?>" class="sound_only">새창</label>
                                        <select name="menu_status[]" id="me_target_<?php echo $row["me_id"]; ?>">
                                            <option value="1" <?php echo get_selected($row['menu_status'], '1', true); ?>>사용안함</option>
                                            <option value="0" <?php echo get_selected($row['menu_status'], '0', true); ?>>사용함</option>
                                        </select>
                                    </td>
                                    <td class="td_num">
                                        <input type="text" name="menu_order[]" value="<?php echo $row['menu_order'] ?>" id="me_order_<?php echo $row["me_id"]; ?>" class="tbl_input" size="5">
                                    </td>
                                    <td class="td_mng">
                                        <?php if(strlen($row['menu_code']) == 2) { ?>
                                            <button type="button" class="btn_add_submenu btn_03 " onclick="fnAddMenu('<?php echo $row['menu_name'];?>','<?php echo $row['menu_code'];?>')">추가</button>
                                        <?php } ?>
                                        <button onclick="fnUpdateMenu('<?php echo G5_URL?>/admin/menu_list_update.php','<?php echo $row["me_id"];?>','<?php echo $row["menu_depth"];?>')" >수정</button>
                                        <button type="button" class="btn_del_menu btn_02" onclick="fnDelMenu('<?php echo G5_URL?>/admin/menu_list_update.php?type=del&menu_depth=1&me_id=<?php echo $row["me_id"]?>&menu_code=<?php echo $row["menu_code"];?>');">삭제</button>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        if ($i==0)
                            echo '<tr id="empty_menu_list"><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
                        ?>
                        </tbody>
                    </table>
                </div>

                <!--<div class="btn_fixed_top">
                    <input type="button" onclick="return add_menu();" class="btn btn_02" value="메뉴추가" >
                    <input type="submit" name="act_button" value="확인" class="btn_submit btn ">
                </div>-->
            </div>
        <!-- </form> -->
    </section>
</div>
<script>
    function fnUpdateMenu(url,id,depth){
        var cate_name = $("#me_name_"+id).val();
        var order = $("#me_order_"+id).val();
        var menu_status = $("#me_target_"+id).val();
        location.href=url+"?me_id="+id+"&type=up&cate_name="+cate_name+"&menu_depth="+depth+"&menu_order="+order+"&menu_status="+menu_status;
    }

    function fnDelMenu(url){
        if(confirm("해당 메뉴를 삭제할 경우 해당 메뉴에 등록된 자료 와 사용자 화면 및 설정에 영향을 줄 수 있습니다.\r\n삭제하시겠습니까?")) {
            location.href = url;
        }
    }

    function fnAddMenu(menu,code){
        $(".submenu_modal .menu_code").val(code);
        menu_dialog.dialog({title:menu+" 서브메뉴 추가"});
        menu_dialog.dialog("open", "img_modal", true);
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
