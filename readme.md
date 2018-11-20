# Aokwevision AK-HD54F25 control

4 inch Mini Size 5MP 4MP outdoor Onvif Network H.264/265 IP PTZ camera speed dome 30X zoom ptz ip camera 60m IR nightvision

## Camera Stream

You can watch camera stream from camera using videolan media player

    vlc rtsp://192.168.1.110/1/h264major
    vlc rtsp://192.168.1.110/1/h264minor
    
## Pan, tilt, zoom

    ./aokwevision-ptz.php
    
to pan, tilt and zoom using keyboard keys left, right, up, down, +, -, and q for quit

## Programmin presets to camera and recalling presets

    ./aokwevision-presets.php [get|set] number

Get = recall saved preset
Set = write current position to preset

where number is preset number 0-255

## Set overlay text

    ./aokwevision-osd-text.php "blah blah blah"

Sets overlay text1 to top left corner. Maximum text length 36 chars.

Camera supports also (at least) scandinavian chars, eg you can write "Tämä on kova kamera PERKELE!" which is nice.

There are total 5 text preset places available, thus this code supports only one at this moment.

## Motivation

I buyed this camera from aliexpress https://www.aliexpress.com/item/New-arrival-4MP-4-inch-Mini-Size-Network-Onvif-IP-PTZ-speed-dome-20X-optical-zoom/32633649414.htm . Nice piece of hardware. Because API was poorly documented, I sniffed some commands from webui (that sucks, in my opinion) and write short programs to do same things from linux console. 

Camera supports ONVIF. You might not need this code at all.

## License

[The MIT License (MIT)](LICENSE)

Copyright (c) 2018 Kari Karvonen
