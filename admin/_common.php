<?php
define('G5_IS_ADMIN', true);
include_once ("../common.php");
include_once(G5_PATH.'/admin/admin.lib.php');
if( isset($token) ){
    $token = @htmlspecialchars(strip_tags($token), ENT_QUOTES);
}
?>