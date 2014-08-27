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

// if composer.phar doesn't exists download (+activate) the composer-downloader
if (!file_exists('composer.phar')) {
    download('https://getcomposer.org/installer', 'composer-dl.inc');
    echo $html[0] . '<h3>composer downloaded</h3><a href="install.php">please reload</a><pre>';
    chmod('composer-dl.inc', 0777);
    include 'composer-dl.inc';
    exit('</pre>' . $html[1]);
} // (try to) remove the composer-downloader
else {
    @unlink('composer-dl.inc');
}

// get composer.json or preset composer
if (file_exists('composer.json')) {
    // if composer.json exists we load
    $composerJson = json_decode(file_get_contents('composer.json'), true);
} else {
    $composerJson = array(
        'minimum-stability' => 'dev',
        'require' => array(
            'php' => '>=5.3.3'
        )
    );
}


// check for installed packages
// if the lock file exists, we know that something was installed already
$installedPackages = array();
$installAction = 'install';
if (file_exists('composer.lock')) {
    $lock = json_decode(file_get_contents('composer.lock'), true);
    if (isset($lock['packages'])) {
        foreach ($lock['packages'] as $p) {
            $installedPackages[$p['name']] = $p;
        }
    }
    $installAction = 'update';
}

// process $_POST if we get an action
if (!empty($_POST['action'])) {
    if ($_POST['action'] == 'true') {
        $composerJson['require'][$_POST['name']] = $_POST['version'];
        echo L('element_added') . "\n";
    }
    if ($_POST['action'] == 'false') {
        unset($composerJson['require'][$_POST['name']]);
        echo L('element_removed') . "\n";
    }

    $json = stripslashes(json_encode($composerJson));
    file_put_contents('composer.json', $json);
    chmod('composer.json', 0777);

    // call composer itself ////////////////////////////////////////////////////////////////////

    // define some environment variables for the commandline application
    putenv('PATH=' . $_SERVER['PATH']);
    putenv('COMPOSER_HOME=' . __DIR__);
    putenv('HOME=' . __DIR__);

    // call composer via command line
    passthru('php composer.phar ' . $installAction . ' --no-interaction', $out);
    echo $out;

    // fix the access rights
    foreach (array('backend', 'vendor', 'cache') as $dir) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $item) {
            chmod($item, 0777);
        }
    }
    unlink('.htaccess');

    exit;
}

// download + parse "suggestions" from webserver
$suggestions = json_decode(download($serviceUrl . '/?' . http_build_query($_GET)), true);


// create the list of packages ///////////////////////////////////////////

// first LI contains the heading (searchbox, pagination...)
$body .= '<div><ul><li><form id="packageheader" method="get" action="install.php">'
    . '<span style="float:right">'
    . (!empty($suggestions['stat']['back']) ? '<button type="button" rel="circle-triangle-w" type="button">' . L('back') . '</button>' : '')
    . (!empty($suggestions['stat']['next']) ? '<button type="button" rel="circle-triangle-e" type="button">' . L('next') . '</button>' : '')
    . '</span>'

    . '<input type="text" name="q" value="' . $_GET['q'] . '" />'
    . '<button type="submit" rel="search" type="button">' . L('search') . '</button>'


    . '</form></li>';

// list packages
$c = 0;
foreach ($suggestions['packages'] as $k => $v) {
    $body .= '<li><h3>' . $k . '</h3><p>'
        . '<span class="pspan"><button class="package" id="cb' . $c . '" type="button" '
        . 'data-name="' . $k . '" data-version="' . $v['version'] . '" '
        . 'rel="' . (isset($composerJson['require'][$k]) ? 'trash">' . L('uninstall') : 'disk">' . L('install')) . '</button>'
        //. (!empty($v['required'])?' readonly="readonly"':'')
        . '</span> <span>'
        . (empty($v['description']) ? L('no_description_available') : $v['description'])
        . '</span></p></li>';
    $c++;
}
$body .= '</ul></div>';

// animated spinner to visualize ajax-background processings
$body .= '<img id="spinner" src="' . $serviceUrl . 'spinner.gif" title="' . L('please_wait') . '" />';

// output html
echo $html[0] . $body . $html[1];

?>
