<?php

declare(strict_types=1);

namespace App\Domain\InputTree;

use App\Service\CsvDataFileReader;

class TreeLoader
{
    public function __construct(
        private CsvDataFileReader $reader,
    ) {
    }

    public function loadTree(string $fileName): Tree
    {
        $tree = new Tree();
        $data = $this->reader->readData($fileName);

        foreach ($data as $csvString) {
            $nodeData = str_getcsv($csvString, ';');
            $tree->addNode(new Node(
                $nodeData[0],
                $nodeData[1],
                $nodeData[2],
                $nodeData[3],
            ));
        }

        return $tree;
    }
}
