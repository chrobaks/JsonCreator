<?php

class Messages {
    
    public static $instance;
    
    private $error;
    private $info;
    
    public function __construct () {
        
        $this->error = array();
        $this->info = array();
        
    }
    
	public static function get_instance(){
		if( ! isset(self::$instance)){self::$instance = new Messages();}
		return self::$instance;
	}
    
    public function hasError () {
        return count($this->error);
    }
    
    public function getError () {
        return $this->error;
    }
    
    public function getInfo () {
        return $this->info;
    }
    
    public function setError ($str) {
        $this->error[] = $str;
    }
    
    public function setInfo ($str) {
        $this->info[] = $str;
    }
}