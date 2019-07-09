// 전역 변수
var errmsg = "";
var errfld = null;

// 필드 검사
function check_field(fld, msg)
{
    if ((fld.value = trim(fld.value)) == "")
        error_field(fld, msg);
    else
        clear_field(fld);
    return;
}

// 필드 오류 표시
function error_field(fld, msg)
{
    if (msg != "")
        errmsg += msg + "\n";
    if (!errfld) errfld = fld;
    fld.style.background = "#BDDEF7";
}

// 필드를 깨끗하게
function clear_field(fld)
{
    fld.style.background = "#FFFFFF";
}

function trim(s)
{
    var t = "";
    var from_pos = to_pos = 0;

    for (i=0; i<s.length; i++)
    {
        if (s.charAt(i) == ' ')
            continue;
        else
        {
            from_pos = i;
            break;
        }
    }

    for (i=s.length; i>=0; i--)
    {
        if (s.charAt(i-1) == ' ')
            continue;
        else
        {
            to_pos = i;
            break;
        }
    }

    t = s.substring(from_pos, to_pos);
    //				alert(from_pos + ',' + to_pos + ',' + t+'.');
    return t;
}

// 자바스크립트로 PHP의 number_format 흉내를 냄
// 숫자에 , 를 출력
function number_format(data)
{

    var tmp = '';
    var number = '';
    var cutlen = 3;
    var comma = ',';
    var i;
    
    data = data + '';

    var sign = data.match(/^[\+\-]/);
    if(sign) {
        data = data.replace(/^[\+\-]/, "");
    }

    len = data.length;
    mod = (len % cutlen);
    k = cutlen - mod;
    for (i=0; i<data.length; i++)
    {
        number = number + data.charAt(i);

        if (i < data.length - 1)
        {
            k++;
            if ((k % cutlen) == 0)
            {
                number = number + comma;
                k = 0;
            }
        }
    }

    if(sign != null)
        number = sign+number;

    return number;
}

// 새 창
function popup_window(url, winname, opt)
{
    window.open(url, winname, opt);
}


// 폼메일 창
function popup_formmail(url)
{
    opt = 'scrollbars=yes,width=417,height=385,top=10,left=20';
    popup_window(url, "wformmail", opt);
}

// , 를 없앤다.
function no_comma(data)
{
    var tmp = '';
    var comma = ',';
    var i;

    for (i=0; i<data.length; i++)
    {
        if (data.charAt(i) != comma)
            tmp += data.charAt(i);
    }
    return tmp;
}

// 삭제 검사 확인
function del(href)
{
    if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        var iev = -1;
        if (navigator.appName == 'Microsoft Internet Explorer') {
            var ua = navigator.userAgent;
            var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                iev = parseFloat(RegExp.$1);
        }

        // IE6 이하에서 한글깨짐 방지
        if (iev != -1 && iev < 7) {
            document.location.href = encodeURI(href);
        } else {
            document.location.href = href;
        }
    }
}

// 쿠키 입력
function set_cookie(name, value, expirehours, domain)
{
    var today = new Date();
    today.setTime(today.getTime() + (60*60*1000*expirehours));
    document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
    if (domain) {
        document.cookie += "domain=" + domain + ";";
    }
}

// 쿠키 얻음
function get_cookie(name)
{
    var find_sw = false;
    var start, end;
    var i = 0;

    for (i=0; i<= document.cookie.length; i++)
    {
        start = i;
        end = start + name.length;

        if(document.cookie.substring(start, end) == name)
        {
            find_sw = true
            break
        }
    }

    if (find_sw == true)
    {
        start = end + 1;
        end = document.cookie.indexOf(";", start);

        if(end < start)
            end = document.cookie.length;

        return unescape(document.cookie.substring(start, end));
    }
    return "";
}

// 쿠키 지움
function delete_cookie(name)
{
    var today = new Date();

    today.setTime(today.getTime() - 1);
    var value = get_cookie(name);
    if(value != "")
        document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
}

var last_id = null;
function menu(id)
{
    if (id != last_id)
    {
        if (last_id != null)
            document.getElementById(last_id).style.display = "none";
        document.getElementById(id).style.display = "block";
        last_id = id;
    }
    else
    {
        document.getElementById(id).style.display = "none";
        last_id = null;
    }
}

function textarea_decrease(id, row)
{
    if (document.getElementById(id).rows - row > 0)
        document.getElementById(id).rows -= row;
}

function textarea_original(id, row)
{
    document.getElementById(id).rows = row;
}

function textarea_increase(id, row)
{
    document.getElementById(id).rows += row;
}

// 글숫자 검사
function check_byte(content, target)
{
    var i = 0;
    var cnt = 0;
    var ch = '';
    var cont = document.getElementById(content).value;

    for (i=0; i<cont.length; i++) {
        ch = cont.charAt(i);
        if (escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }
    // 숫자를 출력
    document.getElementById(target).innerHTML = cnt;

    return cnt;
}

// 브라우저에서 오브젝트의 왼쪽 좌표
function get_left_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var left = obj.offsetLeft + document.body.clientLeft;
    var left = obj.offsetLeft;

    while((parentObj=clientObj.offsetParent) != null)
    {
        left = left + parentObj.offsetLeft;
        clientObj = parentObj;
    }

    return left;
}

// 브라우저에서 오브젝트의 상단 좌표
function get_top_pos(obj)
{
    var parentObj = null;
    var clientObj = obj;
    //var top = obj.offsetTop + document.body.clientTop;
    var top = obj.offsetTop;

    while((parentObj=clientObj.offsetParent) != null)
    {
        top = top + parentObj.offsetTop;
        clientObj = parentObj;
    }

    return top;
}

function flash_movie(src, ids, width, height, wmode)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    return "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' "+wh+" id="+ids+"><param name=wmode value="+wmode+"><param name=movie value="+src+"><param name=quality value=high><embed src="+src+" quality=high wmode="+wmode+" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' "+wh+"></embed></object>";
}

function obj_movie(src, ids, width, height, autostart)
{
    var wh = "";
    if (parseInt(width) && parseInt(height))
        wh = " width='"+width+"' height='"+height+"' ";
    if (!autostart) autostart = false;
    return "<embed src='"+src+"' "+wh+" autostart='"+autostart+"'></embed>";
}

function doc_write(cont)
{
    document.write(cont);
}

var win_password_lost = function(href) {
    window.open(href, "win_password_lost", "left=50, top=50, width=617, height=330, scrollbars=1");
}

$(document).ready(function(){
    /*$("#login_password_lost, #ol_password_lost").click(function(){
        win_password_lost(this.href);
        return false;
    });*/
});

/**
 * 포인트 창
 **/
var win_point = function(href) {
    var new_win = window.open(href, 'win_point', 'left=100,top=100,width=600, height=600, scrollbars=1');
    new_win.focus();
}

/**
 * 쪽지 창
 **/
var win_memo = function(href) {
    var new_win = window.open(href, 'win_memo', 'left=100,top=100,width=620,height=500,scrollbars=1');
    new_win.focus();
}

/**
 * 쪽지 창
 **/
var check_goto_new = function(href, event) {
    if( !(typeof g5_is_mobile != "undefined" && g5_is_mobile) ){
        if (window.opener && window.opener.document && window.opener.document.getElementById) {
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
            window.open(href);
            //window.opener.document.location.href = href;
        }
    }
}

/**
 * 메일 창
 **/
var win_email = function(href) {
    var new_win = window.open(href, 'win_email', 'left=100,top=100,width=600,height=580,scrollbars=1');
    new_win.focus();
}

/**
 * 자기소개 창
 **/
var win_profile = function(href) {
    var new_win = window.open(href, 'win_profile', 'left=100,top=100,width=620,height=510,scrollbars=1');
    new_win.focus();
}

/**
 * 스크랩 창
 **/
var win_scrap = function(href) {
    var new_win = window.open(href, 'win_scrap', 'left=100,top=100,width=600,height=600,scrollbars=1');
    new_win.focus();
}

/**
 * 홈페이지 창
 **/
var win_homepage = function(href) {
    var new_win = window.open(href, 'win_homepage', '');
    new_win.focus();
}

/**
 * 우편번호 창
 **/
var win_zip = function(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if(typeof daum === 'undefined'){
        alert("다음 우편번호 postcode.v2.js 파일이 로드되지 않았습니다.");
        return false;
    }

    var zip_case = 1;   //0이면 레이어, 1이면 페이지에 끼워 넣기, 2이면 새창

    var complete_fn = function(data){
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        var fullAddr = ''; // 최종 주소 변수
        var extraAddr = ''; // 조합형 주소 변수

        // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
        if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
            fullAddr = data.roadAddress;

        } else { // 사용자가 지번 주소를 선택했을 경우(J)
            fullAddr = data.jibunAddress;
        }

        // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
        if(data.userSelectedType === 'R'){
            //법정동명이 있을 경우 추가한다.
            if(data.bname !== ''){
                extraAddr += data.bname;
            }
            // 건물명이 있을 경우 추가한다.
            if(data.buildingName !== ''){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
            extraAddr = (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
        }

        // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        if(of[frm_jibeon] !== undefined){
            of[frm_jibeon].value = data.userSelectedType;
        }
        
        setTimeout(function(){
            of[frm_addr2].focus();
        } , 100);
    };

    switch(zip_case) {
        case 1 :    //iframe을 이용하여 페이지에 끼워 넣기
            var daum_pape_id = 'daum_juso_page'+frm_zip,
                element_wrap = document.getElementById(daum_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", daum_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="접기 버튼">';
                jQuery('form[name="'+frm_name+'"]').find('input[name="'+frm_addr1+'"]').before(element_wrap);
                jQuery("#"+daum_pape_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_wrap.style.display = 'none';
                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
                // iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    element_wrap.style.height = size.height + "px";
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
            break;
        case 2 :    //새창으로 띄우기
            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default :   //iframe을 이용하여 레이어 띄우기
            var rayer_id = 'daum_juso_rayer'+frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="닫기 버튼">';
                document.body.appendChild(element_layer);
                jQuery("#"+rayer_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_layer.style.display = 'none';
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            // iframe을 넣은 element를 보이게 한다.
            element_layer.style.display = 'block';
    }
}

var win_zip2 = function(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
    if(typeof daum === 'undefined'){
        alert("다음 우편번호 postcode.v2.js 파일이 로드되지 않았습니다.");
        return false;
    }

    var zip_case = 0;   //0이면 레이어, 1이면 페이지에 끼워 넣기, 2이면 새창

    var complete_fn = function(data){
        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

        // 각 주소의 노출 규칙에 따라 주소를 조합한다.
        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
        var fullAddr = ''; // 최종 주소 변수
        var extraAddr = ''; // 조합형 주소 변수

        // 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
        //if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
        console.log(data);
        if(data.roadAddress)
            fullAddr = data.roadAddress;
        else
            fullAddr = data.jibunAddress;
        //} else { // 사용자가 지번 주소를 선택했을 경우(J)
        //    fullAddr = data.jibunAddress;
        //}

        // 사용자가 선택한 주소가 도로명 타입일때 조합한다.
        if(data.userSelectedType === 'R'){
            //법정동명이 있을 경우 추가한다.
            if(data.bname !== ''){
                extraAddr += data.bname;
            }
            // 건물명이 있을 경우 추가한다.
            if(data.buildingName !== ''){
                extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
            }
            // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
            extraAddr = (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
        }

        // 우편번호와 주소 정보를 해당 필드에 넣고, 커서를 상세주소 필드로 이동한다.
        var of = document[frm_name];

        of[frm_zip].value = data.zonecode;

        of[frm_addr1].value = fullAddr;
        of[frm_addr3].value = extraAddr;

        //if(of[frm_jibeon] !== undefined){
            of[frm_jibeon].value = data.jibunAddress;
        //}

        setTimeout(function(){
            of[frm_addr2].focus();
        } , 100);
    };

    switch(zip_case) {
        case 1 :    //iframe을 이용하여 페이지에 끼워 넣기
            var daum_pape_id = 'daum_juso_page'+frm_zip,
                element_wrap = document.getElementById(daum_pape_id),
                currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            if (element_wrap == null) {
                element_wrap = document.createElement("div");
                element_wrap.setAttribute("id", daum_pape_id);
                element_wrap.style.cssText = 'display:none;border:1px solid;left:0;width:100%;height:300px;margin:5px 0;position:relative;-webkit-overflow-scrolling:touch;';
                element_wrap.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-21px;z-index:1" class="close_daum_juso" alt="접기 버튼">';
                jQuery('form[name="'+frm_name+'"]').find('input[name="'+frm_addr1+'"]').before(element_wrap);
                jQuery("#"+daum_pape_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_wrap.style.display = 'none';
                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분.
                // iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    element_wrap.style.height = size.height + "px";
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
            break;
        case 2 :    //새창으로 띄우기
            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                }
            }).open();
            break;
        default :   //iframe을 이용하여 레이어 띄우기
            var rayer_id = 'daum_juso_rayer'+frm_zip,
                element_layer = document.getElementById(rayer_id);
            if (element_layer == null) {
                element_layer = document.createElement("div");
                element_layer.setAttribute("id", rayer_id);
                element_layer.style.cssText = 'display:none;border:5px solid;position:fixed;width:300px;height:460px;left:50%;margin-left:-155px;top:50%;margin-top:-235px;overflow:hidden;-webkit-overflow-scrolling:touch;z-index:10000';
                element_layer.innerHTML = '<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnCloseLayer" style="cursor:pointer;position:absolute;right:-3px;top:-3px;z-index:1" class="close_daum_juso" alt="닫기 버튼">';
                document.body.appendChild(element_layer);
                jQuery("#"+rayer_id).off("click", ".close_daum_juso").on("click", ".close_daum_juso", function(e){
                    e.preventDefault();
                    jQuery(this).parent().hide();
                });
            }

            new daum.Postcode({
                oncomplete: function(data) {
                    complete_fn(data);
                    // iframe을 넣은 element를 안보이게 한다.
                    element_layer.style.display = 'none';
                },
                maxSuggestItems : g5_is_mobile ? 6 : 10,
                width : '100%',
                height : '100%'
            }).embed(element_layer);

            // iframe을 넣은 element를 보이게 한다.
            element_layer.style.display = 'block';
    }
}

/**
 * 새로운 비밀번호 분실 창 : 101123
 **/
win_password_lost = function(href)
{
    var new_win = window.open(href, 'win_password_lost', 'width=617, height=330, scrollbars=1');
    new_win.focus();
}

/**
 * 설문조사 결과
 **/
var win_poll = function(href) {
    var new_win = window.open(href, 'win_poll', 'width=616, height=500, scrollbars=1');
    new_win.focus();
}

/**
 * 스크린리더 미사용자를 위한 스크립트 - 지운아빠 2013-04-22
 * alt 값만 갖는 그래픽 링크에 마우스오버 시 title 값 부여, 마우스아웃 시 title 값 제거
 **/
$(function() {
    $('a img').mouseover(function() {
        $a_img_title = $(this).attr('alt');
        $(this).attr('title', $a_img_title);
    }).mouseout(function() {
        $(this).attr('title', '');
    });
});

/**
 * 텍스트 리사이즈
**/
function font_resize(id, rmv_class, add_class, othis)
{
    var $el = $("#"+id);

    $el.removeClass(rmv_class).addClass(add_class);

    set_cookie("ck_font_resize_rmv_class", rmv_class, 1, g5_cookie_domain);
    set_cookie("ck_font_resize_add_class", add_class, 1, g5_cookie_domain);

    if(typeof othis !== "undefined"){
        $(othis).addClass('select').siblings().removeClass('select');
    }
}

/**
 * 댓글 수정 토큰
**/
function set_comment_token(f)
{
    if(typeof f.token === "undefined")
        $(f).prepend('<input type="hidden" name="token" value="">');

    $.ajax({
        url: g5_bbs_url+"/ajax.comment_token.php",
        type: "GET",
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            f.token.value = data.token;
        }
    });
}

$(function(){
    $(".win_point").click(function() {
        win_point(this.href);
        return false;
    });

    $(".win_memo").click(function() {
        win_memo(this.href);
        return false;
    });

    $(".win_email").click(function() {
        win_email(this.href);
        return false;
    });

    $(".win_scrap").click(function() {
        win_scrap(this.href);
        return false;
    });

    $(".win_profile").click(function() {
        win_profile(this.href);
        return false;
    });

    $(".win_homepage").click(function() {
        win_homepage(this.href);
        return false;
    });

    $(".win_password_lost").click(function() {
        win_password_lost(this.href);
        return false;
    });

    /*
    $(".win_poll").click(function() {
        win_poll(this.href);
        return false;
    });
    */

    // 사이드뷰
    var sv_hide = false;
    $(".sv_member, .sv_guest").click(function() {
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv, .sv_wrap").hover(
        function() {
            sv_hide = false;
        },
        function() {
            sv_hide = true;
        }
    );

    $(".sv_member, .sv_guest").focusin(function() {
        sv_hide = false;
        $(".sv").removeClass("sv_on");
        $(this).closest(".sv_wrap").find(".sv").addClass("sv_on");
    });

    $(".sv a").focusin(function() {
        sv_hide = false;
    });

    $(".sv a").focusout(function() {
        sv_hide = true;
    });

    // 셀렉트 ul
    var sel_hide = false;
    $('.sel_btn').click(function() {
        $('.sel_ul').removeClass('sel_on');
        $(this).siblings('.sel_ul').addClass('sel_on');
    });

    $(".sel_wrap").hover(
        function() {
            sel_hide = false;
        },
        function() {
            sel_hide = true;
        }
    );

    $('.sel_a').focusin(function() {
        sel_hide = false;
    });

    $('.sel_a').focusout(function() {
        sel_hide = true;
    });

    $(document).click(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).focusin(function() {
        if(sv_hide) { // 사이드뷰 해제
            $(".sv").removeClass("sv_on");
        }
        if (sel_hide) { // 셀렉트 ul 해제
            $('.sel_ul').removeClass('sel_on');
        }
    });

    $(document).on( "keyup change", "textarea#wr_content[maxlength]", function(){
        var str = $(this).val();
        var mx = parseInt($(this).attr("maxlength"));
        if (str.length > mx) {
            $(this).val(str.substr(0, mx));
            return false;
        }
    });
});

function get_write_token(bo_table)
{
    var token = "";

    $.ajax({
        type: "POST",
        url: g5_bbs_url+"/write_token.php",
        data: { bo_table: bo_table },
        cache: false,
        async: false,
        dataType: "json",
        success: function(data) {
            if(data.error) {
                alert(data.error);
                if(data.url)
                    document.location.href = data.url;

                return false;
            }

            token = data.token;
        }
    });

    return token;
}

function fnShowModal(data){
    $(".modalpopup").html(data);
    $(".modalpopup").addClass("active");
    fnModalTop();
    $("html").attr("style","height:100vh;overflow:hidden;");
}

function fnCloseModal(){
    $(".modalpopup").html('');
    $(".modalpopup").removeClass("active");
    var type = $("body").hasClass("sub");
    if(type) {
        $("html").attr("style", "height:auto;overflow:auto");
    }
}

function fnCloseModal2(){
    $(".modalpopup").html('');
    $(".modalpopup").removeClass("active");
}

function fnModalTop(){
    var height = $(".modalpopup .modal_in").height() + 40; //40은 style 속성 고정값
    var top = height / 2;
    //$(".modalpopup .modal_in").attr("style","top:50%;margin-top: -"+top+"px");
}

function fnMyprofile(mb_id){
    if(mb_id==''){
        alert("로그인이 필요합니다.");
        return false;
    }
    $(".my_profile").addClass("active");
}

function fnCloseProfile(){
    $(".my_profile").removeClass("active");
    $(".mymenu_detail").removeClass("active");
}

function fnQuickView() {
    if($(".quick").hasClass("active")){
        $(".quick").removeClass("active");
        //$("html").attr("style","height:auto;overflow:auto");
        $(".quick_btns img").attr("src",g5_url+"/img/quick_btns.png");
    }else{
        $(".quick").addClass("active");
        //$("html").attr("style","height:100vh;overflow:hidden;");
        $(".quick_btns img").attr("src",g5_url+"/img/quick_btns_on.png");
    }
}

function number_only(t){
    t.value = t.value.replace(/[^0-9]/g, '');
}

//천후표
function fnWeather(mb_id,cmap_id) {
    if(mb_id==""){
        if(confirm("로그인이 필요합니다.")){
            location.href=g5_bbs_url+'/login';
        }
        return false;
    }
    //현장정보 불러오기

}

//현장관리

$(function() {
    fnMenusHeader('');


    $(".etc_view_bg").click(function(){
        fnEtcClose();
    });

    window.onkeydown = function(){
        if(event.keyCode==27 && $(".etc_view").hasClass("active")){
            fnEtcClose();
        }
    }

    $(document).on("click", "form[name=fwrite] input:submit, form[name=fwrite] button:submit, form[name=fwrite] input:image", function() {
        var f = this.form;

        if (typeof(f.bo_table) == "undefined") {
            return;
        }

        var bo_table = f.bo_table.value;
        var token = get_write_token(bo_table);

        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }

        var $f = $(f);

        if(typeof f.token === "undefined")
            $f.prepend('<input type="hidden" name="token" value="">');

        $f.find("input[name=token]").val(token);

        return true;
    });

    $(document).on("click",".modalpopup.active span.bg", function(){
       //$(".modal").removeClass("active");
        fnCloseModal();
    });

    $(document).on("click",".modalpopup .downmenu h3", function(){
        if($(this).parent().hasClass("active")){
            $(this).parent().removeClass("active");
        }else {
            $(this).parent().addClass("active");
            $(".modalpopup .downmenu").not($(this).parent()).removeClass("active");
        }
        fnModalTop();
    });

    $(document).on("click",".quickmenus ",function(){
        var name = $(this).find("label").text();
        switch(name){
            case "CMAP GUIDE":
                
                break;
            case "스케쥴":
                location.href=g5_url+'/page/mypage/schedule';
                break;
            case "현장관리":
                location.href=g5_url+'/page/mylocation/mylocation';
                break;
            case "업무연락서 작성":
                location.href=g5_url+'/page/mypage/my_message_list.php';
                break;
            case "MY CMAP":
                location.href=g5_url+'/page/mypage/mypage';
                break;
            case "게시판":
                location.href=g5_bbs_url+'/board?bo_table=databoard';
                break;
            case "제안하기":
                location.href=g5_url+"/page/board/inquiry"
                break;
            case "결제하기":
                fnQuickView();
                fnPayment();
                break;
            case "천후표":
                location.href=g5_url+"/page/mypage/weather";
                break;
            case "PM_MODE":
                location.href=g5_url+'/page/manager/';
                break;
        }
    });
    $("#hd").mouseover(function(){
        //$("#main").addClass("blur");
        //$("#ft").addClass("blur");
        $(".container").addClass("blur");
        $("#main").addClass("blur");
        $(".search").addClass("blur");
        $(".width-fixed, .full-width").addClass("blur");
    }).mouseout(function(){
        //$("#main").removeClass("blur");
        //$("#ft").removeClass("blur");
        $(".container").removeClass("blur");
        $("#main").removeClass("blur");
        $(".search").removeClass("blur");
        $(".width-fixed, .full-width").removeClass("blur");
    });
});

//현장등록 팝업
function fnConstConfirm(){
    $.ajax({
        url:g5_url+"/page/modal/ajax.confirm.php",
        method:"post",
        data:{}
    }).done(function(data){
        fnShowModal(data);
    });
}

//현장등록 팝업
function fnConstRe(constid,link){
    $.ajax({
        url:g5_url+"/page/modal/ajax.alert.php",
        method:"post",
        data:{title:"현장등록",msg:"등록하던 현장이 있습니다. <br>계속 등록하시겠습니까?",link:link,btns:"개설하기",cancel:"fnConstReCancel("+constid+")"}
    }).done(function(data){
        fnShowModal(data);
    });
}

function fnConstReCancel(constid) {
    $.ajax({
        url:g5_url+"/page/ajax/ajax.mylocation_cancel.php",
        method:"post",
        data:{constid:constid}
    }).done(function(data){
        console.log(data);
        fnCloseModal();
    });
}

function fnSearch(){
    if(!$(".search_area").hasClass("active")){
        $(".search_area").addClass("active");
    }else{
        $(".search_area").removeClass("active");
    }
}

function viewKorean(num) {
    var hanA = new Array("","일","이","삼","사","오","육","칠","팔","구","십");
    var danA = new Array("","십","백","천","","십","백","천","","십","백","천","","십","백","천");
    var result = "";
    for(i=0; i<num.length; i++) {
        str = "";
        han = hanA[num.charAt(num.length-(i+1))];
        if(han != "") str += han+danA[i];
        if(i == 4) str += "만";
        if(i == 8) str += "억";
        if(i == 12) str += "조";
        result = str + result;
    }
    var exp = /억/;
    if(exp.test(result)){
        result = result.replace("만","");
    }
    //if(num != 0) result = result + "원";
    return result ;
}

var const_id = '';
var chk_menu_on = false;
//현장 변경시
function fnChangeConst(mb_id,id){
    const_id = id;
    //저장된 마지막 현장 저장
    $.ajax({
        url:g5_url+"/page/ajax/ajax.current_construct_update.php",
        method:"post",
        data:{const_id:id}
    }).done(function(){
        location.href=g5_url+'/?menu=on';
    });
/*
    //요청 및 초대된 현장 카운트
    $.ajax({
        url:g5_url+'/page/ajax/ajax.get_invite_count.php',
        method:'post',
        data:{const_id:const_id},
        dataType:'json'
        ,beforeSend:function(){
            $(".cmenu1 .counts span").html('...');
        }
    }).done(function(data){
        $(".cmenu1 .counts span").html(Number(data.cnt));
    });

    //작업 요청서 카운트
    $.ajax({
        url:g5_url+'/page/ajax/ajax.get_invite_count.php',
        method:'post',
        data:{const_id:id}
    }).done(function(data){
        console.log(data);
    });

    //제출 지연 현황 카운트
    if(const_id) {
        $.ajax({
            url: g5_url + '/page/ajax/ajax.get_delay_count.php',
            method: 'post',
            data: {const_id: const_id},
            dataType: 'json'
            , beforeSend: function () {
                $(".cmenu4 .counts span").html('...');
            }
        }).done(function (data) {
            $(".cmenu4 .counts span").html(Number(data.cnt));
        });
    }
    //현제 현장의 평가 점수
    if(const_id) {
        $.ajax({
            url: g5_url + '/page/ajax/ajax.get_construct_eval.php',
            method: "post",
            data: {constid: const_id},
            dataType: "json"
        }).done(function (data) {
            console.log(data);
            if (data.ajax_eval1_class) {
                $(".eval1").addClass(data.ajax_eval1_class);
                $(".eval1 span").addClass(data.ajax_eval1_class);
            } else {
                $(".eval1").removeClass('level2');
                $(".eval1").removeClass('level3');
                $(".eval1 span").removeClass('level2');
                $(".eval1 span").removeClass('level3');
            }
            if (data.ajax_eval2_class) {
                $(".eval2").addClass(data.ajax_eval2_class);
                $(".eval2 span").addClass(data.ajax_eval2_class);
            } else {
                $(".eval2").removeClass('level2');
                $(".eval2").removeClass('level3');
                $(".eval2 span").removeClass('level2');
                $(".eval2 span").removeClass('level3');
            }

            //if(data.eval1_total){
            $(".eval1_p").text(data.ajax_eval1_total);
            //}
            //if(data.eval2_total){
            $(".eval2_p").text(data.ajax_eval2_total);
            //}
            if (data.ajax_eval1_left) {
                $(".eval1 span").css({"left": "calc(" + data.ajax_eval1_left + "% - 40px)"});
            }
            if (data.ajax_eval1_left) {
                $(".eval2 span").css({"left": "calc(" + data.ajax_eval2_left + "% - 40px)"});
            }
        });
    }

    if($(".mymenu_detail").hasClass("active") && chk_menu_on == true) {
        fnViewRequest(mb_id, const_id);
    }
    
    
    //날씨정보
    $.ajax({
        url:g5_url+"/page/ajax/ajax.get_weather_location.php",
        method:"post",
        data:{cmap_id:id},
        dataType:"json"
    }).done(function(data){
        if(data.status != 1) {
            if (data.tmn[0] && data.tmx[0]) {
                var min = Math.floor(data.tmn[0]);
                var max = Math.floor(data.tmx[0]);
                if (data.temp[0]) {
                    var current = data.temp[0];
                    $(".now_temp").html(current + "℃");
                }
                $(".temp_min_max").html(min + "℃ / " + max + "℃");
                $(".addr").html(data.addr);
                $(".timedesc").html(data.time);
            }
        }else{
            getLocation();
        }
    });
    */


}

function fnChangeConst2(mb_id,id) {
    const_id = id;
    //저장된 마지막 현장 저장
    $.ajax({
        url: g5_url + "/page/ajax/ajax.current_construct_update.php",
        method: "post",
        data: {const_id: id}
    }).done(function(){
        location.reload();
    });
}

function fnChangeConstSearch(id) {
    const_id = id;
    location.href=g5_url+'page/mypage/my_message_list?const_id='+const_id;
}

function fnSearchPapular(text){
    $("#search_text").val(text);
    document.searchFrom.submit();
}

function fnScheduleView() {
    var id = $("#mylocmap").val();
    if(id){
        location.href=g5_url+"/page/mypage/schedule?id="+id
    }else{
        location.href=g5_url+"/page/mypage/schedule"
    }
}

function fnMenusHeader(me_code) {
    $.ajax({
        url:g5_url+"/page/ajax/ajax.get_menu_header.php",
        method:"post",
        data:{menu_id:me_code}
    }).done(function(data){
        if(me_code=="") {
            $("#allmenu_header10").addClass("active");
            $("li[id^=allmenu_header]").not($("#allmenu_header10")).removeClass("active");
        }else {
            $("#allmenu_header" + me_code).addClass("active");
            $("li[id^=allmenu_header]").not($("#allmenu_header" + me_code)).removeClass("active");
        }
        $(".depth_menu_heads").html('');
        $(".depth_menu_heads").append(data);
    });
}

//현장초대
function fnConstInvite(id){
    $.ajax({
        url:g5_url+"/page/modal/ajax.member_invite.php",
        method:"post",
        data:{constid:id}
    }).done(function(data){
        fnShowModal(data);
    });
}

function fnConstShare(id){
    $.ajax({
        url:g5_url+"/page/modal/ajax.construct_share.php",
        method:"post",
        data:{id:id}
    }).done(function(data){
        fnShowModal(data);
    });
}

function fnConstEdit(type,id){
    if(type==1){
        location.href=g5_url+'/page/mylocation/mylocation_edit?type=edit&constid='+id;
    }else{
        location.href=g5_url+'/page/mylocation/mylocation_edit2?type=edit&constid='+id;
    }
}

//설정 복사
function fnConstCopy(const_id){
    var chk = $("input[id^=copy_]:checked").val();
    var chkcnt = $("input[id^=copy_]:checked").length;
    if(chkcnt==0){
        alert("복사할 대상을 선택해주세요.");
        return false;
    }

    if(confirm("해당 사용자의 지연현황 및 평가데이터를 복사하시겠습니까?\r\n현재 데이터는 자동 저장됩니다.")){
        location.href=g5_url+'/page/mylocation/mylocation_copy?mb_id='+chk+'&const_id='+const_id;
    }
}

//내 설정 복구
function fnConstRestore(mb_id,const_id){
    $.ajax({
        url:g5_url+"/page/modal/ajax.mylocation_set_restore.php",
        method:"post",
        data:{mb_id:mb_id,constid:const_id}
    }).done(function(data){
        fnShowModal(data);
    });
}

//내 설정 저장
function fnConstSave(link){
    $.ajax({
        url:g5_url+"/page/modal/ajax.alert.php",
        method:"post",
        data:{title:"저장하기",msg:"현재 현장 개인설정이 저장됩니다.<br>저장 된 항목은 복구 가능합니다.",link:link,btns:"저장하기"}
    }).done(function(data){
        fnShowModal(data);
    });
}

//현장 탈퇴는 참여자일경우만 가능
function fnConstLeave(mb_id,const_id){
    if(confirm("해당 현장을 탈퇴하시겠습니까?")) {
        location.href = g5_url + '/page/mylocation/mylocation_leave?const_id=' + const_id+'&mb_id='+mb_id;
    }
}

//현장 조인
function fnConstJoin(mb_id,id){
    $.ajax({
        url:g5_url+"/page/ajax/ajax.construct_invite.php",
        method:"post",
        data:{mb_id:mb_id,id:id},
        dataType:'json'
    }).done(function(data){
        if(data.status == 1){
            fnCloseModal();
        }
        alert(data.msg);
    });
}

//현장 조인
function fnConstJoinPm(mb_id,id){
    $.ajax({
        url:g5_url+"/page/ajax/ajax.construct_invite_pm.php",
        method:"post",
        data:{mb_id:mb_id,pm_constid:id},
        dataType:'json'
    }).done(function(data){
        console.log(data);
        if(data.status == 1){
            fnCloseModal();
        }
        alert(data.msg);
    });
}

//사용자관리 오픈
function fnViewRequest(mb_id,const_id){
    if(const_id=="") {
        const_id = $("#mylocmap").val();
    }

    if($(".mymenu_detail").hasClass("active") && chk_menu_on == true) {
        $(".mymenu_detail").removeClass("active");
        chk_menu_on = false;
    }else {
        $.ajax({
            url: g5_url + '/page/ajax/ajax.get_request.php',
            method: "post",
            data: {mb_id: mb_id, const_id: const_id}
        }).done(function (data) {
            if (data == 1) {
                alert("회원 정보가 없습니다.");
            } else {
                $(".infos").show();
                $(".mymenu_detail .title h2").html("사용자 승인")
                $(".mymenu_detail .detail_list").html(data);
                $(".mymenu_detail").addClass("active");
                chk_menu_on = true;
            }
        });
    }
}

//현장초대 요청 승인
function fnConstJoinUp(invite_id,const_id){
    $.ajax({
        url:g5_url+"/page/ajax/ajax.construct_invite_update.php",
        method:"post",
        data:{invite_id:invite_id,const_id:const_id}
    }).done(function(data){
        console.log(data);
        if(data==1){
            alert("사용자 초대정보 오류입니다.");
            return false;
        }else if(data==2) {
            alert("현장 정보 오류입니다.");
            return false;
        }else if(data==3){
            alert("해당 현장이 없거나 삭제 상태입니다.");
            return false;
        }else if(data==4){
            alert("초대 상태를 업데이트 할 수 없습니다.");
            return false;
        }else if(data==5) {
            alert("현장에 참여 하지 못했습니다.\n다시 시도해 주세요.");
            return false;
        }else if(data==6){
            alert("이미 참여한 현장입니다.\n요청 또는 초대는 목록에서 삭제됩니다.");
            $("#invite_"+invite_id).remove();
            var cnt = $("tr[id^='invite_']").length;
            if(cnt==0){
                $(".detail_list table tbody").append("<tr><td colspan='3' class='td_center'>승인요청 및 요청이력이 없습니다.</td></tr>");
            }
            $(".cmap_menu .cmenu1 .counts span").html(cnt);
        }else if(data==0){
            alert("현장에 참여 되었습니다.");
            location.reload();
            $("#invite_"+invite_id).remove();
            var cnt = $("tr[id^='invite_']").length;
            if(cnt==0){
                $(".detail_list table tbody").append("<tr><td colspan='3' class='td_center'>승인요청 및 요청이력이 없습니다.</td></tr>");
            }
            $(".cmap_menu .cmenu1 .counts span").html(cnt);
        }
    });
}

//현장초대 취소/거절
function fnConstCancel(invite_id){
    console.log(invite_id);
    $.ajax({
        url:g5_url+'/page/ajax/ajax.construct_invite_cancel.php',
        method:"post",
        data:{invite_id:invite_id}
    }).done(function(data){
        if(data==1){
            alert("잘못된 정보 입니다.");
            return false;
        }else if(data == 2){
            $("#invite_"+invite_id).remove();
            var cnt = $("tr[id^='invite_']").length;

            if(cnt==0){
                $(".detail_list table tbody").append("<tr><td colspan='3' class='td_center'>승인요청 및 요청이력이 없습니다.</td></tr>");
            }
            $(".cmap_menu .cmenu1 .counts span").html(cnt);
        }
    });
}

function footerModal(url,co_id){
    $.ajax({
        url:url,
        method:"post",
        data:{co_id:co_id}
    }).done(function(data){
        console.log(data);
        $(".modalpopup").append(data);
        $(".modalpopup").addClass("active");
    });
}

//사용자관리 오픈
function fnViewDelay(mb_id,const_id){
    if(const_id=="") {
        const_id = $("#mylocmap").val();
    }

    if($(".mymenu_detail").hasClass("active") && chk_menu_on == true) {
        $(".mymenu_detail").removeClass("active");
    }else {
        $.ajax({
            url: g5_url + '/page/ajax/ajax.get_delay.php',
            method: "post",
            data: {mb_id: mb_id, const_id: const_id}
        }).done(function (data) {
            console.log(data);
            if (data == 1) {
                alert("회원 정보가 없습니다.");
            } else {
                $(".infos").hide();
                $(".mymenu_detail .title h2").html("제출 지연 현황")
                $(".mymenu_detail .detail_list").html(data);
                $(".mymenu_detail").addClass("active");
                chk_menu_on = true;
            }
        });
    }
}

function fnViewMessage(mb_id,const_id){
    if(const_id=="") {
        const_id = $("#mylocmap").val();
    }

    if($(".mymenu_detail").hasClass("active") && chk_menu_on == true) {
        $(".mymenu_detail").removeClass("active");
    }else {
        $.ajax({
            url: g5_url + '/page/ajax/ajax.get_workmessage.php',
            method: "post",
            data: {mb_id: mb_id, const_id: const_id}
        }).done(function (data) {
            if (data == 1) {
                alert("회원 정보가 없습니다.");
            } else {
                $(".infos").hide();
                $(".mymenu_detail .title h2").html("업무연락서")
                $(".mymenu_detail .detail_list").html(data);
                $(".mymenu_detail").addClass("active");
                chk_menu_on = true;
            }
        });
    }
}

function fnWriteMessage(msg_id){
    var const_id = "";
    if(msg_id=="") {
         const_id = $("#cons_id").val();
        if (const_id == "") {
            alert("현장을 선택해 주세요.");
            $("#cons_id").focus();
            return false;
        }
    }
    $.ajax({
        url:g5_url+"/page/ajax/ajax.get_message.php",
        method:"post",
        data:{const_id:const_id,msg_id:msg_id}
    }).done(function(data) {
        if(data==1){
            alert("현장정보가 없습니다.");
        }else if(data==2){
            alert("등록된 현장에 요청서를 보낼 수신자가 없습니다.")
        }else{
            $(".etc_view").html(data)
            $(".etc_view_bg").addClass("active");
            $(".etc_view").addClass("active");
            $("html,body").attr("style", "height:100vh;overflow:hidden;");
        }
    });
}

function fnWriteMessage2(msg_id){
    var const_id = "";
    if(msg_id=="") {
        const_id = $("#cons_id").val();
        if (const_id == "") {
            alert("현장을 선택해 주세요.");
            $("#cons_id").focus();
            return false;
        }
    }
    $.ajax({
        url:g5_url+"/page/mypage/message_preview.php",
        method:"post",
        data:{const_id:const_id,msg_id:msg_id}
    }).done(function(data) {
        $(".etc_view").html(data)
        $(".etc_view_bg").addClass("active");
        $(".etc_view").addClass("active");
        $("html,body").attr("style", "height:100vh;overflow:hidden;");
    });
}

function fnPmPreview(type,constid){
    if(constid==""){
        alert("현장이 선택되지 않았습니다.");
        return false;
    }
    var link = g5_url+"/page/manager/";
    switch (type){
        case 1:
            link += "delay_save_preview.php";
            break;
        case 2:
            link += "eval_save_preview.php";
            break;
        case 3:
            link += "eval2_save_preview.php";
            break;
    }
    console.log(link);
    $.ajax({
        url:link,
        method:"post",
        data:{constids:constid}
    }).done(function(data){
        console.log(data);
        $(".etc_view").html(data)
        $(".etc_view_bg").addClass("active");
        $(".etc_view").addClass("active");
        $("html,body").attr("style", "height:100vh;overflow:hidden;");
    });
}

function fnEtcClose(){
    $(".etc_view").removeClass("active");
    $(".etc_view_bg").removeClass("active");
    $("html,body").removeAttr("style");
}


function fn_join(id,mb_id){
    $.ajax({
        url:g5_url+"/page/ajax/ajax.construct_invite2.php",
        method:"post",
        data:{mb_id:mb_id,id:id},
        dataType:"json"
    }).done(function(data){
        alert(data.msg);
    });
}


function selPayType(amount,payment_type,mb_level){
    if(mb_level > 2){
        if(mb_level==3 && payment_type<=3){//일반 연장
            if(!confirm('현재 사용중인 맴버쉽을 연장 하시겠습니까?')){
                return false;
            }
        }
        if(mb_level==3 && payment_type > 3){//일반에서 PM
            alert('현재 등록된 맴버쉽 환불 후 PM_MODE로 전환 가능합니다.\n맴버쉽 환불은 정보수정 또는 제안하기를 통해 남겨주세요.');
            return false;
        }
        if(mb_level==5 && payment_type <= 3){//PM에서 일반
            alert('현재 등록된 맴버쉽 환불 후 일반사용자로 전환 가능합니다.\n맴버쉽 환불은 정보수정 또는 제안하기를 통해 남겨주세요.');
            return false;
        }
        if(mb_level==5 && payment_type > 3){//PM_MODE 연장
            if(!confirm('현재 사용중인 PM_MODE를 연장하시겠습니까?')){
                return false;
            }
        }
    }
    $.ajax({
        url:g5_url+'/page/modal/ajax.sel_order_type.php',
        method:"post",
        data:{amount:amount,payment_type:payment_type}
    }).done(function(data){
        fnShowModal(data);
        //memberPayment(amount,payment_type,data);
    });
}

function memberPayment(amount,payment_type,order_type,mb_name,mb_hp,mb_email,mb_id) {
    if(order_type==""){
        alert("결제 방식을 선택해 주세요.");
        return false;
    }
    var goodsname = "";
    switch (payment_type){
        case "1":
            goodsname = "맴버쉽 1개월";
            break;
        case "2":
            goodsname = "맴버쉽 6개월";
            break;
        case "3":
            goodsname = "맴버쉽 12개월";
            break;
        case "4":
            goodsname = "PM MODE 1개월";
            break;
        case "5":
            goodsname = "PM MODE 6개월";
            break;
        case "6":
            goodsname = "PM MODE 12개월";
            break;

    }
    var merchant_uid = 'mb_od_'+new Date().getTime()+'_'+mb_id;

    $("#amount").val(amount);
    $("#merchant_uid").val(merchant_uid);

    IMP.request_pay({
        pg:'html5_inicis',
        amount: amount,
        merchant_uid: merchant_uid,
        buyer_name: mb_name,
        buyer_tel: mb_hp,
        buyer_email: mb_email,
        name: goodsname,
        digital:true,
        pay_method:order_type,
        m_redirect_url: g5_url
    }, function (response) {
        //결제 후 호출되는 callback함수
        if (response.success) { //결제 성공
            location.href=g5_url+'/page/mypage/member_payment?amount='+amount+"&merchant_uid="+response.merchant_uid+'&payment_type='+payment_type+'&order_type='+order_type;
        } else {
            alert('결제실패 : ' + response.error_msg);
            //결제 실패시 임시저장 삭제(임시저장 일경우)
            fnPayment();
        }
    })
}

function fnLogout(){
    if(confirm("로그아웃을 하시겠습니까?")) {
        location.href = g5_bbs_url + '/logout';
    }
}

function fnMsgSave(msg_id) {
    if(confirm("해당 업무연락서를 저장 하시겟습니까?")){
        window.open(g5_url+'/page/mypage/my_msg_save?msg_id='+msg_id,"saveMsg",'width=800,height=942,resizable=no,menubar=no,toolbar=no,top=0,left=0, scrollbars=yes');
    }
}

function fnSavePm(type){
    /*var constids = '';

    $("input[id^=const_]").each(function(){
        if($(this).prop("checked")==true){
            if(constids==''){
                constids = $(this).val();
            }else{
                constids += ","+$(this).val();
            }
        }
    });

    if(constids==""){
        alert("저장할 현장을 선택해 주세요.");
        return false;
    }*/

    var link = g5_url+'/page/manager/';
    switch (type){
        case "1":
            link += 'pm_delay_save';
            break;
        case "2":
            link += 'pm_eval_save';
            break;
        case "3":
            link += 'pm_eval2_save';
            break;
    }

    //link += "?constids="+constids

    if(confirm("해당 화면을 엑셀 파일로 저장 하시겠습니까?")){
        window.open(link, 'save');
    }
}