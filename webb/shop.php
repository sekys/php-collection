<?php
// Fusion header
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";	

//Stranky
User::MustLogged();
Debug::Oblast('SHOP');
Header::Title('Shop');
Resource::All('shop');
Header::Js(' $(document).ready(function(){ shop_load(); }); ', 1);
$url = ROOT.'web2/ajax/shop.php?widget=';
	
echo '
<div class="pravidla_head">	
	<div id="tabs_obchod">
		<ul>			
			<li><a href="', $url, 'uvod"><span> &Uacute;vod </span></a></li>
			<li><a href="', $url, 'zmena"><span> Zmen&aacute;re&#328; </span></a></li>
			<li><a href="', $url, 'vip"><span> V I P </span></a></li>
			<li><a href="', $url, 'slot"><span> SLOT </span></a></li>
			<li><a href="', $url, 'admin"><span> Admin </span></a></li>
			<li><a href="', $url, 'body"><span> Body </span></a></li>
			<li><a href="', $url, 'panacikov"><span> Postavi&#269;ky </span></a></li>
			<li><a href="', $url, 'logy"><span> Z&aacute;znamy </span></a></li>
		</ul>	
	</div>
</div>';
/*
	<li><a href="', $url, 'avatar"><span> Avatar </span></a></li>
	<li><a href="', $url, 'bonusy"><span> Bonusy </span></a></li>
*/

// Fusion footer
Debug::Oblast('SHOP');
require_once "side_right.php";
require_once "footer.php";
?>