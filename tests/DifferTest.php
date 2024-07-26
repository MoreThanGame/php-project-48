<?php

namespace Differ\Tests\DifferTest;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @param string $fixtureName
     * @return string
     */
    private function getFixtureFullPath($fixtureName)
    {
        return __DIR__ . "/fixtures/" . $fixtureName;
    }

    /**
     * @dataProvider mainProvider
     * @param string $file1
     * @param string $file2
     * @param string $expectedResult
     * @param string $format
     * @return void
     */
    public function testTwoGendiffs($file1, $file2, $format, $expectedResult)
    {
        $fixture1 = $this->getFixtureFullPath($file1);
        $fixture2 = $this->getFixtureFullPath($file2);
        $diffResult = file_get_contents($this->getFixtureFullPath($expectedResult));
        $this->assertEquals($diffResult, genDiff($fixture1, $fixture2, $format));
    }

    /**
     * @return array<int, array<int, string>>
     */
    public static function mainProvider()
    {
        return [
            [
                'file1.json',
                'file2.json',
                'stylish',
                'result_stylish.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'stylish',
                'result_stylish.txt'
            ],
            [
                'file1.json',
                'file2.json',
                'plain',
                'result_plain.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'plain',
                'result_plain.txt'
            ],
            [
                'file1.json',
                'file2.json',
                'json',
                'result_json.txt'
            ],
            [
                'file1.yaml',
                'file2.yaml',
                'json',
                'result_json.txt'
            ],
        ];
    }
}
