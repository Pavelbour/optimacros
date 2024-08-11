<?php

declare(strict_types=1);

namespace App\Domain\OutputTree;

use App\Domain\OutputTree\StateMachine\StateMachine;
use Throwable;

class TreeWriter
{
    private const string DEFAULT_FILE_NAME = '/app/data/output.json';
    private const string FILE_OPEN_ERROR_MESSAGE = 'Impossible to create file %s';

    public function __construct(
        private StateMachine $stateMachine,
    ) {
    }

    public function writeTree(Tree $tree, string $fileName = self::DEFAULT_FILE_NAME): void
    {
        $stringsToWrite = $this->stateMachine->start($tree);
        try {
            $file = fopen($fileName, 'w');
        } catch (Throwable $t) {
            echo sprintf(self::FILE_OPEN_ERROR_MESSAGE, $fileName);
            exit(1);
        }
        foreach ($stringsToWrite as $string) {
            fputs($file, $string);
        }
        fclose($file);
    }
        
}
