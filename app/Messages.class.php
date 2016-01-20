<?php

class Messages {
    
    public static $instance;
    
    private $error;
    private $info;
    private $messages;
    
    public function __construct () {
        
        $this->error = array();
        $this->info = array();
        
        $this->messages = array(
            'error' => array(
                'field_empty' => 'Kein Eintrag in Feld : $1',
                'field_notfound' => 'Folgendes Feld nicht gefunden : $1',
                'resource_notfound' => 'Resource nicht gefunden : $1',
                'resource_update_failed' => 'Update der Resource-Datei nicht möglich : $1',
                'resource_save_failed' => 'Die Resource konnte nicht gespeichert werden : $1',
                'file_notfound' => 'Folgendes Feld nicht gefunden : $1',
                'file_delete_failed' => 'Kann Datei nicht löschen : $1',
                'file_save_failed' => 'Die Jsondatei konnte nicht gespeichert werden : $1'
            ),
            'info' => array(
                'file_saved' => 'Die Datei wurde gespeichert.',
                'file_delete' => 'Die Datei wurde gelöscht.',
                'resource_saved' => 'Die Resource wurde gespeichert.',
                'resource_delete' => 'Die Resource wurde gelöscht.'
            )
        );
        
    }
    
	public static function get_instance(){
	   
		if( ! isset(self::$instance)){
		  
		  self::$instance = new Messages();
          
        }
		
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
    
    public function setError ($key, $args='') {
        
        if (isset($this->messages['error'][$key])) {
            
            $msg = $this->messages['error'][$key];
            
            if ( $args !=='') {
                
                $msg = str_replace('$1',$args, $msg);
                
            }
            
            $this->error[] = $msg;
            
        }
    }
    
    public function setInfo ($key) {
        
        if (isset($this->messages['info'][$key])) {
            
            $this->info[] = $this->messages['info'][$key];
            
        }
    }
}