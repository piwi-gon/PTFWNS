<?php
if($_GET['selectedYear'] != "") {
    $arr = array('Jan' => 1143, 'Feb' => 1232, 'Mar' => 1305, "Apr" => 1200, 'Mai' => 1343, 'Jun' => 1256, 'Jul' => 1134, "Aug" => 1278, 'Sep' => 1222, 'Okt' => 1432, 'Nov' => 1405, "Dez" => 1541);
} else if($_GET['selectedMonth'] != "") {
    $arr = array('01' => 43, '02' => 32, '03' => 30, "06" => 20, '07' => 34, '08' => 56, '09' => 34, "10" => 78, '12' => 22, '13' => 32, '14' => 40, "15" => 41);
}
echo json_encode($arr);
exit();
?>