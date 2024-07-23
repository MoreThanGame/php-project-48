<?php

namespace Differ\Differ;

function genDiff(string $file1, string $file2): string
{
    // Получаем содержимое файлов
    $content1 = file_get_contents($file1);
    $content2 = file_get_contents($file2);

    // Проверяем на ошибки чтения файлов
    if ($content1 === false || $content2 === false) {
        throw new \Exception('Error reading one of the files.');
    }

    // Декодируем JSON содержимое в массивы
    $data1 = json_decode($content1, true);
    $data2 = json_decode($content2, true);

    // Проверяем на ошибки декодирования JSON
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Invalid JSON format in one of the files.');
    }

    // Обеспечиваем, что $data1 и $data2 являются массивами
    $data1 = is_array($data1) ? $data1 : [];
    $data2 = is_array($data2) ? $data2 : [];

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
            $lines[] = " - $key: " . json_encode($oldValue);
        }

        if ($newValue !== null) {
            $lines[] = " + $key: " . json_encode($newValue);
        }

        return $lines;
    }, $keys);

    // Преобразуем массив строк в плоский массив и затем объединяем их в одну строку
    $flatDiff = array_merge(...array_filter($diff, 'is_array'));

    $output = implode("\n", $flatDiff);
    return "{\n$output\n}";
}
