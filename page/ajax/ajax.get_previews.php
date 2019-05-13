<?php
include_once ("../../common.php");
/*
$filename = str_replace(G5_DATA_URL."/cmap_content/","",$filess);
$fi = explode(".",$filename);

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

            $attachment[$i] = iconv("UTF-8","EUC-KR",$attachment[$i]);

            $pdf1[] = G5_DATA_URL."/cmap_content/".$attachment[$i];

            if($attachmentname[$i]!="") {
                $pdf1name[] = $attachmentname[$i].".".$ext;
            }else{
                $pdf1name[] = "첨부파일".$i;
            }
        }
    }
}*/

@closedir($handle);
?>
<h2 id="preview_title"><?php echo $filenames;?></h2>
<iframe src="<?php echo $filess;?>" frameborder="0" width="100%" height="95%" id="previewer"></iframe>
<?php /*if(count($pdfimgs[$fi[0]]) > 10){*/?><!--<div class="page">페이지 바로 가기 <input type="text" onchange="location.href='#'+this.value"> / <?php /*echo count($pdfimgs[$fi[0]])-1; */?></div><?php /*}*/?>
<div style="width:100%;height:95%;overflow-y:scroll">
    <?php /*for($i=0;$i<count($pdfimgs[$fi[0]]);$i++){*/?>
        <img src="<?php /*echo G5_DATA_URL."/cmap_content/".$pdfimgs[$fi[0]][$i];*/?>" alt="" style="width: 100%;margin:4px 0;border:1px solid #ddd;padding:50px;" id="<?php /*echo $i;*/?>">
    <?php /*}*/?>
</div>-->
