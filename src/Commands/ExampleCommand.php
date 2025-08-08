<?php declare(strict_types=1);

namespace SwagExamplePlugin\Commands;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Bundle\FrameworkBundle\Console\Application as SymfonyApplication;
use Symfony\Component\HttpKernel\KernelInterface;

class ExampleCommand extends Command
{
    private Kernel $kernel;

    public function __construct(Kernel $kernel, ?string $name = null)
    {
        parent::__construct($name);
        $this->kernel = $kernel;

    }

    protected function configure()
    {
        $this->setName('example')->setDescription('Example command');
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = new SymfonyApplication($this->kernel);
        $application->setAutoExit(false); // prevent it from killing this command

        $commands = [
            ['command' => 'cache:clear'],
            ['command' => 'plugin:refresh'],
        ];

        foreach ($commands as $cmdInput) {
            $output->writeln('Running: <info>' . $cmdInput['command'] . '</info>');
            $application->run(new ArrayInput($cmdInput), $output);
        }

        return Command::SUCCESS;
    }

}
