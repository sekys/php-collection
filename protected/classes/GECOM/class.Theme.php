<?
class Theme
{
    public static $right_padding = true;
    
    public static $right = true;
    public static $left = true;
    protected static $WEB_TIME;
    
    public static function Start() {
        self::$WEB_TIME = microtime(true);
    } 
    protected static function Unlogged() {        
    	echo '
	    <form method="post" action="">
    		<img src="', ROOT, 'web2/images/theme/informacie.gif" align="absmiddle" title="Registr&aacute;cia" alt="Registr&aacute;cia" /> Registr&aacute;ciou z&iacute;ska&scaron; v&yacute;hody!
	        <img src="', ROOT, 'web2/images/theme/userman.gif" align="absmiddle" alt="Meno" title="Meno" />
	        Nickname <input type="text" class="logarea" name="user_name" value="" />
	        <img src="', ROOT, 'web2/images/theme/passwordbr.gif" align="absmiddle" alt="Heslo" title="Heslo" /> Password <input name="user_pass" class="logarea" value="" type="password" />
	        <input type="checkbox" name="remember_me" value="y" title="Zapametat heslo" />
	        <input type="submit" name="login" value="" class="login" />
	    </form>
        <div class="buttons">
        	<a href="', ROOT, 'registrovat/"><img onmouseover="la_reg(this);" onmouseout="la_reg(this);" src="', ROOT, 'web2/images/theme/register.jpg" align="top" class="posun" alt="Registrovať" title="Registrovať" /></a>
        	<a href="', ROOT, 'stratene-heslo/"><img onmouseover="la_lost(this);" onmouseout="la_lost(this);" src="', ROOT, 'web2/images/theme/lostpw.jpg" align="top" class="posun" alt="Stratené heslo" title="Stratené heslo" /></a>
        	', FB::LoginButton(PAGE.ROOT.'registrovat/'),'
        </div>';
    }   
    protected static function TopBarItem($id, $data) {
        echo '
        <div class="item"><h3 class="img_', $id, '"><span>', $id, '</span></h3>
	        <div id="tabs_', $id, '">
	            <ul class="ui-tabs-nav">';                                             
	                foreach($data as $d) {
	                    echo '<li><a href="', ROOT, 'web2/ajax/header.php?widget=', $id, '&amp;item=', $d[0], '"><span> ', $d[1], ' </span></a></li>';
	                }
	                echo '
	            </ul>
	        </div>
        </div>';
    }  
    protected static function TopBar() {
        echo '<div class="topbar theme">';
        $data = NULL;
        $data[] = array('novinky', 'Novinky');
        $data[] = array('navody', 'N&aacute;vody');
        $data[] = array('download', 'Downloady');
        $data[] = array('forum', 'F&oacute;rum');
        $data[] = array('anketa', 'Anketa');                 
        self::TopBarItem('Portal', $data);
        echo '<div class="parse"></div>';
        
        $data = NULL;
        $data[] = array('vyzvy', 'V&yacute;zvy');
        $data[] = array('stavky', 'St&aacute;vky');
        $data[] = array('zapasy', 'Pr&aacute;ve sa hraje');
        $data[] = array('historia', 'Hist&oacute;ria');
        self::TopBarItem('Liga', $data);
          echo '<div class="parse"></div>';
        
        $data = NULL;
        $sql = DB::query("SELECT id, skratka FROM `servers` ORDER BY `id` ASC");    
        while($server = $sql->fetch_row()) { 
            $data[] = array('server&amp;id='.$server[0], $server[1]);
        }
        $data[] = array('ventrilo', 'Ventrilo');
        self::TopBarItem('Servery', $data); 
        echo '</div>';
    } 
    public static function Widget($name, $typ, $pole, $class='') {
        openside($name, $class);
        // Panel    
        echo '<div id="tabs_', $typ, '"><ul>';            
        foreach($pole as $a) {
            $url = ROOT.'web2/ajax/ajax.php?widget='.$typ.'&amp;item='.$a[1]; 
            echo '<li><a href="', $url, '"><span> ', $a[0], ' </span></a></li>';
        }
        echo '</ul></div>';
        closeside();
    }
    public static function Widget2($data) {
        $b=0;
        foreach($data as $a) {                
            $b++;
            echo '
            <div onclick="mShowMe(\'menu', $b, '\');"  class="widget2">
                <img id="togglemenu', $b, '" alt="" src="'.ROOT.'web2/images/tool/toggle_collapse.png" />
                ', $a[0], '
            </div>
            <div id="menu', $b, '" class="widget2-menu">';
            foreach($a[1] as $d) {
                echo '<a href="', ROOT, $d[0], '">', $d[1], '</a>';   
            }
            echo '</div>'; 
        }
    }
    public static function Footer() {
        echo '</tr>
        </table>
		<div class="theme-footer">
        	<div class="item a"><span>&copy; Design &amp; Code By Seky<br />Designers: er2^cko, MatusMMM</span></div>            
           	<div class="item b">
    			<span>&copy; 2007-2009 cs.gecom.sk v&scaron;etk&yacute; pr&aacute;va vyhraden&eacute;.Z&aacute;kaz kop&iacute;rovania textu, scriptov, n&aacute;padov a in&yacute; obsah str&aacute;nky.V&scaron;etko je pr&aacute;vne kryt&eacute; spolo&#269;nos&#357;ou Gecom.</span>
            </div>          
            <div class="item c"><span>', DB::Queries(0), ' mysql queries<br />', round(microtime(true) - self::$WEB_TIME, 2), ' loading time</span></div>
        </div>';
        
        
       /* echo '<div class="scroolsidebar">
        		<div class="inner" align="left">
        			<div class="body">
        			
        				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				        <tbody><tr>            
				            <td>
				                <a href="/webb/hrac/Seky/">
				                    <img width="96" vspace="5" hspace="5" height="96" border="0" title="Seky" alt="Seky" src="/webb/web2/images/postavicky/10/256.png"></a>    
				            </td>
				            <td valign="middle">
				                <p align="center"> <img src="/webb/images/ranks/logo.png" title="Manažment" alt="l"> <img src="/webb/images/ranks/10.png" title="D2 0nly Admin" alt="1"> <img src="/webb/images/ranks/wrench.gif" title="Developer Team" alt="w"><br>
				</p>        
				                <a href="http://www.cs.gecom.sk/psychostats/index.php?q=Seky" >P-Stats profil</a><br>
				                <a href="/webb/cup/vyzvy/">Nájsť zápas</a><br><a href="/webb/administration/index.php?aid=475bb62ca49dbe61" >Admin Menu</a><br>
				                <a href="/webb/upravit-profil/">Upraviť profil</a>
				            </td>
				        </tr>    
				    </tbody></table>
        			
        			
        			
        			
        			
                    </div>
                    <div class="name"><span>Seky</span></div>
                </div>   
       </div>';*/
    }
    public static function Header() {        
        $objekt = new Cache(iMEMBER ? 'theme_header_1' : 'theme_header_0');
        $objekt->bot = false;
        $objekt->Zlozka('theme');
        if($objekt->File()) {
            
        // Header
        echo '<div align="center">
        		<div class="header-logo theme">
        			<div class="header">
        				<h1 class="hidden">GeCom::Lekos</h1>
        			</div>
       			</div>';
        self::Menu();
        
        // Panel
       echo '
       <div id="userpannellogged"><div id="panel">';                    
	   if(!iMEMBER) {
	   		self::Unlogged();
	   }	   
       echo '</div>';
       	   
       	   /*echo '<div id="smoothmenu3" class="ddsmoothmenu3">
                    <ul>
                        <li>
                            <h2><a href="">Port&aacute;l</a></h2>                            
                        	<ul>
							    <li><a href="/admin-team.php" > <img src="<? echo ROOT; ?>images/menu/menu19.png" border="0" alt="Admin-team" align="top" /> Admin-team</a></li>
							</ul>
                        </li>
       				</ul>
       </div> ';*/
       
       
       
       echo '</div>';
        
        // Dalej ...
        echo '<div class="header_medzera"></div>';
        self::TopBar();    
        echo '<div class="header_medzera"></div>  
        <table align="center" cellpadding="0" cellspacing="0" width="980">
            <tr>';
        }
        $objekt->File();
    }
    public static function TypicalPage($name, $func) {
		require_once "subheader.php";
		require_once "side_left.php";
		self::Window($name, $func);	
		require_once "side_right.php";
		require_once "footer.php";
	}
	public static function InfoPage($name, $func) {
		if($name) { // zaroven zapina...
			require_once "subheader.php";
			require_once "side_left.php";
			Header::Title($name);
		} else {
			echo "<link rel='stylesheet' href='/themes/seky_web2/styles.css' type='text/css'>";
		}
			call_user_func($func);
		if($name) {
			require_once "side_right.php";
			require_once "footer.php";
		}
	}
	public static function Window($name, $func) {
		Debug::Oblast($name);
		opentable($name);
		Header::Title($name);
	 	call_user_func($func);
		closetable();
		Debug::Oblast($name);
    }
    protected static function Menu() {
        ?>
        <div class='header-menu theme'>
            <div class='left'></div>
            <div class='center'>                                    
                <div id="smoothmenu1" class="ddsmoothmenu">
                    <ul>
                        <li>
                            <h2><a href="<? echo ROOT; ?>">Port&aacute;l</a></h2>                            
                        	<ul>
							    <li><a href="/admin-team.php" > <img src="<? echo ROOT; ?>images/menu/menu19.png" border="0" alt="Admin-team" align="top" /> Admin-team</a></li>
							    <li>
							        <a href="<? echo ROOT; ?>pravidla/" > <img src="<? echo ROOT; ?>images/menu/menu16.png" alt="Pravidl&aacute;" border="0" align="top" /> Pravidl&aacute;</a>
							        <ul>    
							            <li><a href="<? echo ROOT; ?>pravidla/" > <img src="<? echo ROOT; ?>images/menu/menu16.png" border="0" align="top" alt="V&scaron;eobecn&eacute; pravidl&aacute;" /> V&scaron;eobecn&eacute; pravidl&aacute;</a></li>                                
							            <li><a href="<? echo ROOT; ?>pravidla/herne/"> <img src="<? echo ROOT; ?>images/menu/menu16.png" border="0" align="top" alt="Hern&eacute; pravidl&aacute;" /> Hern&eacute; pravidl&aacute;</a></li>
							            <li><a href="<? echo ROOT; ?>pravidla/ligove/" > <img src="<? echo ROOT; ?>images/menu/menu16.png" border="0" align="top" alt="Ligov&eacute; pravidl&aacute;" /> Ligov&eacute; pravidl&aacute;</a></li>
							        </ul>
							    </li>    
							    <li><a href="<? echo ROOT; ?>historia.doc" > <img src="<? echo ROOT; ?>images/menu/menu10.png" border="0" align="top" alt="Hist&oacute;ria" title="Hist&oacute;ria" /> Hist&oacute;ria</a></li>
								<li><a href="<? echo ROOT; ?>viewpage.php?page_id=53" > <img src="<? echo ROOT; ?>images/menu/menu14.png" border="0" align="top" alt="&#381;iados&#357; o unban" title="&#381;iados&#357; o unban" /> &#381;iados&#357; o unban</a></li>
								<li><a href="<? echo ROOT; ?>data.php?mode=connect" > <img src="<? echo ROOT; ?>images/menu/menu1.png" border="0" align="top" alt="Ako sa pripoji&#357; ?" title="Ako sa pripoji&#357; ?" /> Ako sa pripoji&#357; ?</a></li>
								<li><a href="<? echo ROOT; ?>viewpage.php?page_id=47" > <img src="<? echo ROOT; ?>images/menu/menu7.png" border="0" align="top" alt="N&aacute;vody" title="N&aacute;vody" /> N&aacute;vody</a></li>  
								<li><a href="<? echo ROOT; ?>viewpage.php?page_id=49" > <img src="<? echo ROOT; ?>images/menu/menu8.png" border="0" align="top" alt="Download" title="Download" /> Download</a> </li>
								<li><a href="<? echo ROOT; ?>search.php" > <img src="<? echo ROOT; ?>images/menu/menu17.png" border="0" align="top" alt="H&#318;ada&#357;" title="H&#318;ada&#357;" /> H&#318;ada&#357;</a></li>
							    <li><a href="<? echo ROOT; ?>viewpage.php?page_id=37" > <img src="<? echo ROOT; ?>images/menu/menu2.png" border="0" align="top" alt="Podporte n&aacute;s" title="Podporte n&aacute;s" /> Podporte n&aacute;s</a></li>
							</ul>
                        </li>                        
                        <li>
                            <h2><a href="<? echo ROOT; ?>cup/info/">SLS Cup</a></h2>
                            <ul>
                                <li><a href="<? echo ROOT; ?>cup/registrovat/" > <img src="<? echo ROOT; ?>images/menu/menu6.png" border="0" align="top" alt="Vytvori&#357; clan" title="Vytvori&#357; clan" /> Vytvori&#357; clan</a>  </li>
                                <li><a href="<? echo ROOT; ?>cup/nastavenia/" > <img src="<? echo ROOT; ?>images/menu/menu5.png" border="0" align="top" alt="Nastavenia" title="Nastavenia" /> Nastavenia</a> </li> 
                                <li><a href="<? echo ROOT; ?>cup/nastavenia-hraci/" > <img src="<? echo ROOT; ?>images/menu/menu19.png" border="0" align="top" alt="Spr&aacute;va hr&aacute;&#269;ov" title="Spr&aacute;va hr&aacute;&#269;ov" /> Spr&aacute;va hr&aacute;&#269;ov</a>  </li>
                                <li><a href="<? echo ROOT; ?>cup/vyzvy/" > <img src="<? echo ROOT; ?>images/menu/menu20.png" border="0" align="top" alt="V&yacute;zvy a ponuky" title="V&yacute;zvy a ponuky" /> V&yacute;zvy a ponuky</a></li> 
                                <li><a href="<? echo ROOT; ?>cup/nastavenia-vyzvy/" > <img src="<? echo ROOT; ?>images/menu/menu4.png" border="0" align="top" alt="Moje v&yacute;zvy" title="Moje v&yacute;zvy" /> Moje v&yacute;zvy</a></li>       
                                <li><a href="<? echo ROOT; ?>cup/volne-miesta/" > <img src="<? echo ROOT; ?>images/menu/menu23.png" border="0" align="top" alt="Vo&#318;n&eacute; miesta" title="Vo&#318;n&eacute; miesta" /> Vo&#318;n&eacute; miesta</a>  </li>
                                <li><a href="<? echo ROOT; ?>cup/zapasy/" > <img src="<? echo ROOT; ?>images/menu/menu21.png" border="0" align="top" alt="Najbli&#382;&scaron;ie z&aacute;pasy" title="Najbli&#382;&scaron;ie z&aacute;pasy" /> Najbli&#382;&scaron;ie z&aacute;pasy</a> </li> 
                                <li><a href="<? echo ROOT; ?>cup/historia/" > <img src="<? echo ROOT; ?>images/menu/cs_small.gif" height="16" width="16" border="0" align="top" alt="Hist&oacute;ria z&aacute;pasov" title="Hist&oacute;ria z&aacute;pasov" /> Hist&oacute;ria z&aacute;pasov </a> </li> 
                                <li><a href="<? echo ROOT; ?>cup/rank/" > <img src="<? echo ROOT; ?>images/menu/menu10.png" border="0" align="top" alt="Score Tabu&#318;ka" title="Score Tabu&#318;ka" /> Score Tabu&#318;ka</a></li>
                                <li><a href="<? echo ROOT; ?>cup/info/" > <img src="<? echo ROOT; ?>images/menu/menu18.png" border="0" align="top" alt="Informacie" title="Informacie" /> Inform&aacute;cie</a></li>                                
                            </ul>
                        </li>                       
                        <li><h2><a href="<? echo ROOT; ?>ban/">Ban list</a></h2></li>
                        <li><h2><a href="<? echo ROOT; ?>hlstats/">Hl-Stats</a></h2></li>
                        <li><h2><a href="<? echo ROOT; ?>forum/">F&oacute;rum</a></h2></li>
                        <li>
                        	<h2><a href="<? echo ROOT; ?>servery/">Servery</a></h2> 
                        	<ul>
	                            <li><a href="<? echo ROOT; ?>zombie-banka/rank/" > <img src="<? echo ROOT; ?>web2/images/tool/zombie_body.png" border="0" align="top" alt="Zombie-Banka Rank" /> Zombie Rank</a></li> 
	                            <li><a href="<? echo ROOT; ?>zombie-banka/" > <img src="<? echo ROOT; ?>web2/images/tool/zombie_body.png" border="0" align="top" alt="Zombie-Banka &Uacute;&#269;et" /> Zombie &Uacute;&#269;et</a></li> 
	                            <li><a href="<? echo ROOT; ?>deathrun-banka/rank/" > <img src="<? echo ROOT; ?>web2/images/tool/dr_body.png" border="0" align="top" alt="DeathRun Rank" /> DeathRun Rank</a></li> 
	                            <li><a href="<? echo ROOT; ?>deathrun-banka/" > <img src="<? echo ROOT; ?>web2/images/tool/dr_body.png" border="0" align="top" alt="DeathRun &Uacute;&#269;et" /> DeathRun &Uacute;&#269;et</a></li> 
		                        <li><a href="<? echo ROOT; ?>vip/" > <img src="<? echo ROOT; ?>images/menu/menu10.png" border="0" align="top" alt="VIP &uacute;&#269;ty" title="VIP &uacute;&#269;ty" /> VIP &uacute;&#269;et</a> </li>
		                        <li><a href="<? echo ROOT; ?>vip-sloty/" > <img src="<? echo ROOT; ?>images/menu/menu15.png" border="0" align="top" alt="U&#382;&iacute;vatelia" title="U&#382;&iacute;vatelia" /> VIP u&#382;&iacute;vatelia</a> </li>
		                    </ul>
                        </li>
                        <li>
                            <h2><a href="<? echo ROOT; ?>sponzori/">Minet</a></h2>
                            <ul>
                                <li><a href="http://www.gecom.sk/"> <img src="<? echo ROOT; ?>images/menu/logo.png" border="0" align="top" alt="GeCom s. r. o." title="GeCom s. r. o." /> GeCom s. r. o.</a></li>
                                <li><a href="http://www.gecom.sk/meteo/"> <img src="<? echo ROOT; ?>images/menu/logo.png" border="0" align="top" alt="Po&#269;asie Michalovce" title="Po&#269;asie Michalovce" /> Po&#269;asie Michalovce</a></li>
                                <li><a href="http://portal.gecom.sk/login.php"> <img src="<? echo ROOT; ?>images/menu/logo.png" border="0" align="top" alt="GeCom port&aacute;l" title="GeCom port&aacute;l" /> GeCom port&aacute;l</a></li>
                                <li><a href="http://eshop.gecom.sk/"> <img src="<? echo ROOT; ?>images/menu/logo.png" border="0" align="top" alt="eShop GeCom" title="eShop GeCom" /> eShop GeCom</a></li>                            
                            </ul>
                        </li>
                    </ul>
                </div>     
                <form class="searchbox" name='searchform' method='post' action='<? echo ROOT; ?>hladaj/'>  
                   <div class="stextbox"><input type='text' name='stext' value='' class='stextbox2' /> </div>
                    <input type='submit' name='search' value='' class='sbutton' />
                    <input type='hidden' name='stype' value='m' />
                </form>
  		</div>
		<div class='right'></div>
  	</div>
     <?
    }
}