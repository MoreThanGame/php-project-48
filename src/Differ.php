<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Builder\build;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Stylish\toString;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\formatTree;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish')
{
    $firstFile = parse($pathToFile1);
    $secondFile = parse($pathToFile2);
    $files = build($firstFile, $secondFile);

    return formatTree($format, $files);
}
