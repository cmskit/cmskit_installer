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


/*
* some Global Functions
*/

/**
 * encrypt Passwords (using bcrypt if possible)
 * @param string password
 * @pram string salt-string
 * @return string "salt:password-hash"
 */
function crpt($pass, $salt)
{
    if (defined('CRYPT_BLOWFISH') && CRYPT_BLOWFISH) {
        // create the Salt activating bcrypt with 7 Rounds
        $msalt = '$2a$07$' . substr(md5($salt), 0, 22) . '$';
        return $salt . ':' . md5(crypt($pass, $msalt));
    } else {
        // Fallback to the weaker MD5-Encryption (7 Rounds)
        // throw new Exception('bcrypt is not supported!');//test
        return $salt . ':' . md5(md5(md5(md5(md5(md5(md5($salt . $pass)))))));
    }
}


/**
 * Detect Browser-Language
 *
 * @param mixed File-Array containing Translations
 * @param string Default-Language
 * @return preferred detected Language
 */
function bl($arr = array('en'), $default = 'en')
{
    $al = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);

    // Try to detect Primary language if several languages are accepted.
    foreach ($arr as $k) {
        if (strpos($al, $k) === 0 || strpos($al, $k) !== false) {
            return $k;
        }
    }
    // Try to detect any language if not yet detected.
    foreach ($arr as $k) {
        if (preg_match("/[\[\( ]{$k}[;,_\-\)]/", $ua)) {
            return $k;
        }
    }
    // Return default language if language is not yet detected.
    return $default;
}

/**
 * Translate Strings
 * @param $str string
 * @return mixed translated string
 */
function L($str)
{
    global $LL, $lang;
    if (!empty($lang) && isset($LL[$lang][$str])) {
        return $LL[$lang][$str];
    } else {
        /*
        if (!isset($LL['x'][$str])) {
            file_put_contents('./ll.txt', "'" . $lang . '/' . $str . "' => '" . $str . "'," . PHP_EOL, FILE_APPEND);
        }*/
        $LL['x'][$str] = 1;
        return str_replace('_', ' ', $str);
    }
}

/**
 *
 *
 */
function download($url, $file = false)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    if ($file) {
        file_put_contents($file, $data);
        return true;
    } else {
        return $data;
    }
}
