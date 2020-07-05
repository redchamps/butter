<?php
namespace Console\App\Commands\SubCommands;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class Install extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('install')
        ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>->Installing</info>'));
        $dbName = str_replace("<version>", $input->getArgument('version'), $this->config['db']['connection']['dbname']);
        $baseUrl = str_replace("<version>", $input->getArgument('installation-name'), $this->config['base_url']);

        $directory = $this->config['installation_root'].$input->getArgument('installation-name');
        if ($input->getArgument("sample-data")) {
            $output->writeln(sprintf('<info>->->Installing sample data</info>'));
            $this->runCommand("cd $directory && php bin/magento sampledata:deploy");
        }
        $command = "cd $directory && php bin/magento setup:install --backend-frontname='admin' --session-save='files' --db-host='{$this->config['db']['connection']['host']}' --db-name='$dbName' --db-user='{$this->config['db']['connection']['username']}' --db-password='{$this->config['db']['connection']['password']}' --base-url='$baseUrl' --admin-user='admin' --admin-password='admin123' --admin-email='rav@redchamps.com' --admin-firstname='Rav' --admin-lastname='RedChamps' 2>&1";
        $this->runCommand($command);
        $this->runCommand("cd $directory && php bin/magento config:set web/seo/use_rewrites 1");
        $output->writeln(sprintf('<comment>Frontend: %s</comment>', $baseUrl));
        $output->writeln(sprintf('<comment>Backend: %s</comment>', $baseUrl."/admin"));
        return Command::SUCCESS;
    }
}
