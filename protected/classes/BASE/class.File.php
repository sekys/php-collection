<?php

class File {

	public static function Pamet($adresa) {
		if(is_file($adresa)) {
			return filesize($adresa);
		} else {
			return $this->PametDir($adresa);
		}
	}
	public static function PametDir($adresa) {  
		$Size = 0;
		$Dir = opendir($adresa);
		if(!$Dir) return -1;
		while(($File = readdir($Dir)) !== false) {
			// Toto musi tak byt
			if($File[0] == '.') continue; 
			$temp = $adresa.$File;
			if(is_dir($temp)) {// pouzijeme rekurziva na sub zlozky            
				$Size += file_pamet_dir($temp.DIRECTORY_SEPARATOR);
			} else {
				$Size += filesize($temp); 
			}	
		}
		closedir($Dir);
		return $Size;
	}
	public static function Delete($adresa) {
		if(is_file($adresa)) return @unlink($adresa);
		elseif(is_dir($adresa)) {
			$scan = glob(rtrim($adresa,'/').'/*');
			foreach($scan as $index=>$cesta) file_delete($path);
			return @rmdir($cesta);
		}
	}
	public static function NameClear($adresa) { return str_replace( array("/", "\\", "."), "", $adresa); }
	public function GetBytes($bytes) {
		$s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
		$e = floor(log($bytes)/log(1024));
		return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
	}
	public static function Download($subor , $nazov)  {
		global $userdata;
		@mysql_query("INSERT INTO `cstrike`.`web2_logs` (kat, typ, kto, co, komu, int, kedy) VALUES ('-1', '30', ".$userdata[0].", ".$_GET['p1'].", NULL, NULL, ".$time.")");
		header("Content-Description: File Transfer");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"$nazov\"");
		header("Content-Length: " . filesize($subor));
		readfile($subor); 
	}
    public static function FindR($adresa, $name) {
        $Dir = opendir($adresa);
        if(!$Dir) return false;  
        while(($File = readdir($Dir)) !== false) {
            $temp = $adresa.$File;
            // Pouzijeme rekurziva na sub zlozky
            if(is_dir($temp)) {            
                $dirs[] = $temp;
            } else {
                if($File == $name) return $temp; 
            }    
        }
        closedir($Dir);
        // Teraz prechadzaj recurzivne
        foreach($dirs as $cesta) {
            $data = FindFileR($cesta, $name);
            if($data) return $data;
        }
        return false;
    }    
    public static function TempFileCopy($writedata) {
        $temp = tmpfile();
        fwrite($temp, $writedata);
        fseek($temp, 0);
        echo fread($temp, 1024);
        fclose($temp); // this removes the file
    }
}

?>
