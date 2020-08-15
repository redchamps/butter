<?php
namespace Console\App\Commands\SubCommands\Vhost;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class DeleteVhost extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('delete:vhost')
            ->addArgument('domain', InputArgument::REQUIRED, 'Domain Name.');
        parent::configure();
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Deleting Virtual Host %s</info>', $input->getArgument('domain')));
        $this->runCommand(
            "rm {$this->config['vhost']['nginx_path']}sites-available/{$input->getArgument('domain')}"
        );
        $this->runCommand(
            "rm {$this->config['vhost']['nginx_path']}sites-enabled/{$input->getArgument('domain')}"
        );
        return Self::SUCCESS;
    }
}
