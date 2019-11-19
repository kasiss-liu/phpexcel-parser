<?php

namespace Kasiss\PhpExcelParser\Parse;

use Kasiss\PhpExcelParser\Map\NodeList;

class ExcelSheets {

    protected $excel;
    protected $filePath;
    protected $initVars;
    protected $calculatedValues;

    /**
     * @param $excelFilePath string
     * @return null|\PhpOffice\PhpSpreadsheet\SpreadSheet
     */
    public static function loadExcel($excelFilePath) {
        if(!$excelFilePath) {
            return null;
        }
        if(!file_exists($excelFilePath)) {
            return null;
        }
        $excelsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelFilePath);
        return $excelsheet;
    }

    /**
     * @param $excelFilePath string
     */
    public function __construct($excelFilePath="") {
        if($excelFilePath) {
            $this->filePath = $excelFilePath;
            $this->excel = self::loadExcel($this->filePath);
        }
    }

    public function reset() {
        $this->excel = null;
        $this->filePath = "";
        $this->initVars = null;
        $this->calculatedValues = null;
    }

    /**
     * @param $excelFilePath string
     */
    public function load($excelFilePath) {
        $this->excel = self::loadExcel(excelFilePath);
        if(!$this->excel) {
            return false;
        }
        $this->filePath = $excelFilePath;
        return true;
    }

    /**
     * @param $nodeList NodeList
     * @return bool
     */
    public function loadInitVars($nodeList) {
        $this->initVars = $nodeList;
        if(!$this->excel) {
            return false;
        }
        foreach($this->initVars->getList() as $nodeKey => $node) {
            $this->excel->getSheetByName($node['sheetName'])->setCellValue($node['cellName'],$node['value']);
        }
        return true;
    }

    /**
     * @param $nodeList NodeList
     * @return bool
     */
    public function loadCalcVars($nodeList) {
        $this->calculatedValues = $nodeList;
    }

    public function doCalcValue(){
        foreach($this->calculatedValues->getList() as $nodeKey => $node) {
            $this->calculatedValues[$nodeKey]['expression'] = $this->excel->getSheetByName($node['sheetName'])->getCell($node['cellName'])->getValue();
            $this->calculatedValues[$nodeKey]['value'] = $this->excel->getSheetByName($node['sheetName'])->getCell($node['cellName'])->getCalculatedValue();
        }
        return $this->calculatedValues;
    }
    
}