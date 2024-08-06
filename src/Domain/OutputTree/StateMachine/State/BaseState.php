<?php

declare(strict_types=1);

namespace App\Domain\OutputTree\StateMachine\State;

use App\Domain\OutputTree\Node;

trait BaseState
{
    private const int LEADING_SPACE_NUMBER = 2;

    public function generateStrings(): array
    {
        $itemNameString = sprintf('"itemName": "%s",', $this->node->getItemName()) . PHP_EOL;

        $parentName = $this->node->getParent()->getItemName();
        $parentNameString = sprintf(
                '"parent": %s,',
                $parentName ? sprintf('"%s"', $parentName) : 'null'
        ) . PHP_EOL;

        $childrenString = '"children": [';

        return $this->generateArray(
            $this->level,
            $itemNameString,
            $parentNameString,
            $childrenString,
        );
    }

    private function generateArray(int $level, ...$strings): array
    {
        $result = [];
        foreach ($strings as $string) {
            $result[] = $this->getLeadingString($level) . $string;
        }

        return $result;
    }

    public function getLeadingString(): string
    {
        $result = '';
        for ($i=0; $i < $this->level * self::LEADING_SPACE_NUMBER; $i++) { 
            $result .= ' '; 
        }

        return $result;
    }
}
