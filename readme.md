# Aokwevision AK-HD54F25 control

4 inch Mini Size 5MP 4MP outdoor Onvif Network H.264/265 IP PTZ camera speed dome 30X zoom ptz ip camera 60m IR nightvision

Hardware info

* System chip HiSilicon Hi3516A100
* Sensor type Brillnics BRV0500 1/2.5” CMOS

## Camera default username and password

Default IP address is 192.168.1.110

Default username is admin and password is blank.

Note: even you change those, there is invisible hardcoded account that gives you access to admin interface. Invisible username and password is HANKVISION_2016

This camera is insecure. Keep your camera in isolated private network and do not give it direct access to internet.

## Telnet

root:hkipc2016

So with system access we can use openipc.org tool to get system information:


```
Welcome to HiLinux.
~ # mount -o nolock 95.217.179.189:/srv/ro /mnt/nfs/
~ # /mnt/nfs/ipctool 
---
board:
  vendor: Hankvision
  model: V6202IR-OV4689
chip:
  vendor: HiSilicon
  model: 3516AV100
ethernet:
  mac: "00:xx:xx:xx:xx:xx"
  u-mdio-phyaddr: 512943773
  phy-id: 0xa5a5a5a5
  d-mdio-phyaddr: a5a5
rom:
  - type: nor
    block: 64K
    partitions:
      - name: boot
        size: 0x50000
        sha1: 8e569ea5
        contains:
          - name: uboot-env
            offset: 0x40000
      - name: kernel
        size: 0x2b0000
        sha1: f848b1e2
      - name: rootfs
        size: 0x200000
        path: /,jffs2
        sha1: cf51c57c
      - name: data
        size: 0xb00000
        path: /mnt/flash,jffs2,rw
    size: 16M
    addr-mode: 3-byte
ram:
  total: 256M
  media: 160M
firmware:
  u-boot: "2010.06 (May 18 2015 - 09:40:27)"
  kernel: "3.4.35 (Sat Sep 12 11:02:20 CST 2015)"
  toolchain: gcc version 4.8.3 20131202 (prerelease) (Hisilicon_v300) 
  libc: uClibc 0.9.33.2
  sdk: "Hi3516A_MPP_V1.0.4.0 B040 Release (Jun 28 2015, 09:24:48)"
  god-app: /mnt/flash/Server/mediaserver/sdk_app

In this case the sensor is not Brillnics. By model type it's definitely OV4689.
So it's partially supported by OpenIPC.org firmware.
As I see the platform hisilicon-osdrv-hi3516av100 already have sensor support libsns_ov4689.so:

https://github.com/widgetii/openipc-2.1/tree/master/general/package/hisilicon-osdrv-hi3516av100/files/sensor

So soon the camera could be updated with this wonderful and modern firmware.

```

## Camera Stream

You can watch camera stream from camera using videolan media player

    vlc --rtsp-tcp rtsp://192.168.1.110/1/h264major
    vlc --rtsp-tcp rtsp://192.168.1.110/1/h264minor
    vlc --rtsp-tcp rtsp://192.168.1.110/mpeg4
    vlc --rtsp-tcp rtsp://192.168.1.110/mpeg4cif
    vlc --rtsp-tcp rtsp://192.168.1.110/mpeg4/ch01/main/av_stream
    vlc --rtsp-tcp rtsp://192.168.1.110/mpeg4/ch01/sub/av_stream
    vlc --rtsp-tcp rtsp://192.168.1.110/1/major_sjkd
    vlc --rtsp-tcp rtsp://192.168.1.110/1/minor_sjkd

## Still image
    
    wget -q http://admin:@192.168.1.110/jpgimage/1/image.jpg
    
or 

    wget -q http://admin:@192.168.1.110/mjpgstreamreq/1/image.jpg -O image.jpg
    
or without password
    
    date +%s%N | cut -b1-13 | xargs -i wget -q "http://192.168.1.110/jpgmulreq/1/image.jpg?key={}&lq=12" -O snapshot.jpg

See slideshow.html which has example javascript webcam code.

## HTML5 browser live feed

It is possible to get good quality stream to webpage. See html5livestream.txt which has ffmpeg magick and hls.js based webpage example

## Pan, tilt, zoom

    ./aokwevision-ptz.php
    
to pan, tilt and zoom using keyboard keys left, right, up, down, +, -, and q for quit

## Programmin presets to camera and recalling presets

    ./aokwevision-presets.php [get|set|getname|setname] number {name}

Where

    moveto = point camera to saved ptz position
    save = write current ptz values to preset
    getname = display name for preset
    setname = write name for preset

Valid numbers for preset are 0-255
Maximum length for preset name is 64 chars

Name is saved ptz positio's description, for example

    ./aokwevision-presets.php setname 2 "carage door"

where number is preset number 0-255

## Set overlay text

    ./aokwevision-osd-text.php "blah blah blah"

Sets overlay text1 to top left corner. Maximum text length 36 chars.

Camera supports also utf-8 chars, so you can write "Saunan lämpö on 78°C"

There are total 5 text preset places available, thus this code supports only one at this moment.

## Motivation

I buyed this camera from aliexpress https://www.aliexpress.com/item/New-arrival-4MP-4-inch-Mini-Size-Network-Onvif-IP-PTZ-speed-dome-20X-optical-zoom/32633649414.htm . Nice piece of hardware. Because API was poorly documented, I sniffed some commands from webui (that sucks, in my opinion) and write short programs to do same things from linux console. 

Camera supports ONVIF. You might not need this code at all.

## License

[The MIT License (MIT)](LICENSE)

Copyright (c) 2018 Kari Karvonen
