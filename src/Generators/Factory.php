<?php

namespace Kasiss\PhpExcelParser\Generators;

class Factory {

    public static function createBoundaryGen($start,$end,$step,$equal=0) {
        return new Boundary($start,$end,$step,$equal);
    }

    public static function createStepGen($stepData) {
        return new Step($stepData);
    }
}