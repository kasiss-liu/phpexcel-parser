<?php
namespace Kasiss\PhpExcelParser\Map;

class NodeDefine {

    protected $files = [];

    protected $assocKey = null;
    
    protected $data = [];

    public function __construct($files=[],$assocKey = null) {
        $this->files = $files;
        $this->assocKey = $assocKey;
        $this->fresh();
    }

    public function setAssocKey($key) {
        $this->assocKey = $key;
    }
    public function addFile($file) {
        if(!in_array($file,$this->files)) {
            $this->files[] = $file;
        }
    }
    public function fresh() {
        if($this->files) {
          $this->data = Loader::loadNodeDefinedMultiJsonFile($this->assocKey,...$this->files);
        }
    }
    public function get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }
    public function data() {
        return $this->data;
    }
    public function setData($data = []) {
        $this->data  = array_merge($this->data ,$data);
    }

}