<?php

class ICQ 
{      
    protected $uin;
    public function __construct($uin) { 
        $uin = str_replace('-', '', $uin);
        if(is_numeric($uin)) $this->uin = $uin; 
    } 
    protected function GetCrc($url) {
       $ch = curl_init();
       curl_setopt ($ch, CURLOPT_URL, $url);
       curl_setopt ($ch, CURLOPT_HEADER, 0);
       ob_start();
       curl_exec ($ch);
       curl_close ($ch);
       $cache = ob_get_contents();

       ob_end_clean();
       //print($cache);
       return (string)abs(crc32($cache));
     }     
     public function Status() {
        static $crc = array('253889085' => 'offline', '1177883536' => 'online', '1182613274' => 'hidden'); 
        $check = $this->GetCrc( 'http://status.icq.com/online.gif?icq=' . $this->uin . '&img=5');
        if (in_array($check, array_keys($crc))) {
            return $crc[$check];
        }
        return false;
     } 
     public function Status2($timeout=8)  {
        // http://status.icq.com/online.gif?icq=453529751&img=26
        @$fp = fsockopen('status.icq.com', 80, &$errno, &$errstr, $timeout);
        if(!$fp) {
            return "N/A";
        } else {
            $request = "HEAD /online.gif?icq=".$this->uin." HTTP/1.0\r\nHost: web.icq.com\r\nConnection: close\r\n\r\n";
            fputs($fp, $request);
            do {
                $response = fgets($fp, 1024);
            } while(!feof($fp) && !stristr($response, 'Location'));
            fclose($fp);

            if(strstr($response, 'online1')) return 'Online';
            if(strstr($response, 'online0')) return 'Offline';
            if(strstr($response, 'online2')) return 'N/A';
            // N/A unamena, ze uzivatel si nastavil moznost, ze jeho
            // status nemoze byt viditeny cez internet
            return 'N/A';
        }
    }   
}

/*
Usage:

 $icq = new ICQ();
 print($icq->status('123456789'));

Outputs 'online', 'offline' or 'hidden'.
*/