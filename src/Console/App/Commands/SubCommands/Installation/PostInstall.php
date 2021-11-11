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
        $installationName = $input->getArgument('installation-name');
        $directory = $this->getInstallationRoot($input).$installationName;
        $output->writeln(sprintf('<info>->-> Saving url rewrites config</info>'));
        $this->runCommand("cd $directory && {$this->phpBin} bin/magento config:set web/seo/use_rewrites 1");
        if(version_compare($input->getArgument('version'), '2.4.0', 'ge')) {
            $output->writeln(sprintf('<info>->-> Disabling Two-Factor Auth Module</info>'));
            $this->runCommand("cd $directory && {$this->phpBin} bin/magento module:disable Magento_TwoFactorAuth");
        }
        if ($this->config['generate_performance_profile'] != "n" || $input->getOption("generate-profile") != "n") {
            $output->writeln(sprintf('<info>->-> Generating performance profile</info>'));
            $edition = $input->getOption('edition') == "enterprise"?"ee":"ce";
            $profile = $input->getOption("generate-profile")?$input->getOption("generate-profile"):"small";
            if($input->getOption("generate-profile") == "n") {
                $profile = $this->config['generate_performance_profile'];
            }
            if ($profile == "custom") {
                $this->runCommand("cp {$this->root}/../etc/custom.xml {$directory}/setup/performance-toolkit/profiles/$edition/");
            }
            $command = "cd $directory && {$this->phpBin} bin/magento setup:performance:generate-fixtures setup/performance-toolkit/profiles/$edition/$profile.xml";
            $this->runCommand($command);
        }
        if ($input->getOption("post-install-commands") != "null") {
            $customCommands = explode(",", $input->getOption("post-install-commands"));
            $this->config['post_install_commands'] = isset($this->config['post_install_commands'])?array_merge($this->config['post_install_commands'], $customCommands):$customCommands;
        }
        if(isset($this->config['post_install_commands']) && $this->config['post_install_commands']) {
            foreach ($this->config['post_install_commands'] as $command) {
                $command = str_replace("<installation-name>", $installationName, $command);
                $command = str_replace("<time-taken>", $this->getTimeTaken(), $command);
                $output->writeln(sprintf('<info>->-> Running command %s</info>', $command));
                system("cd $directory && $command");
            }
        }
        return Self::SUCCESS;
    }
}
