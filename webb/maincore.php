<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');

Engine::Start();
Debug::Oblast('MAINCORE');
User::Start();
Theme::Start();

define("IN_FUSION", TRUE);
define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("DB_PREFIX", "fusion_");  
define("USER_IP", $_SERVER['REMOTE_ADDR']);

define("SPAGES", S_PUBLIC."web2/pages/");
define("RAJAX", ROOT."web2/ajax/");
 
$settings = DB::Query("SELECT * FROM ".DB_PREFIX."settings")->fetch_assoc();

// Akcie

require('fusion/fusion.php');
Debug::Oblast('MAINCORE');

