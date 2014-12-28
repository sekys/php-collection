<?php

/*
Logy
        Mame    Typ        Opis                                Poznamky
    Kategoria :     
        -1  Liga 
            xy    1    Clan WBS(tag) prijal Seky                    CLANID KOHO zaroven aj KTO prijal pozvanku
            xy    2    Clan WBS(tag) vykopol Seky                    (+kto konkretne)
            xy    3    Clan WBS bol vymazany.                    ID a MENO
            xy    4    Clan WBS sa prihlasil do ligy.                    ID a KTO zalozil .....takto kontorlujeme kolko hrac vytvaral clanov        
            xy    5    Clan WBS zrusil vyzvu s CLL                    ID a KTO a KOMU je 1/0 ci je aktivne    
            x    6    Seky upravil zapas 29, Dovod : 
            x    7    Seky pridal WBS clanu '-234' bodov. Dovod :  Upraveny zapas 49 - je numeric -1 je pridal 1 a 1 odobral
            xy    9    Seky odisiel z clanu WBS.
            xy    24     Clanu WBS boli anulovane body za
                    1 - zmazovanie zapasov
            xy    25    Seky upravil nastavenia WBS clanu                KTOUPRAVYL CLANID
            xy    26    Seky poslal  hromadnu spravu vo WBS clane.
            xy    27     Seky ziadal pozvanku od WBS.    
            xy    28    WBS clan prijal zapas od WBS.
            xy    34    Wbs poslal vyzvu WBS.
            xy    29    WBS clan odmietol sukromnu vyzvu od WBS.
            xy    30    Seky si stiahol demo zo zapasu 19.08 16:00.                ID zapasu
            xy    31    Seky si pozrel profil WBS clanu                        hraca a id clanu        bez time
                    - Profil WBS clanu si pozrelo 315 &#318;ud&iacute;. Najv&auml;&#269;&scaron;&iacute; z&aacute;ujem mal Seky.        
            xy    32    WBS(Seky) poslal pozvanku Sekymu                 CLAN HRAC KTOPOSLAL
                    - zbytocna duplicita pozvanky a logy su tie iste :(
            xy    33    Clan WBS(Seky) nastavil novu hodnost Sekymu.        CLANID KOMU KTO            
            Nedavat na kopu ale porozhadzovat v profile clanu. npriklad na konci widgetoch
        
        0  Vseobecne     // Caste vstupy
            x    0    Klikol na stranku                KTO registrovany alebo ne         bez time                
                    Seky sa odhlasil.                    
            xy    1    Seky pridal spravu do dennika.
                2    Seky kandidoval na admina (PUBLIC) .     web_id a server
                    Seky si pozrel Seky-ho profil.                    
            xy    5    Si pridal seky-ho do priatelov
            
                6     Seky adminovy "Seky"  odstranil prava , dovod :
                7     Seky pridal noveho admina "Seky", dovod : 
                8   Seky zmazal Sekyho kandidaturu.
                9    Seky prijal Sekyho za admina.
                10    Seky nastavil Sekymu prava na 1 server.
            xy    11    Seky vymazal Mitwoca z priatelov.    
            xy    22    Zmenil nastavenia serverov    
                    Zarobyl 3.0
                    
            nieje log    Stratil 100.0 
                        - mozme robit grafy potom 
                        - tu sa pripocitavaju vo vybere aj kupa VIP, Slotu a pod.
                    Sa prihlasil na web                            
                    Admin er2cko zablokoval seky-mu ucet na webe         
                    Prekonal hranicu 100 prispevkov
                    
        1  Banka    
            xy    12     Zm prihlasil banky    
            xy    13    ZM vymazal ucet
            xy    14    ZM zmenil udaje 
            xy    15     ZM Seky poslal 40 bodov Sekymu.    ...posledne je text
            xy    16     DR poslal DR body
            xy    17     DR prihlasil
            xy    18     DR vymazal ucet
            xy    19     DR zmenil udaje
            xy    20     ZM prihlasil do uctu
            xy    21     DR prihlasil do uctu
            
        2  Obchod 
                1    Seky daroval 400.    ||     id kolko clanid
            xy    6    Seky(user-name) si pred&#314;&#382;il SLOT
            y    7    Seky(user-name) prekonal hranicu 100 500 1000 10000 bodov
            xy    10    Seky si zakupil
                    - 1 VIP
                    - 2 Slot            
            x    23     Hrac stavil a vyhral 450SVK na 9 zapasoch.        
                    - koncovy datum je upraveny
                    stavil na WBS clan
                    
            x    2    vyhral vip
            x    3    vyhral slot
            x    4    vyhral 100 sk
                    
                    podal ziadost na admina
                    bol prijaty za admina na d2                    - navrhnut globalne premenne pre servery
                                                                            
    Legenda :    
        x - Je spraveny vstup
        y - je spraveny vystup
        
    add_log($kat, $typ,             $kto, $komu=false, $int=false,         $co=false);    
    
    Class:
        logs_co - zelena 
        logs_kto -cervena
        logs_komu -modra
*/

class WebLog 
{
    public static function Item($txt, $class='') {
        $txt = 'SELECT * FROM `cstrike`.`web2_logs` WHERE '.$txt;       
        $sql = DB::Query($txt);    
        while($udaje = $sql->fetch_assoc()) {            
            echo'
            <tr>
                <td class="bingo2', $class, '" ', STYLE_HOVER, ' id="widget_logy">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">';
                        self::MiniItem($udaje);
                    echo '       
                    </table>            
                </td>
            </tr>';
        }
    }
    public static function MiniItem($udaje) {
        echo'
        <tr>
            <td width="17" align="right" class="info_gray"> '.date("H:m", $udaje['kedy']).' </td>
            <td width="5">&nbsp;</td>
            <td> ', WebLog::Parse($udaje), ' </td> 
        </tr>';
    }
    public static function add(    $kat, 
                        $typ, 
                        $kto, 
                        $komu=false, 
                        $int=false, 
                        $co=false, 
                        $time=true
                    ) {
        $kto = ($kto===false) ? "NULL" : "'".$kto."'";
        $komu = ($komu===false) ? "NULL" : "'".$komu."'";
        $int = ($int===false) ? "NULL" : "'".$int."'";
        $co = ($co===false) ? "NULL" : "'".DB::Vstup($co)."'";
        $time = ($time===false) ? "NULL" : "'".time()."'";
        return DB::Query("INSERT INTO `cstrike`.`web2_logs` (`kat`, `typ`, `kto`, `co`, `komu`, `int`, `kedy`) VALUES ('".$kat."', '".$typ."', ".$kto.", ".$co.", ".$komu.", ".$int.", ".$time.")");
    }    
    public static function Parse($udaje, $pohlad = 0)    // iny pohlad - rozne texty zaznamu
    {    
        /*    Debug
        echo $udaje['kat'].'-'.$udaje['typ'];    
        */
        switch($udaje['kat'])
        {
            case -1: 
            {
                switch($udaje['typ'])
                {
                    case 1: 
                        {
                            return 'Clan <a class="logs_kto" '.self::clan($udaje['kto']).' prijal <a class="logs_komu" '.self::user($udaje['komu']).'.';
                            break;
                        }        
                    case 2: 
                        {
                            return 'Clan <a class="logs_kto" '.self::clan($udaje['kto']).' vykopol <a class="logs_komu" '.self::user($udaje['komu']).'.';
                            break;
                        }        
                    // Ak budeme zmazat zaznamy o clanoch z DB tak to dat do CO sltpca
                    case 3: 
                        {
                            return 'Clan <a class="logs_co" '.self::clan($udaje['co']).' bol vymazan&yacute;.';
                            break;
                        }        
                    case 4: 
                        {
                            return 'Clan <a class="logs_kto" '.self::clan($udaje['kto']).' sa prihl&aacute;sil do ligy.';
                            break;
                        }                
                    case 5: 
                        {
                            return 'Clan <a class="logs_kto" '.self::clan($udaje['kto']).' zru&scaron;il v&yacute;zvu s <a class="logs_komu" '.self::clan($udaje['kto']).'.';
                            break;
                        }
                    case 9: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' odi&scaron;iel z clanu <a class="logs_komu" '.self::clan($udaje['komu']).'.';
                            break;
                        }        
                    case 28: 
                        {
                            return '<a class="logs_kto" '.self::clan($udaje['kto']).' clan prijal z&aacute;pas od <a class="logs_komu" '.self::clan($udaje['komu']).'.';
                            break;
                        }                
                    case 34: 
                        {
                            return '<a class="logs_kto" '.self::clan($udaje['kto']).' poslal v&yacute;zvu <a class="logs_komu" '.self::clan($udaje['komu']).'.';
                            break;
                        }                
                    case 29: 
                        {
                            return '<a class="logs_kto" '.self::clan($udaje['kto']).' clan odmietol s&uacute;kromnu v&yacute;zvu od <a class="logs_komu" '.self::clan($udaje['komu']).'.';
                            break;
                        }            
                    case 25: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' upravil nastavenia WBS clanu <a class="logs_komu" '.self::clan($udaje['komu']).'.';
                            break;
                        }                
                    case 30: 
                        {
                            @$time = mysql_query2("SELECT datum FROM `phpbanlist`.`acp_zapas` WHERE id='".$udaje['komu']."'");
                            $time = mysql_fetch_row($time);
                            $time = date("n.j H:m", $time[0]);
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' si stiahol demo zo z&aacute;pasu <span class="logs_co">'.$time.'</span>.';
                            break;
                        }                
                    case 26: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' poslal  hromadn&uacute; spr&aacute;vu vo <a class="logs_komu" '.self::clan($udaje['komu']).' clane.';
                            break;
                        }            
                    case 31: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' si pozrel profil <a class="logs_komu" '.self::clan($udaje['komu']).' clanu.';
                            break;
                        }                    
                    case 32: 
                        {
                            if($pohlad)
                                return '<a class="logs_kto" '.self::clan($udaje['kto']).' ti poslal pozv&aacute;nku.';
                            else
                                return '<a class="logs_kto" '.self::user($udaje['komu']).' poslal pozv&aacute;nku <a class="logs_komu" '.self::user($udaje['int']).'.';
                            break;
                        }    
                    case 33: 
                        {
                            if($pohlad)
                                return '<a class="logs_kto" '.self::clan($udaje['kto']).' ti poslal pozv&aacute;nku.';
                            else
                                return '<a class="logs_kto" '.self::user($udaje['komu']).' nastavil nov&uacute; hodnos&#357; <a class="logs_komu" '.self::user($udaje['int']).'.';
                            break;
                        }
                    case 24: 
                        {
                            $vysledok = 'Clanu <a class="logs_kto" '.self::clan($udaje['kto']).' boli anulovan&eacute; body za: ';
                            $vysledok .= 'Zmazovanie z&aacute;pasov';
                            return $vysledok;
                            break;
                        }            
                    default:
                        {
                            return 'Nie&#269;o sa udialo '.date("Y-m-d", $udaje['kedy']).' ale &#269;o to neviem :)';
                            break;                
                        }                    
                };
                break;            
            }
            case 0:    
            {
                switch($udaje['typ'])
                {
                    case 0: 
                        {
                            if($udaje['kto'])
                                return '<a class="logs_kto" '.self::user($udaje['kto']).' bol na str&aacute;nke.';
                            else    
                                return 'Neprihl&aacute;sen&yacute; bol na str&aacute;nke.';
                            break;
                        }        
                    case 1: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' pridal spr&aacute;vu do denn&iacute;ka.';
                            break;
                        }    
                    case 11: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' vyhodil <a class="logs_komu" '.self::user($udaje['komu']).' z priate&#318;ov.';
                            break;
                        }            
                    case 5: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' si pridal <a class="logs_komu" '.self::user($udaje['komu']).' do priate&#318;ov';
                            break;
                        }                        
                    case 22: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' zmenil nastavenie serverov.';
                            break;
                        }                                        
                    default:
                        {
                            return 'Nie&#269;o sa udialo '.date("Y-m-d", $udaje['kedy']).' ale &#269;o to neviem :)';
                            break;                
                        }
                };
                break;
            }
            case 1: 
            {
                switch($udaje['typ'])
                {    
                    case 12: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' sa prihl&aacute;sil do <span class="logs_co">Zombie banky</span>.';
                            break;
                        }
                    case 13: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' vymazal zombie &uacute;&#269;et <span class="logs_co">'.$udaje['co'].'</span>.';
                            break;
                        }    
                    case 14: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' zmenil &uacute;daje v <span class="logs_co">Zombie banke</span>.';
                            break;
                        }    
                    case 15: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' poslal <span class="logs_co">'.$udaje['komu'].'ZM</span> bodov <span class="logs_komu">'.mysql_vystup($udaje['co']).'</span>.';
                            break;
                        }        
                    case 16: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' poslal <span class="logs_co">'.$udaje['komu'].'ZM</span> bodov <span class="logs_komu">'.mysql_vystup($udaje['co']).'</span>.';
                            break;
                        }
                    case 17: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' sa prihl&aacute;sil do <span class="logs_co">Deathrun banky</span>.';
                            break;
                        }
                    case 18: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' vymazal deathrun &uacute;&#269;et <span class="logs_co">'.mysql_vystup($udaje['co']).'</span>.';
                            break;
                        }    
                    case 19: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' zmenil &uacute;daje v <span class="logs_co">Deathrun banke</span>.';
                            break;
                        }        
                    case 20: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' pou&#382;il zombie banku.';
                            break;
                        }        
                    case 21: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' pou&#382;il deathrun banku.';
                            break;
                        }
                    default:
                        {
                            return 'Nie&#269;o sa udialo '.date("Y-m-d", $udaje['kedy']).' ale &#269;o to neviem :)';
                            break;                
                        }
                };
                break;        
            }        
            case 2: 
            {
                switch($udaje['typ'])
                {
                    case 6: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' si pred&#314;&#382;il <a class="logs_co" href="'.ROOT.'obchod/">'.$udaje['co'].'</a>.';
                            break;
                        }
                    case 7: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' prekonal hranicu <span class="logs_co">'.$udaje['komu'].'</span> bodov.';
                            break;
                        }    
                    case 10: 
                        {
                            return '<a class="logs_kto" '.self::user($udaje['kto']).' si zak&uacute;pil <a class="logs_co" href="'.ROOT.'shop/">'.$udaje['co'].'</a>.';
                            break;
                        }        
                    default:
                        {
                            return 'Nie&#269;o sa udialo '.date("Y-m-d", $udaje['kedy']).' ale &#269;o to neviem :)';
                            break;                
                        }    
                };
                break;            
            }
            default:
            {
                return 'Nie&#269;o sa udialo '.date("Y-m-d", $udaje['kedy']).' ale &#269;o to neviem :)';
                break;                
            }
        };    
    }        
    protected static function user($id) {
        $data = DB::One("SELECT user_name FROM `cstrike`.`fusion_users` WHERE user_id='".$id."'");
        return 'href="'.ROOT.'hrac/'.uri_out($data).'/">'.$data.'</a>';
    }
    protected static function clan($id) {
        $data = DB::One("SELECT tag FROM `phpbanlist`.`acp_clans` WHERE id='".$id."'");
        return 'href="'.ROOT.'cup/clan/'.$id.'/">'.DB::Vystup($data).'</a>';
    }
}    
?>