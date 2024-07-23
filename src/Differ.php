<?php

namespace Differ\Differ;

function genDiff(string $file1, string $file2): string
{
    $data1 = json_decode(file_get_contents($file1), true);
    $data2 = json_decode(file_get_contents($file2), true);

    $keys = array_keys(array_merge($data1, $data2));
    sort($keys);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Invalid JSON format in one of the files.');
    }

    $diff = array_map(function ($key) use ($data1, $data2) {
        $oldValue = $data1[$key] ?? null;
        $newValue = $data2[$key] ?? null;

        if ($oldValue === $newValue) {
            return "    $key: " . json_encode($oldValue);
        }

        $lines = [];

        if ($oldValue !== null) {
            $lines[] = " - $key: " . json_encode($oldValue);
        }

        if ($newValue !== null) {
            $lines[] = " + $key: " . json_encode($newValue);
        }
        return $lines;
    }, $keys);

    $flatDiff = [];
    foreach ($diff as $item) {
        if (is_array($item)) {
            $flatDiff = array_merge($flatDiff, $item);
        } else {
            $flatDiff[] = $item;
        }
    }

    $output = implode("\n", $flatDiff);
    return "{\n$output\n}";
}
