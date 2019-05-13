<?php
include_once ("./_common.php");
include_once (G5_PATH."/admin/admin.head.php");

$new_member_rows = 5;
$new_point_rows = 5;
$new_write_rows = 5;

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

if ($is_admin != 'super')
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
$result = sql_query($sql);

$colspan = 12;
?>
<div id="wrap">
    <section>
        <div class="admin_title">
            <h2>신규회원 목록</h2>
            <span class="more" onclick="location.href='./member_list'"> + 더보기</span>
        </div>
        <div class="admin_content">
            <div class="tbl_head01 tbl_wrap">
                <table>
                    <caption>신규가입회원</caption>
                    <thead>
                    <tr>
                        <th scope="col">회원아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col">권한</th>
                        <th scope="col">포인트</th>
                        <th scope="col">수신</th>
                        <th scope="col">공개</th>
                        <th scope="col">인증</th>
                        <th scope="col">차단</th>
                        <th scope="col">그룹</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i=0; $row=sql_fetch_array($result); $i++)
                    {
                        // 접근가능한 그룹수
                        $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                        $row2 = sql_fetch($sql2);
                        $group = "";
                        if ($row2['cnt'])
                            $group = '<a href="./boardgroupmember_form?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

                        if ($is_admin == 'group')
                        {
                            $s_mod = '';
                            $s_del = '';
                        }
                        else
                        {
                            $s_mod = '<a href="./member_form?$qstr&amp;w=u&amp;mb_id='.$row['mb_id'].'">수정</a>';
                            $s_del = '<a href="./member_delete?'.$qstr.'&amp;w=d&amp;mb_id='.$row['mb_id'].'&amp;url='.$_SERVER['SCRIPT_NAME'].'" onclick="return delete_confirm(this);">삭제</a>';
                        }
                        $s_grp = '<a href="./boardgroupmember_form?mb_id='.$row['mb_id'].'">그룹</a>';

                        $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
                        $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

                        $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                        $mb_id = $row['mb_id'];
                        if ($row['mb_leave_date'])
                            $mb_id = $mb_id;
                        else if ($row['mb_intercept_date'])
                            $mb_id = $mb_id;

                        ?>
                        <tr>
                            <td class="td_mbid"><?php echo $mb_id ?></td>
                            <td class="td_mbname"><?php echo get_text($row['mb_name']); ?></td>
                            <td class="td_mbname sv_use"><div><?php echo $mb_nick ?></div></td>
                            <td class="td_num"><?php echo $row['mb_level'] ?></td>
                            <td><a href="./point_list?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td>
                            <td class="td_boolean"><?php echo $row['mb_mailling']?'예':'아니오'; ?></td>
                            <td class="td_boolean"><?php echo $row['mb_open']?'예':'아니오'; ?></td>
                            <td class="td_boolean"><?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'예':'아니오'; ?></td>
                            <td class="td_boolean"><?php echo $row['mb_intercept_date']?'예':'아니오'; ?></td>
                            <td class="td_category"><?php echo $group ?></td>
                        </tr>
                        <?php
                    }
                    if ($i == 0)
                        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="btn_list03 btn_list">
                <a href="./member_list">회원 전체보기</a>
            </div>
        </div>
    </section>
</div>
<?php
include_once (G5_PATH."/admin/admin.tail.php")
?>
