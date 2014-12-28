<?php
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
Debug::Oblast('SERVER');

$id = Input::CoolURI('p1');
require SPAGES."server-functions.php";
Resource::Css('servery');
Header::Title('Servery');
opentable('Servery');

if($id > 0) {	// Mame port
	ServerThemeItem($id);
} else {
	ServerThemeList();
}

closetable();
Debug::Oblast('SERVER');
require_once "side_right.php";
require_once "footer.php";
?>
