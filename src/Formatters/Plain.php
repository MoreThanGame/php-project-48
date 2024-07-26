<?php

namespace Differ\Formatters\Plain;

function plain(array $mergedTree, string $accName = ''): string
{
    $lines = array_map(function ($elem) use ($accName) {
        $name = $elem['name'];
        if ($elem['status'] === 'nested') {
            //$accName .= $name . '.';
            return plain($elem['child'], "{$accName}{$name}.");
        } elseif ($elem['status'] === 'added') {
            $value = is_object($elem['value']) ? '[complex value]' : var_export($elem['value'], true);
            return "Property '{$accName}{$elem['name']}' was added with value: {$value}";
        } elseif ($elem['status'] === 'removed') {
            return "Property '{$accName}{$elem['name']}' was removed";
        } elseif ($elem['status'] === 'changed') {
            $oldVal = is_object($elem['oldValue']) ? '[complex value]' : var_export($elem['oldValue'], true);
            $oldValRes = $oldVal === 'NULL' ? 'null' : $oldVal;
            $newVal = is_object($elem['newValue']) ? '[complex value]' : var_export($elem['newValue'], true);
            $newValRes = $newVal === 'NULL' ? 'null' : $newVal;
            return "Property '{$accName}{$elem['name']}' was updated. From {$oldValRes} to {$newValRes}";
        } elseif ((string) $elem['status'] === 'removed') {
            return "Property '{$accName}{$elem['name']}' was removed";
        };
    }, $mergedTree);

    $linesRes = array_filter($lines, fn($line) => $line);//claer clean lines from $lines array with value not changed.
    return implode("\n", $linesRes);
}
