<?php
include_once ("./common.php");
/**
 * User: leemited
 * Date: 2018-10-15
 * Time: 오후 1:17
 */


$sql = "select * from `cmap_depth1` order by id desc";
$res = sql_query($sql);
$i=0;
while($row=sql_fetch_array($res)){
    $cnt[$i]['cnt']=0;
    $j=0;
    $list[$i] = $row;
    $sql = "select * from `cmap_depth2` where depth1_id = {$row['id']}";
    $res2 = sql_query($sql);
    while($row2 = sql_fetch_array($res2)){
        $k=0;
        $list[$i]['depth2'][$j] = $row2;
        $sql = "select * from `cmap_depth3` where depth1_id = {$row['id']} and depth2_id = {$row2['id']}";
        $res3 = sql_query($sql);
        while($row3 = sql_fetch_array($res3)){
            $l=0;
            $list[$i]['depth2'][$j]['depth3'][$k] = $row3;
            $sql = "select * from `cmap_depth4` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']}";
            $res4 = sql_query($sql);
            while($row4 = sql_fetch_array($res4)){
                $m=0;
                $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l] = $row4;
                $sql = "select * from `cmap_content` where depth1_id = {$row['id']} and depth2_id = {$row2['id']} and depth3_id = {$row3['id']} and depth4_id = {$row4['id']}";
                $res5 = sql_query($sql);
                while($row5 = sql_fetch_array($res5)){
                    $cnt[$i]['cnt']++;
                    $list[$i]['depth2'][$j]['depth3'][$k]['depth4'][$l]['depth5'][$m] = $row5;
                    $m++;
                }
                $l++;
            }
            $k++;
        }
        $j++;
    }
    $i++;
}
echo $cnt[0]['cnt']."/".$cnt[1]['cnt'];
//print_r2($list);
?>
<style>
    table tr td{border:1px solid #000;}
</style>
<table>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
    </tr>
    <tr>
        <td rowspan="6"></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
