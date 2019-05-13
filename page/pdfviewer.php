<?php
include_once ("../common.php");
$filepath = G5_DATA_PATH."/cmap_content/".$filename;

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="'.$filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filepath));
header('Accept-Ranges: bytes');

@readfile($filepath);
?>