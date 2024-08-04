<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\InputTree\Node;
use App\Domain\InputTree\Tree;
use App\Service\CsvDataFileReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InputTreeTest extends KernelTestCase
{
    public function testLoadTree(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $reader = $this->createMock(CsvDataFileReader::class);
        $reader->expects(self::once())
            ->method('readData')
            ->willReturn([
                '"Item Name";"Type";"Parent";"Relation"',
                '"Total";"Изделия и компоненты";;',
                '"ПВЛ";"Изделия и компоненты";"Total";',
            ]);

        $container->set(CsvDataFileReader::class, $reader);

        $tree = $container->get(Tree::class);
        $tree->loadTree('');

        $nodes = $tree->getNodes();

        $this->assertIsArray($nodes);
        $this->assertCount(3, $nodes);

        $this->assertInstanceOf(Node::class, $nodes['Item Name']);
        $this->assertIsString($nodes['Item Name']->getItemName());
        $this->assertEquals('Item Name', $nodes['Item Name']->getItemName());
        $this->assertIsString($nodes['Item Name']->getType());
        $this->assertEquals('Type', $nodes['Item Name']->getType());
        $this->assertIsString($nodes['Item Name']->getParent());
        $this->assertEquals('Parent', $nodes['Item Name']->getParent());
        $this->assertIsString($nodes['Item Name']->getRelation());
        $this->assertEquals('Relation', $nodes['Item Name']->getRelation());

        $this->assertInstanceOf(Node::class, $nodes['Total']);
        $this->assertIsString($nodes['Total']->getItemName());
        $this->assertEquals('Total', $nodes['Total']->getItemName());
        $this->assertIsString($nodes['Total']->getType());
        $this->assertEquals('Изделия и компоненты', $nodes['Total']->getType());
        $this->assertIsString($nodes['Total']->getParent());
        $this->assertEmpty($nodes['Total']->getParent());
        $this->assertIsString($nodes['Total']->getRelation());
        $this->assertEmpty($nodes['Total']->getRelation());

        $this->assertInstanceOf(Node::class, $nodes['ПВЛ']);
        $this->assertIsString($nodes['ПВЛ']->getItemName());
        $this->assertEquals('ПВЛ', $nodes['ПВЛ']->getItemName());
        $this->assertIsString($nodes['ПВЛ']->getType());
        $this->assertEquals('Изделия и компоненты', $nodes['ПВЛ']->getType());
        $this->assertIsString($nodes['ПВЛ']->getParent());
        $this->assertEquals('Total', $nodes['ПВЛ']->getParent());
        $this->assertIsString($nodes['ПВЛ']->getRelation());
        $this->assertEmpty($nodes['ПВЛ']->getRelation());
    }
}
