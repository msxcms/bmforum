<?php
/*
 BMForum Datium! Bulletin Board Systems
 Version : Datium!
 
 This is a freeware, but don't change the copyright information.
 A SourceForge Project.
 Web Site: http://www.bmforum.com
 Copyright (C) Bluview Technology
*/
include("datafile/config.php");
$tmpdir = getdate(time());
$tmpdir = $tmpdir[year] . $tmpdir[yday];
if (!$sess_cust) {
    @session_save_path("tmp");
} 
@session_name('bmsess');
@session_cache_limiter("nocache");
@session_start();
$p = $_GET["p"];

if ($_GET['reget'] == 1) {
	$authnum = $gd_auth ? getCode(4,1) : rand(10000, 99999);
	$_SESSION[checkauthnum] = $authnum;
}

if ($gd_auth != 1) {
    $numbers = array(0 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKsOnmqSPjtT1ZdnnjCUqBQAOw==',
        1 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUjAEWyMqoXIprRkjxtZJWrz3iCBQAOw==',
        2 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUDH5hiKubnpPzRQvoVbvyrDHiWAAAOw==',
        3 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKbaHgRyUZtmlPtlfnnMiGUFADs=',
        4 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjAN5mLDtjFJMRjpj1Rv6v1SHN0IFADs=',
        5 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhA+Bpxn/DITL1SRjnps63l1M9RQAOw==',
        6 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVjIEYyWwH3lNyrQTbnVh2Tl3N5wQFADs=',
        7 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIUhI9pwbztAAwP1napnFnzbYEYWAAAOw==',
        8 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDH5hiKubHgSPWXoxVUxC33FZZCkFADs=',
        9 => 'R0lGODlhCAANAJEAAAAAAP////4BAgAAACH5BAQUAP8ALAAAAAAIAA0AAAIVDA6hyJabnnISnsnybXdS73hcZlUFADs=',
        );

    preg_match ("/([0-9]{1})([0-9]{1})([0-9]{1})([0-9]{1})([0-9]{1})/", $_SESSION[checkauthnum], $shownum);
    $showtmp = $shownum[$p];
    $echonum = base64_decode($numbers[$showtmp]);
    flush();

    echo $echonum;
    exit();
} else {
    $width = "70"; // Width
    $height = "25"; // Height
    $len = 4; // Verify code len
    $bgcolor = "#ffffff"; // background color
    $noise = true; // make noise
    $noisenum = 200; // noise points
    $border = true; // border
    $bordercolor = "#000000";
    $image = imageCreate($width, $height);
    $back = getcolor($bgcolor);
    imageFilledRectangle($image, 0, 0, $width, $height, $back);
    $size = $width / $len;
    if ($size > $height) $size = $height;
    $left = ($width - $len * ($size + $size / 10)) / $size;
    
    $code = $_SESSION[checkauthnum];

    $textColor = imageColorAllocate($image, rand(0,200), rand(0,200), rand(0,200));
    imagestring($image, 551, 15, 5, $code, $textColor); 
    
    if ($noise == true) setnoise();
    $bordercolor = getcolor($bordercolor);
    if ($border == true) imageRectangle($image, 0, 0, $width-1, $height-1, $bordercolor);
    header("Content-type: image/png");
    imagePng($image);
    imagedestroy($image);
    exit;
} 
// Verify Code Functions
function getcolor($color)
{
    global $image;
    $color = preg_replace ("/^#/i", "", $color);
    $r = $color[0] . $color[1];
    $r = hexdec ($r);
    $b = $color[2] . $color[3];
    $b = hexdec ($b);
    $g = $color[4] . $color[5];
    $g = hexdec ($g);
    $color = imagecolorallocate ($image, $r, $b, $g);
    return $color;
} 
function setnoise()
{
    global $image, $width, $height, $back, $noisenum;
    for ($i = 0; $i < $noisenum; $i++) {
        $randColor = imageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
        imageSetPixel($image, rand(0, $width), rand(0, $height), $randColor);
    } 
} 
function getCode ($length = 9, $type = 0)
{
    $str = $type ? 'ABCEFHJKMNPRSTUVWXY13456789' : 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $result = '';
    $l = strlen($str) - 1;

    for($i = 0;$i < $length;$i ++){
        $num = rand(0, $l);
        $result .= $str{$num};
    }
    return $result;
}