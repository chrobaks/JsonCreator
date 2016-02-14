<?php

class ViewController {
    
    private $Messages;
    private $Model;
    private $view;
    private $info;
    private $error;
    
    public function __construct () {
        
        $this->Messages = Messages::get_instance();
        $this->Model = ViewModel::get_instance($this->Messages);
        
        $this->view = array('activelayer' => 'new');

        $this->setHttpListener();
    }
    
    private function setHttpListener () {
        
        $act = ( isset($_POST['act']) && ! empty($_POST['act'])) 
            ? $_POST['act'] 
            : ((isset($_GET['act']) && ! empty($_GET['act'])) 
                ? $_GET['act'] 
                : '' );
                
        $infokey = '';
        
        $this->Model->setJsonStorageActiv();
        
        switch ($act) {
            case('newjson'):
            
                $this->Model->addJsonfile();
                $infokey = 'file_saved';
                
                break;
            case('newresource'):
            case('addresource'):
            
                $this->Model->addResource();
                $infokey = 'resource_saved';
                
                break;
            case('deletejson'):
            
                $this->Model->deleteJson();
                $this->view['activelayer'] = 'all';
                $infokey = 'file_delete';
                
                break;
            case('deleteresource'):
            
                $this->Model->deleteResource();
                $infokey = 'resource_delete';
                
                break;
        }
        
        if ( ! $this->Messages->hasError() && $infokey !== '') {
            
            $this->Messages->setInfo($infokey);
            
        }
    }
    
    public function getView () {
        
        $activestorage = $this->Model->getJsonStorageActiv();
        
        if ($activestorage !== '') {
            $this->view['activelayer'] = 'all';
        }
        
        $this->view['activestorage'] = $activestorage;
        $this->view['jsonstorage'] = $this->Model->getJsonstorage();
        $this->view['resourcestorage'] = $this->Model->getResource($activestorage);
        
        $this->view['error'] = $this->Messages->getError();
        $this->view['info'] = $this->Messages->getInfo();
        
        return $this->view;
        
    }
}