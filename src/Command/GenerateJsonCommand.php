<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\InputTree\Tree;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'gentree:generate-json')]
class GenerateJsonCommand extends Command
{
    private const string DEFAULT_INPUT_FILE = '/app/data/input.csv';
    
    public function __construct(
        private Tree $tree,
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
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $inputFile = $input->getOption('input-file') ?? self::DEFAULT_INPUT_FILE;
        
        if (!$inputFile) {
            $inputFile = self::DEFAULT_INPUT_FILE;
        }
        
        $this->tree->loadTree($inputFile);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
