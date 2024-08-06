<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

class ArrayEndState implements State
{
    use BaseState;

    public function __construct(
        private Node $node,
        private int $level,
    ) {
    }

    public function getStrings(): array
    {
        $result = $this->getLeadingString() . ']';
        $result = $this->level > 0 ? $result . PHP_EOL : $result;
        return [$result];
    }

    public function next(): ?State
    {
        return $this->level > 0
            ? $this->getNextState()
            : null;
    }

    private function getNextState(): State
    {
        $level = $this->level - 1;
        return $this->isLastElement()
            ? new LastElementEndState($this->node->getParent() ?? $this->node, $level)
            // : new ElementEndState($this->node->getNextSibling(), $level);
            : new ElementEndState($this->node->getParent()->getNextSibling(), $level);
    }

    private function isLastElement(): bool
    {
        return $this->node->getParent() == null
            || $this->node->getParent()->isLastSibling();
    }
}
