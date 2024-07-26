<?php

namespace Differ\Formaters\Stylish;

function convertToStr(mixed $value, string $indent)
{
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_array($value)) {
        $keys = array_keys($value);
        $newIndent = "$indent    ";
        $lines = array_map(
            function ($key) use ($value, $newIndent) {
                $normalizedValue = convertToStr($value[$key], $newIndent);
                return "{$newIndent}    {$key}: {$normalizedValue}";
            },
            $keys
        );
        $result = ["{", ...$lines, "{$newIndent}}"];
        return implode("\n", $result);
    }
    return (string) $value;
}

function walkTree(array $tree, int $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $lines = array_map(
        function ($node) use ($indent, $depth) {
            ['action' => $action, 'key' => $key] = $node;
            $normalizedValue1 = array_key_exists('value1', $node)
                ? convertToStr($node['value1'], $indent)
                : '';
            switch ($action) {
                case 'nested':
                    return "{$indent}    {$key}: " . walkTree($node['children'], $depth + 1);
                case 'same':
                    return "{$indent}    {$key}: {$normalizedValue1}";
                case 'added':
                    return "{$indent}  + {$key}: {$normalizedValue1}";
                case 'removed':
                    return "{$indent}  - {$key}: {$normalizedValue1}";
                case 'changed':
                    $value2 = $node['value2'];
                    $normalizedValue2 = convertToStr($value2, $indent);
                    return "{$indent}  - {$key}: {$normalizedValue1}\n{$indent}  + {$key}: {$normalizedValue2}";
                default:
                    throw new \Exception("Unknown node status: {$action}" . print_r($node, true));
            }
        },
        $tree
    );
    $result = ["{", ...$lines, "{$indent}}"];
    return implode("\n", $result);
}

function formatStylish(array $tree)
{
    return walkTree($tree);
}
