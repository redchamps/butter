<?php
namespace Console\App\Commands\SubCommands\Filesystem;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteFiles extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('delete:files')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            sprintf('<info>-> Deleting Filesystem</info>')
        );
        $this->runCommand(
            "cd {$this->config['installation_root']} && rm -rf {$input->getArgument('installation-name')}"
        );
        return Self::SUCCESS;
    }
}
