<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$sql = "select * from `g5_member` where mb_id != 'admin' order by mb_datetime desc limit 0, 5;";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $members[] = $row;
}


?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>신규회원 목록</h2>
            <span class="more"> + 더보기</span>
        </div>
        <div class="admin_content">

        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/admin/admin.tail.php")
?>
