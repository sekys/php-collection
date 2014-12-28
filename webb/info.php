<?php
require_once "maincore.php";
Input::issets('off', 'web');

function LoadInfoFile() {
	global $web;
	require SPAGES.'info/'.$web.'.php';
}
function TypicalPage($name) {
	Theme::TypicalPage($name, 'LoadInfoFile');
}
function InfoPage($name) {
	global $off;
	Theme::InfoPage($off ? false : $name, 'LoadInfoFile');
}

switch($web)
{
	case 'sponzori': TypicalPage('Sponzori'); break;
	case 'historia': TypicalPage('Hist&oacute;ria'); break;
	case 'pravidla': InfoPage('V&scaron;eobecn&eacute; pravidl&aacute;'); break;
	case 'ligove': InfoPage('Herne&eacute; pravidl&aacute;'); break;
	case 'herne': InfoPage('Ligov&eacute; pravidl&aacute;'); break;
	case 'kredity': InfoPage('Kredity'); break;
	case 'legenda': InfoPage('Legenda'); break;
	default : Engine::GoHome(); break;
}
