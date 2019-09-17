<?php
$f = @file_get_contents('court.json_2013-09');
if($f == true){ //json file exists
    $arr = json_decode($f,'true');
    print_r($arr);
}
?>
