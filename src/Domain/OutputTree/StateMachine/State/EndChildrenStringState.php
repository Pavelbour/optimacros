<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class EndChildrenStringState implements State
{
    public function __construct(
        private Node $node,
        private int $level,
    ) {
    }

    public function getStrings(): array
    {
        return [PHP_EOL];
    }

    public function next(): ?State
    {
        return new ElementStartState(
            $this->node->getChildren()[0],
            $this->level + 1,
        );
    }
}
