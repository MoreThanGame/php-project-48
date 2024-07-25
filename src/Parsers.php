<?php

namespace Parsers;

use Symfony\Component\Yaml\Yaml;

function parser(string $file1, string $file2): array
{
    $ext1 = pathinfo($file1, PATHINFO_EXTENSION);
    $ext2 = pathinfo($file2, PATHINFO_EXTENSION);

    $content1 = file_get_contents($file1);
    $content2 = file_get_contents($file2);

    if ($content1 === false || $content2 === false) {
        throw new \Exception('Error reading one of the files.');
    }

    if ($ext1 === 'json' && $ext2 === 'json') {
        $array1 = json_decode($content1, true);
        $array2 = json_decode($content2, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON format in one of the files.');
        }
    } elseif (($ext1 === 'yml' || $ext1 === 'yaml') && ($ext2 === 'yml' || $ext2 === 'yaml')) {
        $array1 = Yaml::parse($content1, Yaml::PARSE_OBJECT_FOR_MAP);
        $array2 = Yaml::parse($content2, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new \Exception('Unsupported file formats. Both files should be either JSON or YAML.');
    }

    return [$array1, $array2];
}
