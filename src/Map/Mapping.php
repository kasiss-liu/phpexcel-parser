<?php

namespace Kasiss\PhpExcelParser\Map;

class Mapping implements \arrayaccess {

    protected $realName;
    protected $nodeName;
    protected $description;
    protected $expression;
    protected $value;

    public function __construct($nodeName="",$realName="",$description="",$expression="",$value="") {
        $realName ? $this->realName = $realName : $this->realName = 'realName';
        $nodeName ? $this->nodeName = $nodeName : $this->nodeName = 'nodeName';
        $description ? $this->description = $description : $this->description = 'description';
        $expression ? $this->expression : $this->expression = 'expression';
        $value ? $this->value = $value: $this->value = 'value';
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

}