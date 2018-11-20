#!/usr/bin/php -q
<?php

$cam_user="admin";
$cam_password="";
$cam_ip="192.168.1.110";

function WritePreset($number) {
    global $cam_ip, $cam_user, $cam_passwd;
    $url="http://".$cam_ip."/form/presetSet";
    $headers=array(
        'Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd),
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest'
    );
    $data = "flag=3&presetNum=".intval($number);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    curl_close($ch);
}

function RecallPreset($number) {
    global $cam_ip, $cam_user, $cam_passwd;
    $url="http://".$cam_ip."/form/presetSet";
    $headers=array(
        'Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd),
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest'
    );
    $data = "flag=4&presetNum=".intval($number);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    curl_close($ch);
} 

if (isset($_SERVER["argv"]["2"])) {
    $command=$_SERVER["argv"]["1"];
    $preset=intval($_SERVER["argv"]["2"]);
    if ($preset<0 || $preset > 255) die("Invalid preset $preset. Valid value 0-255\n");
    if ($command == "get") RecallPreset($preset);
    if ($command == "set") WritePreset($preset);
} else {
    echo "Usage: ".$_SERVER["argv"]["0"]." [get|set] number\n";
    echo "\n";
    echo "get = recall saved preset\n";
    echo "set = write position to preset\n";
    echo "\n";
    echo "where number is preset number 0-255\n";
}
