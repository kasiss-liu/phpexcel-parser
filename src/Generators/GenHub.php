<?php

namespace Kasiss\PhpExcelParser\Generators;


class GenHub implements \Iterator {
    protected $gens;

    protected $endPointer = [];
    public function __construct(\Iterator ...$generators) {
        $this->gens = $generators;
        $this->endPointer = count($generators)-1;
    }

    //重置对象属性 遍历开始时被调用
    public function rewind() {
        foreach($this->gens as $gen) {
            $gen->rewind();
        }
    }   
    //验证是否要终止遍历
    public function valid() {
        $valid = true;
        $next = false;
        foreach($this->gens as $key => $gen) {
            if($next) {
                $gen->next();
                $next = false;
            }
            if(!$gen->valid()) {
                if($key == $this->endPointer) {
                    $valid = false;
                    break;
                }else{
                    $gen->rewind();
                    $next = true;
                }
            }
        }
        return $valid;
    }   
    //遍历指针移动时调用
    public function next() {
        foreach($this->gens as $gen) {
            if($gen->valid()) {
                $gen->next();
                break;
            }
        }
    }   
    //获取当前值
    public function current() {
        $current = "";
        foreach($this->gens as $gen) {
            $current .= $gen->current()."_";
        }
        return rtrim($current,"_");
    }   
    //获取当前key
    public function key() {
        $key = "";
        foreach($this->gens as $gen) {
            $key .= $gen->key()."_";
        }
        return rtrim($key,"_");
    }
    
    public function count() {
        return count($this->gens);
    }

    public function total() {
        $total = $this->count() > 0 ? 1 : 0;
        foreach($this->gens as $gen) {
            $total *= $gen->total();
        }
        return $total;
    }
    
}