<?php
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }
    
echo '<td width="200" valign="top" class="side-border-right" ',
    (Theme::$right_padding ? "style='padding-left: 0px;'" : "" ).'>';

// Tipy
r(new FBDownloadFriendsTip)->Render(); 
         
$objekt = new Cache(-1, 'side_right_'.(iMEMBER ? '1' : '0'));
$objekt->bot = false;
$objekt->Zlozka('theme');

if($objekt->File()) 
{
    Debug::Oblast('SIDERIGHT');
    
    if(!iMEMBER) {
        openside('Pripoj sa !');        
        echo '
        <div align="center">
            <a href="http://www.facebook.com/pages/GeCom/180964078582984?v=wall">
                <img hspace="10" border="0" alt="Facebook" title="Pripoj sa k nam aj na facebooku!" src="', ROOT, 'web2/images/theme/fb.png" />
            </a>            
            <a href="http://steamcommunity.com/groups/geecoom/">
                <img hspace="10" border="0" alt="Steam" title="Pripoj sa k nam aj na stem!" src="', ROOT, 'web2/images/theme/steam.png" />
            </a>
        </div>';  
        closeside();
        
        // Sponzory
	    openside('Hlavn&yacute; sponzor');
	    echo '        
	    <a href="', ROOT, 'sponzori/">
	        <img border="0" width="190" height="45" alt="GeCom s.r.o." title="GeCom s.r.o." src="http://www.gecom.sk/images/gecom_2.gif" />
	    </a>
	    <br /><br />
	    <div align="center">
	        <a class="bingo2" ', STYLE_HOVER, ' href="http://www.gecom.sk">Optick&aacute; sie&#357; Michalovce.</a>
	        <a class="bingo2" ', STYLE_HOVER, ' href="http://www.gecom.sk">eShop, Internet, Webhosting</a>
	        <a class="bingo2" ', STYLE_HOVER, ' href="http://www.gecom.sk">Fiber internet</a>
	        <a class="bingo2" ', STYLE_HOVER, ' href="http://www.gecom.sk/?kat=profil">Profil spolo&#269;nosti</a>
	    </div>
	    <br />';
	    closeside();

	    //Galeria
	    openside('Gal&eacute;ria');
	    echo'<div align="center" id="galeria"></div>';
	    closeside();

    } else { 
        echo '<div class="usersidepanel-right"> </div>'; 
    }
      
    // Partnery
	openside('Reklamn&iacute; partneri');
	echo '
	<br />
	<p align="center"><a href="javascript:Contact(\'partner\');"><strong>Chce&scaron; by&#357; partner  ? </strong></a></p>
	<br />';
    closeside();
         
    // Stats 
    Theme::Widget('&Scaron;tatistiky', 'stats', 
        array( 
            array('Server', 'servery'),
            array('Liga', 'liga'),
            array('Web', 'webu')
        )
    );

    // Users
    Theme::Widget('Užívatelia', 'users', 
        array( 
            array('Online', 'online'),
            array('Nov&aacute;&#269;ikovia', 'novacikovia')
        )
    );

    // http://developers.facebook.com/docs/reference/plugins/like-box/
	if(!iMEMBER) {
	echo '<iframe 
		src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FGeCom%2F180964078582984&amp;width=190&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=300" 
		scrolling="no" frameborder="0" class="fblikebox" allowTransparency="true"></iframe>';
	}	
	
	// Reklama
	echo '<div align="center">
		<a href="javascript:Contact(\'partner\');">
			<img height="190" width="190" border="0" src="', RIMAGE, 'theme/adv190x190.jpg">
		</a>
	</div>'; 		
		
    echo '</td>';
    Debug::Oblast('SIDERIGHT'); 
}
$objekt->File(); 
unset($objekt);