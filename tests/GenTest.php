<?php

require __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

use Kasiss\PhpExcelParser\Generators\Factory;
use Kasiss\PhpExcelParser\Generators\GenHub;

class GenTest extends TestCase 
{


    public function testGenHub() {
        $bGen = Factory::createBoundaryGen(0.1,1,0.1);
        $sGen = Factory::createStepGen([1,10,100]);
        $sGen2 = Factory::createStepGen([1,10,100,1000]);

        $genhub = new GenHub(...[$bGen,$sGen,$sGen2]);
        $n=0;
        foreach($genhub as $gg) {
            $n++;
        }
        $this->assertEquals($genhub->total(),$n);
    }

    public function testGenHub1() {
        $bGen = Factory::createBoundaryGen(1,3,1);
        $genhub = new GenHub(...[$bGen]);
        $n=0;
        foreach($genhub as $gg) {
            $n++;
        }
        $this->assertEquals($genhub->total(),$n);
    }
}