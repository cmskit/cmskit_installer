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

if (!is_writable(__DIR__)) {
    exit(L('directory_is_not_writable'));
}

if (!file_exists('index.php')) {
    file_put_contents('index.php', '<?php header(\'location: backend/\');');
}

if (version_compare(PHP_VERSION, '5.3.3') == -1) {
    exit(PHP_VERSION . ' ' . L('this_php_version_is_too_low'));
}

// logout
if (isset($_GET['logout'])) {
    unset($_SESSION['ACCEPTED']);
}

// if we have a running installation (super.php exists) we should check 
// *  if the user is logged in with superroot password OR 
// *  prompt a login
if (file_exists('backend/inc/super.php')) {
    include 'backend/inc/super.php';

    $body .= '<a style="float:right" href="install.php?logout=1">' . L('logout') . '</a>';

    // check captcha + password
    if (!empty($_POST['password']) && !empty($_POST['captcha'])) {

        //
        if ((crpt(substr($_POST['password'], 0, 200), $super[0]) === $super[0] . ':' . $super[1])
            && (isset($_SESSION['captcha_answer']) && $_POST['captcha'] === $_SESSION['captcha_answer'])
        ) {
            $_SESSION['ACCEPTED'] = 1;
        }
    }

    // draw a login box
    if (empty($_SESSION['ACCEPTED'])) {
        echo $html[0] . '<form class="loginbox" method="post" action="install.php">'
            . '<p><img src="install.php?captcha=1" /></p>'
            . '<p><input type="text" autocomplete="off" name="captcha" placeholder="' . L('enter_captcha') . '" /></p>'
            . '<p><input type="password" name="password" placeholder="' . L('enter_password') . '"/></p>'
            . '<input type="submit" value="' . L('log_in') . '" /></form>' . $html[1];
        exit;
    }
}// running installation END
