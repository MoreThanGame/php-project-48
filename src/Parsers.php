<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getRealPath(string $filePath)
{
    $realFilePath = realpath($filePath);
    if ($realFilePath === false) {
        throw new \Exception("File not exists");
    }

    return $realFilePath;
}

function parseFile(string $filePath)
{
    $realFilePath = getRealPath($filePath);
    $fileExtension = pathinfo($realFilePath, PATHINFO_EXTENSION);
    $fileContent = file_get_contents($realFilePath);
    if ($fileContent === false) {
        throw new \Exception("Can't read file");
    }

    switch ($fileExtension) {
        case 'json':
            return json_decode($fileContent, true);
        case 'yml':
        case 'yaml':
            return Yaml::parse($fileContent);
        default:
            throw new \Exception("{$fileExtension} is invalid file format");
    }
}
