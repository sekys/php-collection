<?

function profil_stavba($id) {
	Debug::Oblast('PROFIL_'.$id);
    Resource::Css('profil');
    echo '
		<table width="960" border="0" cellspacing="0" cellpadding="0" id="'.$id.'">
			<tr>
				<td width="730" valign="top">';
					profil_header();	
			echo '</td>
				<td valign="top">';
					profil_sidebar();
			echo '</td>
			</tr>
		</table>
	';
	Debug::Oblast('PROFIL_'.$id);
}
function profil_warning($sprava) {
	echo "<table width='100%' height='70' align='center'>
			<tr>
				<td align='center'>
					<img src='", ROOT, "web2/images/info-icon.png' alt='' title='' />
				</td>
				<td align='center' valign='middle' class='info_gray'>", $sprava, "</td>
			</tr>
		</table>";	
} 
function profil_viac($href, $nazov='VIAC') {
	echo '
	<span class="more-link"><a href="#" onclick="profil_', $href, '();">
		', $nazov, ' <img alt="" border="0" src="', ROOT, 'web2/images2/icon-arrow.gif"/>
	</a></span>';
}
function profil_item_nahlad($src, $href='/web2/galeria.php', $video=false) {
	echo '
	<a href="'.$href.'" class="thumb">
		<em class="item '.( $video ? 'vid' : 'ss' ).'"/>
		<img alt="" src="'.$src.'"/>
	</a>';
}
function profil_login($meno) {
	global $p;
	return profil_warning('<a href="'.ROOT.'registrovat/'.$p->user_name.'/">Pripoj sa do GeCom Lekos komunity a spoj sa s '.$meno.'.</a>');
}
//echo '<ul id="item_list" class="main-videos clearfix">';
function profil_item($data) {
	echo '
	<li class="item clearfix">'; 
		profil_item_nahlad($src, '/web2/galeria.php');	
	echo ' 	
		<div class="details">
			<h3><a href="', ROOT, 'galeria.php?typ=', $data['typ'], '&id='.$data['id'].'">'.$data['meno'].'</a></h3>
			<p>', DB::Vystup($data['popis']), '</p>
			<div class="meta clearfix">
				<a href="/games/misc/" class="game icon clearfix">
					<img alt="" src="http://database-images.wegame.com/game/18/24.jpg?t=-1"/>
					<span class="v-center-wrapper">
						<span class="v-center-middle">Misc Gaming</span>
					</span>
				</a>
			</div>
		</div>
		<table>
			<tr>
				<th>P&aacute;&#269;ilo sa :</th>
				<td>0</td>
			</tr>
			<tr>
				<th>Videlo:</th>
				<td>39 &#318;ud&iacute;</td>
			</tr>
			<tr>
				<th>Koment&aacute;rov:</th>
				<td>0</td>
			</tr>
			<tr>
				<th>Pridan&eacute;:</th>
				<td>', Time::Rozdiel($date['pridane']), '</td>
			</tr>
		</table>
		<div class="clearfix"/>
	</li>';
}
function profil_sparse($name) {
     echo '<h4 class="ui-widget-header pheader_fix"> ', $name, ' </h4>';
}
function bar($x, $txt) {	
	echo '
	<span class="game-played-bar">
		<b style="width: '.$x.'%;"> </b>
		<em>'.$txt.'</em>
	</span>';
}
//echo '<ul>';
/*
<style>
.more-link {
	border-top:1px solid #C0D1E8;
	margin-top:2px;
	padding-top:10px;
}
.more-link a{
	display:block;
	font-weight:bold;
	margin:10px 0 0;
}
.more-link a:hover {
	color:#000000;
	cursor:pointer;
}




a.thumb {
	-moz-background-clip:border;
	-moz-background-inline-policy:continuous;
	-moz-background-origin:padding;
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:5px;
	background:#B0C5E2 none repeat scroll 0 0;
	float:left;
	line-height:0;
	margin-right:10px;
	position:relative;
}
a.thumb em.item {
	-moz-background-clip:border;
	-moz-background-inline-policy:continuous;
	-moz-background-origin:padding;
	bottom:0;
	display:block;
	height:16px;
	position:absolute;
	right:0;
	width:16px;
}
a.thumb em.ss {
	background:transparent url(/web2/web2/images/icon-thumb-ss.png) no-repeat scroll 0 0;
}
a.thumb em.vid {
	background:transparent url(/web2/web2/images/icon-thumb-vid.png) no-repeat scroll 0 0;
}
a.thumb img {
	height:70px;
	padding:5px;
	width:94px;
}



ul.main-videos li.item {
	padding-top:5px !important;
}
.main-videos .item {
	border-bottom:1px solid #C0D1E8;
	padding:14px 0;
}
.clearfix {
	display:block;
}
.main-videos .details {
	float:left;
	margin-left:0;
	overflow:hidden;
	padding-top:2px;
}
.main-videos .details .meta {
	padding:7px 0 0;
}
.main-videos .details .meta a.icon {
	float:left;
	margin-top:-6px;
}
.meta a.icon {
	margin-right:14px;
}
a.icon span.v-center-wrapper {
	float:left;
	font-size:12px;
	height:28px;
	overflow:hidden;
}
.main-videos table {
	float:right;
	font-size:11px;
	margin-top:1px;
}
table {
	border-collapse:separate;
	border-spacing:0;
}
.main-videos table th {
	font-weight:bold;
	padding:2px 10px;
}
.main-videos caption, th, td {
	font-weight:normal;
	text-align:left;
}
</style>
';*/
?>