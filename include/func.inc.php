<?php
function filter($str){
    global $link;
    return mysqli_real_escape_string($link, trim(strip_tags($str)));
}
?>
