<?php
require_once "maincore.php";
Web2::Resource();

function welcome_action($error) {
    global $userdata; 
    if (isset($_REQUEST["logout"]) && $_REQUEST["logout"] == "yes") {
        User::LogOut();
        echo "<b>", 'Odhlasovanie ', $userdata["user_name"], "</b><br><br>\n";
    	unset($userdata);
     } else {
        if ($error == 1) {
            echo "<b>V&aacute;&scaron; &uacute;&#269;et bol zablokovan&yacute;.</b><br><br>\n";
        } elseif ($error == 2) {
            echo "<b>Tento &uacute;&#269;et e&scaron;te nebol aktivovan&yacute;.</b><br><br>\n";
        } elseif ($error == 3) {
            echo "<b>Nespr&aacute;vne meno alebo heslo.</b><br><br>\n";
        } else {
            if(!User::Logged()) {
                echo "<b>Nespr&aacute;vne meno alebo heslo.</b><br><br>\n";
            } else {
                //$result = DB::Query("DELETE FROM ".$db_prefix."online WHERE online_user="0" AND online_ip="".USER_IP.""");
                echo "<b>Prihlasovanie ". User::$m->user_name ."</b><br><br>\n
                Po&#269;kajte pros&iacute;m, prihlasovanie m&ocirc;&#382;e chv&iacute;&#318;u trva&#357;...<br><br>";
            }          
        }
    }  
}
echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Welcome</title>        
        <meta name="copyright" content="2008-2011, Seky" />
        <meta name="author" content="Seky and er2^cko?!" />
        <meta name="robots" content="noindex, nofollow" />    
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">      
        <link rel="shortcut icon" href="', ROOT, 'icon.ico" />
        <meta http-equiv="refresh" content="2; url=', ROOT, '">', 
        Resource::Out(),
    '</head>';
flush();

echo '
    <body class="tbl2 backimg">
        <table width="100%" height="100%">
            <tr>
                <td>
                    <table align="center" cellpadding="0" cellspacing="1" width="80%">
                        <tr>
                            <td>
                                <center><br>
                                <img src="http://www.cs.gecom.sk/themes/seky_web2/img/logo.png" alt="', $settings["sitename"], '"><br><br>';
                                 $error = isset($_GET['error']) ? $_GET['error'] : '';
                                 welcome_action($error);
                                 echo'                                
                                 [ <a href="', ROOT, '">Kliknite sem ak sa str&aacute;nka nezobraz&iacute; do 10 sek&uacute;nd.</a> ]
                                 <br><br>
                                </center>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>'; 