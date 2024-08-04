<?php

declare(strict_types=1);

namespace App\Domain\InputTree;

use App\Service\CsvDataFileReader;

class Tree
{
    private $tree = [];

    public function __construct(
        private CsvDataFileReader $reader,
    ) {}

    public function loadTree(string $fileName): void
    {
        $data = $this->reader->readData($fileName);

        foreach ($data as $csvString) {
            $nodeData = str_getcsv($csvString, ';');
            $this->addNode(new Node(
                $nodeData[0],
                $nodeData[1],
                $nodeData[2],
                $nodeData[3],
            ));
        }
    }

    public function addNode(Node $node): void
    {
        $this->tree[$node->getItemName()] = $node;
    }

    public function getNodes(): array
    {
        return $this->tree;
    }
}
