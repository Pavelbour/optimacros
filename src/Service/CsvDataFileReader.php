<?php

declare(strict_types=1);

namespace App\Service;

class CsvDataFileReader
{
    public function readData(string $fileName): array
    {
        $result = [];
        $file = fopen($fileName, 'r');
        $dataString = '';

        fgets($file);
        while ($dataString = fgets($file)) {
            $result[] = $dataString;
        }

        return $result;
    }
}
