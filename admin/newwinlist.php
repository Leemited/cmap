<?php
include_once('./_common.php');

if( !isset($g5['new_win_table']) ){
    die('<meta charset="utf-8">/data/dbconfig.php 파일에 <strong>$g5[\'new_win_table\'] = G5_TABLE_PREFIX.\'new_win\';</strong> 를 추가해 주세요.');
}
//내용(컨텐츠)정보 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE {$g5['new_win_table']} ", false)) {
    if(sql_query(" DESCRIBE {$g5['g5_shop_new_win_table']} ", false)) {
        sql_query(" ALTER TABLE {$g5['g5_shop_new_win_table']} RENAME TO `{$g5['new_win_table']}` ;", false);
    } else {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['new_win_table']}` (
                      `nw_id` int(11) NOT NULL AUTO_INCREMENT,
                      `nw_device` varchar(10) NOT NULL DEFAULT 'both',
                      `nw_begin_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                      `nw_end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                      `nw_disable_hours` int(11) NOT NULL DEFAULT '0',
                      `nw_left` int(11) NOT NULL DEFAULT '0',
                      `nw_top` int(11) NOT NULL DEFAULT '0',
                      `nw_height` int(11) NOT NULL DEFAULT '0',
                      `nw_width` int(11) NOT NULL DEFAULT '0',
                      `nw_subject` text NOT NULL,
                      `nw_content` text NOT NULL,
                      `nw_content_html` tinyint(4) NOT NULL DEFAULT '0',
                      PRIMARY KEY (`nw_id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
    }
}

$g5['title'] = '팝업레이어 관리';
include_once (G5_PATH.'/admin/admin.head.php');

$sql_common = " from {$g5['new_win_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * $sql_common order by nw_id desc ";
$result = sql_query($sql);
?>
<div id="wrap">
    <section style="position: relative">
        <div class="admin_title">
            <h2>레이어팝업 관리</h2>
            <div class="newwin_btn_top">
                <input type="button" onclick="location.href='./newwinform'" class="newwin_btn" value="추가하기">
            </div>
        </div>
        <div class="clear"></div>
        <div class="admin_content">
            <div class="edit_content">
                <table class="image_table">
                <tr>
                    <th>번호</th>
                    <th>제목</th>
                    <th>접속기기</th>
                    <th>시작일시</th>
                    <th>종료일시</th>
                    <th>시간</th>
                    <th>Left</th>
                    <th>Top</th>
                    <th>Width</th>
                    <th>Height</th>
                    <th>관리</th>
                </tr>
                <?php
                for ($i=0; $row=sql_fetch_array($result); $i++) {
                    switch($row['nw_device']) {
                        case 'pc':
                            $nw_device = 'PC';
                            break;
                        case 'mobile':
                            $nw_device = '모바일';
                            break;
                        default:
                            $nw_device = '모두';
                            break;
                    }
                ?>
                <tr >
                    <td class="td_center"><?php echo $row['nw_id']; ?></td>
                    <td class="td_center"><?php echo $row['nw_subject']; ?></td>
                    <td class="td_center"><?php echo $nw_device; ?></td>
                    <td class="td_center"><?php echo substr($row['nw_begin_time'],2,14); ?></td>
                    <td class="td_center"><?php echo substr($row['nw_end_time'],2,14); ?></td>
                    <td class="td_center"><?php echo $row['nw_disable_hours']; ?>시간</td>
                    <td class="td_center"><?php echo $row['nw_left']; ?>px</td>
                    <td class="td_center"><?php echo $row['nw_top']; ?>px</td>
                    <td class="td_center"><?php echo $row['nw_width']; ?>px</td>
                    <td class="td_center"><?php echo $row['nw_height']; ?>px</td>
                    <td class="td_center">
                        <input type="button" onclick="location.href='./newwinform.php?w=u&amp;nw_id=<?php echo $row['nw_id']; ?>'" value="수정" style="display:inline-block;position:relative;top:inherit;margin-top:0">
                        <input type="button" onclick="delete_newwin('./newwinformupdate.php?w=d&amp;nw_id=<?php echo $row['nw_id']; ?>');" value="삭제" style="display:inline-block;position:relative;top:inherit;margin-top:0">
                    </td>
                </tr>
                <?php
                }

                if ($i == 0) {
                    echo '<tr><td colspan="11" class="empty_table">자료가 한건도 없습니다.</td></tr>';
                }
                ?>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
    function delete_newwin(link){
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")){
            location.href=link;
        }
    }
</script>
<?php
include_once (G5_PATH.'/admin/admin.tail.php');
?>
