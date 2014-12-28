<?php
// Fusion header
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
debug::oblast('BANKA');

User::MustLogged();
$p0 = isset($_GET['p0']) ? $_GET['p0'] : NULL;
$p1 = isset($_GET['p1']) ? $_GET['p1'] : NULL;
$p2 = isset($_GET['p2']) ? $_GET['p2'] : NULL;
$zombie = ($p0 == 1) ? true : false;

//Stranky
if($zombie) {
	$banka['mysql'] = '`phpbanlist`.`zp_bank`';
	$banka['cesta'] = ROOT.'zombie-banka/';
	$banka['nazov_v'] = 'Zombie';
	$banka['nazov'] = 'zombie';
	$banka['users'] = 'zombieid';
} else {
	$banka['mysql'] = '`phpbanlist`.`dr_bank`';
	$banka['cesta'] = ROOT.'deathrun-banka/';
	$banka['nazov_v'] = 'Deathrun';
	$banka['nazov'] = 'deathrun';
	$banka['users'] = 'm_dr';
}
Header::Title($banka['nazov_v'].' banka');
require SPAGES."banka-functions.php";

if( $p1 == 'rank') {
	rank(true, $p2, 50);
} else {
	WebLog::Add(1, $zombie ? 20 : 21, $userdata['user_id']);
	banka();
}
unset($banka);
DB::select_db("cstrike");

// Fusion footer
debug::oblast('BANKA');
require_once "side_right.php";
require_once "footer.php";
?>