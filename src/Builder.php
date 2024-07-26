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

    /*$keys = Functional\sort($keys, function ($key, $key2) {
        return $key <=> $key2;
    });*/
    /*$keys->sort()->toArray();
    print_r($keys);

    /*$keys = array_values(sortBy($keys, function ($elem) {
        return $elem;
    }));*/
    //sort($keys);
    //print_r($keys);

    //$files = [];
    /*foreach ($keys as $key) {
        if (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key === $firstFile->$key //here. Changed non-stricted comparision to a strict one. It Works!
        ) {
            $files[$key] = [
                'name' => $key,
                'status' => 'not changed',
                'value' => $secondFile->$key
            ];
        } elseif (!property_exists($firstFile, $key)) {
            $files[$key] = [
                'name' => $key,
                'status' => 'added',
                'value' => $secondFile->$key
            ];
        } elseif (!property_exists($secondFile, $key)) {
            $files[$key] = [
                'name' => $key,
                'status' => 'removed',
                'value' => $firstFile->$key
            ];
        } elseif (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key !== $firstFile->$key && //here. Changed non-stricted comparision to a strict one.
            !(is_object($firstFile->$key)) || !(is_object($secondFile->$key))
        ) {
            $files[$key] = [
                'name' => $key,
                'status' => 'changed',
                'newValue' => $secondFile->$key,
                'oldValue' => $firstFile->$key
            ];
        } else {
            if (is_object($firstFile->$key) && is_object($secondFile->$key)) {
                $files[$key] = [
                    'name' => $key,
                    'status' => 'nested',
                    'child' => build($firstFile->$key, $secondFile->$key)
                ];
            }
        }
    }*/

    $tree = array_map(function ($key) use ($firstFile, $secondFile) {
        if (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key === $firstFile->$key //here. Changed non-stricted comparision to a strict one. It Works!
        ) {
            return [
                'name' => $key,
                'status' => 'not changed',
                'value' => $secondFile->$key
            ];
        } elseif (!property_exists($firstFile, $key)) {
            return [
                'name' => $key,
                'status' => 'added',
                'value' => $secondFile->$key
            ];
        } elseif (!property_exists($secondFile, $key)) {
            return [
                'name' => $key,
                'status' => 'removed',
                'value' => $firstFile->$key
            ];
        } elseif (
            property_exists($firstFile, $key) &&
            property_exists($secondFile, $key) &&
            $secondFile->$key !== $firstFile->$key && //here. Changed non-stricted comparision to a strict one.
            !(is_object($firstFile->$key)) || !(is_object($secondFile->$key))
        ) {
            return [
                'name' => $key,
                'status' => 'changed',
                'newValue' => $secondFile->$key,
                'oldValue' => $firstFile->$key
            ];
        } else {
            if (is_object($firstFile->$key) && is_object($secondFile->$key)) {
                return [
                    'name' => $key,
                    'status' => 'nested',
                    'child' => build($firstFile->$key, $secondFile->$key)
                ];
            }
        }
    }, $keys);
    //print_r($tree);
    //print_r($files);
    return $tree;
}
