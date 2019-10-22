<?php
session_start();


$test=print_r($_SESSION);
echo "<script>alert($test);</script>";

if(!isset($_SESSION['user_id'])) {
    echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_d_name = $_SESSION['user_d_name'];
$user_p_name = $_SESSION['user_p_name'];

include_once 'db_connect.php';

$query="select * from product";
$result = mysqli_query($db_connect, $query);
$product_list = mysqli_fetch_all($result);
mysqli_free_result($result);

$query="select id, concat(name, ' / ', d_name, ' / ', p_name) as name from user_view";
$result = mysqli_query($db_connect, $query);
$user_list = mysqli_fetch_all($result);
mysqli_free_result($result);

$query="select * from route";
$result = mysqli_query($db_connect, $query);
$route_list = mysqli_fetch_all($result);
mysqli_free_result($result);

$query="select * from rand";
$result = mysqli_query($db_connect, $query);
$rand_list = mysqli_fetch_all($result);
mysqli_free_result($result);

$query="select * from main_notice_view";
$result = mysqli_query($db_connect, $query);
$notice_list = mysqli_fetch_all($result);
mysqli_free_result($result);

$query="select * from db_view";
$result = mysqli_query($db_connect, $query);
$db_list = mysqli_fetch_all($result);
mysqli_free_result($result);

mysqli_close($db_connect);

if (isset($_POST['main_category'])) {
    $main_category = $_POST['main_category'];
} else {
    $main_category = "book";
}

if (isset($_POST['search_category'])) {
    $search_category = $_POST['search_category'];
} else {
    $search_category = "time";
}
?>

<!doctype html>
<html lang="kr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>허니문 리조트</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="jquery-ui-1.12.1/jquery-ui.min.css">
    <script src="jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="jquery-ui-1.12.1/datepicker-ko.js"></script>
</head>

<body>
<div class="left_menu">

    <div class="logo" onclick="window.location.reload()" title="Coconut Tour">
    </div>

    <div class="search">
        <select name="search_u_name" class="select_user" title="담당자 이름을 선택 하세요">
            <option value="" disabled selected hidden>담당자명</option>
            <?php
            foreach($user_list as $user){
                echo "<option value={$user[0]}>{$user[1]}</option>";
            }
            ?>
        </select>
        <div style="height: 8px"></div>
        <div>
            <input type="text" class="input_text" name="search_c_name" placeholder="고객 이름을 입력 하세요"
                   title="고객 이름을 입력 하세요">
            <button class="input_button" title="담당자 이름과 고객 이름으로 검색합니다"><img src="img/search.png" alt=""></button>
        </div>
    </div>

    <div class="menu">
        <div style="height: 16px"></div>
        <button class="top" onclick="window.open('admin.php', '_blank', 'width=814, height=555')"
                title="관리자 화면을 엽니다"><span><img src="img/admin.png"></span>관리자 관리</button>
        <div style="height: 1px"></div>
        <button class="mid" onclick="window.open('reservation.php', '_blank', 'width=814, height=916')"
                title="고객 등록 화면을 엽니다"><span><img src="img/c_add.png"></span>고객 등록</button>
        <div style="height: 1px"></div>
        <button class="mid" onclick="window.open('sms.php', '_blank', 'width=815, height=458')"
                title="SMS 보내기 화면을 엽니다"><span><img src="img/sms.png"></span>SMS 보내기</button>
        <div style="height: 1px"></div>
        <button class="mid" onclick="window.open('notice.php', '_blank', 'width=1617, height=916')"
                title="공지사항 화면을 엽니다"><span><img src="img/notice.png"></span>공지사항</button>
        <div style="height: 1px"></div>
        <button class="mid" onclick="window.open('event.php', '_blank', 'width=1617, height=916')"
                title="현지행사 화면을 엽니다"><span><img src="img/event.png"></span>현지행사</button>
        <div style="height: 1px"></div>
        <button class="btm" onclick="window.open('memo.php', '_blank', 'width=1617, height=916')"
                title="메모함 화면을 엽니다"><span><img src="img/memo.png"></span>메모함</button>
    </div>
</div>
<div class="top_menu">
        <span onclick="window.open('user_edit.php', '_blank', 'width=814, height=399')">
            <?
            echo "안녕 하세요! {$user_name} / {$user_d_name} / {$user_p_name} 님";
            ?>
        </span>
    <span onclick="window.open('user_edit.php', '_blank', 'width=814, height=399')">
            정보수정
        </span>
    <span onclick="location.href='logout.php'">
            <img src="img/logout.png">로그아웃
        </span>
</div>
<div class="main">
    <div class="search_view">
        <button id="btn_time" onclick="search_category('time')">시간조회</button>
        <button id="btn_direct" onclick="search_category('direct')">직접조회</button>

        <div id="t_view" class="t_view">
            <input type="text" name="w_start_date" id="w_start_date" class="date" placeholder="출발일">
            <span style="width: 16px"></span>
            <input type="text" name="r_start_date" id="r_start_date" class="date" placeholder="접수시작일">

            <div style="width: 100%; height: 16px"></div>

            <input type="text" name="w_end_date" id="w_end_date" class="date" placeholder="도착일">
            <span style="width: 16px"></span>
            <input type="text" name="r_end_date" id="r_end_date" class="date" placeholder="접수종료일">
        </div>

        <div id="d_view" class="d_view">
            <span>여행상품</span><span style="width: 16px"></span><span>담당자</span>
            <select name="" id="" title="상품 이름을 선택 하세요">
                <option value="" disabled selected hidden>상품명</option>
                <?php
                foreach($product_list as $p){
                    echo "<option value={$p[0]}>{$p[1]}</option>";
                }
                ?>
            </select>
            <span style="width: 16px"></span>
            <select name="" id="" title="담당자 이름을 선택 하세요">
                <option value="" disabled selected hidden>담당자명</option>
                <?php
                foreach($user_list as $user){
                    echo "<option value={$user[0]}>{$user[1]}</option>";
                }
                ?>
            </select>
            <div style="width: 100%; height: 16px"></div>
            <span>예약경로</span><span style="width: 16px"></span><span>랜드사</span>
            <select name="" id="" title="예약 경로를 선택 하세요">
                <option value="" disabled selected hidden>예약경로</option>
                <?php
                foreach($route_list as $r){
                    echo "<option value={$r[0]}>{$r[1]}</option>";
                }
                ?>
            </select>
            <span style="width: 16px"></span>
            <select name="" id="" title="랜드사를 선택 하세요">
                <option value="" disabled selected hidden>랜드사</option>
                <?php
                foreach($rand_list as $r){
                    echo "<option value={$r[0]}>{$r[1]}</option>";
                }
                ?>
            </select>
        </div>

        <button>조회</button>
    </div>
    <div style="width: 8px"></div>
    <div class="memo_view">
        <div class="title">
            <img src="img/main_memo_title.png" alt="">
            <img src="img/add.png" alt="" style="float: right">
        </div>
        <div class="contents">

        </div>
    </div>
    <div style="width: 8px"></div>
    <div class="notice_event_view">
        <div class="notice_view">
            <div class="title">
                <img src="img/main_notice_title.png" alt="">
                <img src="img/add.png" title="전체 공지사항 화면을 엽니다" style="cursor: pointer; float: right" onclick="window.open('notice.php', '_blank', 'width=1617, height=916')">
            </div>
            <div class="contents">
                <div class="table">
                    <?
                    foreach($notice_list as $n){
                        echo "<div class='row notice_row' value='$n[0]'>";
                        echo "<div class='cell'>$n[1]</div>";
                        echo "<div class='cell'>$n[2]</div>";
                        echo "<div class='cell'>$n[3]</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="event_view">
            <div class="title">
                <img src="img/main_event_title.png" alt="">
                <img src="img/add.png" alt="" style="float: right">
            </div>
            <div class="contents">
                <div class="table">

                </div>
            </div>
        </div>
    </div>
    <div style="width: 100%; height: 8px;"></div>
    <div class="main_view">
        <div class="menu">
            <button id="btn_book" onclick="main_category('book')">계약장부</button>
            <button id="btn_db" onclick="main_category('db')">DB관리</button>
        </div>
        <div id="book_view" class="book_view">
        </div>
        <div id="db_view" class="db_view">
            <div class="list_title">
                <div class="table">
                    <div class="row">
                        <div class="cell">접수번호</div>
                        <div class="cell">분류</div>
                        <div class="cell">한글이름</div>
                        <div class="cell">진행사항</div>
                        <div class="cell">예식일</div>
                        <div class="cell">예약경로</div>
                        <div class="cell">경로담당자</div>
                        <div class="cell">남자연락처</div>
                        <div class="cell">여자연락처</div>
                        <div class="cell">남자이메일</div>
                        <div class="cell">여자이메일</div>
                        <div class="cell">예식장소</div>
                        <div class="cell">담당</div>
                    </div>
                </div>
            </div>
            <div class="list_table">
                <div class="table">
                    <?
                    foreach($db_list as $db){
                        echo "<div class='row'>";
                        echo "<div id='r_id' class='cell' style='display: none'>$db[0]</div>";
                        echo "<div class='cell'>$db[1]</div>";
                        echo "<div class='cell'>$db[3]</div>";
                        echo "<div class='cell'>$db[4]</div>";
                        echo "<div class='cell'>$db[6]</div>";
                        echo "<div class='cell'>$db[7]</div>";
                        echo "<div class='cell'>$db[9]</div>";
                        echo "<div class='cell'>$db[11]</div>";
                        echo "<div class='cell'>$db[12]</div>";
                        echo "<div class='cell'>$db[13]</div>";
                        echo "<div class='cell'>$db[14]</div>";
                        echo "<div class='cell'>$db[15]</div>";
                        echo "<div class='cell'>$db[16]</div>";
                        echo "<div class='cell'>$db[18]</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>

<script>
    $(document).ready(function () {
        $(".notice_row").click(function () {
            window.open("", "notice_view", "toolbar=no, width=1617, height=916, directories=no, status=no, scrollorbars=no, resizable=no");

            var form=document.createElement("form");
            form.action="notice_view.php";
            form.method="post";
            form.target="notice_view"

            var input=document.createElement("textarea");
            input.name="id";
            input.value=$(this).attr('value');

            form.appendChild(input);
            form.style.display="none";

            document.body.appendChild(form);

            form.submit();
        });
    });

    window.onload=function(){
        main_category("<? echo $main_category ?>");
        search_category("<? echo $search_category ?>");
    }

    $(function(){
        $('#w_start_date').datepicker();
        $('#w_end_date').datepicker();
        $('#r_start_date').datepicker();
        $('#r_end_date').datepicker();
    });

    function main_category(category){
        if(category=="book"){
            document.getElementById("btn_book").style.color="white";
            document.getElementById("btn_book").style.backgroundColor="#4c4c4c";

            document.getElementById("btn_db").style.color="#4c4c4c";
            document.getElementById("btn_db").style.backgroundColor="white";

            document.getElementById("book_view").style.display="block";
            document.getElementById("db_view").style.display="none";
        }
        else if(category=="db"){
            document.getElementById("btn_db").style.color="white";
            document.getElementById("btn_db").style.backgroundColor="#4c4c4c";

            document.getElementById("btn_book").style.color="#4c4c4c";
            document.getElementById("btn_book").style.backgroundColor="white";

            document.getElementById("db_view").style.display="block";
            document.getElementById("book_view").style.display="none";
        }
    }

    function search_category(category){
        if(category=="time"){
            document.getElementById("btn_time").style.color="white";
            document.getElementById("btn_time").style.backgroundColor="#4c4c4c";

            document.getElementById("btn_direct").style.color="#4c4c4c";
            document.getElementById("btn_direct").style.backgroundColor="white";

            document.getElementById("t_view").style.display="flex";
            document.getElementById("d_view").style.display="none";
        }
        else if(category=="direct"){
            document.getElementById("btn_direct").style.color="white";
            document.getElementById("btn_direct").style.backgroundColor="#4c4c4c";

            document.getElementById("btn_time").style.color="#4c4c4c";
            document.getElementById("btn_time").style.backgroundColor="white";

            document.getElementById("d_view").style.display="flex";
            document.getElementById("t_view").style.display="none";
        }
    }
</script>