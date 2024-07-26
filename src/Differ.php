<?php

namespace Differ\Differ;

use function Functional\sort;
use function Differ\Formaters\formatResult;
use function Differ\Parsers\parseFile;

// function makeNode(string $key, string $action, mixed $value1 = null, mixed $value2 = null, array $children = null)
// {
//     return array_filter(
//         compact('key', 'action', 'value1', 'value2', 'children'),
//         fn ($value) => isset($value)
//     );
// }

function generateDiffTree(array $data1, array $data2)
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortedKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));
    $tree = array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data1)) {
            $node = ['key' => $key, 'action' => 'added', 'value1' => $data2[$key]];
        } elseif (!array_key_exists($key, $data2)) {
            $node = ['key' => $key, 'action' => 'removed', 'value1' => $data1[$key]];
        } else {
            if (is_array($data1[$key]) and is_array($data2[$key])) {
                $children = generateDiffTree($data1[$key], $data2[$key]);
                $node = ['key' => $key, 'action' => 'nested', 'children' => $children];
            } elseif ($data1[$key] === $data2[$key]) {
                $node = ['key' => $key, 'action' => 'same', 'value1' => $data1[$key]];
            } else {
                $node = [
                    'key' => $key,
                    'action' => 'changed',
                    'value1' => $data1[$key],
                    'value2' => $data2[$key],
                ];
            }
        }
        return $node;
    }, $sortedKeys);
    return $tree;
}

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $data1 = parseFile($pathToFile1);
    $data2 = parseFile($pathToFile2);
    $diffTree = generateDiffTree($data1, $data2);
    // print_r($diffTree);
    $result = formatResult($diffTree, $format);
    // print_r($result);
    return $result;
}
