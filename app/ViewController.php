<?php

class ViewController {
    
    private $view;
    private $info;
    private $error;
    private $Messages;
    private $Model;
    private $Db;
    
    public function __construct () {
        
        $this->Messages = Messages::get_instance();
        $this->Model = ViewModel::get_instance($this->Messages);
        
        $this->view = array('activelayer' => 'new');

        $this->setHttpListener();
    }
    
    private function setHttpListener () {
        
        if ( isset($_POST['jsonfile']) && ! empty($_POST['jsonfile'])) {
            
            $this->addJsonfile();
            
        } else if ( isset($_POST['resourceaction']) && ! empty($_POST['activestorage'])) {
            
            $this->addResource();
            
        } else if ( isset($_GET['delete'], $_GET['activestorage']) && ! empty($_GET['delete']) && ! empty($_GET['activestorage'])) {
            
            $this->deleteResource();
            
            $_POST['activestorage'] = $_GET['activestorage'];
            
        } else if ( isset($_GET['deletefile'])) {
            
            $this->deleteJson();
            
            $this->view['activelayer'] = 'all';
            
        }
    }
    
    private function addJsonfile () {
        
        $this->Model->setJsonfile($_POST);
        
        if ( ! $this->Messages->hasError()) {
            
            $this->Messages->setInfo('file_saved');
        }
    }
    
    private function deleteJson () {
        
        $this->Model->deleteJson($_GET['deletefile']);
        
        if ( ! $this->Messages->hasError()) {
            
            $this->Messages->setInfo('file_delete');
        }
        
    }
    
    
    private function addResource () {
        
        $this->Model->setResource($_POST);
        
        if ( ! $this->Messages->hasError()) {
            
            $this->Messages->setInfo('resource_saved');
        }
        
    }
    
    private function deleteResource () {
        
        $this->Model->deleteResource($_GET['delete'], $_GET['activestorage']);
        
        if ( ! $this->Messages->hasError()) {
            
            $this->Messages->setInfo('resource_delete');
        }
            
    }
    
    public function getView () {
        
        $activestorage = (isset($_POST['activestorage'])) ? $_POST['activestorage'] : '' ;
        
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