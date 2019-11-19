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
        $realName = 'realname';
        $description = 'description';

        if($mapping) {
            isset($mapping['nodeName']) && $mapping['nodeName'] && $nodeName = $mapping['nodeName'];
            isset($mapping['realName']) && $mapping['realName'] && $realName = $mapping['realName'];
            isset($mapping['description']) && $mapping['description'] && $description = $mapping['description'];
        }

        $nodeList = new NodeList();
        foreach($arr as $v) {
            if(!isset($v[$nodeName]) || !isset($v[$realName]) || !isset($v[$description])) {
                continue;
            }
            $node = Node::create($v[$nodeName],$v[$realName],$v[$description]);
            $nodeList->set($v[$realName],$node);
        }
        return $nodeList;
    }
    
}