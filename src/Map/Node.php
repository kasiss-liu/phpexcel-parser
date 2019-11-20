<?php

namespace Kasiss\PhpExcelParser\Map;

class Node implements \arrayaccess {
    
    protected $realName;
    protected $nodeName;
    protected $description;
    protected $expression;
    protected $value;
    protected $sheetName;
    protected $cellName;


    public function __construct($nodeName,$realName,$desc,$expression="",$value="") {
        $this->realName = $realName;
        $this->nodeName = $nodeName;
        $this->description = $desc;
        $this->expression = $expression;
        $this->value = $value;
        if(strpos($nodeName,'!') > 0) {
            $explode = explode('!',$nodeName);
            if(count($explode) > 1) {
                $this->cellName = $explode[1];
                $this->sheetName = $explode[0];
            }
        }
    }

    public function toJson() {
        return json_encode($this->toArray(),JSON_UNESCAPED_UNICODE);
    }

    public function toArray() {
        return [
            'realName' => $this->realName,
            'nodeName' => $this->nodeName,
            'description' => $this->description,
            'expression' => $this->expression,
            'sheetName' => $this->sheetName,
            'cellName' => $this->cellName,
            'value' => $this->value,
        ];
    }

    public function __get($prop) {
        return isset($this->$prop) ? $this->$prop : null;
    }

    public function __set($prop,$value) {
        $this->$prop = $value;
    }

    public function offsetSet($offset,$value) {
        is_null($offset) ? $this->$$value = $value : $this->$offset = $value;
    }

    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetUnset($offset) {
        unset($this->$offset);
    }

    public function offsetGet($offset) {
        return isset($this->$offset) ? $this->$offset : null;
    }

    public static function create($nodeName,$realName,$desc,$expression="",$value="") {
        return new self($nodeName,$realName,$desc,$expression,$value);
    }
}