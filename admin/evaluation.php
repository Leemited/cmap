<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");
@ini_set('memory_limit', '-1');

if($cmap_depth1){
    $where1 = " and depth1_id = '{$cmap_depth1}'";
}

if($cmap_depth2){
    $where2 = " and depth2_id = '{$cmap_depth2}'";
}

if($cmap_depth3){
    $where3 = " and depth3_id = '{$cmap_depth3}'";
}

if($cmap_depth4){
    $where4 = " and depth4_id = '{$cmap_depth4}'";
}

if($depthcon){
    $where5 = " and a.depth_name like '%{$depthcon}%'";
}

$me_code2 = substr($menu_code,0,2);
if(strlen($menu_code)==2) {
    $sql = "select me_code from `cmap_depth1` as o left join `cmap_menu` as m on o.me_id = m.menu_code where SUBSTRING(me_code,1,2) = {$menu_code} and CHAR_LENGTH(me_code) > 2 and m.menu_status = 0 order by m.menu_order asc limit 0, 1";
    $me = sql_fetch($sql);
    $incode = $me["me_code"];
}else{
    $incode = $menu_code;
}
$sql = "select * from `cmap_menu` where SUBSTRING(menu_code,1,2) = {$me_code2} order by menu_order asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $chkme[] = $row["menu_code"];
}

$chkmecode = implode(",",$chkme);

$sql = "select * from `cmap_menu` where SUBSTRING(menu_code,1,2) = {$me_code2} and menu_status = 0 and menu_depth = 1 order by menu_order asc";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $inmenu[] = $row;
}

$sql = "select * from `cmap_depth1` where me_code = '{$incode}'" ;
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $option1[] = $row;
}

$sql = "select * from `cmap_content`";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $content[] = $row;
}

$sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth1` as a left join `cmap_content` as b on a.id = b.depth1_id where a.me_code = '{$incode}' and menu_status = 0 {$where1} group by a.id order by a.id asc ";
$res = sql_query($sql);
$i=0;
while($row=sql_fetch_array($res)){
    $j=0;
    $list[$i] = $row;
    $sql = "select *,a.id as id,COUNT(*) as cnt,a.pk_id from `cmap_depth2` as a left join `cmap_content` as b on a.id = b.depth2_id where a.depth1_id = {$row['id']} {$where2} group by a.id order by a.id asc";
    $res2 = sql_query($sql);
    while($row2 = sql_fetch_array($res2)){
        $k=0;
        $list[$i]['depth2'][$j] = $row2;
        $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth3` as a left join `cmap_content` as b on a.id = b.depth3_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']} {$where3} group by a.id order by a.id asc";
        $res3 = sql_query($sql);
        while($row3 = sql_fetch_array($res3)){
            $l=0;
            $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
            $sql = "select *,a.id as id, COUNT(*) as cnt,a.pk_id from `cmap_depth4` as a left join `cmap_content` as b on a.id = b.depth4_id where a.depth1_id = {$row['id']} and a.depth2_id = {$row2['id']} and a.depth3_id = {$row3['id']} {$where4} group by a.id order by a.id asc";
            $res4 = sql_query($sql);
            while($row4 = sql_fetch_array($res4)){
                $m=0;
                $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row4;
                $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} and depth4_id = {$row4['id']} {$where5}order by id asc";
                $res5 = sql_query($sql);
                while($row5 = sql_fetch_array($res5)){
                    $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m] = $row5;
                    $m++;
                }
                $l++;
            }
            $k++;
        }
        $j++;
    }
    $i++;
}
?>
<div id="wrap">

    <section>
        <div class="admin_title">
            <h2><?php echo $menu_name;?></h2>
            <div class="more menu">
                <input type="button" value="백업" class="edit_btn" onclick="fnBackup()">
                <input type="button" value="복구" class="edit_btn" onclick="/*fnRestore()*/">
                <input type="button" value="메뉴 수정" class="edit_btn" onclick="location.href='menu_list'">
            </div>
        </div>
        <div class="admin_tab">
            <ul>
                <?php
                for($i=0;$i<count($inmenu);$i++) {
                    if($inmenu[$i]["menu_name"]=="건설사업관리점검") {
                        $link = "evaluation";
                    }else if($inmenu[$i]["menu_name"]=="시공평가") {
                        $link = "evaluation2";
                    }else{ //if($inmenu[$i]["menu_code"]=="") {
                        $link = "evaluation3";
                    }
                        ?>
                        <li <?php if ($inmenu[$i]["menu_code"] == $menu_code || (strlen($menu_code)==2 && $i==0)){ ?>class="active"<?php } ?> onclick="location.href=g5_url+'/admin/<?php echo $link;?>?menu_code=<?php echo $inmenu[$i]["menu_code"]; ?>&menu_name=<?php echo urlencode($menu_name); ?>'">
                            <?php echo $inmenu[$i]["menu_name"]; ?>
                        </li>
                <?php } ?>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <div class="desc">
                <h4>※ 간편 등록 방법</h4>
                <ul>
                    <li>* 반드시 지정된 양식을 통해 등록 바랍니다.</li>
                    <li>* 파일은 xls(xlsx은 지원안함)파일로 등록 바랍니다.</li>
                    <li>* 유형,작업선택,구분,항목,주요확인내용,참고,기본제출일의 항목순으로 입력 바랍니다.</li>
                    <li>* 항목의 추가되거나 전체 양식이 변경되어야 한다면 개발사에 연락 바랍니다.</li>
                </ul>
                <div class="inserts">
                    <form action="<?php echo G5_URL?>/admin/construction_insert" method="post" name="insert_form" enctype="multipart/form-data">
                        <input type="hidden" name="menu_id" id="menu_id" value="1">
                        <input type="hidden" name="me_name" id="me_name" value="<?php echo $menu_name;?>">
                        <input type="hidden" name="me_code" id="me_code" value="<?php echo $incode;?>">
                        <input type="text" class="admin_infile" id="admin_infile" readonly>
                        <label for="insert_file">파일첨부</label><input type="file" name="insert_file" id="insert_file" onchange="$('#admin_infile').val(this.value)">
                        <input type="submit" value="등록" class="admin_submit">
                    </form>
                </div>
            </div>
            <div class="search_box">
                <form action="">
                    <input type="hidden" name="me_name" id="me_name" value="<?php echo $menu_name;?>">
                    <input type="hidden" name="me_code" id="me_code" value="<?php echo $incode;?>">
                    <select name="cmap_depth1" id="cmap_depth1">
                        <option value="">공사명</option>
                        <?php for($i=0;$i<count($option1);$i++){?>
                            <option value="<?php echo $option1[$i]['id'];?>" <?php if($cmap_depth1==$option1[$i]["id"]){?>selected<?php }?>><?php echo $option1[$i]['depth_name'];?></option>
                        <?php }?>
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
                    <input type="text" name="depthcon" class="admin_infile" value="<?php echo $depthcon;?>"> <input type="submit" value="검색" class="admin_submit">
                </form>
            </div>
            <div class="edit_content">
                <table id="edit_table" class="resizable">
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="12%">
                        <col width="12%">
                        <col width="*">
                        <col width="10%">
                        <col width="6%">
                    </colgroup>
                    <tbody>
                    <tr>
                        <th>대분류</th>
                        <th>중분류</th>
                        <th>소분류</th>
                        <th>항목</th>
                        <th>주요확인내용</th>
                        <th>참고</th>

                    </tr>
                    <?php
                    $depth_last = 1;
                    for($i=0;$i<count($list);$i++){
                    ?>
                    <tr id="depth1<?php echo $list[$i]['id'];?>" class="depth<?php if($list[$i]['cnt'] == 1){echo " finish_".$list[$i]["id"];}?>" >
                        <td rowspan="<?php echo $list[$i]['cnt'];?>" class="category" id="depth1_<?php echo $list[$i]['id'];?>">
                            <input type="hidden" id="me_code" value="<?php echo $list[$i]["me_code"];?>">
                            <input type="text" value="<?php echo $list[$i]['depth_name'];?>" name="depth1[]" class="input01 center" onkeyup="fnUpdate('<?php echo $list[$i]['pk_id'];?>',$(this).val(),'depth1','<?php echo $list[$i]['me_id'];?>','<?php echo $list[$i]['me_code'];?>');">
                            <!--<input type="button" value="추가" onclick="fnDepth1Add('<?php /*echo $list[$i]["id"];*/?>');">-->
                            <input type="button" value="삭제" onclick="fnDepth1Del('<?php echo $list[$i]["pk_id"];?>','<?php echo $list[$i]["id"];?>');" class="del">
                        </td>
                        <?php
                        for($j=0;$j<count($list[$i]['depth2']);$j++) {
                        //if($j!=0 && $list[$i]['cnt'] == $j+1){?>
                        <!--<tr>-->
                        <?php //}?>
                        <td rowspan="<?php echo $list[$i]['depth2'][$j]['cnt'];?>" class="category parent_<?php echo $list[$i]['id'];?>" id="depth2_<?php echo $list[$i]['depth2'][$j]['id'];?>">
                            <input type="text" value="<?php echo $list[$i]['depth2'][$j]['depth_name'];?>" name="depth2[]" class="center" onkeyup="fnUpdate('<?php echo $list[$i]['depth2'][$j]['pk_id'];?>',$(this).val(),'depth2','','');">
                            <!--<input type="button" value="추가" onclick="fnDepth2Add('<?php /*echo $list[$i]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]["id"];*/?>');">-->
                            <input type="button" value="삭제" onclick="fnDepth2Del('<?php echo $list[$i]['depth2'][$j]["pk_id"];?>','<?php echo $list[$i]['depth2'][$j]["id"];?>');" class="del">
                        </td>
                        <?php
                        for($k=0;$k<count($list[$i]['depth2'][$j]['depth3']);$k++) {
                        //if($k!=0 && $list[$i]['depth2'][$j]['cnt'] == $k+1){?>
                        <!--<tr id="depth2<?php /*echo $list[$i]["depth2"][$j]["id"];*/?>">-->
                        <?php //}?>
                        <td rowspan="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['cnt'];?>" class="category parent_<?php echo $list[$i]['id'];?>" id="depth3_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['id'];?>">
                            <input type="text"  value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth_name'];?>" name="depth3[]" class="center" onkeyup="fnUpdate('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['pk_id'];?>',$(this).val(),'depth3','','');">
                            <!--<input type="button" value="추가" onclick="fnDepth3Add('<?php /*echo $list[$i]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]["id"];*/?>');">-->
                            <input type="button" value="삭제" onclick="fnDepth3Del('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]["pk_id"];?>','<?php echo $list[$i]['depth2'][$j]['depth3'][$k]["id"];?>');" class="del">
                        </td>
                        <?php
                        for ($l = 0; $l < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4']); $l++) {
                        //if($l!=0 && $list[$i]['depth2'][$j]['depth3'][$k]['cnt'] == $l+1){?>
                        <!--<tr>-->
                        <?php //}?>
                        <td rowspan="<?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt']>1){echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['cnt'];}?>" class="category parent_<?php echo $list[$i]['id'];?>"  id="depth4_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['id'];?>">
                            <input type="text" value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth_name'];?>" name="depth4[]" class="center" onkeyup="fnUpdate('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['pk_id'];?>',$(this).val(),'depth4','','');">
                            <!--<input type="button" value="추가" onclick="fnDepth4Add('<?php /*echo $list[$i]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]["id"];*/?>','<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["id"];*/?>');">-->
                            <input type="button" value="삭제" onclick="fnDepth4Del('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["pk_id"];?>','<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]["id"];?>');" class="del">
                        </td>
                        <?php
                        for ($m = 0; $m < count($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5']); $m++) {
                        $depth_last++;
                        $fileid = "files".$list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["id"];
                        ?>

                        <td class="category parent_<?php echo $list[$i]['id'];?>" id="depth5_<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['id'];?>">
                            <input type="text" value="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['content'];?>" name="dpeth5[]" class="left" onkeyup="fnUpdate('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>',$(this).val(),'content','','');">
                            <input type="button" value="삭제" onclick="fnDepth5Del('<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["pk_id"];?>','<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["id"];?>');" class="del">
                        </td>
                        <td class="etc" id="<?php echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];?>">
                            <div id="links">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['link']){
                                    $links = array_filter(explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['link']));
                                    $linknames = array_filter(explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['linkname']));
                                    if(count($links)!=0){
                                        for($q=0;$q<count($links);$q++){ ?>
                                            <a href="<?php echo $links[$q];?>" target="_blank"><?php echo ($linknames[$q])?$linknames[$q]:"링크 ".($q+1);?></a><br>
                                        <?php }
                                    }
                                } ?>
                            </div>
                            <div id="files">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment']){?>
                                    <?php
                                    $files = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment']);
                                    $filesname = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachmentname1']);
                                    if(count($files)!=0){
                                        for($q=0;$q<count($files);$q++) {
                                            if ($files[$q] != "") {
                                                ?>
                                                <a href="javascript:fnImage('<?php echo $files[$q]; ?>');" ><?php echo ($filesname[$q])?$filesname[$q]:"첨부파일 : ".($q + 1); ?></a><br>
                                            <?php }
                                        }
                                    }
                                    ?>
                                <?php }?>
                            </div>
                            <div id="etc1">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['etc1']){
                                    $etc1 = array_filter(explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['etc1']));
                                    $etc1name = array_filter(explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['etcname1']));
                                    if(count($etc1)!=0){
                                        for($q=0;$q<count($etc1);$q++){ ?>
                                            <a href="<?php echo $etc1[$q];?>" target="_blank"><?php echo ($etc1name[$q])?$etc1name[$q]:"사례 ".($q+1);?></a><br>
                                        <?php }
                                    }
                                } ?>
                            </div>
                            <div id="files2">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment2']){?>
                                    <?php
                                    $files = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment2']);
                                    $filesname2 = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachmentname2']);
                                    if(count($files)!=0){
                                        for($q=0;$q<count($files);$q++) {
                                            if ($files[$q] != "") {
                                                ?>
                                                <a href="javascript:fnImage('<?php echo $files[$q]; ?>');" >사례파일<?php echo ($filesname2[$q])?$filesname2[$q]:"첨부파일 : ".($q + 1); ?></a><br>
                                            <?php }
                                        }
                                    }
                                    ?>
                                <?php }?>
                            </div>
                            <div id="files3">
                                <?php if($list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment3']){?>
                                    <?php
                                    $files = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachment3']);
                                    $filenames = explode("``",$list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['attachmentname3']);
                                    if(count($files)!=0){
                                        for($q=0;$q<count($files);$q++) {
                                            if ($files[$q] != "") {
                                                if($filenames[$q] != ""){
                                                    $basicname = $filenames[$q];
                                                }else{
                                                    $basicname = "감사사례".$i;
                                                }
                                                ?>
                                                <a href="javascript:fnImage('<?php echo $files[$q]; ?>');" ><?php echo $basicname; ?></a><br>
                                            <?php }
                                        }
                                    }
                                    ?>
                                <?php }?>
                            </div>
                            <input type="button" value="수정" onclick="depth5ConAdd('<?php echo $list[$i]["depth2"][$j]["depth3"][$k]["depth4"][$l]["depth5"][$m]["pk_id"];?>');" >
                        </td>
                        <!--<td>
                            <input type="text" value="<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['submit_date'];*/?>" name="depth5_report[]" class="center" id="<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]["id"];*/?>" onkeyup="fnUpdate2('<?php /*echo $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m]['pk_id'];*/?>',$(this).val())">
                        </td>-->
                    </tr>
                    <?php if($list[$i]['cnt'] >= $depth_last){?>
                    <tr class="<?php if($list[$i]['cnt'] == $depth_last){echo "finish_".$list[$i]["id"];}?>">
                        <?php }
                        }
                        }
                        }
                        } $depth_last = 1;
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<div class="debug"></div>
<script>
    $(function(){
        $("select[id^=cmap_depth]").each(function(e){
            if(e!=3) {
                $(this).change(function () {
                    var id = $(this).val();
                    getOption(id, e ,'')
                });
            }
        });
        <?php if($cmap_depth1){ ?> // depth2
        getOption("<?php echo $cmap_depth1;?>",0, '<?php echo $cmap_depth2;?>');
        <?php } if($cmap_depth2){ ?> // depth3
        getOption("<?php echo $cmap_depth2;?>",1,'<?php echo $cmap_depth3;?>');
        <?php } if($cmap_depth3){ ?> // depth4
        getOption("<?php echo $cmap_depth3;?>",2,'<?php echo $cmap_depth4;?>');
        <?php } ?>
    });

    $(document).on("dblclick", ".admin_content .edit_content table td.category",function(){
        var index = $(this).attr("id");
        var depth = index.split("_");
        var depthnum = depth[0].replace("depth","");
        var id = depth[1];
        var add_id = Number(id) + 1;
        var me_code = "<?php echo $incode;?>";
        if(me_code==""){
            alert("카테고리를 선택해주세요!");
            return false;
        }
        if(depthnum==1){
            var currentRow = $(this).parent().index();
            var finish = $(this).parent().attr("class");
            var rowspan = $(this).attr("rowspan");
            var trnum = $("#edit_table tr").length;
            //console.log(currentRow + " // " + rowspan + " // " + trnum);
            if(Number(currentRow) + Number(rowspan) == Number(trnum)){
                var parentTr = $("#edit_table tr").eq(trnum - 1);
            }else{
                if(Number(rowspan)>1) {
                    var parentTr = $("#edit_table tr").eq(Number(currentRow) + Number(rowspan));
                }else{
                    var parentTr = $("#edit_table tr").eq(currentRow);
                }
            }

            $.ajax({
                url:g5_url+"/admin/ajax.depth_add.php",
                type:"POST",
                data:{prent_id:id,id:add_id,depth:1,me_code:me_code},
                dataType:"json"
            }).done(function(data){
                if(data.status==1){
                    location.reload();
                    /*var tr = document.createElement("tr");
                    tr.setAttribute("id","depth1"+add_id);
                    tr.setAttribute("class","depth finish_"+add_id);

                    //depth1
                    var td = document.createElement("td");
                    td.setAttribute("rowspan","1");
                    td.setAttribute("class","category");
                    td.setAttribute("id","depth1_"+add_id);
                    var depth1_input = document.createElement("input");
                    depth1_input.setAttribute("name","depth1[]");
                    depth1_input.setAttribute("type","text");
                    depth1_input.setAttribute("class","center");
                    depth1_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id1+"',$(this).val(),'depth1')");
                    var depth1_delete = document.createElement("input");
                    depth1_delete.setAttribute("type","button");
                    depth1_delete.setAttribute("class","del");
                    depth1_delete.setAttribute("value","삭제");
                    depth1_delete.setAttribute("onclick","fnDepth1Del('"+data.pk_id1+"','"+add_id+"')");

                    //depth2
                    var td2 = document.createElement("td");
                    td2.setAttribute("class","category parent_"+add_id);
                    td2.setAttribute("id","depth2_"+data.depth2_id);
                    td2.setAttribute("rowspan","1");
                    var depth2_input = document.createElement("input");
                    depth2_input.setAttribute("name","depth2[]");
                    depth2_input.setAttribute("type","text");
                    depth2_input.setAttribute("class","center");
                    depth2_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id2+"',$(this).val(),'depth2')");
                    var depth2_delete = document.createElement("input");
                    depth2_delete.setAttribute("type","button");
                    depth2_delete.setAttribute("class","del");
                    depth2_delete.setAttribute("value","삭제");
                    depth2_delete.setAttribute("onclick","fnDepth2Del('"+data.pk_id2+"','"+data.depth2_id+"')");

                    //depth3
                    var td3 = document.createElement("td");
                    td3.setAttribute("class","category parent_"+add_id);
                    td3.setAttribute("id","depth3_"+data.depth3_id);
                    td3.setAttribute("rowspan","1");
                    var depth3_input = document.createElement("input");
                    depth3_input.setAttribute("name","depth3[]");
                    depth3_input.setAttribute("type","text");
                    depth3_input.setAttribute("class","center");
                    depth3_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id3+"',$(this).val(),'depth3')")
                    var depth3_delete = document.createElement("input");
                    depth3_delete.setAttribute("type","button");
                    depth3_delete.setAttribute("class","del");
                    depth3_delete.setAttribute("value","삭제");
                    depth3_delete.setAttribute("onclick","fnDepth3Del('"+data.pk_id3+"'.'"+data.depth3_id+"')");

                    //depth4
                    var td4 = document.createElement("td");
                    td4.setAttribute("class","category parent_"+add_id);
                    td4.setAttribute("id","depth4_"+data.depth4_id);
                    td4.setAttribute("rowspan","1");
                    var depth4_input = document.createElement("input");
                    depth4_input.setAttribute("name","depth4[]");
                    depth4_input.setAttribute("type","text");
                    depth4_input.setAttribute("class","center");
                    depth4_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id4+"',$(this).val(),'depth4')")
                    var depth4_delete = document.createElement("input");
                    depth4_delete.setAttribute("type","button");
                    depth4_delete.setAttribute("class","del");
                    depth4_delete.setAttribute("value","삭제");
                    depth4_delete.setAttribute("onclick","fnDepth4Del('"+data.pk_id4+"','"+data.depth4_id+"')");

                    //depth5
                    var td5 = document.createElement("td");
                    td5.setAttribute("class","category parent_"+add_id);
                    td5.setAttribute("id","depth5_"+data.depth5_id);
                    td5.setAttribute("rowspan","1");
                    var depth5_input = document.createElement("input");
                    depth5_input.setAttribute("name","depth5[]");
                    depth5_input.setAttribute("type","text");
                    depth5_input.setAttribute("class","left");
                    depth5_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id5+"',$(this).val(),'content')")
                    var depth5_delete = document.createElement("input");
                    depth5_delete.setAttribute("type","button");
                    depth5_delete.setAttribute("class","del");
                    depth5_delete.setAttribute("value","삭제");
                    depth5_delete.setAttribute("onclick","fnDepth5Del('"+data.pk_id5+"','"+data.depth5_id+"')");

                    //depth6
                    var td6 = document.createElement("td");
                    td6.setAttribute("class","etc");
                    var depth6_delete = document.createElement("input");
                    depth6_delete.setAttribute("type","button");
                    depth6_delete.setAttribute("value","수정");
                    depth6_delete.setAttribute("onclick","depth5ConAdd('"+data.pk_id5+"')");
                    var divlinks = document.createElement("div");
                    divlinks.setAttribute("id","links");
                    var divetc1 = document.createElement("div");
                    divlinks.setAttribute("id","etc1");
                    var divfiles = document.createElement("div");
                    divlinks.setAttribute("id","files");


                    //depth7
                    /*var td7 = document.createElement("td");
                    var depth7_input = document.createElement("input");
                    depth7_input.setAttribute("name","depth5_report[]");
                    depth7_input.setAttribute("type","text");
                    depth7_input.setAttribute("class","center");
                    depth7_input.setAttribute("value","0");
                    depth7_input.setAttribute("onkeyup","fnUpdate2('"+data.pk_id5+"',$(this).val())");*

                    td.appendChild(depth1_input);
                    td.appendChild(depth1_delete);
                    td2.appendChild(depth2_input);
                    td2.appendChild(depth2_delete);
                    td3.appendChild(depth3_input);
                    td3.appendChild(depth3_delete);
                    td4.appendChild(depth4_input);
                    td4.appendChild(depth4_delete);
                    td5.appendChild(depth5_input);
                    td5.appendChild(depth5_delete);
                    td6.appendChild(divlinks);
                    td6.appendChild(divetc1);
                    td6.appendChild(divfiles);
                    td6.appendChild(depth6_delete);
                    //td7.appendChild(depth7_input);

                    tr.appendChild(td);
                    tr.appendChild(td2);
                    tr.appendChild(td3);
                    tr.appendChild(td4);
                    tr.appendChild(td5);
                    tr.appendChild(td6);
                    //tr.appendChild(td7);
                    //var parent = $(".finish_" + id);
                    $("tr[id^='depth1']").each(function(){
                        if($(this).hasClass("depth")) {
                            var id = $(this).attr("id");
                            var num = id.replace('depth1',"");
                            console.log($(this).attr("id") + "//" + num + "//" + add_id);
                            if (Number(num) >= add_id) {
                                var newNum = Number(num) + 1;
                                $(this).attr("id", "depth1" + newNum);
                            }
                        }
                    });
                    $("tr[class*='finish_']").each(function(e){
                        var cls = $(this).attr("class");
                        var numArray = cls.split("finish_");
                        var num = numArray[1];
                        if(Number(num)>id) {
                            var newNum = Number(num) + 1;
                            $(this).removeClass("finish_"+num);
                            $(this).addClass("finish_"+ newNum);
                        }
                    });
                    //parentTr.after(tr);
                    if(finish.indexOf("finish")!=-1){
                        if(finish.indexOf("depth")!=-1) {
                            var resetfinish = finish.split(" ");
                            parentTr.removeClass(resetfinish[1]);
                            tr.setAttribute("class", "depth " + resetfinish[1]);
                            parentTr.after(tr);
                        }else{
                            tr.setAttribute("class", "depth " + finish);
                            parentTr.after(tr);
                            parentTr.removeClass(finish);
                        }
                    }else {
                        parentTr.after(tr);
                    }*/

                }
                if(data.status == 2){
                    alert("항목을 추가하지 못했습니다.");
                }
            });
        }
        if(depthnum==2){
            var findTr = $(this).attr("rowspan");
            if(!findTr){
                findTr = 1;
            }
            var currentRow = $(this).parent().index();
            var finish = $(this).parent().attr("class");
            var rowspan = $(this).attr("rowspan");
            var trnum = $("#edit_table tr").length;
            if(Number(currentRow) + Number(rowspan) == Number(trnum)){
                console.log("A");
                var parentTr = $("#edit_table tr").eq(trnum - 1);
            }else{
                if(Number(rowspan)>1) {
                    console.log("B 1 // " + (Number(currentRow) + Number(rowspan)));
                    var parentTr = $("#edit_table tr").eq(Number(currentRow) + Number(rowspan) - 1);
                }else{
                    console.log("B 2");
                    var parentTr = $("#edit_table tr").eq(currentRow);
                }
            }

            var parentclass = $(this).attr("class");
            var parent_row = parentclass.split(" ");
            var parent_id = parent_row[1].replace("parent_","");
            var depth1row = $("#depth1_"+parent_id).attr("rowspan");

            $.ajax({
                url:g5_url+"/admin/ajax.depth_add.php",
                type:"POST",
                data:{parent_id:parent_id,id:add_id,depth:2,me_code:me_code},
                dataType:"json"
            }).done(function(data) {
                location.reload();
                /*var tr = document.createElement("tr");
                tr.setAttribute("class","");
                //tr.setAttribute("id","depth2"+add_id);

                //depth2
                var td2 = document.createElement("td");
                td2.setAttribute("class", "category parent_"+parent_id);
                td2.setAttribute("id","depth2_"+data.depth2_id);
                var depth2_input = document.createElement("input");
                depth2_input.setAttribute("name", "depth2[]");
                depth2_input.setAttribute("type", "text");
                depth2_input.setAttribute("class", "center");
                depth2_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id2+"',$(this).val(),'depth2')");
                var depth2_delete = document.createElement("input");
                depth2_delete.setAttribute("type", "button");
                depth2_delete.setAttribute("value", "삭제");
                depth2_delete.setAttribute("class", "del");
                depth2_delete.setAttribute("onclick","fnDepth2Del('"+data.pk_id2+"','"+data.depth2_id+"')");

                //depth3
                var td3 = document.createElement("td");
                td3.setAttribute("class", "category parent_"+parent_id);
                td3.setAttribute("id", "depth3_"+data.depth3_id);
                var depth3_input = document.createElement("input");
                depth3_input.setAttribute("name", "depth3[]");
                depth3_input.setAttribute("type", "text");
                depth3_input.setAttribute("class", "center");
                depth3_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id3+"',$(this).val(),'depth3')");
                var depth3_delete = document.createElement("input");
                depth3_delete.setAttribute("type", "button");
                depth3_delete.setAttribute("value", "삭제");
                depth3_delete.setAttribute("class", "del");
                depth3_delete.setAttribute("onclick","fnDepth3Del('"+data.pk_id3+"','"+data.depth3_id+"')");


                //depth4
                var td4 = document.createElement("td");
                td4.setAttribute("class", "category parent_"+parent_id);
                td4.setAttribute("id", "depth4_"+data.depth4_id);
                var depth4_input = document.createElement("input");
                depth4_input.setAttribute("name", "depth4[]");
                depth4_input.setAttribute("type", "text");
                depth4_input.setAttribute("class", "center");
                depth4_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id4+"',$(this).val(),'depth4')");
                var depth4_delete = document.createElement("input");
                depth4_delete.setAttribute("type", "button");
                depth4_delete.setAttribute("value", "삭제");
                depth4_delete.setAttribute("class", "del");
                depth4_delete.setAttribute("onclick","fnDepth4Del('"+data.pk_id4+"','"+data.depth4_id+"')");

                //depth5
                var td5 = document.createElement("td");
                td5.setAttribute("class", "category parent_"+parent_id);
                td5.setAttribute("id", "depth5_"+data.depth5_id);
                var depth5_input = document.createElement("input");
                depth5_input.setAttribute("name", "depth5[]");
                depth5_input.setAttribute("type", "text");
                depth5_input.setAttribute("class", "left");
                depth5_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id5+"',$(this).val(),'content')");
                var depth5_delete = document.createElement("input");
                depth5_delete.setAttribute("type", "button");
                depth5_delete.setAttribute("value", "삭제");
                depth5_delete.setAttribute("class", "del");
                depth5_delete.setAttribute("onclick","fnDepth5Del('"+data.pk_id5+"','"+data.depth5_id+"')");

                //depth6
                var td6 = document.createElement("td");
                td6.setAttribute("class", "etc");
                td6.setAttribute("id", data.depth5_id);
                var depth6_delete = document.createElement("input");
                depth6_delete.setAttribute("type", "button");
                depth6_delete.setAttribute("value", "수정");
                depth6_delete.setAttribute("onclick","depth5ConAdd('"+data.pk_id5+"')");
                var divlinks = document.createElement("div");
                divlinks.setAttribute("id","links");
                var divetc1 = document.createElement("div");
                divlinks.setAttribute("id","etc1");
                var divfiles = document.createElement("div");
                divlinks.setAttribute("id","files");


                //depth7
                /*var td7 = document.createElement("td");
                var depth7_input = document.createElement("input");
                depth7_input.setAttribute("name", "depth5_report[]");
                depth7_input.setAttribute("type", "text");
                depth7_input.setAttribute("class", "center");
                depth7_input.setAttribute("id", data.depth5_id);
                depth7_input.setAttribute("value","0");
                depth7_input.setAttribute("onkeyup","fnUpdate2('"+data.pk_id5+"',$(this).val())");*

                td2.appendChild(depth2_input);
                td2.appendChild(depth2_delete);
                td3.appendChild(depth3_input);
                td3.appendChild(depth3_delete);
                td4.appendChild(depth4_input);
                td4.appendChild(depth4_delete);
                td5.appendChild(depth5_input);
                td5.appendChild(depth5_delete);
                td6.appendChild(divlinks);
                td6.appendChild(divetc1);
                td6.appendChild(divfiles);
                td6.appendChild(depth6_delete);
                //td7.appendChild(depth7_input);

                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                tr.appendChild(td6);
                //tr.appendChild(td7);

                $("td[id^='depth2_']").each(function(){
                    var id = $(this).attr("id");
                    var num = id.replace("depth2_","");
                    if(Number(num) >= data.depth2_id){
                        var newNum = Number(num)+1;
                        $(this).attr("id","depth2_"+newNum);
                    }
                });

                $("#depth1_" + parent_id).attr("rowspan", Number(depth1row) + 1);

                //console.log("after : " + after);
                if(finish.indexOf("finish")!=-1){
                    if(finish.indexOf("depth")!=-1) {
                        var resetfinish = finish.split(" ");
                        parentTr.removeClass(resetfinish[1]);
                        tr.setAttribute("class", resetfinish[1]);
                        parentTr.after(tr);
                    }else{
                        tr.setAttribute("class", finish);
                        parentTr.after(tr);
                        parentTr.removeClass(finish);
                    }
                }else {
                    parentTr.after(tr);
                }*/

            });
        }
        if(depthnum==3){
            //parentTr
            var findTr = $(this).attr("rowspan");
            if(!findTr){
                findTr=1;
            }

            var parentclass = $(this).attr("class");
            var parent_row = parentclass.split(" ");
            var parent_id = parent_row[1].replace("parent_","");
            var depth1row = $("#depth1_"+parent_id).attr("rowspan");

            var currentRow = $(this).parent().index();
            var finish = $(this).parent().attr("class");
            if(finish.indexOf("finish")!=-1){
                var parentTr = $("#edit_table tr").eq(currentRow);
            }else{
                var parentTr = $("#edit_table tr").eq(Number(currentRow)+Number(findTr));
            }

            $.ajax({
                url:g5_url+"/admin/ajax.depth_add.php",
                type:"POST",
                data:{thisid:id,id:add_id,depth:3,me_code:me_code},
                dataType:"json"
            }).done(function(data) {
                location.reload();
                //depth1_row
                /*var depth1Row = $("#depth1_"+data.depth1_id).attr("rowspan");
                if(!depth1Row){
                    depth1Row = 1;
                }
                $("#depth1_"+data.depth1_id).attr("rowspan",Number(depth1Row)+1);
                //depth2_row
                var depth2Row = $("#depth2_"+data.depth2_id).attr("rowspan");
                if(!depth2Row){
                    depth2Row = 1;
                }
                $("#depth2_"+data.depth2_id).attr("rowspan",Number(depth2Row)+1);

                var tr = document.createElement("tr");
                tr.setAttribute("class","");
                //depth3
                var td3 = document.createElement("td");
                td3.setAttribute("class", "category parent_"+parent_id);
                td3.setAttribute("id", "depth3_"+data.depth3_id);
                var depth3_input = document.createElement("input");
                depth3_input.setAttribute("name", "depth3[]");
                depth3_input.setAttribute("type", "text");
                depth3_input.setAttribute("class", "center");
                depth3_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id3+"',$(this).val(),'depth3')");
                var depth3_delete = document.createElement("input");
                depth3_delete.setAttribute("type", "button");
                depth3_delete.setAttribute("value", "삭제");
                depth3_delete.setAttribute("class", "del");
                depth3_delete.setAttribute("onclick","fnDepth3Del('"+data.pk_id3+"','"+data.depth3_id+"')");

                //depth4
                var td4 = document.createElement("td");
                td4.setAttribute("class", "category parent_"+parent_id);
                td4.setAttribute("id", "depth4_"+data.depth4_id);
                var depth4_input = document.createElement("input");
                depth4_input.setAttribute("name", "depth4[]");
                depth4_input.setAttribute("type", "text");
                depth4_input.setAttribute("class", "center");
                depth4_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id4+"',$(this).val(),'depth4')");
                var depth4_delete = document.createElement("input");
                depth4_delete.setAttribute("type", "button");
                depth4_delete.setAttribute("value", "삭제");
                depth4_delete.setAttribute("class", "del");
                depth4_delete.setAttribute("onclick","fnDepth4Del('"+data.pk_id4+"','"+data.depth4_id+"')");


                //depth5
                var td5 = document.createElement("td");
                td5.setAttribute("class", "category parent_"+parent_id);
                td5.setAttribute("id", "depth5_"+data.depth5_id);
                var depth5_input = document.createElement("input");
                depth5_input.setAttribute("name", "depth5[]");
                depth5_input.setAttribute("type", "text");
                depth5_input.setAttribute("class", "left");
                depth5_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id5+"',$(this).val(),'content')");
                var depth5_delete = document.createElement("input");
                depth5_delete.setAttribute("type", "button");
                depth5_delete.setAttribute("value", "삭제");
                depth5_delete.setAttribute("class", "del");
                depth5_delete.setAttribute("onclick","fnDepth5Del('"+data.pk_id5+"','"+data.depth5_id+"')");


                //depth6
                var td6 = document.createElement("td");
                td6.setAttribute("class", "etc");
                td6.setAttribute("id", data.depth5_id);
                var depth6_delete = document.createElement("input");
                depth6_delete.setAttribute("type", "button");
                depth6_delete.setAttribute("value", "수정");
                depth6_delete.setAttribute("onclick","depth5ConAdd('"+data.pk_id5+"')");
                var divlinks = document.createElement("div");
                divlinks.setAttribute("id","links");
                var divetc1 = document.createElement("div");
                divlinks.setAttribute("id","etc1");
                var divfiles = document.createElement("div");
                divlinks.setAttribute("id","files");


                //depth7
                /*var td7 = document.createElement("td");
                var depth7_input = document.createElement("input");
                depth7_input.setAttribute("name", "depth5_report[]");
                depth7_input.setAttribute("type", "text");
                depth7_input.setAttribute("class", "center");
                depth7_input.setAttribute("id", data.depth5_id);
                depth7_input.setAttribute("value","0");
                depth7_input.setAttribute("onkeyup","fnUpdate2('"+data.pk_id5+"',$(this).val())");*

                td3.appendChild(depth3_input);
                td3.appendChild(depth3_delete);
                td4.appendChild(depth4_input);
                td4.appendChild(depth4_delete);
                td5.appendChild(depth5_input);
                td5.appendChild(depth5_delete);
                td6.appendChild(divlinks);
                td6.appendChild(divetc1);
                td6.appendChild(divfiles);
                td6.appendChild(depth6_delete);
                //td7.appendChild(depth7_input);

                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                tr.appendChild(td6);
                //tr.appendChild(td7);

                $("td[id^='depth3_']").each(function(){
                    var id = $(this).attr("id");
                    var num = id.replace("depth3_" , "");
                    if(Number(num) >= data.depth3_id){
                        var newNum = Number(num)+1;
                        $(this).attr("id","depth3_"+newNum);
                    }
                });

                if(finish.indexOf("finish")!=-1){
                    if(finish.indexOf("depth")!=-1) {
                        var resetfinish = finish.split(" ");
                        parentTr.removeClass(resetfinish[1]);
                        tr.setAttribute("class", resetfinish[1]);
                        parentTr.after(tr);
                    }else{
                        tr.setAttribute("class", finish);
                        parentTr.after(tr);
                        parentTr.removeClass(finish);
                    }
                }else {
                    parentTr.before(tr);
                }*/
            });
        }
        if(depthnum==4){
            //parentTr
            var findTr = $(this).attr("rowspan");
            if(!findTr){
                findTr=1;
            }

            var parentclass = $(this).attr("class");
            var parent_row = parentclass.split(" ");
            var parent_id = parent_row[1].replace("parent_","");
            var depth1row = $("#depth1_"+parent_id).attr("rowspan");

            var currentRow = $(this).parent().index();
            var finish = $(this).parent().attr("class");
            if(finish.indexOf("finish")!=-1){
                var parentTr = $("#edit_table tr").eq(currentRow);
            }else{
                var parentTr = $("#edit_table tr").eq(Number(currentRow)+Number(findTr));
            }
            $.ajax({
                url:g5_url+"/admin/ajax.depth_add.php",
                type:"POST",
                data:{thisid:id,id:add_id,depth:4,me_code:me_code},
                dataType:"json"
            }).done(function(data) {
                location.reload();
                /*console.log(data);
                //depth1_row
                var depth1Row = $("#depth1_"+data.depth1_id).attr("rowspan");
                if(!depth1Row){
                    depth1Row = 1;
                }
                $("#depth1_"+data.depth1_id).attr("rowspan",Number(depth1Row)+1);
                //depth2_row
                var depth2Row = $("#depth2_"+data.depth2_id).attr("rowspan");
                if(!depth2Row){
                    depth2Row = 1;
                }
                $("#depth2_"+data.depth2_id).attr("rowspan",Number(depth2Row)+1);

                //depth3_row
                var depth3Row = $("#depth3_"+data.depth3_id).attr("rowspan");
                if(!depth3Row){
                    depth3Row = 1;
                }
                $("#depth3_"+data.depth3_id).attr("rowspan",Number(depth3Row)+1);

                var tr = document.createElement("tr");
                tr.setAttribute("class","");
                //depth4
                var td4 = document.createElement("td");
                td4.setAttribute("class", "category parent_"+parent_id);
                td4.setAttribute("id", "depth4_"+data.depth4_id);
                var depth4_input = document.createElement("input");
                depth4_input.setAttribute("name", "depth4[]");
                depth4_input.setAttribute("type", "text");
                depth4_input.setAttribute("class", "center");
                depth4_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id4+"',$(this).val(),'depth4')");
                var depth4_delete = document.createElement("input");
                depth4_delete.setAttribute("type", "button");
                depth4_delete.setAttribute("value", "삭제");
                depth4_delete.setAttribute("class", "del");
                depth4_delete.setAttribute("onclick","fnDepth4Del('"+data.pk_id4+"','"+data.depth4_id+"')");

                //depth5
                var td5 = document.createElement("td");
                td5.setAttribute("class", "category parent_"+parent_id);
                td5.setAttribute("id", "depth5_"+data.depth5_id);
                var depth5_input = document.createElement("input");
                depth5_input.setAttribute("name", "depth5[]");
                depth5_input.setAttribute("type", "text");
                depth5_input.setAttribute("class", "left");
                depth5_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id5+"',$(this).val(),'content')");
                var depth5_delete = document.createElement("input");
                depth5_delete.setAttribute("type", "button");
                depth5_delete.setAttribute("value", "삭제");
                depth5_delete.setAttribute("class", "del");
                depth5_delete.setAttribute("onclick","fnDepth5Del('"+data.pk_id5+"','"+data.depth5_id+"')");

                //depth6
                var td6 = document.createElement("td");
                td6.setAttribute("class", "etc");
                td6.setAttribute("id", data.depth5_id);
                var depth6_delete = document.createElement("input");
                depth6_delete.setAttribute("type", "button");
                depth6_delete.setAttribute("value", "수정");
                depth6_delete.setAttribute("onclick","depth5ConAdd('"+data.pk_id5+"')");
                var divlinks = document.createElement("div");
                divlinks.setAttribute("id","links");
                var divetc1 = document.createElement("div");
                divlinks.setAttribute("id","etc1");
                var divfiles = document.createElement("div");
                divlinks.setAttribute("id","files");


                //depth7
                /*var td7 = document.createElement("td");
                var depth7_input = document.createElement("input");
                depth7_input.setAttribute("name", "depth5_report[]");
                depth7_input.setAttribute("type", "text");
                depth7_input.setAttribute("class", "center");
                depth7_input.setAttribute("id", data.depth5_id);
                depth7_input.setAttribute("value","0");
                depth7_input.setAttribute("onkeyup","fnUpdate2('"+data.pk_id5+"',$(this).val())");*

                td4.appendChild(depth4_input);
                td4.appendChild(depth4_delete);
                td5.appendChild(depth5_input);
                td5.appendChild(depth5_delete);
                td6.appendChild(divlinks);
                td6.appendChild(divetc1);
                td6.appendChild(divfiles);
                td6.appendChild(depth6_delete);
                //td7.appendChild(depth7_input);

                tr.appendChild(td4);
                tr.appendChild(td5);
                tr.appendChild(td6);
                //tr.appendChild(td7);

                $("td[id^='depth4_']").each(function(){
                    var id = $(this).attr("id");
                    var num = id.replace("depth4_" , "");
                    if(Number(num) >= data.depth4_id){
                        var newNum = Number(num)+1;
                        $(this).attr("id","depth4_"+newNum);
                    }
                });
                if(finish.indexOf("finish")!=-1){
                    if(finish.indexOf("depth")!=-1) {
                        var resetfinish = finish.split(" ");
                        parentTr.removeClass(resetfinish[1]);
                        tr.setAttribute("class", resetfinish[1]);
                        parentTr.after(tr);
                    }else{
                        tr.setAttribute("class", finish);
                        parentTr.after(tr);
                        parentTr.removeClass(finish);
                    }
                }else {
                    parentTr.before(tr);
                }*/
            });
        }
        if(depthnum==5){
            //parentTr
            var findTr = $(this).attr("rowspan");
            if(!findTr){
                findTr=1;
            }

            var parentclass = $(this).attr("class");
            var parent_row = parentclass.split(" ");
            var parent_id = parent_row[1].replace("parent_","");
            var depth1row = $("#depth1_"+parent_id).attr("rowspan");

            var currentRow = $(this).parent().index();
            var finish = $(this).parent().attr("class");
            if(finish.indexOf("finish")!=-1){
                var parentTr = $("#edit_table tr").eq(currentRow);
            }else{
                var parentTr = $("#edit_table tr").eq(Number(currentRow)+Number(findTr));
            }
            $.ajax({
                url:g5_url+"/admin/ajax.depth_add.php",
                type:"POST",
                data:{thisid:id,id:add_id,depth:5,me_code:me_code},
                dataType:"json"
            }).done(function(data) {
                location.reload();
                //depth1_row
                /*var depth1Row = $("#depth1_"+data.depth1_id).attr("rowspan");
                if(!depth1Row){
                    depth1Row = 1;
                }
                $("#depth1_"+data.depth1_id).attr("rowspan",Number(depth1Row)+1);
                //depth2_row
                var depth2Row = $("#depth2_"+data.depth2_id).attr("rowspan");
                if(!depth2Row){
                    depth2Row = 1;
                }
                $("#depth2_"+data.depth2_id).attr("rowspan",Number(depth2Row)+1);

                //depth3_row
                var depth3Row = $("#depth3_"+data.depth3_id).attr("rowspan");
                if(!depth3Row){
                    depth3Row = 1;
                }
                $("#depth3_"+data.depth3_id).attr("rowspan",Number(depth3Row)+1);

                //depth4_row
                var depth4Row = $("#depth4_"+data.depth4_id).attr("rowspan");
                if(!depth4Row){
                    depth4Row = 1;
                }
                $("#depth4_"+data.depth4_id).attr("rowspan",Number(depth4Row)+1);

                var tr = document.createElement("tr");
                tr.setAttribute("class","");
                //depth5
                var td5 = document.createElement("td");
                td5.setAttribute("class", "category parent_"+parent_id);
                td5.setAttribute("id", "depth5_"+data.depth5_id);
                var depth5_input = document.createElement("input");
                depth5_input.setAttribute("name", "depth5[]");
                depth5_input.setAttribute("type", "text");
                depth5_input.setAttribute("class", "left");
                depth5_input.setAttribute("onkeyup","fnUpdate('"+data.pk_id5+"',$(this).val(),'content')");
                var depth5_delete = document.createElement("input");
                depth5_delete.setAttribute("type", "button");
                depth5_delete.setAttribute("value", "삭제");
                depth5_delete.setAttribute("class", "del");
                depth5_delete.setAttribute("onclick","fnDepth5Del('"+data.pk_id5+"','"+data.depth5_id+"')");

                //depth6
                var td6 = document.createElement("td");
                td6.setAttribute("class", "etc");
                td6.setAttribute("id", data.depth5_id);
                var depth6_delete = document.createElement("input");
                depth6_delete.setAttribute("type", "button");
                depth6_delete.setAttribute("value", "수정");
                depth6_delete.setAttribute("onclick","depth5ConAdd('"+data.pk_id5+"')");
                var divlinks = document.createElement("div");
                divlinks.setAttribute("id","links");
                var divetc1 = document.createElement("div");
                divlinks.setAttribute("id","etc1");
                var divfiles = document.createElement("div");
                divlinks.setAttribute("id","files");


                //depth7
                /*var td7 = document.createElement("td");
                var depth7_input = document.createElement("input");
                depth7_input.setAttribute("name", "depth5_report[]");
                depth7_input.setAttribute("type", "text");
                depth7_input.setAttribute("class", "center");
                depth7_input.setAttribute("id", data.depth5_id);
                depth7_input.setAttribute("value","0");
                depth7_input.setAttribute("onkeyup","fnUpdate2('"+data.pk_id5+"',$(this).val())");*

                td5.appendChild(depth5_input);
                td5.appendChild(depth5_delete);
                td6.appendChild(divlinks);
                td6.appendChild(divetc1);
                td6.appendChild(divfiles);
                td6.appendChild(depth6_delete);
                //td7.appendChild(depth7_input);

                tr.appendChild(td5);
                tr.appendChild(td6);
                //tr.appendChild(td7);

                $("td[id^='depth5_']").each(function(){
                    var id = $(this).attr("id");
                    var num = id.replace("depth5_" , "");
                    if(Number(num) >= data.depth5_id){
                        var newNum = Number(num)+1;
                        $(this).attr("id","depth5_"+newNum);
                    }
                });
                if(finish.indexOf("finish")!=-1){
                    if(finish.indexOf("depth")!=-1) {
                        var resetfinish = finish.split(" ");
                        parentTr.removeClass(resetfinish[1]);
                        tr.setAttribute("class", resetfinish[1]);
                        parentTr.after(tr);
                    }else{
                        tr.setAttribute("class", finish);
                        parentTr.after(tr);
                        parentTr.removeClass(finish);
                    }
                }else {
                    parentTr.before(tr);
                }*/
            });
        }
    });

    function getOption(id,depth,depthid){
        console.log(id+"//"+depth);
        $.ajax({
            url:g5_url+"/admin/ajax.depth.php",
            method:"POST",
            data:{id:id,depth:depth,depthid:depthid}
        }).done(function(data){
            console.log(data);
            if(depth==0){
                $("#cmap_depth2").html("<option value=''>작업</opion>");
                $("#cmap_depth2").append(data);
            }if(depth==1){
                $("#cmap_depth3").html("<option value=''>구분</opion>");
                $("#cmap_depth3").append(data);
            }if(depth==2){
                $("#cmap_depth4").html("<option value=''>항목</opion>");
                $("#cmap_depth4").append(data);
            }
        });
    }

    function fnDepth1Del(pk_id,id){
        if(confirm("해당 항목을 삭제하시겠습니까? \n하위 항목이 있을경우 모두 삭제됩니다.")){
            location.href=g5_url+'/admin/construction_delete?pk_id='+pk_id+'&id='+id+"&menu_id=1&depth=1";
        }else{
            return false;
        }
    }
    function fnDepth2Del(pk_id,id){
        if(confirm("해당 항목을 삭제하시겠습니까? \n하위 항목이 있을경우 모두 삭제됩니다.")){
            location.href=g5_url+'/admin/construction_delete?pk_id='+pk_id+'&id='+id+"&menu_id=1&depth=2";
        }else{
            return false;
        }
    }
    function fnDepth3Del(pk_id,id){
        if(confirm("해당 항목을 삭제하시겠습니까? \n하위 항목이 있을경우 모두 삭제됩니다.")){
            location.href=g5_url+'/admin/construction_delete?pk_id='+pk_id+'&id='+id+"&menu_id=1&depth=3";
        }else{
            return false;
        }
    }
    function fnDepth4Del(pk_id,id){
        if(confirm("해당 항목을 삭제하시겠습니까? \n하위 항목이 있을경우 모두 삭제됩니다.")){
            location.href=g5_url+'/admin/construction_delete?pk_id='+pk_id+'&id='+id+"&menu_id=1&depth=4";
        }else{
            return false;
        }
    }
    function fnDepth5Del(pk_id,id){
        if(confirm("해당 항목을 삭제하시겠습니까? \n하위 항목이 있을경우 모두 삭제됩니다.")){
            location.href=g5_url+'/admin/construction_delete?pk_id='+pk_id+'&id='+id+"&menu_id=1&depth=5";
        }else{
            return false;
        }
    }


    function depth5ConAdd(id){
        $("#link1").val('');
        $("#link2").val('');
        $("#link3").val('');
        $("#etc1_1").val('');
        $("#etc1_2").val('');
        $("#etc1_3").val('');
        $("#linkname1").val('');
        $("#linkname2").val('');
        $("#linkname3").val('');
        $("#etc1name_1").val('');
        $("#etc1name_2").val('');
        $("#etc1name_3").val('');
        $("#filename1").val('');
        $("#filename2").val('');
        $("#filename3").val('');
        $("#filesnames1").val('');
        $("#filesnames2").val('');
        $("#filesnames3").val('');
        $("#filesnames11").val('');
        $("#filesnames22").val('');
        $("#filesnames33").val('');
        $(".add_file1").html('');
        $(".add_file2").html('');
        $(".add_file3").html('');
        $(".add_files1").html('');
        $(".add_files2").html('');
        $(".add_files3").html('');
        $(".add_files11").html('');
        $(".add_files22").html('');
        $(".add_files33").html('');
        $("#file1").val('');
        $("#file2").val('');
        $("#file3").val('');
        $("#files1").val('');
        $("#files2").val('');
        $("#files3").val('');
        $("#files11").val('');
        $("#files22").val('');
        $("#files33").val('');
        $("#content_id").val(id);
        $.ajax({
            url:g5_url+"/admin/get_content.php",
            type:"POST",
            data:{pk_id:id},
            dataType:"json"
        }).done(function(data){
            console.log(data);
            if(data.status==0){
                alert("선택된 항목이 없습니다.");
                return false;
            }else if(data.status==1){
                if(data.link0) {
                    $("#link1").val(data.link0)
                }
                if(data.link1) {
                    $("#link2").val(data.link1)
                }
                if(data.link2) {
                    $("#link3").val(data.link2)
                }
                if(data.linkname0) {
                    $("#linkname1").val(data.linkname0)
                }
                if(data.linkname1) {
                    $("#linkname2").val(data.linkname1)
                }
                if(data.linkname2) {
                    $("#linkname3").val(data.linkname2)
                }
                /*if(data.etc1_0) {
                 $("#etc1_1").val(data.etc1_0)
                 }
                 if(data.etc1_1) {
                 $("#etc1_2").val(data.etc1_1)
                 }
                 if(data.etc1_2) {
                 $("#etc1_3").val(data.etc1_2)
                 }
                 if(data.etcname1_0) {
                 $("#etcname1_1").val(data.etcname1_0)
                 }
                 if(data.etcname1_1) {
                 $("#etcname1_2").val(data.etcname1_1)
                 }
                 if(data.etcname1_2) {
                 $("#etcname1_3").val(data.etcname1_2)
                 }*/
                if(data.filename0){
                    $("#filename1").val(data.filename0);
                }
                if(data.filename1){
                    $("#filename2").val(data.filename1);
                }
                if(data.filename2){
                    $("#filename3").val(data.filename2);
                }
                if(data.filesname0){
                    $("#filesnames1").val(data.filesname0);
                }
                if(data.filesname1){
                    $("#filesnames2").val(data.filesname1);
                }
                if(data.filesname2){
                    $("#filesnames3").val(data.filesname2);
                }
                if(data.filesnames0){
                    $("#filesnames11").val(data.filesnames0);
                }
                if(data.filesnames1){
                    $("#filesnames22").val(data.filesnames1);
                }
                if(data.filesnames2){
                    $("#filesnames33").val(data.filesnames2);
                }
                if(data.file0) {
                    $(".add_file1").append("<span>" + data.file0 + "</span><input type='checkbox' name='fileDel1' value='1'> 삭제<br>");
                }
                if(data.file1) {
                    $(".add_file2").append("<span>" + data.file1 + "</span><input type='checkbox' name='fileDel2' value='1'> 삭제<br>");
                }
                if(data.file2) {
                    $(".add_file3").append("<span>" + data.file2 + "</span><input type='checkbox' name='fileDel3' value='1'> 삭제<br>");
                }
                if(data.files0) {
                    $(".add_files1").append("<span>" + data.files0 + "</span><input type='checkbox' name='fileDel4' value='1'> 삭제<br>");
                }
                if(data.files1) {
                    $(".add_files2").append("<span>" + data.files1 + "</span><input type='checkbox' name='fileDel5' value='1'> 삭제<br>");
                }
                if(data.files2) {
                    $(".add_files3").append("<span>" + data.files2 + "</span><input type='checkbox' name='fileDel6' value='1'> 삭제<br>");
                }
                if(data.filess0) {
                    $(".add_files11").append("<span>" + data.filess0 + "</span><input type='checkbox' name='fileDel7' value='1'> 삭제<br>");
                }
                if(data.filess1) {
                    $(".add_files22").append("<span>" + data.filess1 + "</span><input type='checkbox' name='fileDel8' value='1'> 삭제<br>");
                }
                if(data.filess2) {
                    $(".add_files33").append("<span>" + data.filess2 + "</span><input type='checkbox' name='fileDel9' value='1'> 삭제<br>");
                }
                dialog.dialog("open","modal",true);
            }else if(data.status==2){
                alert("잘못된 항목입니다. 다시 확인해 주세요.");
                return false;
            }
        });
    }

    var upText = null;

    function fnUpdate(id,text,depth,me_id,me_code){
        if(!id){
            alert('선택항목의 정보가 올바르지 않습니다.');
            return false;
        }
        if(upText!=null){
            clearTimeout(upText);
        }
        upText = setTimeout(function(){
            $.ajax({
                url:g5_url+"/admin/ajax.all_update.php",
                type:"POST",
                data:{pk_id:id,text:text,depth:depth,me_id:me_id,me_code:me_code}
            }).done(function(data){
                if(data=="2"){
                    alert('현재 작성하신 글이 업데이트 하지 못했습니다,\n새로고침후 다시 이용해 보세요.');
                }if(data=="1"){
                    $(".debug").html('업데이트 되었습니다.');
                    $(".debug").addClass("active");
                    setTimeout(function(){$(".debug").removeClass("active");},1000);
                }
            });
        },1000)
    }
    var upText2 = null;
    function fnUpdate2(id,text){
        if(!id){
            alert('선택항목의 정보가 올바르지 않습니다.');
            return false;
        }
        if(upText2!=null){
            clearTimeout(upText2);
        }
        upText2 = setTimeout(function(){
            $.ajax({
                url:g5_url+"/admin/ajax.all_update2.php",
                type:"POST",
                data:{pk_id:id,text:text}
            }).done(function(data){
                if(data=="2"){
                    alert('제출일 업데이트가 실패 하였습니다.,\n새로고침후 다시 이용해 보세요.');
                }if(data=="1"){
                    $(".debug").html('업데이트 되었습니다.');
                    $(".debug").addClass("active");
                    setTimeout(function(){$(".debug").removeClass("active");},1000);
                }
            });
        },300)
    }


    function fnImage(file){
        var ext = file.split(".");
        var chkext = ext[1].toLowerCase();
        var text = "jpg,jpeg,gif,png";
        if(text.indexOf(chkext) != -1) {
            var path = g5_url + "/data/cmap_content/" + file;
            $(".img_modal img").attr("src", path);
            img_dialog.dialog("open", "img_modal", true);
        }else{
            location.href = './download?file='+file;
        }
    }

    $(function() {
        var pressed = false;
        var start = undefined;
        var startX, startWidth;

        $("table td").mousedown(function(e) {
            start = $(this);
            pressed = true;
            startX = e.pageX;
            startWidth = $(this).width();
            $(start).addClass("resizing");
        });

        $(document).mousemove(function(e) {
            if(pressed) {
                $(start).width(startWidth+(e.pageX-startX));
            }
        });

        $(document).mouseup(function() {
            if(pressed) {
                $(start).removeClass("resizing");
                pressed = false;
            }
        });
    });
</script>
<?php
include_once (G5_PATH."/admin/admin.tail.php");
?>
