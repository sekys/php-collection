$.ajaxSetup({cache: true}); 

/* Po nacitani */
$(document).ready(
    function(){    
		/* Css - binduje aj ajaxi :) */
		$('.bingo').live({
			mouseout: function() { $(this).removeClass("bingo-over"); },
			mouseover: function () { $(this).addClass("bingo-over"); }
		});
		Tips();

        /* Tabulky  */
        web2_Tabs('Portal', 280, 140);
        web2_Tabs('Liga', 295, 140);
        web2_Tabs('Servery', 320, 140);
        web2_Tabs('users', 190, 160);
        web2_Tabs('admin', 190, 160);
        web2_Tabs('stats', 190, 160);
        web2_Tabs('top', 190, 160);
        web2_Tabs('tip', 190, 120);

        /* Ostatne */
        galeria( random_to(10) );
        ajax("#galeria", AJAX+"galeria.php?widget=random");
        ddsmoothmenu.init({ 
            mainmenuid: "smoothmenu1",
            orientation: 'h',
            classname: 'ddsmoothmenu',
            contentsource: "markup" //["smoothmenu1", AJAX+"smoothmenu.htm"] // 
        }); 
        ddsmoothmenu.init({ 
            mainmenuid: "smoothmenu2",
            orientation: 'v',
            classname: 'ddsmoothmenu2',
            contentsource: "markup" //["smoothmenu1", AJAX+"smoothmenu.htm"] // 
        }); 
        ddsmoothmenu.init({ 
            mainmenuid: "smoothmenu3",
            orientation: 'v',
            classname: 'ddsmoothmenu3',
            contentsource: "markup" //["smoothmenu1", AJAX+"smoothmenu.htm"] // 
        });
        
        $("#newsaccordion").accordion({ 
        	header: 'li', 
        	fillSpace: false
        });
        CSSMakeScrollFixed('#userpannellogged', 150);
    }
);

/* Este predtym cachujeme obrazky ... */
precache_images(
	IMAGES+"tool/item_bullet.gif",
    IMAGES+"tool/toggle.png",
    IMAGES+"theme/logout2.gif",
    /*IMAGES+"tool/green-hilite.png",
    IMAGES+"tool/orange-hilite.png",
    IMAGES+"tool/green.png",
    IMAGES+"tool/orange.png",
    IMAGES+"tool/default.png",
    IMAGES+"tool/default-hilite.png",*/
    hlas_img+"4.gif"
);

function Deconstructor() {
    if(user_id == 0) unlogged();
    else logged();
}
function logged() { 
    $(document).ready( function(){ 
        /* User panel */
        ajaxpre("#userpannellogged #panel", 
        	AJAX+"userlogged.php?akcia=headerpanel", 
        	950, 33);
        ajax(".usersidepanel-left", 
        	AJAX+"userlogged.php?akcia=usersidepanel-left");
        ajaxpre(".usersidepanel-right", 
        	AJAX+"userlogged.php?akcia=usersidepanel-right", 
        	190, 100);
    });
}
function unlogged() {
    precache_images(
        IMAGES+"tema/login_hov.jpg",
        IMAGES+"tema/regihov.jpg",
        IMAGES+"tema/lostpw_hov.jpg"
    );
}