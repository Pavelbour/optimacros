<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class ElementEndState implements State
{
    use BaseState;

    public function __construct(
        private Node $node,
        private int $level,
    ) {
    }

    public function getStrings(): array
    {
        return [ $this->getLeadingString() . '},' . PHP_EOL];
    }

    public function next(): ?State
    {
        return new ElementStartState($this->node->getNextSibling(), $this->level);
    }
}
