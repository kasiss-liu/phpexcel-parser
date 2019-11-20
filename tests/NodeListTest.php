<?php

require __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

use Kasiss\PhpExcelParser\Map\Mapping;
use Kasiss\PhpExcelParser\Map\Loader;
use Kasiss\PhpExcelParser\Map\NodeList;

class NodeListTest extends TestCase 
{

    public function createData() {
        $mapping = new Mapping('node','real','desc');
        $data = [];
        for($i = 1; $i <= 10; $i++) {
            $tmp = ['node'=>'A'.$i,'real'=>'test'.$i,"desc"=>"desc ".$i,"value"=>'',"expression"=>''];
            $data[] = $tmp;
        }
        return ['data'=>$data ,'mapping'=> $mapping];
    }

    public function testNodeList() {
        $testData = $this->createData();
        $testingData = $testData['data'];
        $testingMapping = $testData['mapping'];

        $nodeList = Loader::importArray($testingData,$testingMapping);
  
        $this->assertTrue($nodeList instanceof NodeList);
        $this->assertEquals(10,$nodeList->len());
        
        $node = $nodeList['test1'];
        $this->assertEquals("test1",$node->realName);
        $this->assertEquals("A1",$node->nodeName);
        $this->assertEquals("desc 1",$node->description);

        $node2 = $nodeList->get('test2');
        $this->assertEquals("test2",$node2->realName);
        $this->assertEquals("A2",$node2->nodeName);
        $this->assertEquals("desc 2",$node2->description);

        $node3 = $nodeList->test3;
        $this->assertEquals("test3",$node3->realName);
        $this->assertEquals("A3",$node3->nodeName);
        $this->assertEquals("desc 3",$node3->description);
        
        $this->assertTrue(is_array($node3->toArray()));
        $this->assertTrue(is_array($nodeList->toArray()));

    }
}