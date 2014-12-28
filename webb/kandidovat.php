<?php
// Fusion header
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
debug::oblast('KANDIDOVAT');

// Hore do headeru AK je prihlaseny AK je casovo ok AK este nepodal ziadost alebo pripadne nehlasoval
if(User::Logged()) {
	$komentar_id = 'K';
	opentable('');
    require(SPAGES."kandidovat/functions.php");
	require(SPAGES."kandidovat/normal.php");
    require(SPAGES."kandidovat/kandiduj.php");
    require(SPAGES."kandidovat/info-parts.php");  
    require(SPAGES."kandidovat/info.php"); 
	header_kandidatury();
	
	if(isset($_GET['info'])) {
		if(!info($_GET['info'])) normal();
	} elseif(isset($_POST['kontrola']) || isset($_GET['ziadost'])) {
		if(!kandiduj()) normal();
	} else {
		normal();
	}
    
	closetable();
	unset($komentar_id);
} else {
	User::Unlogin();
}
		
// Fusion footer
debug::oblast('KANDIDOVAT');
require_once "side_right.php";
require_once "footer.php";
?>