<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine;

use App\Domain\OutputTree\Node;
use App\Domain\OutputTree\StateMachine\State\ArrayStartState;
use App\Domain\OutputTree\Tree;

class StateMachine
{
    private const string ROOT_NODE_ITEM_NAME = 'Total';

    public function start(Tree $tree): array
    {
        $result = [];
        $zeroNode = new Node('Zero');
        $rootNode = $tree->getNode(self::ROOT_NODE_ITEM_NAME);
        $zeroNode->addChild($rootNode);
        $rootNode->addParent($zeroNode);
        $currentState = new ArrayStartState($zeroNode, 0);
        $result = array_merge($result, $currentState->getStrings());

        

        while ($currentState = $currentState->next()) {
            $result = array_merge($result, $currentState->getStrings());
        }

        return $result;
    }
}
