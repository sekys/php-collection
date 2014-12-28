<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php'); 

Session::Start();
$captcha = new CaptchaRender();
$captcha->session_var = 'captcha';
$captcha->imageFormat = 'png';
//$captcha->debug = true;
$captcha->resourcesPath = "/home/cstrike/scripts/captcha/";
$captcha->CreateImage();
?>
