<?php
# cms-kit installation script
# compiled at 2014-08-27 13:23:50
# 
# Shrunk with http://code.google.com/p/php-compressor

session_start();
date_default_timezone_set('UTC');
error_reporting(E_ALL^E_NOTICE);
$serviceUrl='http://localhost/cmskit_suggestions/';
if(isset($_GET['captcha']))
{
$ch='23456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';$s='';
for($i=0;$i<5;$i++)$s.=$ch[mt_rand(0,strlen($ch)-1)];
$_SESSION['captcha_answer']=$s;
$s=' '.$s.' ';
$f=5;
$w=imagefontwidth($f)*strlen($s);
$h=imagefontheight($f)+3;
$i=imagecreatetruecolor($w,$h);
$c1=imagecolorallocate($i,mt_rand(150,255),mt_rand(150,255),mt_rand(150,255));
$c2=imagecolorallocate($i,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
imagefill($i,0,0,$c1);
imagestring($i,$f,0,0,$s,$c2);
$i=imagerotate($i,mt_rand(-5,5),$c1);
header('Content-type: image/png');
imagepng($i);
imagedestroy($i);
exit;
}
if(get_magic_quotes_gpc())
{
function stripslashes_gpc(&$value)
{
$value=stripslashes($value);
}
array_walk_recursive($_GET,'stripslashes_gpc');
array_walk_recursive($_POST,'stripslashes_gpc');
}
foreach($_GET as$k=>$v){
$_GET[str_replace('amp;','',$k)]=preg_replace('/\W, /','',$v);
}
$LL=array();
$body='';
$LL['de']=array(
'install'=>'installieren',
'do_you_really_want_to'=>'möchten Sie wirklich',
'the_package'=>'das Paket',
'uninstall'=>'deinstallieren',
'back'=>'zurück',
'next'=>'vor',
'search'=>'suche',
'no_description_available'=>'keine Beschreibung verfügbar',
'please_wait'=>'bitte warten',
);
$LL['en']=array(
'install'=>'install',
'do_you_really_want_to'=>'do you really want to',
'the_package'=>'the package',
'uninstall'=>'uninstall',
'back'=>'back',
'next'=>'next',
'search'=>'search',
'no_description_available'=>'no description available',
'please_wait'=>'please wait',
);
$lang=browserLang(array('de','en'),'en');
$html=array('<!DOCTYPE html>
<html lang="en">
<head>
<title>cms-kit package installer</title>
<meta charset="utf-8" />
<style>
/* HTML5 Boilerplate */
article, aside, details, figcaption, figure, footer, header, hgroup, nav, section { display: block; }
audio, canvas, video { display: inline-block; *display: inline; *zoom: 1; }
audio:not([controls]) { display: none; }
[hidden] { display: none; }

html { font-size: 100%; overflow-y: scroll; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
body { margin: 0; font-size: 13px; line-height: 1.231; }
body, button, input, select, textarea { font-family: sans-serif; color: #222; }

a { color: #00e; }
a:visited { color: #551a8b; }
a:hover { color: #06e; }
a:focus { outline: thin dotted; }
a:hover, a:active { outline: 0; }


/* custom styles */
div {
	width:600px;
	margin: auto;
}
ul{
	
	list-style-type: none;
	
}
li {
	border-bottom: 1px solid #333;
	padding: 5px;
}
.pspan{float:right}

.loginbox {
	width: 300px;
	border: 1px solid #ccc;
	padding: 5px;
	margin: auto;
	margin-top: 100px;
}

#spinner{
	position :absolute;
	display:none;
	z-index: 1;
	top: 40%;
	left: 50%;
	margin-left:-64px;
}

</style>

<link rel="stylesheet" type="text/css" href="vendor/cmskit/jquery-ui/themes/smoothness/jquery-ui.css" />
<script type="text/javascript" src="vendor/cmskit/jquery-ui/jquery.min.js"></script>
<script type="text/javascript" src="vendor/cmskit/jquery-ui/jquery-ui.min.js"></script>

<script type="text/javascript">
if (typeof jQuery == "undefined") {
    document.writeln(unescape("%3Crel=\'stylesheet\' type=\'text/css\' href=\'http://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css\' /%3E"));
    document.writeln(unescape("%3Cscript src=\'http://code.jquery.com/jquery-1.11.1.min.js\' type=\'text/javascript\'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript src=\'http://code.jquery.com/ui/1.11.1/jquery-ui.min.js\' type=\'text/javascript\'%3E%3C/script%3E"));
}
</script>

<script>
$(document).ready(function()
{
	$("button").each(function()
	{
		if($(this).attr("rel"))
		{
			$(this).button({
				icons:{primary:"ui-icon-"+$(this).attr("rel")}, 
				text:false
			})
		}
	});
	//
	$(".package").button().on("click", function()
	{
		var c = $(this).text() === "'.L('install').'",
			q = confirm("'.L('do_you_really_want_to').' "+$(this).text()+" '.L('the_package').' \""+$(this).data("name")+"\"?");

		if (q)
		{
			
			$("#spinner").show();
			$.post("install.php",
			{
				action: c,
				name: $(this).data("name"),
				version: $(this).data("version")
			},
			function(d) {
				$("#spinner").hide();
				alert(d);
			});
			
			var options;
			if (c) {
				options = {
					label: "'.L('uninstall').'",
					icons: {
						primary: "ui-icon-trash"
					}
				};
			} else {
				options = {
					label: "'.L('install').'",
					icons: {
						primary: "ui-icon-disk"
					}
				};
			}
			$(this).button("option", options);
		}
	});
});
</script>
</head>
<body>
','
</body>
</html>
');
function crpt($pass,$salt)
{
if(defined('CRYPT_BLOWFISH')&&CRYPT_BLOWFISH)
{
$msalt='$2a$07$'.substr(md5($salt),0,22).'$';
return$salt.':'.md5(crypt($pass,$msalt));
}
else
{
return$salt.':'.md5(md5(md5(md5(md5(md5(md5($salt.$pass)))))));
}
}
function browserLang($arr=array('en'),$default='en')
{
$al=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
$ua=strtolower($_SERVER['HTTP_USER_AGENT']);
foreach($arr as$k)
{
if(strpos($al,$k)===0||strpos($al,$k)!==false)
{
return$k;
}
}
foreach($arr as$k)
{
if(preg_match("/[\[\( ]{$k}[;,_\-\)]/",$ua))
{
return$k;
}
}
return$default;
}
function L($str)
{
global$LL,$lang;
if(!empty($lang)&&isset($LL[$lang][$str])){
return$LL[$lang][$str];
}else{
if(!isset($LL['x'][$str])){file_put_contents('./ll.txt',"'".$lang.'/'.$str."' => '".$str."',".PHP_EOL,FILE_APPEND);}$LL['x'][$str]=1;
return str_replace('_',' ',$str);
}
}
function download($url,$file=false)
{
$ch=curl_init();
$timeout=5;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
$data=curl_exec($ch);
curl_close($ch);
if($file){
file_put_contents($file,$data);
return true;
}else{
return$data;
}
}
if(!is_writable(__DIR__)){
exit(L('directory_is_not_writable'));
}
if(!file_exists('index.php')){
file_put_contents('index.php','<?php header(\'location: backend/\');');
}
if(version_compare(PHP_VERSION,'5.3.3')==-1){
exit(PHP_VERSION.' '.L('this_php_version_is_too_low'));
}
if(isset($_GET['logout']))
{
unset($_SESSION['ACCEPTED']);
}
if(file_exists('backend/inc/super.php'))
{
include'backend/inc/super.php';
$body.='<a style="float:right" href="install.php?logout=1">'.L('logout').'</a>';
if(!empty($_POST['password'])&&!empty($_POST['captcha']))
{
if((crpt(substr($_POST['password'],0,200),$super[0])===$super[0].':'.$super[1])
&&(isset($_SESSION['captcha_answer'])&&$_POST['captcha']===$_SESSION['captcha_answer'])
)
{
$_SESSION['ACCEPTED']=1;
}
}
if(empty($_SESSION['ACCEPTED']))
{
echo$html[0].'<form class="loginbox" method="post" action="install.php">'
.'<p><img src="install.php?captcha=1" /></p>'
.'<p><input type="text" autocomplete="off" name="captcha" placeholder="'.L('enter_captcha').'" /></p>'
.'<p><input type="password" name="password" placeholder="'.L('enter_password').'"/></p>'
.'<input type="submit" value="'.L('log_in').'" /></form>'.$html[1];
exit;
}
}
if(!file_exists('composer.phar'))
{
download('https://getcomposer.org/installer','composer-dl.inc');
echo$html[0].'<h3>composer downloaded</h3><a href="install.php">please reload</a><pre>';
chmod('composer-dl.inc',0777);
include'composer-dl.inc';
exit('</pre>'.$html[1]);
}
else
{
@unlink('composer-dl.inc');
}
if(file_exists('composer.json'))
{
$composerJson=json_decode(file_get_contents('composer.json'),true);
}
else
{
$composerJson=array(
'minimum-stability'=>'dev',
'require'=>array(
'php'=>'>=5.3.3'
)
);
}
$installedPackages=array();
$installAction='install';
if(file_exists('composer.lock'))
{
$lock=json_decode(file_get_contents('composer.lock'),true);
if(isset($lock['packages']))
{
foreach($lock['packages']as$p)
{
$installedPackages[$p['name']]=$p;
}
}
$installAction='update';
}
if(!empty($_POST['action']))
{
if($_POST['action']=='true'){
$composerJson['require'][$_POST['name']]=$_POST['version'];
echo L('element_added')."\n";
}
if($_POST['action']=='false'){
unset($composerJson['require'][$_POST['name']]);
echo L('element_removed')."\n";
}
$json=stripslashes(json_encode($composerJson));
file_put_contents('composer.json',$json);
chmod('composer.json',0777);
putenv('PATH='.$_SERVER['PATH']);
putenv('COMPOSER_HOME='.__DIR__);
putenv('HOME='.__DIR__);
passthru('php composer.phar '.$installAction.' --no-interaction',$out);
echo$out;
foreach(array('backend','vendor','cache')as$dir){
$iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
foreach($iterator as$item){
chmod($item,0777);
}
}
unlink('.htaccess');
exit;
}
$suggestions=json_decode(download($serviceUrl.'/?'.http_build_query($_GET)),true);
$body.='<div><ul><li><form id="packageheader" method="get" action="install.php">'
.'<span style="float:right">'
.(!empty($suggestions['stat']['back'])?'<button type="button" rel="circle-triangle-w" type="button">'.L('back').'</button>':'')
.(!empty($suggestions['stat']['next'])?'<button type="button" rel="circle-triangle-e" type="button">'.L('next').'</button>':'')
.'</span>'
.'<input type="text" name="q" value="'.$_GET['q'].'" />'
.'<button type="submit" rel="search" type="button">'.L('search').'</button>'
.'</form></li>';
$c=0;
foreach($suggestions['packages']as$k=>$v)
{
$body.='<li><h3>'.$k.'</h3><p>'
.'<span class="pspan"><button class="package" id="cb'.$c.'" type="button" '
.'data-name="'.$k.'" data-version="'.$v['version'].'" '
.'rel="'.(isset($composerJson['require'][$k])?'trash">'.L('uninstall'):'disk">'.L('install')).'</button>'
.'</span> <span>'
.(empty($v['description'])?L('no_description_available'):$v['description'])
.'</span></p></li>';
$c++;
}
$body.='</ul></div>';
$body.='<img id="spinner" src="'.$serviceUrl.'spinner.gif" title="'.L('please_wait').'" />';
echo$html[0].$body.$html[1];