<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\InputTree\Tree;
use App\Domain\InputTree\TreeLoader;
use App\Domain\OutputTree\TreeWriter;
use App\Service\TreeProcessorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'gentree:generate-json')]
class GenerateJsonCommand extends Command
{
    private const string DEFAULT_INPUT_FILE = '/app/data/input.csv';
    private const string DEFAULT_OUTPUT_FILE = '/app/data/output.json';

    private Tree $tree;
    
    public function __construct(
        private TreeLoader $loader,
        private TreeProcessorService $processor,
        private TreeWriter $writer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'input-file',
            'i',
            InputOption::VALUE_REQUIRED,
        );

        $this->addOption(
            'output-file',
            'o',
            InputOption::VALUE_REQUIRED,
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $inputFile = $input->getOption('input-file') ?? self::DEFAULT_INPUT_FILE;
    
        $this->tree = $this->loader->loadTree($inputFile);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputTree = $this->loader->loadTree($input->getOption('input-file') ?? self::DEFAULT_INPUT_FILE);
        $outputTree = $this->processor->processTree($inputTree);
        $this->writer->writeTree($outputTree, $input->getOption('output-file' ?? self::DEFAULT_OUTPUT_FILE));
        return Command::SUCCESS;
    }
}
