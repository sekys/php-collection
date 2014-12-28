<?php
class Web2Action
{
    public static function Specific() {
        if(!isset($_GET['action'])) return;
        switch($_GET['action']) {
            case 'unlogin' : { self::Unlogged(); } 
            default: {
            }
        }      
    }
    protected static function Unlogged() {
        Mess::Alert('G/L Port&aacute;l ', '
        <div align="center">
            <p>Vstupuje&scaron; do z&oacute;ny kde je potrebn&eacute; prihl&aacute;senie !</p>
            <p><a href="#" onclick="login(this);">Prihl&aacute;si&#357;</a></p>
            <p><a href="'.ROOT.'registrovat/">Zaregistrova&#357;</a></p>
        </div>');
    }
    /*protected static function CheckLogin() {
        global $userdata, $settings;
        if(isset($_COOKIE['fusion_user'])) {   
            $cookie_vars = explode(".", $_COOKIE['fusion_user']);
            $cookie_1 = is_numeric($cookie_vars['0']) ? $cookie_vars['0'] : "0";
            $cookie_2 = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
            $result = DB::Query("SELECT * FROM ".DB_PREFIX."users WHERE user_id='$cookie_1' AND user_password='$cookie_2'");
            unset($cookie_vars,$cookie_1,$cookie_2);
            if ($result->num_rows != 0) {
                $userdata = $result->fetch_assoc();
                if ($userdata['user_status'] == 0) {
                    if ($userdata['user_offset'] <> 0) {
                        $settings['timeoffset'] = $settings['timeoffset'] + $userdata['user_offset'];
                    }
                    if (empty($_COOKIE['fusion_lastvisit'])) {
                        setcookie("fusion_lastvisit", $userdata['user_lastvisit'], time() + 3600, "/", "", "0");
                    } else {
                    }
                } else {
                    header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                    setcookie("fusion_user", "", time() - 7200, "/", "", "0");
                    setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
                    Engine::Presmeruj(PAGE);
                }
            } else {
                header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                setcookie("fusion_user", "", time() - 7200, "/", "", "0");
                setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
            }
        } else {
            $userdata = "";    
            $userdata['user_level'] = 0;
            $userdata['user_id'] = 0; 
            $userdata['user_rights'] = ""; 
            $userdata['user_groups'] = "";
        }
    } */
}