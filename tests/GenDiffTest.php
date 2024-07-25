<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        $this->fixturesDir = __DIR__ . '../../src/';
    }

    public function testGenDiffWithValidJsonFiles(): void
    {
        $file1 = $this->fixturesDir . 'file1.json';
        $file2 = $this->fixturesDir . 'file2.json';

        $expected = trim(<<<'EOD'
{
  - follow: false
    host: "hexlet.io"
  - proxy: "123.234.53.22"
  - timeout: 50
  + timeout: 20
  + verbose: true
}
EOD);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithNonExistentJsonFile(): void
    {
        $file1 = $this->fixturesDir . 'file1.json';
        $file2 = $this->fixturesDir . 'test_non_existent.json';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error reading one of the files.');

        genDiff($file1, $file2);
    }

    public function testGenDiffWithValidYamlFiles(): void
    {
        $file1 = $this->fixturesDir . 'filepath1.yml';
        $file2 = $this->fixturesDir . 'filepath2.yml';

        $expected = trim(<<<'EOD'

{
  - follow: false
    host: "hexlet.io"
  - proxy: "123.234.53.22"
  - timeout: 50
  + timeout: 20
  + verbose: true
}
EOD);

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithNonExistentYamlFile(): void
    {
        $file1 = $this->fixturesDir . 'filepath1.yml';
        $file2 = $this->fixturesDir . 'test_non_existent.yml';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error reading one of the files.');

        genDiff($file1, $file2);
    }
}
