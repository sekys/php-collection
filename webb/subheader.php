<?php
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

Debug::Oblast('SUBHEADER');

if ($settings['maintenance'] == "1" && !iADMIN) Engine::Presmeruj(ROOT."maintenance.php");
if (iMEMBER) {
    $result = DB::Query("UPDATE ".DB_PREFIX."users SET user_lastvisit='".Time::$TIME."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");
} else {
    WebLog::Add(0, 0, 0, false, false, false, false); 
}
// Include cele WEB 2.0
Web2::Constructor();
Theme::Header();
Debug::Oblast('SUBHEADER');
