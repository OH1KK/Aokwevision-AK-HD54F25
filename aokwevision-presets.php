#!/usr/bin/php -q
<?php

$cam_user="admin";
$cam_password="";
$cam_ip="192.168.1.110";

function ReadPresetName($number) {
    global $cam_ip, $cam_user, $cam_passwd;
    $url="http://".$cam_ip."/form/presetAjax";
    $headers=array(
        'Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd),
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest'
    );
    $data = "existFlag=1&flag=2&presetName=&presetNum=".intval($number);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    if (preg_match("/presetName=(.+)/", $return, $match)) {
        echo $match["1"]."\n";
    } else {
        echo "Preset desciption not found\n";
    }
    curl_close($ch);
}

function WritePresetName($number, $name='') {
    global $cam_ip, $cam_user, $cam_passwd;
    $url="http://".$cam_ip."/form/presetSet";
    $headers=array(
        'Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd),
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest'
    );
    $data = "existFlag=0&flag=2&presetNum=".intval($number)."&presetName=".urlencode($name);
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
    $name=@filter_var($_SERVER["argv"]["3"],FILTER_SANITIZE_STRING);
    if ($preset<0 || $preset > 255) die("Invalid preset $preset. Valid value 0-255\n");
    if ($command == "moveto") RecallPreset($preset);
    if ($command == "save") WritePreset($preset);
    if ($command == "setname") WritePresetName($preset,$name);
    if ($command == "getname") ReadPresetName($preset);
} else {
    echo "Usage: ".$_SERVER["argv"]["0"]." [get|set|getname|setname] number {name}\n";
    echo "\n";
    echo "moveto = point camera to saved ptz position\n";
    echo "save = write current ptz values to preset\n";
    echo "getname = display name for preset\n";
    echo "setname = write name for preset\n";
    echo "\n";
    echo "Valid numbers for preset are 0-255\n";
    echo "Maximum length for preset name is 64 chars\n";
    echo "\n";
    echo "Name is saved ptz position's description, for example:\n";
    echo " ".$_SERVER["argv"]["0"]." setname 2 \"carage door\"\n";
}
