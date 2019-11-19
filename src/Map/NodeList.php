<?php

namespace Kasiss\PhpExcelParser\Map;

class NodeList  implements \arrayaccess{
    
    protected $list = [];

    public function set($nodeKey,Node $node) {
        $this->list[$nodeKey] = $node;
    }
    public function unset($nodeKey) {
        unsert($this->list[$nodeKey]);
    }
    public function len() {
        return count($this->list);
    }

    public function get($nodeKey) {
        return isset($this->list[$nodeKey]) ? $this->list[$nodeKey] : null;
    }

    public function __get($nodeKey) {
        return isset($this->list[$nodeKey]) ? $this->list[$nodeKey] : null;
    }

    public function offsetSet($offset,$value) {
        is_null($offset) ? $this->list[] = $value : $this->list[$offset] = $value;
    }

    public function offsetExists($offset) {
        return isset($this->list[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->list[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->list[$offset]) ? $this->list[$offset] : null;
    }

}