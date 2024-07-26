<?php

namespace Differ\Formatters;

use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Stylish\toString;
use function Differ\Formatters\Json\json;

function formatTree(string $format, array $tree)
{
    if ($format === 'stylish') {
        return toString(stylish($tree));
    } elseif ($format === 'plain') {
        return plain($tree);
    } elseif ($format === 'json') {
        return json($tree);
    }
}
