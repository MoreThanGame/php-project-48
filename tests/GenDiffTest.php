<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    private string $fixturesDir;

    protected function setUp(): void
    {
        $this->fixturesDir = __DIR__ . '/fixtures/';
    }

    public function testGenDiffWithValidFiles(): void
    {
        $file1 = $this->fixturesDir . 'test_file1.json';
        $file2 = $this->fixturesDir . 'test_file2.json';

        $expected = <<<'EOD'
{
  - follow: false
    host: "hexlet.io"
  - proxy: "123.234.53.22"
  - timeout: 50
  + timeout: 20
  + verbose: true
}
EOD;

        $this->assertEquals($expected, genDiff($file1, $file2));
    }

    public function testGenDiffWithNonExistentFile(): void
    {
        $file1 = $this->fixturesDir . 'test_file1.json';
        $file2 = $this->fixturesDir . 'test_non_existent.json';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error reading one of the files.');

        genDiff($file1, $file2);
    }
}
