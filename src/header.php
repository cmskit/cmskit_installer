<?php
/********************************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Christoph Taubmann (info@cms-kit.org)
 *  All rights reserved
 *
 *  This script is part of cms-kit Framework.
 *  This is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License Version 3 as published by
 *  the Free Software Foundation, or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/licenses/gpl.html
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ************************************************************************************/
session_start();

// set the timezone (not used atm)
date_default_timezone_set('UTC');

// define how to handle error reporting
//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);

$serviceUrl = 'http://localhost/cmskit_suggestions/';

// create captcha image
if (isset($_GET['captcha'])) {
    $ch = '23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ'; // 0/1 look like O/l, so we remove both
    $s = '';
    for ($i = 0; $i < 5; $i++) $s .= $ch[mt_rand(0, strlen($ch) - 1)];
    $_SESSION['captcha_answer'] = $s;
    $s = ' ' . $s . ' ';
    $f = 5;
    $w = imagefontwidth($f) * strlen($s);
    $h = imagefontheight($f) + 3;
    $i = imagecreatetruecolor($w, $h);
    $c1 = imagecolorallocate($i, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
    $c2 = imagecolorallocate($i, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
    imagefill($i, 0, 0, $c1);
    imagestring($i, $f, 0, 0, $s, $c2);
    $i = imagerotate($i, mt_rand(-5, 5), $c1);
    header('Content-type: image/png');
    imagepng($i);
    imagedestroy($i);
    exit;
}

// disabling magic quotes at runtime
if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }

    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    //array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    //array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

// fix/sanitize GET-Parameter 
foreach ($_GET as $k => $v) {
    $_GET[str_replace('amp;', '', $k)] = preg_replace('/\W, /', '', $v);
}

// language array
$LL = array();

// body-html
$body = '';
