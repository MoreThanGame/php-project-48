<?php

use PHPUnit\Framework\TestCase;

class GenDiffTest extends TestCase
{
    protected $file1;
    protected $file2;
    protected $file3;

    protected function setUp(): void
    {
        // Путь к директории с фиктурами
        $fixturesDir = __DIR__ . '/fixtures/';

        // Путь к файлам фиктур
        $this->file1 = $fixturesDir . 'test_file1.json';
        $this->file2 = $fixturesDir . 'test_file2.json';
        $this->file3 = $fixturesDir . 'test_file3.json';
    }

    public function testCompareEqualJsonFiles()
    {
        $result = genDiff($this->file1, $this->file1);
        $this->assertJsonStringEqualsJsonString(json_encode([]), $result);
    }

    public function testCompareDifferentJsonFiles()
    {
        $expected = json_encode([
            "city" => "Changed",
        ]);

        $result = genDiff($this->file1, $this->file2);
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testCompareJsonFileWithAddedAndRemovedKeys()
    {
        $expected = json_encode([
            "name" => "Added",
            "age" => "Added",
            "city" => "Added",
            "address" => "Removed",
        ]);

        $result = genDiff($this->file1, $this->file3);
        $this->assertJsonStringEqualsJsonString($expected, $result);
    }

    public function testCompareJsonFileWithEmptyFile()
    {
        $emptyFile = __DIR__ . '/fixtures/test_empty.json';
        file_put_contents($emptyFile, '');

        $expected = json_encode([
            "name" => "Added",
            "age" => "Added",
            "city" => "Added",
        ]);

        $result = genDiff($this->file1, $emptyFile);
        $this->assertJsonStringEqualsJsonString($expected, $result);

        unlink($emptyFile);
    }

    public function testCompareJsonFileWithInvalidJson()
    {
        $invalidJsonFile = __DIR__ . '/fixtures/test_invalid.json';
        file_put_contents($invalidJsonFile, '{name: "John", "age": 30, "city": "New York"}'); // Invalid JSON

        $this->expectException(\Exception::class);
        genDiff($this->file1, $invalidJsonFile);

        unlink($invalidJsonFile);
    }
}
