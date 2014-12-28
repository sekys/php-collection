<?php
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }  
Debug::Oblast('FOOTER');
Theme::Footer();
Web2::Deconstructor(); 

if (iADMIN) {
	DB::Query("DELETE FROM ".DB_PREFIX."flood_control WHERE flood_timestamp < '".(Time::$TIME-360)."'");
	DB::Query("DELETE FROM ".DB_PREFIX."thread_notify WHERE notify_datestamp < '".(Time::$TIME-1209600)."'");
	DB::Query("DELETE FROM ".DB_PREFIX."vcode WHERE vcode_datestamp < '".(Time::$TIME-360)."'");
	DB::Query("DELETE FROM ".DB_PREFIX."new_users WHERE user_datestamp < '".(Time::$TIME-86400)."'");
}
Debug::Oblast('FOOTER');
Engine::Deconstructor();
