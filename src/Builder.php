<?php

namespace Differ\Builder;

use Functional;

function build(object $firstFile, object $secondFile): array
{
    $keys = Functional\sort(array_unique(array_merge(
        array_keys(get_object_vars($firstFile)),
        array_keys(get_object_vars($secondFile))
    )), function ($key, $key2) {
        return $key <=> $key2;
    });

    $tree = array_map(function ($key) use ($firstFile, $secondFile) {
        if (isset($firstFile->$key) && isset($secondFile->$key)) {
            if ($secondFile->$key === $firstFile->$key) {
                return [
                    'name' => $key,
                    'status' => 'not changed',
                    'value' => $secondFile->$key
                ];
            } elseif ($secondFile->$key !== $firstFile->$key && (!is_object($firstFile->$key) || !is_object($secondFile->$key))) {
                return [
                    'name' => $key,
                    'status' => 'changed',
                    'newValue' => $secondFile->$key,
                    'oldValue' => $firstFile->$key
                ];
            } elseif (is_object($firstFile->$key) && is_object($secondFile->$key)) {
                return [
                    'name' => $key,
                    'status' => 'nested',
                    'child' => build($firstFile->$key, $secondFile->$key)
                ];
            }
        } elseif (!isset($firstFile->$key)) {
            return [
                'name' => $key,
                'status' => 'added',
                'value' => $secondFile->$key
            ];
        } elseif (!isset($secondFile->$key)) {
            return [
                'name' => $key,
                'status' => 'removed',
                'value' => $firstFile->$key
            ];
        }

        return null; // Возвращаем null для фильтрации пустых значений
    }, $keys);

    return array_filter($tree); // Удаляем элементы с null значением
}
