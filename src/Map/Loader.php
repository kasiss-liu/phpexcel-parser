<?php

namespace Kasiss\PhpExcelParser\Map;

class Loader {
    
    public static function importJson(string $json) {
        $data = json_decode($json,true);
        if(!$data || !is_array($data)) {
            return false;
        }
        return self::importArray($data);
    }

    public static function importArray(array $arr,$mapping=null) {
        if(!is_array($arr)) {
            return false;
        }
        $nodeName = 'nodeName';
        $realName = 'realName';
        $description = 'description';
        $expression = 'expression';
        $value = 'value';

        if($mapping) {
            isset($mapping['nodeName']) && $mapping['nodeName'] && $nodeName = $mapping['nodeName'];
            isset($mapping['realName']) && $mapping['realName'] && $realName = $mapping['realName'];
            isset($mapping['description']) && $mapping['description'] && $description = $mapping['description'];
            isset($mapping['expression']) && $mapping['expression'] && $expression = $mapping['expression'];
            isset($mapping['value']) && $mapping['value'] && $value = $mapping['value'];
        }
        $nodeList = new NodeList();
        foreach($arr as $v) {
            if(!isset($v[$nodeName]) || !isset($v[$realName]) || !isset($v[$description])) {
                continue;
            }
            if(!isset($v[$expression])) {
                $v[$expression] = null;
            }
            if(!isset($v[$value])) {
                $v[$value] = null;
            }
            $node = Node::create($v[$nodeName],$v[$realName],$v[$description],$v[$expression],$v[$value]);
            $nodeList->set($v[$realName],$node);
        }
        return $nodeList;
    }


    public static function loadNodeDefinedJsonFile($mappingFilePath,$assocKey = "") {
        if(!file_exists($mappingFilePath)) {
            return false;
        }
        $contents = \file_get_contents($mappingFilePath);
        $jsonArr = \json_decode($contents,1);
        if(!$jsonArr) {
            return false;
        }
        if($assocKey) {
            $jsonArr = array_column($jsonArr,null,$assocKey);
        }
        return $jsonArr;
    }

    public static function loadNodeDefinedMultiJsonFile($assocKey, ...$mapFiles) {
        $data = [];
        foreach($mapFiles as $key => $filePath) {
            $data[$key] = Loader::loadNodeDefinedJsonFile($filePath,null);
        }
        $data = array_merge(...$data);
        if($assocKey) {
            $data = array_column($data,null,$assocKey);
        }
        return $data;
    }


    
}