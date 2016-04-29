<?php
include('include/db.inc.php');
$visitor_ip = $_SERVER['REMOTE_ADDR'];
$visit_date = date("Y-m-d");

$visit_query = mysqli_query($link, "SELECT id FROM visit WHERE ip='$visitor_ip' AND date='$visit_date'");

if(mysqli_num_rows($visit_query) > 0){
    $row1 = mysqli_fetch_array($visit_query);
    $visitor_id = $row1["id"];
    $visit_update = mysqli_query($link, "UPDATE visit SET visits=visits+1 WHERE id='$visitor_id'");
}else{
    $insert = mysqli_query($link, "INSERT INTO visit (ip, date, visits) VALUES ('$visitor_ip', '$visit_date', 1)");
}
$visit_query_sum = mysqli_query($link, "SELECT SUM(visits) FROM visit");
$visit_sum = mysqli_fetch_row($visit_query_sum); 

$visitors_query = mysqli_query($link, "SELECT DISTINCT ip FROM visit");
$visitors = mysqli_num_rows($visitors_query);
mysqli_close($link);
?>