<?php

namespace Differ\Tests;

require_once __DIR__ . '/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $file1 = 'file1.json';
        $file2 = 'file2.json';

        $result = file_get_contents(__DIR__ . '/fixtures/diffs.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }

    public function testYaml(): void
    {
        $file1 = 'file1.yml';
        $file2 = 'file2.yml';

        $result = file_get_contents(__DIR__ . '/fixtures/diffs.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }

    public function testNestedJson(): void
    {
        $file1 = 'nested1.json';
        $file2 = 'nested2.json';

        $result = file_get_contents(__DIR__ . '/fixtures/nested.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }

    public function testNestedYaml(): void
    {
        $file1 = 'nested1.yml';
        $file2 = 'nested2.yml';

        $result = file_get_contents(__DIR__ . '/fixtures/nested.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'stylish'));
    }

    public function testPlain(): void
    {
        $file1 = 'nested1.yml';
        $file2 = 'nested2.yml';

        $result = file_get_contents(__DIR__ . '/fixtures/plainRes.txt');
        $this->assertEquals($result, genDiff($file1, $file2, 'plain'));
    }
}
