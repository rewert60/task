<?php
function filter($str){
    global $link;
    return misqli_real_escape_string($link, trim(strip_tags($str)));
}
?>
