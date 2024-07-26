<?php

namespace Differ\Formatters\Stylish;

use Functional;

function stylish(array $treeOfFiles): array
{
    //print_r($treeOfFiles);

    $arrayOfDifferences = array_map(function ($node) {
        switch ($node) {
            case $node['status'] === 'not changed':
                return ["{$node['name']}" => $node["value"]];
            case $node['status'] === 'added':
                return ["+ {$node['name']}" => $node["value"]];
            case $node['status'] === 'removed':
                return ["- {$node['name']}" => $node["value"]];
            case $node['status'] === 'changed':
                return ["- {$node['name']}" => $node["oldValue"], "+ {$node['name']}" => $node["newValue"]];
            case $node['status'] === 'nested':
                return ["{$node['name']}" => stylish($node['child'])];
        }
    }, $treeOfFiles);


    $keys = Functional\flat_map($arrayOfDifferences, function ($elem) {
        return array_keys($elem);
    });

    $values = Functional\flat_map($arrayOfDifferences, function ($elem) {
        return $elem;
    });


    $res = array_combine($keys, $values);
    return $res;


    /*$res = array_reduce($treeOfFiles, function ($acc, $node) {
        switch ($node) {
            case $node['status'] === 'not changed':
                $acc["{$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'added':
                $acc["+ {$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'removed';
                $acc["- {$node['name']}"] = $node["value"];//Should not use of mutating operators
                break;
            case $node['status'] === 'changed':
                $acc["- {$node['name']}"] = $node["oldValue"];//Should not use of mutating operators
                $acc["+ {$node['name']}"] = $node["newValue"];//Should not use of mutating operators
                break;
            case $node['status'] === 'nested':
                $acc["{$node['name']}"] = stylish($node['child']);//Should not use of mutating operators
                break;
        }
        return $acc;
    }, []);
    //print_r($res);
    return $res;*/
}

function toString(array $formattedArray): string
{
    $formattedJson = (string) json_encode($formattedArray, JSON_PRETTY_PRINT);
    $unqoted = str_replace('"', '', $formattedJson);
    $uncommas = str_replace(",", "", $unqoted);
    $result = str_replace("  - ", "- ", str_replace("  + ", "+ ", $uncommas));
    return $result;
}
