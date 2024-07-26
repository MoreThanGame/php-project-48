<?php

namespace Differ\Formaters\Json;

function formatJson(array $tree)
{
    return json_encode($tree);
}
