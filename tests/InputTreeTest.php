<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\InputTree\Node;
use App\Domain\InputTree\TreeLoader;
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
                '"Total";"Изделия и компоненты";;',
                '"ПВЛ";"Изделия и компоненты";"Total";',
                '"Стандарт.#1";"Варианты комплектации";"ПВЛ";',
                '"Тележка Б25.#2";"Прямые компоненты";"Стандарт.#1";"Тележка Б25"'
            ]);

        $container->set(CsvDataFileReader::class, $reader);

        $loader = $container->get(TreeLoader::class);
        $tree = $loader->loadTree('');

        $this->assertSame(4, $tree->getSize());

        $node = $tree->getNode('Total');
        $this->assertNotNull($node);
        $this->assertIsString($node->getItemName());
        $this->assertEquals('Total', $node->getItemName());
        $this->assertIsString($node->getType());
        $this->assertEquals('Изделия и компоненты', $node->getType());
        $this->assertIsString($node->getParent());
        $this->assertEmpty($node->getParent());
        $this->assertIsString($node->getRelation());
        $this->assertEmpty($node->getRelation());

        $node = $tree->getNode('ПВЛ');
        $this->assertNotNull($node);
        $this->assertIsString($node->getItemName());
        $this->assertEquals('ПВЛ', $node->getItemName());
        $this->assertIsString($node->getType());
        $this->assertEquals('Изделия и компоненты', $node->getType());
        $this->assertIsString($node->getParent());
        $this->assertEquals('Total', $node->getParent());
        $this->assertIsString($node->getRelation());
        $this->assertEmpty($node->getRelation());

        $node = $tree->getNode('Стандарт.#1');
        $this->assertNotNull($node);
        $this->assertIsString($node->getItemName());
        $this->assertEquals('Стандарт.#1', $node->getItemName());
        $this->assertIsString($node->getType());
        $this->assertEquals('Варианты комплектации', $node->getType());
        $this->assertIsString($node->getParent());
        $this->assertEquals('ПВЛ', $node->getParent());
        $this->assertIsString($node->getRelation());
        $this->assertEmpty($node->getRelation());

        $node = $tree->getNode('Тележка Б25.#2');
        $this->assertNotNull($node);
        $this->assertIsString($node->getItemName());
        $this->assertEquals('Тележка Б25.#2', $node->getItemName());
        $this->assertIsString($node->getType());
        $this->assertEquals('Прямые компоненты', $node->getType());
        $this->assertIsString($node->getParent());
        $this->assertEquals('Стандарт.#1', $node->getParent());
        $this->assertIsString($node->getRelation());
        $this->assertEquals('Тележка Б25', $node->getRelation());

        $this->assertNull($tree->getNode('NotItemName'));

        $index = $tree->getIndex();
        $articleComponentIndex = $index['articleComponentIndex'];
        $directComponentIndex = $index['directComponentIndex'];
        $variantIndex = $index['variantIndex'];

        $this->assertCount(2, $articleComponentIndex);

        $node = $articleComponentIndex[0];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('Total', $node->getItemName());

        $node = $articleComponentIndex[1];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('ПВЛ', $node->getItemName());
                
        $this->assertCount(1, $directComponentIndex);

        $node = $directComponentIndex[0];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('Тележка Б25.#2', $node->getItemName());

        $this->assertCount(1, $variantIndex);

        $node = $variantIndex[0];
        $this->assertInstanceOf(Node::class, $node);
        $this->assertEquals('Стандарт.#1', $node->getItemName());
    }
}
