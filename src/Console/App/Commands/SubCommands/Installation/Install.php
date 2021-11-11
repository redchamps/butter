<?php
namespace Console\App\Commands\SubCommands\Installation;

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
        $dbName = $_SERVER['db_name'];
        $baseUrl = str_replace("<version>", $input->getArgument('installation-name'), $this->config['base_url']);

        $directory = $this->getInstallationRoot($input).$input->getArgument('installation-name');
        $this->getApplication()->find('pre:install')->run($input, $output);
        $output->writeln(sprintf('<info>-> Initiating Installation</info>'));
        $installationOptions = $this->config['installation_options'];
        $command = "cd $directory && {$this->phpBin} bin/magento setup:install --backend-frontname='{$installationOptions['frontname']}' --session-save='files' --db-host='{$this->config['db']['connection']['host']}' --db-name='$dbName' --db-user='{$this->config['db']['connection']['username']}' --db-password='{$this->config['db']['connection']['password']}' --base-url='$baseUrl' --admin-user='{$installationOptions['admin-username']}' --admin-password='{$installationOptions['admin-password']}' --admin-email='{$installationOptions['admin-email']}' --admin-firstname='{$installationOptions['admin-firstname']}' --admin-lastname='{$installationOptions['admin-lastname']}' 2>&1";
        $this->runCommand($command);
        $this->getApplication()->find('post:install')->run($input, $output);
        $output->writeln(sprintf('<info>Frontend: %s</info>', $baseUrl));
        $output->writeln(sprintf('<info>Backend: %s</info>', $baseUrl."/admin"));
        $output->writeln(sprintf('<info>Directory Path: %s</info>', $directory));
        return Self::SUCCESS;
    }
}
