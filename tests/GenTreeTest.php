<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenTreeTest extends KernelTestCase
{
    public function testGenTree(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('gentree:generate-json');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '-i' => '/app/data/input.csv',
            '-o' => '/app/data/gentree-test.json'
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
