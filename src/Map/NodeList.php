<?php

namespace Kasiss\PhpExcelParser\Map;

class NodeList implements \arrayaccess{
    
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

    public function getList() {
        return $this->list;
    }

    public function toArray() {
        $d = [];
        foreach($this->list as $node) {
            $d[] = $node->toArray();
        }
        return $d;
    }

    public function toJson() {
        $d = $this->toArray(); 
        return json_encode($d,JSON_UNESCAPED_UNICODE);
    }

    public function get($nodeKey) {
        return isset($this->list[$nodeKey]) ? $this->list[$nodeKey] : null;
    }

    public function __get($nodeKey) {
        return isset($this->list[$nodeKey]) ? $this->list[$nodeKey] : null;
    }

    public function cloneMerge(NodeList $nodeList) {
        $newNodeList = $this->clone();
        $newInNodeList = $nodeList->clone();
        return $this->merge($newNodeList,$newInNodeList);
    }

    private function merge($nodeList1,$nodeList2) {
        foreach($nodeList2->getList() as $nodeKey => $node) {
            $nodeList1->set($nodeKey,$node->clone());
        }
        return $nodeList1;
    }
    
    public function clone() {
        $self = new self();
        return $this->merge($self,$this); 
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

    public function __clone() {
       return $this->clone();
    }

    public function __toString() {
        return $this->toJson();
    }
}