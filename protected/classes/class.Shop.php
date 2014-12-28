<?
class Shop 
{
    public static function GetAbs($id) { return abs(self::Get($id)); }
    public static function Get($id)  {	
	    switch($id) {
		    case -1: {
				    // Cennik v obchode na VIp a SLOT
			    return array
				    (	// Cena	 Dlzka		Tabulka
					    1 => array(120, 30, 'vip'),
						     array(90,  30, 'slot')
				    );
			    break;	
		    }
		    case 0: { 
			    return -50; // Cena kanidatury
			    break;	
		    }			
		    case 1: { 
			    return 3; // dostane za referala
			    break;	
		    }			
		    case 2: { 
			    return 5.0; // kurz zo zombie bodov na koruny
			    break;	
		    }			
		    case 3: { 
			    return 1.48; // kurz na koruny a zombie body
			    break;	
		    }			
		    case 4: { 
			    return 3.0; // kurz zo DR bodov na koruny
			    break;	
		    }			
		    case 5: { 
			    return 1.48; // kurz na koruny a DR body
			    break;	
		    }			
		    case 6: { // penazi za shoutbox
			    return round(0.1 + (rand(10) / 100), 1);
			    break;	
		    }			
		    case 7: {  // penazi za odpoved na fore....
			    return round(0.3 + (rand(10) / 100), 1);
			    break;	
		    }			
		    case 8: { // za komentar, za kazde jedno pismenko, pekne pouzijeme nahodu ;)
			    return round(0.2 + (rand(10) / 100), 1);
			    break;	
		    }
		    case 9: { // za hlasovanie v ankete ...
			    return 3;
			    break;	
		    }		
		    case 10: { // za hlasovanie hvizdicky
			    return 3;
			    break;	
		    }	
		    case 11: { // poslanie spravy v denniku
			    return -5;
			    break;	
		    }
            case 12: { // za hlasovanie kandidatuty
                return 3;
                break;    
            }			
		    default: { // ostatne ...
			    return 0; break; 
		    }
	    };
    }
    public static function String($user, $id, $txt) {
	    // Vypocitavame kolko ma dostat za prispevok ....
	    return self::KorunySet($user, floor($txt * self::Get($id)) );
    }
    public static function Kup($user, $id) {
	    /*
		    vracia :
		    minus hodnotu ked ubralo
		    plus hodnotu ked pridalo
		    FALSE  ak sa neico nepodarilo		
	    */
	    return self::KorunySet($id, self::Get($id));
    }
    public static function KorunySet($id, $kolko) {
        DB::Query("UPDATE `cstrike`.`fusion_users` SET `korun` = `korun` + '".$kolko."' WHERE user_id = '".$id."'");
        return $kolko;
    }
    public static function KorunyGet($id) {
        return DB::One("SELECT korun as pocet FROM `cstrike`.`fusion_cstrike` WHERE user_id = '".$id."'");
    }
    public static function Vynimka($user) {
	    return ($user == 3);
    }
    public static function Progress($x , $text) {	
	    return '
	    <div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
		    <table id="progressbar" width="'.$x.'%" class="ui-progressbar-value ui-widget-header ui-corner-left" border="0" cellspacing="0" cellpadding="0" >
			    <tr><td align="center" valign="middle" >'.$text.'</td></tr>
		    </table>
	    </div>';
    }
    public static function Unprogress($x, $text) {	
	    return '
	    <div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
		    <table id="progressbar_green" width="'.$x.'%" class="ui-corner-left ui-progressbar-value-green" border="0" cellspacing="0" cellpadding="0" style="background-position: 1px 50%;">
			    <tr><td align="center" valign="middle" >'.$text.'</td></tr>
		    </table>
	    </div>
	    <script type="text/javascript">
		    window.setInterval("progressbar()", 50);
	    </script>';
	    //  http://jqueryui.com/themeroller/images/?new=3ec92c&w=60&h=60&f=png&q=100&fltr[]=over|textures/08_diagonals_thick.png|0|0|75	
    }
}
