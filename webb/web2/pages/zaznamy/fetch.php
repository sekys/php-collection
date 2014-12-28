<?

function ZaznamyFetch(&$kategoria, &$cas, &$podmienka) 
{
    // Hladame kategoriu
    global $userdata;
    switch($kategoria) {
        case "liga" : { 
            if($userdata['clan_id']) {
                $podmienka = "`kat`='-1'";
            } else {
                echo Mess::Error('Webov&eacute; z&aacute;znamy ' , 'Nem&aacute;&scaron; clan  !');
				return false;
			}    
            break;    
        }
        case "vseobecne" : { $podmienka = "`kat`='0'"; break; }
        case "banka" : { $podmienka = "`kat`='1'"; break; }                
        case "obchod" : { $podmienka = "`kat`='2'"; break; }                
        default : { 
            $podmienka = "`typ` > 0"; 
            $kategoria = "vsetko";
            break; 
        }
    };
	
	// Priradime podla casu
    switch($cas) {
        case "dnes" : {  $TIME = Time::$TIME-60*60*24*1; break; }
        case "tyzden" : {  $TIME = Time::$TIME-60*60*24*7; break; }
        case "mesiac" : {  $TIME = Time::$TIME-60*60*24*30; break; }                    
        default : { 
            $TIME = '0'; 
            $cas = 'vsetko';
            break; 
        }
    };
    $podmienka .= " AND `kedy` > '".$TIME."'"; 
	return true;
}