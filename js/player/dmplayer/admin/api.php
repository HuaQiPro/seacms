<?php
include ('data.php');
    $json = [
       'code' => 1,
       'data' => $yzm
    ];
die(json_encode($json));
