<?

//web2_buble("Pozri novy PATCH 42.");

class Web2
{
    public static function Buble($sprava) {
        Resource::Js('infoBar');
        Engine::Js("$(function() { showInfoBar('".$sprava."'); });");
    }
	public static function BuildHeader() {
		global $settings;
  		// Nedat to do temy ?
		echo '
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <meta name="copyright" content="2008-2011, Seky" />
	    <meta name="author" content="Seky & er2^cko?!" />
	    <meta name="robots" content="index, follow" />         
		<meta name="description" content="', $settings['description'], '" />
		<meta name="keywords" content="', $settings['keywords'], '" />
	    <link rel="shortcut icon" href="', ROOT, 'images/icon.ico" />';
	}		
	public static function Resource() {
		Resource::Js( 
	        'jquery-1.4.4.min', 
	        'jquery-ui-1.8.9.custom.min',
	        'gpe',
	        'engine',
	        'function',
	        'social',
	        'comment',
	        'friend',
	        'ajax',
	        'window',
	        'ddsmoothmenu',
	        'forms',
	        'main'
	    );
	    Resource::Css(
    		'theme',
    		'theme_misc',
	        'styles',
	        'ui.tabs',
	        'ddsmoothmenu'
	    );
		
		// + FB
		if(!iMEMBER) Header::Add(FB::JSFile());	
	}	
	public static function Constructor() 
	{
		// Nieje trieda ale aj tak xD
		global $settings;
		Header::Title($settings['sitename']);
		self::Resource();
		Engine::SetCallback('Web2::BuildHeader');
		Kategorie::Load();
	}
	public static function Deconstructor() {
		Debug::Oblast('Web2::Deconstructor');
	    
	    // Preposielame udaje
	    global $userdata;
	    Header::Js("
	        user_id = ".( isset($userdata['user_id']) ? $userdata['user_id'] : 0 ).";
	        admin = ".( (iADMIN && ($userdata['user_rights'] != "" || $userdata['user_rights'] != "C")) ? "true" : "false" ).";
	        Deconstructor();
	    ", 1);
	        
		//Stavky::Start();
		
		// Pomocne oblasti potom na javascript ....
		echo '<div id="dialog_error"> </div>
			<div id="engine"> </div>
			<div id="popup_okno"> </div>
			<div id="popup_user_cache" > </div>';
		
	    // Vypustame posledne premenne
		Debug::Oblast('Web2::Deconstructor');
	}  
	public static function Posta($id, $od, $sprava, $subject = 'Automatick&aacute; spr&aacute;va') {
		DB::Query("INSERT INTO `cstrike`.`fusion_messages` 
		(message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) 
		VALUES 
		('".DB::Vstup($id)."', ".$od.",'".DB::Vstup($subject)."','".DB::Vstup($sprava)."','y','0','".time()."','0')");
	}  
}