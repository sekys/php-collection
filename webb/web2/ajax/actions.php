<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/globals.php');  
Ajax::Start();

Input::issets('akcia');
if(!Input::Nums('id')) Ajax::cExit(); 
if(!User::Logged()) exit; // bez spravy treba
// automaticky token ? :P

// Dalej

switch($akcia)
{
// Priatelia DELETE
    case 1 :  {    
                DB::Query("DELETE FROM `cstrike`.`priatelia` WHERE `id` = '".User::$m->user_id."' AND `priatel` = '".$id."' LIMIT 1");
                WebLog::Add(0, 11, User::$m->user_id, $id);
                break;
            }
    case 4 : // Kandidovat hlasovanie
            {
                if(User::$m->user_joined + Time::Dni(14) > time()) {
                    echo Mess::Tip('Hlasova&#357; mo&#382;e&scaron; a&#382; po 14 d&#328;och !');
                }
                $i = isset($_GET['i']) ? $_GET['i'] : '';
                $i = ($i==1) ? 1.0 : 0.0;
                $vysledok = web2_hlasuj(4, $id, User::$m->user_id, $i, array(0.0, 1.0), 12);                                        
                
                if(!$vysledok) {
                    echo Mess::Tip('U&#382; si raz hlasoval!');
                } else {
                    echo Mess::Tip('Hlas pridan&yacute;');
                }            
                break;
            }
    case 12 : // Kandidovat vymazat
            {    
                // Ak je admin tlacidlo na DELETE na kandidata a na VSETKE HLASY
                if( User::$m->user_level >= 102 or ( $id == User::$m->user_id))
                {
                    // Zaciname
                    WebLog::Add(0, 8, User::$m->user_id, $id); 
                    web2_deletehlasy(4, $id);
                    DB::Query("DELETE FROM `cstrike`.`kandidati` WHERE `id`='".$id."'");               
                    echo Mess::Tip('Kandidat&uacute;ra zru&scaron;en&aacute;');
                } else {
                    echo Mess::Tip('Nem&aacute;&scaron; dostato&#269;n&eacute; pr&aacute;va.');
                }
                break;
            }    
    case 13 : { // kandidovat prijat        
                function kandidat_error() {
                    echo Mess::Tip('Nem&aacute;&scaron; dostato&#269;n&eacute; pr&aacute;va.'); 
                    exit();
                }
                if(!User::$m->wamxid) Ajax::cExit();
                $sql = Amx::Wget(User::$m->wamxid, 'level, username');
                if(!$sql->num_row) kandidat_error();
                $admin = $sql->fetch_assoc();
                
                if($admin['level'] != 1) {
                     kandidat_error();
                } else {                   
                    $data = new Member($id, 'amxid, user_name, cs_steam'); 
                    $amxid = $data->amxid;
                    
                    if(!$amxid) {  // Pridaj ho do databazy..
                        // TODO: Tu neskor mozem dat EXP days podla toho kolko zakupil.
                         Amx::Addadmin( 
                            $data->cs_steam, 
                            '', 
                            'z', 
                            'ce', 
                            $data->cs_steam, 
                            $data->user_name
                         );
                         $amxid = DB::id();
                         Amx::WebLog($admin['username'], 'AMXXAdmin config', 'Added admin: '.$data->cs_steam);
                         WebLog::Add(0, 9, User::$m->user_id, $id, $amxid); 
                         $sprava = 'Nov&yacute; admin zaregistrovan&yacute;.';                        
                    }
                    // Pridaj mu server,..
                    $server = DB::One('SELECT amx FROM `servers` WHERE `headadmin`="'.User::$m->user_id.'"');
                    if($server) {
                        WebLog::Add(0, 10, User::$m->user_id, $id, $server);
                        Amx::addadmintoserver($amxid, $server);
                        if(User::$m->wamxid) $sprava = 'Pr&aacute;va aktualizovan&eacute;.'; 
                    } 
                    echo Mess::Tip($sprava);               
                }
                // TODO: dat to len ze nastavy amxid, prida do zoznamu adminov, ale na servery nie - neskor mozno ano                                
                // TODO: ak po konci kandidatury nedostane hlas kandidatura sa zrusi ako neuspesna........+ .WebLog                    
               break; 
                  
                }
    case 14 : { // Poslanie komentare
    	if(	!isset($_POST['ctype'])) exit;    	
    	$ctype = preg_replace('[^A-Za-z0-9]', '', $_POST['ctype']);
    	$ctype = DB::Clear($ctype);
    	$ctype = DB::Vstup($ctype);
    	$id = DB::Vstup($id);
    	$smileys = false; //isset($_POST['disable_smileys']);
    	
    	$c = new Comment;
    	$c->Set2($ctype, $id);
    	$c->AjaxPost($smileys);
    	// to uz vlastne vsetko posle :)
    } 
    case 15 : {
		// Pozriet administration/comments.php
	}  
	case 16 : { // Tipy
		// Nacitaj objekt
		Input::issets('mid');
		$file = $CLASSES_DIRS[3].$mid.'.php';
		if(!file_exists($file)) {
			Ajax::cExit();		
		}
		require_once($file);
		$objekt = new $mid;
		
		if(isset($_GET['hidden'])) {
			$objekt->Hide();
		} else {
			$objekt->Accept();
		}
	}                                  
    // Priatelia ADD
    case 5 : {    
                if(User::$m->user_id != $id) //sam seba
                {
                    $pocet = DB::One("SELECT COUNT(id) AS pocet FROM `priatelia` WHERE `id`='".User::$m->user_id."' AND `priatel` = '".$id."'");
                    if(!$pocet) // Musime kontrolovat ci uz nema v priatelov ...
                    {
                        DB::Query("INSERT INTO `cstrike`.`priatelia` (`id`, `priatel`) VALUES ('".User::$m->user_id."', '".$id."')");
                        WebLog::Add(0, 5, User::$m->user_id, $id, false, false, true);
                    }
                }
                break;
            }
    // Dennik pridana sprava
    case 6 :  {    
                // Velke riziko SQL / XML injection
                if(isset($_POST['sprava'])) {
                    $sprava = substr($_POST['sprava'], 0, 32);
                    $sprava = DB::Vstup($sprava);
                    WebLog::Add(0, 1, User::$m->user_id, false, false, $sprava, true);            
                    Shop::Kup(User::$m->user_id, 10);
                    echo'<div class="bingo">
                    	<a href="', User::$m->Link(), '">', user::$m->Out('user_name'), ':</a>
                    	<span class="denniktext">', DB::Vystup($sprava), ' </span> 
                	</div>'; 
                    }    
                break;
            }        
    // Anketa
    case 7 :  {
                Input::NumsA('vote');                               
                @$result = DB::One("SELECT COUNT(*) FROM `cstrike`.`fusion_poll_votes` WHERE `vote_user`='".User::$m->user_id."' AND `poll_id`='".$id."' LIMIT 1");
                if (!$result) {
                    // Obchod
                    Shop::Kup(User::$m->user_id, 9);
                    DB::Query("INSERT INTO `cstrike`.`fusion_poll_votes` (vote_user, vote_opt, poll_id) VALUES ('".User::$m->user_id."', '".$vote."', '".$id."')");
                    echo Mess::Tip('Hlas pridan&yacute;.');
                }            
                break;
            }    
    // Fanklub
    case 8 :  {    
                @$pocet = DB::One("SELECT COUNT(id) AS pocet FROM `phpbanlist`.`web2_fanklub` WHERE clan ='".$id."' AND id='".User::$m->user_id."' LIMIT 1");
                if(!$pocet) {    // toggle prepiname
                    DB::Query("DELETE FROM phpbanlist`.`web2_fanklub` WHERE `clan ='".$id."' AND `id`='".User::$m->user_id."'");
                } else {                    
                    DB::Query("INSERT INTO `phpbanlist`.`web2_fanklub` (`clan`, `id`) VALUES ('".$id."', '".User::$m->user_id."')");
                }            
                break;
            }    
    // Hlasovanie *****
    case 9 :  {    
                $typ2 = isset($_GET['typ']) ? $_GET['typ'] : ''; 
                $i = isset($_GET['i']) ? $_GET['i'] : '';
                $vysledok = web2_hlasuj($typ2, $id, User::$m->user_id, $i, array(0.0, 5.0), 10);                                        
                
                if(!$vysledok) {
                    echo '<span class="info_gray">U&#382; si hlasoval.</span>';
                } else {
                    // Odpoved
                    require_once("../hlasovanie.php");
                    $typ = array(1, 2, 3); //globalna
                    hlasovanie(NEHLASUJ, $id, hlasov($typ2, $id), NEHLASUJ, false);
                }                
                break;
            }    
    // Stena pridana sprva
    case 10 :   {    
            $c = new Captcha();
            $c->PostSave('stena_poslat', 'stena_predmet');
            $c->WindowAjax();
            
            function stena_poslat($post) {
    			// Zapisujeme data                                                                    
                $predmet = DB::Vstup(
                    Words::Censore(
                        DB::Clear(
                            trim($post['stena_predmet'])
                        )
                    ));
                $sprava = DB::Vstup(
                    Words::Censore(
                        DB::Clear(
                            trim($post['stena_sprava'])
                        )
                    ));
                DB::Query("INSERT INTO ".DB_PREFIX."comments 
                (comment_item_id, comment_type, comment_name, comment_message, comment_smileys, comment_datestamp, comment_ip) 
                VALUES ('".User::$m->user_id."', 'S', '".$predmet."', '".$sprava."', '1', '".time()."', '".$_SERVER['REMOTE_ADDR']."')");
                // Vratim SCRIPT na pridanie spravy HTML js($txt);                    
			}               
            break;
    }
    // Nastavenie postavicky ...
    case 11 : {
                // Ide vymazat ...
                if(!$id) {
                    // Nastavyme  0, avatar ponechame ....
                    DB::Query("UPDATE `cstrike`.`fusion_users` SET `postava`='0' WHERE user_id = '".User::$m->user_id."'");
                    echo Mess::Tip('Postava &uacute;spe&scaron;ne odstr&aacute;nen&aacute;.');
                } else {
                    if($id > 0 and $id < 35) {
                        // postava + avatar
                        DB::Query("UPDATE `cstrike`.`fusion_users` SET `postava`='".$id."' ".( $_GET['avatar'] ? ", `user_avatar`='".$id."'" : "" )." WHERE user_id = '".User::$m->user_id."'");
                        // vratime novy obrazok ...
                        echo '<img align="absmiddle" border="0" alt="" src="', Member::Postavicka($id, 256), '">
                        <div class="info_gray">(Postava &uacute;spe&scaron;ne nastaven&aacute;)</div>';
                    } else {
                        Ajax::cExit();
                    }
                }
                break;
            }
    // Obchod            2-Len STAV         3-Zakupi a stav                                ...pozor na OR vsetko musi byt poredtym
    case 2 or 3 : {                    
                switch($id) {                    
                    // Vip + Slot + Casove veci ...
                    default : {    
                            $cennik = Shop::Get(-1); 
                            if(!User::$m->amx) {
                                echo Mess::Tip('<a href="'.ROOT.'forum/">&laquo; Pomoc</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mus&iacute;&scaron; ma&#357; aktivovan&yacute; <strong>Hern&yacute; modul. </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a href="'.ROOT.'nastavenie/">Aktivova&#357; &raquo;</a>');
                            } else {                                    
                                // Vypocet dni
                                if( $userdata[$cennik[$id][2]] ) {
                                    $rozdiel = Time::Rozdiel($userdata[$cennik[$id][2]]);
                                    if($rozdiel <= 0) {    
                                        DB::Query("UPDATE `cstrike`.`fusion_users` SET `".$cennik[$id][2]."` = NULL WHERE user_id = '".User::$m->user_id."'");
                                        $rozdiel = 0;
                                    }
                                } else {
                                    $rozdiel = 0; /* ??? */
                                }
                                
                                // Zakupime
                                if($akcia == 3) {                                    
                                    if( User::$m->korun >= $cennik[$id][0]) { //ak ma prachy
                                        // prachy prec a nastav novy datum
                                        $novydatum = 1*60*60*24*($cennik[$id][1] + $rozdiel);
                                        DB::Query("UPDATE `cstrike`.`fusion_users` SET `".$cennik[$id][2]."` = '".$novydatum."', `korun` = `korun` - '".$cennik[$id][0]."' WHERE user_id = '".User::$m->user_id."'");                                         
                                        WebLog::Add(2, 10, User::$m->user_id, $id, false, false, true);
                                        $rozdiel += $cennik[$id][0];
                                    }
                                }
                                
                                // Stavy
                                if(!$rozdiel) {                                    
                                    $x = Functions::Percenta(User::$m->korun, $cennik[$id][0]);                                
                                    $text = ($x >= 100 ) ? '<a href="javascript:obchod('.$id.');">Zak&uacute;pi&#357; za '.$cennik[$id][0].' SVK</a>' : $x.'%';
                                    echo shop_progress($x, $text);                                    
                                } else {                                
                                    $x = Functions::Percenta($rozdiel, $cennik[$id][1]);
                                    $x = ($x < 8) ? 7 : $x;
                                    $text = ($x >= 30 ) ? 'Ost&aacute;va '.$rozdiel : $rozdiel;
                                    $text .= ($rozdiel == 1 ) ? ' de&#328;' : ' dn&iacute;';                                        
                                    echo Shop::Unprogress($x, $text);                                    
                                }
                            }
                            break;
                    }
                };
                break;
            }
    default : { 
        Ajax::cExit(); 
        break; }
};
 
