<?php

namespace Kasiss\PhpExcelParser\DataMaker;

use Kasiss\PhpExcelParser\Map\NodeDefine;
use Kasiss\PhpExcelParser\Map\Loader;
use Kasiss\PhpExcelParser\Parse\ExcelSheets;
use Kasiss\PhpExcelParser\Generators\GenHub;


class Maker {
    
    protected $excelStore;

    protected $mappingDir;

    protected $outputDir;
    
    protected $assocKey;

    protected $version;

    protected $saveHandler;

    protected $outputFileName;

    public function __construct($excelBasePath,$mappingDir,$outputDir,$assocKey,$version) {
        $this->excelStore = $excelBasePath;
        $this->mappingDir = $mappingDir;
        $this->outputDir = $outputDir;
        $this->version = $version;
        $this->assocKey = $assocKey;
        $this->setSaveHandler($this->saveFileHandler());
    }

    public function produceData($excelName,$inputNodeFiles,$outputNodeFiles,GenHub $genhub,$hubMap) {

        if($genhub->count() != count($hubMap)) {
            return false;
        }

        $iNodeFiles = $this->checkAndCompleteFilePath($this->mappingDir,$inputNodeFiles);
        $oNodeFiles = $this->checkAndCompleteFilePath($this->mappingDir,$outputNodeFiles);

        $inputMapping = new NodeDefine($iNodeFiles,$this->assocKey);
        $inputNodes = $inputMapping->data();
        
        $outputMapping = new NodeDefine($oNodeFiles,$this->assocKey);
        $outputNodes = $outputMapping->data();

        $inputNodeList = Loader::importArray($inputNodes);
        $outputNoddeList = Loader::importArray($outputNodes);
        $outputNoddeList = $inputNodeList->cloneMerge($outputNoddeList);

        $excel = new ExcelSheets($this->excelStore."/".$excelName);
        $excel->loadCalcVars($outputNoddeList);

        foreach($genhub as $vars) {
               
            $params = explode('_',$vars);
            foreach($params as $k=>$v) {
                $inputNodes[$hubMap[$k]]['expression'] = $v;
            }
    
            $inputNodeList = Loader::importArray($inputNodes);
            
            $excel->loadInitVars($inputNodeList);
            $nodeList = $excel->doCalcValue();

            call_user_func_array($this->saveHandler,[$nodeList,$excelName,$this->outputDir,$this->version,$this->outputFileName]);
        }
       
    }

    private function checkAndCompleteFilePath($prefix,$checkData) {
        $return = [];
        foreach($checkData as $file) {
            if(\file_exists($file)) {
                $return[] = $file;
                continue;
            }
            $fileComplete = $prefix."/".$file;
            if(\file_exists($fileComplete)) {
                $return[] = $fileComplete;
                continue;
            }
        }
        return $return;
    }

    private function saveFileHandler() {
        return function ($line,$excelName,$outputDir,$version,$outputFileName) {
            $excelNames = explode("/",$excelName);
            $dotNodes = explode(".",$excelNames[count($excelNames)-1]);
            array_pop($dotNodes);
            $realname = implode(".",$dotNodes);
            $versionDir = $outputDir."/".$version;
            $filename = $outputFileName ? $outputFileName : $versionDir."/".$realname.".dat";
            if(!is_dir($versionDir)) {
                mkdir($versionDir);
            }
            if(!is_dir($versionDir)) {
                throw new \Exception("mkdir failed");
            }
            \file_put_contents($filename,strval($line).PHP_EOL,FILE_APPEND);
        };
    }

    public function setSaveHandler($fn) {
        if(! $fn instanceof \Closure ) {
            throw new \Exception("fn is not a Closure");
        }
        $this->saveHandler = $fn;
    }

    public function setOutputFileName($filename) {
        $this->outputFileName = $filename;
    }


}