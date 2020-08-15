<?php
namespace Console\App\Commands\SubCommands;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class RestartServer extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('restart:server')
            ->addArgument('domain', InputArgument::REQUIRED, 'Domain Name.');
        parent::configure();
        $this->addArgument('installation-name', InputArgument::OPTIONAL, 'Installation Name.');
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Restarting nginx</info>'));
        system($this->config['vhost']['server_restart_command']);
        return Self::SUCCESS;
    }
}
