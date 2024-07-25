<?php

namespace Differ\Differ;

use function Parsers\parser;

function convertToArray(mixed $data): array
{
    if (is_object($data)) {
        $json = json_encode($data);
        if ($json === false) {
            throw new \RuntimeException('Error encoding object to JSON.');
        }
        $array = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Error decoding JSON to array: ' . json_last_error_msg());
        }
        return $array;
    }
    return $data;
}

function genDiff(string $file1, string $file2, string $format = 'stylish'): string
{
    [$data1, $data2] = parser($file1, $file2);

    $data1 = convertToArray($data1);
    $data2 = convertToArray($data2);

    // Объединяем и сортируем ключи из обоих массивов
    $keys = array_keys(array_merge($data1, $data2));
    sort($keys);

    // Формируем массив различий
    $diff = array_map(function ($key) use ($data1, $data2) {
        $oldValue = $data1[$key] ?? null;
        $newValue = $data2[$key] ?? null;

        if ($oldValue === $newValue) {
            return "    $key: " . json_encode($oldValue);
        }

        $lines = [];
        if ($oldValue !== null) {
            $lines[] = "  - $key: " . json_encode($oldValue);
        }

        if ($newValue !== null) {
            $lines[] = "  + $key: " . json_encode($newValue);
        }

        return implode("\n", $lines);
    }, $keys);

    // Объединяем строки различий в одну строку
    $output = implode("\n", array_filter($diff));
    return "{\n$output\n}";
}
