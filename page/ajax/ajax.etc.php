<?php
include_once ("../../common.php");

$sql = "select * from `cmap_content` where pk_id = '{$pk_id}'";
$etc = sql_fetch($sql);

if($etc["link"]!=""){
    $links = explode("``",$etc["link"]);
    $linknames = explode("``",$etc["linkname"]);
}

if($etc["etc1"]!=""){
    $etcs = explode("``",$etc["etc1"]);
    $etcnames = explode("``",$etc["etcname1"]);
}

if($etc["attachment"]!=""){
    $attachment = explode("``",$etc["attachment"]);
    $attachmentname = explode("``",$etc["attachmentname1"]);
    for($i=0;$i<count($attachment);$i++){
        if($attachment[$i]!=""){
            $ext = array_pop(explode(".",$attachment[$i]));
            $fi = explode(".",$attachment[$i]);
            if(strpos($ext,"jpge,jpg,gif,png")===false){
                $dir = G5_DATA_PATH."/cmap_content";
                $handle = @opendir($dir);

                if(is_dir($dir)){
                    while(false!==($file=readdir($handle))){
                        if(strpos($file,$fi[0])!==false && ($file != '.' || $file != '..') && strpos($file,"pdf")===false){
                            if(strpos($file,"-")!==false){
                                $n = explode("-",$file);
                                $new = explode(".",$n[1]);
                                $pdfimgs[$fi[0]][$new[0]] = $file;
                            }else {
                                $pdfimgs[$fi[0]][] = $file;
                            }
                        }
                    }
                }

                @closedir($handle);

                //$attachment[$i] = iconv("UTF-8","8859_1",$attachment[$i]);

                $pdf1[] = G5_DATA_URL."/cmap_content/".$attachment[$i];

                if($attachmentname[$i]!="") {
                    $pdf1name[] = $attachmentname[$i].".".$ext;
                }else{
                    $pdf1name[] = "첨부파일".$i;
                }
            }
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
            }
        }
    }
}
?>
<div class="previews">
    <?php if(count($pdf1) > 0 || count($pdf2) > 0){
        $file0 = str_replace("https://xn--z69akkg7o1wgdnk53m.com:443/data/cmap_content/","",$pdf1[0]);
        $fi = explode(".",$file0);
        if($pdf1[0] != ""){
            //echo $pdf1[0];
            //sort($pdfimgs[$fi[0]]);
            //$pdf1[0] = iconv("UTF-8","8859_1",$pdf1[0]);
        ?>
        <h2 id="preview_title"><?php echo $pdf1name[0];?></h2>
        <iframe src="<?php echo $pdf1[0];?>" frameborder="0" width="100%" height="95%" id="previewer" title="<?php echo $pdf1name;?>"></iframe>
            <!--<div style="width:100%;height:95%;overflow-y:scroll">
                <?php /*for($i=0;$i<count($pdfimgs[$fi[0]]);$i++){*/?>
                    <img src="<?php /*echo G5_DATA_URL."/cmap_content/".$pdfimgs[$fi[0]][$i];*/?>" alt="" style="width: 100%;margin:4px 0;border:1px solid #ddd;padding:50px;" id="<?php /*echo $i;*/?>">
                <?php /*}*/?>
            </div>-->
        <?php }else{?>
            <div class="no_images">
                <div class="trs">
                    <div class="tds">미리보기 없음</div>
                </div>
            </div>
        <?php }?>
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
        <th><img src="<?php echo G5_IMG_URL;?>/ic_links2.svg" alt="">관련법령</th>
        <th><img src="<?php echo G5_IMG_URL;?>/ic_preview.svg" alt="">미리보기</th>
        <th><img src="<?php echo G5_IMG_URL;?>/ic_attach2.svg" alt="">다운로드</th>
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
                    <a href="javascript:fnFileView('<?php echo $pdf1[$i];?>','<?php echo $pdf1name[$i];?>')"><?php echo $pdf1name[$i];?></a>
            <?php }
                }else{ ?>
                -
            <?php }?>
        </td>
        <td>
            <?php if($item2[$i]!="" || $pdf2[$i] != ""){
                if($pdf2[$i]!=""){
                    $downfile = explode(".",$pdf2name[$i]);
                    $dwfilename = $downfile[0];
                    $dwfile = str_replace("https://xn--z69akkg7o1wgdnk53m.com:443/data/cmap_content/","",$pdf2[$i]);
                    $dwfile = str_replace("http://xn--z69akkg7o1wgdnk53m.com:443/data/cmap_content/","",$pdf2[$i]);
                    $dwfile = str_replace("https://xn--z69akkg7o1wgdnk53m.com/data/cmap_content/","",$dwfile);
                    $dwfile = str_replace("http://xn--z69akkg7o1wgdnk53m.com/data/cmap_content/","",$dwfile);
                    ?>
                    <a href="<?php echo G5_URL;?>/page/view_download.php?file=<?php echo $dwfile;?>&filename=<?php echo $dwfilename;?>"><?php echo $pdf2name[$i];?></a> <!--<input type="button" onclick="fnFileView('<?php /*echo $pdf2[$i];*/?>','<?php /*echo $pdf2name[$i];*/?>')" value="보기">-->
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
        $.ajax({
            url:g5_url+'/page/ajax/ajax.get_previews.php',
            method:"post",
            data:{filess:file,filenames:filename}
        }).done(function(data){
            console.log(data);
            $(".previews").html(data);
        });
        //$("#previewer").attr("src",file);
        //$("#preview_title").html(filename);

    }

    var getAcrobatInfo = function () {

        var getBrowserName = function () {
            return this.name = this.name || function () {
                    var userAgent = navigator ? navigator.userAgent.toLowerCase() : "other";

                    if (userAgent.indexOf("chrome") > -1) { return "chrome"; }
                    else if (userAgent.indexOf("safari") > -1) { return "safari"; }
                    else if (userAgent.indexOf("msie") > -1 || userAgent.indexOf("trident") > -1) { return "ie"; }
                    else if (userAgent.indexOf("firefox") > -1) { return "firefox";}
                    return userAgent;
                }();
        };

        var getActiveXObject = function (name) {
            try { return new ActiveXObject(name); } catch (e) { }
        };

        var getNavigatorPlugin = function (name) {
            try {
                for (key in navigator.plugins) {
                    var plugin = navigator.plugins[key];
                    if (plugin.name.toLowerCase().indexOf(name) > -1) { return plugin; }
                }
            } catch (e) {

            }

        };

        var getPDFPlugin = function () {
            return this.plugin = this.plugin || function () {
                    if (getBrowserName() == 'ie') {
                        return getActiveXObject('AcroPDF.PDF') || getActiveXObject('PDF.PdfCtrl');
                    }
                    else {
                        return getNavigatorPlugin('adobe acrobat') || getNavigatorPlugin('pdf') || getNavigatorPlugin('foxit reader');  // works for all plugins which has word like 'adobe acrobat', 'pdf' and 'foxit reader'.
                    }
                }();
        };

        var isAcrobatInstalled = function () {
            return !!getPDFPlugin();
        };

        var getAcrobatVersion = function () {
            try {
                var plugin = getPDFPlugin();

                if (getBrowserName() == 'ie') {
                    var versions = plugin.GetVersions().split(',');
                    var latest = versions[0].split('=');
                    return parseFloat(latest[1]);
                }

                if (plugin.version) return parseInt(plugin.version);
                return plugin.name

            }
            catch (e) {
                return null;
            }
        };

        return {
            browser: getBrowserName(),      // Return browser name
            acrobat: isAcrobatInstalled() ? true : false,   // return pdf viewer is enabled or not
            acrobatVersion: getAcrobatVersion()  // reurn acrobat version for browser
        };
    }

</script>
