<?php

declare(strict_types=1);

namespace App\Tests\Utils;

trait CompareFiles
{
    public function compare(string $exampleFileName, string $fileName)
    {
        $exampleFile = fopen($exampleFileName, 'r');
        $fileUnderTest = fopen($fileName, 'r');

        $exampleData = [];
        $testData = [];

        while ($dataString = fgets($exampleFile)) {
            $exampleData[] = $dataString;
        }

        while ($dataString = fgets($fileUnderTest)) {
            $testData[] = $dataString;
        }

        foreach ($exampleData as $index => $exampleString) {
            $this->assertEquals($exampleString, $testData[$index]);
        }
    }
}
