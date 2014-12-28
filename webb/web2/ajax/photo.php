<?
define('S_GALERIA', '/home/cstrike/scripts/galeria/');

$id = isset($_GET['id']) ? $_GET['id'] : '';
$w = isset($_GET['w']) ? $_GET['w'] : '';
$h = isset($_GET['h']) ? $_GET['h'] : '';

if(!$id) die('<h1> Image not found </h1>');
header("Content-type: ".$header."; charset: UTF-8");