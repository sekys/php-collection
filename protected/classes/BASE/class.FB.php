<?

class FB 
{	
	// JSON decode je velmi pomaly !
	const APPID = '180243745338816';
	const SECRET = '934c0957431561efbc8431bc86efc01f';
	public static $USER = array();
	
	public static function JSFile() {
		return '<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>';
	}
	public static function GetCookie() {
   		if(!isset($_COOKIE['fbs_' . self::APPID])) return false;
   	 	parse_str(trim($_COOKIE['fbs_' . self::APPID], '\\"'), self::$USER);
    	$payload = '';
    	foreach (self::$USER as $key => $value) {
        	if ($key != 'sig') $payload .= $key . '=' . $value;
   	 	}
    	if( md5($payload.self::SECRET) != self::$USER['sig']) {
    		self::$USER = array();
    		return false;
		}	
    	return true;
	}
	protected static function JS($txt) {
		// Echo alebo header volat,..
		echo '<script type="text/javascript">';
		echo $txt;
		echo '</script>';
	}
	public static function LikeButton() {
		// http://developers.facebook.com/docs/reference/plugins/like
	}
	public static function LoginButton($regurl) {
		echo '
		<fb:login-button perms="publish_stream" autologoutlink="true" registration-url="', $regurl, '"></fb:login-button>
        <div id="fb-root"></div>';
        self::JS('
            FB.init({ appId: \''.self::APPID.'\', status: true, cookie: true, xfbml: true });
            FB.Event.subscribe(\'auth.login\', function(response) {
                window.location.reload();
            });
        ');
	} 
	public static function Register($url, $fields, $width=530) 
	{
		// http://developers.facebook.com/docs/user_registration
		echo '
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js#appId='.self::APPID.',&xfbml=1"></script>

		<fb:registration 
			fields=\'', json_encode($fields), '\' 
			redirect-uri="', $url, '"
			width="', $width, '">
		</fb:registration>';
	}	
	public static function Avatar($id) {
		return 'http://graph.facebook.com/'.$id.'/picture';
	}
	public static function Get($txt) {
		// Me data
		// moze byt: likes, friends, groups, photos, events 
		return self::json('me/'.$txt.'?access_token='.self::$USER['access_token']);
	}
	public static function PublicInfo($name) {
		// lukas.sekerak
		// coca.cola
		// hoci aj fotku, proste vsetke verejne infomacie
		return self::json($name);	
	}
	public static function isError($data) {
		return isset($data['error']);
	}
	public static function Error($data) {
		if(!self::isError($data)) return false;
		$a = $data['error'];
		throw new Exception($a['type'].': '.$a['message']);	
		return true;
	}
	protected static function json($url) {
		return json_decode(file_get_contents('https://graph.facebook.com/'.$url), true);
	}
	public static function StreamPublishing($txt) {
		// http://developers.facebook.com/docs/guides/canvas/
		self::JS('
		<script>FB.ui({
     		method: \'stream.publish\',
     		message: \''.$txt.'\'
   			}
  		);
		</script>');
	}
	public static function Test() {
		echo self::JSFile();
		echo '<pre>';
		//self::Register();
		self::GetCookie();
		//self::LoginButton('http://www.cs.gecom.sk/register/');
		print_r(self::$USER);
		print_r(self::Get('friends'));
		//print_r(self::PublicInfo('peter.hamar'));
		//self::StreamPublishing();
		echo '</pre>';
	}
	
	
	// toto posiela po registracii na dany link
	protected static function ParseSignedRequest($signed_request) {
		list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

		// decode the data
		$sig = self::base64_url_decode($encoded_sig);
		$data = json_decode(self::base64_url_decode($payload), true);

		if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
			error_log('Unknown algorithm. Expected HMAC-SHA256');
			return null;
		}

		// check sig
		$expected_sig = hash_hmac('sha256', $payload, self::SECRET, $raw = true);
		if ($sig !== $expected_sig) {
			error_log('Bad Signed JSON signature!');
			return null;
		}
		return $data;
	}
	protected static function base64_url_decode($input) {
	    return base64_decode(strtr($input, '-_', '+/'));
	}
	public static function RegisterPost() {
		if(isset($_REQUEST['signed_request'])) {
			return self::ParseSignedRequest($_REQUEST['signed_request']);
		}
		return FALSE;
	}




}
