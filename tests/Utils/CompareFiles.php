<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use Throwable;

trait CompareFiles
{
    private const string FILE_OPEN_ERROR_MESSAGE = 'File %s not found.';

    public function compare(string $exampleFileName, string $fileName)
    {
        try {
        $exampleFile = fopen($exampleFileName, 'r');
        } catch (Throwable $t) {
            echo sprintf(self::FILE_OPEN_ERROR_MESSAGE, $fileName);
            exit(1);
        }
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

        fclose($exampleFile);
    }
}
