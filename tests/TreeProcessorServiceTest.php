<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\InputTree\Node;
use App\Domain\InputTree\Tree as InputTree;
use App\Service\TreeProcessorService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TreeProcessorServiceTest extends KernelTestCase
{
    private const string ARTICLES_AND_COMPONENTS = 'Изделия и компоненты';
    private const string DIRECT_COMPONENTS = 'Прямые компоненты';
    private const string VARIANTS = 'Варианты комплектации';

    public function testProcessTree():void
    {
        $processor = new TreeProcessorService();

        $inputTree = new InputTree();
        
        $inputTree->addNode(new Node(
            'Total',
            self::ARTICLES_AND_COMPONENTS,
        ));

        $inputTree->addNode(new Node(
            'ПВЛ',
            self::ARTICLES_AND_COMPONENTS,
            'Total',
        ));

        $inputTree->addNode(new Node(
            'Стандарт.#1',
            self::VARIANTS,
            'ПВЛ',
        ));

        $inputTree->addNode(new Node(
            'Тележка Б25.#2',
            self::DIRECT_COMPONENTS,
            'Стандарт.#1',
            'Тележка Б25',
        ));

        $inputTree->addNode(new Node(
            'Тележка Б25',
            self::ARTICLES_AND_COMPONENTS,
            'Total',
        ));

        $inputTree->addNode(new Node(
            'Стандарт.#5',
            self::VARIANTS,
            'Тележка Б25',
        ));

        $inputTree->addNode(new Node(
            'РБ ЦДЛР.9855.00.02.000.#17',
            self::DIRECT_COMPONENTS,
            'Стандарт.#5',
        ));

        $inputTree->addNode(new Node(
            'РБ ЦДЛР.9855.00.02.000.#18',
            self::DIRECT_COMPONENTS,
            'Стандарт.#5',
        ));

        $inputTree->addNode(new Node(
            'Колесная пара 25 т.#19',
            self::DIRECT_COMPONENTS,
            'Стандарт.#5',
            'Колесная пара 25 т',
        ));

        $inputTree->addNode(new Node(
            'Колесная пара 25 т',
            self::ARTICLES_AND_COMPONENTS,
            'Total',
        ));

        $inputTree->addNode(new Node(
            'Стандарт.#10',
            self::VARIANTS,
            'Колесная пара 25 т',
        ));

        $inputTree->addNode(new Node(
            'Колесо 25т.#52',
            self::DIRECT_COMPONENTS,
            'Стандарт.#10',
        ));

        $inputTree->addNode(new Node(
            'Ось 25т.#53',
            self::DIRECT_COMPONENTS,
            'Стандарт.#10',
        ));

        $outputTree = $processor->processTree($inputTree);

        $this->assertSame(13, $outputTree->getSize());

        $node = $outputTree->getNode('Total');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(3, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('ПВЛ', $child->getItemName());
        $child = $children[1];
        $this->assertNotNull($child);
        $this->assertEquals('Тележка Б25', $child->getItemName());
        $child = $children[2];
        $this->assertNotNull($child);
        $this->assertEquals('Колесная пара 25 т', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNull($parent);

        $node = $outputTree->getNode('ПВЛ');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Стандарт.#1', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Total', $parent->getItemName());

        $node = $outputTree->getNode('Стандарт.#1');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Тележка Б25.#2', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('ПВЛ', $parent->getItemName());

        $node = $outputTree->getNode('Тележка Б25.#2');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Стандарт.#5', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#1', $parent->getItemName());

        $node = $outputTree->getNode('Тележка Б25');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Стандарт.#5', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Total', $parent->getItemName());

        $node = $outputTree->getNode('Стандарт.#5');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(3, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('РБ ЦДЛР.9855.00.02.000.#17', $child->getItemName());
        $child = $children[1];
        $this->assertNotNull($child);
        $this->assertEquals('РБ ЦДЛР.9855.00.02.000.#18', $child->getItemName());
        $child = $children[2];
        $this->assertNotNull($child);
        $this->assertEquals('Колесная пара 25 т.#19', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Тележка Б25', $parent->getItemName());

        $node = $outputTree->getNode('РБ ЦДЛР.9855.00.02.000.#17');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(0, $children);
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#5', $parent->getItemName());

        $node = $outputTree->getNode('РБ ЦДЛР.9855.00.02.000.#18');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(0, $children);
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#5', $parent->getItemName());

        $node = $outputTree->getNode('Колесная пара 25 т.#19');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Стандарт.#10', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#5', $parent->getItemName());

        $node = $outputTree->getNode('Колесная пара 25 т');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(1, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Стандарт.#10', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Total', $parent->getItemName());

        $node = $outputTree->getNode('Стандарт.#10');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(2, $children);
        $child = $children[0];
        $this->assertNotNull($child);
        $this->assertEquals('Колесо 25т.#52', $child->getItemName());
        $child = $children[1];
        $this->assertNotNull($child);
        $this->assertEquals('Ось 25т.#53', $child->getItemName());
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Колесная пара 25 т', $parent->getItemName());

        $node = $outputTree->getNode('Колесо 25т.#52');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(0, $children);
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#10', $parent->getItemName());

        $node = $outputTree->getNode('Ось 25т.#53');
        $this->assertNotNull($node);
        $children = $node->getChildren();
        $this->assertCount(0, $children);
        $parent = $node->getParent();
        $this->assertNotNull($parent);
        $this->assertEquals('Стандарт.#10', $parent->getItemName());
    }
}
