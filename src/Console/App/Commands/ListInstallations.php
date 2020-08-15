<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class ListInstallations extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('magento:list')
            ->setDescription('See Installed Magento 2 versions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!is_array($this->config)) {
            $this->getApplication()->find('copy:config')->run(new ArrayInput([]), $output);
            return 1;
        }
        $output->writeln(
            sprintf('<info>Available Magento installations:</info>')
        );
        $this->runCommand("cd {$this->config['installation_root']} && ls | cat -n");

        return Self::SUCCESS;
    }
}
