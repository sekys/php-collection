<?php

class FTP 
{
	private $spojenie;

	public function Stiahni($subor , $nazov) {
		header("Content-Description: File Transfer");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=\"$nazov\"");
		header("Content-Length: " . filesize($subor));
		readfile($subor); 
	}	
	public function byteConvert($bytes) {
		static $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
		$e = floor( log($bytes)/log(1024) );
		return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
	}
	public function Prenes($ftp_subor, $home_subor) {		
		$fp = fopen($home_subor, 'w');
		$ret = ftp_nb_fget($this->spojenie, $fp, $ftp_subor, FTP_BINARY);	
		while ($ret == FTP_MOREDATA) $ret = ftp_nb_continue($this->spojenie);
		if ($ret != FTP_FINISHED) {
			throw new Exception("Subor sa nepodarilo stiahnut.");
			fclose($fp);
			return false;
		}
		fclose($fp);
		return true;
	}
	public function __construct($ip, $name, $pass) {		
		// Spojenie
		$ftp = ftp_connect($ip);
		if(!$ftp) die("Nepodarilo sa pripojit na server.");
		$login = ftp_login($ftp, $name, $pass);		
		if(!$login) die("Nepodarilo sa prihlasit na server.");
		$this->spojenie = $ftp;
	}
	public function Zoznam($cesta) {
		$subory = ftp_rawlist($this->spojenie, $cesta);		
        $out = NULL;

        for($i=0; $i < count($subory); $i++) {
			if(ereg("([-dl][rwxst-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)", $subory[$i], $regs)) 
			{
				// $regs[] = (int) strpos("-dl", $regs[1]{0});	//  => 0 subor , 1 zlozka	
                array_shift($regs);		
				$out[] = $regs;					
			}
		}
		return $out;
	}
    public function Najdi($cesta, $subor) {
        $subory = ftp_rawlist($this->spojenie, $cesta);        
        $out = NULL;

        for($i=0; $i < count($subory); $i++) {
            if(ereg("([-dl][rwxst-]+).* ([0-9]*) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9])[ ]+(([0-9]{2}:[0-9]{2})|[0-9]{4}) (.+)", $subory[$i], $regs)) 
            {
                // $regs[] = (int) strpos("-dl", $regs[1]{0});    //  => 0 subor , 1 zlozka    
                if( !(strpos($regs[9], $subor) === false) )    { 
                    array_shift($regs);        
                    $out[] = $regs;
                }                    
            }
        }
        return $out;
    }    
	public function Get($cesta, $mode = FTP_BINARY, $pokracovat = 0, $limit = 1024)
    {
	    // Vytvorenie stream tempu
        $pipes = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        if($pipes === false) return false;
        if(!stream_set_blocking($pipes[1], 0)) {
            fclose($pipes[0]); fclose($pipes[1]);
            return false;
        }
	    // Stahovanie
        $fail = false; $data='';
        if($pokracovat == 0){
            @$ret = ftp_nb_fget($this->spojenie, $pipes[0], $cesta, $mode);
        } else {
            @$ret = ftp_nb_fget($this->spojenie, $pipes[0], $cesta, $mode, $pokracovat);
        }	    
        while($ret==FTP_MOREDATA){
            while(!$fail && !feof($pipes[1])){
                $r = fread($pipes[1], $limit);
                if($r === '') break;
                if($r === false) { $fail = true; break; }
                $data .= $r;
            }
            $ret = ftp_nb_continue($this->spojenie);
        }
	    // Parsovanie
        fclose($pipes[0]); fclose($pipes[1]);
        if($fail || $ret!=FTP_FINISHED) return false;
        return $data;
    }
    public function FromFileToArray($data) { return explode("\n", $data); }	
    public function Set($cesta, $co, $mode = FTP_BINARY , $limit = 1024)
    {
	    @$temp = fopen( 'php://temp' , 'r+');
	    if(!fwrite($temp , $co ) == false ) throw new Exception("Nepodarilo sa vytvorit TEMP subor.");
	    rewind($temp);  //refresh 
        return $this->_Set($temp, $co, $mode, $limit);
    }
    public function SetFromFile($cesta, $co, $mode = FTP_BINARY , $limit = 1024)
    {
        @$temp = fopen($co, 'r+');
        return $this->_Set($temp, $co, $mode, $limit);
    }	
    protected function _Set($temp, $co, $mode, $limit) {
        @$ret = ftp_nb_fput($this->spojenie, $cesta, $temp, $mode);  // upload      
        while($ret==FTP_MOREDATA) $ret = ftp_nb_continue($this->spojenie);
        @fclose($temp);
        return ($ret == FTP_FINISHED);
    }
    public function chmod($path, $perm) { return @ftp_chmod($this->spojenie, $perm, $path); } 
    public function delete_dir($filepath) { return @ftp_rmdir($this->spojenie, $filepath); } 
    public function delete($filepath) { return @ftp_delete($this->spojenie, $filepath); }
    public function rename($old_file, $new_file) { return @ftp_rename($this->spojenie, $old_file, $new_file); } 
    public function upload2($locpath, $rempath, $automode = true) { return @ftp_put($this->spojenie, $rempath, $locpath, $automode ? FTP_BINARY : FTP_ASCII); } 
    public function mkdir($path) { return  @ftp_mkdir($this->spojenie, $path); }    	
}

?>
