<?php

namespace Kasiss\PhpExcelParser\Generators;


class Step implements \Iterator {

    protected $data = [];
    protected $total = 0;
    protected $currentNum = 0;

    public function __construct($stepData) {
        $this->data = $stepData;
        $this->total = count($this->data);
    }

    //重置对象属性 遍历开始时被调用
    public function rewind() {
        $this->currentNum = 0;
        reset($this->data);
    }   
    //验证是否要终止遍历
    public function valid() {
        return $this->currentNum < $this->total;
    }   
    //遍历指针移动时调用
    public function next() {
        $this->currentNum ++;
        next($this->data);
    }   
    //获取当前值
    public function current() {
        return $this->data[key($this->data)];
    }   
    //获取当前key
    public function key() {
        return key($this->data);
    } 

}