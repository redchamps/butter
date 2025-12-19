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
        $installationName = $input->getArgument('installation-name');
        $directory = $this->getInstallationRoot($input).$installationName;
        if ($input->getOption("pre-install-commands") != "null") {
            $customCommands = explode(",", $input->getOption("pre-install-commands"));
            $this->config['pre_install_commands'] = isset($this->config['pre_install_commands'])?array_merge($this->config['pre_install_commands'], $customCommands):$customCommands;
        }
        if(isset($this->config['pre_install_commands']) && $this->config['pre_install_commands']) {
            foreach ($this->config['pre_install_commands'] as $command) {
                $command = str_replace("<installation-name>",$installationName, $command);
                $output->writeln(sprintf('<info>->->-> Running command %s</info>', $command));
                $this->runCommand("cd $directory && $command");
            }
        }
        if(isset($this->config['extra_extensions']) && $this->config['extra_extensions']) {
            $output->writeln(sprintf('<info>->-> Installing Extra Extensions</info>'));
            foreach ($this->config['extra_extensions'] as $extension) {
                $output->writeln(sprintf("<info>->->-> Installing Extension $extension</info>"));
                system("cd $directory && {$this->composerBin} require $extension");
            }
        }
        if((version_compare($input->getArgument('version'), '2.4.0', 'ge') || $input->getOption("edition") == 'mage-os') &&
            isset($this->config['mysql_legacy']) &&
            $this->config['mysql_legacy'] == 'y'
        ) {
            $output->writeln(sprintf('<info>->-> Installing Mysql Legacy Extension</info>'));
            system("cd $directory && {$this->composerBin} require swissup/module-search-mysql-legacy");
        }
        if ($input->getOption("sample-data") == "y") {
            $output->writeln(sprintf('<info>->-> Installing sample data</info>'));
            system("cd $directory && {$this->phpBin} bin/magento sampledata:deploy");
        }
        return Self::SUCCESS;
    }
}
