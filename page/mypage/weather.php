<?php
include_once("../../common.php");

include_once(G5_PATH . "/_head.php");
include_once(G5_PLUGIN_PATH . '/jquery-ui/datepicker.php');

$year               = ($_GET['toYear'])     ? $_GET['toYear']   : date("Y");
$month              = ($_GET['toMonth'])    ? $_GET['toMonth']  : date("m");
$date_search_start  = ($_GET['dss'])        ? $_GET['dss']      : date("Y-m-d", strtotime('-1 months'));
$date_search_end    = ($_GET['dse'])        ? $_GET['dse']      : date("Y-m-d", strtotime('+3 days'));
$select_const       = ($_GET['s_const'])    ? $_GET['s_const']  : null;
$select_const_id    = ($_GET['s_c_id'])     ? $_GET['s_c_id']   : null;
$ck_total           = ($_GET['ck_total'])   ? $_GET['ck_total'] : false;
$ck_sunny           = ($_GET['ck_sunny'])   ? $_GET['ck_sunny'] : false;
$ck_rain            = ($_GET['ck_rain'])    ? $_GET['ck_rain']  : false;
$ck_snow            = ($_GET['ck_snow'])    ? $_GET['ck_snow']  : false;
$ck_etc             = ($_GET['ck_etc'])     ? $_GET['ck_etc']   : false;
$ck_tmn             = ($_GET['ck_tmn'])     ? $_GET['ck_tmn']   : false;
$ck_tmx             = ($_GET['ck_tmx'])     ? $_GET['ck_tmx']   : false;
$TMN                = ($_GET['TMN'])        ? $_GET['TMN']      : 5;
$TMX                = ($_GET['TMX'])        ? $_GET['TMX']      : 36;

class my_const
{
    public $id;
    public $name;
    public $lat;
    public $lng;
}

$my_consts = array();

$sql = "select id, cmap_name as name, cmap_construct_lat as lat, cmap_construct_lng as lng from `cmap_my_construct` where (mb_id = '{$member['mb_id']}') and status = 0 order by id desc";
$res = sql_query($sql);
//echo $sql;

while ($row = sql_fetch_array($res)) {
    $temp_const = new my_const;
    $temp_const->id = $row["id"];
    $temp_const->name = $row["name"];
    $temp_const->lat = $row["lat"];
    $temp_const->lng = $row["lng"];

    $my_consts[] = $temp_const;
}

if ($select_const == null && $select_const_id == null) {
    $select_const = $my_consts[0];
}

if ($select_const_id != null){
    foreach($my_consts as $const){
        if($const->id==$select_const_id)
            $select_const=$const;
    }
}

$total = sql_fetch("select count(*) as cnt from `weather` where lat={$select_const->lat} and lng={$select_const->lng}");
if (!$page)
    $page = 1;
$total = $total['cnt'];
$rows = 10;
$start = ($page - 1) * $rows;
$total_page = ceil($total / $rows);

$sql = "select id, const_id, insert_date, concat(addr1, ' ', addr2, ' ', addr3) as addr, POP, SKY, PTY, T3H, REH, POP2, SKY2, PTY2, T3H2, REH2, TMN, TMX, sum(rain00, rain06, rain12, rain18) as rain, sum(snow00, snow06, snow12, snow18) as snow
        from `weather` 
        where lat={$select_const->lat} and lng={$select_const->lng} 
        order by insert_date desc limit {$start}, {$rows};";
//echo $sql;

$res = sql_query($sql);
$c = 0;
while ($row = sql_fetch_array($res)) {
    $worklist[$c] = $row;
    $worklist[$c]['num'] = $total - ($start) - $c;
    $c++;
}
?>

<style>
    .weather_header {
        position: relative;
        width: 100%;
        top: 116px;
    }

    .weather_title {
        position: relative;
        width: 40%;
        float: left;
    }

    .weather_search_condition {
        position: relative;
        width: 60%;
        float: right;
    }

    .weather_result {
        position: relative;
        width: 100%;
        top: 116px;
    }
</style>

<div class="weather_header" class="full-width-fixed">
    <div class="weather_title">
        <div>
            <header class="sub">
                <h2>천후표</h2>
            </header>
        </div>
        <div class="big_month">
            <a class="prev_year" href="javascript:location.href='<?php echo G5_URL ?>/page/mypage/weather?toYear=<?php echo ($month != 1) ? ($prevYear - 1) : $prevYear ?>&toMonth=<?php echo $month ?>&id=<?php echo $id; ?>'">
                <img src="<?php echo G5_IMG_URL ?>/cal_arrow_year_prev.png" alt=""> </a>
            <a class="prev_month" href="javascript:location.href='<?php echo G5_URL ?>/page/mypage/weather?toYear=<?php echo $prevYear ?>&toMonth=<?php echo $prevMonth ?>&id=<?php echo $id; ?>'">
                <img src="<?php echo G5_IMG_URL ?>/cal_arrow_m_prev.png" alt=""> </a>
            <span><?php echo $year; ?>. <?php echo (strlen($month) == 1) ? "0" . $month : $month; ?></span>
            <a class="next_month" href="javascript:location.href='<?php echo G5_URL ?>/page/mypage/weather?toYear=<?php echo $nextYear ?>&toMonth=<?php echo $nextMonth ?>&id=<?php echo $id; ?>'">
                <img src="<?php echo G5_IMG_URL ?>/cal_arrow_m_next.png" alt=""> </a>
            <a class="next_year" href="javascript:location.href='<?php echo G5_URL ?>/page/mypage/weather?toYear=<?php echo ($month != 12) ? ($nextYear + 1) : $nextYear ?>&toMonth=<?php echo $month ?>&id=<?php echo $id; ?>'">
                <img src="<?php echo G5_IMG_URL ?>/cal_arrow_year_next.png" alt=""> </a>
        </div>
    </div>
    <div class="weather_search_condition">
        <div>
            <table style="width:100%">
                <colgroup>
                    <col width="10%">
                    <col width="90%">
                </colgroup>
                <tr>
                    <td>현장선택</td>
                    <td>
                        <select id="const" style="width:100%">
                            <option>현장선택</option>
                            <?php
                                foreach($my_consts as $const){
                                    if($const->id==$select_const->id)
                                        $prt_text="<option value='{$const->id}' selected='selected'>{$const->name}</option>";
                                    else
                                        $prt_text="<option value='{$const->id}'>{$const->name}</option>";
                                }

                                echo $prt_text;
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div>
            출력조건
            <input type="checkbox" id="ck_total" onclick="check_total()" checked>전체
            <input type="checkbox" id="ck_sunny" onclick="check_checked()" checked>맑음
            <input type="checkbox" id="ck_snow" onclick="check_checked()" checked>눈
            <input type="checkbox" id="ck_rain" onclick="check_checked()" checked>비
            <input type="checkbox" id="ck_etc" onclick="check_checked()" checked>기타
            <input type="checkbox" id="ck_tmn" onclick="check_checked()" checked>최저
            <select id="TMN">
                <option value="">최저온도선택</option>
                <?php for ($i = 50; $i >= -50; $i--) { ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == 5) { ?> selected="selected" <?php } ?>>
                        <?php echo $i; ?> </option>
                <?php } ?>
            </select>
            도 이상
            <input type="checkbox" id="ck_tmx" onclick="check_checked()" checked>최고
            <select id="TMX">
                <option value="">최고온도선택</option>
                <?php for ($i = 50; $i >= -50; $i--) { ?>
                    <option value="<?php echo $i; ?>" <?php if ($i == 36) { ?> selected="selected" <?php } ?>>
                        <?php echo $i; ?> </option>
                <?php } ?>
            </select>
            도 이하
        </div>
        <div>
            <label>조회기간</label>
            <input type="text" id="dss" class="datepicker basic_input01" value="<?php echo $date_search_start; ?>">
            <input type="text" id="dse" class="datepicker basic_input01" value="<?php echo $date_search_end; ?>">
            <input type="button" class="basic_btn03" value="조회" onclick="weather_search()">
            <input type="button" class="basic_btn02" value="저장">
            <input type="button" class="basic_btn02" value="출력">
        </div>
    </div>
</div>
<div class="weather_result view" class="full-width-fixed">
    <table class="view_table">
        <colgroup>
            <col width="10%">
            <col width="13%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
            <col width="7%">
        </colgroup>
        <tr>
            <th rowspan="2">일자</th>
            <th rowspan="2">지역</th>
            <th colspan="4">오전 (9시 기준)</th>
            <th colspan="4">오후 (12시 기준)</th>
            <th colspan="2">일일 기온</th>
            <th rowspan="2">일일 강수량 (mm / cm)</th>
        </tr>
        <tr>
            <th>강수확률</th>
            <th>날씨</th>
            <th>온도</th>
            <th>습도</th>
            <th>강수확률</th>
            <th>날씨</th>
            <th>온도</th>
            <th>습도</th>
            <th>최저</th>
            <th>최고</th>
        </tr>
        <?php

        for($i=0;$i<$c;$i++){
            $row=$worklist[$i];
            $weather_text="";
            $weather_text2="";
            $rain_text="";
            $rain_text2="";
            if($row["PTY"]==0){
                switch($row["SKY"]){
                    case(1):
                        $weather_text="맑음";
                        break;
                    case(2):
                        $weather_text="구름조금";
                        break;
                    case(3):
                        $weather_text="구름많음";
                        break;
                    case(4):
                        $weather_text="흐림";
                        break;
                }
            }
            else{
                switch($row["PTY"]){
                case(1):
                    $weather_text="비";
                    break;
                case(2):
                    $weather_text="비/눈";
                    break;
                case(3):
                    $weather_text="눈";
                    break;
                }
            }
            if($row["PTY2"]==0){
                switch($row["SKY2"]){
                    case(1):
                        $weather_text2="맑음";
                        break;
                    case(2):
                        $weather_text2="구름조금";
                        break;
                    case(3):
                        $weather_text2="구름많음";
                        break;
                    case(4):
                        $weather_text2="흐림";
                        break;
                }
            }
            else{
                switch($row["PTY2"]){
                case(1):
                    $weather_text2="비";
                    break;
                case(2):
                    $weather_text2="비/눈";
                    break;
                case(3):
                    $weather_text2="눈";
                    break;
                }
            }
            $prt_text = "
                <tr>
                    <td>{$row["insert_date"]}</td>
                    <td>{$row["addr"]}</td>
                    <td>{$row["POP"]}%</td>
                    <td>{$weather_text}</td>
                    <td>{$row["T3H"]}°C</td>
                    <td>{$row["REH"]}%</td>
                    <td>{$row["POP2"]}%</td>
                    <td>{$weather_text2}</td>
                    <td>{$row["T3H2"]}°C</td>
                    <td>{$row["REH2"]}%</td>
                    <td>{$row["TMN"]}°C</td>
                    <td>{$row["TMX"]}°C</td>
                    <td>{$row["rain"]}mm / {$row["snow"]} cm</td>
                </tr>
            ";
            echo $prt_text;
        }
        ?>
    </table>
</div>

<script>
    function weather_search(){
        var url_str="weather.php?";

        if(document.getElementById('const').selectedIndex!=0)
            url_str+="&s_c_id="+document.getElementById('const').value;
        
        if(document.getElementById('ck_total').checked){
            url_str+="&ck_total="+document.getElementById('ck_total').checked;

            if(document.getElementById('ck_tmn').checked){
                if(document.getElementById('TMN').selectedIndex!=0)
                    url_str+="&TMN="+document.getElementById('TMN').value;
            }
            if(document.getElementById('ck_tmx').checked){
                if(document.getElementById('TMX').selectedIndex!=0)
                    url_str+="&TMX="+document.getElementById('TMX').value;
            }
        }
        else{
            if(document.getElementById('ck_sunny').checked)
                url_str+="&ck_sunny="+document.getElementById('ck_sunny').checked;
            if(document.getElementById('ck_rain').checked)
                url_str+="&ck_rain="+document.getElementById('ck_rain').checked;
            if(document.getElementById('ck_snow').checked)
                url_str+="&ck_snow="+document.getElementById('ck_snow').checked;
            if(document.getElementById('ck_etc').checked)
                url_str+="&ck_etc="+document.getElementById('ck_etc').checked;

            if(document.getElementById('ck_tmn').checked){
                if(document.getElementById('TMN').selectedIndex!=0)
                    url_str+="&TMN="+document.getElementById('TMN').value;
            }
            if(document.getElementById('ck_tmx').checked){
                if(document.getElementById('TMX').selectedIndex!=0)
                    url_str+="&TMX="+document.getElementById('TMX').value;
            }
        }

        url_str+="&dss="+document.getElementById('dss').value;
        url_str+="&dse="+document.getElementById('dse').value;

        //debugger;
        location=url_str;
    }

    function check_total(){
        var checkbox = document.getElementById('ck_total');
        if (checkbox.checked){
            checkbox = document.getElementById('ck_sunny');
            checkbox.checked=true;
            checkbox = document.getElementById('ck_rain');
            checkbox.checked=true;
            checkbox = document.getElementById('ck_snow');
            checkbox.checked=true;
            checkbox = document.getElementById('ck_etc');
            checkbox.checked=true;
            checkbox = document.getElementById('ck_tmn');
            checkbox.checked=true;
            checkbox = document.getElementById('ck_tmx');
            checkbox.checked=true;
        }
        else{
            checkbox = document.getElementById('ck_sunny');
            checkbox.checked=false;
            checkbox = document.getElementById('ck_rain');
            checkbox.checked=false;
            checkbox = document.getElementById('ck_snow');
            checkbox.checked=false;
            checkbox = document.getElementById('ck_etc');
            checkbox.checked=false;
            checkbox = document.getElementById('ck_tmn');
            checkbox.checked=false;
            checkbox = document.getElementById('ck_tmx');
            checkbox.checked=false;
        }
    }
    function check_checked(){
        if(document.getElementById('ck_sunny').checked && document.getElementById('ck_rain').checked && document.getElementById('ck_snow').checked && document.getElementById('ck_etc').checked && document.getElementById('ck_tmn').checked && document.getElementById('ck_tmx').checked)
            document.getElementById('ck_total').checked=true;
        else
            document.getElementById('ck_total').checked=false;
    }
</script>

<?php
include_once(G5_PATH . "/_tail.php");
?>