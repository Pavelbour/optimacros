<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\InputTree\Tree as InputTree;
use App\Domain\OutputTree\Node;
use App\Domain\OutputTree\Tree as OutputTree;

class TreeProcessorService
{
    private OutputTree $outputTree;

    public function __construct()
    {
        $this->outputTree = new OutputTree();
    }

    public function processTree(InputTree $inputTree): OutputTree
    {
        foreach ($inputTree->getNodes() as $node) {
            $this->outputTree->addNode(new Node($node->getItemName()));
        }

        $this->processChildren($inputTree->getNodes());
        $this->processDirectComponent($inputTree);
        
        return $this->outputTree;
    }
    
    private function processChildren(array $nodes): void
    {
        foreach ($nodes as $node) {
            if ($node->getParent() != '') {
                $parent = $this->outputTree->getNode($node->getParent());
                $child = $this->outputTree->getNode($node->getItemName());
                
                $child->addParent($parent);
                $parent->addChild($child);
            }
       } 
    }

    private function processDirectComponent(InputTree $inputTree): void
    {
        $nodes = $inputTree->getIndex()['directComponentIndex'];

        foreach ($nodes as $node) {
            $relation = $node->getRelation();
            if ($relation) {
                $children = $this->outputTree
                                ->getNode($relation)
                                ->getChildren();

                $parent = $this->outputTree->getNode($node->getItemName());
                foreach ($children as $child) {
                    $parent->addChild($child);
                }
            }
        }
    }
}
