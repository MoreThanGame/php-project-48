<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private string $file1;
    private string $file2;
    private string $file3;

    protected function setUp(): void
    {
        // Путь к директории с фиктурами
        $fixturesDir = __DIR__ . '/fixtures/';

        // Путь к файлам фиктур
        $this->file1 = $fixturesDir . 'test_file1.json';
        $this->file2 = $fixturesDir . 'test_file2.json';
        $this->file3 = $fixturesDir . 'test_file3.json';
    }

    public function testCompareEqualJsonFiles(): void
    {
        $expected = <<<EOT
{
    host: "hexlet.io",
    timeout: 50,
    proxy: "123.234.53.22",
    follow: false
}
EOT;
        $result = genDiff($this->file1, $this->file1);
        $this->assertEquals($expected, $result);
    }

    public function testCompareDifferentJsonFiles(): void
    {
        $expected = <<<EOT
{
    - follow: false
    host: "hexlet.io"
    - proxy: "123.234.53.22"
    - timeout: 50
    + timeout: 20
    + verbose: true
}
EOT;
        $result = genDiff($this->file1, $this->file2);
        $this->assertEquals($expected, $result);
    }

    public function testCompareJsonFileWithAddedAndRemovedKeys(): void
    {
        $expected = <<<EOT
{
    - follow: false
    host: "hexlet.io"
    - proxy: "123.234.53.22"
    - timeout: 50
    + timeout: 20
    + verbose: true
}
EOT;
        $result = genDiff($this->file1, $this->file3);
        $this->assertEquals($expected, $result);
    }

    public function testCompareJsonFileWithEmptyFile(): void
    {
        $emptyFile = __DIR__ . '/fixtures/test_empty.json';
        file_put_contents($emptyFile, '{}');

        $expected = <<<EOT
{
    - follow: false
    host: "hexlet.io"
    - proxy: "123.234.53.22"
    - timeout: 50
}
EOT;
        $result = genDiff($this->file1, $emptyFile);
        $this->assertEquals($expected, $result);

        unlink($emptyFile);
    }

    public function testCompareJsonFileWithInvalidJson(): void
    {
        $invalidJsonFile = __DIR__ . '/fixtures/test_invalid.json';
        file_put_contents($invalidJsonFile, '{name: "John", "age": 30, "city": "New York"}'); // Invalid JSON

        $this->expectException(\Exception::class);
        genDiff($this->file1, $invalidJsonFile);

        unlink($invalidJsonFile);
    }
}
