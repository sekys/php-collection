<?php
/*	CACHE funkcia nielen na cele subory ale ja na casti stranky ....	

	http://www.theukwebdesigncompany.com/articles/php-caching.php
	http://articles.sitepoint.com/article/caching-php-performance/4
	http://ontosys.com/php/cache.html	
	
	ZLOZKA bez HTMl mozeme davat vsetke objekty.
	Cize aj CSS aj binarky aj vsetko a aj HTMl samotne.
*/

class Cache
{
	const C_ZAKAZANE = -1;
	const C_NEZACALO = 0;
	const C_ZACALO = 1;
	const C_JENACITANE = 2;
	const ZLOZKA = '/home/cstrike/protected/cache/';
    
    private $status = self::C_NEZACALO;
    private $zlozka;
    public $subor;
    public $compresia = false;
    public $cas = -1; // -1 na trvalo
    public $bot = true;
	/* 
	//	CACHE typ
			-1 Cachovanie je zakazane...
			0 ani nezacalo ,
			1 zacalo cachovat
			2 je nacitane z cache
					
	// Casovanie	
		Pri testovani : Uspech cca 66% usetrene casu na 1 nacitanie
		Za cachce cas ak sa viacej krat nacitava tak 66 * x uspora + DB uspora.
		Cela cache trieda a jej spustenie zabera cca 0.020 sec pri malych textoch
		je to skoro take iste nacitanie....len echo berie rovanko casu :)
		Cache nacitanie trva len 0.003 sec.(nacitanie zo suboru)
	
	// Compresia	
		-Pridana podpora compresie.....
		- Pri TRUE ide defaultna compresia
		- Alebo sa da nastavit vlastna 
		- Alebo ziadna .....
		
	// 	SECURITY WARNING	
		Ak ide citat subor na localhoste , uzivatel tam nesmie zadat adresu .../
		aby sa nedostal k vnutornym nepovolenym suborom.
		Pomoze FILE_CLEAR alebo zahashovat nazov suboru ALEBO no_cahce
		ak nesplna podmienky podporogramu.
		
		Ak je dana stranka je nebezspecna pre verejnost a je len pr eregistrovanych ...
		tak musime mat zlozku niekde kde nieje webovy pristup :) alebo hashujeme ......
	*/

	public function Cache($name, $cas = -1) {
		$this->subor = $name;
		$this->cas = $cas;
		// Na testovanie
		//$this->status = self::C_ZAKAZANE;
	}
	protected function BotCheck() {
		// Ake je to bot cachovanie je zakazane ....      
        return ($this->bot and Browser::is_bot());
	}
    public function __desctruct() {
        if($this->status == self::C_ZACALO) {
            $this->FileEnd();
        }
    }
    public function FullFile() {
      	if($this->BotCheck()) return;
      	// Destructor sa potom zavola,..  
      	if(!$this->FileStart()) exit;
    }
    public function Zlozka($name) {
    	$this->zlozka = $name.'/';	
	}
    public function File() {
        // TRUE - vrati hodnotu aby oblasts a nacitala normalne...
        // Nezacalo cachovat ...
        if($this->status == self::C_NEZACALO) {
            return $this->FileStart();
        } else {
            $this->FileEnd();
            return false;
        }
    }
	public function Refresh() {
		// Prikaz ze CACHE je stara...z inej aplikacie alebo ma byt obnovenie 
		$name = $this->Cesta($this->subor);
        if(!is_file($name)) return false;
		return @unlink($name);
	}
	// Cacahuje funkciu, rozlicne podla jej nazvu ale i parametrov	
	// Nacachuje celu funkciu, teda ECHO vo funkcii ...
	// Vlastnu sublozku si moze zase nastavit a takto
	// mozme spolocne akcie davat do zlozky a zaroven aj mazat

	public function OutputFunction() { 	
		// Nazov - dostava tam aj callback
		$args = func_get_args();
		$callback = $this->subor;
		$this->subor = $callback.implode(',', $args);
		// Volame cache
		if($this->FileStart()) {
			$x = call_user_func_array($callback, $args); 
			$this->FileEnd();
			return $x;
		}
		return NULL; // nespustilo sa
	}
	// Toto cachuje len vysledok z funckie
	public function VarFunction() { 		
		// Nazov - dostava tam aj callback
		$args = func_get_args();
		$file = $this->Cesta($this->subor.implode(',', $args));
		// Volame cache
		if($this->fcanopen($file) and !$this->BotCheck()) {
			return unserialize( file_get_contents($file) );
		} else {
			$output = serialize(call_user_func_array($this->subor, $args));
            $fp = fopen($file, "w");
            fputs($fp, $output);
            fclose($fp);
            return $output;
		}
	}
	// $callback vracia string s kodom,...
	// $run ci to aj hned spusti pri spracovani
	public function Precompile($txt, $run=true) {
		$file = $this->Cesta($txt);	
		if($this->fcanopen($file) and !$this->BotCheck()) {
			require_once $file;
			return true;
		} else {
			// Ak je to funckia ...
			if(function_exists($txt)) {
				$args = func_get_args();
				array_shift($args);
				array_shift($args);
				$txt = call_user_func_array($txt, $args);
			}
            $fp = fopen($file, "w");
            fputs($fp, $txt);
            fclose($fp);
            if($run) require_once $file;
            return false;
		}
	}
	// Pociatky cronu...raz za cas to spusti, 0 je once
	//$c = new Cache;
	//$c->subor = 'lol';
	//$c->Run();
	// if(Cache::Once('lol')) { Stiahne zoznam FB } 
	public function Run() {
		$file = $this->Cesta($this->subor);
		if($this->fcanopen($file) and !$this->BotCheck()) return false;
        fclose( fopen($file, "w")); // to vytvori subor
		return true;
	}
	public function RunFunction() {
		$file = $this->Cesta($this->subor);
		if($this->fcanopen($file) and !$this->BotCheck()) return false;
		$args = func_get_args();
		call_user_func_array($this->subor, $args); 
        fclose( fopen($file, "w"));
		return true;
	}
	// * For static components: implement "Never expire" policy by setting far future Expires header
    // * For dynamic components: use an appropriate Cache-Control header to help the browser with conditional requests

	public function ClientFullCache() {
		// http://php.vrana.cz/http-cachovani.php
		// Pouzivat isto na staticke stranky, najlepsie na subory ktore s anemenia
		$f = $this->Cesta($this->subor);
        $time = file_exists($f) ? filemtime($f) : time();
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $time) . " GMT");
	}
	public function ClientCacheTO() {	
		// Pouzivat isto na staticke stranky, ked vieme cas odhadnut	
		$cas = $this->cas == -1 ? 1.0e-9 : $this->cas;
        header('Cache-Control: must-revalidate');
		header('Expires: '.gmdate('D, d M Y H:i:s', time() + $cas).'GMT');  
	}
	public function CANT() { 
		$this->status = self::C_ZAKAZANE; 
	}	
    protected function Cesta($name) { 
    	return self::ZLOZKA.$this->zlozka.md5($name);
    } 
	private function FileStart() {
		if(!$this->BotCheck()) {
        	$this->status = self::C_ZAKAZANE;
        	return true;
		}
		$this->status = self::C_ZACALO;
		$name = $this->Cesta($this->subor);
        if($this->fcanopen($name)) {
			//debug_oblast('CACHE ON', true);
			// Otvarame cache subor
			readfile($name);
			$this->status = self::C_JENACITANE;
			return false;
		}
		ob_start(); 
		//debug_oblast('CACHE OFF', true);
        // echo '<!-- ', $name, ' - ', date('l jS F Y h:i:s A'), ' -->';
		return true;
	}
	private function FileEnd() {
		if($this->status != self::C_ZACALO) return false;
        $name = $this->Cesta($this->subor); 
		@$fp = fopen($name, 'w');
		if(!$fp) {
			exit('Do suboru <b>'.$name.'</b> sa neda zapisovat.');
			return false;
		}
		// Compresia
        if($this->compresia === true) {
            $this->compresia = 'default_compression'; 
        }
        if($this->compresia) {
        	$txt = call_user_func($this->compresia, ob_get_contents());	
		} else {
			$txt = ob_get_contents();;	
		}
        // inak je defaultne false ....cize vypnute 
		fwrite($fp, $txt);
		fclose($fp);
		ob_end_clean(); 
		echo $txt;
	}
	private function fcanopen($name) { // false prebehne
		if(!file_exists($name)) return false;
		if($this->cas == -1) return true;
        if(time() > filemtime($name) + $this->cas ) return false;
		return true;
	}	
	public static function Test() {
		$temp = new Cache(60, 'cache_test');
		if($temp->File()) {  
			echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
			echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
			echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
			echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
			echo 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
			echo '<? echo 4+4; ?>'; // fungje pomocou readfile aj ochrana :)
		}
		unset($temp); // alebo $temp->File(); a pouzivat dalej
	}
}

//Cache::Test();

// necachuju sa cele subory ale povedzme ze stredok newsiek alebo komenatare si budeme cachovat
// vsetko zalezi a je dollezite ze chache subor, nazov tochto suboru
// davame ako celu adresu
// lebo ak bude v adrese ?id=1&v=on a ine nastavenia tak bud eproblem ....
// benchmark ab -t30 -c5 http://www.example.com/
