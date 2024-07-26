<?php

namespace Differ\Formaters;

use function Differ\Formaters\Stylish\formatStylish;
use function Differ\Formaters\Plain\formatPlain;
use function Differ\Formaters\Json\formatJson;

function formatResult(array $tree, string $format): string
{
    switch ($format) {
        case 'stylish':
            return formatStylish($tree);
        case 'plain':
            return formatPlain($tree);
        case 'json':
            return formatJson($tree);
        default:
            throw new \Exception("{$format} is invalid format");
    }
}
