<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!defined('G5_SET_TIME_LIMIT')) define('G5_SET_TIME_LIMIT', 0);
@set_time_limit(G5_SET_TIME_LIMIT);


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//==========================================================================================================================


function g5_path()
{
    $chroot = substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], dirname(__FILE__)));
    $result['path'] = str_replace('\\', '/', $chroot.dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? '' : ':'.$_SERVER['SERVER_PORT'];
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$g5_path = g5_path();

include_once($g5_path['path'].'/config.php');   // 설정 파일

unset($g5_path);


// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}


// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
        $pattern = G5_ESCAPE_PATTERN;
        $replace = G5_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}


//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if (get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(G5_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(G5_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(G5_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(G5_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================


// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);


// 완두콩님이 알려주신 보안관련 오류 수정
// $member 에 값을 직접 넘길 수 있음
$config = array();
$member = array();
$board  = array();
$group  = array();
$g5     = array();


//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = G5_DATA_PATH.'/'.G5_DBCONFIG_FILE;
if (file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
    include_once(G5_LIB_PATH.'/common.lib.php');    // 공통 라이브러리

    $connect_db = sql_connect(G5_MYSQL_HOST, G5_MYSQL_USER, G5_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(G5_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $g5 배열에 저장 - 명랑폐인님 제안
    $g5['connect_db'] = $connect_db;

    sql_set_charset('utf8', $connect_db);
    if(defined('G5_MYSQL_SET_MODE') && G5_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if (defined('G5_TIMEZONE')) sql_query(" set time_zone = '".G5_TIMEZONE."'");
} else {
?>

<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>오류! <?php echo G5_VERSION ?> 설치하기</title>
<link rel="stylesheet" href="install/install.css">
</head>
<body>

<div id="ins_bar">
    <span id="bar_img">GNUBOARD5</span>
    <span id="bar_txt">Message</span>
</div>
<h1>그누보드5를 먼저 설치해주십시오.</h1>
<div class="ins_inner">
    <p>다음 파일을 찾을 수 없습니다.</p>
    <ul>
        <li><strong><?php echo G5_DATA_DIR.'/'.G5_DBCONFIG_FILE ?></strong></li>
    </ul>
    <p>그누보드 설치 후 다시 실행하시기 바랍니다.</p>
    <div class="inner_btn">
        <a href="<?php echo G5_URL; ?>/install/"><?php echo G5_VERSION ?> 설치하기</a>
    </div>
</div>
<div id="ins_ft">
    <strong>GNUBOARD5</strong>
    <p>GPL! OPEN SOURCE GNUBOARD</p>
</div>

</body>
</html>

<?php
    exit;
}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0);    // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

session_save_path(G5_SESSION_PATH);

if (isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", G5_COOKIE_DOMAIN);

@session_start();
//==============================================================================


//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch(" select * from {$g5['config_table']} ");

define('G5_HTTP_BBS_URL',  https_url(G5_BBS_DIR, false));
define('G5_HTTPS_BBS_URL', https_url(G5_BBS_DIR, true));
if ($config['cf_editor'])
    define('G5_EDITOR_LIB', G5_EDITOR_PATH."/{$config['cf_editor']}/editor.lib.php");
else
    define('G5_EDITOR_LIB', G5_LIB_PATH."/editor.lib.php");

define('G5_CAPTCHA_DIR',    !empty($config['cf_captcha']) ? $config['cf_captcha'] : 'kcaptcha');
define('G5_CAPTCHA_URL',    G5_PLUGIN_URL.'/'.G5_CAPTCHA_DIR);
define('G5_CAPTCHA_PATH',   G5_PLUGIN_PATH.'/'.G5_CAPTCHA_DIR);

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if (isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(G5_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if (isset($_REQUEST['sca']))  {
    $sca = clean_xss_tags(trim($_REQUEST['sca']));
    if ($sca) {
        $sca = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", "", $sca);
        $qstr .= '&amp;sca=' . urlencode($sca);
    }
} else {
    $sca = '';
}

if (isset($_REQUEST['sfl']))  {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    if ($sfl)
        $qstr .= '&amp;sfl=' . urlencode($sfl); // search field (검색 필드)
} else {
    $sfl = '';
}


if (isset($_REQUEST['stx']))  { // search text (검색어)
    $stx = get_search_string(trim($_REQUEST['stx']));
    if ($stx || $stx === '0')
        $qstr .= '&amp;stx=' . urlencode(cut_str($stx, 20, ''));
} else {
    $stx = '';
}

if (isset($_REQUEST['sst']))  {
    $sst = trim($_REQUEST['sst']);
    $sst = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sst);
    if ($sst)
        $qstr .= '&amp;sst=' . urlencode($sst); // search sort (검색 정렬 필드)
} else {
    $sst = '';
}

if (isset($_REQUEST['sod']))  { // search order (검색 오름, 내림차순)
    $sod = preg_match("/^(asc|desc)$/i", $sod) ? $sod : '';
    if ($sod)
        $qstr .= '&amp;sod=' . urlencode($sod);
} else {
    $sod = '';
}

if (isset($_REQUEST['sop']))  { // search operator (검색 or, and 오퍼레이터)
    $sop = preg_match("/^(or|and)$/i", $sop) ? $sop : '';
    if ($sop)
        $qstr .= '&amp;sop=' . urlencode($sop);
} else {
    $sop = '';
}

if (isset($_REQUEST['spt']))  { // search part (검색 파트[구간])
    $spt = (int)$spt;
    if ($spt)
        $qstr .= '&amp;spt=' . urlencode($spt);
} else {
    $spt = '';
}

if (isset($_REQUEST['page'])) { // 리스트 페이지
    $page = (int)$_REQUEST['page'];
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

if (isset($_REQUEST['w'])) {
    $w = substr($w, 0, 2);
} else {
    $w = '';
}

if (isset($_REQUEST['wr_id'])) {
    $wr_id = (int)$_REQUEST['wr_id'];
} else {
    $wr_id = 0;
}

if (isset($_REQUEST['bo_table'])) {
    $bo_table = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['bo_table']));
    $bo_table = substr($bo_table, 0, 20);
} else {
    $bo_table = '';
}

// URL ENCODING
if (isset($_REQUEST['url'])) {
    $url = strip_tags(trim($_REQUEST['url']));
    $urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if (G5_DOMAIN) {
        $p = @parse_url(G5_DOMAIN);
        $urlencode = G5_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}

if (isset($_REQUEST['gr_id'])) {
    if (!is_array($_REQUEST['gr_id'])) {
        $gr_id = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['gr_id']));
    }
} else {
    $gr_id = '';
}
//===================================


// 자동로그인 부분에서 첫로그인에 포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if ($_SESSION['ss_mb_id']) { // 로그인중이라면
    $member = get_member($_SESSION['ss_mb_id']);

    // 차단된 회원이면 ss_mb_id 초기화
    if($member['mb_intercept_date'] && $member['mb_intercept_date'] <= date("Ymd", G5_SERVER_TIME)) {
        set_session('ss_mb_id', '');
        $member = array();
    } else {
        // 오늘 처음 로그인 이라면
        if (substr($member['mb_today_login'], 0, 10) != G5_TIME_YMD) {
            // 첫 로그인 포인트 지급
            insert_point($member['mb_id'], $config['cf_login_point'], G5_TIME_YMD.' 첫로그인', '@login', $member['mb_id'], G5_TIME_YMD);

            // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
            // 해당 회원의 접근일시와 IP 를 저장
            $sql = " update {$g5['member_table']} set mb_today_login = '".G5_TIME_YMDHIS."', mb_login_ip = '{$_SERVER['REMOTE_ADDR']}' where mb_id = '{$member['mb_id']}' ";
            sql_query($sql);
        }
    }
} else {
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if ($tmp_mb_id = get_cookie('ck_mb_id')) {

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if (strtolower($tmp_mb_id) != strtolower($config['cf_admin'])) {
            $sql = " select mb_password, mb_intercept_date, mb_leave_date, mb_email_certify from {$g5['member_table']} where mb_id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            if($row['mb_password']){
                $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['SERVER_SOFTWARE'] . $_SERVER['HTTP_USER_AGENT'] . $row['mb_password']);
                // 쿠키에 저장된 키와 같다면
                $tmp_key = get_cookie('ck_auto');
                if ($tmp_key === $key && $tmp_key) {
                    // 차단, 탈퇴가 아니고 메일인증이 사용이면서 인증을 받았다면
                    if ($row['mb_intercept_date'] == '' &&
                        $row['mb_leave_date'] == '' &&
                        (!$config['cf_use_email_certify'] || preg_match('/[1-9]/', $row['mb_email_certify'])) ) {
                        // 세션에 회원아이디를 저장하여 로그인으로 간주
                        set_session('ss_mb_id', $tmp_mb_id);

                        // 페이지를 재실행
                        echo "<script type='text/javascript'> window.location.reload(); </script>";
                        exit;
                    }
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}


$write = array();
$write_table = "";
if ($bo_table) {
    $board = sql_fetch(" select * from {$g5['board_table']} where bo_table = '$bo_table' ");
    if ($board['bo_table']) {
        set_cookie("ck_bo_table", $board['bo_table'], 86400 * 1);
        $gr_id = $board['gr_id'];
        $write_table = $g5['write_prefix'] . $bo_table; // 게시판 테이블 전체이름
        //$comment_table = $g5['write_prefix'] . $bo_table . $g5['comment_suffix']; // 코멘트 테이블 전체이름
        if (isset($wr_id) && $wr_id)
            $write = sql_fetch(" select * from $write_table where wr_id = '$wr_id' ");
    }
}

if ($gr_id) {
    $group = sql_fetch(" select * from {$g5['group_table']} where gr_id = '$gr_id' ");
}


// 회원, 비회원 구분
$is_member = $is_guest = false;
$is_admin = '';
if ($member['mb_id']) {
    $is_member = true;
    $is_admin = is_admin($member['mb_id']);
    $member['mb_dir'] = substr($member['mb_id'],0,2);
} else {
    $is_guest = true;
    $member['mb_id'] = '';
    $member['mb_level'] = 1; // 비회원의 경우 회원레벨을 가장 낮게 설정
}


if ($is_admin != 'super') {
    // 접근가능 IP
    $cf_possible_ip = trim($config['cf_possible_ip']);
    if ($cf_possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $cf_possible_ip);
        for ($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if (empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if ($is_possible_ip)
                break;
        }
        if (!$is_possible_ip)
            die ("<meta charset=utf-8>접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['cf_intercept_ip']));
    for ($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if (empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if ($is_intercept_ip)
            die ("<meta charset=utf-8>접근 불가합니다.");
    }
}


// 테마경로
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true)
    $config['cf_theme'] = trim($_GET['theme']);

if(isset($config['cf_theme']) && trim($config['cf_theme'])) {
    $theme_path = G5_PATH.'/'.G5_THEME_DIR.'/'.$config['cf_theme'];
    if(is_dir($theme_path)) {
        define('G5_THEME_PATH',        $theme_path);
        define('G5_THEME_URL',         G5_URL.'/'.G5_THEME_DIR.'/'.$config['cf_theme']);
        define('G5_THEME_MOBILE_PATH', $theme_path.'/'.G5_MOBILE_DIR);
        define('G5_THEME_LIB_PATH',    $theme_path.'/'.G5_LIB_DIR);
        define('G5_THEME_CSS_URL',     G5_THEME_URL.'/'.G5_CSS_DIR);
        define('G5_THEME_IMG_URL',     G5_THEME_URL.'/'.G5_IMG_DIR);
        define('G5_THEME_JS_URL',      G5_THEME_URL.'/'.G5_JS_DIR);
    }
    unset($theme_path);
}


// 테마 설정 로드
if(defined('G5_THEME_PATH') && is_file(G5_THEME_PATH.'/theme.config.php'))
    include_once(G5_THEME_PATH.'/theme.config.php');

//=====================================================================================
// 사용기기 설정
// 테마의 G5_THEME_DEVICE 설정에 따라 사용자 화면 제한됨
// 테마에 별도 설정이 없는 경우 config.php G5_SET_DEVICE 설정에 따라 사용자 화면 제한됨
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//-------------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('G5_THEME_DEVICE') && G5_THEME_DEVICE != '') {
    switch(G5_THEME_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}

if(defined('G5_SET_DEVICE') && $set_device) {
    switch(G5_SET_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}
//==============================================================================

//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
// G5_MOBILE_AGENT : config.php 에서 선언
//------------------------------------------------------------------------------
if (G5_USE_MOBILE && $set_device) {
    if ($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if ($_REQUEST['device']=='mobile')
        $is_mobile = true;
    else if (isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if (is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('G5_IS_MOBILE', $is_mobile);
define('G5_DEVICE_BUTTON_DISPLAY', $set_device);
if (G5_IS_MOBILE) {
    $g5['mobile_path'] = G5_PATH.'/'.$g5['mobile_dir'];
}
//==============================================================================


//==============================================================================
// 스킨경로
//------------------------------------------------------------------------------
if (G5_IS_MOBILE) {
    $board_skin_path    = get_skin_path('board', $board['bo_mobile_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_mobile_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_mobile_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_mobile_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_mobile_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_mobile_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_mobile_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_mobile_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_mobile_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_mobile_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_mobile_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_mobile_faq_skin']);
} else {
    $board_skin_path    = get_skin_path('board', $board['bo_skin']);
    $board_skin_url     = get_skin_url('board', $board['bo_skin']);
    $member_skin_path   = get_skin_path('member', $config['cf_member_skin']);
    $member_skin_url    = get_skin_url('member', $config['cf_member_skin']);
    $new_skin_path      = get_skin_path('new', $config['cf_new_skin']);
    $new_skin_url       = get_skin_url('new', $config['cf_new_skin']);
    $search_skin_path   = get_skin_path('search', $config['cf_search_skin']);
    $search_skin_url    = get_skin_url('search', $config['cf_search_skin']);
    $connect_skin_path  = get_skin_path('connect', $config['cf_connect_skin']);
    $connect_skin_url   = get_skin_url('connect', $config['cf_connect_skin']);
    $faq_skin_path      = get_skin_path('faq', $config['cf_faq_skin']);
    $faq_skin_url       = get_skin_url('faq', $config['cf_faq_skin']);
}
//==============================================================================


// 방문자수의 접속을 남김
include_once(G5_BBS_PATH.'/visit_insert.inc.php');


// 일정 기간이 지난 DB 데이터 삭제 및 최적화
include_once(G5_BBS_PATH.'/db_table.optimize.php');


// common.php 파일을 수정할 필요가 없도록 확장합니다.
$extend_file = array();
$tmp = dir(G5_EXTEND_PATH);
while ($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(G5_EXTEND_PATH.'/'.$file);
    }
    unset($file);
}
unset($extend_file);

ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

$html_process = new html_process();

if($is_member) {
    if($member["mb_level"]==6){
        $member["mb_auth"]=false;
        if(strpos($_SERVER["REQUEST_URI"],"/page/company/")!==false) {
        }else{
            if(strpos($_SERVER["REQUEST_URI"],"/bbs/logout")!==false) {

            }else if(strpos($_SERVER["REQUEST_URI"],"/ajax/")!==false){

            }else if(strpos($_SERVER["REQUEST_URI"],"/board/inquiry")!==false){
                //goto_url(G5_URL . "/page/company/");
            }else {
                goto_url(G5_URL . "/page/company/");
            }
        }
    }
    $member["mb_auth"]=true;
    //구매 확인 | 5일 무료 이용권 체크 | 이후 결제 여부 판단 | 결제가 있다면 현재 기간이 있는지 판단
    $todays = date("Y-m-d");
    $sql = "select count(*) as cnt from `cmap_payments` where mb_id = '{$member["mb_id"]}' and '{$todays}' BETWEEN payment_start_date and payment_end_date and order_cancel = 0";
    $chkMembeship = sql_fetch($sql);
    if($chkMembeship["cnt"]==0) {
        $member["mb_auth"]=false;
    }else{
        if(date("Y-m-d",strtotime("+ 5 day", strtotime($member["mb_datetime"]))) >= $todays){
            $member["mb_auth"]=false;
        }
    }

    if($member["mb_paused_status"]==1){
        $member["mb_auth"]=false;
    }

    if($is_admin){
        $member["mb_auth"]=true;
    }

    $sql = "select * from `cmap_my_current_construct` where mb_id = '{$member["mb_id"]}'";
    $current_const = sql_fetch($sql);

    if($current_const==null){
        $sql = "select id from `cmap_my_construct` where mb_id = '{$member["mb_id"]}'";
        $chkConst = sql_fetch($sql);
        if($chkConst==null){
            $sql = "select * from `cmap_my_construct` where members like '%{$member["mb_id"]}%' or manager_mb_id like '%{$member["mb_id"]}%' order by id desc";
            $res = sql_query($sql);
            while($row = sql_fetch_array($res)){
                $mbs = explode(",",$row["members"]);
                $currentchk = false;
                for($i=0;$i<count($mbs);$i++){
                    if($mbs[$i]==$member["mb_id"]){
                        $sql = "insert into `cmap_my_current_construct` set mb_id = '{$member["mb_id"]}', const_id = '{$row["id"]}'";
                        sql_query($sql);
                        $currentchk = true;
                        continue;
                    }
                }
                if($currentchk==false) {
                    $mbs2 = explode(",", $row["manager_mb_id"]);
                    for ($i = 0; $i < count($mbs2); $i++) {
                        if ($mbs2[$i] == $member["mb_id"]) {
                            $sql = "insert into `cmap_my_current_construct` set mb_id = '{$member["mb_id"]}', const_id = '{$row["id"]}'";
                            sql_query($sql);
                            continue;
                        }
                    }
                }
            }
        }else{
            $sql = "insert into `cmap_my_current_construct` set mb_id = '{$member["mb_id"]}', const_id = '{$chkConst["id"]}'";
            sql_query($sql);
        }

        $sql = "select * from `cmap_my_current_construct` where mb_id = '{$member["mb_id"]}'";
        $current_const = sql_fetch($sql);
    }

    /*if($member["mb_level"]==5){
        $constwhere = " or manager_mb_id = '{$member["mb_id"]}'";
    }*/

    //내 설정 가져오기
    $myset = sql_fetch("select * from `cmap_mymenu_theme` where mb_id ='{$member["mb_id"]}'");

    //퀵메뉴 로드
    $setquick = sql_fetch("select * from `cmap_myquick` where mb_id ='{$member["mb_id"]}'");
    $quickcnt = count($setquick);
    if ($quickcnt > 0) {
        $menuorder = explode("``", $setquick["quick_menu"]);
        $menuordername = explode("``", $setquick["quick_menu_name"]);
        $menuorderstatus = explode("``", $setquick["quick_menu_status"]);
        for ($i = 0; $i < count($menuorder); $i++) {
            if ($menuorderstatus[$i] == 1) {
                if($member["mb_level"]==5 && $menuordername[$i] == "현장관리"){continue;}
                $quickmenu[] = "<li class='quickmenus " . $menuorder[$i] . "'><label>" . $menuordername[$i] . "</label><div class='img'><img src='" . G5_IMG_URL . "/ic_" . $menuorder[$i] . ".svg' alt='" . $menuorder[$i] . "'></div><div class='clear'></div></li>";
            }
        }
    }

    //네비게이터 설정정보 유무 파악
    $sql = "select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}'";
    $mynavis = sql_query($sql);
    while($myrow = sql_fetch_array($mynavis)){
        $setNavi[] = $myrow;
    }
    if(count($setNavi) < 5){
        //
        $navis1 = sql_fetch("select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '10'");
        $navis2 = sql_fetch("select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '30'");
        $navis3 = sql_fetch("select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '40'");
        $navis4 = sql_fetch("select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '50'");
        $navis5 = sql_fetch("select * from `cmap_navigator` where mb_id = '{$member["mb_id"]}' and menu_code = '60'");

        if($navis1==null){
            $sql = "insert into `cmap_navigator` set menu_ids = '5``7``8``9``10``11``12``13', menu_ids_actives = '1``1``1``1``1``1``1``1', sub_ids = '', sub_ids_actives = '' , mb_id = '{$member["mb_id"]}', menu_code = '10', update_date = now(), update_time = now()";
            //echo $sql;
            sql_query($sql);
        }

        if($navis2==null){
            $sql = "insert into `cmap_navigator` set menu_ids = '35``97``49``50``51``52``53``54``95``94``47``46``36``37``38``39``45``40``41``42``96``44``57', menu_ids_actives = '1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1', sub_ids = '51|142``51|143``51|144``52|525``52|526``53|146``53|147``53|148``53|149``95|151``95|152``95|153``95|154``95|155``95|156``95|157``47|159``47|160``46|161``46|162``46|163``46|164``36|165``36|166``36|167``36|168``36|169``36|170``96|177``96|178', sub_ids_actives = '1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1' , mb_id = '{$member["mb_id"]}', menu_code = '30', update_date = now(), update_time = now()";
            //echo $sql;
            sql_query($sql);
        }

        if($navis3==null){
            $sql = "insert into `cmap_navigator` set menu_ids = '121``60``125``104``105``106``107``108``109``110``111``112``113``114``115``116``117``118``119``120', menu_ids_actives = '1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1', sub_ids = '121|428``121|429``121|430``121|431``121|432``121|433``121|434``60|308``60|309``60|310``60|311``60|312``125|500``125|501``125|502``125|503``125|504``125|505``104|318``104|319``104|320``104|321``105|322``105|323``105|324``105|325``105|326``106|327``106|328``106|329``106|330``106|331``106|332``107|333``107|334``107|335``107|336``107|337``107|338``107|339``108|340``108|341``108|342``108|343``108|344``108|345``109|346``109|347``109|348``109|349``110|408``110|409``110|410``110|411``110|412``110|413``110|414``110|415``110|416``110|417``110|418``110|419``110|420``110|421``110|422``110|423``110|424``110|425``110|426``110|427``111|350``111|351``111|352``112|478``112|479``112|477``113|355``113|356``113|357``113|358``113|359``114|360``114|361``114|362``114|363``114|364``114|365``114|366``114|367``114|368``114|369``115|370``115|371``115|372``116|450``116|451``116|452``117|374``117|375``117|376``117|377``118|378``118|379``118|380``118|381``118|382``118|383``118|384``118|385``118|386``118|387``118|388``118|389``119|390``119|391``120|392``120|393``120|394``120|395``120|396``120|397``120|398``120|399``120|400``120|401``120|402``120|403``120|404``120|405``120|406``120|407', sub_ids_actives = '1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1``1' , mb_id = '{$member["mb_id"]}', menu_code = '40', update_date = now(), update_time = now()";
            //echo $sql;
            sql_query($sql);
        }

        if($navis4==null){
            $sql = "insert into `cmap_navigator` set menu_ids = '58', menu_ids_actives = '1', sub_ids = '', sub_ids_actives = '' , mb_id = '{$member["mb_id"]}', menu_code = '50', update_date = now(), update_time = now()";
            //echo $sql;
            sql_query($sql);
        }

        if($navis5==null){
            $sql = "insert into `cmap_navigator` set menu_ids = '64``129', menu_ids_actives = '1``1', sub_ids = '64|511``64|510``64|509``129|517``129|518``129|519``129|520``129|521``129|522``129|523``129|524', sub_ids_actives = '1``1``1``1``1``1``1``1``1``1``1' , mb_id = '{$member["mb_id"]}', menu_code = '60', update_date = now(), update_time = now()";
            //echo $sql;
            sql_query($sql);
        }
    }
}
//navigator
/*$navisql = "select * from `cmap_menu` where menu_status = 0 and menu_depth = 0 order by menu_order ";
$navires = sql_query($navisql);
while($navirow=sql_fetch_array($navires)){
    $menulist[] = $navirow;
}*/

$hanlist = array("가","나","다","라","마","바","사","아","자","차","타","카","파","하","거","너","더","러","머","버","서","어","저","처","터","커","퍼","허");

?>


