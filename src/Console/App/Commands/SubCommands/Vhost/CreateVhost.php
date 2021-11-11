<?php
namespace Console\App\Commands\SubCommands\Vhost;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateVhost extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('create:vhost')
            ->addArgument('domain', InputArgument::REQUIRED, 'Domain Name.')
            ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Creating Virtual Host %s</info>', $input->getArgument('domain')));
        $vhostData = str_replace(
            [
                '<installation-path>',
                '<domain>'
            ],
            [
                $this->getInstallationRoot($input).$input->getArgument('installation-name'),
                $input->getArgument('domain')
            ],
            $this->config['vhost']['vhost_config']
        );
        $this->runCommand(
            "echo '$vhostData' > {$this->config['vhost']['nginx_path']}sites-available/{$input->getArgument('domain')}"
        );
        $output->writeln(sprintf('<info>-> Activating Virtual Host %s</info>', $input->getArgument('domain')));
        $this->runCommand(
            "ln -s {$this->config['vhost']['nginx_path']}sites-available/{$input->getArgument('domain')} {$this->config['vhost']['nginx_path']}sites-enabled/"
        );
        return Self::SUCCESS;
    }
}
