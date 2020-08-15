<?php
namespace Console\App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class MagentoInstall extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('magento:install');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if(!is_array($this->config)) {
            $this->getApplication()->find('copy:config')->run(new ArrayInput([]), $output);
            return 1;
        }
        $version = $input->getArgument('version');
        $output->writeln(
            sprintf('<info>Installing Magento %s version %s</info>',
                $input->getOption('edition'), $version
            )
        );
        $installationName = str_replace([".", "-"], ["", ""], $version);
        $arguments = $input->getArguments();
        foreach ($input->getOptions() as $name => $value) {
            $arguments["--".$name] = $value;
        }
        $arguments['installation-name'] = $input->getOption("edition") == "enterprise"?$installationName."-ee":$installationName;
        $processedArguments = new ArrayInput($arguments);

        $this->getApplication()->find('place:files')->run($processedArguments, $output);
        $this->getApplication()->find('create:database')->run($processedArguments, $output);
        $this->getApplication()->find('install')->run($processedArguments, $output);
        if($this->vhostEnabled()) {
            $arguments['domain'] = str_replace(
                ["<version>", "http://",  "https://"],
                [$installationName, "", ""],
                rtrim($this->config['base_url'], "/")
            );
            $processedArguments = new ArrayInput($arguments);
            $this->getApplication()->find('create:vhost')->run($processedArguments, $output);
            $this->getApplication()->find('restart:server')->run($processedArguments, $output);
        }
        return Self::SUCCESS;
    }
}
