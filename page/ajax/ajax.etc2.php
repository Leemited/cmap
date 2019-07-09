<?php
include_once ("../../common.php");

$sql = "select * from `cmap_content` where pk_id = '{$pk_id}'";
$etc = sql_fetch($sql);


$attachment = explode("``",$etc["attachment3"]);
$attachmentname = explode("``",$etc["attachmentname3"]);
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

            $attachment[$i] = iconv("UTF-8","EUC-KR",$attachment[$i]);

            $pdf1[] = G5_DATA_URL."/cmap_content/".$attachment[$i];

            if($attachmentname[$i]!="") {
                $pdf1name[] = $attachmentname[$i].".".$ext;
            }else{
                $pdf1name[] = "감사사례".$i;
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
            //sort($pdfimgs[$fi[0]]);
        ?>
        <h2 id="preview_title"><?php echo $pdf1name[0];?></h2>
            <?php /*if(count($pdfimgs[$fi[0]]) > 10){*/?><!--<div class="page">페이지 바로 가기 <input type="text" onchange="location.href='#'+this.value"> / <?php /*echo count($pdfimgs[$fi[0]])-1; */?></div>--><?php /*}*/?>
            <iframe src="<?php echo $pdf1[0];?>" frameborder="0" width="100%" height="95%" id="previewer"></iframe>
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
        <th><img src="<?php echo G5_IMG_URL;?>/ic_etc2.svg" alt="">감사사례</th>
    </tr>
    <?php for($i=0;$i<3;$i++){?>
    <tr>
        <td>
            <?php if($item1[$i]!="" || $pdf1[$i] != ""){
                if($pdf1[$i]!=""){?>
                    <a href="javascript:fnFileView('<?php echo $pdf1[$i];?>','<?php echo $pdf1name[$i];?>')"><?php echo $pdf1name[$i];?></a>
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
