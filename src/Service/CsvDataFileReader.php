<?php

declare(strict_types=1);

namespace App\Service;

use Throwable;

class CsvDataFileReader
{
    private const string FILE_OPEN_ERROR_MESSAGE = 'Input file %s not found.';

    public function readData(string $fileName): array
    {
        $result = [];
        try {
            $file = fopen($fileName, 'r');
        } catch (Throwable $t) {
            echo sprintf(self::FILE_OPEN_ERROR_MESSAGE, $fileName);
            exit(1);
        }
        $dataString = '';

        $headers = fgets($file);
        while ($dataString = fgets($file)) {
            $result[] = $dataString;
        }

        fclose($file);

        return $result;
    }
}
