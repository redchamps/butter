<?php
namespace Console\App\Commands\SubCommands\Installation;

use Console\App\Commands\AbstractCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class PostInstall extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('post:install')
        ->addArgument('installation-name', InputArgument::REQUIRED, 'Installation Name.');
        $this->setHidden(true);
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>-> Running Post-Install Commands</info>'));
        $directory = $this->config['installation_root'].$input->getArgument('installation-name');
        $output->writeln(sprintf('<info>->-> Saving url rewrites config</info>'));
        $this->runCommand("cd $directory && php bin/magento config:set web/seo/use_rewrites 1");
        if(version_compare($input->getArgument('version'), '2.4.0', 'ge')) {
            $output->writeln(sprintf('<info>->-> Disabling Two-Factor Auth Module</info>'));
            $this->runCommand("cd $directory && php bin/magento module:disable Magento_TwoFactorAuth");
        }
        if ($this->config['generate_performance_profile'] != "n" || $input->getOption("generate-profile") != "n") {
            $output->writeln(sprintf('<info>->-> Generating performance profile</info>'));
            $edition = $input->getOption('edition') == "enterprise"?"ee":"ce";
            $profile = $input->getOption("generate-profile")?$input->getOption("generate-profile"):"small";
            if($input->getOption("generate-profile") == "n") {
                $profile = $this->config['generate_performance_profile'];
            }
            $command = "cd $directory && php bin/magento setup:performance:generate-fixtures setup/performance-toolkit/profiles/$edition/$profile.xml";
            $this->runCommand($command);
        }
        if(isset($this->config['post_install_commands']) && $this->config['post_install_commands']) {
            foreach ($this->config['post_install_commands'] as $command) {
                $output->writeln(sprintf('<info>->-> Running command %s</info>', $command));
                system("cd $directory && $command");
            }
        }
        return Self::SUCCESS;
    }
}
