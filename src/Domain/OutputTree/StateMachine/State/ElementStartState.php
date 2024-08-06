<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class ElementStartState implements State
{
    use BaseState;

    public function __construct(
        private Node $node,
        private int $level,
    ) {  
    }

    public function getStrings(): array
    {
        return [ $this->getLeadingString() . '{' . PHP_EOL];
    }

    public function next(): ?State
    {
        return new ElementBodyState(
            $this->node,
            $this->level + 1,
        );
    }
}
