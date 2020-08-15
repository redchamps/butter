<?php
namespace Console\App\Commands\SubCommands\Installation;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PreInstall extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('pre:install')
        ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Running Pre-Install Commands</info>'));
        $directory = $this->config['installation_root'].$input->getArgument('installation-name');
        if(isset($this->config['extra_extensions']) && $this->config['extra_extensions']) {
            $output->writeln(sprintf('<info>->-> Installing Extra Extensions</info>'));
            foreach ($this->config['extra_extensions'] as $extension) {
                $output->writeln(sprintf("<info>->->-> Installing Extension $extension</info>"));
                system("cd $directory && composer require $extension");
            }
        }
        if ($input->getOption("sample-data") == "y") {
            $output->writeln(sprintf('<info>->-> Installing sample data</info>'));
            system("cd $directory && php bin/magento sampledata:deploy");
        }
        return Self::SUCCESS;
    }
}
