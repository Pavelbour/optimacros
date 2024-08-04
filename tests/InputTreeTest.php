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

        $nodes = $tree->getNodes();

        $this->assertIsArray($nodes);
        $this->assertCount(4, $nodes);

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

        $this->assertInstanceOf(Node::class, $nodes['Стандарт.#1']);
        $this->assertIsString($nodes['Стандарт.#1']->getItemName());
        $this->assertEquals('Стандарт.#1', $nodes['Стандарт.#1']->getItemName());
        $this->assertIsString($nodes['Стандарт.#1']->getType());
        $this->assertEquals('Варианты комплектации', $nodes['Стандарт.#1']->getType());
        $this->assertIsString($nodes['Стандарт.#1']->getParent());
        $this->assertEquals('ПВЛ', $nodes['Стандарт.#1']->getParent());
        $this->assertIsString($nodes['Стандарт.#1']->getRelation());
        $this->assertEmpty($nodes['Стандарт.#1']->getRelation());

        $this->assertInstanceOf(Node::class, $nodes['Тележка Б25.#2']);
        $this->assertIsString($nodes['Тележка Б25.#2']->getItemName());
        $this->assertEquals('Тележка Б25.#2', $nodes['Тележка Б25.#2']->getItemName());
        $this->assertIsString($nodes['Тележка Б25.#2']->getType());
        $this->assertEquals('Прямые компоненты', $nodes['Тележка Б25.#2']->getType());
        $this->assertIsString($nodes['Тележка Б25.#2']->getParent());
        $this->assertEquals('Стандарт.#1', $nodes['Тележка Б25.#2']->getParent());
        $this->assertIsString($nodes['Тележка Б25.#2']->getRelation());
        $this->assertEquals('Тележка Б25', $nodes['Тележка Б25.#2']->getRelation());

        $index = $tree->getIndex();
        $articleComponentIndex = $index['articleComponentIndex'];
        $directComponentIndex = $index['directComponentIndex'];
        $variantIndex = $index['variantIndex'];

        $this->assertCount(2, $articleComponentIndex);
        $this->assertContains('Total', $articleComponentIndex);
        $this->assertContains('ПВЛ', $articleComponentIndex);
        
        $this->assertCount(1, $directComponentIndex);
        $this->assertContains('Тележка Б25.#2', $directComponentIndex);

        $this->assertCount(1, $variantIndex);
        $this->assertContains('Стандарт.#1', $variantIndex);
    }
}
