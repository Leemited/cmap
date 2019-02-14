<?php
include_once ("../../common.php");

$sql = "select * from `cmap_content` where pk_id = '{$pk_id}'";
$etc = sql_fetch($sql);

if($etc["link"]!=""){
    $links = explode("``",$etc["link"]);
    $linknames = explode("``",$etc["linkname"]);
    //echo "<h1>참고링크</h1>";
    /*for($i=0;$i<count($links);$i++){
        //$links[] = $links[$i];
        if($links[$i]!=""){
            //echo "링크".$i." : " . "<a href='{$links[$i]}' target='_blank' >".$linknames[$i]."</a><br>";
        }
    }*/
}

if($etc["etc1"]!=""){
    $etcs = explode("``",$etc["etc1"]);
    $etcnames = explode("``",$etc["etcname1"]);
    //echo "<h1>참고사례</h1>";
    /*for($i=0;$i<count($etcs);$i++){
        if($etcs[$i]!=""){
            //echo "참고" .$i. " : <a href='".$etcs[$i]."' target='_blank' >".$etcnames[$i]."</a><br>";
        }
    }*/
}

if($etc["attachment"]!=""){
    $attachment = explode("``",$etc["attachment"]);
    $attachmentname = explode("``",$etc["attachmentname1"]);
    for($i=0;$i<count($attachment);$i++){
        if($attachment[$i]!=""){
            $ext = array_pop(explode(".",$attachment[$i]));
            if(strpos($ext,"jpge,jpg,gif,png")===false){
                $pdf1[] = G5_DATA_URL."/cmap_content/".$attachment[$i];
                if($attachmentname[$i]!="") {
                    $pdf1name[] = $attachmentname[$i].".".$ext;
                }else{
                    $pdf1name[] = "첨부파일".$i;
                }
            }/*if($ext=="pdf" || $ext == "PDF"){
                $pdf1[] = G5_DATA_URL."/cmap_content/".$attachment[$i];
                if($attachmentname[$i]!="") {
                    $pdf1name[] = $attachmentname[$i].".".$ext;
                }else{
                    $pdf1name[] = "첨부파일".$i;
                }
            }*/
            //echo "참고파일 : " . $attachment[$i]."// 파일명 : ".$attachmentname[$i]."<br>";
        }
    }
}

if($etc["attachment2"]!=""){
    $attachment2 = explode("``",$etc["attachment2"]);
    $attachmentname2 = explode("``",$etc["attachmentname2"]);
    for($i=0;$i<count($attachment2);$i++){
        if($attachment2[$i]!=""){
            $ext = array_pop(explode(".",$attachment2[$i]));
            if(strpos($ext,"jpge,jpg,gif,png,pdf")===false){
                $pdf2[] = G5_DATA_URL."/cmap_content/".$attachment2[$i];
                if($attachmentname2[$i]!="") {
                    $pdf2name[] = $attachmentname2[$i].".".$ext;
                }else{
                    $pdf2name[] = "사례파일".$i;
                }
            }/*if($ext=="pdf" || $ext == "PDF"){
                $pdf2[] = G5_DATA_URL."/cmap_content/".$attachment2[$i];
                if($attachmentname2[$i]!="") {
                    $pdf2name[] = $attachmentname2[$i].".".$ext;
                }else{
                    $pdf2name[] = "첨부파일".$i;
                }
            }*/
            //echo "사례파일 : " . $attachment2[$i]."// 파일명 : ".$attachmentname2[$i]."<br>";
        }
    }
}
?>
<div class="previews">
    <?php /*if(count($item1) > 0 || count($item2) > 0){*/?><!--
        <div class="owl-carousel" id="images">
            <?php /*if(count($item1)>0) {
                for ($i = 0; $i < count($item1); $i++) {
                    */?>
                    <div class="item">
                        <h2><?php /*echo $item1name[$i];*/?></h2>
                        <img src="<?php /*echo $item1[$i];*/?>" alt="">
                    </div>
                <?php /*}
            }*/?>
            <?php /*if(count($item2)>0) {
                for ($i = 0; $i < count($item2); $i++) {
                    */?>
                    <div class="item">
                        <h2><?php /*echo $item2name[$i];*/?></h2>
                        <img src="<?php /*echo $item2[$i];*/?>" alt="">
                    </div>
                <?php /*}
            }*/?>
        </div>
    --><?php /*}*/?>
    <?php if(count($pdf1) > 0 || count($pdf2) > 0){?>
        <h2 id="preview_title"><?php echo $pdf1name[0];?></h2>
        <iframe src="<?php echo $pdf1[0];?>" frameborder="0" width="100%" height="95%" id="previewer"></iframe>
    <?php }else{?>
        <div class="no_images">
            <div class="trs">
                <div class="tds">미리보기 없음</div>
            </div>
        </div>
    <?php }?>
</div>
<table class="preview_table">
    <tr>
        <th><img src="<?php echo G5_IMG_URL;?>/ic_links.svg" alt="">관련법령</th>
        <th><img src="<?php echo G5_IMG_URL;?>/ic_attach.svg" alt="">미리보기</th>
        <!--<th><img src="<?php /*echo G5_IMG_URL;*/?>/ic_etc.svg" alt="">사례</th>-->
        <th><img src="<?php echo G5_IMG_URL;?>/ic_attach.svg" alt="">다운로드</th>
    </tr>
    <?php for($i=0;$i<3;$i++){?>
    <tr>
        <td>
        <?php if($links[$i] !=""){
            if($linknames[$i]==""){
                $linknames[$i] == "관련법령".($i+1);
            }
            echo "<a href='".$links[$i]."' target='_blank'>".$linknames[$i]."</a>";
        }else{
            echo "-";
        }?>
        </td>
        <td>
            <?php if($item1[$i]!="" || $pdf1[$i] != ""){
                if($pdf1[$i]!=""){?>
                    <?php echo $pdf1name[$i];?> <input type="button" onclick="fnFileView('<?php echo $pdf1[$i];?>','<?php echo $pdf1name[$i];?>')" value="보기">
            <?php }
                }else{ ?>
                -
            <?php }?>
        </td>
        <!--<td>
            <?php /*if($etcs[$i] !=""){
                if($etcnames[$i]==""){
                    $etcnames[$i] == "사례".($i+1);
                }
                echo "<a href='".$etcs[$i]."' target='_blank'>".$etcnames[$i]."</a>"; 
            }else{
                echo "-";
            }*/?>
        </td>-->
        <td>
            <?php if($item2[$i]!="" || $pdf2[$i] != ""){
                if($pdf2[$i]!=""){?>
                    <?php echo $pdf2name[$i];?> <input type="button" onclick="fnFileView('<?php echo $pdf2[$i];?>','<?php echo $pdf2name[$i];?>')" value="보기">
                <?php }
            }else{ ?>
                -
            <?php }?>
        </td>
    </tr>
    <?php }?>
</table>
<script>
    var owl = $("#images");
    function fnFileView(file,filename){
        $("#previewer").attr("src",file);
        $("#preview_title").html(filename);
    }
</script>
