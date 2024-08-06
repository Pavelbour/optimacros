<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\InputTree\Node;
use App\Domain\OutputTree\StateMachine\StateMachine;
use App\Domain\InputTree\Tree as InputTree;
use App\Domain\OutputTree\TreeWriter;
use App\Service\TreeProcessorService;
use App\Tests\Utils\CompareFiles;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TreeWriterTest extends KernelTestCase
{
    use CompareFiles;

    private const string ARTICLES_AND_COMPONENTS = 'Изделия и компоненты';
    private const string DIRECT_COMPONENTS = 'Прямые компоненты';
    private const string VARIANTS = 'Варианты комплектации';

    private const string EXAMPLE_FILE_NAME = '/app/data/test-example.json';

    public function testWriteTree(): void
    {
        $fileName = '/app/data/output-test.json';
        $stateMachine = new StateMachine();
        $writer = new TreeWriter($stateMachine);

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

        $tree = $processor->processTree($inputTree);
        

        $writer->writeTree($tree, $fileName);

        $this->assertFileExists($fileName);

        $this->compare(self::EXAMPLE_FILE_NAME, $fileName);
    }
}
