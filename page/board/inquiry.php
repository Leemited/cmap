<?php
include_once ("../../common.php");

$bbody = "board";
$sub = "sub";
$mypage = true;
$menu_id = 'depth_desc_inquiry';
if($member["mb_level"]!= 6) {
    include_once(G5_PATH . "/_head.php");
}else{
    include_once (G5_PATH."/head.com.php");
}
// FAQ MASTER
$faq_master_list = array();
$sql = " select * from {$g5['faq_master_table']} order by fm_order,fm_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result))
{
    $key = $row['fm_id'];
    if (!$fm_id) $fm_id = $key;
    $faq_master_list[$key] = $row;
}

if ($fm_id){
    $qstr .= '&amp;fm_id=' . $fm_id; // 마스터faq key_id
}

$fm = $faq_master_list[$fm_id];
if (!$fm['fm_id'])
    alert('등록된 내용이 없습니다.');

$admin_href = '';
$himg_src = '';
$timg_src = '';
if($is_admin)
    $admin_href = G5_ADMIN_URL.'/faqmasterform?w=u&amp;fm_id='.$fm_id;

if(!G5_IS_MOBILE) {
    $himg = G5_DATA_PATH.'/faq/'.$fm_id.'_h';
    if (is_file($himg)){
        $himg_src = G5_DATA_URL.'/faq/'.$fm_id.'_h';
    }

    $timg = G5_DATA_PATH.'/faq/'.$fm_id.'_t';
    if (is_file($timg)){
        $timg_src = G5_DATA_URL.'/faq/'.$fm_id.'_t';
    }
}

$category_href = G5_BBS_URL.'/faq.php';
$category_stx = '';
$faq_list = array();

$stx = trim($stx);
$sql_search = '';

if($stx) {
    $sql_search = " and ( INSTR(fa_subject, '$stx') > 0 or INSTR(fa_content, '$stx') > 0 ) ";
}

if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)

$page_rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];

$sql = " select count(*) as cnt
                from {$g5['faq_table']}
                where fm_id = '$fm_id'
                  $sql_search ";
$total = sql_fetch($sql);
$total_count = $total['cnt'];

$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$sql = " select *
                from {$g5['faq_table']}
                where fm_id = '$fm_id'
                  $sql_search
                order by fa_order , fa_id
                limit $from_record, $page_rows ";
$result = sql_query($sql);
for ($i=0;$row=sql_fetch_array($result);$i++){
    $faq_list[] = $row;
    if($stx) {
        $faq_list[$i]['fa_subject'] = search_font($stx, conv_content($faq_list[$i]['fa_subject'], 1));
        $faq_list[$i]['fa_content'] = search_font($stx, conv_content($faq_list[$i]['fa_content'], 1));
    }
}

$today = date("Y-m-d");
if($payments){
    if($od_type=="pm") {
        $text = "PM회원 일괄 결제 문의 \r\n\r\n";
    }else{
        $text = "CM회원 일괄 결제 문의 \r\n\r\n";
    }
    $text .= "다음회원의 결제를 요청합니다.\r\n\r\n";
    $data1 = explode(",",$payments);
    for($i=0;$i<count($data1);$i++){
        $texts = explode("``",$data1[$i]);
        $sql = "select * from `cmap_payments` where order_cancel = 0 and '{$today}' between payment_start_date and payment_end_date and mb_id = '{$texts[1]}' ";//구매 이력 확인
        $mbchk = sql_fetch($sql);
        if($mbchk==null){
            $ty = "신규결제";
        }else{
            $ty = "연장결제";
        }
        $text .= $texts[1]. " : ". $texts[2]."개월 [".$ty."]\r\n";
        $payments_mb_id[] = $texts[1];
    }
}
?>
<div class="width-fixed">
    <header class="sub">
        <h2>제안하기</h2>
    </header>
    <div id="faq_wrap" class="faq_<?php echo $fm_id; ?>">
        <h3 class="sub_title">자주 묻는 질문 TOP 10</h3>
        <?php // FAQ 내용
        if( count($faq_list) ){
            ?>
            <section id="faq_con">
                <ul>
                    <?php
                    foreach($faq_list as $key=>$v){
                        if(empty($v))
                            continue;
                        ?>
                        <li>
                            <h3 onclick="return faq_open(this);"><span class="tit_bg">Q</span><a href="#none" ><?php echo conv_content($v['fa_subject'], 1); ?></a></h3>
                            <div class="con_inner">
                                <!--<span class="tit_bg">A</span>-->
                                <?php echo conv_content($v['fa_content'], 1); ?>
                                <!-- <div class="con_closer"><button type="button" class="closer_btn btn_b03">닫기</button></div> -->
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </section>
            <?php

        } else {
            if($stx){
                echo '<p class="empty_list">검색된 게시물이 없습니다.</p>';
            } else {
                echo '<div class="empty_list">등록된 FAQ가 없습니다.';
                if($is_admin)
                    echo '<br><a href="'.G5_ADMIN_URL.'/faqmasterlist">FAQ를 새로 등록하시려면 FAQ관리</a> 메뉴를 이용하십시오.';
                echo '</div>';
            }
        }
        ?>
    </div>
    <div class="inquiry_wrap">
        <h3 class="sub_title">문의/제안하기</h3>
        <form action="<?php echo G5_URL?>/page/board/inquiry_update" method="post" enctype="multipart/form-data">
            <input type="hidden" name="payments" id="payments" value="<?php echo $payments;?>">
            <input type="hidden" name="payments_mb_id" id="payments_mb_id" value="<?php echo implode(",",$payments_mb_id);?>">
        <table>
            <tr>
                <th>성명 <span>*</span></th>
                <td>
                    <input type="text" name="name" id="name" class="basic_input01 width100" required value="<?php echo $member["mb_name"];?>">
                </td>
            </tr>
            <tr>
                <th>이메일 <span>*</span></th>
                <td>
                    <input type="text" name="email" id="email1" class="basic_input01 width30" required> @
                    <input type="text" name="email2" id="email2" class="basic_input01 width30" required>
                    <select name="inquiry_type" id="inquiry_type" class="basic_input01" onchange="$('#email2').val(this.value)">
                        <option value="">직접입력</option>
                        <option value="naver.com">naver.com</option>
                        <option value="gmail.com">gmail.com</option>
                        <option value="daum.net">daum.net</option>
                        <option value="hanmail.net">hanmail.net</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>유형 <span>*</span></th>
                <td>
                    <select name="inquiry_type" id="inquiry_type" class="basic_input01" required >
                        <option value="문의">문의</option>
                        <option value="제안">제안</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>내용 <span>*</span></th>
                <td>
                    <textarea name="content" id="content" cols="30" rows="10" class="basic_input01 width100" style="resize: none" required><?php echo $text;?></textarea>
                </td>
            </tr>
            <tr>
                <th>첨부파일</th>
                <td class="files">
                    <input type="file" name="file" id="file" onchange="$('#filename').html(this.value + ' [파일 변경]')">
                    <label for="file" id="filename">+ 파일 첨부</label>
                    <p>
                        * 파일첨부는 <strong>JPG, GIF, PNG, DOCS, XLSX, PPTX, HWP, PDF</strong>만 가능하며<br>
                        * 파일첨부용량은 <strong>5MB</strong>로 제한합니다.
                    </p>
                </td>
            </tr>
        </table>
        <div class="inquiry_btns">
            <input type="submit" value="제안/문의 등록" class="basic_btn01">
        </div>
        </form>
    </div>
</div>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
    $(function() {
        $(".closer_btn").on("click", function() {
            $(this).closest(".con_inner").slideToggle();
        });
    });

    function faq_open(el)
    {
        var $con = $(el).closest("li").find(".con_inner");

        if($con.is(":visible")) {
            $con.slideUp();

        } else {
            $("#faq_con .con_inner:visible").css("display", "none");

            $con.slideDown(
                function() {
                    // 이미지 리사이즈
                    $con.viewimageresize2();
                }
            );
        }

        return false;
    }
</script>
<!-- } FAQ 끝 -->
<?php
if($member["mb_level"]!=6){
    include_once (G5_PATH."/_tail.php");
}else{
    include_once (G5_PATH."/tail.com.php");
}
?>
