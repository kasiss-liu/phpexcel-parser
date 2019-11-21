<?php

require __DIR__."/../vendor/autoload.php";


use PHPUnit\Framework\TestCase;
use Kasiss\PhpExcelParser\Parse\ExcelSheets;
use Kasiss\PhpExcelParser\Map\Mapping;
use Kasiss\PhpExcelParser\Map\Loader;
use Kasiss\PhpExcelParser\Map\NodeList;


class ParseExcelTest extends TestCase
 {
    public function ParseExcel()
    {
        $file = __DIR__."/tests_data/testing.xlsx";
        $excel = ExcelSheets::loadExcel($file);
        $excel->getSheetByName('测试')->setCellValue('A1',2);
        $valueCalc = $excel->getSheetByName('控制台')->getCell('A1')->getCalculatedValue();
        
        $this->assertEquals(103,$valueCalc);
        $valueExpression = $excel->getSheetByName('控制台')->getCell('A1')->getValue();
        $this->assertEquals("=测试!A1+测试!B1",$valueExpression);
    }

    function createInputNodeData() {
        $mapping = new Mapping('node','real','desc','expr','v');
        $data = [];
        for($i = 1; $i <= 10; $i++) {
            $tmp = ['node'=>'测试!A'.$i,'real'=>'test'.$i,"desc"=>"desc ".$i,'expr'=>strval($i*10)];
            $data[] = $tmp;
        }
        $data[] = ['node'=>'控制台!A11','real'=>'test11',"desc"=>"desc 11","expr"=>"=控制台!A1*2"];
        $data[] = ['node'=>'控制台!A12','real'=>'test12',"desc"=>"desc 12","expr"=>"0"];
        return ['data'=>$data ,'mapping'=> $mapping];
    }

    function createCalcNodeData() {
        $mapping = new Mapping('node','real','desc','expr','v');
        $data = [];
        for($i = 1; $i <= 10; $i++) {
            $tmp = ['node'=>'控制台!A'.$i,'real'=>'test'.$i,"desc"=>"desc ".$i];
            $data[] = $tmp;
        }
        $data[] = ['node'=>'控制台!A11','real'=>'test11',"desc"=>"desc 11"];
        $data[] = ['node'=>'控制台!A13','real'=>'test13',"desc"=>"desc 13"];
        return ['data'=>$data ,'mapping'=> $mapping];
    }

    /**
     * @test
     */
    public function CalcExcel()
    {

        $inputNode = $this->createInputNodeData();
        $calcNode = $this->createCalcNodeData();

        $testingInputData = $inputNode['data'];
        $testingMappingData = $inputNode['mapping'];
        $inputNode = Loader::importArray($testingInputData,$testingMappingData);
        $testingCalcData = $calcNode['data'];
        $testingCalcMappingData = $calcNode['mapping'];
        $calcNode = Loader::importArray($testingCalcData,$testingCalcMappingData);

        $file = __DIR__."/tests_data/testing.xlsx";
        $excel = new ExcelSheets($file);
        $excel->loadInitVars($inputNode);
        $excel->loadCalcVars($calcNode);

        $result = $excel->doCalcValue();
        $this->assertEquals(111,$result['test1']['value']);

        $this->assertEquals(222,$result['test11']['value']);

        $this->assertEquals(13,$inputNode->cloneMerge($calcNode)->len());
    }




 }