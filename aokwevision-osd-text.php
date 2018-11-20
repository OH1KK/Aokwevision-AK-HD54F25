#!/usr/bin/php -q
<?php

$cam_user="admin";
$cam_password="";
$cam_ip="192.168.1.110";

function WriteOverlay1($OSD1Str, $Text1OSDEnable=1, $Text1OSDX=2, $Text1OSDY=2, $OSD1Size=3) {
    global $cam_ip, $cam_user, $cam_passwd;
    $url="http://".$cam_ip."/form/OSDSet";
    $headers=array(
        'Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd),
        'Content-Type: application/x-www-form-urlencoded',
        'X-Requested-With: XMLHttpRequest'
    );
    $data = ""
    ."Text1OSDEnable=${Text1OSDEnable}&"
    ."Text1OSDX=${Text1OSDX}&"
    ."Text1OSDY=${Text1OSDY}&"
    ."OSD1Size=${OSD1Size}&"
    ."OSD1Str=${OSD1Str}";
    
    /* and other default osd text - comment out if don't want to see */
    $data.="&Text2OSDEnable=0&"
    ."Text3OSDEnable=0&"
    ."Text4OSDEnable=0&"
    ."Text5OSDEnable=0&"
    ."MultipleOSDEnable=1&"
    ."MultipleOSDX=95&"
    ."MultipleOSDY=98&"
    ."MultipleOSDSize=3&"
    ."DTimeOSDEnable=1&"
    ."DTimeOSDX=95&"
    ."DTimeOSDY=2&"
    ."DTimeOSDSize=3";
    
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

if (isset($_SERVER["argv"]["1"])) {
    $overlaytext=urlencode(substr(filter_var($_SERVER["argv"]["1"],FILTER_SANITIZE_STRING),0,35));
    WriteOverlay1($overlaytext);
} else {
    echo "Usage: ".$_SERVER["argv"]["0"]." \"blah blah blah\"\n";
    echo "\n";
    echo "maximum text length is 36 chars\n";
}
