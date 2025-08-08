<?php declare(strict_types=1);

namespace SwagExamplePlugin\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class InstallPluginCommand extends Command
{

    protected function configure(): void
    {
        $this->setName('install');
        $this
            ->setDescription('Installs and activates a plugin by name (provided at runtime)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Ask for plugin name
        $helper = $this->getHelper('question');
        $question = new Question('Enter the plugin name to install: ');
        $pluginName = $helper->ask($input, $output, $question);

        if (!$pluginName) {
            $output->writeln('<error>No plugin name provided. Aborting.</error>');
            return Command::FAILURE;
        }

        // Run the plugin:install command
        $output->writeln("<info>Installing and activating plugin: $pluginName</info>");
        $process = new Process(['bin/console', 'plugin:install', $pluginName, '--activate']);
        $process->setTimeout(60);
        $process->run(function ($type, $buffer) use ($output) {
            $output->write($buffer);
        });

        // Check if it succeeded
        if (!$process->isSuccessful()) {
            $output->writeln('<error>Plugin installation failed.</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
