<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

interface State
{
    public function getStrings(): array;
    public function next(): ?State;
}
