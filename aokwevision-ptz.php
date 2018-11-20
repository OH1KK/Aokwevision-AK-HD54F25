#!/usr/bin/php -q
<?php

$sleep=50000; // usec
$cam_user="admin";
$cam_password="";
$cam_ip="192.168.1.110";

function ptz($command=0, $ZFSpeed=0, $PTSpeed=0, $panSpeed=6, $tiltSpeed=6) {
    global $cam_ip, $cam_user, $cam_passwd;
    /* 
    ** panSpeed = 1 - 8
    ** tiltSpeed = 1 - 8
    ** Commands 
    ** 0 = Stop
    ** 1 = Up 
    ** 2 = Down
    ** 3 = Left
    ** 4 = Right
    ** 5 = NE (up + right)
    ** 6 = SE (down + right)
    ** 7 = SW (down + left)
    ** 8 = NW (up + left)
    ** 9 = Iris open
    ** 10 = Iris close 
    ** 11 = Focus near
    ** 12 = Focus far
    ** 13 = Zoom in
    ** 14 = Zoom out
    */
    $url="http://".$cam_ip."/form/setPTZCfg?command=".intval($command)."&ZFSpeed=".intval($ZFSpeed)."&PTSpeed=".intval($PTSpeed)."&panSpeed=".intval($panSpeed)."&tiltSpeed=".intval($tiltSpeed);
    $headers=array('Authorization: Basic '.base64_encode($cam_user.":".$cam_passwd));
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    curl_close($ch);
} 

echo "Use left/right/up/down keys to pan/tilt, + key to zoom in and - key to zoom out. q to quit.\n";

readline_callback_handler_install('', function() { });

system('stty cbreak -echo');
$stdin=fopen('php://stdin','r');
while ( true ) {
    $key = ord(fgetc($stdin));
    switch($key) {
        case 65:
            // up
            ptz(1);
            usleep($sleep);
            ptz(0);
            echo "up\n";
            continue;
        case 66:
            // down
            ptz(2);
            usleep($sleep);
            ptz(0);
            echo "down\n";
            continue;
        case 67:
            // right
            echo "right\n";
            ptz(4);
            usleep($sleep);
            ptz(0);
            continue;
        case 68:
            // left
            echo "left\n";
            ptz(3);
            usleep($sleep);
            ptz(0);
            continue;
        case 43:
            // + zoom in
            ptz(13);
            usleep($sleep);
            ptz(0);
            echo "Zoom in\n";
            continue;
        case 45:
            // + zoom out
            ptz(14);
            usleep($sleep);
            ptz(0);
            echo "Zoom out\n";
            continue;
        case 113:
            die("Quit");
        default:
            //echo "keycode=$key\n";
    } 
}
