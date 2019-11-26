<?php

namespace Kasiss\PhpExcelParser\Generators;


class Boundary implements \Iterator {

    protected $start;
    protected $end;
    protected $step;
    protected $val;
    protected $key = 0;
    protected $equal = 0;

    public function __construct($start,$end,$step,$equal=0) {
        $this->start = ($equal < 0) ?  ($start + $step) : $start;
        $this->end = $end;
        $this->step = $step;
        $this->equal = $equal;

    }

    public function total() {
        return floor(($this->end - $this->start) / $this->step);
    }

    //重置对象属性 遍历开始时被调用
    public function rewind() {
        $this->val = $this->start;
    }   
    //验证是否要终止遍历
    public function valid() {
        return ($this->equal === 0) ? ($this->val <= $this->end) : ($this->val < $this->end);
    }   
    //遍历指针移动时调用
    public function next() {
        $this->val += $this->step; 
        $this->key ++;
    }   
    //获取当前值
    public function current() {
        return $this->val;
    }   
    //获取当前key
    public function key() {
        return $this->key;
    }   

}