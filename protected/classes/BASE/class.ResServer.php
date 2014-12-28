<?	

/*	RESOURCE funkcie
	Podpora:	
		CSS
		JAVASCRIPT
		Image
		CSS compresie
		
	text/css	
	Tymto usetrime az 73% !
    Je to aj efektivnejsie, aby sa sa viacero suborov nevolalo.	
*/

class ResServer
{
    /*
    -1 na vzdy
    0 nechachuj 
    n - casuj na
    */
    public $cachetime = -1;
    public $paths = array();
    public $compress = true;    
    /*
    0 - nic
    1 - js
    2 - css
    */
    private $typ;  
    private $files = array();
    private $badfiles = array();
    private $filespaths = array(); 
    private $cachedriver;  
    private $canrun = false; 
    private $name;
    
    /* Pouziva sa na strane  serveru */
    public function SetTyp($typ) { $this->typ = $typ; $this->Header(); }
    protected function Error($msg) { die($msg); }
    public function Files($f) { $data = explode(",", $f); foreach($data as $name) { $this->files[] = $name; } }
    public function File($f) { $this->files[] = $f; }
    private function InfoItem($name, $data) { printf("/* %20s:  %-40s */\n", $name, $data); }
    private function GetFileName() { return implode(",", $this->files); }
    private function Header() {
        switch($this->typ) {
            case 0 : {  $header = "text/javascript"; break; }
            case 1 : {  $header = "text/css";        break; }
            default: {  $this->Error("BAD TYPE"); ; }
        }
        header("Content-type: ".$header."; charset: UTF-8");
    }
   
    public function Start() {
        if(!count($this->files)) $this->Error("NO FILES"); 
        if($this->cachetime == 0) {
             $this->canrun = true;
             return;
        }
        $this->NaOstro();
    }
    public function End() { 
    	if($this->cachetime != 0) $this->cachedriver->file();
    }
    public function EchoData() {
        if(!$this->canrun) return;
        foreach($this->filespaths as $file) {
            if($this->compress) {
                $data = file_get_contents($file);
                $this->compress($data);
                echo $data;
            } else { 
                readfile($file); 
            }     
        }
        flush();   
    }
    public function Info() {       
        if(!$this->canrun) return;
        echo "\n"; 
        $this->InfoItem('HTTP Host', $_SERVER['HTTP_HOST']);
        $this->InfoItem('Machine', $_SERVER["SERVER_NAME"]);
        $this->InfoItem('Name', md5($this->name));
        $this->InfoItem('Generated', date('l jS F Y h:i:s A'));
        if($this->cachetime != 0 )$this->InfoItem('CachedTo', $this->cachetime==-1 ? "INFINITY" : date('l jS F Y h:i:s A', time()+$this->cachetime) );
        $this->InfoItem('Compression', $this->compress ? "ON" : "OFF");
        $this->InfoItem('Files', $this->GetFileName() );     
        if(count($this->badfiles)) $this->InfoItem('BadFiles', implode(",", $this->badfiles));
        echo "\n\n";
        flush();
    }

    private function NaOstro() {    
        $meno = $this->compress ? '1' : '0';
        $meno .= $this->typ . $this->GetFileName();
        $this->name = md5($meno); 
        $this->cachedriver = new Cache($this->name, $this->cachetime);
        $this->cachedriver->bot = false;
        $this->cachedriver->Zlozka('resource');
        // Header
        if($this->cachetime == -1) {
        	$this->cachedriver->ClientFullCache();
		}
        $this->canrun = $this->cachedriver->File();
        if($this->canrun ) {
             $this->ParseFiles();
        } 
    }
    
    private function compress(&$buffer) {
        // TODO: Neviem ako filtrovat //
        // nejde ako ma - nici kod
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); // remove comments
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer); // remove tabs, spaces, newlines, etc.
        $buffer = str_replace('{ ', '{', $buffer); // remove unnecessary spaces.
        $buffer = str_replace(' }', '}', $buffer);
        $buffer = str_replace('; ', ';', $buffer);
        $buffer = str_replace(', ', ',', $buffer);
        $buffer = str_replace(' {', '{', $buffer);
        $buffer = str_replace('} ', '}', $buffer);
        $buffer = str_replace(': ', ':', $buffer);
        $buffer = str_replace(' ,', ',', $buffer);
        $buffer = str_replace(' ;', ';', $buffer);
    }
    private function ParseFiles() {
        $subory = $this->files;
        $this->files= array();
        foreach($subory as $subor) {
            $meno = BaseSTR::AntiRelativePath($subor); // anti relative path 
            $zlozka = $this->paths[$this->typ];
            for($i=0; $i < count($zlozka); $i++) {
                /* 
                    alebo File::FindR($adresa, $name) :)... 
                    alebo inac ziadat subor v tvare game/ajax.css
                */
                $url = sprintf($zlozka[$i], $meno);
                if(is_file($url)) {
                    $this->filespaths[] = $url;
                    $this->files[] = $meno;
                } else {
                    $this->badfiles[] = $meno;
                }
            } 
        }
    }
}

?>
