<?

class DBTable extends Generic 
{
    public function out($name) { return BaseSTR::XSS($this->__data[$name]); }
    public function uri($name) { return STR::uri_out($this->__data[$name]); }
    public function uout($name) { return STR::uri_out(self::out($name)); }
    public function setout() { 
        foreach(func_get_args() as $a) $this->__data[$name] = $this->out($name); 
    }    
}