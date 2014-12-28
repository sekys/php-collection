<? 
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php'); 
Ajax::Start(); 
Input::NumsA('id');

$objekt = new Cache($id, 30);
$objekt->bot = false;
$objekt->Zlozka('user');
$objekt->ClientCacheTO();
$objekt->FullFile(); 

// Vyberame
$userdata = new Member;
$userdata->GetID($id, 'cs_meno, user_avatar, user_name, user_lastvisit, user_icq, vip, slot');
$userdata->AFinded();

// Pripravyme
echo '
<b><center>', $userdata->out('cs_meno'), '</center></b>
<hr>
<img vspace="5" hspace="5" atl="', $userdata->cs_meno, '" ', $userdata->avatar(), '  border="0" width="100" height="120" >
<br>';
// Web status
if($userdata->user_lastvisit)
	echo 'WEB : ', $userdata->OnlineStatus(), '<br>';
// Icq status
if($userdata->user_icq)
	echo 'ICQ : ', $userdata->ICQStatus();	
$userdata->Bonuses();
