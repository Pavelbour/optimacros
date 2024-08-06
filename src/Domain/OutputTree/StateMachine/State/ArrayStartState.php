<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;
use App\Domain\OutputTree\Tree;

class ArrayStartState implements State
{
    use BaseState;

    public function __construct(
        private Node $node,
        private int $level,
    ) {  
    }

    public function getStrings(): array
    {
        return [$this->getLeadingString() . '[' . PHP_EOL];
    }

    public function next(): ?State
    {
        return new ElementStartState(
            $this->node->getChildren()[0],
            $this->level + 1,
        );
    }
}
