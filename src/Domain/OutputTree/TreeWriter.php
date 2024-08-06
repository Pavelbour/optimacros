<?php

declare(strict_types=1);

namespace App\Domain\OutputTree;

use App\Domain\OutputTree\StateMachine\StateMachine;
use Symfony\Component\Serializer\SerializerInterface;

class TreeWriter
{
    private const string DEFAULT_FILE_NAME = '/app/data/output.json';

    public function __construct(
        private StateMachine $stateMachine,
    ) {
    }

    public function writeTree(Tree $tree, string $fileName = self::DEFAULT_FILE_NAME): void
    {
        $stringsToWrite = $this->stateMachine->start($tree);
        $file = fopen($fileName, 'w');
        foreach ($stringsToWrite as $string) {
            fputs($file, $string);
        }
    }
        
}
