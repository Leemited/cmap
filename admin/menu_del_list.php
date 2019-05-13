<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$sql = " select * from `cmap_menu` where menu_depth = 0 order by menu_order ";
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
        
        <div class="menu_tab">
            <ul>
                <li onclick="location.href='<?php echo G5_URL?>/admin/menu_list'">사용메뉴</li>
                <li class="active" >삭제메뉴</li>
            </ul>
        </div>
        <div class="clear"></div>
        <!--<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" >-->
            <input type="hidden" name="token" value="">
            <div class="admin_content">
                <h2>전체메뉴 관리</h2>
                <div id="menulist"  class="edit_content menu_list">
                    <table>
                        <colgroup>
                            <col width="*">
                            <!--<col width="*">-->
                            <col width="15%">
                        </colgroup>
                        <tr>
                            <th scope="">메뉴</th>
                            <th scope="">관리</th>
                        </tr>
                        <tbody>
                        <?php
                        for ($i=0;$i<count($menus); $i++)
                        {
                            $sql = "select count(*)as cnt from `cmap_menu` where menu_depth=1 and SUBSTRING(menu_code,1,2) = '{$menus[$i]["menu_code"]}' and menu_status = 3 order by menu_order";
                            $cnt = sql_fetch($sql);
                            if($cnt["cnt"]==0){
                                $chk = false;
                                if($menus[$i]["menu_status"]==0) {
                                    $chk = true;
                                    continue;
                                }
                            }else{
                                $chk = true;
                            }

                            $bg = 'bg'.($i%2);
                            $sub_menu_class = '';

                            $search  = array('"', "'");
                            $replace = array('&#034;', '&#039;');
                            $me_name = str_replace($search, $replace, $menus[$i]['me_name']);

                            ?>
                            <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($menus[$i]['menu_code'], 0, 2); ?>">
                                <td class="td_category<?php echo $sub_menu_class; ?>"  <?php if($chk==true){?> colspan="2" <?php }?> style="text-align: left;padding:10px" >
                                    <?php
                                        echo $menus[$i]["menu_name"];
                                    ?>
                                </td>
                                <!--<td>
                                    <label for="me_link_<?php /*echo $i; */?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                                    <input type="text" name="me_link[]" value="<?php /*echo $menus[$i]['me_link'] */?>" id="me_link_<?php /*echo $i; */?>" required class="required tbl_input full_input">
                                </td>-->
                                <?php if($chk==false){?>
                                <td class="td_mng">
                                        <button type="button" class="btn_add_submenu btn_03 " onclick="fnReset('<?php echo $menus[$i]["me_id"];?>','<?php echo $menus[$i]['menu_depth']?>')">복원</button>
                                </td>
                                <?php }?>
                            </tr>
                            <?php
                            $sql = "select * from `cmap_menu` where menu_depth=1 and SUBSTRING(menu_code,1,2) = '{$menus[$i]["menu_code"]}' and menu_status = 3 order by menu_order";
                            $res = sql_query($sql);
                            for($j=0;$row = sql_fetch_array($res);$j++){?>
                                <tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['menu_code'], 0, 2); ?>" >
                                    <td class="td_category sub_menu_class" style="text-align: left;"  >
                                        <?php
                                            echo $row["menu_name"];
                                        ?>
                                    </td>
                                    <!--<td>
                                    <label for="me_link_<?php /*echo $i; */?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
                                    <input type="text" name="me_link[]" value="<?php /*echo $row['me_link'] */?>" id="me_link_<?php /*echo $i; */?>" required class="required tbl_input full_input">
                                </td>-->
                                    <td class="td_mng">
                                        <button type="button" class="btn_add_submenu btn_03 " onclick="fnReset('<?php echo $row["me_id"];?>','<?php echo $row['menu_depth']?>')">복원</button>
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
    function fnReset(me_id, depth){
        location.href='menu_list_update?type=reset&me_id='+me_id+'&menu_depth='+depth;
    }
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
