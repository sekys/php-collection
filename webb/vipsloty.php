<?php
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
Debug::Oblast('VIPSLOTY');

$farba = true; 
Header::Title('VIP & SLOT');

echo '
	<table width="100%" cellspacing="0" cellpadding="2" class="serverlist_table bb">
		<tr><td width="100%" align="left" colspan="3">
			<strong>Zak&uacute;pene VIP / Slot</strong>
		</td></tr>
	</table>
';
	
// Nakupene VIP alebo slot
$z = new Zoznam;
$z->actual = Input::Num('zoznam');
$z->list = 50;

$z->celkovo = DB::One("SELECT COUNT(user_name) FROM `cstrike`.`fusion_users` 
								WHERE vip IS NOT NULL OR slot IS NOT NULL");
if($z->celkovo) {					
	
	echo '<table class="shadow_list" width="100%" cellspacing="0" cellpadding="1">';	
	$sql = DB::Query("SELECT user_name, user_id, vip, slot, user_groups FROM `cstrike`.`fusion_users` 
						WHERE vip IS NOT NULL OR slot IS NOT NULL
						ORDER BY user_name DESC
					".$zoznam->mysql($zoznam, $pocet));
	
	$m = new Member;				
	while($m->next($sql))
	{
		$farba  = !$farba;
		echo $farba ? '<tr class="server_status_pasik">' : '<tr>';
		echo '
			<td>', $m, '</td>
			<td width="70" align="center" class="info_gray">';
			if($farba) { echo '<span class="color_white">'; }

			if($m->vip and $m->slot) {
				echo 'VIP & Slot';
			} elseif($m->vip) {
				echo 'VIP';
			} else {
				echo 'Slot';
			}
			if($farba) { echo '</span>'; }
			echo '
			</td>
			<td width="110" align="center">';
				if($m->vip and $m->slot ) {
					echo date("j.n H:m", $m->vip);
					echo date("j.n H:m", $m->slot);
				} elseif($m['vip']) {
					echo date("j.n H:m", $m->vip);
				} else {
					echo date("j.n H:m", $m->slot);
				}                
			echo ' <img alt="Cas" src="', ROOT, 'web2/images/clock.png" />
			</td>
		</tr>';
	}
	echo '</table>';
	$z->Make($z->actual ? ROOT.'vip-sloty/' : ROOT.'vip-sloty/%s/');
}
echo '<br /><br /><br /><br /><br /><br />';	
Debug::Oblast('VIPSLOTY');
require_once "side_right.php";
require_once "footer.php";
?>
