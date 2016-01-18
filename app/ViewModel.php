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
    }
    
	public static function get_instance($Messages){
		if( ! isset(self::$instance)){self::$instance = new ViewModel($Messages);}
		return self::$instance;
	}
    
    private function putEncodeContents ($resource, $filename) {
        
        $json = json_encode($resource);
        file_put_contents($filename, $json);
        
    }
    
    private function getDecodeContents ($filename) {
        
        $res = file_get_contents($filename);
        
        return json_decode($res, true);
    }
    
    private function setResourceIndexKey ($resourceconf) {
        
        $this->resource_indexkey = (isset($resourceconf['indexkey'])) ? $resourceconf['indexkey']:'';
        
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
    
    private function filterJsonfileObject ($arr) {
        
        $res = true;
        
        foreach($this->jsonfile_object as $k=>$v) {
            
            if (key_exists($k,$arr)) {
                
                $arr[$k] = trim($arr[$k]);
                
                if ( ! empty($arr[$k]) || empty($arr[$k]) && $k === "indexkey") {
                    
                    $this->jsonfile_object[$k] = $arr[$k];
                    
                } else {
                    $this->Messages->setError('Kein Eintrag in Feld : '.$k);
                    $res = false;
                    break;
                }
                
            }else {
                $this->Messages->setError('Folgendes Feld nicht gefunden : '.$k);    
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
                    $this->Messages->setError('Kein Eintrag in Feld : '.$k);
                    $res = false;
                    break;
                }
                
            }else {
                $this->Messages->setError('Folgendes Feld nicht gefunden : '.$k);    
                $res = false;
                break;
            }
        }
        
        if (isset($arr[$this->resource_indexkey])) {
            
            $this->resource_object_id = $arr[$this->resource_indexkey];
            
        }
        
        return $res;
    }
    
    public function deleteJson ($filename) {
        
        $filename_jason = DIR.DIRECTORY_SEPARATOR.JSONPATH.$filename.'.json';
        $filename_conf = DIR.DIRECTORY_SEPARATOR.JSONPATH.$filename.'.conf.json';
        
        if (file_exists($filename_jason) && file_exists($filename_conf)) {
            
            if ( ! unlink ($filename_jason)) {
                
                $this->Messages->setError('Kann Datei nicht löschen : '.$filename.'.json'); 
                
            } else {
                
               if ( ! unlink ($filename_conf)) {
                
                $this->Messages->setError('Kann Datei nicht löschen : '.$filename.'.conf.json'); 
                
                }
            }
            
            
        } else {
            
            $this->Messages->setError('Datei nicht gefunden : '.$filename.'.json'); 
            
        }
    }
    
    public function deleteResource ($resourceid, $activestorage) {
        
        $resource = $this->getDecodeContents(JSONPATH.$activestorage);
        
        if (isset($resource[$resourceid])) {
            
            unset($resource[$resourceid]);
            
            $this->putEncodeContents($resource, JSONPATH.$activestorage);
            
        } else {
            
            $this->Messages->setError('Resource nicht gefunden : '.$resourceid); 
            
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
    
    public function setJsonfile ($data) {
        
        if ($this->filterJsonfileObject ($data)) {
            
            $conffile = JSONPATH.$this->jsonfile_object['jsonfile'].'.conf.json';
            $jsonfile = JSONPATH.$this->jsonfile_object['jsonfile'].'.json';
            
            $this->putEncodeContents($this->jsonfile_object, $conffile);
            
            if (file_exists($conffile)) {
                
                file_put_contents($jsonfile, '{}');
                
                if ( ! file_exists($jsonfile)) {
                    
                    $this->Messages->setError('Die json datei konnte nicht gespeichert werden.');
                }
                
            } else {
                
                $this->Messages->setError('Die json.config datei konnte nicht gespeichert werden.');
                
            }
        }
    }
    
    public function setResource ($data) {
        
        if ($this->filterResourceObject ($data)) {
            
            $resource = $this->getDecodeContents(JSONPATH.$data['activestorage']);
            $resource[$this->resource_object_id] = $this->resource_object;
            
            $this->putEncodeContents($resource, JSONPATH.$data['activestorage']);
            
        }   
    }
}