<?php

class ViewModel {
    
	public static $instance;
    
    private $Messages;
    private $resource_keys;
    private $resource_indexkey;
    private $resource_object;
    private $resource_object_id;
    private $jsonfile_object;
    private $jsonstorage;
    private $jsonstorage_activ;
    private $filter;
    
    public function __construct ($Messages) {
        
        $this->Messages = $Messages;
        $this->resource_keys = array();
        $this->resource_indexkey = '';
        $this->resource_object = array();
        $this->resource_object_id = '';
        $this->jsonfile_object = array(
            "jsonfile" => "",
            "keys" => "",
            "indexkey" => ""
        );
        $this->jsonstorage = array();
        $this->jsonstorage_activ = '';
        
        $this->filter = array(
            'deletejson' => array('deletefile'),
            'deleteresource' => array('delete','activestorage')
        );
    }
    
	public static function get_instance($Messages = ''){
	   
		if( ! isset(self::$instance)){
		  
		  self::$instance = new ViewModel($Messages);
          
        }
        
		return self::$instance;
	}
    
    private function putEncodeContents ($resource, $filename) {
        
        $json = (is_array($resource)) ? json_encode($resource) : $resource;
        file_put_contents($filename, $json);
        
        return file_exists($filename);
    }
    
    private function getDecodeContents ($filename) {
        
        $res = file_get_contents($filename);
        
        return json_decode($res, true);
    }
    
    private function setResourceIndexKey ($resourceconf) {
        
        $this->resource_indexkey = (isset($resourceconf['indexkey'])) ? $resourceconf['indexkey'] : '';
        
    }
    private function setResourceKeys ($resourceconf) {
        
        $this->resource_keys = array();
        
        if( ! empty($resourceconf['keys'])) {
            
            $keys = explode(',',$resourceconf['keys']);
            
            foreach($keys as $key) {
                
                $this->resource_keys[] = trim($key);
                
            }
        }
    }
    private function setJsonstorage () {
        
        $this->jsonstorage = array(
            'configs' => array(),
            'jsons' => array()
        );
        
        if (is_dir(JSONPATH)) {
            
            $files = scandir(JSONPATH);
            
            if ( ! empty($files)) {
                
                foreach ($files as $file) {
                    
                    if( preg_match('/\.json$/',$file)) {
                        
                        if( preg_match('/\.conf\.json$/',$file)) {
                            
                            $this->jsonstorage['configs'][] = $file;
                        } else {
                            
                            $this->jsonstorage['jsons'][] = $file;
                        }
                    }
                }
            }
        }
    }
    
    private function setResourceObject ($filename) {
        
        $fileid = str_replace('.json','',$filename);
        $resourceconf = $this->getDecodeContents(JSONPATH.$fileid.'.conf.json');
        $keys = explode(',',$resourceconf['keys']);
        
        foreach($keys as $key){
            
            $key = trim($key);
            
            if ($key!==$resourceconf['indexkey']) {
                
                $this->resource_object[$key] = '';
                
            } else {
                
                $this->setResourceIndexKey($resourceconf);
            }
        }
    }
    
    private function filterHttp ($filter,$type='post') {
        
        $http = ($type==='post') ? $_POST : $_GET;
        $res = array();
        
        if (isset($this->filter[$filter])) {
            
            foreach ($this->filter[$filter] as $key) {
                
                if (isset($http[$key])) {
                    
                    $val = trim($http[$key]);
                    
                    if ( ! empty($val)) {
                        $res[$key] = $val;
                    }
                    
                }
            }
            
        }
        
        return $res;
        
    }
    
    private function filterJsonfileObject ($arr) {
        
        $res = true;
        
        foreach($this->jsonfile_object as $k=>$v) {
            
            if (key_exists($k,$arr)) {
                
                $arr[$k] = trim($arr[$k]);
                
                if ( ! empty($arr[$k]) || empty($arr[$k]) && $k === "indexkey") {
                    
                    $this->jsonfile_object[$k] = $arr[$k];
                    
                } else {
                    $this->Messages->setError('field_empty', $k);
                    $res = false;
                    break;
                }
                
            }else {
                $this->Messages->setError('field_notfound', $k);    
                $res = false;
                break;
            }
        }
        if ( $res === true) {
            
            $this->jsonfile_object['autoindex'] = ($this->jsonfile_object['indexkey']==='') ? 1:0;
            
            if ($this->jsonfile_object['indexkey']==='') {
                
                $this->jsonfile_object['indexkey'] = 'autoindex_';
            }
            
        }
        
        
        return $res;
    }
    
    private function filterResourceObject ($arr) {
        
        $this->setResourceObject($arr['activestorage']);
        
        $res = true;
        
        foreach($this->resource_object as $k=>$v) {
            
            if (key_exists($k,$arr)) {
                
                $arr[$k] = trim($arr[$k]);
                
                if ( ! empty($arr[$k])) {
                    
                    $this->resource_object[$k] = $arr[$k];
                    
                } else {
                    $this->Messages->setError('field_empty', $k);
                    $res = false;
                    break;
                }
                
            }else {
                $this->Messages->setError('field_notfound', $k);    
                $res = false;
                break;
            }
        }
        
        if (isset($arr[$this->resource_indexkey])) {
            
            $this->resource_object_id = $arr[$this->resource_indexkey];
            
        }
        
        return $res;
    }
    
    public function setJsonStorageActiv () {
        
        $this->jsonstorage_activ = '';
        
        if (isset($_POST['activestorage']) && ! empty($_POST['activestorage'])) {
            
            $this->jsonstorage_activ = $_POST['activestorage'];
            
        } else if (isset($_GET['activestorage']) && ! empty($_GET['activestorage'])) {
            
            $this->jsonstorage_activ = $_GET['activestorage'];
        }
        
    }
    
    public function getJsonStorageActiv () {
        
        return $this->jsonstorage_activ;
        
    }
    
    public function deleteJson () {
        
        $data = $this->filterHttp('deletejson','get');
        
        $filename = $data['deletefile'];
        
        $filename_jason = DIR.DIRECTORY_SEPARATOR.JSONPATH.$filename.'.json';
        $filename_conf = DIR.DIRECTORY_SEPARATOR.JSONPATH.$filename.'.conf.json';
        
        if (file_exists($filename_jason) && file_exists($filename_conf)) {
            
            if ( ! unlink ($filename_jason)) {
                
                    $this->Messages->setError('file_delete_failed', $filename.'.json'); 
                
            } else {
                
               if ( ! unlink ($filename_conf)) {
                
                    $this->Messages->setError('file_delete_failed', $filename.'.conf.json'); 
                
                }
            }
        } else {
            
            $this->Messages->setError('file_notfound', $filename.'.json'); 
            
        }
    }
    
    public function deleteResource () {
        
        $data = $this->filterHttp('deleteresource','get');
        
        $resourceid = $data['delete'];
        
        $resource = $this->getDecodeContents(JSONPATH.$this->jsonstorage_activ);
        
        if (isset($resource[$resourceid])) {
            
            unset($resource[$resourceid]);
            
            if ( ! $this->putEncodeContents($resource, JSONPATH.$this->jsonstorage_activ)) {
                
                $this->Messages->setError('resource_update_failed', $this->jsonstorage_activ);
                
            }
            
        } else {
            
            $this->Messages->setError('resource_notfound', $resourceid); 
            
        }
    }
    
    private function getActivestorageIndex ($activestorage, $storage) {
        
        $res = array_search($activestorage, $storage);
        
        if ($res === false){
            $res = 0;
        }
        
        return $res;
    }
    
    public function getResource ($activestorage = '') {
        
        if ( ! empty($this->jsonstorage['jsons'])){
            
            $activestorage = $this->getActivestorageIndex($activestorage, $this->jsonstorage['jsons']);
            
            $file = JSONPATH.$this->jsonstorage['jsons'][$activestorage];
            $conffile = JSONPATH.$this->jsonstorage['configs'][$activestorage];
            
            if ( file_exists($file) && file_exists($conffile)) {
                
                $resourceconf = $this->getDecodeContents($conffile);
                
                $this->setResourceKeys($resourceconf);
                $this->setResourceIndexKey($resourceconf);
                
                $res = $this->getDecodeContents($file);
                
            }
        }
        
        $res = array(
            'resource' => $res,
            'resourcekeys' => $this->resource_keys,
            'resourceindexkey' => $this->resource_indexkey,
            'jsonfile' => $this->jsonstorage['jsons'][$activestorage]
        );
        
        return $res;
    }
    
    public function getResourceKeys () {
        
        return $this->resource_keys;
        
    }
    
    public function getJsonstorage () {
        
        $this->setJsonstorage();
        
        return $this->jsonstorage;
        
    }
    
    public function addJsonfile () {
        
        if ($this->filterJsonfileObject ($_POST)) {
            
            $conffile = JSONPATH.$this->jsonfile_object['jsonfile'].'.conf.json';
            $jsonfile = JSONPATH.$this->jsonfile_object['jsonfile'].'.json';
            
            if ($this->putEncodeContents($this->jsonfile_object, $conffile)) {
                
                if ( ! $this->putEncodeContents('{}', $jsonfile)) {
                    
                    $this->Messages->setError('file_save_failed', $jsonfile);
                }
            } else {
                
                $this->Messages->setError('file_save_failed', $conffile);
                
            }
        }
    }
    
    public function addResource () {
        
        if ($this->filterResourceObject ($_POST)) {
            
            $resource = $this->getDecodeContents(JSONPATH.$data['activestorage']);
            $resource[$this->resource_object_id] = $this->resource_object;
            
            if ( ! $this->putEncodeContents($resource, JSONPATH.$data['activestorage'])) {
                
                $this->Messages->setError('resource_save_failed', $this->resource_object_id);
                
            }
        }   
    }
}